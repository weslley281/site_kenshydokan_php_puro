<?php
if (isset($_COOKIE['remember_token']) && !isset($_SESSION['id_usuario'])) {
    include_once __DIR__ . '/../models/usuarioModel.php';

    $token = $_COOKIE['remember_token'];
    $token_hash = hash('sha256', $token);

    $usuarioModelRepo = new Usuario();
    $usuario = $usuarioModelRepo->findUserByRememberToken($token_hash);

    if ($usuario && strtotime($usuario['remember_token_expires_at']) > time()) {
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['nome'] = $usuario['nome'];
        $_SESSION['id_fil'] = $usuario['id_fil'];
        $_SESSION['nivel'] = $usuario['nivel'];
    }
}
