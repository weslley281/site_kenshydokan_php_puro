<?php
namespace App\Models;

use App\Core\Database;

/**
 * Model de Confrontos e Lutas
 */
class LutaModel
{
    public static function create(array $data): int
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            INSERT INTO campeonato_lutas (
                id_chave, id_campeonato, id_categoria, id_tatame, numero_luta, rodada,
                posicao_na_chave, id_inscricao_aka, id_inscricao_shiro, status, proxima_luta_id
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iiiiiiiiisi",
            $data['id_chave'],
            $data['id_campeonato'],
            $data['id_categoria'],
            $data['id_tatame'],
            $data['numero_luta'],
            $data['rodada'],
            $data['posicao_na_chave'],
            $data['id_inscricao_aka'],
            $data['id_inscricao_shiro'],
            $data['status'],
            $data['proxima_luta_id']
        );
        $stmt->execute();
        $id = $conn->insert_id;
        $stmt->close();
        return $id;
    }

    public static function find(int $id): ?array
    {
        $conn = Database::getConnection();
        $sql = "
            SELECT l.*,
                   c.titulo AS campeonato_titulo,
                   cat.nome AS categoria_nome, cat.modalidade,
                   t.nome AS tatame_nome, t.numero AS tatame_numero,
                   aka.nome_atleta_snapshot AS aka_nome, aka.dojo_snapshot AS aka_dojo, aka.graduacao_nome_snapshot AS aka_graduacao,
                   shiro.nome_atleta_snapshot AS shiro_nome, shiro.dojo_snapshot AS shiro_dojo, shiro.graduacao_nome_snapshot AS shiro_graduacao
            FROM campeonato_lutas l
            JOIN campeonatos c ON l.id_campeonato = c.id_campeonato
            JOIN campeonato_categorias cat ON l.id_categoria = cat.id_categoria
            LEFT JOIN campeonato_tatames t ON l.id_tatame = t.id_tatame
            LEFT JOIN campeonato_inscricoes aka ON l.id_inscricao_aka = aka.id_inscricao
            LEFT JOIN campeonato_inscricoes shiro ON l.id_inscricao_shiro = shiro.id_inscricao
            WHERE l.id_luta = ?
            LIMIT 1
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $luta = $res->fetch_assoc();
        $stmt->close();
        return $luta ?: null;
    }

    public static function byChave(int $idChave): array
    {
        $conn = Database::getConnection();
        $sql = "
            SELECT l.*,
                   aka.nome_atleta_snapshot AS aka_nome, aka.dojo_snapshot AS aka_dojo,
                   shiro.nome_atleta_snapshot AS shiro_nome, shiro.dojo_snapshot AS shiro_dojo
            FROM campeonato_lutas l
            LEFT JOIN campeonato_inscricoes aka ON l.id_inscricao_aka = aka.id_inscricao
            LEFT JOIN campeonato_inscricoes shiro ON l.id_inscricao_shiro = shiro.id_inscricao
            WHERE l.id_chave = ?
            ORDER BY l.rodada ASC, l.posicao_na_chave ASC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idChave);
        $stmt->execute();
        $res = $stmt->get_result();
        $lutas = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $lutas;
    }

    public static function byTatame(int $idTatame, ?string $status = null): array
    {
        $conn = Database::getConnection();
        $sql = "
            SELECT l.*,
                   cat.nome AS categoria_nome,
                   aka.nome_atleta_snapshot AS aka_nome, aka.dojo_snapshot AS aka_dojo,
                   shiro.nome_atleta_snapshot AS shiro_nome, shiro.dojo_snapshot AS shiro_dojo
            FROM campeonato_lutas l
            JOIN campeonato_categorias cat ON l.id_categoria = cat.id_categoria
            LEFT JOIN campeonato_inscricoes aka ON l.id_inscricao_aka = aka.id_inscricao
            LEFT JOIN campeonato_inscricoes shiro ON l.id_inscricao_shiro = shiro.id_inscricao
            WHERE l.id_tatame = ?
        ";
        if ($status) {
            $sql .= " AND l.status = ? ORDER BY l.ordem_fila ASC, l.numero_luta ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $idTatame, $status);
        } else {
            $sql .= " ORDER BY CASE WHEN l.status = 'em_andamento' THEN 1 WHEN l.status = 'chamada' THEN 2 WHEN l.status = 'agendada' THEN 3 ELSE 4 END, l.ordem_fila ASC, l.numero_luta ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $idTatame);
        }

        $stmt->execute();
        $res = $stmt->get_result();
        $lutas = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $lutas;
    }

    public static function updateStatus(int $id, string $status): bool
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE campeonato_lutas SET status = ? WHERE id_luta = ?");
        $stmt->bind_param("si", $status, $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public static function setWinner(int $idLuta, int $vencedorInscricaoId, string $formaVitoria): bool
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            UPDATE campeonato_lutas
            SET id_vencedor = ?, forma_vitoria = ?, status = 'concluida', data_fim = NOW()
            WHERE id_luta = ?
        ");
        $stmt->bind_param("isi", $vencedorInscricaoId, $formaVitoria, $idLuta);
        $res = $stmt->execute();
        $stmt->close();

        // Se houver próxima luta agendada, aloca o vencedor na próxima fase!
        $luta = self::find($idLuta);
        if ($luta && !empty($luta['proxima_luta_id'])) {
            $proxId = (int)$luta['proxima_luta_id'];
            $proxLuta = self::find($proxId);
            if ($proxLuta) {
                // Se a posição da luta atual for ímpar, entra como Aka na próxima; se par, entra como Shiro
                $campo = ($luta['posicao_na_chave'] % 2 !== 0) ? 'id_inscricao_aka' : 'id_inscricao_shiro';
                $stmtProx = $conn->prepare("UPDATE campeonato_lutas SET {$campo} = ? WHERE id_luta = ?");
                $stmtProx->bind_param("ii", $vencedorInscricaoId, $proxId);
                $stmtProx->execute();
                $stmtProx->close();
            }
        }

        return $res;
    }

    public static function assignTatame(int $idLuta, int $idTatame, int $ordem): bool
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE campeonato_lutas SET id_tatame = ?, ordem_fila = ? WHERE id_luta = ?");
        $stmt->bind_param("iii", $idTatame, $ordem, $idLuta);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }
}
