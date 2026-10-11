<?php
require_once __DIR__ . '/migrations.php';
require_once __DIR__ . '/../models/usuarioModel.php';

$migration = new Migration();
$migration->executarTodas();

$usuarioModel = new Usuario();
$admin = $usuarioModel->buscarPorEmail('admin@kenshydokan.org.br');
if (!$admin) {
    $usuarioModel->criarUsuario('Administrador Geral', 'admin@kenshydokan.org.br', 'admin123', 'admin');
    echo "Usuário padrão criado: admin@kenshydokan.org.br / senha: admin123\n";
}

echo "Todas as 15 tabelas do banco 'campeonatos' criadas com sucesso!\n";
