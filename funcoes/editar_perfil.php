<?php

include_once("../classes/conexao.php");
include_once("../classes/usuarios.php");

$registro = new usuario;

$c = new conectar();
$conexao = $c->conexao();

$id_usuario = $_POST["id_usuario"];
$nome = $_POST["nome"];
$email = $_POST["email"];
$telefone = $_POST["telefone"];

	$tentativa = $registro->editar_usuario($id_usuario, $nome, $email, $telefone);
	if ($tentativa > 0) {
		echo "<script language='javascript'>window.alert('Perfil editado com sucesso'); </script>";
    	echo "<script language='javascript'>window.location='../filiado/perfil.php'; </script>";
	}else{
		echo "<script language='javascript'>window.alert('Erro ao Editar'); </script>";
    	echo "<script language='javascript'>window.location='../filiado/editar_perfil.php'; </script>";
	}

