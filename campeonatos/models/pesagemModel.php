<?php
/**
 * Modelo de Pesagem Oficial (campeonato_pesagens)
 * Padrão IDÊNTICO ao site principal (models/pesagemModel.php)
 */

include_once __DIR__ . "/../db/conexao.php";

class Pesagem {

    private $conexao;

    public function __construct() {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function buscarPorCampeonato(int $idCampeonato): array {
        $sql = "SELECT 
                    p.*, 
                    i.nome_atleta_snapshot AS nome_atleta, 
                    i.dojo_snapshot AS dojo,
                    c.nome AS categoria_nome,
                    u.nome AS operador_nome
                FROM campeonato_pesagens p
                JOIN campeonato_inscricoes i ON p.id_inscricao = i.id_inscricao
                JOIN campeonato_categorias c ON i.id_categoria = c.id_categoria
                LEFT JOIN campeonato_usuarios u ON p.operador_id = u.id_usuario
                WHERE p.id_campeonato = ?
                ORDER BY p.created_at DESC";

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

    public function contarTentativas(int $idInscricao): int {
        $stmt = $this->conexao->prepare("SELECT COUNT(*) AS total FROM campeonato_pesagens WHERE id_inscricao = ?");
        $stmt->bind_param("i", $idInscricao);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int)($row['total'] ?? 0);
    }

    public function validarPeso(array $categoria, float $pesoAferido): array {
        if ($categoria['exige_pesagem'] == 0) {
            return ['aprovado' => true, 'motivo' => 'Categoria não exige pesagem'];
        }

        $pesoMin = $categoria['peso_min'] !== null ? (float)$categoria['peso_min'] : null;
        $pesoMax = $categoria['peso_max'] !== null ? (float)$categoria['peso_max'] : null;
        $tolerancia = (float)($categoria['tolerancia_peso_kg'] ?? 0.0);

        if ($pesoMin !== null && $pesoAferido < $pesoMin) {
            return [
                'aprovado' => false,
                'motivo' => "Peso aferido ({$pesoAferido} kg) abaixo do mínimo exigido ({$pesoMin} kg)."
            ];
        }

        if ($pesoMax !== null) {
            $limiteComTolerancia = $pesoMax + $tolerancia;
            if ($pesoAferido > $limiteComTolerancia) {
                return [
                    'aprovado' => false,
                    'motivo' => "Peso aferido ({$pesoAferido} kg) excedeu o peso máximo ({$pesoMax} kg) mesmo com tolerância (+{$tolerancia} kg)."
                ];
            }
        }

        return ['aprovado' => true, 'motivo' => 'Peso dentro dos limites regulamentares'];
    }

    public function registrarPesagem(array $dados): int {
        $sql = "INSERT INTO campeonato_pesagens (
            id_inscricao, id_campeonato, peso_aferido, resultado, tentativa, operador_id, observacoes
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexao->prepare($sql);
        $idIns = (int)$dados['id_inscricao'];
        $idCamp = (int)$dados['id_campeonato'];
        $peso = (float)$dados['peso_aferido'];
        $tent = (int)$dados['tentativa'];
        $op = (int)$dados['operador_id'];

        $stmt->bind_param(
            "iidsiis",
            $idIns,
            $idCamp,
            $peso,
            $dados['resultado'],
            $tent,
            $op,
            $dados['observacoes']
        );

        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }
}
