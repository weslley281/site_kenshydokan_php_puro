<?php
	include_once("../classes/postagens.php");

	$registro = new postagem();

	$id_postagem = $_POST["id_postagem"];
	$titulo = $_POST["titulo"];
	$conteudo = $_POST["conteudo"];
	$situacao = "sim";
	$data = date("Y,m,d");


	$tentativa = $registro->editar_postagens($id_postagem, $titulo, $conteudo, $situacao, $data);

	if ($tentativa > 0) {
	    echo "<script language='javascript'>window.alert('Postagem Editada com sucesso, aguarde um administrador aprovar a sua postagem'); </script>";
	    echo "<script language='javascript'>window.location='../filiado/postagens.php'; </script>";
	}else{
	    echo "<script language='javascript'>window.alert('Erro ao Postar'); </script>";
	    echo "<script language='javascript'>window.location='../filiado/editar_postagem.php?id=$id_postagem'; </script>";
	}