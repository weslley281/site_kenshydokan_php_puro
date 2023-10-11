<?php

include_once("../classes/conexao.php");
include_once("../classes/usuarios.php");

$registro = new usuario;

$c = new Conexao();
$conexao = $c->conectar();

$id_usuario = $_POST["id_usuario"];
$senha_antiga = $_POST["senha_antiga"];
$senha1 = $_POST["senha1"];
$senha2 = $_POST["senha2"];

$busca = "SELECT * FROM usuarios WHERE id_usuario = '$id_usuario'";
$resultado = mysqli_query($conexao, $busca);
$usuario = mysqli_fetch_array($resultado);
$senha_banco = $usuario["senha"];
if (password_verify($senha_antiga, $senha_banco)) {
	if ($senha1 == $senha2 or $senha2 == $senha1) {
		$senha = password_hash($senha1, PASSWORD_DEFAULT);
		$tentativa = $registro->editar_senha($id_usuario, $senha);
		if ($tentativa > 0) {
			echo "<script language='javascript'>window.alert('Senha Editada com Sucesso'); </script>";
			echo "<script language='javascript'>window.location='../filiado/editar_perfil.php'; </script>";
		} else {
			echo "<script language='javascript'>window.alert('Erro ao Editar Senha'); </script>";
			echo "<script language='javascript'>window.location='../filiado/editar_perfil.php'; </script>";
		}
	} else {
		echo "<script language='javascript'>window.alert('As senhas estão diferentes, tente novamente'); </script>";
		echo "<script language='javascript'>window.location='../filiado/editar_perfil.php'; </script>";
	}
} else {
	echo "<script language='javascript'>window.alert('Senha Antiga Invalida'); </script>";
	echo "<script language='javascript'>window.location='../filiado/editar_perfil.php'; </script>";
}
