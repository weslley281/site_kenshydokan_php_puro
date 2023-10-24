<?php

// require_once '../libs/PHPMailer-master/src/PHPMailer.php';
// require_once '../libs/PHPMailer-master/src/SMTP.php';

// use PHPMailer\PHPMailer\PHPMailer;

// if ($_SERVER["REQUEST_METHOD"] === "POST") {
//     $name = filter_input(INPUT_POST, 'name');
//     $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
//     $phone = filter_input(INPUT_POST, 'phone');
//     $message = filter_input(INPUT_POST, 'message');

//     if (empty($name) || empty($email) || empty($phone) || empty($message) || !$email) {
//         echo "<script>alert('Preencha todos os campos');</script>";
//         echo "<script>window.location='../contato.php';</script>";
//     } else {
//         $mail = new PHPMailer();
//         $mail->isSMTP();
//         $mail->Host = 'smtp.gmail.com'; // Substitua pelo servidor SMTP real
//         $mail->SMTPAuth = true;
//         $mail->Username = 'kenshydokan@gmail.com'; // Substitua pelo seu e-mail
//         $mail->Password = 'Kenshydokan12'; // Substitua pela sua senha
//         $mail->Port = 587; // Porta SMTP
//         $mail->SMTPSecure = 'tls'; // Protocolo de segurança

//         $mail->setFrom($email, $name);
//         $mail->addAddress('instituto@kenshydokan.org.br'); // Endereço de e-mail de destino
//         $mail->Subject = "Contato do Site: $name";
//         $mail->Body = "Você recebeu uma nova mensagem do formulário de contato do seu site.\n\n" .
//             "Aqui estão os detalhes:\n\nNome: $name\n\nEmail: $email\n\nTelefone: $phone\n\nMensagem:\n$message";

//         if ($mail->send()) {
//             echo "deu certo";
//             echo "<script>alert('Mensagem Enviada com sucesso');</script>";
//             //echo "<script>window.location='../views/contato.php';</script>";
//         } else {
//             echo "não deu certo";
//             echo "<script>alert('Erro ao enviar a mensagem');</script>";
//             //echo "<script>window.location='../views/contato.php';</script>";
//         }
//     }
// }

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
