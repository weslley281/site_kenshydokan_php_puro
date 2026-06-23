<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/filiadoModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $dataMudanca = date("Y-m-d");
        $filiadoModel = new FiliadoModel();

        if ($_POST["tipo"] == "inserir") {
            $novoFiliado = new FiliadoModel(
                null,
                $_POST["codigo"],
                null, // id_graduacao legada ignorada
                $_POST["nome"],
                $_POST["dojo"],
                $_POST["telefone"],
                $_POST["data_nascimento"],
                $_POST["email"],
                $_POST["endereco"],
                $_POST["cidade"],
                $_POST["id_estado"],
                $_POST["confirmacao"],
                $dataMudanca
            );

            if ($filiadoModel->criarFiliado($novoFiliado)) {
                $id_novo = $novoFiliado->getIdFiliado();
                $graduacoes = isset($_POST["graduacoes"]) ? $_POST["graduacoes"] : [];
                $filiadoModel->salvarGraduacoes($id_novo, $graduacoes);

                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=filiados');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=filiados');
            }
        } elseif ($_POST["tipo"] == "editar") {
            $id_filiado = $_POST["id_filiado"];
            
            $novoFiliado = new FiliadoModel(
                $id_filiado,
                $_POST["codigo"],
                null, // id_graduacao legada ignorada
                $_POST["nome"],
                $_POST["dojo"],
                $_POST["telefone"],
                $_POST["data_nascimento"],
                $_POST["email"],
                $_POST["endereco"],
                $_POST["cidade"],
                $_POST["id_estado"],
                $_POST["confirmacao"],
                $dataMudanca
            );

            if ($filiadoModel->editarFiliado($id_filiado, $novoFiliado)) {
                $graduacoes = isset($_POST["graduacoes"]) ? $_POST["graduacoes"] : [];
                $filiadoModel->salvarGraduacoes($id_filiado, $graduacoes);

                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=filiados');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=filiados');
            }
        } elseif ($_POST["tipo"] == "excluir") {
            $id_filiado = $_POST["id_filiado"];

            if ($filiadoModel->excluirFiliado($id_filiado)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=filiados');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=filiados');
            }
        } elseif ($_POST["tipo"] == "desconfirmar") {
            $id_filiado = $_POST["id_filiado"];
            $filiado = $filiadoModel->buscarFiliadoPorId($id_filiado);
            if ($filiado) {
                $filiado->setConfirmacao("nao");
                if ($filiadoModel->editarFiliado($id_filiado, $filiado)) {
                    exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=filiados');
                } else {
                    exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=filiados');
                }
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=filiados');
            }
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", '../views/admin/index.php?pagina=filiados');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../views/admin/index.php?pagina=filiados');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
