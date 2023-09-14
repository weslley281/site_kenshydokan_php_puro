<?php

include_once("../../classes/postagens.php");

$registro = new postagem();

$situacao = $_POST["situacao"];
$id_postagem = $_POST["id_postagem"];

$tentativa = $registro->editar_situacao_postagem($id_postagem, $situacao);

if ($tentativa > 0) {
    echo "<script language='javascript'>window.alert('Postagem Editada com sucesso'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?postagens'; </script>";
}else{
    echo "<script language='javascript'>window.alert('Erro ao Editar'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?postagens'; </script>";
}