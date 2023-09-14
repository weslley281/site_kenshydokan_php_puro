<?php

include_once("../classes/usuarios.php");

$registros = new usuario();

$nome1 = $_POST["nome1"];
$nome2 = $_POST["nome2"];
$nome = $nome1 . " " . $nome2;
$tipo = $_POST["tipo"];
$id_filiado = $_POST["id_filiado"];
$telefone = $_POST["telefone"];
$email = $_POST["email"];
$senha1 = $_POST["senha1"];
$senha2 = $_POST["senha2"];
$id_filiado = $_POST["id_filiado"];

if ($senha1 != $senha2 or $senha2 != $senha1) {
	echo "<script language='javascript'>window.alert('As senhas estão diferentes, por favor tente novamente'); </script>";
    echo "<script language='javascript'>window.location='../registrar_usuario.php'; </script>";
}else{
	$senha = password_hash($senha1, PASSWORD_DEFAULT);

	$tentativa = $registros->registrar_usuario($nome, $email, $tipo, $telefone, $id_filiado, $senha);
	if ($tentativa > 0) {
        echo "<script language='javascript'>window.alert('Usuario Cadastrado com sucesso'); </script>";
        echo "<script language='javascript'>window.location='../inicio.php'; </script>";
    }else{
        echo "<script language='javascript'>window.alert('Erro ao Cadastrar'); </script>";
        echo "<script language='javascript'>window.location='../registrar_usuario.php'; </script>";
    }
}
