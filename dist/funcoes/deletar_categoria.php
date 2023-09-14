<?php

include_once("../classes/categorias.php");
include_once("../classes/conexao.php");

$registro = new categoria();

$id_categoria = $_GET["id"];

$tentativa = $registro->excluir_categoria($id_categoria);

if ($tentativa > 0) {
    echo "<script language='javascript'>window.alert('Categoria Excluida com sucesso'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?categorias'; </script>";
}else{
    echo "<script language='javascript'>window.alert('Erro ao Excluir'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?categorias'; </script>";
}