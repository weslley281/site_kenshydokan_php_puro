<?php
// controllers/stripeWebhookController.php

// Define headers para JSON e evita cache
header('Content-Type: application/json');
header('Cache-Control: no-cache');

// Este endpoint é acessado externamente pelo Stripe, portanto não requer autenticação de sessão
include_once __DIR__ . "/../db/conexao.php";
include_once __DIR__ . "/../models/dojoMensalidadeModel.php";
include_once __DIR__ . "/../models/dojoFinanceiroModel.php";
include_once __DIR__ . "/../models/filiadoModel.php";
include_once __DIR__ . "/../models/dojoRecorrenciaModel.php";

$payload = @file_get_contents('php://input');
$event = null;

try {
    $event = json_decode($payload, true);
} catch (Exception $e) {
    error_log("Erro ao parsear payload do Webhook: " . $e->getMessage());
    http_response_code(400);
    echo json_encode(["error" => "Invalid payload"]);
    exit();
}

if ($event && isset($event['type'])) {
    $eventType = $event['type'];
    $eventData = $event['data']['object'];

    $mensalidadeModel = new DojoMensalidadeModel();
    $financeiroModel = new DojoFinanceiroModel();
    $filiadoModelRepo = new FiliadoModel();
    $recorrenciaModel = new DojoRecorrenciaModel();

    if ($eventType === 'checkout.session.completed') {
        // Recupera os metadados configurados na sessão de checkout
        $id_filiado = isset($eventData['metadata']['id_filiado']) ? intval($eventData['metadata']['id_filiado']) : null;
        $id_mensalidade = isset($eventData['metadata']['id_mensalidade']) ? intval($eventData['metadata']['id_mensalidade']) : null;
        $id_recorrencia = isset($eventData['metadata']['id_recorrencia']) ? intval($eventData['metadata']['id_recorrencia']) : null;
        $valor = isset($eventData['amount_total']) ? floatval($eventData['amount_total'] / 100) : 0;
        $descricao = isset($eventData['metadata']['descricao']) ? $eventData['metadata']['descricao'] : "Mensalidade do Dojô";

        if ($id_filiado && $valor > 0) {
            // Se for do tipo Assinatura Recorrente, grava os IDs do Stripe
            if (isset($eventData['mode']) && $eventData['mode'] === 'subscription') {
                $customer_id = $eventData['customer'] ?? null;
                $subscription_id = $eventData['subscription'] ?? null;
                if ($customer_id && $subscription_id) {
                    if ($id_recorrencia) {
                        $recorrenciaModel->ativarRecorrenciaStripe($id_recorrencia, $customer_id, $subscription_id);
                    } else {
                        $rec = $recorrenciaModel->buscarAtivaOuPendentePorFiliado($id_filiado);
                        if ($rec) {
                            $recorrenciaModel->ativarRecorrenciaStripe($rec['id'], $customer_id, $subscription_id);
                        }
                    }
                }
            }

            // Realiza a conciliação do pagamento (marca mensalidade correspondente como paga)
            $filiado_obj = $filiadoModelRepo->buscarFiliadoPorId($id_filiado);
            $nome_filiado = $filiado_obj ? $filiado_obj->getNome() : 'Aluno';
            $data_pagamento = date("Y-m-d");
            $mensalidade_paga = null;

            if ($id_mensalidade) {
                $m = $mensalidadeModel->buscarMensalidadePorId($id_mensalidade);
                if ($m && $m['status_pagamento'] !== 'pago') {
                    if ($mensalidadeModel->receberMensalidade($id_mensalidade, $data_pagamento)) {
                        $mensalidade_paga = $m;
                    }
                }
            } else {
                $mensalidades_aluno = $mensalidadeModel->buscarMensalidadesPorFiliado($id_filiado);
                usort($mensalidades_aluno, function($a, $b) {
                    return strcmp($a['referencia'], $b['referencia']);
                });

                foreach ($mensalidades_aluno as $m) {
                    if ($m['status_pagamento'] !== 'pago') {
                        if ($mensalidadeModel->receberMensalidade($m['id'], $data_pagamento)) {
                            $mensalidade_paga = $m;
                            $id_mensalidade = $m['id'];
                            break;
                        }
                    }
                }

                if (!$mensalidade_paga) {
                    $referencia_atual = date("Y-m");
                    
                    // Tenta buscar o dia de vencimento personalizado configurado para o aluno
                    $dia_vencimento = 10;
                    include_once __DIR__ . "/../models/dojoAlunoConfigModel.php";
                    $alunoConfigModel = new DojoAlunoConfigModel();
                    $config = $alunoConfigModel->buscarPorFiliado($id_filiado);
                    if ($config) {
                        $dia_vencimento = $config['dia_vencimento'];
                    }
                    $data_vencimento = $referencia_atual . "-" . str_pad($dia_vencimento, 2, '0', STR_PAD_LEFT);
                    
                    if ($mensalidadeModel->criarMensalidade($id_filiado, $referencia_atual, $valor, $data_vencimento)) {
                        $mensalidades_aluno = $mensalidadeModel->buscarMensalidadesPorFiliado($id_filiado);
                        foreach ($mensalidades_aluno as $m) {
                            if ($m['referencia'] === $referencia_atual) {
                                if ($mensalidadeModel->receberMensalidade($m['id'], $data_pagamento)) {
                                    $mensalidade_paga = $m;
                                    $id_mensalidade = $m['id'];
                                }
                                break;
                            }
                        }
                    }
                }
            }

            // Registra a entrada no caixa geral
            $desc_caixa = "Stripe: " . $descricao . " - " . $nome_filiado;
            if ($mensalidade_paga) {
                $desc_caixa .= " (Ref: " . $mensalidade_paga['referencia'] . ")";
            }
            
            $financeiroModel->registrarMovimentacao(
                $desc_caixa,
                'entrada',
                $valor,
                $data_pagamento,
                'mensalidade',
                $id_mensalidade
            );

            $mensalidadeModel->atualizarInadimplencias();

            echo json_encode(["status" => "success", "message" => "Checkout de pagamento unico ou assinatura inicial concluido com sucesso."]);
            exit();
        }
    } elseif ($eventType === 'invoice.payment_succeeded') {
        // Cobrança recorrente mensal subsequente do Stripe
        $subscription_id = $eventData['subscription'] ?? null;
        $valor = isset($eventData['amount_paid']) ? floatval($eventData['amount_paid'] / 100) : 0;

        if ($subscription_id && $valor > 0) {
            // Localiza o aluno pelo ID da assinatura na tabela dojo_recorrencias
            $recorrencia = $recorrenciaModel->buscarPorSubscriptionId($subscription_id);

            if ($recorrencia) {
                $id_filiado = intval($recorrencia['id_filiado']);
                $filiado_obj = $filiadoModelRepo->buscarFiliadoPorId($id_filiado);
                $nome_filiado = $filiado_obj ? $filiado_obj->getNome() : 'Aluno';

                $data_pagamento = date("Y-m-d");
                $mensalidade_paga = null;
                $id_mensalidade = null;

                // Busca a mensalidade pendente/atrasada mais antiga para quitar
                $mensalidades_aluno = $mensalidadeModel->buscarMensalidadesPorFiliado($id_filiado);
                usort($mensalidades_aluno, function($a, $b) {
                    return strcmp($a['referencia'], $b['referencia']);
                });

                foreach ($mensalidades_aluno as $m) {
                    if ($m['status_pagamento'] !== 'pago') {
                        if ($mensalidadeModel->receberMensalidade($m['id'], $data_pagamento)) {
                            $mensalidade_paga = $m;
                            $id_mensalidade = $m['id'];
                            break;
                        }
                    }
                }

                // Se não há pendências de outros meses, cria a do mês atual
                if (!$mensalidade_paga) {
                    $referencia_atual = date("Y-m");
                    
                    // Tenta buscar o dia de vencimento personalizado configurado para o aluno
                    $dia_vencimento = 10;
                    include_once __DIR__ . "/../models/dojoAlunoConfigModel.php";
                    $alunoConfigModel = new DojoAlunoConfigModel();
                    $config = $alunoConfigModel->buscarPorFiliado($id_filiado);
                    if ($config) {
                        $dia_vencimento = $config['dia_vencimento'];
                    }
                    $data_vencimento = $referencia_atual . "-" . str_pad($dia_vencimento, 2, '0', STR_PAD_LEFT);

                    if ($mensalidadeModel->criarMensalidade($id_filiado, $referencia_atual, $valor, $data_vencimento)) {
                        $mensalidades_aluno = $mensalidadeModel->buscarMensalidadesPorFiliado($id_filiado);
                        foreach ($mensalidades_aluno as $m) {
                            if ($m['referencia'] === $referencia_atual) {
                                if ($mensalidadeModel->receberMensalidade($m['id'], $data_pagamento)) {
                                    $mensalidade_paga = $m;
                                    $id_mensalidade = $m['id'];
                                }
                                break;
                            }
                        }
                    }
                }

                // Registra a entrada no caixa geral
                $desc_caixa = "Stripe Recorrente: Mensalidade - " . $nome_filiado;
                if ($mensalidade_paga) {
                    $desc_caixa .= " (Ref: " . $mensalidade_paga['referencia'] . ")";
                }

                $financeiroModel->registrarMovimentacao(
                    $desc_caixa,
                    'entrada',
                    $valor,
                    $data_pagamento,
                    'mensalidade',
                    $id_mensalidade
                );

                $mensalidadeModel->atualizarInadimplencias();

                echo json_encode(["status" => "success", "message" => "Cobrança subsequente da assinatura Stripe registrada com sucesso."]);
                exit();
            }
        }
    } elseif ($eventType === 'customer.subscription.deleted') {
        // Assinatura cancelada no Stripe (seja pelo aluno no painel ou ao fim do ciclo)
        $subscription_id = $eventData['id'] ?? null;
        if ($subscription_id) {
            $recorrenciaModel->finalizarCancelamentoRecorrencia($subscription_id);
            echo json_encode(["status" => "success", "message" => "Assinatura Stripe finalizada no banco de dados."]);
            exit();
        }
    }
}

http_response_code(200);
echo json_encode(["status" => "ignored", "message" => "Evento nao processado"]);
?>
