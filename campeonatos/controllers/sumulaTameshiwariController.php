<?php
/**
 * Controlador de Súmula de Tameshiwari (Quebramento)
 * Padrão IDÊNTICO ao site principal (controllers/sumulaTameshiwariController.php)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../models/sumulaTameshiwariModel.php";
include_once __DIR__ . "/../models/auditoriaModel.php";
include_once __DIR__ . "/alertHelper.php";

if (!isset($_SESSION["id_usuario"])) {
    exibirMensagemEredirecionar("Acesso restrito.", "../views/login.php");
}

include_once __DIR__ . "/csrfHelper.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrfExigir('../views/inicio.php');
    exigirPerfil(['mesario']);
    $tameshiwariModel = new SumulaTameshiwari();
    $tipo = $_POST["tipo"] ?? "";
    $idCampeonato = (int)($_POST["id_campeonato"] ?? 0);
    $idCategoria = (int)($_POST["id_categoria"] ?? 0);

    if ($tipo === "registrar_tameshiwari") {
        $idInscricao = (int)($_POST["id_inscricao"] ?? 0);
        $tecnica = $_POST["tecnica"] ?? "seiken";
        $material = $_POST["material"] ?? "madeira";
        $qtdDeclarada = (int)($_POST["qtd_declarada"] ?? 1);
        $qtdQuebrada = (int)($_POST["qtd_quebrada"] ?? 0);
        $resultado = $_POST["resultado"] ?? "falha_ou_parcial";
        $tempoSegundos = (int)($_POST["tempo_segundos"] ?? 0);
        $arbitro = trim($_POST["arbitro_responsavel"] ?? "Árbitro de Tameshiwari");
        $obs = trim($_POST["observacoes"] ?? "");

        // Regra Oficial: falha ou quebra parcial resulta em nota zero
        if ($resultado === "falha_ou_parcial") {
            $qtdQuebrada = 0;
        }

        $id = $tameshiwariModel->gravarTentativa([
            'id_campeonato' => $idCampeonato,
            'id_categoria' => $idCategoria,
            'id_inscricao' => $idInscricao,
            'tecnica' => $tecnica,
            'material' => $material,
            'qtd_declarada' => $qtdDeclarada,
            'qtd_quebrada' => $qtdQuebrada,
            'resultado' => $resultado,
            'tempo_segundos' => $tempoSegundos,
            'arbitro_responsavel' => $arbitro,
            'observacoes' => $obs ?: null
        ]);

        Auditoria::log("REGISTRAR_TAMESHIWARI", "campeonato_tameshiwari", $id, null, [
            'tecnica' => $tecnica,
            'declaradas' => $qtdDeclarada,
            'quebradas' => $qtdQuebrada,
            'resultado' => $resultado
        ], null, $idCampeonato);

        exibirMensagemEredirecionar("Tentativa de Tameshiwari registrada com sucesso!", "../views/sumula_tameshiwari.php?id=" . $idCategoria);
    }
}
