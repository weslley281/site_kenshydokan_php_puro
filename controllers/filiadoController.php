<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/filiadoModel.php";
    include_once "../repositorios/filiadoRepositorio.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $dataMudanca = date("Y-m-d");

        $filiadoRepositorio = new FiliadoRepositorio();

        if ($_POST["tipo"] == "inserir") {
            $filiadoModel = new FiliadoModel(
                null,
                $_POST["id_graduacao"],
                $_POST["nome"],
                $_POST["dojo"],
                $_POST["telefone"],
                $_POST["rg"],
                $_POST["email"],
                $_POST["endereco"],
                $_POST["cidade"],
                $_POST["id_estado"],
                $_POST["confirmacao"],
                $dataMudanca
            );

            if ($filiadoRepositorio->criarFiliado($filiadoModel)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin.php?pagina=filiados');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin.php?pagina=filiados');
            }
        } elseif ($_POST["tipo"] == "editar") {
            $id_filiado = $_POST["id_filiado"];

            $filiadoModel = new FiliadoModel(
                $id_filiado,
                $_POST["id_graduacao"],
                $_POST["nome"],
                $_POST["dojo"],
                $_POST["telefone"],
                $_POST["rg"],
                $_POST["email"],
                $_POST["endereco"],
                $_POST["cidade"],
                $_POST["id_estado"],
                $_POST["confirmacao"],
                $dataMudanca
            );

            if ($filiadoRepositorio->editarFiliado($id_filiado, $filiadoModel)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin.php?pagina=filiados');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin.php?pagina=filiados');
            }
        } elseif ($_POST["tipo"] == "excluir") {
            $id_filiado = $_POST["id_filiado"];

            if ($filiadoRepositorio->excluirFiliado($id_filiado)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin.php?pagina=filiados');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin.php?pagina=filiados');
            }
        } elseif ($_POST["tipo"] == "desconfirmar") {
            $id_filiado = $_POST["id_filiado"];
            $filiado = $filiadoRepositorio->buscarFiliadoPorId($id_filiado);
            if ($filiado) {
                $filiado->setConfirmacao("nao");
                if ($filiadoRepositorio->editarFiliado($id_filiado, $filiado)) {
                    exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin.php?pagina=filiados');
                } else {
                    exibirMensagemEredirecionar(MSG_ERRO, '../views/admin.php?pagina=filiados');
                }
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin.php?pagina=filiados');
            }
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", '../views/admin.php?pagina=filiados');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../views/admin.php?pagina=filiados');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
