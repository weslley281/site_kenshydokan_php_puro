<?php
require(__DIR__ . '/../libs/fpdf/fpdf.php');

class CertificadoModel
{
    private $id;
    private $id_usuario;
    private $id_curso;
    private $codigo_verificacao;
    private $data_emissao;
    private $caminho_arquivo;

    public function __construct($id, $id_usuario, $id_curso, $codigo_verificacao, $data_emissao, $caminho_arquivo)
    {
        $this->id = $id;
        $this->id_usuario = $id_usuario;
        $this->id_curso = $id_curso;
        $this->codigo_verificacao = $codigo_verificacao;
        $this->data_emissao = $data_emissao;
        $this->caminho_arquivo = $caminho_arquivo;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function getIdCurso()
    {
        return $this->id_curso;
    }

    public function getCodigoVerificacao()
    {
        return $this->codigo_verificacao;
    }

    public function getDataEmissao()
    {
        return $this->data_emissao;
    }

    public function getCaminhoArquivo()
    {
        return $this->caminho_arquivo;
    }

    // Setters (if needed, though for certificates, they might be less common)
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function setIdCurso($id_curso)
    {
        $this->id_curso = $id_curso;
    }

    public function setCodigoVerificacao($codigo_verificacao)
    {
        $this->codigo_verificacao = $codigo_verificacao;
    }

    public function setDataEmissao($data_emissao)
    {
        $this->data_emissao = $data_emissao;
    }

    public function setCaminhoArquivo($caminho_arquivo)
    {
        $this->caminho_arquivo = $caminho_arquivo;
    }
}

class PDF extends FPDF
{
    function Header()
    {
        // Nome da instituição no topo
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,"Instituto de Artes Marciais e Defesa Pessoal Kenshydokan",0,1,'C');
        $this->Ln(2);
    }

    function Footer()
    {
        global $codigoVerificacao;
        // Código de verificação no rodapé
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $texto = "Código de Verificação: " . $codigoVerificacao;
        $this->Cell(0,10,mb_convert_encoding($texto,'ISO-8859-1','UTF-8'),0,0,'C');
    }
}
