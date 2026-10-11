<?php
/**
 * Modelo de Leitura de Filiados (base principal da Kenshydokan)
 * Conexão estritamente READ-ONLY via conectarPrincipal()
 * Padrão IDÊNTICO ao site principal (models/filiadoModel.php)
 */

include_once __DIR__ . "/../db/conexao.php";

class Filiado {

    private $conexao;

    public function __construct() {
        $c = new Conexao();
        // Conecta ao banco de dados do domínio principal (u515961161_kenshydokan)
        $this->conexao = $c->conectarPrincipal();
    }

    public function buscarPorId($id): ?array {
        $id = (int)$id;
        $sql = "SELECT 
                    f.id_filiado,
                    f.nome,
                    f.dataNascimento,
                    f.dojo,
                    f.codigo,
                    f.foto,
                    COALESCE(g.nome, 'Sem Graduação') AS graduacao_nome,
                    COALESCE(fg.id_graduacao, 0) AS id_graduacao
                FROM filiados f
                LEFT JOIN filiados_graduacoes fg ON f.id_filiado = fg.id_filiado AND fg.id_arte = 1
                LEFT JOIN graduacoes g ON fg.id_graduacao = g.id_graduacao
                WHERE f.id_filiado = ?";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function buscarPorTermo(string $termo, int $limite = 30): array {
        $termoLimpo = '%' . trim($termo) . '%';
        $sql = "SELECT 
                    f.id_filiado,
                    f.nome,
                    f.dataNascimento,
                    f.dojo,
                    f.codigo,
                    COALESCE(g.nome, 'Iniciante / Branca') AS graduacao_nome,
                    COALESCE(fg.id_graduacao, 1) AS id_graduacao
                FROM filiados f
                LEFT JOIN filiados_graduacoes fg ON f.id_filiado = fg.id_filiado AND fg.id_arte = 1
                LEFT JOIN graduacoes g ON fg.id_graduacao = g.id_graduacao
                WHERE f.nome LIKE ? OR f.codigo LIKE ? OR f.dojo LIKE ?
                ORDER BY f.nome ASC
                LIMIT ?";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("sssi", $termoLimpo, $termoLimpo, $termoLimpo, $limite);
        $stmt->execute();
        $res = $stmt->get_result();
        $lista = [];
        while ($row = $res->fetch_assoc()) {
            $lista[] = $row;
        }
        $stmt->close();
        return $lista;
    }
}
