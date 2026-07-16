<?php
// models/certificadoManualModel.php
include_once __DIR__ . "/../db/conexao.php";

class CertificadoManualModel
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function criarCertificadoManual($id_filiado, $titulo, $data_emissao): bool
    {
        try {
            $stmt = $this->conexao->prepare("
                INSERT INTO certificados_manuais (id_filiado, titulo, data_emissao)
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("iss", $id_filiado, $titulo, $data_emissao);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao criar certificado manual: " . $e->getMessage());
            return false;
        }
    }

    public function editarCertificadoManual($id, $id_filiado, $titulo, $data_emissao): bool
    {
        try {
            $stmt = $this->conexao->prepare("
                UPDATE certificados_manuais 
                SET id_filiado = ?, titulo = ?, data_emissao = ?
                WHERE id = ?
            ");
            $stmt->bind_param("issi", $id_filiado, $titulo, $data_emissao, $id);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao editar certificado manual: " . $e->getMessage());
            return false;
        }
    }

    public function excluirCertificadoManual($id): bool
    {
        try {
            $stmt = $this->conexao->prepare("DELETE FROM certificados_manuais WHERE id = ?");
            $stmt->bind_param("i", $id);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao excluir certificado manual: " . $e->getMessage());
            return false;
        }
    }

    public function buscarPorId($id)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM certificados_manuais WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $dados = $result->fetch_assoc();
            $stmt->close();
            return $dados;
        } catch (Exception $e) {
            error_log("Erro ao buscar certificado manual por ID: " . $e->getMessage());
            return null;
        }
    }

    public function listarTodos(): array
    {
        $dados = [];
        $query = "
            SELECT c.*, f.nome, f.codigo, f.dojo, g.graduacao
            FROM certificados_manuais c
            INNER JOIN filiados f ON c.id_filiado = f.id_filiado
            LEFT JOIN graduacoes g ON g.id_graduacao = COALESCE(
                (SELECT id_graduacao FROM filiados_graduacoes WHERE id_filiado = f.id_filiado AND id_arte = 1 LIMIT 1), 
                (SELECT id_graduacao FROM filiados_graduacoes WHERE id_filiado = f.id_filiado LIMIT 1)
            )
            ORDER BY c.data_emissao DESC, c.id DESC
        ";
        $resultado = $this->conexao->query($query);
        if ($resultado) {
            $dados = $resultado->fetch_all(MYSQLI_ASSOC);
            $resultado->free();
        }
        return $dados;
    }

    public function listarPorFiliado($id_filiado): array
    {
        $dados = [];
        try {
            $stmt = $this->conexao->prepare("
                SELECT c.*, f.nome, f.codigo, f.dojo, g.graduacao
                FROM certificados_manuais c
                INNER JOIN filiados f ON c.id_filiado = f.id_filiado
                LEFT JOIN graduacoes g ON g.id_graduacao = COALESCE(
                    (SELECT id_graduacao FROM filiados_graduacoes WHERE id_filiado = f.id_filiado AND id_arte = 1 LIMIT 1), 
                    (SELECT id_graduacao FROM filiados_graduacoes WHERE id_filiado = f.id_filiado LIMIT 1)
                )
                WHERE c.id_filiado = ?
                ORDER BY c.data_emissao DESC
            ");
            $stmt->bind_param("i", $id_filiado);
            $stmt->execute();
            $result = $stmt->get_result();
            $dados = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } catch (Exception $e) {
            error_log("Erro ao buscar certificados por filiado: " . $e->getMessage());
        }
        return $dados;
    }
}
