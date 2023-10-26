<?php
class CategoriaModel
{
    private $id_categoria;
    private $categoria;
    private $dataCriacao;
    private $dataMudanca;

    public function __construct($id_categoria, $categoria, $dataMudanca)
    {
        $this->id_categoria = $id_categoria;
        $this->categoria = $categoria;
        $this->dataCriacao = date("Y-m-d");
        $this->dataMudanca = $dataMudanca;
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
}
