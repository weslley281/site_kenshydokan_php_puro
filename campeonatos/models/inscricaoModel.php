<?php
/**
 * Modelo de Inscrição com Snapshot Imutável (campeonato_inscricoes)
 * Padrão IDÊNTICO ao site principal (models/inscricaoModel.php)
 */

include_once __DIR__ . "/../db/conexao.php";

class Inscricao {

    private $conexao;

    public function __construct() {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function buscarPorCampeonato(int $idCampeonato, ?int $idCategoria = null): array {
        $sql = "SELECT 
                    i.*, 
                    c.nome AS categoria_nome, 
                    c.modalidade AS categoria_modalidade,
                    c.peso_min,
                    c.peso_max,
                    c.tolerancia_peso_kg,
                    c.exige_pesagem
                FROM campeonato_inscricoes i
                JOIN campeonato_categorias c ON i.id_categoria = c.id_categoria
                WHERE i.id_campeonato = ?";

        if ($idCategoria !== null) {
            $sql .= " AND i.id_categoria = ?";
        }

        $sql .= " ORDER BY c.nome ASC, i.nome_atleta_snapshot ASC";

        $stmt = $this->conexao->prepare($sql);
        if ($idCategoria !== null) {
            $stmt->bind_param("ii", $idCampeonato, $idCategoria);
        } else {
            $stmt->bind_param("i", $idCampeonato);
        }

        $stmt->execute();
        $res = $stmt->get_result();
        $lista = [];
        while ($row = $res->fetch_assoc()) {
            $lista[] = $row;
        }
        $stmt->close();
        return $lista;
    }

    public function buscarPorId($id): ?array {
        $id = (int)$id;
        $sql = "SELECT 
                    i.*, 
                    c.nome AS categoria_nome, 
                    c.modalidade AS categoria_modalidade,
                    c.peso_min,
                    c.peso_max,
                    c.tolerancia_peso_kg,
                    c.exige_pesagem
                FROM campeonato_inscricoes i
                JOIN campeonato_categorias c ON i.id_categoria = c.id_categoria
                WHERE i.id_inscricao = ?";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function jaInscritoNaCategoria(int $idCategoria, int $idFiliado): bool {
        $stmt = $this->conexao->prepare("SELECT id_inscricao FROM campeonato_inscricoes WHERE id_categoria = ? AND id_filiado = ?");
        $stmt->bind_param("ii", $idCategoria, $idFiliado);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return !empty($res);
    }

    public function criarComSnapshot(array $dados): int {
        $sql = "INSERT INTO campeonato_inscricoes (
            id_campeonato, id_categoria, id_filiado,
            nome_atleta_snapshot, dojo_snapshot, graduacao_id_snapshot, graduacao_nome_snapshot,
            data_nascimento_snapshot, idade_evento, sexo, peso_declarado,
            status_elegibilidade
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexao->prepare($sql);
        $idCamp = (int)$dados['id_campeonato'];
        $idCat = (int)$dados['id_categoria'];
        $idFil = (int)$dados['id_filiado'];
        $gradId = (int)$dados['graduacao_id_snapshot'];
        $idade = (int)$dados['idade_evento'];
        $peso = isset($dados['peso_declarado']) && $dados['peso_declarado'] !== '' ? (float)$dados['peso_declarado'] : null;

        $stmt->bind_param(
            "iiississssds",
            $idCamp,
            $idCat,
            $idFil,
            $dados['nome_atleta_snapshot'],
            $dados['dojo_snapshot'],
            $gradId,
            $dados['graduacao_nome_snapshot'],
            $dados['data_nascimento_snapshot'],
            $idade,
            $dados['sexo'],
            $peso,
            $dados['status_elegibilidade']
        );

        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    public function atualizarStatus(int $id, string $status, ?string $motivo = null): bool {
        $stmt = $this->conexao->prepare("UPDATE campeonato_inscricoes SET status_elegibilidade = ?, motivo_ineligibilidade = ? WHERE id_inscricao = ?");
        $stmt->bind_param("ssi", $status, $motivo, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function atualizarCategoria(int $id, int $novaCategoriaId): bool {
        $stmt = $this->conexao->prepare("UPDATE campeonato_inscricoes SET id_categoria = ? WHERE id_inscricao = ?");
        $stmt->bind_param("ii", $novaCategoriaId, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function excluirInscricao(int $id): bool {
        $stmt = $this->conexao->prepare("DELETE FROM campeonato_inscricoes WHERE id_inscricao = ?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
