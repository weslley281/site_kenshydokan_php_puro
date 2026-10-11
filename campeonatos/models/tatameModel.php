<?php
/**
 * Modelo de Áreas de Tatame (campeonato_tatames)
 * Padrão IDÊNTICO ao site principal (models/tatameModel.php)
 */

include_once __DIR__ . "/../db/conexao.php";

class Tatame {

    private $conexao;

    public function __construct() {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function buscarPorCampeonato(int $idCampeonato): array {
        $sql = "SELECT t.*, u.nome AS operador_nome
                FROM campeonato_tatames t
                LEFT JOIN campeonato_usuarios u ON t.operador_id = u.id_usuario
                WHERE t.id_campeonato = ?
                ORDER BY t.numero ASC";

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

    public function buscarPorId(int $idTatame): ?array {
        $stmt = $this->conexao->prepare("SELECT * FROM campeonato_tatames WHERE id_tatame = ?");
        $stmt->bind_param("i", $idTatame);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function criarTatame(array $dados): int {
        $sql = "INSERT INTO campeonato_tatames (id_campeonato, numero, nome, status) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexao->prepare($sql);
        $idCamp = (int)$dados['id_campeonato'];
        $num = (int)$dados['numero'];
        $status = $dados['status'] ?? 'disponivel';

        $stmt->bind_param("iiss", $idCamp, $num, $dados['nome'], $status);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    public function atualizarStatus(int $idTatame, string $status, ?int $lutaId = null, ?int $operadorId = null): bool {
        $stmt = $this->conexao->prepare("UPDATE campeonato_tatames SET status = ?, luta_em_andamento_id = ?, operador_id = ? WHERE id_tatame = ?");
        $stmt->bind_param("siii", $status, $lutaId, $operadorId, $idTatame);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
