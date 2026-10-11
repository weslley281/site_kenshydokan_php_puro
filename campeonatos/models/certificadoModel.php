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
            id_campeonato, id_inscricao, nome_titular, tipo_certificado, codigo_autenticidade, arquivo_pdf_nome
        ) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexao->prepare($sql);
        $idCamp = (int)$dados['id_campeonato'];
        $idIns = (int)$dados['id_inscricao'];

        $stmt->bind_param("iissss", $idCamp, $idIns, $dados['nome_titular'], $dados['tipo_certificado'], $dados['codigo_autenticidade'], $dados['arquivo_pdf_nome']);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }
}
