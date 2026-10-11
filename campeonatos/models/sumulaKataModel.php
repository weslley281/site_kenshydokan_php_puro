<?php
/**
 * Modelo de Súmula de Kata (campeonato_notas_kata)
 * Padrão IDÊNTICO ao site principal (models/sumulaKataModel.php)
 */

include_once __DIR__ . "/../db/conexao.php";

class SumulaKata {

    private $conexao;

    public function __construct() {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function gravarNota(array $dados): int {
        $sql = "INSERT INTO campeonato_notas_kata (
            id_campeonato, id_categoria, id_inscricao, rodada, is_desempate,
            kata_nome, arbitro_ordem, arbitro_nome, nota, observacao
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexao->prepare($sql);
        $idCamp = (int)$dados['id_campeonato'];
        $idCat = (int)$dados['id_categoria'];
        $idIns = (int)$dados['id_inscricao'];
        $rodada = (int)$dados['rodada'];
        $desempate = (int)$dados['is_desempate'];
        $ordem = (int)$dados['arbitro_ordem'];
        $nota = (float)$dados['nota'];

        $stmt->bind_param("iiiiisisds", $idCamp, $idCat, $idIns, $rodada, $desempate, $dados['kata_nome'], $ordem, $dados['arbitro_nome'], $nota, $dados['observacao']);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    public function obterRanking(int $idCategoria, int $rodada = 1): array {
        $sql = "SELECT 
                    k.id_inscricao,
                    i.nome_atleta_snapshot AS nome_atleta,
                    i.dojo_snapshot AS dojo,
                    i.graduacao_nome_snapshot AS graduacao_nome,
                    k.kata_nome,
                    k.is_desempate,
                    COUNT(k.id_nota) AS total_arbitros,
                    ROUND(AVG(k.nota), 2) AS media_final,
                    GROUP_CONCAT(CONCAT(k.arbitro_ordem, 'º: ', k.nota) ORDER BY k.arbitro_ordem SEPARATOR ' | ') AS notas_detalhes
                FROM campeonato_notas_kata k
                JOIN campeonato_inscricoes i ON k.id_inscricao = i.id_inscricao
                WHERE k.id_categoria = ? AND k.rodada = ?
                GROUP BY k.id_inscricao, k.kata_nome, k.is_desempate
                ORDER BY media_final DESC";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("ii", $idCategoria, $rodada);
        $stmt->execute();
        $res = $stmt->get_result();
        $lista = [];
        while ($row = $res->fetch_assoc()) {
            $lista[] = $row;
        }
        $stmt->close();
        return $lista;
    }

    public function checarEmpateNoPodio(array $ranking): bool {
        if (count($ranking) < 2) return false;
        // Verifica se há empates entre 1º e 2º, ou 2º e 3º, ou 3º e 4º
        $top3 = array_slice($ranking, 0, 4);
        for ($i = 0; $i < count($top3) - 1; $i++) {
            if ((float)$top3[$i]['media_final'] === (float)$top3[$i + 1]['media_final']) {
                return true;
            }
        }
        return false;
    }
}
