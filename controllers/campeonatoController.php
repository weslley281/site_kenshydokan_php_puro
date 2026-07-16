<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Apenas administradores podem fazer modificacoes de campeonatos
if (!isset($_SESSION["id_usuario"]) || $_SESSION['nivel'] !== "admin") {
    echo "<script language='javascript'>window.alert('Acesso negado.'); </script>";
    echo "<script language='javascript'>window.location='../views/admin/index.php'; </script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/campeonatoModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro na operacao. Tente novamente.");
    define("MSG_SUCESSO", "Operacao realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $campeonatoModel = new Campeonato();

        if ($_POST["tipo"] == "criar_campeonato") {
            $titulo = $_POST["titulo"];
            $subtitulo = $_POST["subtitulo"];
            $endereco = $_POST["endereco"];
            $ativo = $_POST["ativo"];
            $dataCriacao = $_POST["dataCriacao"];
            $tipo_campeonato = $_POST["tipo_campeonato"]; // "interno" ou "externo"
            $link_externo = ($tipo_campeonato === 'externo') ? $_POST["link_externo"] : null;

            $novoCamp = new Campeonato(null, $titulo, $subtitulo, $endereco, $ativo, $dataCriacao, null, $tipo_campeonato, $link_externo);

            if ($campeonatoModel->criarCampeonato($novoCamp)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=campeonatos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=campeonatos');
            }
        } elseif ($_POST["tipo"] == "editar_campeonato") {
            $id_campeonato = $_POST["id_campeonato"];
            $titulo = $_POST["titulo"];
            $subtitulo = $_POST["subtitulo"];
            $endereco = $_POST["endereco"];
            $ativo = $_POST["ativo"];
            $dataCriacao = $_POST["dataCriacao"];
            $tipo_campeonato = $_POST["tipo_campeonato"]; // "interno" ou "externo"
            $link_externo = ($tipo_campeonato === 'externo') ? $_POST["link_externo"] : null;

            if ($campeonatoModel->editarCampeonato($id_campeonato, $titulo, $subtitulo, $endereco, $ativo, $dataCriacao, $tipo_campeonato, $link_externo)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=campeonatos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=campeonatos');
            }
        } elseif ($_POST["tipo"] == "excluir_campeonato") {
            $id_campeonato = $_POST["id_campeonato"];

            if ($campeonatoModel->excluirCampeonato($id_campeonato)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=campeonatos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=campeonatos');
            }
        }
    } else {
        exibirMensagemEredirecionar("Tipo de requisicao invalido.", '../views/admin/index.php?pagina=campeonatos');
    }
} else {
    exibirMensagemEredirecionar("Metodo de requisicao invalido.", '../views/admin/index.php?pagina=campeonatos');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
