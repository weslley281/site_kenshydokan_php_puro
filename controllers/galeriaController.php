<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/galeriaModel.php";
    include_once "../repositorios/galeriaRepositorio.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $dataMudanca = date("Y-m-d");

        $galeriaRepositorio = new GaleriaRepositorio();

        if ($_POST["tipo"] == "inserir") {
            $galeriaModel = new GaleriaModel(
                null,
                $_POST["nome"],
                $dataMudanca,
                $dataMudanca
            );

            if ($galeriaRepositorio->criarGaleria($galeriaModel)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/sucesso.php');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/erro.php');
            }
        } elseif ($_POST["tipo"] == "editar") {
            $id_galeria = $_POST["id_galeria"];

            $galeriaModel = new GaleriaModel(
                $id_galeria,
                $_POST["nome"],
                $dataMudanca,
                $dataMudanca
            );

            if ($galeriaRepositorio->editarGaleria($id_galeria, $galeriaModel)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/sucesso.php');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/erro.php');
            }
        } elseif ($_POST["tipo"] == "excluir") {
            $id_galeria = $_POST["id_galeria"];

            if ($galeriaRepositorio->excluirGaleria($id_galeria)) {
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
