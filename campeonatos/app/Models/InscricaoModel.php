<?php
namespace App\Models;

use App\Core\Database;

/**
 * Model de Inscrições com Snapshot Imutável de Filiados
 */
class InscricaoModel
{
    public static function byCampeonato(int $idCampeonato, ?int $idCategoria = null): array
    {
        $conn = Database::getConnection();

        if ($idCategoria) {
            $stmt = $conn->prepare("
                SELECT i.*, c.nome AS categoria_nome, c.modalidade, c.exige_pesagem,
                       (SELECT resultado FROM campeonato_pesagens WHERE id_inscricao = i.id_inscricao ORDER BY id_pesagem DESC LIMIT 1) AS pesagem_resultado,
                       (SELECT peso_aferido FROM campeonato_pesagens WHERE id_inscricao = i.id_inscricao ORDER BY id_pesagem DESC LIMIT 1) AS peso_aferido
                FROM campeonato_inscricoes i
                JOIN campeonato_categorias c ON i.id_categoria = c.id_categoria
                WHERE i.id_campeonato = ? AND i.id_categoria = ?
                ORDER BY i.nome_atleta_snapshot ASC
            ");
            $stmt->bind_param("ii", $idCampeonato, $idCategoria);
        } else {
            $stmt = $conn->prepare("
                SELECT i.*, c.nome AS categoria_nome, c.modalidade, c.exige_pesagem,
                       (SELECT resultado FROM campeonato_pesagens WHERE id_inscricao = i.id_inscricao ORDER BY id_pesagem DESC LIMIT 1) AS pesagem_resultado,
                       (SELECT peso_aferido FROM campeonato_pesagens WHERE id_inscricao = i.id_inscricao ORDER BY id_pesagem DESC LIMIT 1) AS peso_aferido
                FROM campeonato_inscricoes i
                JOIN campeonato_categorias c ON i.id_categoria = c.id_categoria
                WHERE i.id_campeonato = ?
                ORDER BY c.modalidade ASC, c.nome ASC, i.nome_atleta_snapshot ASC
            ");
            $stmt->bind_param("i", $idCampeonato);
        }

        $stmt->execute();
        $res = $stmt->get_result();
        $inscricoes = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $inscricoes;
    }

    public static function find(int $id): ?array
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            SELECT i.*, c.nome AS categoria_nome, c.modalidade, c.peso_min, c.peso_max, c.exige_pesagem
            FROM campeonato_inscricoes i
            JOIN campeonato_categorias c ON i.id_categoria = c.id_categoria
            WHERE i.id_inscricao = ?
            LIMIT 1
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $insc = $res->fetch_assoc();
        $stmt->close();
        return $insc ?: null;
    }

    public static function isRegistered(int $idCategoria, int $idFiliado): bool
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT id_inscricao FROM campeonato_inscricoes WHERE id_categoria = ? AND id_filiado = ? LIMIT 1");
        $stmt->bind_param("ii", $idCategoria, $idFiliado);
        $stmt->execute();
        $res = $stmt->get_result();
        $exists = ($res->num_rows > 0);
        $stmt->close();
        return $exists;
    }

    public static function createWithSnapshot(array $data): int
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            INSERT INTO campeonato_inscricoes (
                id_campeonato, id_categoria, id_filiado, nome_atleta_snapshot, dojo_snapshot,
                graduacao_id_snapshot, graduacao_nome_snapshot, data_nascimento_snapshot,
                idade_evento, sexo, peso_declarado, status_elegibilidade
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iiississisds",
            $data['id_campeonato'],
            $data['id_categoria'],
            $data['id_filiado'],
            $data['nome_atleta_snapshot'],
            $data['dojo_snapshot'],
            $data['graduacao_id_snapshot'],
            $data['graduacao_nome_snapshot'],
            $data['data_nascimento_snapshot'],
            $data['idade_evento'],
            $data['sexo'],
            $data['peso_declarado'],
            $data['status_elegibilidade']
        );
        $stmt->execute();
        $id = $conn->insert_id;
        $stmt->close();
        return $id;
    }

    public static function updateStatus(int $id, string $status, ?string $motivo = null): bool
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE campeonato_inscricoes SET status_elegibilidade = ?, motivo_reprovacao = ? WHERE id_inscricao = ?");
        $stmt->bind_param("ssi", $status, $motivo, $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public static function updateCategoria(int $idInscricao, int $novaCategoriaId): bool
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE campeonato_inscricoes SET id_categoria = ? WHERE id_inscricao = ?");
        $stmt->bind_param("ii", $novaCategoriaId, $idInscricao);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }
}
