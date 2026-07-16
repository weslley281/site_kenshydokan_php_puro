<?php
// models/exameGraduacaoModel.php
include_once __DIR__ . "/../db/conexao.php";

class ExameGraduacaoModel
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function criarExame($id_filiado, $id_arte, $id_graduacao_atual, $id_graduacao_pretendida, $nota, $comentarios, $data_exame, $situacao = 'pendente'): bool
    {
        try {
            $stmt = $this->conexao->prepare("
                INSERT INTO exames_graduacao (id_filiado, id_arte, id_graduacao_atual, id_graduacao_pretendida, nota, comentarios, data_exame, situacao)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("iiiidsss", $id_filiado, $id_arte, $id_graduacao_atual, $id_graduacao_pretendida, $nota, $comentarios, $data_exame, $situacao);
            $res = $stmt->execute();
            
            // Se aprovado, atualiza/insere automaticamente a nova graduação do filiado na modalidade
            if ($res && $situacao === 'aprovado') {
                $this->atualizarGraduacaoFiliado($id_filiado, $id_arte, $id_graduacao_pretendida);
            }

            $stmt->close();
            return $res;
        } catch (Exception $e) {
            error_log("Erro ao criar exame: " . $e->getMessage());
            return false;
        }
    }

    public function editarExame($id, $id_filiado, $id_arte, $id_graduacao_atual, $id_graduacao_pretendida, $nota, $comentarios, $data_exame, $situacao): bool
    {
        try {
            $stmt = $this->conexao->prepare("
                UPDATE exames_graduacao
                SET id_filiado = ?, id_arte = ?, id_graduacao_atual = ?, id_graduacao_pretendida = ?, nota = ?, comentarios = ?, data_exame = ?, situacao = ?
                WHERE id = ?
            ");
            $stmt->bind_param("iiiidsssi", $id_filiado, $id_arte, $id_graduacao_atual, $id_graduacao_pretendida, $nota, $comentarios, $data_exame, $situacao, $id);
            $res = $stmt->execute();

            // Se aprovado, atualiza/insere automaticamente a nova graduação do filiado na modalidade
            if ($res && $situacao === 'aprovado') {
                $this->atualizarGraduacaoFiliado($id_filiado, $id_arte, $id_graduacao_pretendida);
            }

            $stmt->close();
            return $res;
        } catch (Exception $e) {
            error_log("Erro ao editar exame: " . $e->getMessage());
            return false;
        }
    }

    public function excluirExame($id): bool
    {
        try {
            $stmt = $this->conexao->prepare("DELETE FROM exames_graduacao WHERE id = ?");
            $stmt->bind_param("i", $id);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        } catch (Exception $e) {
            error_log("Erro ao excluir exame: " . $e->getMessage());
            return false;
        }
    }

    public function buscarPorId($id)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM exames_graduacao WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $dados = $result->fetch_assoc();
            $stmt->close();
            return $dados;
        } catch (Exception $e) {
            error_log("Erro ao buscar exame por ID: " . $e->getMessage());
            return null;
        }
    }

    public function listarTodos(): array
    {
        $query = "
            SELECT 
                eg.*, 
                f.nome AS aluno_nome, 
                f.codigo AS aluno_codigo,
                am.nome AS modalidade,
                g_atual.graduacao AS graduacao_atual, 
                g_pret.graduacao AS graduacao_pretendida
            FROM exames_graduacao eg
            INNER JOIN filiados f ON eg.id_filiado = f.id_filiado
            INNER JOIN artes_marciais am ON eg.id_arte = am.id_arte
            INNER JOIN graduacoes g_atual ON eg.id_graduacao_atual = g_atual.id_graduacao
            INNER JOIN graduacoes g_pret ON eg.id_graduacao_pretendida = g_pret.id_graduacao
            ORDER BY eg.data_exame DESC, eg.id DESC
        ";
        $result = $this->conexao->query($query);
        if ($result) {
            $dados = $result->fetch_all(MYSQLI_ASSOC);
            $result->free();
            return $dados;
        }
        return [];
    }

    public function listarPorFiliado($id_filiado): array
    {
        $query = "
            SELECT 
                eg.*, 
                am.nome AS modalidade,
                g_atual.graduacao AS graduacao_atual, 
                g_pret.graduacao AS graduacao_pretendida
            FROM exames_graduacao eg
            INNER JOIN artes_marciais am ON eg.id_arte = am.id_arte
            INNER JOIN graduacoes g_atual ON eg.id_graduacao_atual = g_atual.id_graduacao
            INNER JOIN graduacoes g_pret ON eg.id_graduacao_pretendida = g_pret.id_graduacao
            WHERE eg.id_filiado = ?
            ORDER BY eg.data_exame DESC
        ";
        try {
            $stmt = $this->conexao->prepare($query);
            $stmt->bind_param("i", $id_filiado);
            $stmt->execute();
            $result = $stmt->get_result();
            $dados = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $dados;
        } catch (Exception $e) {
            error_log("Erro ao listar exames do filiado: " . $e->getMessage());
            return [];
        }
    }

    private function atualizarGraduacaoFiliado($id_filiado, $id_arte, $id_graduacao): void
    {
        try {
            $stmt = $this->conexao->prepare("
                INSERT INTO filiados_graduacoes (id_filiado, id_arte, id_graduacao)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE id_graduacao = ?
            ");
            $stmt->bind_param("iiii", $id_filiado, $id_arte, $id_graduacao, $id_graduacao);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            error_log("Erro ao atualizar graduacao do filiado: " . $e->getMessage());
        }
    }
}
?>
