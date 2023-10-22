<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION['nivel'] != "admin") {
    echo "<script language='javascript'>window.alert('Você não pode fazer isso'); </script>";
    echo "<script language='javascript'>window.location='../views/login.php'; </script>";
    exit();
}

include_once "../db/conexao.php";
include_once "../repositorios/publicacaoRepositorio.php";

$c = new Conexao;
$conexao = $c->conectar();

$id_publicacao = $_GET["id"];

$busca = "SELECT * FROM postagens WHERE id_publicacao = '$id_publicacao'";
$resultado = mysqli_query($conexao, $busca);
$publicacao = mysqli_fetch_array($resultado);

$status = $publicacao["status"] == "aguardando" ? "aprovado" : "aguardando";

if (PublicacaoRepositorio::editar_status_publicacao($id_publicacao, $status)) {
    echo "<script language='javascript'>window.location='../views/admin.php#postagens'; </script>";
} else {
    echo "<script language='javascript'>window.alert('Erro'); </script>";
    echo "<script language='javascript'>window.location='../views/admin.php#postagens; </script>";
}
