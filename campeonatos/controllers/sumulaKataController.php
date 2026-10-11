<?php
/**
 * Controlador de Súmula de Kata
 * Padrão IDÊNTICO ao site principal (controllers/sumulaKataController.php)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../models/sumulaKataModel.php";
include_once __DIR__ . "/../models/auditoriaModel.php";
include_once __DIR__ . "/alertHelper.php";

if (!isset($_SESSION["id_usuario"])) {
    exibirMensagemEredirecionar("Acesso restrito.", "../views/login.php");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $kataModel = new SumulaKata();
    $tipo = $_POST["tipo"] ?? "";
    $idCampeonato = (int)($_POST["id_campeonato"] ?? 0);
    $idCategoria = (int)($_POST["id_categoria"] ?? 0);
    $idInscricao = (int)($_POST["id_inscricao"] ?? 0);

    if ($tipo === "lancar_notas") {
        $kataNome = trim($_POST["kata_nome"] ?? "");
        $notas = $_POST["notas"] ?? [];
        $arbitros = $_POST["arbitros"] ?? [];

        if (empty($notas) || $idInscricao <= 0) {
            exibirMensagemEredirecionar("Informe o atleta e as notas dos árbitros.", "../views/sumula_kata.php?id=" . $idCategoria);
        }

        foreach ($notas as $ordem => $notaVal) {
            $notaNum = (float)$notaVal;
            $arbNome = trim($arbitros[$ordem] ?? "Árbitro {$ordem}");
            if ($notaNum >= 0.0 && $notaNum <= 10.0) {
                $kataModel->gravarNota([
                    'id_campeonato' => $idCampeonato,
                    'id_categoria' => $idCategoria,
                    'id_inscricao' => $idInscricao,
                    'rodada' => 1,
                    'is_desempate' => 0,
                    'kata_nome' => $kataNome,
                    'arbitro_ordem' => (int)$ordem,
                    'arbitro_nome' => $arbNome,
                    'nota' => $notaNum,
                    'observacao' => null
                ]);
            }
        }

        Auditoria::log("LANCAR_NOTAS_KATA", "campeonato_notas_kata", $idInscricao, null, [
            'kata' => $kataNome,
            'notas' => $notas
        ], null, $idCampeonato);

        exibirMensagemEredirecionar("Notas de Kata registradas e média calculada com sucesso!", "../views/sumula_kata.php?id=" . $idCategoria);

    } elseif ($tipo === "desempate") {
        $kataNome = trim($_POST["kata_nome"] ?? "");
        $notas = $_POST["notas"] ?? [];
        $arbitros = $_POST["arbitros"] ?? [];

        foreach ($notas as $ordem => $notaVal) {
            $notaNum = (float)$notaVal;
            $arbNome = trim($arbitros[$ordem] ?? "Árbitro {$ordem}");
            $kataModel->gravarNota([
                'id_campeonato' => $idCampeonato,
                'id_categoria' => $idCategoria,
                'id_inscricao' => $idInscricao,
                'rodada' => 2,
                'is_desempate' => 1,
                'kata_nome' => $kataNome,
                'arbitro_ordem' => (int)$ordem,
                'arbitro_nome' => $arbNome,
                'nota' => $notaNum,
                'observacao' => 'Desempate oficial com novo Kata'
            ]);
        }

        Auditoria::log("DESEMPATE_KATA", "campeonato_notas_kata", $idInscricao, null, ['notas' => $notas], "Novo Kata de desempate", $idCampeonato);
        exibirMensagemEredirecionar("Notas de desempate de Kata registradas com sucesso!", "../views/sumula_kata.php?id=" . $idCategoria);
    }
}
