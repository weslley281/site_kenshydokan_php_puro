<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION['nivel'] !== "admin") {
    echo "<script language='javascript'>window.alert('Você não pode fazer isso'); </script>";
    echo "<script language='javascript'>window.location='../views/login.php'; </script>";
    exit();
}

include_once "../db/conexao.php";
include_once "../models/publicacaoModel.php";

if (!isset($_GET["id"])) {
    echo "<script language='javascript'>window.location='../views/admin/index.php?pagina=postagens'; </script>";
    exit();
}

$id_publicacao = (int)$_GET["id"];
$postagem = Publicacao::buscarPostagemPorId($id_publicacao);

if (!$postagem) {
    echo "<script language='javascript'>window.alert('Postagem não encontrada.'); </script>";
    echo "<script language='javascript'>window.location='../views/admin/index.php?pagina=postagens'; </script>";
    exit();
}

$status = ($postagem["status"] === "aguardando") ? "aprovado" : "aguardando";

if (Publicacao::editar_status_publicacao($id_publicacao, $status)) {
    echo "<script language='javascript'>window.location='../views/admin/index.php?pagina=postagens'; </script>";
} else {
    echo "<script language='javascript'>window.alert('Erro ao atualizar status'); </script>";
    echo "<script language='javascript'>window.location='../views/admin/index.php?pagina=postagens'; </script>";
}
?>
