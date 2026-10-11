<?php
/**
 * Controlador de Tatames e Áreas de Combate
 * Padrão IDÊNTICO ao site principal (controllers/tatameController.php)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../models/tatameModel.php";
include_once __DIR__ . "/../models/auditoriaModel.php";
include_once __DIR__ . "/alertHelper.php";

if (!isset($_SESSION["id_usuario"])) {
    exibirMensagemEredirecionar("Acesso restrito.", "../views/login.php");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tatameModel = new Tatame();
    $tipo = $_POST["tipo"] ?? "";
    $idCampeonato = (int)($_POST["id_campeonato"] ?? 0);

    if ($tipo === "criar_tatame") {
        $numero = (int)($_POST["numero"] ?? 1);
        $nome = trim($_POST["nome"] ?? "Tatame " . $numero);

        $id = $tatameModel->criarTatame([
            'id_campeonato' => $idCampeonato,
            'numero' => $numero,
            'nome' => $nome,
            'status' => 'disponivel'
        ]);

        if ($id > 0) {
            Auditoria::log("CRIAR_TATAME", "campeonato_tatames", $id, null, ['nome' => $nome], null, $idCampeonato);
            exibirMensagemEredirecionar("Tatame '{$nome}' cadastrado com sucesso!", "../views/tatames.php?id=" . $idCampeonato);
        } else {
            exibirMensagemEredirecionar("Erro ao cadastrar tatame.", "../views/tatames.php?id=" . $idCampeonato);
        }

    } elseif ($tipo === "mudar_status") {
        $idTatame = (int)($_POST["id_tatame"] ?? 0);
        $novoStatus = $_POST["status"] ?? "disponivel";
        $lutaId = !empty($_POST["luta_id"]) ? (int)$_POST["luta_id"] : null;
        $operadorId = $_SESSION["id_usuario"] ?? null;

        if ($tatameModel->atualizarStatus($idTatame, $novoStatus, $lutaId, $operadorId)) {
            exibirMensagemEredirecionar("Status do tatame atualizado.", "../views/painel_tatame.php?id=" . $idTatame);
        } else {
            exibirMensagemEredirecionar("Erro ao atualizar status do tatame.", "../views/painel_tatame.php?id=" . $idTatame);
        }
    }
}
