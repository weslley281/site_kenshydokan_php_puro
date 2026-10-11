<?php
/**
 * Front Controller do Sistema de Campeonatos e Súmulas Kenshydokan Jutsu
 * Subdomínio: campeonatos.kenshydokan.org.br
 */

declare(strict_types=1);

// Carrega o autoloader nativo em PHP puro
require_once dirname(__DIR__) . '/app/autoload.php';

use App\Core\Session;
use App\Core\View;
use App\Core\Router;
use App\Core\Database;

// Configuração de ambiente e fuso horário
Database::loadEnv(dirname(__DIR__) . '/.env');
date_default_timezone_set('America/Cuiaba');

// Inicia sessão segura
Session::start();

// Configura o diretório das Views
View::setViewsPath(dirname(__DIR__) . '/app/Views');

// ==============================================================================
// REGISTRO DE ROTAS DO SISTEMA
// ==============================================================================

// Autenticação
Router::get('/login', [App\Controllers\AuthController::class, 'loginForm']);
Router::post('/login', [App\Controllers\AuthController::class, 'login']);
Router::get('/logout', [App\Controllers\AuthController::class, 'logout']);

// Dashboard e Início
Router::get('/', [App\Controllers\DashboardController::class, 'index']);
Router::get('/dashboard', [App\Controllers\DashboardController::class, 'index']);

// Gestão de Campeonatos
Router::get('/campeonatos', [App\Controllers\CampeonatoController::class, 'index']);
Router::get('/campeonatos/novo', [App\Controllers\CampeonatoController::class, 'create']);
Router::post('/campeonatos/salvar', [App\Controllers\CampeonatoController::class, 'store']);
Router::get('/campeonatos/{id}', [App\Controllers\CampeonatoController::class, 'show']);
Router::get('/campeonatos/{id}/editar', [App\Controllers\CampeonatoController::class, 'edit']);
Router::post('/campeonatos/{id}/atualizar', [App\Controllers\CampeonatoController::class, 'update']);
Router::post('/campeonatos/{id}/status', [App\Controllers\CampeonatoController::class, 'changeStatus']);

// Gestão de Categorias
Router::get('/campeonatos/{id}/categorias', [App\Controllers\CategoriaController::class, 'index']);
Router::post('/categorias/salvar', [App\Controllers\CategoriaController::class, 'store']);
Router::post('/categorias/{id}/deletar', [App\Controllers\CategoriaController::class, 'destroy']);
Router::post('/campeonatos/{id}/categorias/modelo-padrao', [App\Controllers\CategoriaController::class, 'loadDefaultTemplate']);

// Inscrições e Integração com Filiados
Router::get('/campeonatos/{id}/inscricoes', [App\Controllers\InscricaoController::class, 'index']);
Router::get('/campeonatos/{id}/inscricoes/nova', [App\Controllers\InscricaoController::class, 'create']);
Router::get('/api/filiados/busca', [App\Controllers\InscricaoController::class, 'searchFiliados']);
Router::post('/inscricoes/salvar', [App\Controllers\InscricaoController::class, 'store']);
Router::post('/inscricoes/{id}/status', [App\Controllers\InscricaoController::class, 'updateStatus']);

// Pesagem Oficial
Router::get('/campeonatos/{id}/pesagem', [App\Controllers\PesagemController::class, 'index']);
Router::post('/pesagem/salvar', [App\Controllers\PesagemController::class, 'store']);
Router::post('/pesagem/{id}/remanejar', [App\Controllers\PesagemController::class, 'remanejarCategoria']);

// Chaveamento e Sorteios
Router::get('/campeonatos/{id}/chaves', [App\Controllers\ChaveamentoController::class, 'index']);
Router::get('/chaves/{id_categoria}/visualizar', [App\Controllers\ChaveamentoController::class, 'viewBracket']);
Router::post('/chaves/{id_categoria}/sortear', [App\Controllers\ChaveamentoController::class, 'generateBracket']);
Router::get('/chaves/{id_categoria}/pdf', [App\Controllers\ChaveamentoController::class, 'exportPdf']);

// Gestão de Tatames
Router::get('/campeonatos/{id}/tatames', [App\Controllers\TatameController::class, 'index']);
Router::post('/tatames/salvar', [App\Controllers\TatameController::class, 'store']);
Router::get('/tatames/{id_tatame}/painel', [App\Controllers\TatameController::class, 'panel']);
Router::get('/tatames/{id_tatame}/telao', [App\Controllers\TatameController::class, 'bigScreen']);

// Súmula Digital de Kumite (AJAX em Tempo Real)
Router::get('/sumula/kumite/{id_luta}', [App\Controllers\SumulaKumiteController::class, 'show']);
Router::post('/sumula/kumite/{id_luta}/iniciar', [App\Controllers\SumulaKumiteController::class, 'start']);
Router::post('/sumula/kumite/{id_luta}/evento', [App\Controllers\SumulaKumiteController::class, 'recordEvent']);
Router::post('/sumula/kumite/{id_luta}/finalizar', [App\Controllers\SumulaKumiteController::class, 'finish']);

// Súmula Digital de Kata
Router::get('/sumula/kata/{id_categoria}', [App\Controllers\SumulaKataController::class, 'show']);
Router::post('/sumula/kata/salvar', [App\Controllers\SumulaKataController::class, 'store']);
Router::post('/sumula/kata/desempate', [App\Controllers\SumulaKataController::class, 'tiebreak']);

// Súmula Digital de Tameshiwari
Router::get('/sumula/tameshiwari/{id_categoria}', [App\Controllers\SumulaTameshiwariController::class, 'show']);
Router::post('/sumula/tameshiwari/salvar', [App\Controllers\SumulaTameshiwariController::class, 'store']);

// Homologação e Resultados Oficiais
Router::get('/campeonatos/{id}/resultados', [App\Controllers\HomologacaoController::class, 'index']);
Router::post('/campeonatos/{id}/homologar/{id_categoria}', [App\Controllers\HomologacaoController::class, 'homologate']);
Router::post('/campeonatos/{id}/retificar/{id_sumula}', [App\Controllers\HomologacaoController::class, 'rectify']);

// Certificados Oficiais
Router::get('/campeonatos/{id}/certificados', [App\Controllers\CertificadoController::class, 'index']);
Router::get('/certificados/gerar/{id_inscricao}', [App\Controllers\CertificadoController::class, 'generateSingle']);
Router::post('/campeonatos/{id}/certificados/lote', [App\Controllers\CertificadoController::class, 'generateBatch']);
Router::get('/validar-certificado', [App\Controllers\CertificadoController::class, 'validatePublic']);
Router::post('/validar-certificado', [App\Controllers\CertificadoController::class, 'checkHash']);

// Relatórios Oficiais em PDF
Router::get('/campeonatos/{id}/relatorios/{tipo}', [App\Controllers\RelatorioController::class, 'generatePdf']);

// Trilha de Auditoria
Router::get('/campeonatos/{id}/auditoria', [App\Controllers\AuditoriaController::class, 'index']);

// Despacha a rota atual
Router::dispatch();
