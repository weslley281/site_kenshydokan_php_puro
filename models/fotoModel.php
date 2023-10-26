<?php
class FotoModel
{
    private $id_foto;
    private $id_galeria;
    private $nome;
    private $foto;
    private $dataUpload;
    private $dataCriacao;
    private $dataMudanca;

    public function __construct($id_foto, $id_galeria, $nome, $foto, $dataUpload, $dataMudanca)
    {
        $this->id_foto = $id_foto;
        $this->id_galeria = $id_galeria;
        $this->nome = $nome;
        $this->foto = $foto;
        $this->dataUpload = $dataUpload;
        $this->dataCriacao = date("Y-m-d");
        $this->dataMudanca = $dataMudanca;
    }

    public function getIdFoto()
    {
        return $this->id_foto;
    }

    public function setIdFoto($id_foto)
    {
        $this->id_foto = $id_foto;
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

    public function getFoto()
    {
        return $this->foto;
    }

    public function setFoto($foto)
    {
        $this->foto = $foto;
    }

    public function getDataUpload()
    {
        return $this->dataUpload;
    }

    public function setDataUpload($dataUpload)
    {
        $this->dataUpload = $dataUpload;
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
