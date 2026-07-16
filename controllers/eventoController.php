<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Apenas administradores podem fazer modificações de eventos
if (!isset($_SESSION["id_usuario"]) || $_SESSION['nivel'] !== "admin") {
    echo "<script language='javascript'>window.alert('Acesso negado.'); </script>";
    echo "<script language='javascript'>window.location='../views/admin/index.php'; </script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/eventoModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro na operação. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $eventoModel = new Evento();

        if ($_POST["tipo"] == "criar_evento") {
            $titulo = $_POST["titulo"];
            $descricao = $_POST["descricao"];
            $data_evento = $_POST["data_evento"];
            $tipo_evento = $_POST["tipo_evento"];
            $endereco = ($tipo_evento === 'presencial') ? $_POST["endereco"] : null;
            $link_assistir = ($tipo_evento === 'online') ? $_POST["link_assistir"] : null;

            $novoEvento = new Evento(null, $titulo, $descricao, $data_evento, $tipo_evento, $endereco, $link_assistir);

            if ($eventoModel->criarEvento($novoEvento)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=eventos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=eventos');
            }
        } elseif ($_POST["tipo"] == "editar_evento") {
            $id_evento = $_POST["id_evento"];
            $titulo = $_POST["titulo"];
            $descricao = $_POST["descricao"];
            $data_evento = $_POST["data_evento"];
            $tipo_evento = $_POST["tipo_evento"];
            $endereco = ($tipo_evento === 'presencial') ? $_POST["endereco"] : null;
            $link_assistir = ($tipo_evento === 'online') ? $_POST["link_assistir"] : null;

            if ($eventoModel->editarEvento($id_evento, $titulo, $descricao, $data_evento, $tipo_evento, $endereco, $link_assistir)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=eventos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=eventos');
            }
        } elseif ($_POST["tipo"] == "excluir_evento") {
            $id_evento = $_POST["id_evento"];

            if ($eventoModel->excluirEvento($id_evento)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=eventos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=eventos');
            }
        }
    } else {
        exibirMensagemEredirecionar("Tipo de requisição inválido.", '../views/admin/index.php?pagina=eventos');
    }
} else {
    exibirMensagemEredirecionar("Método de requisição inválido.", '../views/admin/index.php?pagina=eventos');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
