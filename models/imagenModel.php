<?php
class Imagem
{
    private string $nome;
    private string $caminho;

    public function __construct($nome, $caminho)
    {
        $this->nome = $nome;
        $this->caminho = $caminho;
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
}
