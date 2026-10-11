<?php
namespace App\Models;

use App\Core\Database;
use Exception;

/**
 * Model de Integração e Leitura Segura da Tabela 'filiados' do Banco Raiz
 * ESTE MODEL É ESTRITAMENTE SOMENTE-LEITURA.
 */
class FiliadoModel
{
    /**
     * Busca filiados por nome ou código para preenchimento de formulários (Select2)
     */
    public static function search(string $term, int $limit = 25): array
    {
        $conn = Database::getMainConnection();
        $termLike = '%' . $term . '%';

        $sql = "
            SELECT f.id_filiado, f.codigo, f.nome, f.dojo, f.dataNascimento, f.confirmacao,
                   COALESCE(g.graduacao, 'Faixa Branca 8° Kyu') AS graduacao_nome,
                   COALESCE(fg.id_graduacao, 2) AS id_graduacao
            FROM filiados f
            LEFT JOIN filiados_graduacoes fg ON f.id_filiado = fg.id_filiado AND (fg.id_arte = 1 OR fg.id_arte IS NULL)
            LEFT JOIN graduacoes g ON fg.id_graduacao = g.id_graduacao
            WHERE (f.nome LIKE ? OR f.codigo LIKE ? OR f.dojo LIKE ?)
            ORDER BY f.nome ASC
            LIMIT ?
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $termLike, $termLike, $termLike, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $filiados = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $filiados;
    }

    /**
     * Busca um filiado por seu identificador único
     */
    public static function findById(int $idFiliado): ?array
    {
        $conn = Database::getMainConnection();

        $sql = "
            SELECT f.id_filiado, f.codigo, f.nome, f.dojo, f.dataNascimento, f.telefone, f.email, f.cidade, f.confirmacao,
                   COALESCE(g.graduacao, 'Faixa Branca 8° Kyu') AS graduacao_nome,
                   COALESCE(fg.id_graduacao, 2) AS id_graduacao
            FROM filiados f
            LEFT JOIN filiados_graduacoes fg ON f.id_filiado = fg.id_filiado AND (fg.id_arte = 1 OR fg.id_arte IS NULL)
            LEFT JOIN graduacoes g ON fg.id_graduacao = g.id_graduacao
            WHERE f.id_filiado = ?
            LIMIT 1
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idFiliado);
        $stmt->execute();
        $result = $stmt->get_result();
        $filiado = $result->fetch_assoc();
        $stmt->close();

        return $filiado ?: null;
    }

    /**
     * Retorna a lista de todos os filiados ativos
     */
    public static function listActive(int $limit = 500): array
    {
        $conn = Database::getMainConnection();

        $sql = "
            SELECT f.id_filiado, f.codigo, f.nome, f.dojo, f.dataNascimento, f.confirmacao,
                   COALESCE(g.graduacao, 'Faixa Branca 8° Kyu') AS graduacao_nome,
                   COALESCE(fg.id_graduacao, 2) AS id_graduacao
            FROM filiados f
            LEFT JOIN filiados_graduacoes fg ON f.id_filiado = fg.id_filiado AND fg.id_arte = 1
            LEFT JOIN graduacoes g ON fg.id_graduacao = g.id_graduacao
            WHERE f.confirmacao = 'sim'
            ORDER BY f.nome ASC
            LIMIT ?
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $filiados = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $filiados;
    }
}
