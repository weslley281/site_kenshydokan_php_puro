<?php
if (isset($_COOKIE['remember_token']) && !isset($_SESSION['id_usuario'])) {
    include_once __DIR__ . '/../db/conexao.php';
    include_once __DIR__ . '/../repositorios/usuarioRepositorio.php';

    $token = $_COOKIE['remember_token'];
    $token_hash = hash('sha256', $token);

    $c = new Conexao();
    $conexao = $c->conectar();

    $usuarioRepo = new UsuarioRepositorio($conexao);
    $usuario = $usuarioRepo->findUserByRememberToken($token_hash);

    if ($usuario && strtotime($usuario['remember_token_expires_at']) > time()) {
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['nome'] = $usuario['nome'];
        $_SESSION['id_fil'] = $usuario['id_fil'];
        $_SESSION['nivel'] = $usuario['nivel'];
    }
}
