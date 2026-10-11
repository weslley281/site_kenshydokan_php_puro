<?php
/**
 * Modelo de Classificação Oficial e Medalhas (campeonato_classificacao)
 * Padrão IDÊNTICO ao site principal (models/classificacaoModel.php)
 */

include_once __DIR__ . "/../db/conexao.php";

class Classificacao {

    private $conexao;

    public function __construct() {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function buscarPorCategoria(int $idCategoria): array {
        $sql = "SELECT 
                    c.*,
                    i.nome_atleta_snapshot AS nome_atleta,
                    i.dojo_snapshot AS dojo,
                    i.graduacao_nome_snapshot AS graduacao_nome
                FROM campeonato_classificacao c
                JOIN campeonato_inscricoes i ON c.id_inscricao = i.id_inscricao
                WHERE c.id_categoria = ?
                ORDER BY c.colocacao ASC";

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

    public function obterQuadroMedalhas(int $idCampeonato): array {
        $sql = "SELECT 
                    i.dojo_snapshot AS dojo,
                    SUM(CASE WHEN c.medalha = 'ouro' THEN 1 ELSE 0 END) AS ouro,
                    SUM(CASE WHEN c.medalha = 'prata' THEN 1 ELSE 0 END) AS prata,
                    SUM(CASE WHEN c.medalha = 'bronze' THEN 1 ELSE 0 END) AS bronze,
                    COUNT(c.id_classificacao) AS total_medalhas
                FROM campeonato_classificacao c
                JOIN campeonato_inscricoes i ON c.id_inscricao = i.id_inscricao
                WHERE c.id_campeonato = ? AND c.medalha != 'nenhuma'
                GROUP BY i.dojo_snapshot
                ORDER BY ouro DESC, prata DESC, bronze DESC, total_medalhas DESC";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $idCampeonato);
        $stmt->execute();
        $res = $stmt->get_result();
        $lista = [];
        while ($row = $res->fetch_assoc()) {
            $lista[] = $row;
        }
        $stmt->close();
        return $lista;
    }

    public function limparPorCategoria(int $idCategoria): bool {
        $stmt = $this->conexao->prepare("DELETE FROM campeonato_classificacao WHERE id_categoria = ?");
        $stmt->bind_param("i", $idCategoria);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function gravarColocacao(int $idCampeonato, int $idCategoria, int $idInscricao, int $colocacao, string $medalha, int $pontos, ?int $homologadoPor = null): int {
        $sql = "INSERT INTO campeonato_classificacao (
            id_campeonato, id_categoria, id_inscricao, colocacao, medalha, pontos_ranking, homologado_por
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("iiiisii", $idCampeonato, $idCategoria, $idInscricao, $colocacao, $medalha, $pontos, $homologadoPor);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }
}
