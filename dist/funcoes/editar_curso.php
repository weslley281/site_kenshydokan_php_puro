<?php

include_once("../classes/cursos.php");
include_once("../classes/conexao.php");

$registro = new curso();

$id_curso = $_POST["id_curso"];
$curso = $_POST["nome"];
$descricao = $_POST["descricao"];
$id_categoria = $_POST["id_categoria"];
$professor = $_POST["professor"];
$situacao = $_POST["situacao"];;

$tentativa = $registro->editar_curso($id_curso, $id_categoria, $curso, $descricao, $professor, $situacao);

if ($tentativa > 0) {
    echo "<script language='javascript'>window.alert('Curso Editado com sucesso'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?cursos'; </script>";
}else{
    echo "<script language='javascript'>window.alert('Erro ao Editar'); </script>";
    //echo "<script language='javascript'>window.location='../inicio.php?cursos'; </script>";
}