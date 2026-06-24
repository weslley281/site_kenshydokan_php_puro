<?php
// models/certificadoUploadModel.php
include_once __DIR__ . "/../db/conexao.php";

class CertificadoUploadModel
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function criarCertificadoUpload($id_filiado, $titulo, $imagem, $data_upload): bool
    {
        try {
            $stmt = $this->conexao->prepare("
                INSERT INTO certificados_upload (id_filiado, titulo, imagem, data_upload)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param("isss", $id_filiado, $titulo, $imagem, $data_upload);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao salvar upload de certificado: " . $e->getMessage());
            return false;
        }
    }

    public function excluirCertificadoUpload($id): bool
    {
        try {
            $stmt = $this->conexao->prepare("DELETE FROM certificados_upload WHERE id = ?");
            $stmt->bind_param("i", $id);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao excluir upload de certificado: " . $e->getMessage());
            return false;
        }
    }

    public function buscarPorId($id)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM certificados_upload WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $dados = $result->fetch_assoc();
            $stmt->close();
            return $dados;
        } catch (Exception $e) {
            error_log("Erro ao buscar upload de certificado por ID: " . $e->getMessage());
            return null;
        }
    }

    public function listarTodos(): array
    {
        $dados = [];
        $query = "
            SELECT c.*, f.nome, f.codigo, f.dojo, g.graduacao
            FROM certificados_upload c
            INNER JOIN filiados f ON c.id_filiado = f.id_filiado
            LEFT JOIN graduacoes g ON g.id_graduacao = COALESCE(
                (SELECT id_graduacao FROM filiados_graduacoes WHERE id_filiado = f.id_filiado AND id_arte = 1 LIMIT 1), 
                (SELECT id_graduacao FROM filiados_graduacoes WHERE id_filiado = f.id_filiado LIMIT 1)
            )
            ORDER BY c.data_upload DESC, c.id DESC
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
                FROM certificados_upload c
                INNER JOIN filiados f ON c.id_filiado = f.id_filiado
                LEFT JOIN graduacoes g ON g.id_graduacao = COALESCE(
                    (SELECT id_graduacao FROM filiados_graduacoes WHERE id_filiado = f.id_filiado AND id_arte = 1 LIMIT 1), 
                    (SELECT id_graduacao FROM filiados_graduacoes WHERE id_filiado = f.id_filiado LIMIT 1)
                )
                WHERE c.id_filiado = ?
                ORDER BY c.data_upload DESC
            ");
            $stmt->bind_param("i", $id_filiado);
            $stmt->execute();
            $result = $stmt->get_result();
            $dados = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } catch (Exception $e) {
            error_log("Erro ao buscar uploads de certificados por filiado: " . $e->getMessage());
        }
        return $dados;
    }
}
