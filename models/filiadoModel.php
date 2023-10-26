<?php
class FiliadoModel
{
    private $id_filiado;
    private $id_graduacao;
    private $nome;
    private $dojo;
    private $telefone;
    private $rg;
    private $email;
    private $endereco;
    private $cidade;
    private $id_estado;
    private $confirmacao;
    private $dataCriacao;
    private $dataMudanca;

    public function __construct($id_filiado, $id_graduacao, $nome, $dojo, $telefone, $rg, $email, $endereco, $cidade, $id_estado, $confirmacao, $dataMudanca)
    {
        $this->id_filiado = $id_filiado;
        $this->id_graduacao = $id_graduacao;
        $this->nome = $nome;
        $this->dojo = $dojo;
        $this->telefone = $telefone;
        $this->rg = $rg;
        $this->email = $email;
        $this->endereco = $endereco;
        $this->cidade = $cidade;
        $this->id_estado = $id_estado;
        $this->confirmacao = $confirmacao;
        $this->dataCriacao = date("Y-m-d");
        $this->dataMudanca = $dataMudanca;
    }

    public function getIdFiliado()
    {
        return $this->id_filiado;
    }

    public function setIdFiliado($id_filiado)
    {
        $this->id_filiado = $id_filiado;
    }

    public function getIdGraduacao()
    {
        return $this->id_graduacao;
    }

    public function setIdGraduacao($id_graduacao)
    {
        $this->id_graduacao = $id_graduacao;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getDojo()
    {
        return $this->dojo;
    }

    public function setDojo($dojo)
    {
        $this->dojo = $dojo;
    }

    public function getTelefone()
    {
        return $this->telefone;
    }

    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    public function getRg()
    {
        return $this->rg;
    }

    public function setRg($rg)
    {
        $this->rg = $rg;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEndereco()
    {
        return $this->endereco;
    }

    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
    }

    public function getCidade()
    {
        return $this->cidade;
    }

    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }

    public function getIdEstado()
    {
        return $this->id_estado;
    }

    public function setIdEstado($id_estado)
    {
        $this->id_estado = $id_estado;
    }

    public function getConfirmacao()
    {
        return $this->confirmacao;
    }

    public function setConfirmacao($confirmacao)
    {
        $this->confirmacao = $confirmacao;
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
