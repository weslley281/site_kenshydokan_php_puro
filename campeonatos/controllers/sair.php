<?php
/**
 * Logout do Sistema de Campeonatos
 * Padrão IDÊNTICO ao site principal (controllers/sair.php)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];
session_destroy();

header("Location: ../views/login.php");
exit;
