<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["nivel"] !== "admin" && $_SESSION["nivel"] !== "sensei")) {
    echo "<script language='javascript'>window.alert('Você não tem permissão para realizar esta operação.'); </script>";
    echo "<script language='javascript'>window.location='../views/login.php'; </script>";
    exit();
}

include_once "../models/publicacaoModel.php";
include_once "../models/imagemModel.php";

if (!isset($_GET["id"])) {
    echo "<script language='javascript'>window.location='../views/perfil/suas_postagens.php'; </script>";
    exit();
}

$id_publicacao = $_GET["id"];
$postagem_atual = Publicacao::buscarPostagemPorId($id_publicacao);

if (!$postagem_atual) {
    echo "<script language='javascript'>window.alert('Postagem não encontrada.'); </script>";
    echo "<script language='javascript'>window.location='../views/perfil/suas_postagens.php'; </script>";
    exit();
}

// Verifica se o usuário é o autor do post ou administrador
if ($postagem_atual["id_usuario"] != $_SESSION["id_usuario"] && $_SESSION["nivel"] !== "admin") {
    echo "<script language='javascript'>window.alert('Você não tem permissão para excluir esta postagem.'); </script>";
    echo "<script language='javascript'>window.location='../views/perfil/suas_postagens.php'; </script>";
    exit();
}

// Exclui a imagem de capa associada
if (!empty($postagem_atual["id_imagem"])) {
    $imagemModel = new Imagem();
    $dados_imagem = Imagem::procura_imagem($postagem_atual["id_imagem"]);
    if ($dados_imagem) {
        $caminho_img = $dados_imagem["caminho"];
        $caminho_img_rel = str_starts_with($caminho_img, "../") ? $caminho_img : "../" . $caminho_img;
        if (file_exists($caminho_img_rel)) {
            unlink($caminho_img_rel);
        }
    }
    $imagemModel->deleta_imagem($postagem_atual["id_imagem"]);
}

if (Publicacao::excluir_publicacao($id_publicacao)) {
    echo "<script language='javascript'>window.alert('Postagem Excluída com sucesso'); </script>";
    echo "<script language='javascript'>window.location='../views/perfil/suas_postagens.php'; </script>";
} else {
    echo "<script language='javascript'>window.alert('Erro ao Excluir'); </script>";
    echo "<script language='javascript'>window.location='../views/perfil/suas_postagens.php'; </script>";
}
?>