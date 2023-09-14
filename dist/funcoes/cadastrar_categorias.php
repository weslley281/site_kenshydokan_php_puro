<?php

include_once("../classes/categorias.php");
include_once("../classes/conexao.php");

$registro = new categoria();

$categoria = $_POST["categoria"];

$tentativa = $registro->registrar_categoria($categoria);

if ($tentativa > 0) {
    echo "<script language='javascript'>window.alert('Categoria Cadastrada com sucesso'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?categorias'; </script>";
}else{
    echo "<script language='javascript'>window.alert('Erro ao Cadastrar'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?categorias'; </script>";
}