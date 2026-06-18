<?php
include_once "db/migrations.php";

$migration = new Migration();
$migration->criarTabelaPublicacao();
echo "<br>";
$migration->criarTabelaFiliados();
echo "<br>";
$migration->criarTabelaGraduacoes();
echo "<br>";
$migration->criarTabelaGaleria();
echo "<br>";
$migration->criarTabelaFotos();
echo "<br>";
$migration->criarTabelaUsuarios();
echo "<br>";
$migration->criarTabelaCampeonatos();
echo "<br>";
$migration->criarTabelaImagens();
echo "<br>";
$migration->criarTabelaCursos();
echo "<br>";
$migration->criarTabelaAulas();
echo "<br>";
$migration->criarTabelaCategorias();
echo "<br>";
$migration->criarTabelaEstados();
echo "<br>";
$migration->criarTabelaAvaliacoes();
echo "<br>";
$migration->criarTabelaCertificados();

echo "<script language='javascript'>window.location='views/inicio.php'; </script>";
