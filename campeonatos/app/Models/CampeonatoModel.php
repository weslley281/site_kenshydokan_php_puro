<?php
namespace App\Models;

use App\Core\Database;

/**
 * Model de Campeonatos e Gestão do Ciclo de Vida
 */
class CampeonatoModel
{
    public static function all(): array
    {
        $conn = Database::getConnection();
        $sql = "
            SELECT c.*,
                   (SELECT COUNT(*) FROM campeonato_categorias WHERE id_campeonato = c.id_campeonato) AS total_categorias,
                   (SELECT COUNT(*) FROM campeonato_inscricoes WHERE id_campeonato = c.id_campeonato) AS total_inscritos,
                   (SELECT COUNT(*) FROM campeonato_lutas WHERE id_campeonato = c.id_campeonato) AS total_lutas
            FROM campeonatos c
            ORDER BY c.data_inicio DESC
        ";
        $res = $conn->query($sql);
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public static function find(int $id): ?array
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM campeonatos WHERE id_campeonato = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $camp = $res->fetch_assoc();
        $stmt->close();
        return $camp ?: null;
    }

    public static function create(array $data): int
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            INSERT INTO campeonatos (titulo, edicao, ano, local, cidade, estado, data_inicio, data_fim, data_referencia_idade, organizador, status, modalidades_habilitadas)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "ssisssssssss",
            $data['titulo'],
            $data['edicao'],
            $data['ano'],
            $data['local'],
            $data['cidade'],
            $data['estado'],
            $data['data_inicio'],
            $data['data_fim'],
            $data['data_referencia_idade'],
            $data['organizador'],
            $data['status'],
            $data['modalidades_habilitadas']
        );
        $stmt->execute();
        $id = $conn->insert_id;
        $stmt->close();
        return $id;
    }

    public static function update(int $id, array $data): bool
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            UPDATE campeonatos
            SET titulo = ?, edicao = ?, ano = ?, local = ?, cidade = ?, estado = ?,
                data_inicio = ?, data_fim = ?, data_referencia_idade = ?, organizador = ?,
                modalidades_habilitadas = ?
            WHERE id_campeonato = ?
        ");
        $stmt->bind_param(
            "ssissssssssi",
            $data['titulo'],
            $data['edicao'],
            $data['ano'],
            $data['local'],
            $data['cidade'],
            $data['estado'],
            $data['data_inicio'],
            $data['data_fim'],
            $data['data_referencia_idade'],
            $data['organizador'],
            $data['modalidades_habilitadas'],
            $id
        );
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public static function updateStatus(int $id, string $status): bool
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE campeonatos SET status = ? WHERE id_campeonato = ?");
        $stmt->bind_param("si", $status, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
