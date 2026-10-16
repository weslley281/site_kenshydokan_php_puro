<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('exigirLogin')) {
    function exigirLogin(string $destino = '../views/login.php'): void
    {
        if (!empty($_SESSION['id_usuario'])) {
            return;
        }

        header('Location: ' . $destino);
        exit;
    }
}
