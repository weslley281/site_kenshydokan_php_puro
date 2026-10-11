<?php
namespace App\Models;

use App\Core\Database;

/**
 * Model de Súmula Digital de Kumite
 */
class SumulaKumiteModel
{
    public static function byLuta(int $idLuta): ?array
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM campeonato_sumulas_kumite WHERE id_luta = ? LIMIT 1");
        $stmt->bind_param("i", $idLuta);
        $stmt->execute();
        $res = $stmt->get_result();
        $sumula = $res->fetch_assoc();
        $stmt->close();
        return $sumula ?: null;
    }

    public static function createInitial(int $idLuta): int
    {
        $existing = self::byLuta($idLuta);
        if ($existing) {
            return (int)$existing['id_sumula'];
        }

        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO campeonato_sumulas_kumite (id_luta) VALUES (?)");
        $stmt->bind_param("i", $idLuta);
        $stmt->execute();
        $id = $conn->insert_id;
        $stmt->close();
        return $id;
    }

    public static function updateScore(int $idSumula, array $data): bool
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            UPDATE campeonato_sumulas_kumite
            SET tempo_decorrido_segundos = ?, pontos_aka = ?, pontos_shiro = ?,
                wazaari_aka = ?, wazaari_shiro = ?, ippon_aka = ?, ippon_shiro = ?,
                chui_aka = ?, chui_shiro = ?, shido_aka = ?, shido_shiro = ?,
                hansoku_make_aka = ?, hansoku_make_shiro = ?, saidas_aka = ?, saidas_shiro = ?,
                golden_score_ativo = ?, vencedor_id = ?, motivo_encerramento = ?
            WHERE id_sumula = ?
        ");
        $stmt->bind_param(
            "iddiiiiiiiiiiiiiisi",
            $data['tempo_decorrido_segundos'],
            $data['pontos_aka'],
            $data['pontos_shiro'],
            $data['wazaari_aka'],
            $data['wazaari_shiro'],
            $data['ippon_aka'],
            $data['ippon_shiro'],
            $data['chui_aka'],
            $data['chui_shiro'],
            $data['shido_aka'],
            $data['shido_shiro'],
            $data['hansoku_make_aka'],
            $data['hansoku_make_shiro'],
            $data['saidas_aka'],
            $data['saidas_shiro'],
            $data['golden_score_ativo'],
            $data['vencedor_id'],
            $data['motivo_encerramento'],
            $idSumula
        );
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public static function recordEvent(array $data): int
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            INSERT INTO campeonato_eventos_luta (id_luta, segundo_luta, fase, lado, tipo_evento, pontos_atribuidos, descricao, id_usuario_operador)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iisssdsi",
            $data['id_luta'],
            $data['segundo_luta'],
            $data['fase'],
            $data['lado'],
            $data['tipo_evento'],
            $data['pontos_atribuidos'],
            $data['descricao'],
            $data['id_usuario_operador']
        );
        $stmt->execute();
        $id = $conn->insert_id;
        $stmt->close();
        return $id;
    }

    public static function getEvents(int $idLuta): array
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM campeonato_eventos_luta WHERE id_luta = ? ORDER BY id_evento DESC");
        $stmt->bind_param("i", $idLuta);
        $stmt->execute();
        $res = $stmt->get_result();
        $events = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $events;
    }

    public static function homologate(int $idSumula, int $userId): bool
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE campeonato_sumulas_kumite SET homologado = 1, homologado_por = ?, data_homologacao = NOW() WHERE id_sumula = ?");
        $stmt->bind_param("ii", $userId, $idSumula);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }
}
