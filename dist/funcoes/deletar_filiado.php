<?php

include_once("../classes/filiados.php");
$registro = new filiado();

$id_filiado = $_GET["id"];

$tentativa = $registro->excluir_filiado($id_filiado);
if ($tentativa > 0) {
    echo "<script language='javascript'>window.alert('Filiado Excluido com sucesso'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?filiados'; </script>";
}else{
    echo "<script language='javascript'>window.alert('Erro ao Excluir'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?filiados'; </script>";
}
