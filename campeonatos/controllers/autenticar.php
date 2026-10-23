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

include_once __DIR__ . "/csrfHelper.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrfExigir('../views/login.php');
    $email = trim($_POST["email"] ?? "");
    $senha = trim($_POST["senha"] ?? "");

    if (empty($email) || empty($senha)) {
        exibirMensagemEredirecionar("Preencha todos os campos para fazer login.", "../views/login.php");
    }

    $email = strtolower($email);
    $usuarioModel = new Usuario();

    // RF01.3: bloqueio temporário após 5 tentativas inválidas seguidas
    $segundosBloqueio = $usuarioModel->segundosDeBloqueio($email);
    if ($segundosBloqueio > 0) {
        $minutosRestantes = (int)ceil($segundosBloqueio / 60);
        exibirMensagemEredirecionar(
            "Muitas tentativas de login inválidas. Por segurança, o acesso está bloqueado por mais {$minutosRestantes} minuto(s).",
            "../views/login.php"
        );
    }

    $usuario = $usuarioModel->autenticar($email, $senha);

    if ($usuario) {
        $usuarioModel->registrarTentativaLogin($email, true);
        session_regenerate_id(true);
        $_SESSION["id_usuario"] = $usuario["id_usuario"];
        $_SESSION["nome"] = $usuario["nome"];
        $_SESSION["email"] = $usuario["email"];
        $_SESSION["nivel"] = $usuario["nivel"];

        exibirMensagemEredirecionar("Bem-vindo(a), " . $usuario["nome"] . "!", "../views/inicio.php");
    } else {
        $usuarioModel->registrarTentativaLogin($email, false);
        exibirMensagemEredirecionar("E-mail ou senha incorretos.", "../views/login.php");
    }
} else {
    header("Location: ../views/login.php");
    exit;
}
