<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\LutaModel;
use App\Models\SumulaKumiteModel;
use App\Models\TatameModel;
use App\Models\SumulaTameshiwariModel;
use App\Services\KumiteEngineService;
use App\Models\AuditoriaModel;

/**
 * Controlador da Súmula Digital de Kumite em Tempo Real (AJAX)
 */
class SumulaKumiteController extends Controller
{
    public function show(string $idLuta): void
    {
        $this->requireAuth();
        $idLuta = (int)$idLuta;

        $luta = LutaModel::find($idLuta);
        if (!$luta) {
            Session::flash('error', 'Luta não encontrada.');
            $this->redirect('/campeonatos');
        }

        // Garante que o registro da súmula existe
        $idSumula = SumulaKumiteModel::createInitial($idLuta);
        $sumula = SumulaKumiteModel::byLuta($idLuta);
        $eventos = SumulaKumiteModel::getEvents($idLuta);

        // Busca histórico de Tameshiwari dos dois atletas para subsidiar o Hantei
        $tameshiwariAka = 0;
        $tameshiwariShiro = 0;
        if (!empty($luta['id_inscricao_aka'])) {
            $inscAka = \App\Models\InscricaoModel::find((int)$luta['id_inscricao_aka']);
            if ($inscAka) {
                $tameshiwariAka = SumulaTameshiwariModel::getTotalQuebradoPorFiliado((int)$luta['id_campeonato'], (int)$inscAka['id_filiado']);
            }
        }
        if (!empty($luta['id_inscricao_shiro'])) {
            $inscShiro = \App\Models\InscricaoModel::find((int)$luta['id_inscricao_shiro']);
            if ($inscShiro) {
                $tameshiwariShiro = SumulaTameshiwariModel::getTotalQuebradoPorFiliado((int)$luta['id_campeonato'], (int)$inscShiro['id_filiado']);
            }
        }

        $this->render('sumulas/kumite', [
            'title' => "Luta #{$luta['numero_luta']} | Súmula Digital",
            'luta' => $luta,
            'sumula' => $sumula,
            'eventos' => $eventos,
            'tameshiwariAka' => $tameshiwariAka,
            'tameshiwariShiro' => $tameshiwariShiro
        ], 'layouts/tatame');
    }

    public function start(string $idLuta): void
    {
        $this->requireAuth();
        $idLuta = (int)$idLuta;

        $luta = LutaModel::find($idLuta);
        if ($luta) {
            LutaModel::updateStatus($idLuta, 'em_andamento');
            if ($luta['id_tatame']) {
                TatameModel::updateStatus((int)$luta['id_tatame'], 'em_luta', $idLuta, Session::userId());
            }
            SumulaKumiteModel::recordEvent([
                'id_luta' => $idLuta,
                'segundo_luta' => 0,
                'fase' => 'regulamentar',
                'lado' => 'geral',
                'tipo_evento' => 'cronometro',
                'pontos_atribuidos' => 0.0,
                'descricao' => 'Combate iniciado (Hajime). Cronômetro em execução.',
                'id_usuario_operador' => Session::userId() ?? 1
            ]);
        }

        $this->json(['success' => true]);
    }

