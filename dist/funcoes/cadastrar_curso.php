<?php

include_once("../classes/cursos.php");
include_once("../classes/conexao.php");

$registro = new curso();

$curso = $_POST["nome"];
$descricao = $_POST["descricao"];
$id_categoria = $_POST["id_categoria"];
$professor = $_POST["professor"];
$data = date("Y,m,d");
$situacao = 2;

//manda a imagem para a pasta
$caminho = '../../imagens_produtos/' .$_FILES['foto']['name'];
$nome = $_FILES['foto']['name'];  
$nome_temp = $_FILES['foto']['tmp_name']; 
move_uploaded_file($nome_temp, $caminho);

//procura se a imagem existe
$c = new conectar();
$conexao = $c->conexao();
$consulta = "SELECT * FROM imagens WHERE nome = '$nome'";
$resultado = mysqli_query($conexao, $consulta);
$dado = mysqli_fetch_array($resultado);                    	
$linha = mysqli_num_rows($resultado);

//insere a imagem
if ($linha == 0) {
	$tentativa = $registro->registrar_imagem_curso($nome, $caminho);
}

//busca o id da imagem depois de inserir 
$consulta = "SELECT * FROM imagens WHERE nome = '$nome'";
$resultado = mysqli_query($conexao, $consulta);
$dado = mysqli_fetch_array($resultado);                    	

$id_imagem = $dado["id_imagem"];

//cadastra o serviço
$tentativa2 = $registro->registrar_curso($id_categoria, $curso, $descricao, $professor, $id_imagem, $data, $situacao);

if ($tentativa2 > 0) {
    echo "<script language='javascript'>window.alert('Curso Cadastrado com sucesso'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php?cursos'; </script>";
}else{
    echo "<script language='javascript'>window.alert('Erro ao Cadastrar'); </script>";
    //echo "<script language='javascript'>window.location='../inicio.php?cursos'; </script>";
}

