<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/dojoModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $dataFiliacao = date("Y-m-d");

        $dojoModel = new DojoModel();

        if ($_POST["tipo"] == "inserir") {
            $novoDojo = new DojoModel(
                null,
                $_POST["razao_social"],
                $_POST["nome_fantasia"],
                $_POST["cnpj"],
                $_POST["id_filiado_responsavel"],
                $_POST["telefone"],
                $_POST["celular"],
                $_POST["email"],
                $_POST["cep"],
                $_POST["endereco"],
                $_POST["cidade"],
                $_POST["estado"],
                $dataFiliacao,
                "ativo",
                null
            );

            if ($dojoModel->criarDojo($novoDojo)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=dojos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=dojos');
            }
        } elseif ($_POST["tipo"] == "editar") {
            $id_dojo = $_POST["id_dojo"];

            $novoDojo = new DojoModel(
                $id_dojo,
                $_POST["razao_social"],
                $_POST["nome_fantasia"],
                $_POST["cnpj"],
                $_POST["id_filiado_responsavel"],
                $_POST["telefone"],
                $_POST["celular"],
                $_POST["email"],
                $_POST["cep"],
                $_POST["endereco"],
                $_POST["cidade"],
                $_POST["estado"],
                $dataFiliacao,
                "ativo",
                null
            );

            if ($dojoModel->editarDojo($novoDojo)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=dojos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=dojos');
            }
        } elseif ($_POST["tipo"] == "excluir") {
            $id_dojo = $_POST["id_dojo"];

            if ($dojoModel->excluirDojo($id_dojo)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=dojos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=dojos');
            }
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", '../views/admin/index.php?pagina=dojos');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../views/admin/index.php?pagina=dojos');
}