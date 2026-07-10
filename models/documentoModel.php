<?php
// models/documentoModel.php
include_once __DIR__ . "/../db/conexao.php";

class DocumentoModel
{
    private $id_documento;
    private $titulo;
    private $descricao;
    private $arquivo;
    private $dataCriacao;
    private $dataMudanca;
    private $conexao;

    public function __construct(
        $id_documento = null,
        $titulo = null,
        $descricao = null,
        $arquivo = null,
        $dataMudanca = null
    ) {
        $this->id_documento = $id_documento;
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->arquivo = $arquivo;
        $this->dataCriacao = date("Y-m-d");
        $this->dataMudanca = $dataMudanca;

        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    // Getters
    public function getIdDocumento()
    {
        return $this->id_documento;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getArquivo()
    {
        return $this->arquivo;
    }

    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }

    public function getDataMudanca()
    {
        return $this->dataMudanca;
    }

    // Setters
    public function setIdDocumento($id_documento)
    {
        $this->id_documento = $id_documento;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function setArquivo($arquivo)
    {
        $this->arquivo = $arquivo;
    }

    public function setDataCriacao($dataCriacao)
    {
        $this->dataCriacao = $dataCriacao;
    }

    public function setDataMudanca($dataMudanca)
    {
        $this->dataMudanca = $dataMudanca;
    }

    // Métodos CRUD

    public function criarDocumento(DocumentoModel $documento): bool
    {
        try {
            $stmt = $this->conexao->prepare("
                INSERT INTO documentos (titulo, descricao, arquivo, dataCriacao, dataMudanca)
                VALUES (?, ?, ?, ?, ?)
            ");
            $titulo = $documento->getTitulo();
            $descricao = $documento->getDescricao();
            $arquivo = $documento->getArquivo();
            $dataCriacao = $documento->getDataCriacao();
            $dataMudanca = $documento->getDataMudanca();

            $stmt->bind_param("sssss", $titulo, $descricao, $arquivo, $dataCriacao, $dataMudanca);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao salvar documento: " . $e->getMessage());
            return false;
        }
    }

    public function excluirDocumento($id_documento): bool
    {
        try {
            $stmt = $this->conexao->prepare("DELETE FROM documentos WHERE id_documento = ?");
            $stmt->bind_param("i", $id_documento);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao excluir documento: " . $e->getMessage());
            return false;
        }
    }

    public function buscarPorId($id_documento)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM documentos WHERE id_documento = ?");
            $stmt->bind_param("i", $id_documento);
            $stmt->execute();
            $result = $stmt->get_result();
            $dados = $result->fetch_assoc();
            $stmt->close();
            return $dados;
        } catch (Exception $e) {
            error_log("Erro ao buscar documento por ID: " . $e->getMessage());
            return null;
        }
    }

    public function listarTodos(): array
    {
        $dados = [];
        try {
            $query = "SELECT * FROM documentos ORDER BY dataCriacao DESC, id_documento DESC";
            $resultado = $this->conexao->query($query);
            if ($resultado) {
                $dados = $resultado->fetch_all(MYSQLI_ASSOC);
                $resultado->free();
            }
        } catch (Exception $e) {
            error_log("Erro ao listar todos os documentos: " . $e->getMessage());
        }
        return $dados;
    }
}
