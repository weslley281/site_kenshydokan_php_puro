<?php
/**
 * Modelo de Súmula de Tameshiwari (campeonato_tameshiwari)
 * Padrão IDÊNTICO ao site principal (models/sumulaTameshiwariModel.php)
 */

include_once __DIR__ . "/../db/conexao.php";

class SumulaTameshiwari {

    private $conexao;

    public function __construct() {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function gravarTentativa(array $dados): int {
        $sql = "INSERT INTO campeonato_tameshiwari (
            id_campeonato, id_categoria, id_inscricao, tecnica, material,
            qtd_declarada, qtd_quebrada, resultado, tempo_segundos,
            arbitro_responsavel, observacoes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexao->prepare($sql);
        $idCamp = (int)$dados['id_campeonato'];
        $idCat = (int)$dados['id_categoria'];
        $idIns = (int)$dados['id_inscricao'];
        $dec = (int)$dados['qtd_declarada'];
        $queb = ($dados['resultado'] === 'falha_ou_parcial') ? 0 : (int)$dados['qtd_quebrada'];
        $tempo = (int)$dados['tempo_segundos'];

        $stmt->bind_param(
            "iiissiisiss",
            $idCamp, $idCat, $idIns, $dados['tecnica'], $dados['material'],
            $dec, $queb, $dados['resultado'], $tempo,
            $dados['arbitro_responsavel'], $dados['observacoes']
        );

        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    public function obterRanking(int $idCategoria): array {
        $sql = "SELECT 
                    t.id_inscricao,
                    i.nome_atleta_snapshot AS nome_atleta,
                    i.dojo_snapshot AS dojo,
                    i.graduacao_nome_snapshot AS graduacao_nome,
                    COUNT(t.id_tameshiwari) AS total_tentativas,
                    SUM(t.qtd_declarada) AS total_declaradas,
                    SUM(t.qtd_quebrada) AS total_quebradas
                FROM campeonato_tameshiwari t
                JOIN campeonato_inscricoes i ON t.id_inscricao = i.id_inscricao
                WHERE t.id_categoria = ?
                GROUP BY t.id_inscricao
                ORDER BY total_quebradas DESC, total_declaradas DESC";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $idCategoria);
        $stmt->execute();
        $res = $stmt->get_result();
        $lista = [];
        while ($row = $res->fetch_assoc()) {
            $lista[] = $row;
        }
        $stmt->close();
        return $lista;
    }

    public function obterTotalQuebradoPorFiliado(int $idCampeonato, int $idFiliado): int {
        $sql = "SELECT COALESCE(SUM(t.qtd_quebrada), 0) AS total 
                FROM campeonato_tameshiwari t
                JOIN campeonato_inscricoes i ON t.id_inscricao = i.id_inscricao
                WHERE t.id_campeonato = ? AND i.id_filiado = ?";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("ii", $idCampeonato, $idFiliado);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int)($row['total'] ?? 0);
    }
}
