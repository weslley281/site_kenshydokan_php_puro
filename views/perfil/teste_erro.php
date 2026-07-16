<?php
// 1. Força o relatório de todos os erros possíveis no PHP 8.3
error_reporting(E_ALL);

// 2. Força o PHP a exibir os erros diretamente no navegador
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<h1>Iniciando teste de depuração...</h1>";

// 3. Tenta incluir o arquivo que está quebrando
// Se houver um erro fatal nele, o wrapper vai capturar e mostrar na tela!
require "perfil.php"; 
?>