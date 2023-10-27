<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/categoriaModel.php";
    include_once "../repositorios/categoriaRepositorio.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $dataMudanca = date("Y-m-d");

        $categoriaRepositorio = new CategoriaRepositorio();

        if ($_POST["tipo"] == "inserir") {
            $categoriaModel = new CategoriaModel(
                null,
                $_POST["categoria"],
                $dataMudanca
            );

            if ($categoriaRepositorio->criarCategoria($categoriaModel)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin.php?pagina=cursos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin.php?pagina=cursos');
            }
        } elseif ($_POST["tipo"] == "editar") {
            $id_categoria = $_POST["id_categoria"];

            $categoriaModel = new CategoriaModel(
                $id_categoria,
                $_POST["categoria"],
                $dataMudanca
            );

            if ($categoriaRepositorio->editarCategoria($id_categoria, $categoriaModel)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin.php?pagina=cursos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin.php?pagina=cursos');
            }
        } elseif ($_POST["tipo"] == "excluir") {
            $id_categoria = $_POST["id_categoria"];

            if ($categoriaRepositorio->excluirCategoria($id_categoria)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin.php?pagina=cursos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin.php?pagina=cursos');
            }
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", '../views/admin.php?pagina=cursos');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../views/admin.php?pagina=cursos');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
