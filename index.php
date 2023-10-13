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

header("location:views/inicio.php");
