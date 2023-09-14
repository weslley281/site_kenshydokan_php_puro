<?php

include_once("../classes/graduacao.php");
include_once("../classes/conexao.php");

$registro = new graduacao();

$graduacao = $_POST["graduacao"];
$id_graduacao = $_POST["id_graduacao"];

$tentativa = $registro->editar_graduacao($id_graduacao, $graduacao);

if ($tentativa > 0) {
    echo "<script language='javascript'>window.alert('Graduação Editada com sucesso'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?graduacao'; </script>";
}else{
    echo "<script language='javascript'>window.alert('Erro ao Editar'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?graduacao'; </script>";
}