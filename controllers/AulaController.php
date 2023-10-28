<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/aulaModel.php";
    include_once "../repositorios/aulaRepositorio.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $dataMudanca = date("Y-m-d");

        $aulaRepositorio = new AulaRepositorio();

        if ($_POST["tipo"] == "inserir") {
            $aulaModel = new AulaModel(
                null,
                $_POST["id_curso"],
                $_POST["titulo"],
                $_POST["link"],
                $dataMudanca
            );

            if ($aulaRepositorio->criarAula($aulaModel)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin.php?pagina=aulas');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin.php?pagina=aulas');
            }
        } elseif ($_POST["tipo"] == "editar") {
            $id_aula = $_POST["id_aula"];

            $aulaModel = new AulaModel(
                $id_aula,
                $_POST["id_curso"],
                $_POST["titulo"],
                $_POST["link"],
                $dataMudanca
            );

            if ($aulaRepositorio->editarAula($id_aula, $aulaModel)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin.php?pagina=aulas');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin.php?pagina=aulas');
            }
        } elseif ($_POST["tipo"] == "excluir") {
            $id_aula = $_POST["id_aula"];

            if ($aulaRepositorio->excluirAula($id_aula)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin.php?pagina=aulas');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin.php?pagina=aulas');
            }
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", '../views/admin.php?pagina=aulas');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../views/admin.php?pagina=aulas');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
