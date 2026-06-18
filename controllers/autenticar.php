<?php
session_start();

include_once "../db/conexao.php";
include_once "../models/usuarioModel.php";

$c = new Conexao();
$conexao = $c->conectar();

// Defina um limite para tentativas de login malsucedidas
$limiteTentativas = 3;

// Verifique se a variável de sessão para tentativas existe
if (!isset($_SESSION['tentativas'])) {
    $_SESSION['tentativas'] = 0;
}

if (isset($_SESSION['bloqueio']) && $_SESSION['bloqueio'] > time()) {
    $tempoRestante = $_SESSION['bloqueio'] - time();
    $mensagem = "Usuário bloqueado, tente novamente em " . gmdate("H:i:s", $tempoRestante);
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='../views/login.php'; </script>";
    exit();
}

if (isset($_POST['usuario'], $_POST['senha'])) {
    $email = mysqli_real_escape_string($conexao, strtolower($_POST['usuario']));
    $senha = mysqli_real_escape_string($conexao, $_POST['senha']);

    $consulta = "SELECT id_usuario, email, nome, id_fil, nivel, senha FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conexao, $consulta);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($resultado && $dado = mysqli_fetch_array($resultado)) {
        if (password_verify($senha, $dado["senha"])) {
            $_SESSION['id_usuario'] = $dado['id_usuario'];
            $_SESSION['email'] = $email;
            $_SESSION['nome'] = $dado['nome'];
            $_SESSION['id_fil'] = $dado['id_fil'];
            $_SESSION['nivel'] = $dado['nivel'];

            if(isset($_POST['lembrar'])){
                $token = bin2hex(random_bytes(32));
                $token_hash = hash('sha256', $token);
                $expires = date('Y-m-d H:i:s', time() + (86400 * 30));

                $usuarioRepo = new Usuario($conexao);
                $usuarioRepo->updateRememberToken($dado['id_usuario'], $token_hash, $expires);

                setcookie('remember_token', $token, time() + (86400 * 30), "/");
            }else{
                setcookie('remember_token', '', time() - 3600, '/');
            }

            // Resetar tentativas e bloqueio
            unset($_SESSION['tentativas']);
            unset($_SESSION['bloqueio']);
            echo "<script language='javascript'>window.location='../views/perfil/perfil.php'; </script>";
            exit();
        }
    }

    // Incrementa o contador de tentativas
    $_SESSION['tentativas']++;

    // Verifica se o limite de tentativas foi atingido
    if ($_SESSION['tentativas'] >= $limiteTentativas) {
        // Bloqueia o usuário por 1 hora a partir deste momento
        $_SESSION['bloqueio'] = time() + 3600;
    }
}

// Se o login falhar, redirecione de volta para a página de login com uma mensagem de erro
echo "<script language='javascript'>window.alert('Erro'); </script>";
echo "<script language='javascript'>window.location='../views/login.php'; </script>";
exit();