<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar permissão de admin
    if (!isset($_SESSION["id_usuario"]) || $_SESSION['nivel'] != "admin") {
        $_SESSION["msg_erro"] = "Acesso não autorizado.";
        header("Location: ../views/cobrancas/index.php");
        exit();
    }

    $tipo_destinatario = isset($_POST["tipo_destinatario"]) ? trim($_POST["tipo_destinatario"]) : "";
    $email_destino = isset($_POST["email_destino"]) ? trim($_POST["email_destino"]) : "";
    $frequencia = isset($_POST["frequencia"]) ? trim($_POST["frequencia"]) : "";
    $valor = isset($_POST["valor"]) ? floatval($_POST["valor"]) : 0.0;
    $descricao = isset($_POST["descricao"]) ? trim($_POST["descricao"]) : "";

    if (empty($email_destino) || $valor <= 0 || empty($descricao)) {
        $_SESSION["msg_erro"] = "Por favor, preencha todos os campos obrigatórios e garanta que o valor é maior do que zero.";
        header("Location: ../views/cobrancas/index.php");
        exit();
    }

    // Carregar o helper do Stripe
    require_once __DIR__ . '/../models/stripeHelper.php';

    // Gerar o link de pagamento no Stripe
    $resultado = StripeHelper::criarCheckoutSession($descricao, $valor, $email_destino);

    if ($resultado['sucesso'] === true) {
        $stripe_url = $resultado['url'];
        $_SESSION['stripe_link'] = $stripe_url;

        // Montar a mensagem do e-mail
        $destino = $email_destino;
        $assunto = "WKKA - Nova Cobranca: " . $descricao;
        
        $mensagem_email = "Olá,\n\n";
        $mensagem_email .= "Você está recebendo uma nova cobrança emitida pelo Instituto de Artes Marciais Kenshydokan / WKKA.\n\n";
        $mensagem_email .= "DETALHES DA COBRANÇA:\n";
        $mensagem_email .= "--------------------------------------------------\n";
        $mensagem_email .= "Descrição: " . $descricao . "\n";
        $mensagem_email .= "Valor: R$ " . number_format($valor, 2, ',', '.') . "\n";
        $mensagem_email .= "Tipo/Frequência: " . ucfirst($frequencia) . "\n";
        $mensagem_email .= "--------------------------------------------------\n\n";
        $mensagem_email .= "Para efetuar o pagamento de forma segura (Cartão de Crédito, PIX ou Boleto), acesse o link oficial do Stripe abaixo:\n";
        $mensagem_email .= $stripe_url . "\n\n";
        $mensagem_email .= "Se tiver qualquer dúvida, entre em contato respondendo a este e-mail.\n\n";
        $mensagem_email .= "Atenciosamente,\n";
        $mensagem_email .= "Diretoria WKKA / Instituto Kenshydokan\n";

        // Headers
        $headers = "From: no-reply@kenshydokan.org.br\r\n";
        $headers .= "Reply-To: institutokenshydokan@gmail.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Tentar enviar o e-mail
        if (mail($destino, $assunto, $mensagem_email, $headers)) {
            $_SESSION["msg_sucesso"] = "Cobrança gerada com sucesso e link enviado para o e-mail: <strong>" . htmlspecialchars($email_destino) . "</strong>!";
        } else {
            $_SESSION["msg_sucesso"] = "Cobrança gerada na Stripe com sucesso, mas o servidor local não pôde disparar o e-mail de notificação. Você ainda pode copiar o link gerado abaixo.";
        }

    } else {
        // Exibir erro retornado do Stripe ou conexão
        $_SESSION["msg_erro"] = "Falha ao gerar cobrança na Stripe: " . htmlspecialchars($resultado['erro']);
    }

    header("Location: ../views/cobrancas/index.php");
    exit();
} else {
    header("Location: ../views/cobrancas/index.php");
    exit();
}
