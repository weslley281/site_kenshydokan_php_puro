<?php
/**
 * Modelo de Categoria (campeonato_categorias)
 * Padrão IDÊNTICO ao site principal (models/categoriaModel.php)
 */

include_once __DIR__ . "/../db/conexao.php";

class Categoria {

    private $conexao;

    public function __construct() {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function buscarPorCampeonato(int $idCampeonato): array {
        $lista = [];
        $stmt = $this->conexao->prepare("SELECT * FROM campeonato_categorias WHERE id_campeonato = ? ORDER BY modalidade, sexo, idade_min, peso_min");
        $stmt->bind_param("i", $idCampeonato);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $lista[] = $row;
        }
        $stmt->close();
        return $lista;
    }

    public function buscarPorId($id): ?array {
        $id = (int)$id;
        $stmt = $this->conexao->prepare("SELECT * FROM campeonato_categorias WHERE id_categoria = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function criarCategoria(array $dados): int {
        $sql = "INSERT INTO campeonato_categorias (
            id_campeonato, nome, modalidade, sexo, idade_min, idade_max,
            peso_min, peso_max, tolerancia_peso_kg, duracao_luta_segundos,
            tipo_chaveamento, exige_pesagem
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexao->prepare($sql);
        $idCamp = (int)$dados['id_campeonato'];
        $duracao = isset($dados['duracao_luta_segundos']) ? (int)$dados['duracao_luta_segundos'] : 180;
        $tipoChave = $dados['tipo_chaveamento'] ?? 'eliminatoria_simples';
        $exigePesagem = isset($dados['exige_pesagem']) ? (int)$dados['exige_pesagem'] : 1;

        $stmt->bind_param(
            "isssiiddsisi",
            $idCamp,
            $dados['nome'],
            $dados['modalidade'],
            $dados['sexo'],
            $dados['idade_min'],
            $dados['idade_max'],
            $dados['peso_min'],
            $dados['peso_max'],
            $dados['tolerancia_peso_kg'],
            $duracao,
            $tipoChave,
            $exigePesagem
        );

        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    public function editarCategoria(int $id, array $dados): bool {
        $sql = "UPDATE campeonato_categorias SET 
            nome = ?, modalidade = ?, sexo = ?, idade_min = ?, idade_max = ?,
            peso_min = ?, peso_max = ?, tolerancia_peso_kg = ?, duracao_luta_segundos = ?,
            tipo_chaveamento = ?, exige_pesagem = ?
            WHERE id_categoria = ?";

        $stmt = $this->conexao->prepare($sql);
        $duracao = isset($dados['duracao_luta_segundos']) ? (int)$dados['duracao_luta_segundos'] : 180;
        $tipoChave = $dados['tipo_chaveamento'] ?? 'eliminatoria_simples';
        $exigePesagem = isset($dados['exige_pesagem']) ? (int)$dados['exige_pesagem'] : 1;

        $stmt->bind_param(
            "sssiddsisi",
            $dados['nome'],
            $dados['modalidade'],
            $dados['sexo'],
            $dados['idade_min'],
            $dados['idade_max'],
            $dados['peso_min'],
            $dados['peso_max'],
            $dados['tolerancia_peso_kg'],
            $duracao,
            $tipoChave,
            $exigePesagem,
            $id
        );

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function excluirCategoria(int $id): bool {
        $stmt = $this->conexao->prepare("DELETE FROM campeonato_categorias WHERE id_categoria = ?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
