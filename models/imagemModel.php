<?php
class Imagem
{
    private string $nome;
    private string $caminho;
    private string $dataMudanca;
    private string $dataCriacao;

    public function __construct($nome, $caminho, $dataMudanca)
    {
        $this->nome = $nome;
        $this->caminho = $caminho;
        $this->dataMudanca = $dataMudanca;
        $this->dataCriacao = date("Y-m-d");
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getCaminho()
    {
        return $this->caminho;
    }

    public function setCaminho($caminho)
    {
        $this->caminho = $caminho;
    }

    public function getDataMudanca()
    {
        return $this->dataMudanca;
    }

    public function setDataMudanca($dataMudanca)
    {
        $this->dataMudanca = $dataMudanca;
    }

    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }
}
