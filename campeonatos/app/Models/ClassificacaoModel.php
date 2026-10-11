<?php
namespace App\Models;

use App\Core\Database;

/**
 * Model de Classificação Oficial e Medalhas
 */
class ClassificacaoModel
{
    public static function byCategoria(int $idCategoria): array
    {
        $conn = Database::getConnection();
        $sql = "
            SELECT cl.*, i.nome_atleta_snapshot, i.dojo_snapshot, i.graduacao_nome_snapshot,
                   c.nome AS categoria_nome, c.modalidade
            FROM campeonato_classificacoes cl
            JOIN campeonato_inscricoes i ON cl.id_inscricao = i.id_inscricao
            JOIN campeonato_categorias c ON cl.id_categoria = c.id_categoria
            WHERE cl.id_categoria = ?
            ORDER BY cl.colocacao ASC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idCategoria);
        $stmt->execute();
        $res = $stmt->get_result();
        $podio = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $podio;
    }

    public static function savePodium(int $idCampeonato, int $idCategoria, int $idInscricao, int $colocacao, string $medalha, int $pontosEquipe = 0): int
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            INSERT INTO campeonato_classificacoes (id_campeonato, id_categoria, id_inscricao, colocacao, medalha, pontos_equipe, homologado)
            VALUES (?, ?, ?, ?, ?, ?, 1)
            ON DUPLICATE KEY UPDATE id_inscricao = VALUES(id_inscricao), medalha = VALUES(medalha), pontos_equipe = VALUES(pontos_equipe)
        ");
        $stmt->bind_param("iiiisi", $idCampeonato, $idCategoria, $idInscricao, $colocacao, $medalha, $pontosEquipe);
        $stmt->execute();
        $id = $conn->insert_id;
        $stmt->close();
        return $id;
    }

    public static function getMedalTable(int $idCampeonato): array
    {
        $conn = Database::getConnection();
        $sql = "
            SELECT i.dojo_snapshot,
                   COUNT(CASE WHEN cl.medalha = 'ouro' THEN 1 END) AS ouro,
                   COUNT(CASE WHEN cl.medalha = 'prata' THEN 1 END) AS prata,
                   COUNT(CASE WHEN cl.medalha = 'bronze' THEN 1 END) AS bronze,
                   COUNT(cl.id_classificacao) AS total_medalhas
            FROM campeonato_classificacoes cl
            JOIN campeonato_inscricoes i ON cl.id_inscricao = i.id_inscricao
            WHERE cl.id_campeonato = ? AND cl.homologado = 1
            GROUP BY i.dojo_snapshot
            ORDER BY ouro DESC, prata DESC, bronze DESC, total_medalhas DESC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idCampeonato);
        $stmt->execute();
        $res = $stmt->get_result();
        $table = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $table;
    }
}
