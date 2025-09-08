<?php
class Foto
{
    private $id_foto;
    private $id_galeria;
    private $nome;
    private $foto;
    private $dataUpload;

    public function __construct($id_foto, $id_galeria, $nome, $foto, $dataUpload)
    {
        $this->id_foto = $id_foto;
        $this->id_galeria = $id_galeria;
        $this->nome = $nome;
        $this->foto = $foto;
        $this->dataUpload = $dataUpload;
    }

    public function getIdFoto()
    {
        return $this->id_foto;
    }

    public function getIdGaleria()
    {
        return $this->id_galeria;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getFoto()
    {
        return $this->foto;
    }

    public function getDataUpload()
    {
        return $this->dataUpload;
    }
}
?>