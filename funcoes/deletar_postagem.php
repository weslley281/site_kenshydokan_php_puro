<?php

include_once("../classes/postagens.php");
include_once("../classes/conexao.php");

$registro = new postagem();

$id_postagem = $_GET["id"];

$tentativa = $registro->excluir_postagens($id_postagem);

if ($tentativa > 0) {
    echo "<script language='javascript'>window.alert('Postagem Excluida com sucesso'); </script>";
    echo "<script language='javascript'>window.location='../filiado/postagens.php'; </script>";
}else{
    echo "<script language='javascript'>window.alert('Erro ao Excluir'); </script>";
    echo "<script language='javascript'>window.location='../filiado/postagens.php; </script>";
}