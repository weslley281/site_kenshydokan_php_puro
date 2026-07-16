<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validação de autenticação
    if (!isset($_SESSION["id_usuario"]) || $_SESSION['nivel'] !== "sensei") {
        echo "<script>alert('Acesso negado.'); window.location.href = '../views/login.php';</script>";
        exit();
    }

    include_once "../models/dojoAlunoConfigModel.php";
    include_once "../models/dojoMensalidadeModel.php";
    include_once "../models/dojoFinanceiroModel.php";
    include_once "../models/filiadoModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro ao processar. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $tipo = $_POST["tipo"];
        $alunoConfigModel = new DojoAlunoConfigModel();
        $mensalidadeModel = new DojoMensalidadeModel();
        $financeiroModel = new DojoFinanceiroModel();

        if ($tipo === "salvar_aluno_config") {
            $id_filiado = intval($_POST["id_filiado"]);
            $valor = floatval($_POST["valor_mensalidade"]);
            $vencimento = intval($_POST["dia_vencimento"]);
            $status = $_POST["status_aluno"];

            if ($alunoConfigModel->salvarConfiguracao($id_filiado, $valor, $vencimento, $status)) {
                $mensalidadeModel->atualizarInadimplencias();
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=gerenciamento_dojo&sub=alunos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=gerenciamento_dojo&sub=alunos');
            }
        } elseif ($tipo === "lancar_mensalidades_mes") {
            $referencia = $_POST["referencia"]; // Ex: '2026-06'
            if (empty($referencia)) {
                $referencia = date("Y-m");
            }

            $resultado = $mensalidadeModel->gerarMensalidadesDoMes($referencia);
            $mensalidadeModel->atualizarInadimplencias();

            $msg = "Lançamentos concluídos: " . $resultado['criados'] . " gerados, " . $resultado['pulados'] . " já existentes.";
            exibirMensagemEredirecionar($msg, '../views/admin/index.php?pagina=gerenciamento_dojo&sub=mensalidades&referencia=' . $referencia);
        } elseif ($tipo === "receber_mensalidade") {
            $id_mensalidade = intval($_POST["id_mensalidade"]);
            $data_pagamento = $_POST["data_pagamento"];
            if (empty($data_pagamento)) {
                $data_pagamento = date("Y-m-d");
            }

            $mensalidade = $mensalidadeModel->buscarMensalidadePorId($id_mensalidade);
            if ($mensalidade) {
                if ($mensalidadeModel->receberMensalidade($id_mensalidade, $data_pagamento)) {
                    // Busca dados do filiado para o histórico financeiro
                    $filiadoModelRepo = new FiliadoModel();
                    $filiado_obj = $filiadoModelRepo->buscarFiliadoPorId($mensalidade['id_filiado']);
                    $nome_filiado = $filiado_obj ? $filiado_obj->getNome() : 'Aluno';

                    // Registra entrada no caixa geral automaticamente
                    $descricao = "Mensalidade - " . $nome_filiado . " (Ref: " . $mensalidade['referencia'] . ")";
                    $financeiroModel->registrarMovimentacao(
                        $descricao,
                        'entrada',
                        $mensalidade['valor'],
                        $data_pagamento,
                        'mensalidade',
                        $id_mensalidade
                    );

                    // Recalcula o status de inadimplência de todos os alunos
                    $mensalidadeModel->atualizarInadimplencias();

                    $destino = '../views/admin/index.php?pagina=gerenciamento_dojo&sub=mensalidades&referencia=' . $mensalidade['referencia'];
                    echo "<script language='javascript'>
                        if (confirm('Operação realizada com sucesso! Deseja gerar e imprimir o recibo de pagamento?')) {
                            window.open('imprimir_recibo.php?id=" . $id_mensalidade . "', '_blank');
                        }
                        window.location='" . $destino . "';
                    </script>";
                } else {
                    exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=gerenciamento_dojo&sub=mensalidades');
                }
            } else {
                exibirMensagemEredirecionar("Mensalidade não encontrada.", '../views/admin/index.php?pagina=gerenciamento_dojo&sub=mensalidades');
            }
        } elseif ($tipo === "lancar_financeiro") {
            $descricao = $_POST["descricao"];
            $tipo_mov = $_POST["tipo_movimentacao"]; // 'entrada' ou 'saida'
            $valor = floatval($_POST["valor"]);
            $data_mov = $_POST["data_movimentacao"];
            $categoria = $_POST["categoria"];

            if (empty($data_mov)) {
                $data_mov = date("Y-m-d");
            }

            if ($financeiroModel->registrarMovimentacao($descricao, $tipo_mov, $valor, $data_mov, $categoria)) {
                $ref_mes = substr($data_mov, 0, 7);
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=gerenciamento_dojo&sub=financeiro&referencia=' . $ref_mes);
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=gerenciamento_dojo&sub=financeiro');
            }
        } elseif ($tipo === "excluir_financeiro") {
            $id_financeiro = intval($_POST["id_financeiro"]);
            $ref_mes = $_POST["referencia_mes"];

            if ($financeiroModel->excluirMovimentacao($id_financeiro)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=gerenciamento_dojo&sub=financeiro&referencia=' . $ref_mes);
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=gerenciamento_dojo&sub=financeiro');
            }
        } elseif ($tipo === "enviar_link_stripe") {
            $id_filiado = intval($_POST["id_filiado"]);
            $cobranca_tipo = $_POST["cobranca_tipo"] ?? "avulso";
            $id_mensalidade = isset($_POST["id_mensalidade"]) && $_POST["id_mensalidade"] !== "" ? intval($_POST["id_mensalidade"]) : null;
            $valor = floatval($_POST["valor"]);
            $descricao = trim($_POST["descricao"] ?? "Mensalidade do Dojô");

            if ($cobranca_tipo === "recorrente") {
                include_once "../models/dojoRecorrenciaModel.php";
                $recorrenciaModel = new DojoRecorrenciaModel();

                if ($recorrenciaModel->proporRecorrencia($id_filiado, $valor)) {
                    $filiadoModelRepo = new FiliadoModel();
                    $f = $filiadoModelRepo->buscarFiliadoPorId($id_filiado);
                    $emailEnviado = false;
                    $email = '';
                    
                    if ($f) {
                        $email = $f->getEmail();
                        $nome = $f->getNome();
                        $login_url = "http://" . $_SERVER['HTTP_HOST'] . "/gerenciador_dojo/views/login.php";
                        $assunto = "=?UTF-8?B?" . base64_encode("Proposta de Débito Automático - Dojô") . "?=";
                        
                        $mensagemHtml = "
                        <html>
                        <head>
                          <title>Assinatura Recorrente Proposta</title>
                          <style>
                            body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; padding: 20px; }
                            .container { max-width: 600px; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-top: 5px solid #dc3545; }
                            h2 { color: #dc3545; }
                            .valor { font-size: 24px; font-weight: bold; color: #28a745; margin: 15px 0; }
                            .btn-ativar { display: inline-block; background-color: #28a745; color: #ffffff !important; text-decoration: none; padding: 12px 30px; border-radius: 50px; font-weight: bold; margin-top: 15px; }
                            .footer { margin-top: 30px; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 15px; }
                          </style>
                        </head>
                        <body>
                          <div class='container'>
                            <h2>Olá, " . htmlspecialchars($nome) . "!</h2>
                            <p>Seu Sensei propôs uma assinatura recorrente para automatizar o pagamento de suas mensalidades no Dojô:</p>
                            <p><strong>Modalidade:</strong> Débito Automático Recorrente (Mensal)</p>
                            <div class='valor'>R$ " . number_format($valor, 2, ',', '.') . " / mês</div>
                            <p>Para ativar, você precisa acessar a sua área do aluno, ler e aceitar o Termo de Autorização Recorrente.</p>
                            <p>Clique no botão abaixo para acessar o painel e realizar a ativação com segurança:</p>
                            <p><a class='btn-ativar' href='{$login_url}' target='_blank'>Acessar Painel do Aluno</a></p>
                            <p>Caso o botão acima não funcione, copie e cole o link a seguir no seu navegador:</p>
                            <p style='word-break: break-all;'><a href='{$login_url}'>{$login_url}</a></p>
                            <div class='footer'>
                              <p>Este é um e-mail automático enviado pelo sistema de gestão do seu Dojô. Por favor, não responda.</p>
                            </div>
                          </div>
                        </body>
                        </html>
                        ";

                        $headers = "MIME-Version: 1.0\r\n";
                        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
                        $headers .= "From: Cobrancas <noreply@" . $_SERVER['HTTP_HOST'] . ">\r\n";

                        try {
                            $emailEnviado = mail($email, $assunto, $mensagemHtml, $headers);
                        } catch (Throwable $e) {
                            error_log("Erro ao enviar email de proposta recorrente: " . $e->getMessage());
                        }
                    }

                    if ($emailEnviado) {
                        $msg = "Cobrança recorrente mensal proposta com sucesso e notificação enviada por e-mail para o aluno ({$email})!";
                    } else {
                        $msg = "Cobrança recorrente mensal proposta com sucesso no banco, mas ocorreu uma falha ao enviar o e-mail de notificação. Avise o aluno para ativar pelo painel.";
                    }
                    exibirMensagemEredirecionar($msg, '../views/admin/index.php?pagina=gerenciamento_dojo&sub=link_stripe');
                } else {
                    exibirMensagemEredirecionar("Erro ao propor cobrança recorrente.", '../views/admin/index.php?pagina=gerenciamento_dojo&sub=link_stripe');
                }
                exit();
            }

            $filiadoModelRepo = new FiliadoModel();
            $f = $filiadoModelRepo->buscarFiliadoPorId($id_filiado);

            if ($f && $valor > 0) {
                $email = $f->getEmail();
                $nome = $f->getNome();

                // Inclui as chaves do Stripe a partir de config.php
                require_once __DIR__ . "/../config.php";

                $stripe_secret = STRIPE_SECRET_KEY;
                $url_sucesso = "http://" . $_SERVER['HTTP_HOST'] . "/gerenciador_dojo/views/admin/index.php?pagina=gerenciamento_dojo" . ($id_mensalidade ? "&sub=mensalidades" : "&sub=link_stripe");
                $url_cancelamento = "http://" . $_SERVER['HTTP_HOST'] . "/gerenciador_dojo/views/admin/index.php?pagina=gerenciamento_dojo" . ($id_mensalidade ? "&sub=mensalidades" : "&sub=link_stripe");

                $dados_session = [
                    'line_items[0][price_data][currency]' => 'brl',
                    'line_items[0][price_data][product_data][name]' => $descricao,
                    'line_items[0][price_data][unit_amount]' => intval(round($valor * 100)),
                    'line_items[0][quantity]' => 1,
                    'mode' => 'payment',
                    'success_url' => $url_sucesso,
                    'cancel_url' => $url_cancelamento,
                    'customer_email' => $email,
                    'client_reference_id' => strval($id_filiado),
                    'metadata[id_filiado]' => strval($id_filiado),
                    'metadata[descricao]' => $descricao
                ];

                if ($id_mensalidade) {
                    $dados_session['metadata[id_mensalidade]'] = strval($id_mensalidade);
                }

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/checkout/sessions");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dados_session));
                curl_setopt($ch, CURLOPT_USERPWD, $stripe_secret . ":");
                
                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                $response_data = json_decode($response, true);

                $destino = $id_mensalidade ? '../views/admin/index.php?pagina=gerenciamento_dojo&sub=mensalidades' : '../views/admin/index.php?pagina=gerenciamento_dojo&sub=link_stripe';

                if ($http_code === 200 && isset($response_data['url'])) {
                    $checkout_url = $response_data['url'];

                    // Envia e-mail com a cobrança do Stripe
                    $assunto = "=?UTF-8?B?" . base64_encode("Link de Pagamento - " . $descricao) . "?=";
                    
                    $mensagemHtml = "
                    <html>
                    <head>
                      <title>Link de Pagamento Stripe</title>
                      <style>
                        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; padding: 20px; }
                        .container { max-width: 600px; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-top: 5px solid #dc3545; }
                        h2 { color: #dc3545; }
                        .valor { font-size: 24px; font-weight: bold; color: #28a745; margin: 15px 0; }
                        .btn-pagar { display: inline-block; background-color: #dc3545; color: #ffffff !important; text-decoration: none; padding: 12px 30px; border-radius: 50px; font-weight: bold; margin-top: 15px; }
                        .footer { margin-top: 30px; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 15px; }
                      </style>
                    </head>
                    <body>
                      <div class='container'>
                        <h2>Olá, " . htmlspecialchars($nome) . "!</h2>
                        <p>Foi gerada uma cobrança para você referente ao serviço:</p>
                        <p><strong>Descrição:</strong> " . htmlspecialchars($descricao) . "</p>
                        <div class='valor'>R$ " . number_format($valor, 2, ',', '.') . "</div>
                        <p>Clique no botão abaixo para efetuar o pagamento com segurança via Stripe (Cartão, Pix, etc.):</p>
                        <p><a class='btn-pagar' href='{$checkout_url}' target='_blank'>Pagar com Stripe</a></p>
                        <p>Caso o botão acima não funcione, copie e cole o link a seguir no seu navegador:</p>
                        <p style='word-break: break-all;'><a href='{$checkout_url}'>{$checkout_url}</a></p>
                        <div class='footer'>
                          <p>Este é um e-mail automático enviado pelo sistema de gestão do seu Dojô. Por favor, não responda.</p>
                        </div>
                      </div>
                    </body>
                    </html>
                    ";

                    $headers = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
                    $headers .= "From: Cobrancas <noreply@" . $_SERVER['HTTP_HOST'] . ">\r\n";

                    $emailEnviado = false;
                    try {
                        $emailEnviado = mail($email, $assunto, $mensagemHtml, $headers);
                    } catch (Throwable $e) {
                        error_log("Erro ao enviar email com link Stripe: " . $e->getMessage());
                    }

                    if ($emailEnviado) {
                        $msg = "Link de pagamento criado no Stripe e enviado com sucesso para o e-mail: {$email}!\\n\\nURL de Pagamento: {$checkout_url}";
                    } else {
                        $msg = "Link de pagamento criado no Stripe, mas ocorreu uma falha ao enviar o e-mail para o aluno.\\n\\nCopie e envie o link manualmente:\\n{$checkout_url}";
                    }
                    exibirMensagemEredirecionar($msg, $destino);
                } else {
                    $error_msg = $response_data['error']['message'] ?? "Erro desconhecido da API do Stripe.";
                    error_log("Erro Stripe API: " . $response);
                    exibirMensagemEredirecionar("Erro ao criar link no Stripe: " . $error_msg, $destino);
                }
            } else {
                $destino = isset($_POST["id_mensalidade"]) && $_POST["id_mensalidade"] !== "" ? '../views/admin/index.php?pagina=gerenciamento_dojo&sub=mensalidades' : '../views/admin/index.php?pagina=gerenciamento_dojo&sub=link_stripe';
                exibirMensagemEredirecionar("Aluno não encontrado ou valor inválido.", $destino);
            }
        }
    } else {
        exibirMensagemEredirecionar("Lançamento inválido.", '../views/admin/index.php?pagina=gerenciamento_dojo');
    }
} else {
    exibirMensagemEredirecionar("A requisição deve ser do tipo POST.", '../views/admin/index.php?pagina=gerenciamento_dojo');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
}
?>

