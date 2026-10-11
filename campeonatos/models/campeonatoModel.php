<?php
/**
 * Modelo de Campeonato (campeonato_eventos)
 * Padrão IDÊNTICO ao site principal (models/campeonatoModel.php)
 */

include_once __DIR__ . "/../db/conexao.php";

class Campeonato {

    private $conexao;

    public function __construct() {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function buscarTodos(): array {
        $lista = [];
        $res = $this->conexao->query("SELECT * FROM campeonato_eventos ORDER BY data_inicio DESC");
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $lista[] = $row;
            }
        }
        return $lista;
    }

    public function buscarPorId($id): ?array {
        $id = (int)$id;
        $stmt = $this->conexao->prepare("SELECT * FROM campeonato_eventos WHERE id_campeonato = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function criarCampeonato(array $dados): int {
        $sql = "INSERT INTO campeonato_eventos (
            titulo, subtitulo, descricao, edicao, ano, data_inicio, data_fim,
            data_pesagem, data_referencia_idade, local_nome, local_endereco,
            cidade, estado, status, limite_atletas
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexao->prepare($sql);
        $limite = $dados['limite_atletas'] ? (int)$dados['limite_atletas'] : null;

        $stmt->bind_param(
            "ssssissssssssii",
            $dados['titulo'],
            $dados['subtitulo'],
            $dados['descricao'],
            $dados['edicao'],
            $dados['ano'],
            $dados['data_inicio'],
            $dados['data_fim'],
            $dados['data_pesagem'],
            $dados['data_referencia_idade'],
            $dados['local_nome'],
            $dados['local_endereco'],
            $dados['cidade'],
            $dados['estado'],
            $dados['status'],
            $limite
        );

        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    public function editarCampeonato(int $id, array $dados): bool {
        $sql = "UPDATE campeonato_eventos SET 
            titulo = ?, subtitulo = ?, descricao = ?, edicao = ?, ano = ?,
            data_inicio = ?, data_fim = ?, data_pesagem = ?, data_referencia_idade = ?,
            local_nome = ?, local_endereco = ?, cidade = ?, estado = ?, status = ?,
            limite_atletas = ?
            WHERE id_campeonato = ?";

        $stmt = $this->conexao->prepare($sql);
        $limite = $dados['limite_atletas'] ? (int)$dados['limite_atletas'] : null;

        $stmt->bind_param(
            "ssssissssssssiii",
            $dados['titulo'],
            $dados['subtitulo'],
            $dados['descricao'],
            $dados['edicao'],
            $dados['ano'],
            $dados['data_inicio'],
            $dados['data_fim'],
            $dados['data_pesagem'],
            $dados['data_referencia_idade'],
            $dados['local_nome'],
            $dados['local_endereco'],
            $dados['cidade'],
            $dados['estado'],
            $dados['status'],
            $limite,
            $id
        );

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function excluirCampeonato(int $id): bool {
        $stmt = $this->conexao->prepare("DELETE FROM campeonato_eventos WHERE id_campeonato = ?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function atualizarStatus(int $id, string $status): bool {
        $stmt = $this->conexao->prepare("UPDATE campeonato_eventos SET status = ? WHERE id_campeonato = ?");
        $stmt->bind_param("si", $status, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
