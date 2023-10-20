<?php
include_once "../repositorios/publicacaoRepositorio.php";

$id_publicacao = $_GET["id"];

if (PublicacaoRepositorio::excluir_publicacao($id_publicacao)) {
    echo "<script language='javascript'>window.alert('Postagem Excluida com sucesso'); </script>";
    echo "<script language='javascript'>window.location='../views/suas_postagens.php'; </script>";
} else {
    echo "<script language='javascript'>window.alert('Erro ao Excluir'); </script>";
    echo "<script language='javascript'>window.location='../views/suas_postagens.php; </script>";
}
