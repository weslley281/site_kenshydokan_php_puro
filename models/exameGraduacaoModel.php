<?php

class ExameGraduacaoModel
{
    private $id;
    private $nome;
    private $documento;
    private $id_graduacao_atual;
    private $id_graduacao_pretendida;
    private $id_professor;
    private $email;
    private $dataCriacao;
    private $dataMudanca;
    private $situacao;

    public function __construct($id, $nome, $documento, $id_graduacao_atual, $id_graduacao_pretendida, $id_professor, $email, $dataCriacao, $dataMudanca, $situacao)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->documento = $documento;
        $this->id_graduacao_atual = $id_graduacao_atual;
        $this->id_graduacao_pretendida = $id_graduacao_pretendida;
        $this->id_professor = $id_professor;
        $this->email = $email;
        $this->dataCriacao = $dataCriacao;
        $this->dataMudanca = $dataMudanca;
        $this->situacao = $situacao;
    }

    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getDocumento() { return $this->documento; }
    public function getIdGraduacaoAtual() { return $this->id_graduacao_atual; }
    public function getIdGraduacaoPretendida() { return $this->id_graduacao_pretendida; }
    public function getIdProfessor() { return $this->id_professor; }
    public function getEmail() { return $this->email; }
    public function getDataCriacao() { return $this->dataCriacao; }
    public function getDataMudanca() { return $this->dataMudanca; }
    public function getSituacao() { return $this->situacao; }
}
