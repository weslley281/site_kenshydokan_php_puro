<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    echo "<script language='javascript'>window.alert('Você não pode fazer isso'); </script>";
    echo "<script language='javascript'>window.location='../views/login.php'; </script>";
    exit();
}

include_once "../models/publicacaoModel.php";

$id_publicacao = $_GET["id"];

if (Publicacao::excluir_publicacao($id_publicacao)) {
    echo "<script language='javascript'>window.alert('Postagem Excluida com sucesso'); </script>";
    echo "<script language='javascript'>window.location='../views/perfil/suas_postagens.php'; </script>";
} else {
    echo "<script language='javascript'>window.alert('Erro ao Excluir'); </script>";
    echo "<script language='javascript'>window.location='../views/perfil/suas_postagens.php'; </script>";
}