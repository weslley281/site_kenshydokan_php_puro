<?php
/**
 * Controlador da Súmula Digital de Kumite em Tempo Real (AJAX e POST)
 * Padrão IDÊNTICO ao site principal (controllers/sumulaKumiteController.php)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../models/sumulaKumiteModel.php";
include_once __DIR__ . "/../models/lutaModel.php";
include_once __DIR__ . "/../models/tatameModel.php";
include_once __DIR__ . "/../models/auditoriaModel.php";
include_once __DIR__ . "/alertHelper.php";

$sumulaModel = new SumulaKumite();
$lutaModel = new Luta();
$tatameModel = new Tatame();

// Endpoint AJAX: Eventos em tempo real da súmula
if (isset($_GET['acao']) && $_GET['acao'] === 'evento') {
    header('Content-Type: application/json; charset=utf-8');

    $idLuta = (int)($_POST['id_luta'] ?? 0);
    $luta = $lutaModel->buscarPorId($idLuta);
    $sumula = $sumulaModel->buscarPorLuta($idLuta);

    if (!$luta || !$sumula) {
        echo json_encode(['success' => false, 'error' => 'Luta ou súmula não encontrada.']);
        exit;
    }

    $tipo = $_POST['tipo'] ?? '';
    $lado = $_POST['lado'] ?? 'aka'; // aka, shiro, geral
    $segundoLuta = (int)($_POST['segundo_luta'] ?? 0);
    $descricao = trim($_POST['descricao'] ?? '');
    $pontosAtribuidos = 0.0;
    $fase = !empty($sumula['golden_score_ativo']) ? 'golden_score' : 'regulamentar';

    $sumula['tempo_decorrido_segundos'] = $segundoLuta;

    switch ($tipo) {
        case 'wazaari':
            $sumula["wazaari_{$lado}"] = (int)$sumula["wazaari_{$lado}"] + 1;
            $sumula["pontos_{$lado}"] = (float)$sumula["pontos_{$lado}"] + 0.5;
            $pontosAtribuidos = 0.5;
            $descricao = $descricao ?: "Waza-ari (+0.5) concedido ao lado " . strtoupper($lado);
            break;

        case 'ippon':
            $sumula["ippon_{$lado}"] = 1;
            $sumula["pontos_{$lado}"] = (float)$sumula["pontos_{$lado}"] + 1.0;
            $pontosAtribuidos = 1.0;
            $descricao = $descricao ?: "Ippon (+1.0) concedido ao lado " . strtoupper($lado);
            break;

        case 'shido':
            $sumula["shido_{$lado}"] = (int)$sumula["shido_{$lado}"] + 1;
            $descricao = $descricao ?: "Shido (Advertência simples) aplicada ao lado " . strtoupper($lado);
            break;

        case 'chui':
            $sumula["chui_{$lado}"] = (int)$sumula["chui_{$lado}"] + 1;
            // Regra Oficial: 2º Chui converte em 0.5 ponto (Waza-ari) ao adversário!
            if ($sumula["chui_{$lado}"] % 2 === 0) {
                $oponente = ($lado === 'aka') ? 'shiro' : 'aka';
                $sumula["wazaari_{$oponente}"] = (int)$sumula["wazaari_{$oponente}"] + 1;
                $sumula["pontos_{$oponente}"] = (float)$sumula["pontos_{$oponente}"] + 0.5;
                $descricao = "2º Chui aplicado ao lado " . strtoupper($lado) . " -> Convertido em 0.5 ponto (Waza-ari) para o adversário " . strtoupper($oponente);
                $pontosAtribuidos = 0.5;
            } else {
                $descricao = "Chui (Advertência oficial) aplicada ao lado " . strtoupper($lado);
            }
            break;

        case 'saida':
            $sumula["saidas_{$lado}"] = (int)$sumula["saidas_{$lado}"] + 1;
            // Regra Oficial: 2ª Saída de área consecutiva dá 0.5 pt ao adversário
            if ($sumula["saidas_{$lado}"] % 2 === 0) {
                $oponente = ($lado === 'aka') ? 'shiro' : 'aka';
                $sumula["wazaari_{$oponente}"] = (int)$sumula["wazaari_{$oponente}"] + 1;
                $sumula["pontos_{$oponente}"] = (float)$sumula["pontos_{$oponente}"] + 0.5;
                $descricao = "2ª Saída de área (Jogai) do lado " . strtoupper($lado) . " -> Concedido 0.5 ponto (Waza-ari) ao oponente " . strtoupper($oponente);
                $pontosAtribuidos = 0.5;
            } else {
                $descricao = "Saída da área de combate (Jogai) registrada para o lado " . strtoupper($lado);
            }
            break;

        case 'hansoku_make':
            $sumula["hansoku_make_{$lado}"] = 1;
            $descricao = "Desclassificação imediata (Hansoku-make) aplicada ao lado " . strtoupper($lado);
            break;

        case 'golden_score':
            $sumula['golden_score_ativo'] = 1;
            $fase = 'golden_score';
            $descricao = "Início da prorrogação em Golden Score (Ponto de Ouro).";
            break;

        case 'tempo':
            // Sincronização periódica do cronômetro
            break;
    }

    if ($tipo !== 'tempo') {
        $tipoEv = in_array($tipo, ['wazaari', 'ippon']) ? 'ponto' : (in_array($tipo, ['shido', 'chui', 'hansoku_make', 'saida']) ? 'penalidade' : 'cronometro');
        $sumulaModel->gravarEvento([
            'id_luta' => $idLuta,
            'segundo_luta' => $segundoLuta,
            'fase' => $fase,
            'lado' => $lado,
            'tipo_evento' => $tipoEv,
            'pontos_atribuidos' => $pontosAtribuidos,
            'descricao' => $descricao,
            'id_usuario_operador' => $_SESSION['id_usuario'] ?? 1
        ]);
    }

    // Avaliação do motor de regras esportivas da Kenshydokan
    $avaliacao = $sumulaModel->avaliarRegrasKumite($sumula);
    if ($avaliacao['finalizada']) {
        $ladoVenc = $avaliacao['vencedor_lado'];
        $idVenc = ($ladoVenc === 'aka') ? (int)$luta['id_inscricao_aka'] : (int)$luta['id_inscricao_shiro'];
        $sumula['vencedor_id'] = $idVenc;
        $sumula['motivo_encerramento'] = $avaliacao['motivo'];

        $lutaModel->definirVencedor($idLuta, $idVenc, $avaliacao['forma_vitoria']);
        if (!empty($luta['id_tatame'])) {
            $tatameModel->atualizarStatus((int)$luta['id_tatame'], 'disponivel', null);
        }
    }

    $sumulaModel->atualizarPlacar((int)$sumula['id_sumula'], $sumula);

    echo json_encode([
        'success' => true,
        'sumula' => $sumula,
        'avaliacao' => $avaliacao,
        'eventos' => $sumulaModel->buscarEventos($idLuta)
    ]);
    exit;
}

// Finalização manual ou decisão oficial dos árbitros (Hantei / Pontos)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tipo = $_POST["tipo"] ?? "";
    $idLuta = (int)($_POST["id_luta"] ?? 0);

    if ($tipo === "finalizar_luta") {
        $luta = $lutaModel->buscarPorId($idLuta);
        $sumula = $sumulaModel->buscarPorLuta($idLuta);

        if (!$luta || !$sumula) {
            exibirMensagemEredirecionar("Luta não encontrada.", "../views/campeonatos.php");
        }

        $vencedorLado = $_POST['vencedor_lado'] ?? 'aka';
        $formaVitoria = $_POST['forma_vitoria'] ?? 'pontos';
        $motivo = trim($_POST['motivo'] ?? "Decisão oficial da arbitragem ({$formaVitoria})");

        $idVencedor = ($vencedorLado === 'aka') ? (int)$luta['id_inscricao_aka'] : (int)$luta['id_inscricao_shiro'];

        $sumula['vencedor_id'] = $idVencedor;
        $sumula['motivo_encerramento'] = $motivo;
        $sumulaModel->atualizarPlacar((int)$sumula['id_sumula'], $sumula);

        $lutaModel->definirVencedor($idLuta, $idVencedor, $formaVitoria);

        if (!empty($luta['id_tatame'])) {
            $tatameModel->atualizarStatus((int)$luta['id_tatame'], 'disponivel', null);
        }

        Auditoria::log("FINALIZAR_LUTA_KUMITE", "campeonato_lutas", $idLuta, null, [
            'vencedor_id' => $idVencedor,
            'forma_vitoria' => $formaVitoria,
            'motivo' => $motivo
        ], null, (int)$luta['id_campeonato']);

        exibirMensagemEredirecionar("Combate #{$luta['numero_luta']} finalizado com sucesso!", "../views/painel_tatame.php?id=" . ($luta['id_tatame'] ?? 1));
    }
}
