<?php
class Publicacao
{
    private $id_usuario;
    private $titulo;
    private $conteudo;
    private $status;
    private $dataCriacao;
    private $dataMudanca;

    public function __construct($id_usuario, $titulo, $conteudo, $dataMudanca, $status = "aguardando")
    {
        $this->id_usuario = $id_usuario;
        $this->titulo = $titulo;
        $this->conteudo = $conteudo;
        $this->status = $status;
        $this->dataCriacao = date("Y-m-d");
        $this->dataMudanca = $dataMudanca;
    }

    // Métodos Getters
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function getConteudo()
    {
        return $this->conteudo;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }

    public function getDataMudanca()
    {
        return $this->dataMudanca;
    }

    // Métodos Setters
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function setConteudo($conteudo)
    {
        $this->conteudo = $conteudo;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setDataCriacao($dataCriacao)
    {
        $this->dataCriacao = $dataCriacao;
    }

    public function setDataMudanca($dataMudanca)
    {
        $this->dataMudanca = $dataMudanca;
    }
}
