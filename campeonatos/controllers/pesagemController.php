<?php
/**
 * Controlador da Pesagem Oficial
 * Padrão IDÊNTICO ao site principal (controllers/pesagemController.php)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../models/pesagemModel.php";
include_once __DIR__ . "/../models/inscricaoModel.php";
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/auditoriaModel.php";
include_once __DIR__ . "/alertHelper.php";

if (!isset($_SESSION["id_usuario"])) {
    exibirMensagemEredirecionar("Acesso restrito.", "../views/login.php");
}

include_once __DIR__ . "/csrfHelper.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrfExigir('../views/inicio.php');
    exigirPerfil(['mesario']);
    $pesagemModel = new Pesagem();
    $inscricaoModel = new Inscricao();
    $categoriaModel = new Categoria();

    $tipo = $_POST["tipo"] ?? "";
    $idCampeonato = (int)($_POST["id_campeonato"] ?? 0);

    if ($tipo === "registrar_pesagem") {
        $idInscricao = (int)($_POST["id_inscricao"] ?? 0);
        $pesoAferido = (float)($_POST["peso_aferido"] ?? 0.0);
        $observacoes = trim($_POST["observacoes"] ?? "");
        $operadorId = $_SESSION["id_usuario"] ?? 1;

        $inscricao = $inscricaoModel->buscarPorId($idInscricao);
        if (!$inscricao) {
            exibirMensagemEredirecionar("Inscrição não localizada.", "../views/pesagem.php?id=" . $idCampeonato);
        }

        $categoria = $categoriaModel->buscarPorId((int)$inscricao['id_categoria']);
        $tentativa = $pesagemModel->contarTentativas($idInscricao) + 1;

        // Validação oficial do peso
        $validacao = $pesagemModel->validarPeso($categoria, $pesoAferido);
        $resultado = $validacao['aprovado'] ? 'aprovado' : 'reprovado';

        $idPesagem = $pesagemModel->registrarPesagem([
            'id_inscricao' => $idInscricao,
            'id_campeonato' => $idCampeonato,
            'peso_aferido' => $pesoAferido,
            'resultado' => $resultado,
            'tentativa' => $tentativa,
            'operador_id' => $operadorId,
            'observacoes' => $observacoes ?: null
        ]);

        // Atualiza status de elegibilidade da inscrição
        $statusInscricao = $validacao['aprovado'] ? 'aprovada' : 'reprovada';
        $motivo = $validacao['aprovado'] ? null : $validacao['motivo'];
        $inscricaoModel->atualizarStatus($idInscricao, $statusInscricao, $motivo);

        Auditoria::log("REGISTRAR_PESAGEM", "campeonato_pesagens", $idPesagem, null, [
            'atleta' => $inscricao['nome_atleta_snapshot'],
            'peso' => $pesoAferido,
            'resultado' => $resultado,
            'tentativa' => $tentativa
        ], $motivo, $idCampeonato);

        if ($validacao['aprovado']) {
            exibirMensagemEredirecionar("Pesagem APROVADA ({$pesoAferido} kg). Atleta liberado para o chaveamento!", "../views/pesagem.php?id=" . $idCampeonato);
        } else {
            exibirMensagemEredirecionar("Pesagem REPROVADA ({$pesoAferido} kg). {$validacao['motivo']}", "../views/pesagem.php?id=" . $idCampeonato);
        }

    } elseif ($tipo === "remanejar_categoria") {
        exigirPerfil([]); // somente administrador
        $idInscricao = (int)($_POST["id_inscricao"] ?? 0);
        $novaCategoriaId = (int)($_POST["nova_categoria_id"] ?? 0);
        $justificativa = trim($_POST["justificativa"] ?? "");

        if ($novaCategoriaId <= 0 || empty($justificativa)) {
            exibirMensagemEredirecionar("Selecione a nova categoria e preencha a justificativa.", "../views/pesagem.php?id=" . $idCampeonato);
        }

        $inscricao = $inscricaoModel->buscarPorId($idInscricao);
        if (!$inscricao) {
            exibirMensagemEredirecionar("Inscrição não encontrada.", "../views/pesagem.php?id=" . $idCampeonato);
        }

        $catAntiga = $inscricao['id_categoria'];
        $inscricaoModel->atualizarCategoria($idInscricao, $novaCategoriaId);
        $inscricaoModel->atualizarStatus($idInscricao, 'aprovada', "Remanejado para nova categoria: " . $justificativa);

        Auditoria::log("REMANEJAR_CATEGORIA_PESO", "campeonato_inscricoes", $idInscricao, [
            'id_categoria_antiga' => $catAntiga
        ], [
            'id_categoria_nova' => $novaCategoriaId
        ], $justificativa, $idCampeonato);

        exibirMensagemEredirecionar("Atleta remanejado com sucesso e aprovado na nova categoria!", "../views/pesagem.php?id=" . $idCampeonato);
    }
}
