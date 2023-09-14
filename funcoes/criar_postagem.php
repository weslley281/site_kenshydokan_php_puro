<?php
	include_once("../classes/postagens.php");

	$registro = new postagem();

	$id_usuario = $_POST["id_usuario"];
	$titulo = $_POST["titulo"];
	$conteudo = $_POST["conteudo"];
	$situacao = "nao";
	$data = date("Y,m,d");


	$tentativa = $registro->criar_postagens($id_usuario, $titulo, $conteudo, $situacao, $data);

	if ($tentativa > 0) {
	    echo "<script language='javascript'>window.alert('Postagem criada com sucesso, aguarde um administrador aprovar a sua postagem'); </script>";
	    echo "<script language='javascript'>window.location='../filiado/postagens.php'; </script>";
	}else{
	    echo "<script language='javascript'>window.alert('Erro ao Postar'); </script>";
	    echo "<script language='javascript'>window.location='../filiado/editar_postagem.php'; </script>";
	}