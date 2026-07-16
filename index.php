<?php
include_once "db/migrations.php";

$migration = new Migration();
$migration->criarTabelaPublicacao();
$migration->criarTabelaFiliados();
$migration->criarTabelaGraduacoes();
$migration->criarTabelaGaleria();
$migration->criarTabelaFotos();
$migration->criarTabelaUsuarios();
$migration->criarTabelaCampeonatos();
$migration->criarTabelaImagens();
$migration->criarTabelaCursos();
$migration->criarTabelaAulas();
$migration->criarTabelaCategorias();
$migration->criarTabelaEstados();
$migration->criarTabelaAvaliacoes();
$migration->criarTabelaCertificados();
$migration->criarTabelaDojos();
$migration->criarTabelaDojoAlunosConfig();
$migration->criarTabelaDojoMensalidades();
$migration->criarTabelaDojoFinanceiro();
$migration->criarTabelaCertificadosManuais();
$migration->criarTabelaCertificadosUpload();
$migration->criarTabelaArtesMarciais();
$migration->criarTabelaFiliadosGraduacoes();
$migration->criarTabelaListaPresenca();
$migration->criarTabelaDocumentos();

header("Location: views/inicio.php");
exit();
?>
