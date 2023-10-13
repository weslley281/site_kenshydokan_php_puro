<?php
include_once "db/migrations.php";
$migration = new Migration();
$migration->criarTabelaPublicacao();

header("location:views/inicio.php");
