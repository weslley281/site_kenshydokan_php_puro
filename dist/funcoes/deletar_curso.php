<?php

include_once("../classes/cursos.php");
include_once("../classes/conexao.php");

$registro = new curso();

$id_curso = $_GET["id"];
$id_imagem = $_GET["id_img"];

$tentativa = $registro->excluir_curso($id_curso);

if ($tentativa > 0) {
	$c = new conectar();
	$conexao = $c->conexao();
	$consulta = "SELECT * FROM imagens WHERE id_imagem = '$id_imagem'";
	$resultado = mysqli_query($conexao, $consulta);
	$imagem = mysqli_fetch_array($resultado);
	$caminho = "../../imagens_produtos/".$imagem["nome"];
	var_dump($caminho);
	unlink($caminho);
	$tentativa2 = $registro->excluir_imagem_curso($id_imagem);
    echo "<script language='javascript'>window.alert('Curso Excluido com sucesso'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?cursos'; </script>";
}else{
    echo "<script language='javascript'>window.alert('Erro ao Excluir'); </script>";
    //echo "<script language='javascript'>window.location='../inicio.php?cursos'; </script>";
}