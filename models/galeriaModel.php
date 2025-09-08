<?php
class Galeria
{
    private $id_galeria;
    private $nome;

    public function __construct($id_galeria, $nome)
    {
        $this->id_galeria = $id_galeria;
        $this->nome = $nome;
    }

    public function getIdGaleria()
    {
        return $this->id_galeria;
    }

    public function getNome()
    {
        return $this->nome;
    }
}
?>