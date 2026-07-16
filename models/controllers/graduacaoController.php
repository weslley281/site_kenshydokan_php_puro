<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/graduacaoModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $graduacaoModel = new Graduacao();

        if ($_POST["tipo"] == "criar_graduacao") {
            $graduacao = $_POST["graduacao"];
            $novaGraduacao = new Graduacao(null, $graduacao);

            if ($graduacaoModel->criarGraduacao($novaGraduacao)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=graduacoes');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=graduacoes');
            }
        } elseif ($_POST["tipo"] == "editar_graduacao") {
            $id_graduacao = $_POST["id_graduacao"];
            $graduacao = $_POST["graduacao"];

            if ($graduacaoModel->editarGraduacao($id_graduacao, $graduacao)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=graduacoes');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=graduacoes');
            }
        } elseif ($_POST["tipo"] == "excluir_graduacao") {
            $id_graduacao = $_POST["id_graduacao"];

            if ($graduacaoModel->excluirGraduacao($id_graduacao)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=graduacoes');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=graduacoes');
            }
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", '../views/admin/index.php?pagina=graduacoes');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../views/admin/index.php?pagina=graduacoes');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
