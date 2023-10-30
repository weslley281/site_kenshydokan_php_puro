<?php
class CertificadoModel
{
    private $id_certificado;
    private $id_usuario;
    private $id_curso;
    private $dataCriacao;
    private $dataMudanca;

    public function __construct($id_certificado, $id_usuario, $id_curso, $dataMudanca)
    {
        $this->id_certificado = $id_certificado;
        $this->id_usuario = $id_usuario;
        $this->id_curso = $id_curso;
        $this->dataCriacao = date("Y-m-d");
        $this->dataMudanca = $dataMudanca;
    }

    public function getIdCertificado()
    {
        return $this->id_certificado;
    }

    public function setIdCertificado($id_certificado)
    {
        $this->id_certificado = $id_certificado;
    }

    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function getIdCurso()
    {
        return $this->id_curso;
    }

    public function setIdCurso($id_curso)
    {
        $this->id_curso = $id_curso;
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
