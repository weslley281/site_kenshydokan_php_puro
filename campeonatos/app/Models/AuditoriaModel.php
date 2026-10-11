<?php
namespace App\Models;

use App\Core\Database;
use App\Core\Session;

/**
 * Model de Trilha Inalterável de Auditoria
 */
class AuditoriaModel
{
    public static function log(
        string $acao,
        string $entidade,
        int $registroId,
        ?array $dadosAntigos = null,
        ?array $dadosNovos = null,
        ?string $justificativa = null,
        ?int $idCampeonato = null
    ): int {
        $conn = Database::getConnection();
        $idUsuario = Session::userId() ?? 1;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        $antigosJson = $dadosAntigos ? json_encode($dadosAntigos, JSON_UNESCAPED_UNICODE) : null;
        $novosJson = $dadosNovos ? json_encode($dadosNovos, JSON_UNESCAPED_UNICODE) : null;

        $stmt = $conn->prepare("
            INSERT INTO campeonato_auditoria (
                id_campeonato, id_usuario, acao, entidade, registro_id,
                dados_anteriores_json, dados_novos_json, justificativa, ip_origem
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iississss",
            $idCampeonato,
            $idUsuario,
            $acao,
            $entidade,
            $registroId,
            $antigosJson,
            $novosJson,
            $justificativa,
            $ip
        );
        $stmt->execute();
        $id = $conn->insert_id;
        $stmt->close();
        return $id;
    }

    public static function byCampeonato(int $idCampeonato, int $limit = 100): array
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            SELECT a.*, u.nome AS usuario_nome, u.email AS usuario_email
            FROM campeonato_auditoria a
            JOIN campeonato_usuarios u ON a.id_usuario = u.id_usuario
            WHERE a.id_campeonato = ?
            ORDER BY a.data_hora DESC
            LIMIT ?
        ");
        $stmt->bind_param("ii", $idCampeonato, $limit);
        $stmt->execute();
        $res = $stmt->get_result();
        $list = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $list;
    }
}
