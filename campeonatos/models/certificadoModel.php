<?php
/**
 * Modelo de Certificados Digitais e Hash de Autenticidade (campeonato_certificados)
 * Padrão IDÊNTICO ao site principal (models/certificadoModel.php)
 */

include_once __DIR__ . "/../db/conexao.php";

class Certificado {

    private $conexao;

    public function __construct() {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function buscarPorCampeonato(int $idCampeonato): array {
        $sql = "SELECT c.*, i.dojo_snapshot AS dojo, cat.nome AS categoria_nome
                FROM campeonato_certificados c
                JOIN campeonato_inscricoes i ON c.id_inscricao = i.id_inscricao
                JOIN campeonato_categorias cat ON i.id_categoria = cat.id_categoria
                WHERE c.id_campeonato = ?
                ORDER BY c.data_emissao DESC";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $idCampeonato);
        $stmt->execute();
        $res = $stmt->get_result();
        $lista = [];
        while ($row = $res->fetch_assoc()) {
            $lista[] = $row;
        }
        $stmt->close();
        return $lista;
    }

    public function buscarAtletasPorCampeonato(int $idCampeonato): array {
        $sql = "SELECT
                    i.id_filiado,
                    MIN(i.id_inscricao) AS id_inscricao,
                    MAX(i.nome_atleta_snapshot) AS nome_titular,
                    MAX(i.dojo_snapshot) AS dojo,
                    COUNT(DISTINCT i.id_categoria) AS total_categorias,
                    (
                        SELECT c.id_certificado
                        FROM campeonato_certificados c
                        JOIN campeonato_inscricoes ci ON ci.id_inscricao = c.id_inscricao
                        WHERE c.id_campeonato = i.id_campeonato
                          AND ci.id_filiado = i.id_filiado
                        ORDER BY c.data_emissao DESC, c.id_certificado DESC
                        LIMIT 1
                    ) AS id_certificado,
                    (
                        SELECT c.codigo_autenticidade
                        FROM campeonato_certificados c
                        JOIN campeonato_inscricoes ci ON ci.id_inscricao = c.id_inscricao
                        WHERE c.id_campeonato = i.id_campeonato
                          AND ci.id_filiado = i.id_filiado
                        ORDER BY c.data_emissao DESC, c.id_certificado DESC
                        LIMIT 1
                    ) AS codigo_autenticidade
                FROM campeonato_inscricoes i
                WHERE i.id_campeonato = ? AND i.status_elegibilidade = 'aprovada'
                GROUP BY i.id_campeonato, i.id_filiado
                ORDER BY nome_titular";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $idCampeonato);
        $stmt->execute();
        $res = $stmt->get_result();
        $lista = [];
        while ($row = $res->fetch_assoc()) {
            $lista[] = $row;
        }
        $stmt->close();
        return $lista;
    }

    public function buscarPorAtleta(int $idCampeonato, int $idFiliado): ?array {
        $sql = "SELECT c.*
                FROM campeonato_certificados c
                JOIN campeonato_inscricoes i ON i.id_inscricao = c.id_inscricao
                WHERE c.id_campeonato = ? AND i.id_filiado = ?
                ORDER BY c.data_emissao DESC, c.id_certificado DESC
                LIMIT 1";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("ii", $idCampeonato, $idFiliado);
        $stmt->execute();
        $certificado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $certificado ?: null;
    }

    public function buscarPorId(int $idCertificado): ?array {
        $sql = "SELECT
                    c.*,
                    i.id_filiado,
                    i.dojo_snapshot AS dojo,
                    e.titulo AS campeonato_titulo,
                    e.data_inicio AS campeonato_data_inicio,
                    e.data_fim AS campeonato_data_fim,
                    e.cidade AS campeonato_cidade,
                    e.estado AS campeonato_estado,
                    e.status AS campeonato_status
                FROM campeonato_certificados c
                JOIN campeonato_inscricoes i ON i.id_inscricao = c.id_inscricao
                JOIN campeonato_eventos e ON e.id_campeonato = c.id_campeonato
                WHERE c.id_certificado = ?
                LIMIT 1";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $idCertificado);
        $stmt->execute();
        $certificado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $certificado ?: null;
    }

    public function buscarResultadosAtleta(int $idCampeonato, int $idFiliado): array {
        $sql = "SELECT cat.nome AS categoria, cat.modalidade, cl.colocacao, cl.medalha
                FROM campeonato_classificacao cl
                JOIN campeonato_inscricoes i ON i.id_inscricao = cl.id_inscricao
                JOIN campeonato_categorias cat ON cat.id_categoria = cl.id_categoria
                WHERE cl.id_campeonato = ? AND i.id_filiado = ?
                ORDER BY cat.modalidade, cat.nome, cl.colocacao";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("ii", $idCampeonato, $idFiliado);
        $stmt->execute();
        $res = $stmt->get_result();
        $lista = [];
        while ($row = $res->fetch_assoc()) {
            $lista[] = $row;
        }
        $stmt->close();
        return $lista;
    }

    public function buscarPorHash(string $hash): ?array {
        $hash = trim(strtoupper($hash));
        $sql = "SELECT 
                    c.*, 
                    e.titulo AS campeonato_titulo,
                    cat.nome AS categoria_nome,
                    i.dojo_snapshot AS dojo
                FROM campeonato_certificados c
                JOIN campeonato_eventos e ON c.id_campeonato = e.id_campeonato
                JOIN campeonato_inscricoes i ON c.id_inscricao = i.id_inscricao
                JOIN campeonato_categorias cat ON i.id_categoria = cat.id_categoria
                WHERE c.codigo_autenticidade = ?";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("s", $hash);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function criarCertificado(array $dados): int {
        $sql = "INSERT INTO campeonato_certificados (
            id_campeonato, id_inscricao, nome_titular, tipo_certificado, codigo_autenticidade, arquivo_pdf_nome, resultados_snapshot
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexao->prepare($sql);
        $idCamp = (int)$dados['id_campeonato'];
        $idIns = (int)$dados['id_inscricao'];
        $resultadosSnapshot = json_encode(
            $dados['resultados_snapshot'] ?? [],
            JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
        );

        $stmt->bind_param("iisssss", $idCamp, $idIns, $dados['nome_titular'], $dados['tipo_certificado'], $dados['codigo_autenticidade'], $dados['arquivo_pdf_nome'], $resultadosSnapshot);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }
}
