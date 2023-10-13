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

header("location:views/inicio.php");
