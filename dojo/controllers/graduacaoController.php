<?php
// controllers/graduacaoController.php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/graduacaoModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $graduacaoModel = new Graduacao();

        // Apenas Sensei (Administrador) pode realizar alterações de graduação
        if (!isset($_SESSION["id_usuario"]) || $_SESSION["nivel"] !== "sensei") {
            exibirMensagemEredirecionar("Acesso negado.", "../views/login.php");
        }

        if ($_POST["tipo"] === "criar_graduacao") {
            $nome = trim($_POST["graduacao"]);
            if (empty($nome)) {
                exibirMensagemEredirecionar("Erro: O nome da graduação não pode ser vazio.", '../views/admin/index.php?pagina=graduacoes');
            }

            $novaGraduacao = new Graduacao(null, $nome);
            if ($graduacaoModel->criarGraduacao($novaGraduacao)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=graduacoes');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=graduacoes');
            }
        } elseif ($_POST["tipo"] === "editar_graduacao") {
            $id_graduacao = intval($_POST["id_graduacao"]);
            $nome = trim($_POST["graduacao"]);

            if (empty($nome) || $id_graduacao <= 0) {
                exibirMensagemEredirecionar("Erro: Dados inválidos.", '../views/admin/index.php?pagina=graduacoes');
            }

            if ($graduacaoModel->editarGraduacao($id_graduacao, $nome)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=graduacoes');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=graduacoes');
            }
        } elseif ($_POST["tipo"] === "excluir_graduacao") {
            $id_graduacao = intval($_POST["id_graduacao"]);

            if ($id_graduacao <= 0) {
                exibirMensagemEredirecionar("Erro: ID inválido.", '../views/admin/index.php?pagina=graduacoes');
            }

            if ($graduacaoModel->excluirGraduacao($id_graduacao)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=graduacoes');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=graduacoes');
            }
        }
    } else {
        exibirMensagemEredirecionar("Dados não fornecidos.", '../views/admin/index.php?pagina=graduacoes');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST.", '../views/login.php');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>alert('$mensagem'); window.location='$destino';</script>";
    exit;
}
?>
