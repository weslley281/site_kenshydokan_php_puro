<?php
namespace App\Models;

use App\Core\Database;

/**
 * Model de Chaves Eliminatórias
 */
class ChaveModel
{
    public static function byCategoria(int $idCategoria): ?array
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM campeonato_chaves WHERE id_categoria = ? ORDER BY versao DESC LIMIT 1");
        $stmt->bind_param("i", $idCategoria);
        $stmt->execute();
        $res = $stmt->get_result();
        $chave = $res->fetch_assoc();
        $stmt->close();
        return $chave ?: null;
    }

    public static function saveBracket(int $idCampeonato, int $idCategoria, string $tipo, int $totalAtletas, int $totalRodadas, string $arvoreJson): int
    {
        $conn = Database::getConnection();

        // Incrementa versão se já existir
        $existing = self::byCategoria($idCategoria);
        $versao = $existing ? ((int)$existing['versao'] + 1) : 1;

        $stmt = $conn->prepare("
            INSERT INTO campeonato_chaves (id_campeonato, id_categoria, tipo_formato, total_atletas, total_rodadas, status, versao, arvore_json)
            VALUES (?, ?, ?, ?, ?, 'sorteada', ?, ?)
        ");
        $stmt->bind_param("iisiiis", $idCampeonato, $idCategoria, $tipo, $totalAtletas, $totalRodadas, $versao, $arvoreJson);
        $stmt->execute();
        $id = $conn->insert_id;
        $stmt->close();
        return $id;
    }

    public static function deleteByCategoria(int $idCategoria): void
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM campeonato_chaves WHERE id_categoria = ?");
        $stmt->bind_param("i", $idCategoria);
        $stmt->execute();
        $stmt->close();
    }
}
