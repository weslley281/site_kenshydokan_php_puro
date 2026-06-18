<?php
require_once(__DIR__ . '/../libs/fpdf/fpdf.php');
include_once __DIR__ . "/../db/conexao.php";

class CertificadoModel
{
    private $id;
    private $id_usuario;
    private $id_curso;
    private $codigo_verificacao;
    private $data_emissao;
    private $caminho_arquivo;
    private $conexao;

    public function __construct($id = null, $id_usuario = null, $id_curso = null, $codigo_verificacao = null, $data_emissao = null, $caminho_arquivo = null)
    {
        $this->id = $id;
        $this->id_usuario = $id_usuario;
        $this->id_curso = $id_curso;
        $this->codigo_verificacao = $codigo_verificacao;
        $this->data_emissao = $data_emissao;
        $this->caminho_arquivo = $caminho_arquivo;

        $c = new Conexao();
        $this->conexao = $c->conectar();
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

    // Setters
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

    // Métodos vindos do Repositório

    public function criarCertificado(CertificadoModel $certificado): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO certificados (id_usuario, id_curso, codigo_verificacao, data_emissao, caminho_arquivo) VALUES (?, ?, ?, ?, ?)");
            $id_u = $certificado->getIdUsuario();
            $id_c = $certificado->getIdCurso();
            $cv = $certificado->getCodigoVerificacao();
            $de = $certificado->getDataEmissao();
            $ca = $certificado->getCaminhoArquivo();

            $inserir->bind_param("iisss", $id_u, $id_c, $cv, $de, $ca);
            $resultado = $inserir->execute();
            $inserir->close();

            if (!$resultado) {
                throw new Exception("Erro ao criar o certificado.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao criar o certificado: " . $e->getMessage());
            return false;
        }
    }

    public function buscarCertificadoPorCodigo(string $codigo_verificacao)
    {
        try {
            $busca = "SELECT * FROM certificados WHERE codigo_verificacao = ?";
            $procura = $this->conexao->prepare($busca);
            $procura->bind_param("s", $codigo_verificacao);
            $procura->execute();
            $result = $procura->get_result();

            if ($result->num_rows === 0) {
                return null;
            }

            $certificado = $result->fetch_assoc();
            $procura->close();

            return $certificado;
        } catch (Exception $e) {
            error_log("Erro ao buscar certificado por código: " . $e->getMessage());
            return null;
        }
    }

    public function buscarCertificadosPorUsuario(int $id_usuario): array
    {
        try {
            $certificados = [];
            $busca = "SELECT * FROM certificados WHERE id_usuario = ?";
            $procura = $this->conexao->prepare($busca);
            $procura->bind_param("i", $id_usuario);
            $procura->execute();
            $result = $procura->get_result();

            while ($row = $result->fetch_assoc()) {
                $certificados[] = $row;
            }
            $procura->close();

            return $certificados;
        } catch (Exception $e) {
            error_log("Erro ao buscar certificados por usuário: " . $e->getMessage());
            return [];
        }
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
