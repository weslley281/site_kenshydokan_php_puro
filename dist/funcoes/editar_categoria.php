<?php

include_once("../classes/categorias.php");
include_once("../classes/conexao.php");

$registro = new categoria();

$id_categoria = $_POST["id_categoria"];
$categoria = $_POST["categoria"];

$tentativa = $registro->editar_categoria($id_categoria, $categoria);

if ($tentativa > 0) {
    echo "<script language='javascript'>window.alert('Categoria Editada com sucesso'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?categorias'; </script>";
}else{
    echo "<script language='javascript'>window.alert('Erro ao Editar'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?categorias'; </script>";
}