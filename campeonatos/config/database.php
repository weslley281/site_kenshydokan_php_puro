<?php
/**
 * Configuração dos Bancos de Dados (Dual-Database)
 * Sistema Oficial de Campeonatos e Súmulas Kenshydokan Jutsu
 */

return [
    // Conexão Principal (Banco campeonatos)
    'default' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => getenv('DB_PORT') ?: 3306,
        'database' => getenv('DB_NAME') ?: 'campeonatos',
        'username' => getenv('DB_USER') ?: 'u515961161_kenshydokan',
        'password' => getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'Wesv@g28',
        'charset' => 'utf8mb4',
    ],

    // Conexão Corporativa do Site Raiz (Somente Leitura de Filiados)
    'main' => [
        'host' => getenv('MAIN_DB_HOST') ?: '127.0.0.1',
        'port' => getenv('MAIN_DB_PORT') ?: 3306,
        'database' => getenv('MAIN_DB_NAME') ?: 'u515961161_kenshydokan',
        'username' => getenv('MAIN_DB_USER') ?: 'u515961161_kenshydokan',
        'password' => getenv('MAIN_DB_PASS') !== false ? getenv('MAIN_DB_PASS') : 'Wesv@g28',
        'charset' => 'utf8mb4',
    ],
];
