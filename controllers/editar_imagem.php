<?php
include_once "../db/conexao.php";
include_once "../repositorios/publicacaoRepositorio.php";

$id_usuario = $_POST["id_usuario"];
$registro = new UsuarioRepositorio;

//manda a imagem para a pasta
$caminho = '../img/' . $_FILES['foto']['name'];
$nome = $_FILES['foto']['name'];
$nome_temp = $_FILES['foto']['tmp_name'];
move_uploaded_file($nome_temp, $caminho);

//procura se a imagem existe
$c = new Conexao();
$conexao = $c->conectar();
$consulta = "SELECT * FROM imagens WHERE nome = '$nome'";
$resultado = mysqli_query($conexao, $consulta);
$dado = mysqli_fetch_array($resultado);
$linha = mysqli_num_rows($resultado);

//insere a imagem
if ($linha == 0) {
    $tentativa = $registro->registrar_imagem($nome, $caminho);
}

//busca o id da imagem depois de inserir
$consulta = "SELECT * FROM imagens WHERE nome = '$nome'";
$resultado = mysqli_query($conexao, $consulta);
$dado = mysqli_fetch_array($resultado);

$id_imagem = $dado["id_imagem"];

$consulta = "UPDATE usuarios SET id_imagem = '$id_imagem' WHERE id_usuario = $id_usuario";
$resultado = mysqli_query($conexao, $consulta);

if ($resultado > 0) {
    echo "<script language='javascript'>window.alert('Imagem Editada com sucesso'); </script>";
    echo "<script language='javascript'>window.location='../filiado/perfil.php'; </script>";
} else {
    echo "<script language='javascript'>window.alert('Erro ao Editar'); </script>";
    echo "<script language='javascript'>window.location='../filiado/editar_perfil.php'; </script>";
}
