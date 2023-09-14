<?php

include_once("../classes/filiados.php");
$registro = new filiado();

$id_graduacao = $_POST["id_graduacao"];
$nome = $_POST["nome"];
$dojo = $_POST["dojo"];
$telefone = $_POST["telefone"];
$rg = $_POST["rg"];
$email = $_POST["email"];
$endereco = $_POST["endereco"];
$cidade = $_POST["cidade"];
$id_estado = $_POST["id_estado"];
$confirmacao = $_POST["confirmacao"];
$data = date("Y, m, d");
var_dump($data);

$tentativa = $registro->registrar_filiado($id_graduacao, $nome, $dojo, $telefone, $rg, $email, $endereco, $cidade, $id_estado, $confirmacao);
if ($tentativa > 0) {
    echo "<script language='javascript'>window.alert('Filiado Editado com sucesso'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?filiados'; </script>";
}else{
    echo "<script language='javascript'>window.alert('Erro ao Editar'); </script>";
    //echo "<script language='javascript'>window.location='../inicio.php?filiados'; </script>";
}
