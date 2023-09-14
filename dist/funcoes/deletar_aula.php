<?php

include_once("../classes/aulas.php");
include_once("../classes/conexao.php");

$registro = new aula();

$id_aula = $_GET["id"];

$tentativa = $registro->excluir_aula($id_aula);

if ($tentativa > 0) {
    echo "<script language='javascript'>window.alert('Aula Excluida com Sucesso'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?cursos'; </script>";
}else{
    echo "<script language='javascript'>window.alert('Erro ao Excluir Aula'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?cursos'; </script>";
}