<?php

include_once("../classes/aulas.php");
include_once("../classes/conexao.php");

$registro = new aula();

$id_curso = $_POST["id_curso"];
$titulo = $_POST["titulo"];
$link = $_POST["link"];

$tentativa = $registro->adcionar_aula($id_curso, $titulo, $link);

if ($tentativa > 0) {
    echo "<script language='javascript'>window.alert('Aula Inserida com Sucesso'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?cursos'; </script>";
}else{
    echo "<script language='javascript'>window.alert('Erro ao Inserir Aula'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?cursos'; </script>";
}