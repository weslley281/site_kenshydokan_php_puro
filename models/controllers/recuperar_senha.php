<?php
session_start();
include_once "../db/conexao.php";
include_once "../models/usuarioModel.php";

$c = new Conexao();
$conexao = $c->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    if ($email) {
        $usuario = Usuario::buscarUsuarioPorEmail($email);
        if ($usuario) {
            // Gerar token e salvar no banco
            $token = Usuario::gerarTokenRecuperacao($usuario['id_usuario']);

            // Montar link de recuperação
            $link = "https://kenshydokan.org.br/views/recuperar_senha.php?token=" . urlencode($token);

            // Enviar e-mail (exemplo simples)
            $to = $usuario['email'];
            $subject = "Recuperação de senha";
            $message = "Clique no link para redefinir sua senha: $link";
            $headers = "From: no-reply@kenshydokan.com\r\n";

            mail($to, $subject, $message, $headers);

            $_SESSION['recuperar_senha_msg'] = "E-mail de recuperação enviado!";
        } else {
            $_SESSION['recuperar_senha_msg'] = "E-mail não encontrado!";
        }
    }
    header("Location: ../views/recuperar_senha.php");
    exit();
}
