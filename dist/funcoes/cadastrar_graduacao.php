<?php

include_once("../classes/graduacao.php");
include_once("../classes/conexao.php");

$registro = new graduacao();

$graduacao = $_POST["graduacao"];

$tentativa = $registro->registrar_graduacao($graduacao);

if ($tentativa > 0) {
    echo "<script language='javascript'>window.alert('Graduação Cadastrada com sucesso'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?graduacao'; </script>";
}else{
    echo "<script language='javascript'>window.alert('Erro ao Cadastrar'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?graduacao'; </script>";
}