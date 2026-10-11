<?php
namespace App\Models;

use App\Core\Database;

/**
 * Model de Pesagem Oficial
 */
class PesagemModel
{
    public static function record(array $data): int
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            INSERT INTO campeonato_pesagens (id_inscricao, id_campeonato, peso_aferido, unidade, id_operador, resultado, diferenca_peso, observacoes)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iidsisds",
            $data['id_inscricao'],
            $data['id_campeonato'],
            $data['peso_aferido'],
            $data['unidade'],
            $data['id_operador'],
            $data['resultado'],
            $data['diferenca_peso'],
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
        $stmt = $conn->prepare("
            SELECT p.*, u.nome AS operador_nome
            FROM campeonato_pesagens p
            JOIN campeonato_usuarios u ON p.id_operador = u.id_usuario
            WHERE p.id_inscricao = ?
            ORDER BY p.data_hora DESC
        ");
        $stmt->bind_param("i", $idInscricao);
        $stmt->execute();
        $res = $stmt->get_result();
        $list = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $list;
    }

    public static function byCampeonato(int $idCampeonato): array
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            SELECT p.*, i.nome_atleta_snapshot, i.dojo_snapshot, c.nome AS categoria_nome, c.peso_min, c.peso_max,
                   u.nome AS operador_nome
            FROM campeonato_pesagens p
            JOIN campeonato_inscricoes i ON p.id_inscricao = i.id_inscricao
            JOIN campeonato_categorias c ON i.id_categoria = c.id_categoria
            JOIN campeonato_usuarios u ON p.id_operador = u.id_usuario
            WHERE p.id_campeonato = ?
            ORDER BY p.data_hora DESC
        ");
        $stmt->bind_param("i", $idCampeonato);
        $stmt->execute();
        $res = $stmt->get_result();
        $list = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $list;
    }
}
