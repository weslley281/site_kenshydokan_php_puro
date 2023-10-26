<?php
class GaleriaModel
{
    private $id_galeria;
    private $nome;
    private $dataCriacao;
    private $dataMudanca;

    public function __construct($id_galeria, $nome, $dataMudanca)
    {
        $this->id_galeria = $id_galeria;
        $this->nome = $nome;
        $this->dataCriacao = date("Y-m-d");
        $this->dataMudanca = $dataMudanca;
    }

    public function getIdGaleria()
    {
        return $this->id_galeria;
    }

    public function setIdGaleria($id_galeria)
    {
        $this->id_galeria = $id_galeria;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
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
