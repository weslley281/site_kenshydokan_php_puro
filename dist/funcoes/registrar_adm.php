<?php

include_once("../classes/registros.php");

$registros = new adm();

$nome = $_POST["nome1"];
$sobrenome = $_POST["nome2"];
$email = $_POST["email"];
$senha1 = $_POST["senha1"];
$senha2 = $_POST["senha2"];

if ($senha1 != $senha2 or $senha2 != $senha1) {
	echo "<script language='javascript'>window.alert('As senhas estão diferentes, por favor tente novamente'); </script>";
    echo "<script language='javascript'>window.location='../registrar_adm.php'; </script>";
}else{
	$senha = password_hash($senha1, PASSWORD_DEFAULT);

	$tentativa = $registros->registrar_adm($nome, $sobrenome, $email, $senha);
	if ($tentativa > 0) {
        echo "<script language='javascript'>window.alert('Adiministrador Cadastrado com sucesso'); </script>";
        echo "<script language='javascript'>window.location='../inicio.php'; </script>";
    }else{
        echo "<script language='javascript'>window.alert('Erro ao Cadastrar'); </script>";
        echo "<script language='javascript'>window.location='../registrar_adm.php'; </script>";
    }
}
