<?php
namespace App\Models;

use App\Core\Database;

/**
 * Model de Certificados Oficiais com Hash Criptográfico
 */
class CertificadoModel
{
    public static function create(array $data): int
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            INSERT INTO campeonato_certificados (
                id_campeonato, id_inscricao, nome_titular, tipo_certificado,
                codigo_autenticidade, arquivo_pdf_nome
            ) VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iissss",
            $data['id_campeonato'],
            $data['id_inscricao'],
            $data['nome_titular'],
            $data['tipo_certificado'],
            $data['codigo_autenticidade'],
            $data['arquivo_pdf_nome']
        );
        $stmt->execute();
        $id = $conn->insert_id;
        $stmt->close();
        return $id;
    }

    public static function findByHash(string $hash): ?array
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            SELECT cert.*, c.titulo AS campeonato_titulo, c.edicao, c.data_inicio, c.local, c.cidade, c.estado
            FROM campeonato_certificados cert
            JOIN campeonatos c ON cert.id_campeonato = c.id_campeonato
            WHERE cert.codigo_autenticidade = ?
            LIMIT 1
        ");
        $stmt->bind_param("s", $hash);
        $stmt->execute();
        $res = $stmt->get_result();
        $cert = $res->fetch_assoc();
        $stmt->close();
        return $cert ?: null;
    }

    public static function byCampeonato(int $idCampeonato): array
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            SELECT * FROM campeonato_certificados
            WHERE id_campeonato = ?
            ORDER BY data_emissao DESC
        ");
        $stmt->bind_param("i", $idCampeonato);
        $stmt->execute();
        $res = $stmt->get_result();
        $list = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $list;
    }
}
