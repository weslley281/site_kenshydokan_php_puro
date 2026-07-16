<?php
// index.php
include_once __DIR__ . "/db/migrations.php";

try {
    $migration = new Migration();
    $migration->executarTodas();
} catch (Throwable $t) {
    error_log("Erro ao executar as migracoes: " . $t->getMessage());
}

header("Location: views/login.php");
exit();
?>
