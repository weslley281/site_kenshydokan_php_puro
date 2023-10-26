<?php
include_once "../db/conexao.php";
include_once "../models/categoriaModel.php";

class CategoriaRepositorio
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function criarCategoria(CategoriaModel $categoria): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO categorias (categoria, dataMudanca, dataCriacao) VALUES (?, ?, ?)");
            $inserir->bind_param("sss", $categoria->getCategoria(), $categoria->getDataMudanca(), $categoria->getDataCriacao());
            $resultado = $inserir->execute();
            $inserir->close();

            if (!$resultado) {
                throw new Exception("Erro ao criar a categoria.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao criar a categoria: " . $e->getMessage());
            return false;
        }
    }

    public function editarCategoria($id_categoria, CategoriaModel $categoria): bool
    {
        try {
            $editar = $this->conexao->prepare("UPDATE categorias SET categoria = ?, dataMudanca = ? WHERE id_categoria = ?");
            $editar->bind_param("ssi", $categoria->getCategoria(), $categoria->getDataMudanca(), $id_categoria);
            $resultado = $editar->execute();
            $editar->close();

            if (!$resultado) {
                throw new Exception("Erro ao editar a categoria.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao editar a categoria: " . $e->getMessage());
            return false;
        }
    }

    public function excluirCategoria($id_categoria): bool
    {
        try {
            $deletar = $this->conexao->prepare("DELETE FROM categorias WHERE id_categoria = ?");
            $deletar->bind_param("i", $id_categoria);
            $resultado = $deletar->execute();
            $deletar->close();

            if (!$resultado) {
                throw new Exception("Erro ao excluir a categoria.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao excluir a categoria: " . $e->getMessage());
            return false;
        }
    }

    public static function buscarCategoria($id_categoria)
    {
        try {
            $c = new Conexao();
            $conexao = $c->conectar();

            $busca = "SELECT * FROM categorias WHERE id_categoria = ?";
            $procura = $conexao->prepare($busca);
            $procura->bind_param("i", $id_categoria);
            $procura->execute();
            $result = $procura->get_result();

            if ($result->num_rows === 0) {
                return null;
            }

            $categoria = $result->fetch_assoc();
            $procura->close();

            return $categoria;
        } catch (Exception $e) {
            error_log("Erro ao buscar a categoria: " . $e->getMessage());
            return null;
        }
    }
}
