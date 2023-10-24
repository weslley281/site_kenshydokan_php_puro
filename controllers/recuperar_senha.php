<?php
include_once "../repositorios/usuarioRepositorio.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenha o endereço de email fornecido pelo usuário
    $email = $_POST["email"];

    // Verifique se o email está associado a uma conta válida (você deve implementar essa verificação)

    $usuario = UsuarioRepositorio::buscarUsuarioExistente($email);

    if (!$usuario) {
        echo "<script>alert('Usário não existe');</script>";
        echo "<script>window.location='../views/login.php';</script>";
        exit();
    }

    // Gere um token de recuperação de senha (usando uniqid()) e associe-o ao usuário em seu banco de dados
    $token = uniqid();

    if (UsuarioRepositorio::editarTokenUsuario($usuario["id_usuario"], $token)) {

        // Agora, construa o link de recuperação com o token
        $link_recuperacao = "https://kenshydokan.org.br/views/recuperar_senha?id=" . $token;

        // Envie o email de recuperação
        $assunto = "Recuperação de Senha";
        $mensagem = "Olá,\n\nVocê solicitou a recuperação de senha. Clique no link a seguir para redefinir sua senha:\n\n";
        $mensagem .= $link_recuperacao . "\n\n";
        $mensagem .= "Se você não solicitou essa recuperação, ignore este email.\n";

        $headers = "From: $email\n";
        $headers .= "Reply-To: $email";

        // Use a função mail() ou uma biblioteca de email para enviar o email
        if (mail($usuario["email"], $assunto, $mensagem)) {
            exibirMensagemEredirecionar('Mensagem Enviada com sucesso', "../views/login.php");
        } else {
            exibirMensagemEredirecionar('Erro ao enviar a mensagem', "../views/login.php");
        }
    } else {
        exibirMensagemEredirecionar('Erro no banco de dados', "../views/login.php");
    }
} else {
    exibirMensagemEredirecionar('Você não pode fazer isso', "../views/login.php");
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
