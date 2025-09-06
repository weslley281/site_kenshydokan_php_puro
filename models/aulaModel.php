<?php
class AulaModel
{
    private $id_aula;
    private $id_curso;
    private $titulo;
    private $link;
    private $dataCriacao;
    private $dataMudanca;

    public function __construct($id_aula, $id_curso, $titulo, $link, $dataMudanca)
    {
        $this->id_aula = $id_aula;
        $this->id_curso = $id_curso;
        $this->titulo = $titulo;
        $this->link = $link;
        $this->dataCriacao = date("Y-m-d");
        $this->dataMudanca = $dataMudanca;
    }

    public function getIdAula()
    {
        return $this->id_aula;
    }

    public function setIdAula($id_aula)
    {
        $this->id_aula = $id_aula;
    }

    public function getIdCurso()
    {
        return $this->id_curso;
    }

    public function setIdCurso($id_curso)
    {
        $this->id_curso = $id_curso;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link)
    {
        $this->link = $link;
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
