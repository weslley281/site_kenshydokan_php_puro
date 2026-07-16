<?php
// controllers/alunoController.php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validação de autenticação do aluno
    if (!isset($_SESSION["id_usuario"]) || !isset($_SESSION["id_fil"]) || intval($_SESSION["id_fil"]) <= 0) {
        echo "<script>alert('Acesso negado.'); window.location.href = '../views/login.php';</script>";
        exit();
    }

    include_once "../models/dojoRecorrenciaModel.php";
    include_once "../models/filiadoModel.php";
    require_once "../config.php";

    $id_filiado = intval($_SESSION["id_fil"]);
    $recorrenciaModel = new DojoRecorrenciaModel();
    $filiadoModelRepo = new FiliadoModel();

    if (isset($_POST["tipo"])) {
        $tipo = $_POST["tipo"];

        if ($tipo === "ativar_recorrencia_stripe") {
            // Verifica se aceitou os termos
            if (!isset($_POST["aceita_termos"])) {
                echo "<script>alert('Você precisa aceitar os termos de autorização para prosseguir.'); window.history.back();</script>";
                exit();
            }

            // Busca a proposta de recorrência pendente no banco de dados para o aluno logado
            $recorrencia = $recorrenciaModel->buscarAtivaOuPendentePorFiliado($id_filiado);
            $filiado_obj = $filiadoModelRepo->buscarFiliadoPorId($id_filiado);

            if ($recorrencia && $filiado_obj && $recorrencia['status'] === 'pendente') {
                $email = $filiado_obj->getEmail();
                $nome = $filiado_obj->getNome();
                $valor = floatval($recorrencia['valor']);
                $descricao = "Assinatura Mensal - " . $nome;

                $ip_aceite = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
                $termo_versao = 'v1';
                $data_hora_aceite = date("Y-m-d H:i:s");

                // Registra o termo de aceitação com IP e Data/Hora na tabela dojo_recorrencias
                $recorrenciaModel->registrarAceiteRecorrencia($recorrencia['id'], $ip_aceite, $termo_versao);

                $stripe_secret = STRIPE_SECRET_KEY;
                $url_sucesso = "http://" . $_SERVER['HTTP_HOST'] . "/gerenciador_dojo/views/dashboard_aluno.php?msg=assinatura_sucesso";
                $url_cancelamento = "http://" . $_SERVER['HTTP_HOST'] . "/gerenciador_dojo/views/dashboard_aluno.php?msg=assinatura_cancelada";

                // Payload para criação da sessão de Checkout em modo SUBSCRIPTION (Assinatura Recorrente)
                $dados_session = [
                    'line_items[0][price_data][currency]' => 'brl',
                    'line_items[0][price_data][product_data][name]' => $descricao,
                    'line_items[0][price_data][unit_amount]' => intval(round($valor * 100)),
                    'line_items[0][price_data][recurring][interval]' => 'month',
                    'line_items[0][price_data][recurring][interval_count]' => 1,
                    'line_items[0][quantity]' => 1,
                    'mode' => 'subscription',
                    'success_url' => $url_sucesso,
                    'cancel_url' => $url_cancelamento,
                    'customer_email' => $email,
                    'client_reference_id' => strval($id_filiado),
                    'metadata[id_filiado]' => strval($id_filiado),
                    'metadata[id_recorrencia]' => strval($recorrencia['id']),
                    'metadata[descricao]' => $descricao,
                    'metadata[recorrente]' => '1',
                    'metadata[ip_aceite]' => $ip_aceite,
                    'metadata[data_hora_aceite]' => $data_hora_aceite,
                    'metadata[termo_versao]' => $termo_versao
                ];

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

                if ($http_code === 200 && isset($response_data['url'])) {
                    header("Location: " . $response_data['url']);
                    exit();
                } else {
                    $error_msg = $response_data['error']['message'] ?? "Erro ao iniciar sessao no Stripe.";
                    error_log("Erro Stripe Checkout: " . $response);
                    echo "<script>alert('Erro no Stripe: " . addslashes($error_msg) . "'); window.history.back();</script>";
                    exit();
                }
            } else {
                echo "<script>alert('Nenhuma cobranca recorrente pendente encontrada.'); window.location.href = '../views/dashboard_aluno.php';</script>";
                exit();
            }
        } elseif ($tipo === "cancelar_recorrencia_stripe") {
            // Busca a assinatura ativa correspondente exclusivamente ao aluno logado (segurança!)
            $recorrencia = $recorrenciaModel->buscarAtivaOuPendentePorFiliado($id_filiado);

            if (!$recorrencia || empty($recorrencia['stripe_subscription_id'])) {
                echo "<script>alert('Nenhuma assinatura ativa vinculada ao seu cadastro.'); window.location.href = '../views/dashboard_aluno.php';</script>";
                exit();
            }

            // Segurança: NÃO confie em subscription_id vindo do POST.
            // Busque a assinatura pelo id_filiado logado e compare com o que está no banco.
            $subscription_id_post = $_POST["subscription_id"] ?? "";
            if (!empty($subscription_id_post) && $recorrencia['stripe_subscription_id'] !== $subscription_id_post) {
                error_log("Tentativa de cancelamento suspeita: filiado {$id_filiado} tentou enviar subscription_id {$subscription_id_post} mas no banco possui {$recorrencia['stripe_subscription_id']}");
                echo "<script>alert('Erro de segurança: Assinatura inválida.'); window.location.href = '../views/dashboard_aluno.php';</script>";
                exit();
            }

            if (in_array($recorrencia['status'], ['ativo', 'cancelando'])) {
                if ($recorrencia['status'] === 'cancelando') {
                    echo "<script>alert('Seu cancelamento já foi agendado anteriormente.'); window.location.href = '../views/dashboard_aluno.php';</script>";
                    exit();
                }

                $subscription_id = $recorrencia['stripe_subscription_id'];
                $stripe_secret = STRIPE_SECRET_KEY;

                // Cancela ao final do período de cobrança no Stripe para evitar perdas financeiras e disputas
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/subscriptions/" . $subscription_id);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['cancel_at_period_end' => 'true']));
                curl_setopt($ch, CURLOPT_USERPWD, $stripe_secret . ":");
                
                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                $response_data = json_decode($response, true);

                if ($http_code === 200 && (isset($response_data['cancel_at_period_end']) && $response_data['cancel_at_period_end'] === true)) {
                    $recorrenciaModel->agendarCancelamentoRecorrencia($recorrencia['id']);
                    echo "<script>alert('Débito automático cancelado! Sua assinatura continuará ativa até o fim do período já pago.'); window.location.href = '../views/dashboard_aluno.php';</script>";
                    exit();
                } else {
                    $error_msg = $response_data['error']['message'] ?? "Erro ao cancelar assinatura no Stripe.";
                    error_log("Erro ao cancelar Stripe Subscription: " . $response);
                    echo "<script>alert('Erro Stripe: " . addslashes($error_msg) . "'); window.history.back();</script>";
                    exit();
                }
            } else {
                echo "<script>alert('Nenhuma assinatura ativa vinculada ao seu cadastro.'); window.location.href = '../views/dashboard_aluno.php';</script>";
                exit();
            }
        }
    }
} else {
    echo "<script>alert('Requisicao invalida.'); window.location.href = '../views/dashboard_aluno.php';</script>";
}
?>
