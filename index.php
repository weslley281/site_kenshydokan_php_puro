<?php
include_once "db/migrations.php";
$migration = new Migration();
$migration->criarTabelaPublicacao();
echo "<br>";
$migration->criarTabelaFiliados();

header("location:views/inicio.php");
