<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/aulaModel.php";
    include_once "../repositorios/AulaRepositorio.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $dataMudanca = date("Y-m-d");
        $id_curso = $_POST['id_curso'];

        $aulaRepositorio = new AulaRepositorio();

        if ($_POST["tipo"] == "inserir") {
            $aulaModel = new AulaModel(
                null,
                $id_curso,
                $_POST["titulo"],
                $_POST["link"],
                $dataMudanca
            );

            $destino = "../views/admin/index.php?pagina=cursos";
            if ($aulaRepositorio->criarAula($aulaModel)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, $destino);
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, $destino);
            }
        } elseif ($_POST["tipo"] == "editar") {
            $id_aula = $_POST["id_aula"];
            $id_curso = $_POST["id_curso"];

            $aulaModel = new AulaModel(
                $id_aula,
                $_POST["id_curso"],
                $_POST["titulo"],
                $_POST["link"],
                $dataMudanca
            );
            
            $destino = "../views/admin/editar_aula.php?id=$id_aula&id_curso=$id_curso";
            if ($aulaRepositorio->editarAula($id_aula, $aulaModel)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, $destino);
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, $destino);
            }
        } elseif ($_POST["tipo"] == "excluir") {
            $id_aula = $_POST["id_aula"];
            $id_curso = $_POST["id_curso"];

            if ($aulaRepositorio->excluirAula($id_aula)) {
                $destino = "../views/admin/editar_curso.php?id=$id_curso";
                exibirMensagemEredirecionar(MSG_SUCESSO, $destino);
            } else {
                $destino = "../views/admin/editar_curso.php?id=$id_curso";
                exibirMensagemEredirecionar(MSG_ERRO, $destino);
            }
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", '../views/admin/index.php?pagina=aulas');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../views/admin/index.php?pagina=aulas');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
