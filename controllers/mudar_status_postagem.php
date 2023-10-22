<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION['nivel'] != "admin") {
    echo "<script language='javascript'>window.alert('Você não pode fazer isso'); </script>";
    echo "<script language='javascript'>window.location='../views/login.php'; </script>";
    exit();
}

include_once "../db/conexao.php";

$c = new Conexao;
$conexao = $c->conectar();

$id_publicacao = $_GET["id"];

$busca = "SELECT * FROM postagens WHERE id_publicacao = '$id_publicacao'";
$resultado = mysqli_query($conexao, $busca);
$publicacao = mysqli_fetch_array($resultado);

$status = $publicacao["status"] == "aguardando" ? "aprovado" : "aguardando";

if (PublicacaoRepositorio::excluir_publicacao($id_publicacao)) {
    echo "<script language='javascript'>window.alert('Postagem Excluida com sucesso'); </script>";
    echo "<script language='javascript'>window.location='../views/suas_postagens.php'; </script>";
} else {
    echo "<script language='javascript'>window.alert('Erro ao Excluir'); </script>";
    echo "<script language='javascript'>window.location='../views/suas_postagens.php; </script>";
}
