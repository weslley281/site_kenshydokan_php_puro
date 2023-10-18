<?php
include_once "../models/usuarioModel.php";
include_once "../models/imagemModel.php";
include_once "../repositorios/usuarioRepositorio.php";
include_once "../repositorios/imagemRepositorio.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["email"]) && isset($_POST["nome"]) && isset($_POST["senha"]) && isset($_POST["type"])) {
        if ($_POST["type"] == "inserir") {
            $imagemRepositorio = new ImagemRepositorio;
            $usuarioRepositorio = new UsuarioRepositorio;

            $senhaSegura = password_hash($_POST["senha"], PASSWORD_DEFAULT);

            $diretorioUpload = "../img/";
            $nomeImagem = uniqid() . $_FILES["imagem"]["name"];
            $caminho = $diretorioUpload . $nomeImagem;

            $extensaoImagem = strtolower(pathinfo($caminho, PATHINFO_EXTENSION));

            if (in_array($extensaoImagem, ["jpg", "jpeg", "gif", "png"])) {
                if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho)) {
                    $imagemModel = new Imagem($nomeImagem, $caminho);

                    if ($imagemRepositorio->registrar_imagem($imagemModel)) {
                        $id_imagem = $imagemRepositorio::procura_id_imagem($nomeImagem);

                        $usuarioModel = new Usuario($_POST["nome"], $_POST["id_fil"], $id_imagem, $_POST["email"], $senhaSegura, $_POST["dataMudanca"]);

                        if ($usuarioRepositorio->criarUsuario($usuarioModel)) {
                            echo "<script language='javascript'>window.alert('Usuário criado com sucesso'); </script>";
                            echo "<script language='javascript'>window.location='../views/login.php'; </script>";
                        } else {
                            echo "<script language='javascript'>window.alert('Erro: Usuário não cadastrado, tente novamente'); </script>";
                            echo "<script language='javascript'>window.location='../views/cadastrar.php'; </script>";
                        }
                    } else {
                        echo "<script language='javascript'>window.alert('Erro: Imagem não salva, tente novamente'); </script>";
                        echo "<script language='javascript'>window.location='../views/cadastrar.php'; </script>";
                    }
                } else {
                    echo "<script language='javascript'>window.alert('Erro: Imagem não enviada, tente novamente'); </script>";
                    echo "<script language='javascript'>window.location='../views/cadastrar.php'; </script>";
                }
            } else {
                echo "<script language='javascript'>window.alert('Formato de imagem inválido'); </script>";
                echo "<script language='javascript'>window.location='../views/cadastrar.php'; </script>";
            }
        }
    } else {
        echo "<script language='javascript'>window.alert('Preencha todos os dados'); </script>";
        echo "<script language='javascript'>window.location='../views/cadastrar.php'; </script>";
    }
} else {
    echo "<script language='javascript'>window.alert('A requisição não é POST'); </script>";
    echo "<script language='javascript'>window.location='../views/cadastrar.php'; </script>";
}
