<?php
// models/listaPresencaModel.php
include_once __DIR__ . "/../db/conexao.php";

class ListaPresencaModel
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function salvarPresenca($id_filiado, $id_arte, $data_presenca, $status, $conteudo_aula): bool
    {
        try {
            $stmt = $this->conexao->prepare("
                INSERT INTO lista_presenca (id_filiado, id_arte, data_presenca, status, conteudo_aula)
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE status = ?, conteudo_aula = ?
            ");
            $stmt->bind_param("iisssss", $id_filiado, $id_arte, $data_presenca, $status, $conteudo_aula, $status, $conteudo_aula);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        } catch (Exception $e) {
            error_log("Erro ao salvar presenca: " . $e->getMessage());
            return false;
        }
    }

    public function registrarChamadaLote($id_arte, $data_presenca, $conteudo_aula, $filiadosStatus): bool
    {
        $this->conexao->begin_transaction();
        try {
            // Remove registros existentes daquele dia e daquela modalidade para evitar duplicatas orfas
            $stmtDel = $this->conexao->prepare("DELETE FROM lista_presenca WHERE id_arte = ? AND data_presenca = ?");
            $stmtDel->bind_param("is", $id_arte, $data_presenca);
            $stmtDel->execute();
            $stmtDel->close();

            // Insere os novos dados
            $stmtIns = $this->conexao->prepare("
                INSERT INTO lista_presenca (id_filiado, id_arte, data_presenca, status, conteudo_aula)
                VALUES (?, ?, ?, ?, ?)
            ");

            foreach ($filiadosStatus as $id_filiado => $status) {
                // status: 'P' (presente) ou 'F' (falta)
                $stmtIns->bind_param("iisss", $id_filiado, $id_arte, $data_presenca, $status, $conteudo_aula);
                $stmtIns->execute();
            }
            $stmtIns->close();

            $this->conexao->commit();
            return true;
        } catch (Exception $e) {
            $this->conexao->rollback();
            error_log("Erro ao registrar chamada em lote: " . $e->getMessage());
            return false;
        }
    }

    public function buscarChamadaPorDataEArte($id_arte, $data_presenca): array
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT id_filiado, status 
                FROM lista_presenca 
                WHERE id_arte = ? AND data_presenca = ?
            ");
            $stmt->bind_param("is", $id_arte, $data_presenca);
            $stmt->execute();
            $result = $stmt->get_result();
            $lista = [];
            while ($row = $result->fetch_assoc()) {
                $lista[$row['id_filiado']] = $row['status'];
            }
            $stmt->close();
            return $lista;
        } catch (Exception $e) {
            error_log("Erro ao buscar chamada por data e arte: " . $e->getMessage());
            return [];
        }
    }

    public function buscarFrequenciaPorFiliado($id_filiado): array
    {
        // Retorna a frequencia total por modalidade do aluno
        $query = "
            SELECT 
                a.id_arte, 
                a.nome AS modalidade,
                COUNT(p.id) AS total_aulas,
                SUM(CASE WHEN p.status = 'P' THEN 1 ELSE 0 END) AS total_presencas,
                SUM(CASE WHEN p.status = 'F' THEN 1 ELSE 0 END) AS total_faltas
            FROM artes_marciais a
            INNER JOIN filiados_graduacoes fg ON a.id_arte = fg.id_arte
            LEFT JOIN lista_presenca p ON a.id_arte = p.id_arte AND p.id_filiado = fg.id_filiado
            WHERE fg.id_filiado = ?
            GROUP BY a.id_arte
        ";
        try {
            $stmt = $this->conexao->prepare($query);
            $stmt->bind_param("i", $id_filiado);
            $stmt->execute();
            $result = $stmt->get_result();
            $resumos = [];
            while ($row = $result->fetch_assoc()) {
                $total = intval($row['total_aulas']);
                $presencas = intval($row['total_presencas']);
                $frequencia = ($total > 0) ? round(($presencas / $total) * 100, 1) : 100.0;
                
                $row['taxa_frequencia'] = $frequencia;
                $resumos[] = $row;
            }
            $stmt->close();
            return $resumos;
        } catch (Exception $e) {
            error_log("Erro ao buscar frequencia por filiado: " . $e->getMessage());
            return [];
        }
    }

    public function listarPresencasDetalhadoPorFiliadoEArte($id_filiado, $id_arte): array
    {
        $query = "
            SELECT data_presenca, status, conteudo_aula 
            FROM lista_presenca 
            WHERE id_filiado = ? AND id_arte = ? 
            ORDER BY data_presenca DESC
        ";
        try {
            $stmt = $this->conexao->prepare($query);
            $stmt->bind_param("ii", $id_filiado, $id_arte);
            $stmt->execute();
            $result = $stmt->get_result();
            $dados = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $dados;
        } catch (Exception $e) {
            error_log("Erro ao listar presencas detalhadas: " . $e->getMessage());
            return [];
        }
    }
}
?>
