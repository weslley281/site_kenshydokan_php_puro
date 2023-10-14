<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = filter_input(INPUT_POST, 'name');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone');
    $message = filter_input(INPUT_POST, 'message');

    if (empty($name) || empty($email) || empty($phone) || empty($message) || !$email) {
        echo "<script>alert('Preencha todos os campos');</script>";
        echo "<script>window.location='../contato.php';</script>";
    } else {
        $to = 'instituto@kenshydokan.org.br';
        $subject = "Contato do Site: $name";
        $body = "Você recebeu uma nova mensagem do formulário de contato do seu site.\n\n" .
            "Aqui estão os detalhes:\n\nNome: $name\n\nEmail: $email\n\nTelefone: $phone\n\nMensagem:\n$message";

        $headers = "From: $email\n";
        $headers .= "Reply-To: $email";

        if (mail($to, $subject, $body, $headers)) {
            echo "<script>alert('Mensagem Enviada com sucesso');</script>";
            echo "<script>window.location='../views/contato.php';</script>";
        } else {
            echo "<script>alert('Erro ao enviar a mensagem');</script>";
            echo "<script>window.location='../views/contato.php';</script>";
        }
    }
}
