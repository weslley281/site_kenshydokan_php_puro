<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/aulaModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");
    define("MSG_ORDEM_DUPLICADA", "Erro: O número de ordenação já está em uso neste curso.");

    if (isset($_POST["tipo"])) {
        $dataMudanca = date("Y-m-d");
        $id_curso = $_POST['id_curso'];
        $num_ordenacao = isset($_POST['num_ordenacao']) ? (int)$_POST['num_ordenacao'] : 0;

        $aulaModelRepo = new AulaModel();

        if ($_POST["tipo"] == "inserir") {
            if ($aulaModelRepo->verificarOrdemExistente($id_curso, $num_ordenacao)) {
                $destino = "../views/admin/editar_curso.php?id=$id_curso";
                exibirMensagemEredirecionar(MSG_ORDEM_DUPLICADA, $destino);
            }

            $aulaModel = new AulaModel(
                null,
                $id_curso,
                $_POST["titulo"],
                $_POST["aula"],
                $num_ordenacao,
                $dataMudanca
            );

            $destino = "../views/admin/editar_curso.php?id=$id_curso";
            if ($aulaModelRepo->criarAula($aulaModel)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, $destino);
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, $destino);
            }
        } elseif ($_POST["tipo"] == "editar") {
            $id_aula = $_POST["id_aula"];
            $id_curso = $_POST["id_curso"];

            if ($aulaModelRepo->verificarOrdemExistente($id_curso, $num_ordenacao, $id_aula)) {
                $destino = "../views/admin/editar_aula.php?id=$id_aula&id_curso=$id_curso";
                exibirMensagemEredirecionar(MSG_ORDEM_DUPLICADA, $destino);
            }

            $aulaModel = new AulaModel(
                $id_aula,
                $_POST["id_curso"],
                $_POST["titulo"],
                $_POST["aula"],
                $num_ordenacao,
                $dataMudanca
            );
            
            $destino = "../views/admin/editar_aula.php?id=$id_aula&id_curso=$id_curso";
            if ($aulaModelRepo->editarAula($id_aula, $aulaModel)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, $destino);
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, $destino);
            }
        } elseif ($_POST["tipo"] == "excluir") {
            $id_aula = $_POST["id_aula"];
            $id_curso = $_POST["id_curso"];

            if ($aulaModelRepo->excluirAula($id_aula)) {
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
