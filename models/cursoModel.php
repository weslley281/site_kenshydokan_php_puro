<?php
class CursoModel
{
    private $id_curso;
    private $id_categoria;
    private $nome;
    private $descricao;
    private $professor;
    private $id_imagem;
    private $cargaHoraria;
    private $situacao;
    private $dataCriacao;
    private $dataMudanca;
    private $percentual_conclusao_certificado;

    public function __construct($id_curso, $id_categoria, $nome, $descricao, $professor, $id_imagem, $cargaHoraria, $situacao, $dataMudanca, $percentual_conclusao_certificado)
    {
        $this->id_curso = $id_curso;
        $this->id_categoria = $id_categoria;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->professor = $professor;
        $this->id_imagem = $id_imagem;
        $this->cargaHoraria = $cargaHoraria;
        $this->situacao = $situacao;
        $this->dataCriacao = date("Y-m-d");
        $this->dataMudanca = $dataMudanca;
        $this->percentual_conclusao_certificado = $percentual_conclusao_certificado;
    }

    public function getIdCurso()
    {
        return $this->id_curso;
    }

    public function setIdCurso($id_curso)
    {
        $this->id_curso = $id_curso;
    }

    public function getIdCategoria()
    {
        return $this->id_categoria;
    }

    public function setIdCategoria($id_categoria)
    {
        $this->id_categoria = $id_categoria;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getProfessor()
    {
        return $this->professor;
    }

    public function setProfessor($professor)
    {
        $this->professor = $professor;
    }

    public function getIdImagem()
    {
        return $this->id_imagem;
    }

    public function setIdImagem($id_imagem)
    {
        $this->id_imagem = $id_imagem;
    }

    public function getCargaHoraria()
    {
        return $this->cargaHoraria;
    }

    public function setCargaHoraria($cargaHoraria)
    {
        $this->cargaHoraria = $cargaHoraria;
    }

    public function getSituacao()
    {
        return $this->situacao;
    }

    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
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

    public function getPercentualConclusaoCertificado()
    {
        return $this->percentual_conclusao_certificado;
    }

    public function setPercentualConclusaoCertificado($percentual_conclusao_certificado)
    {
        $this->percentual_conclusao_certificado = $percentual_conclusao_certificado;
    }
}
