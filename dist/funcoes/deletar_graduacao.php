<?php

include_once("../classes/graduacao.php");
include_once("../classes/conexao.php");

$registro = new graduacao();

$id_graduacao = $_GET["id"];

$tentativa = $registro->excluir_graduacao($id_graduacao);

if ($tentativa > 0) {
    echo "<script language='javascript'>window.alert('Graduação Excluida com sucesso'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?graduacao'; </script>";
}else{
    echo "<script language='javascript'>window.alert('Erro ao Excluir'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?graduacao'; </script>";
}