<?php
/**
 * Controlador de Autenticação do Sistema de Campeonatos
 * Padrão IDÊNTICO ao site principal (controllers/autenticar.php)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../models/usuarioModel.php";
include_once __DIR__ . "/alertHelper.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $senha = trim($_POST["senha"] ?? "");

    if (empty($email) || empty($senha)) {
        exibirMensagemEredirecionar("Preencha todos os campos para fazer login.", "../views/login.php");
    }

    $usuarioModel = new Usuario();
    $usuario = $usuarioModel->autenticar($email, $senha);

    if ($usuario) {
        $_SESSION["id_usuario"] = $usuario["id_usuario"];
        $_SESSION["nome"] = $usuario["nome"];
        $_SESSION["email"] = $usuario["email"];
        $_SESSION["nivel"] = $usuario["nivel"];

        exibirMensagemEredirecionar("Bem-vindo(a), " . $usuario["nome"] . "!", "../views/inicio.php");
    } else {
        exibirMensagemEredirecionar("E-mail ou senha incorretos.", "../views/login.php");
    }
} else {
    header("Location: ../views/login.php");
    exit;
}
