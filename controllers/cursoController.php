<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/cursoModel.php";
    include_once "../repositorios/cursoRepositorio.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $dataMudanca = date("Y-m-d");

        $cursoRepositorio = new CursoRepositorio();

        if ($_POST["tipo"] == "inserir") {
            $cursoModel = new CursoModel(
                null,
                $_POST["id_categoria"],
                $_POST["nome"],
                $_POST["descricao"],
                $_POST["professor"],
                $_POST["id_imagem"],
                "aguardando",
                $dataMudanca
            );

            if ($cursoRepositorio->criarCurso($cursoModel)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/sucesso.php');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/erro.php');
            }
        } elseif ($_POST["tipo"] == "editar") {
            $id_curso = $_POST["id_curso"];

            $cursoModel = new CursoModel(
                $id_curso,
                $_POST["id_categoria"],
                $_POST["nome"],
                $_POST["descricao"],
                $_POST["professor"],
                $_POST["id_imagem"],
                $_POST["situacao"],
                $dataMudanca
            );

            if ($cursoRepositorio->editarCurso($id_curso, $cursoModel)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/sucesso.php');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/erro.php');
            }
        } elseif ($_POST["tipo"] == "excluir") {
            $id_curso = $_POST["id_curso"];

            if ($cursoRepositorio->excluirCurso($id_curso)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/sucesso.php');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/erro.php');
            }
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", '../views/erro.php');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../views/erro.php');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
