<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_campeonato = isset($_POST["id_campeonato"]) ? intval($_POST["id_campeonato"]) : 0;
    $titulo_campeonato = isset($_POST["titulo_campeonato"]) ? trim($_POST["titulo_campeonato"]) : "";
    $nome = isset($_POST["nome"]) ? trim($_POST["nome"]) : "";
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $telefone = isset($_POST["telefone"]) ? trim($_POST["telefone"]) : "";
    $dojo = isset($_POST["dojo"]) ? trim($_POST["dojo"]) : "";
    $graduacao = isset($_POST["graduacao"]) ? trim($_POST["graduacao"]) : "";
    $observacoes = isset($_POST["observacoes"]) ? trim($_POST["observacoes"]) : "";

    if (empty($nome) || empty($email) || empty($telefone) || empty($id_campeonato)) {
        header('Content-Type: application/json');
        echo json_encode(["status" => "error", "message" => "Por favor, preencha todos os campos obrigatórios."]);
        exit();
    }

    $destino = "institutokenshydokan@gmail.com";
    $assunto = "Inscricao Campeonato Interno: " . $titulo_campeonato;
    
    $mensagem_email = "Nova inscricao para Campeonato Interno recebida:\n";
    $mensagem_email .= "==================================================\n";
    $mensagem_email .= "CAMPEONATO: " . strtoupper($titulo_campeonato) . " (ID: $id_campeonato)\n";
    $mensagem_email .= "==================================================\n\n";
    $mensagem_email .= "DADOS DO ATLETA:\n";
    $mensagem_email .= "Nome Completo: $nome\n";
    $mensagem_email .= "E-mail: $email\n";
    $mensagem_email .= "Telefone/WhatsApp: $telefone\n";
    $mensagem_email .= "Dojo: $dojo\n";
    $mensagem_email .= "Graduacao: $graduacao\n\n";
    $mensagem_email .= "OBSERVACOES/CATEGORIA:\n";
    $mensagem_email .= (!empty($observacoes) ? $observacoes : "Nenhuma observacao informada.") . "\n\n";
    $mensagem_email .= "--------------------------------------------------\n";
    $mensagem_email .= "E-mail gerado pelo portal Kenshydokan.\n";

    $headers = "From: no-reply@kenshydokan.org.br\r\n";
    $headers .= "Reply-To: $email\r\n";

    if (mail($destino, $assunto, $mensagem_email, $headers)) {
        header('Content-Type: application/json');
        echo json_encode(["status" => "success", "message" => "Sua inscricao foi realizada e enviada com sucesso!"]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(["status" => "error", "message" => "Erro ao enviar a inscricao por e-mail. Tente novamente."]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => "Metodo de requisicao invalido."]);
}
