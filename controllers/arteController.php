<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/arteModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $arteModel = new ArteMarcial();

        if ($_POST["tipo"] == "criar_arte") {
            $nome = $_POST["nome"];
            $novaArte = new ArteMarcial(null, $nome);

            if ($arteModel->criarArte($novaArte)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=artes');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=artes');
            }
        } elseif ($_POST["tipo"] == "editar_arte") {
            $id_arte = $_POST["id_arte"];
            $nome = $_POST["nome"];

            if ($arteModel->editarArte($id_arte, $nome)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=artes');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=artes');
            }
        } elseif ($_POST["tipo"] == "excluir_arte") {
            $id_arte = $_POST["id_arte"];

            if ($arteModel->excluirArte($id_arte)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=artes');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=artes');
            }
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", '../views/admin/index.php?pagina=artes');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../views/admin/index.php?pagina=artes');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