    public function recordEvent(string $idLuta): void
    {
        $this->requireAuth();
        $idLuta = (int)$idLuta;

        $luta = LutaModel::find($idLuta);
        $sumula = SumulaKumiteModel::byLuta($idLuta);

        if (!$luta || !$sumula) {
            $this->json(['success' => false, 'error' => 'Luta ou súmula não encontrada.'], 404);
        }

        $tipo = $this->post('tipo', ''); // wazaari, ippon, shido, chui, hansoku_make, saida, tempo, golden_score
        $lado = $this->post('lado', 'aka'); // aka, shiro, geral
        $segundoLuta = (int)$this->post('segundo_luta', 0);
        $descricao = trim($this->post('descricao', ''));
        $pontosAtribuidos = 0.0;
        $fase = !empty($sumula['golden_score_ativo']) ? 'golden_score' : 'regulamentar';

        // Atualização do tempo corrido
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
                $resChui = KumiteEngineService::applyChui($lado, $sumula);
                if ($resChui['converteu_ponto']) {
                    $oponente = ($lado === 'aka') ? 'shiro' : 'aka';
                    $descricao = "2º Chui aplicado ao lado " . strtoupper($lado) . " -> Convertido em 0.5 ponto (Waza-ari) para o adversário " . strtoupper($oponente);
                    $pontosAtribuidos = 0.5;
                } else {
                    $descricao = "Chui (Advertência oficial) aplicada ao lado " . strtoupper($lado);
                }
                break;

            case 'saida':
                $sumula["saidas_{$lado}"] = (int)$sumula["saidas_{$lado}"] + 1;
                if ($sumula["saidas_{$lado}"] % 2 === 0) {
                    $oponente = ($lado === 'aka') ? 'shiro' : 'aka';
                    $sumula["wazaari_{$oponente}"] = (int)$sumula["wazaari_{$oponente}"] + 1;
                    $sumula["pontos_{$oponente}"] = (float)$sumula["pontos_{$oponente}"] + 0.5;
                    $descricao = "2ª Saída de área consecutiva do lado " . strtoupper($lado) . " -> Waza-ari concedido ao oponente " . strtoupper($oponente);
                    $pontosAtribuidos = 0.5;
                } else {
                    $descricao = "Saída da área de combate registrada para o lado " . strtoupper($lado);
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
                // Apenas atualização periódica do cronômetro
                break;
        }

        // Grava o evento na trilha cronológica se não for apenas sincronização de tempo
        if ($tipo !== 'tempo') {
            SumulaKumiteModel::recordEvent([
                'id_luta' => $idLuta,
                'segundo_luta' => $segundoLuta,
                'fase' => $fase,
                'lado' => $lado,
                'tipo_evento' => in_array($tipo, ['wazaari', 'ippon']) ? 'ponto' : (in_array($tipo, ['shido', 'chui', 'hansoku_make', 'saida']) ? 'penalidade' : 'cronometro'),
                'pontos_atribuidos' => $pontosAtribuidos,
                'descricao' => $descricao,
                'id_usuario_operador' => Session::userId() ?? 1
            ]);
        }

        // Avalia se o combate encerrou por superioridade (6 Waza-ari, Golden Score ou Hansoku-make)
        $avaliacao = KumiteEngineService::evaluateState($sumula);
        if ($avaliacao['finalizada']) {
            $ladoVencedor = $avaliacao['vencedor_lado'];
            $idVencedor = ($ladoVencedor === 'aka') ? (int)$luta['id_inscricao_aka'] : (int)$luta['id_inscricao_shiro'];
            $sumula['vencedor_id'] = $idVencedor;
            $sumula['motivo_encerramento'] = $avaliacao['motivo'];

            // Conclui a luta no sistema e avança na chave
            LutaModel::setWinner($idLuta, $idVencedor, $avaliacao['forma_vitoria']);
            if ($luta['id_tatame']) {
                TatameModel::updateStatus((int)$luta['id_tatame'], 'disponivel', null);
            }
        }

        // Salva estado da súmula no banco
        SumulaKumiteModel::updateScore((int)$sumula['id_sumula'], $sumula);

        $this->json([
            'success' => true,
            'sumula' => $sumula,
            'avaliacao' => $avaliacao,
            'eventos' => SumulaKumiteModel::getEvents($idLuta)
        ]);
    }

    public function finish(string $idLuta): void
    {
        $this->requireAuth();
        $this->validateCsrf();
        $idLuta = (int)$idLuta;

        $vencedorLado = $this->post('vencedor_lado', ''); // aka ou shiro
        $formaVitoria = $this->post('forma_vitoria', 'pontos');
        $motivo = trim($this->post('motivo', ''));

        $luta = LutaModel::find($idLuta);
        $sumula = SumulaKumiteModel::byLuta($idLuta);

        if (!$luta || !$sumula) {
            $this->redirect('/campeonatos');
        }

        $idVencedor = ($vencedorLado === 'aka') ? (int)$luta['id_inscricao_aka'] : (int)$luta['id_inscricao_shiro'];

        $sumula['vencedor_id'] = $idVencedor;
        $sumula['motivo_encerramento'] = $motivo ?: "Decisão oficial da arbitragem ({$formaVitoria})";
        SumulaKumiteModel::updateScore((int)$sumula['id_sumula'], $sumula);

        LutaModel::setWinner($idLuta, $idVencedor, $formaVitoria);

        if ($luta['id_tatame']) {
            TatameModel::updateStatus((int)$luta['id_tatame'], 'disponivel', null);
        }

        AuditoriaModel::log('FINALIZAR_LUTA_KUMITE', 'campeonato_lutas', $idLuta, null, [
            'vencedor_id' => $idVencedor,
            'forma_vitoria' => $formaVitoria,
            'motivo' => $motivo
        ], null, (int)$luta['id_campeonato']);

        Session::flash('success', "Combate #{$luta['numero_luta']} finalizado com sucesso!");
        $this->redirect("/tatames/" . ($luta['id_tatame'] ?: 1) . "/painel");
    }
}
