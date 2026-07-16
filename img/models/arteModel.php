<?php
include_once __DIR__ . "/../db/conexao.php";

class ArteMarcial
{
    private $id_arte;
    private $nome;
    private $conexao;

    public function __construct($id_arte = null, $nome = null)
    {
        $this->id_arte = $id_arte;
        $this->nome = $nome;

        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function getIdArte()
    {
        return $this->id_arte;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function criarArte(ArteMarcial $arte): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO artes_marciais (nome, dataCriacao) VALUES (?, CURDATE())");
            $nome = $arte->getNome();
            $inserir->bind_param("s", $nome);
            $resultado = $inserir->execute();
            $inserir->close();

            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao criar a arte marcial: " . $e->getMessage());
            return false;
        }
    }

    public function editarArte($id_arte, $nome): bool
    {
        try {
            $editar = $this->conexao->prepare("UPDATE artes_marciais SET nome = ?, dataMudanca = CURDATE() WHERE id_arte = ?");
            $editar->bind_param("si", $nome, $id_arte);
            $resultado = $editar->execute();
            $editar->close();

            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao editar a arte marcial: " . $e->getMessage());
            return false;
        }
    }

    public function excluirArte($id_arte): bool
    {
        try {
            $deletar = $this->conexao->prepare("DELETE FROM artes_marciais WHERE id_arte = ?");
            $deletar->bind_param("i", $id_arte);
            $resultado = $deletar->execute();
            $deletar->close();

            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao excluir a arte marcial: " . $e->getMessage());
            return false;
        }
    }

    public function listarArtes()
    {
        $sql = "SELECT * FROM artes_marciais ORDER BY id_arte ASC";
        $result = $this->conexao->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function buscarArtePorId($id_arte)
    {
        try {
            $busca = $this->conexao->prepare("SELECT * FROM artes_marciais WHERE id_arte = ?");
            $busca->bind_param("i", $id_arte);
            $busca->execute();
            $result = $busca->get_result();

            if ($result->num_rows === 0) {
                return null;
            }

            $dados = $result->fetch_assoc();
            $busca->close();

            return new ArteMarcial($dados['id_arte'], $dados['nome']);
        } catch (Exception $e) {
            error_log("Erro ao buscar a arte marcial por ID: " . $e->getMessage());
            return null;
        }
    }
}
