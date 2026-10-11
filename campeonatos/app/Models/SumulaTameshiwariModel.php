<?php
namespace App\Models;

use App\Core\Database;

/**
 * Model de Súmula Digital de Tameshiwari (Quebramentos Oficiais)
 */
class SumulaTameshiwariModel
{
    public static function recordAttempt(array $data): int
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            INSERT INTO campeonato_tentativas_tameshiwari (
                id_campeonato, id_categoria, id_inscricao, tecnica, material,
                qtd_declarada, qtd_quebrada, resultado, tempo_segundos, arbitro_responsavel, observacoes
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                material = VALUES(material), qtd_declarada = VALUES(qtd_declarada),
                qtd_quebrada = VALUES(qtd_quebrada), resultado = VALUES(resultado),
                tempo_segundos = VALUES(tempo_segundos), arbitro_responsavel = VALUES(arbitro_responsavel),
                observacoes = VALUES(observacoes)
        ");
        $stmt->bind_param(
            "iiissiisiss",
            $data['id_campeonato'],
            $data['id_categoria'],
            $data['id_inscricao'],
            $data['tecnica'],
            $data['material'],
            $data['qtd_declarada'],
            $data['qtd_quebrada'],
            $data['resultado'],
            $data['tempo_segundos'],
            $data['arbitro_responsavel'],
            $data['observacoes']
        );
        $stmt->execute();
        $id = $conn->insert_id;
        $stmt->close();
        return $id;
    }

    public static function byInscricao(int $idInscricao): array
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM campeonato_tentativas_tameshiwari WHERE id_inscricao = ?");
        $stmt->bind_param("i", $idInscricao);
        $stmt->execute();
        $res = $stmt->get_result();
        $attempts = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $attempts;
    }

    public static function getRanking(int $idCategoria): array
    {
        $conn = Database::getConnection();
        $sql = "
            SELECT i.id_inscricao, i.nome_atleta_snapshot, i.dojo_snapshot, i.graduacao_nome_snapshot,
                   SUM(CASE WHEN t.resultado = 'sucesso_total' THEN t.qtd_quebrada ELSE 0 END) AS total_quebrado_valido,
                   COUNT(t.id_tentativa) AS total_oficinas_realizadas
            FROM campeonato_inscricoes i
            LEFT JOIN campeonato_tentativas_tameshiwari t ON i.id_inscricao = t.id_inscricao
            WHERE i.id_categoria = ?
            GROUP BY i.id_inscricao
            ORDER BY total_quebrado_valido DESC, i.nome_atleta_snapshot ASC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idCategoria);
        $stmt->execute();
        $res = $stmt->get_result();
        $ranking = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $ranking;
    }

    /**
     * Retorna o total quebrado para critério de desempate no Kumite (Hantei)
     */
    public static function getTotalQuebradoPorFiliado(int $idCampeonato, int $idFiliado): int
    {
        $conn = Database::getConnection();
        $sql = "
            SELECT COALESCE(SUM(CASE WHEN t.resultado = 'sucesso_total' THEN t.qtd_quebrada ELSE 0 END), 0) AS total
            FROM campeonato_tentativas_tameshiwari t
            JOIN campeonato_inscricoes i ON t.id_inscricao = i.id_inscricao
            WHERE t.id_campeonato = ? AND i.id_filiado = ?
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $idCampeonato, $idFiliado);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return (int)($row['total'] ?? 0);
    }
}
