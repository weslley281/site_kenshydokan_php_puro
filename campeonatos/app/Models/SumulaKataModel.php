<?php
namespace App\Models;

use App\Core\Database;

/**
 * Model de Súmula Digital de Kata (Notas de 0.0 a 10.0 e Média Aritmética)
 */
class SumulaKataModel
{
    public static function recordScore(array $data): int
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            INSERT INTO campeonato_notas_kata (
                id_campeonato, id_categoria, id_inscricao, rodada, is_desempate,
                kata_nome, arbitro_ordem, arbitro_nome, nota, observacao
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE nota = VALUES(nota), observacao = VALUES(observacao), kata_nome = VALUES(kata_nome)
        ");
        $stmt->bind_param(
            "iiiiisisds",
            $data['id_campeonato'],
            $data['id_categoria'],
            $data['id_inscricao'],
            $data['rodada'],
            $data['is_desempate'],
            $data['kata_nome'],
            $data['arbitro_ordem'],
            $data['arbitro_nome'],
            $data['nota'],
            $data['observacao']
        );
        $stmt->execute();
        $id = $conn->insert_id;
        $stmt->close();
        return $id;
    }

    public static function getScoresByInscricao(int $idCategoria, int $idInscricao, int $rodada = 1): array
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            SELECT * FROM campeonato_notas_kata
            WHERE id_categoria = ? AND id_inscricao = ? AND rodada = ?
            ORDER BY arbitro_ordem ASC
        ");
        $stmt->bind_param("iii", $idCategoria, $idInscricao, $rodada);
        $stmt->execute();
        $res = $stmt->get_result();
        $scores = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $scores;
    }

    public static function getRanking(int $idCategoria, int $rodada = 1): array
    {
        $conn = Database::getConnection();
        $sql = "
            SELECT i.id_inscricao, i.nome_atleta_snapshot, i.dojo_snapshot, i.graduacao_nome_snapshot,
                   k.kata_nome,
                   COUNT(k.id_nota_kata) AS total_arbitros,
                   ROUND(AVG(k.nota), 2) AS media_final,
                   GROUP_CONCAT(k.nota ORDER BY k.arbitro_ordem SEPARATOR ' | ') AS notas_detalhadas
            FROM campeonato_inscricoes i
            JOIN campeonato_notas_kata k ON i.id_inscricao = k.id_inscricao AND k.rodada = ?
            WHERE i.id_categoria = ?
            GROUP BY i.id_inscricao, k.kata_nome
            ORDER BY media_final DESC, i.nome_atleta_snapshot ASC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $rodada, $idCategoria);
        $stmt->execute();
        $res = $stmt->get_result();
        $ranking = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $ranking;
    }
}
