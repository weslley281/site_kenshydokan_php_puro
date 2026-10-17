<?php
/**
 * Modelo de Súmula Digital de Kumite (campeonato_sumula_kumite e campeonato_eventos_kumite)
 * Regras Oficiais Kenshydokan Jutsu (6 Waza-ari = 3.0 pts, 2 Chui = 0.5 pt, Golden Score, etc.)
 * Padrão IDÊNTICO ao site principal (models/sumulaKumiteModel.php)
 */

include_once __DIR__ . "/../db/conexao.php";

class SumulaKumite {

    private $conexao;

    public function __construct() {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function buscarOuCriarInicial(int $idLuta): array {
        $existente = $this->buscarPorLuta($idLuta);
        if ($existente) {
            return $existente;
        }

        $stmt = $this->conexao->prepare("INSERT INTO campeonato_sumula_kumite (id_luta) VALUES (?)");
        $stmt->bind_param("i", $idLuta);
        $stmt->execute();
        $stmt->close();

        return $this->buscarPorLuta($idLuta);
    }

    public function buscarPorLuta(int $idLuta): ?array {
        $stmt = $this->conexao->prepare("SELECT * FROM campeonato_sumula_kumite WHERE id_luta = ?");
        $stmt->bind_param("i", $idLuta);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function atualizarPlacar(int $idSumula, array $dados): bool {
        $sql = "UPDATE campeonato_sumula_kumite SET 
            tempo_decorrido_segundos = ?, pontos_aka = ?, pontos_shiro = ?,
            wazaari_aka = ?, wazaari_shiro = ?, ippon_aka = ?, ippon_shiro = ?,
            shido_aka = ?, shido_shiro = ?, chui_aka = ?, chui_shiro = ?,
            saidas_aka = ?, saidas_shiro = ?, hansoku_make_aka = ?, hansoku_make_shiro = ?,
            golden_score_ativo = ?, vencedor_id = ?, motivo_encerramento = ?
            WHERE id_sumula = ?";

        $stmt = $this->conexao->prepare($sql);
        $tempo = (int)$dados['tempo_decorrido_segundos'];
        $ptAka = (float)$dados['pontos_aka'];
        $ptShiro = (float)$dados['pontos_shiro'];
        $wAka = (int)$dados['wazaari_aka'];
        $wShiro = (int)$dados['wazaari_shiro'];
        $iAka = (int)$dados['ippon_aka'];
        $iShiro = (int)$dados['ippon_shiro'];
        $sAka = (int)$dados['shido_aka'];
        $sShiro = (int)$dados['shido_shiro'];
        $cAka = (int)$dados['chui_aka'];
        $cShiro = (int)$dados['chui_shiro'];
        $saiAka = (int)$dados['saidas_aka'];
        $saiShiro = (int)$dados['saidas_shiro'];
        $hmAka = (int)$dados['hansoku_make_aka'];
        $hmShiro = (int)$dados['hansoku_make_shiro'];
        $gs = (int)$dados['golden_score_ativo'];
        $vencId = !empty($dados['vencedor_id']) ? (int)$dados['vencedor_id'] : null;

        $stmt->bind_param(
            "iddiiiiiiiiiiiiisii",
            $tempo, $ptAka, $ptShiro, $wAka, $wShiro, $iAka, $iShiro,
            $sAka, $sShiro, $cAka, $cShiro, $saiAka, $saiShiro,
            $hmAka, $hmShiro, $gs, $vencId, $dados['motivo_encerramento'], $idSumula
        );

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function gravarEvento(array $evento): int {
        $sql = "INSERT INTO campeonato_eventos_kumite (
            id_luta, segundo_luta, fase, lado, tipo_evento, pontos_atribuidos, descricao, id_usuario_operador
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexao->prepare($sql);
        $idLuta = (int)$evento['id_luta'];
        $seg = (int)$evento['segundo_luta'];
        $pts = (float)$evento['pontos_atribuidos'];
        $op = (int)$evento['id_usuario_operador'];

        $stmt->bind_param("iisssdsi", $idLuta, $seg, $evento['fase'], $evento['lado'], $evento['tipo_evento'], $pts, $evento['descricao'], $op);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    public function buscarEventos(int $idLuta): array {
        $stmt = $this->conexao->prepare("SELECT * FROM campeonato_eventos_kumite WHERE id_luta = ? ORDER BY id_evento ASC");
        $stmt->bind_param("i", $idLuta);
        $stmt->execute();
        $res = $stmt->get_result();
        $lista = [];
        while ($row = $res->fetch_assoc()) {
            $lista[] = $row;
        }
        $stmt->close();
        return $lista;
    }

    /**
     * Motor de Regras Oficiais de Kumite Kenshydokan
     */
    public function avaliarRegrasKumite(array $sumula): array {
        // A luta só termina antecipadamente quando um atleta soma 3 pontos.
        if ((float)$sumula['pontos_aka'] >= 3.0) {
            return [
                'finalizada' => true,
                'vencedor_lado' => 'aka',
                'forma_vitoria' => 'ippon',
                'motivo' => 'Waza-ari Awasete Ippon (Acúmulo de 6 Waza-ari / 3.0 pts)'
            ];
        }
        if ((float)$sumula['pontos_shiro'] >= 3.0) {
            return [
                'finalizada' => true,
                'vencedor_lado' => 'shiro',
                'forma_vitoria' => 'ippon',
                'motivo' => 'Waza-ari Awasete Ippon (Acúmulo de 6 Waza-ari / 3.0 pts)'
            ];
        }

        // Golden Score só pode começar depois do tempo regulamentar.
        if (!empty($sumula['golden_score_ativo'])) {
            if ((float)$sumula['pontos_aka'] > (float)$sumula['pontos_shiro']) {
                return [
                    'finalizada' => true,
                    'vencedor_lado' => 'aka',
                    'forma_vitoria' => 'golden_score',
                    'motivo' => 'Golden Score (Ponto de Ouro marcado)'
                ];
            }
            if ((float)$sumula['pontos_shiro'] > (float)$sumula['pontos_aka']) {
                return [
                    'finalizada' => true,
                    'vencedor_lado' => 'shiro',
                    'forma_vitoria' => 'golden_score',
                    'motivo' => 'Golden Score (Ponto de Ouro marcado)'
                ];
            }
        }

        return ['finalizada' => false];
    }
}
