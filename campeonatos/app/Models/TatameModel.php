<?php
namespace App\Models;

use App\Core\Database;

/**
 * Model de Tatames e Áreas de Competição
 */
class TatameModel
{
    public static function byCampeonato(int $idCampeonato): array
    {
        $conn = Database::getConnection();
        $sql = "
            SELECT t.*,
                   u.nome AS operador_nome,
                   (SELECT COUNT(*) FROM campeonato_lutas WHERE id_tatame = t.id_tatame AND status IN ('agendada', 'chamada', 'em_andamento')) AS lutas_pendentes,
                   (SELECT COUNT(*) FROM campeonato_lutas WHERE id_tatame = t.id_tatame AND status = 'concluida') AS lutas_concluidas
            FROM campeonato_tatames t
            LEFT JOIN campeonato_usuarios u ON t.id_operador_atual = u.id_usuario
            WHERE t.id_campeonato = ?
            ORDER BY t.numero ASC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idCampeonato);
        $stmt->execute();
        $res = $stmt->get_result();
        $tatames = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $tatames;
    }

    public static function find(int $id): ?array
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM campeonato_tatames WHERE id_tatame = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $t = $res->fetch_assoc();
        $stmt->close();
        return $t ?: null;
    }

    public static function create(array $data): int
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO campeonato_tatames (id_campeonato, numero, nome, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $data['id_campeonato'], $data['numero'], $data['nome'], $data['status']);
        $stmt->execute();
        $id = $conn->insert_id;
        $stmt->close();
        return $id;
    }

    public static function updateStatus(int $id, string $status, ?int $lutaId = null, ?int $operadorId = null): bool
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            UPDATE campeonato_tatames
            SET status = ?, luta_em_andamento_id = ?, id_operador_atual = COALESCE(?, id_operador_atual)
            WHERE id_tatame = ?
        ");
        $stmt->bind_param("siii", $status, $lutaId, $operadorId, $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }
}
