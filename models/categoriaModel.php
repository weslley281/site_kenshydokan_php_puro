<?php
include_once __DIR__ . "/../db/conexao.php";

class CategoriaModel
{
    private $id_categoria;
    private $categoria;
    private $dataCriacao;
    private $dataMudanca;
    private $conexao;

    public function __construct($id_categoria = null, $categoria = null, $dataMudanca = null)
    {
        $this->id_categoria = $id_categoria;
        $this->categoria = $categoria;
        $this->dataCriacao = date("Y-m-d");
        $this->dataMudanca = $dataMudanca;

        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function getIdCategoria()
    {
        return $this->id_categoria;
    }

    public function setIdCategoria($id_categoria)
    {
        $this->id_categoria = $id_categoria;
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }

    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }

    public function setDataCriacao($dataCriacao)
    {
        $this->dataCriacao = $dataCriacao;
    }

    public function getDataMudanca()
    {
        return $this->dataMudanca;
    }

    public function setDataMudanca($dataMudanca)
    {
        $this->dataMudanca = $dataMudanca;
    }

    // Métodos vindos do Repositório

    public function criarCategoria(CategoriaModel $categoria): bool
    {
        $inserir = $this->conexao->prepare("INSERT INTO categorias (categoria, dataMudanca, dataCriacao) VALUES (?, ?, ?)");
        $cat = $categoria->getCategoria();
        $dm = $categoria->getDataMudanca();
        $dc = $categoria->getDataCriacao();
        $inserir->bind_param("sss", $cat, $dm, $dc);
        $resultado = $inserir->execute();
        $inserir->close();

        return $resultado;
    }

    public function editarCategoria(int $id_categoria, CategoriaModel $categoria): bool
    {
        $atualizar = $this->conexao->prepare("UPDATE categorias SET categoria = ?, dataMudanca = ? WHERE id_categoria = ?");
        $cat = $categoria->getCategoria();
        $dm = $categoria->getDataMudanca();
        $atualizar->bind_param("ssi", $cat, $dm, $id_categoria);
        $resultado = $atualizar->execute();
        $atualizar->close();

        return $resultado;
    }

    public function excluirCategoria(int $id_categoria): bool
    {
        $excluir = $this->conexao->prepare("DELETE FROM categorias WHERE id_categoria = ?");
        $excluir->bind_param("i", $id_categoria);
        $resultado = $excluir->execute();
        $excluir->close();

        return $resultado;
    }

    public static function buscarCategorias()
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $busca = "SELECT * FROM categorias";
        $resultado = $conexao->query($busca);

        $categorias = [];
        while ($row = $resultado->fetch_assoc()) {
            $categorias[] = $row;
        }

        return $categorias;
    }

    public static function buscarCategoria($id_categoria)
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $busca = "SELECT * FROM categorias WHERE id_categoria = ?";
        $procura = $conexao->prepare($busca);
        $procura->bind_param("i", $id_categoria);
        $procura->execute();
        $resultado = $procura->get_result();

        $categoria = $resultado->fetch_assoc();
        $procura->close();

        return $categoria;
    }
}
