<?php
include_once __DIR__ . "/../db/conexao.php";

class DojoAlunoConfigModel
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function buscarPorFiliado($id_filiado)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM dojo_alunos_config WHERE id_filiado = ?");
            $stmt->bind_param("i", $id_filiado);
            $stmt->execute();
            $result = $stmt->get_result();
            $dados = $result->fetch_assoc();
            $stmt->close();

            if (!$dados) {
                // Configuração padrão provisória
                return [
                    'id_filiado' => $id_filiado,
                    'valor_mensalidade' => 100.00,
                    'dia_vencimento' => 10,
                    'status_aluno' => 'adimplente'
                ];
            }
            return $dados;
        } catch (Exception $e) {
            error_log("Erro ao buscar config do aluno: " . $e->getMessage());
            return null;
        }
    }

    public function salvarConfiguracao($id_filiado, $valor, $dia_vencimento, $status_aluno): bool
    {
        try {
            $stmt = $this->conexao->prepare("
                INSERT INTO dojo_alunos_config (id_filiado, valor_mensalidade, dia_vencimento, status_aluno)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE valor_mensalidade = ?, dia_vencimento = ?, status_aluno = ?
            ");
            $stmt->bind_param("ididsis", $id_filiado, $valor, $dia_vencimento, $status_aluno, $valor, $dia_vencimento, $status_aluno);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao salvar config do aluno: " . $e->getMessage());
            return false;
        }
    }

    public function listarAlunosConfig(): array
    {
        $alunos = [];
        // Seleciona todos os filiados confirmados, faz LEFT JOIN com dojo_alunos_config e calcula o status financeiro dinamicamente
        $query = "
            SELECT f.id_filiado, f.nome, f.telefone, f.email, g.graduacao,
                   COALESCE(c.valor_mensalidade, 100.00) AS valor_mensalidade,
                   COALESCE(c.dia_vencimento, 10) AS dia_vencimento,
                   (
                       SELECT r.status 
                       FROM dojo_recorrencias r 
                       WHERE r.id_filiado = f.id_filiado AND r.status IN ('pendente', 'ativo', 'cancelando') 
                       ORDER BY r.id DESC LIMIT 1
                   ) AS recorrencia_status,
                   CASE 
                       WHEN c.status_aluno = 'pausado' THEN 'pausado'
                       WHEN EXISTS (
                           SELECT 1 
                           FROM dojo_mensalidades m 
                           WHERE m.id_filiado = f.id_filiado AND m.status_pagamento = 'atrasado'
                       ) THEN 'inadimplente'
                       ELSE 'adimplente'
                   END AS status_aluno
            FROM filiados f
            LEFT JOIN graduacoes g ON g.id_graduacao = COALESCE(
                (SELECT id_graduacao FROM filiados_graduacoes WHERE id_filiado = f.id_filiado AND id_arte = 1 LIMIT 1), 
                (SELECT id_graduacao FROM filiados_graduacoes WHERE id_filiado = f.id_filiado LIMIT 1)
            )
            LEFT JOIN dojo_alunos_config c ON f.id_filiado = c.id_filiado
            WHERE f.confirmacao = 'sim'
            ORDER BY f.nome ASC
        ";

        $resultado = $this->conexao->query($query);
        if ($resultado) {
            $alunos = $resultado->fetch_all(MYSQLI_ASSOC);
            $resultado->free();
        }
        return $alunos;
    }

    public function atualizarStatus($id_filiado, $status): bool
    {
        try {
            // Garante que o registro exista antes de atualizar
            $config = $this->buscarPorFiliado($id_filiado);
            $valor = $config['valor_mensalidade'];
            $vencimento = $config['dia_vencimento'];

            $stmt = $this->conexao->prepare("
                INSERT INTO dojo_alunos_config (id_filiado, valor_mensalidade, dia_vencimento, status_aluno)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE status_aluno = ?
            ");
            $stmt->bind_param("idiss", $id_filiado, $valor, $vencimento, $status, $status);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao atualizar status do aluno: " . $e->getMessage());
            return false;
        }
    }

    public function contarStatus(): array
    {
        $counts = ['adimplente' => 0, 'inadimplente' => 0, 'pausado' => 0];
        
        $query = "
            SELECT 
                status_aluno,
                COUNT(*) as total
            FROM (
                SELECT 
                    CASE 
                        WHEN c.status_aluno = 'pausado' THEN 'pausado'
                        WHEN EXISTS (
                            SELECT 1 
                            FROM dojo_mensalidades m 
                            WHERE m.id_filiado = f.id_filiado AND m.status_pagamento = 'atrasado'
                        ) THEN 'inadimplente'
                        ELSE 'adimplente'
                    END AS status_aluno
                FROM filiados f
                LEFT JOIN dojo_alunos_config c ON f.id_filiado = c.id_filiado
                WHERE f.confirmacao = 'sim'
            ) AS t
            GROUP BY status_aluno
        ";

        $resultado = $this->conexao->query($query);
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $status = strtolower($row['status_aluno']);
                if (array_key_exists($status, $counts)) {
                    $counts[$status] = intval($row['total']);
                }
            }
            $resultado->free();
        }
        return $counts;
    }
}
?>
