<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/categoriaModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $dataMudanca = date("Y-m-d");

        $categoriaModel = new CategoriaModel();

        if ($_POST["tipo"] == "inserir") {
            $novaCategoria = new CategoriaModel(
                null,
                $_POST["categoria"],
                $dataMudanca
            );

            if ($categoriaModel->criarCategoria($novaCategoria)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=cursos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=cursos');
            }
        } elseif ($_POST["tipo"] == "editar") {
            $id_categoria = $_POST["id_categoria"];

            $novaCategoria = new CategoriaModel(
                $id_categoria,
                $_POST["categoria"],
                $dataMudanca
            );

            if ($categoriaModel->editarCategoria($id_categoria, $novaCategoria)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=cursos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=cursos');
            }
        } elseif ($_POST["tipo"] == "excluir") {
            $id_categoria = $_POST["id_categoria"];

            if ($categoriaModel->excluirCategoria($id_categoria)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=cursos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=cursos');
            }
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", '../views/admin/index.php?pagina=cursos');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../views/admin/index.php?pagina=cursos');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
