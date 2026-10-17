<?php
require_once __DIR__ . '/migrations.php';

$migration = new Migration();
$migration->executarTodas();

echo "Todas as 15 tabelas do banco 'campeonatos' criadas com sucesso!\n";
echo "O administrador inicial é provisionado automaticamente no primeiro acesso usando ADMIN_EMAIL e ADMIN_PASSWORD do .env.\n";
