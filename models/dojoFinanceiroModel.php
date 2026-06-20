<?php
include_once __DIR__ . "/../db/conexao.php";

class DojoFinanceiroModel
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function registrarMovimentacao($descricao, $tipo, $valor, $data_movimentacao, $categoria, $id_referencia = null): bool
    {
        try {
            $stmt = $this->conexao->prepare("
                INSERT INTO dojo_financeiro (descricao, tipo, valor, data_movimentacao, categoria, id_referencia)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("ssdssi", $descricao, $tipo, $valor, $data_movimentacao, $categoria, $id_referencia);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao registrar movimentação financeira: " . $e->getMessage());
            return false;
        }
    }

    public function excluirMovimentacao($id): bool
    {
        try {
            $stmt = $this->conexao->prepare("DELETE FROM dojo_financeiro WHERE id = ?");
            $stmt->bind_param("i", $id);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao excluir movimentação financeira: " . $e->getMessage());
            return false;
        }
    }

    public function buscarMovimentacoesMes($referencia_mes): array
    {
        $movimentacoes = [];
        $data_inicio = $referencia_mes . '-01';
        $data_fim = date("Y-m-t", strtotime($data_inicio));

        $query = "
            SELECT * FROM dojo_financeiro 
            WHERE data_movimentacao BETWEEN ? AND ?
            ORDER BY data_movimentacao DESC, id DESC
        ";

        try {
            $stmt = $this->conexao->prepare($query);
            $stmt->bind_param("ss", $data_inicio, $data_fim);
            $stmt->execute();
            $result = $stmt->get_result();
            $movimentacoes = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } catch (Exception $e) {
            error_log("Erro ao buscar movimentações financeiras: " . $e->getMessage());
        }
        return $movimentacoes;
    }

    public function buscarResumoFinanceiroMes($referencia_mes): array
    {
        $resumo = ['entradas' => 0.00, 'saidas' => 0.00, 'saldo' => 0.00];
        $data_inicio = $referencia_mes . '-01';
        $data_fim = date("Y-m-t", strtotime($data_inicio));

        $query = "
            SELECT tipo, SUM(valor) as total 
            FROM dojo_financeiro 
            WHERE data_movimentacao BETWEEN ? AND ?
            GROUP BY tipo
        ";

        try {
            $stmt = $this->conexao->prepare($query);
            $stmt->bind_param("ss", $data_inicio, $data_fim);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                if ($row['tipo'] == 'entrada') {
                    $resumo['entradas'] = floatval($row['total']);
                } elseif ($row['tipo'] == 'saida') {
                    $resumo['saidas'] = floatval($row['total']);
                }
            }
            $resumo['saldo'] = $resumo['entradas'] - $resumo['saidas'];
            $stmt->close();
        } catch (Exception $e) {
            error_log("Erro ao calcular resumo financeiro: " . $e->getMessage());
        }
        return $resumo;
    }

    public function buscarResumoAnual($ano): array
    {
        $dados = [];
        $query = "
            SELECT 
                DATE_FORMAT(data_movimentacao, '%Y-%m') AS mes_ref,
                SUM(CASE WHEN tipo = 'entrada' THEN valor ELSE 0 END) AS entradas,
                SUM(CASE WHEN tipo = 'saida' THEN valor ELSE 0 END) AS saidas
            FROM dojo_financeiro
            WHERE YEAR(data_movimentacao) = ?
            GROUP BY DATE_FORMAT(data_movimentacao, '%Y-%m')
            ORDER BY mes_ref ASC
        ";
        try {
            $stmt = $this->conexao->prepare($query);
            $stmt->bind_param("i", $ano);
            $stmt->execute();
            $result = $stmt->get_result();
            $dados = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } catch (Exception $e) {
            error_log("Erro ao buscar resumo anual: " . $e->getMessage());
        }
        return $dados;
    }
}
?>
