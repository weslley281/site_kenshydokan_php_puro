<?php
// controllers/certificadoManualController.php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Apenas admin
    if (!isset($_SESSION["id_usuario"]) || $_SESSION['nivel'] != "admin") {
        echo "<script>alert('Acesso negado.'); window.location.href = '../views/login.php';</script>";
        exit();
    }

    include_once "../models/certificadoManualModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $model = new CertificadoManualModel();

        if ($_POST["tipo"] == "inserir") {
            $id_filiado = intval($_POST["id_filiado"]);
            $titulo = $_POST["titulo"];
            $data_emissao = $_POST["data_emissao"];

            if ($model->criarCertificadoManual($id_filiado, $titulo, $data_emissao)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=certificados_manuais');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=certificados_manuais');
            }
        } elseif ($_POST["tipo"] == "editar") {
            $id = intval($_POST["id"]);
            $id_filiado = intval($_POST["id_filiado"]);
            $titulo = $_POST["titulo"];
            $data_emissao = $_POST["data_emissao"];

            if ($model->editarCertificadoManual($id, $id_filiado, $titulo, $data_emissao)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=certificados_manuais');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=certificados_manuais');
            }
        } elseif ($_POST["tipo"] == "excluir") {
            $id = intval($_POST["id"]);

            if ($model->excluirCertificadoManual($id)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=certificados_manuais');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=certificados_manuais');
            }
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", '../views/admin/index.php?pagina=certificados_manuais');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../views/admin/index.php?pagina=certificados_manuais');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
?>
