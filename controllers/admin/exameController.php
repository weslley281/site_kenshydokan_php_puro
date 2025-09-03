<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../../models/exameGraduacaoModel.php";
    include_once "../../repositorios/exameGraduacaoRepositorio.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    $dataMudanca = date("Y-m-d H:i:s");

    $exameRepositorio = new ExameGraduacaoRepositorio();

    $exameModel = new ExameGraduacaoModel(
        $_POST["id"],
        $_POST["nome"],
        $_POST["documento"],
        $_POST["id_graduacao_atual"],
        $_POST["id_graduacao_pretendida"],
        null, // id_professor is not editable
        null, // email is not editable
        null, // dataCriacao is not editable
        $dataMudanca,
        $_POST["situacao"]
    );

    if ($exameRepositorio->editar($exameModel)) {
        exibirMensagemEredirecionar(MSG_SUCESSO, '../../views/admin/index.php?pagina=exames');
    } else {
        exibirMensagemEredirecionar(MSG_ERRO, '../../views/admin/index.php?pagina=exames');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../../views/admin/index.php?pagina=exames');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
