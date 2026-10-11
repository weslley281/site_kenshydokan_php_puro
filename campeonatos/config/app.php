<?php
/**
 * Configurações Gerais da Aplicação
 * Sistema Oficial de Campeonatos e Súmulas Kenshydokan Jutsu
 */

return [
    'name' => getenv('APP_NAME') ?: 'Campeonatos Kenshydokan Jutsu',
    'env' => getenv('APP_ENV') ?: 'production',
    'debug' => (getenv('APP_DEBUG') === 'true' || getenv('APP_DEBUG') === '1'),
    'url' => getenv('APP_URL') ?: 'https://campeonatos.kenshydokan.org.br',
    'timezone' => 'America/Cuiaba',
    'base_path' => dirname(__DIR__),
    'storage_path' => dirname(__DIR__) . '/storage',
];
