<?php

class DojoModel {
    private $id_dojo;
    private $razao_social;
    private $nome_fantasia;
    private $cnpj;
    private $id_filiado_responsavel;
    private $telefone;
    private $celular;
    private $email;
    private $cep;
    private $endereco;
    private $cidade;
    private $estado;
    private $data_filiacao;
    private $status;
    private $imagem;

    public function __construct() {
        // Construtor pode ser usado para inicializar valores padrão, se necessário
    }

    // Getters
    public function getIdDojo() {
        return $this->id_dojo;
    }

    public function getRazaoSocial() {
        return $this->razao_social;
    }

    public function getNomeFantasia() {
        return $this->nome_fantasia;
    }

    public function getCnpj() {
        return $this->cnpj;
    }

    public function getIdFiliadoResponsavel() {
        return $this->id_filiado_responsavel;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    public function getCelular() {
        return $this->celular;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getCep() {
        return $this->cep;
    }

    public function getEndereco() {
        return $this->endereco;
    }

    public function getCidade() {
        return $this->cidade;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getDataFiliacao() {
        return $this->data_filiacao;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getImagem() {
        return $this->imagem;
    }

    // Setters
    public function setIdDojo($id_dojo) {
        $this->id_dojo = $id_dojo;
    }

    public function setRazaoSocial($razao_social) {
        $this->razao_social = $razao_social;
    }

    public function setNomeFantasia($nome_fantasia) {
        $this->nome_fantasia = $nome_fantasia;
    }

    public function setCnpj($cnpj) {
        $this->cnpj = $cnpj;
    }

    public function setIdFiliadoResponsavel($id_filiado_responsavel) {
        $this->id_filiado_responsavel = $id_filiado_responsavel;
    }

    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    public function setCelular($celular) {
        $this->celular = $celular;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setCep($cep) {
        $this->cep = $cep;
    }

    public function setEndereco($endereco) {
        $this->endereco = $endereco;
    }

    public function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setDataFiliacao($data_filiacao) {
        $this->data_filiacao = $data_filiacao;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setImagem($imagem) {
        $this->imagem = $imagem;
    }
}
