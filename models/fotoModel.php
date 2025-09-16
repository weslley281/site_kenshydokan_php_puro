<?php
class Foto
{
    private $id_foto;
    private int $id_galeria;
    private string $nome;
    private string $foto;
    private string $dataUpload;

    public function __construct($id_foto, int $id_galeria, string $nome, string $foto, string $dataUpload)
    {
        $this->id_foto = $id_foto;
        $this->id_galeria = $id_galeria;
        $this->nome = $nome;
        $this->foto = $foto;
        $this->dataUpload = $dataUpload;
    }

    public function getIdFoto(): int
    {
        return $this->id_foto;
    }

    public function getIdGaleria(): int
    {
        return $this->id_galeria;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getFoto(): string
    {
        return $this->foto;
    }

    public function getDataUpload(): string
    {
        return $this->dataUpload;
    }
}
