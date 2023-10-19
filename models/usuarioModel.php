<?php
class Usuario
{
    private $nome;
    private $id_fil;
    private $id_imagem;
    private $email;
    private $nivel;
    private $telefone;
    private $senha;
    private $dataCriacao;
    private $dataMudanca;

    public function __construct($nome, $id_fil, $id_imagem, $email, $telefone, $dataMudanca, $senha = "", $nivel = "aluno")
    {
        $this->nome = $nome;
        $this->id_fil = $id_fil;
        $this->id_imagem = $id_imagem;
        $this->email = $email;
        $this->nivel = $nivel;
        $this->telefone = $telefone;
        $this->senha = $senha;
        $this->dataCriacao = date("Y-m-d");
        $this->dataMudanca = $dataMudanca;
    }

    // Métodos getters
    public function getNome()
    {
        return $this->nome;
    }

    public function getIdFil()
    {
        return $this->id_fil;
    }

    public function getIdImagem()
    {
        return $this->id_imagem;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getNivel()
    {
        return $this->nivel;
    }

    public function getTelefone()
    {
        return $this->telefone;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }

    public function getDataMudanca()
    {
        return $this->dataMudanca;
    }

    // Métodos setters
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function setIdFil($id_fil)
    {
        $this->id_fil = $id_fil;
    }

    public function setIdImagem($id_imagem)
    {
        $this->id_imagem = $id_imagem;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setNivel($nivel)
    {
        $this->nivel = $nivel;
    }

    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
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
