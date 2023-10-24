<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $mensagem = $_POST["mensagem"];

    if (empty($nome) || empty($email) || empty($telefone) || empty($mensagem)) {
        echo "<script>alert('Preencha todos os campos');</script>";
        echo "<script>window.location='../contato.php';</script>";
        exit();
    } else {
        // Configurar o email de destino
        $destino = "weslleyhenrique800@gmail.com";
        $assunto = "Contato do site Kenshydokan";
        // Montar a mensagem de email
        $mensagem_email = "Nome: $nome\n";
        $mensagem_email .= "Email: $email\n";
        $mensagem_email .= "Email: $telefone\n";
        $mensagem_email .= "Você recebeu uma nova mensagem do formulário de contato do seu site: \n$mensagem";



        $headers = "From: $email\n";
        $headers .= "Reply-To: $email";

        if (mail($destino, $assunto, $mensagem_email)) {
            echo "<script>alert('Mensagem Enviada com sucesso');</script>";
            //echo "<script>window.location='../views/contato.php';</script>";
        } else {
            echo "<script>alert('Erro ao enviar a mensagem');</script>";
            //echo "<script>window.location='../views/contato.php';</script>";
        }
    }
}
