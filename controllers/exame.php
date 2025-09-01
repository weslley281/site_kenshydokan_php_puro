<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/exameGraduacaoModel.php";
    include_once "../repositorios/exameGraduacaoRepositorio.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    $dataCriacao = date("Y-m-d H:i:s");
    $dataMudanca = date("Y-m-d H:i:s");

    $exameRepositorio = new ExameGraduacaoRepositorio();

    $exameModel = new ExameGraduacaoModel(
        null,
        $_POST["nome"],
        $_POST["documento"],
        $_POST["graduacao_atual"],
        $_POST["graduacao_pretendida"],
        $_SESSION["id_usuario"],
        $_POST["email"],
        $dataCriacao,
        $dataMudanca,
        'aguardando'
    );

    if ($exameRepositorio->criar($exameModel)) {
        exibirMensagemEredirecionar(MSG_SUCESSO, '../views/perfil/perfil.php');
    } else {
        exibirMensagemEredirecionar(MSG_ERRO, '../views/perfil/exame_graduacao.php');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../views/perfil/perfil.php');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
