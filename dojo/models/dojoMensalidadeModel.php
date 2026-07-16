<?php
include_once __DIR__ . "/../db/conexao.php";

class DojoMensalidadeModel
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function buscarMensalidadePorId($id)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM dojo_mensalidades WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $dados = $result->fetch_assoc();
            $stmt->close();
            return $dados;
        } catch (Exception $e) {
            error_log("Erro ao buscar mensalidade por ID: " . $e->getMessage());
            return null;
        }
    }

    public function buscarMensalidadesPorFiliado($id_filiado): array
    {
        $mensalidades = [];
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM dojo_mensalidades WHERE id_filiado = ? ORDER BY referencia DESC");
            $stmt->bind_param("i", $id_filiado);
            $stmt->execute();
            $result = $stmt->get_result();
            $mensalidades = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } catch (Exception $e) {
            error_log("Erro ao buscar histórico de mensalidades: " . $e->getMessage());
        }
        return $mensalidades;
    }

    public function buscarMensalidadesReferencia($referencia): array
    {
        $mensalidades = [];
        $query = "
            SELECT m.*, f.nome, 
                   CASE 
                       WHEN c.status_aluno = 'pausado' THEN 'pausado'
                       WHEN EXISTS (
                           SELECT 1 
                           FROM dojo_mensalidades m2 
                           WHERE m2.id_filiado = f.id_filiado AND m2.status_pagamento = 'atrasado'
                       ) THEN 'inadimplente'
                       ELSE 'adimplente'
                   END AS status_aluno
            FROM dojo_mensalidades m
            INNER JOIN filiados f ON m.id_filiado = f.id_filiado
            LEFT JOIN dojo_alunos_config c ON m.id_filiado = c.id_filiado
            WHERE m.referencia = ?
            ORDER BY f.nome ASC
        ";

        try {
            $stmt = $this->conexao->prepare($query);
            $stmt->bind_param("s", $referencia);
            $stmt->execute();
            $result = $stmt->get_result();
            $mensalidades = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } catch (Exception $e) {
            error_log("Erro ao buscar mensalidades por referência: " . $e->getMessage());
        }
        return $mensalidades;
    }

    public function criarMensalidade($id_filiado, $referencia, $valor, $data_vencimento): bool
    {
        try {
            $stmt = $this->conexao->prepare("
                INSERT INTO dojo_mensalidades (id_filiado, referencia, valor, data_vencimento, status_pagamento)
                VALUES (?, ?, ?, ?, 'pendente')
            ");
            $stmt->bind_param("isds", $id_filiado, $referencia, $valor, $data_vencimento);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao criar mensalidade: " . $e->getMessage());
            return false;
        }
    }

    public function receberMensalidade($id_mensalidade, $data_pagamento = null): bool
    {
        if ($data_pagamento === null) {
            $data_pagamento = date("Y-m-d");
        }

        try {
            $stmt = $this->conexao->prepare("
                UPDATE dojo_mensalidades 
                SET data_pagamento = ?, status_pagamento = 'pago' 
                WHERE id = ?
            ");
            $stmt->bind_param("si", $data_pagamento, $id_mensalidade);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao receber mensalidade: " . $e->getMessage());
            return false;
        }
    }

    public function gerarMensalidadesDoMes($referencia): array
    {
        $stats = ['criados' => 0, 'erros' => 0, 'pulados' => 0];

        // Seleciona todos os filiados ativos (confirmacao = 'sim' e status_aluno != 'pausado')
        $query = "
            SELECT f.id_filiado, 
                   COALESCE(c.valor_mensalidade, 100.00) AS valor,
                   COALESCE(c.dia_vencimento, 10) AS vencimento,
                   COALESCE(c.status_aluno, 'adimplente') AS status
            FROM filiados f
            LEFT JOIN dojo_alunos_config c ON f.id_filiado = c.id_filiado
            WHERE f.confirmacao = 'sim' AND COALESCE(c.status_aluno, 'adimplente') != 'pausado'
        ";

        $result_filiados = $this->conexao->query($query);
        if ($result_filiados) {
            while ($aluno = $result_filiados->fetch_assoc()) {
                $id_filiado = $aluno['id_filiado'];
                $valor = $aluno['valor'];
                $dia = str_pad($aluno['vencimento'], 2, '0', STR_PAD_LEFT);
                $data_vencimento = $referencia . '-' . $dia;

                // Verifica se já existe para evitar erro de UNIQUE KEY
                $check = $this->conexao->prepare("SELECT id FROM dojo_mensalidades WHERE id_filiado = ? AND referencia = ?");
                $check->bind_param("is", $id_filiado, $referencia);
                $check->execute();
                $check->store_result();
                $existe = $check->num_rows > 0;
                $check->close();

                if ($existe) {
                    $stats['pulados']++;
                    continue;
                }

                if ($this->criarMensalidade($id_filiado, $referencia, $valor, $data_vencimento)) {
                    $stats['criados']++;
                } else {
                    $stats['erros']++;
                }
            }
            $result_filiados->free();
        }
        return $stats;
    }

    public function atualizarInadimplencias(): void
    {
        $hoje = date("Y-m-d");
        // Atualiza status de pendente para atrasado se a data de vencimento passou
        $query = "
            UPDATE dojo_mensalidades 
            SET status_pagamento = 'atrasado' 
            WHERE status_pagamento = 'pendente' AND data_vencimento < ?
        ";
        try {
            $stmt = $this->conexao->prepare($query);
            $stmt->bind_param("s", $hoje);
            $stmt->execute();
            $stmt->close();

            // Atualiza o status_aluno na config para 'inadimplente' se tiver mensalidades atrasadas
            $query_filiados = "
                UPDATE dojo_alunos_config c
                SET c.status_aluno = 'inadimplente'
                WHERE c.id_filiado IN (
                    SELECT DISTINCT id_filiado FROM dojo_mensalidades WHERE status_pagamento = 'atrasado'
                ) AND c.status_aluno = 'adimplente'
            ";
            $this->conexao->query($query_filiados);

            // Atualiza para 'adimplente' os alunos que pagaram tudo e não têm nenhuma mensalidade atrasada
            $query_adimplentes = "
                UPDATE dojo_alunos_config c
                SET c.status_aluno = 'adimplente'
                WHERE c.id_filiado NOT IN (
                    SELECT DISTINCT id_filiado FROM dojo_mensalidades WHERE status_pagamento = 'atrasado'
                ) AND c.status_aluno = 'inadimplente'
            ";
            $this->conexao->query($query_adimplentes);
        } catch (Exception $e) {
            error_log("Erro ao atualizar inadimplências: " . $e->getMessage());
        }
    }
}
?>
