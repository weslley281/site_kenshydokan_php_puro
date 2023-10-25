<?php
session_start();

include_once "../db/conexao.php";
include_once "../repositorios/usuarioRepositorio.php";
$c = new Conexao();
$conexao = $c->conectar();

// Defina um limite para tentativas de login malsucedidas
$limiteTentativas = 3;

// Verifique se a variável de sessão para tentativas existe
if (!isset($_SESSION['tentativas'])) {
    $_SESSION['tentativas'] = 0;
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

            echo "<script language='javascript'>window.location='../views/perfil.php'; </script>";
            exit();
        }
    }

    // Incrementa o contador de tentativas
    $_SESSION['tentativas']++;

    // Verifica se o limite de tentativas foi atingido
    if ($_SESSION['tentativas'] >= $limiteTentativas) {
        UsuarioRepositorio::editarNivelUsuario($resultado);
    }
}

// Se o login falhar, redirecione de volta para a página de login com uma mensagem de erro.
echo "<script language='javascript'>window.location='../views/login.php'; </script>";
