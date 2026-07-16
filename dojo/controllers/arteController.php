<?php
// controllers/arteController.php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/arteModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $arteModel = new ArteMarcial();

        // Apenas Sensei (Administrador) pode realizar alterações de artes marciais
        if (!isset($_SESSION["id_usuario"]) || $_SESSION["nivel"] !== "sensei") {
            exibirMensagemEredirecionar("Acesso negado.", "../views/login.php");
        }

        if ($_POST["tipo"] === "criar_arte") {
            $nome = trim($_POST["nome"]);
            if (empty($nome)) {
                exibirMensagemEredirecionar("Erro: O nome da arte marcial não pode ser vazio.", '../views/admin/index.php?pagina=artes');
            }

            $novaArte = new ArteMarcial(null, $nome);
            if ($arteModel->criarArte($novaArte)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=artes');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=artes');
            }
        } elseif ($_POST["tipo"] === "editar_arte") {
            $id_arte = intval($_POST["id_arte"]);
            $nome = trim($_POST["nome"]);

            if (empty($nome) || $id_arte <= 0) {
                exibirMensagemEredirecionar("Erro: Dados inválidos.", '../views/admin/index.php?pagina=artes');
            }

            if ($arteModel->editarArte($id_arte, $nome)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=artes');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=artes');
            }
        } elseif ($_POST["tipo"] === "excluir_arte") {
            $id_arte = intval($_POST["id_arte"]);

            if ($id_arte <= 0) {
                exibirMensagemEredirecionar("Erro: ID inválido.", '../views/admin/index.php?pagina=artes');
            }

            if ($arteModel->excluirArte($id_arte)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=artes');
            } else {
                exibirMensagemEredirecionar("Erro: Não foi possível excluir. Verifique se existem alunos vinculados a esta arte marcial.", '../views/admin/index.php?pagina=artes');
            }
        }
    } else {
        exibirMensagemEredirecionar("Dados não fornecidos.", '../views/admin/index.php?pagina=artes');
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
