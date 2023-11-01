<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/publicacaoModel.php";
    include_once "../repositorios/publicacaoRepositorio.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $dataMudanca = date("Y-m-d");

        $publicacaoRepositorio = new PublicacaoRepositorio();

        if ($_POST["tipo"] == "inserir") {
            $id_usuario = $_POST["id_usuario"];
            $titulo = $_POST["titulo"];
            $conteudo = $_POST["conteudo"];

            $publicacao = new Publicacao($id_usuario, $titulo, $conteudo, $dataMudanca);

            if ($publicacaoRepositorio->criarPublicacao($publicacao)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/suas_postagens.php');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/criar_postagem.php');
            }
        } elseif ($_POST["tipo"] == "editar") {
            $id_publicacao = $_POST["id_publicacao"];
            $titulo = $_POST["titulo"];
            $conteudo = $_POST["conteudo"];

            $publicacao = new Publicacao(null, $titulo, $conteudo, $dataMudanca);

            if ($publicacaoRepositorio->editar_publicacao($id_publicacao, $publicacao)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/suas_postagens.php');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/editar_postagem.php');
            }
        } elseif ($_POST["tipo"] == "excluir") {
            $id_publicacao = $_POST["id_publicacao"];

            if ($publicacaoRepositorio::excluir_publicacao($id_publicacao)) {
                if (isset($_SESSION["nivel"] && $_SESSION['nivel'] == "admin")) {
                    exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin.php?pagina=postagens');
                    exit();
                }
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/suas_postagens.php');
            } else {
                if (isset($_SESSION["nivel"] && $_SESSION['nivel'] == "admin")) {
                    exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin.php?pagina=postagens');
                    exit();
                }
                exibirMensagemEredirecionar(MSG_ERRO, '../views/suas_postagens.php');
            }
        } else {
            exibirMensagemEredirecionar("Tipo de operação inválido", '../views/suas_postagens.php');
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", '../views/suas_postagens.php');
    }
} else {
    exibirMensagemEredirecionar("Não é uma requisição post", '../views/suas_postagens.php');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
