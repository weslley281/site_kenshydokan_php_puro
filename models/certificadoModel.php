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

    public static function verificarEGerarCertificadoAuto(int $id_usuario, int $id_curso): bool
    {
        try {
            if (!class_exists('AulaModel')) {
                include_once __DIR__ . "/aulaModel.php";
            }
            if (!class_exists('CursoModel')) {
                include_once __DIR__ . "/cursoModel.php";
            }
            if (!class_exists('Usuario')) {
                include_once __DIR__ . "/usuarioModel.php";
            }

            $aulaModel = new AulaModel();
            $certificadoModelRepo = new self();

            $curso = CursoModel::buscarCurso($id_curso);
            $usuario = Usuario::buscarUsuario($id_usuario);

            if (!$curso || !$usuario) {
                return false;
            }

            if (($curso['temCertificado'] ?? '') !== 'sim') {
                return false;
            }

            // Check completion progress
            $total_aulas = $aulaModel->getTotalAulasPorCurso($id_curso);
            if ($total_aulas === 0) {
                return false;
            }

            $aulas_assistidas = $aulaModel->getAulasAssistidasPorUsuario($id_usuario);
            $aulas_assistidas_no_curso = 0;
            foreach ($aulas_assistidas as $aula_id) {
                $aula_detail = $aulaModel->buscarAula($aula_id);
                if ($aula_detail && $aula_detail['id_curso'] == $id_curso) {
                    $aulas_assistidas_no_curso++;
                }
            }

            $percentual_conclusao = ($aulas_assistidas_no_curso / $total_aulas) * 100;
            $percentual_necessario = $curso['percentual_conclusao_certificado'] ?? 100;

            if ($percentual_conclusao < $percentual_necessario) {
                return false;
            }

            // Check if certificate already exists
            $existing_certificates = $certificadoModelRepo->buscarCertificadosPorUsuario($id_usuario);
            foreach ($existing_certificates as $cert) {
                if ($cert['id_curso'] == $id_curso) {
                    return true; // Already exists
                }
            }

            // Generate unique verification code (required globally for PDF footer)
            global $codigoVerificacao;
            $codigoVerificacao = uniqid('CERT_') . bin2hex(random_bytes(8));

            $nome = $usuario['nome'];
            $nome_curso = $curso['nome'];
            $cargaHoraria = $curso['cargaHoraria'];

            $meses = [
                'January' => 'janeiro', 'February' => 'fevereiro', 'March' => 'março',
                'April' => 'abril', 'May' => 'maio', 'June' => 'junho',
                'July' => 'julho', 'August' => 'agosto', 'September' => 'setembro',
                'October' => 'outubro', 'November' => 'novembro', 'December' => 'dezembro'
            ];
            $mes_en = date('F');
            $mes_pt = $meses[$mes_en] ?? $mes_en;
            $data = date('d') . ' de ' . $mes_pt . ' de ' . date('Y');

            $pdf = new PDF('L', 'mm', 'A4');
            $pdf->AddPage();

            // Draw a beautiful double border
            $pdf->SetDrawColor(168, 32, 26); // WKKA Red
            $pdf->SetLineWidth(1.5);
            $pdf->Rect(12, 12, 273, 186);

            $pdf->SetDrawColor(30, 30, 36); // Charcoal
            $pdf->SetLineWidth(0.5);
            $pdf->Rect(14, 14, 269, 182);

            // Centered Title
            $pdf->SetY(26);
            $pdf->SetFont('Arial', 'B', 24);
            $pdf->SetTextColor(168, 32, 26);
            $pdf->Cell(0, 12, mb_convert_encoding('CERTIFICADO DE CONCLUSÃO', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

            // Centered logo
            $logo_path = __DIR__ . '/../arquivos/logo_instituto.png';
            if (file_exists($logo_path)) {
                $pdf->Image($logo_path, 133.5, 41, 30, 30);
            }
            
            $pdf->Ln(32);

            // Main text
            $pdf->SetFont('Arial', 'I', 13);
            $pdf->SetTextColor(50, 50, 50);
            $pdf->Cell(0, 10, mb_convert_encoding("Certificamos que o(a) aluno(a)", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
            $pdf->Ln(1);

            // Highlighted name
            $pdf->SetFont('Arial', 'B', 22);
            $pdf->SetTextColor(30, 30, 36);
            $pdf->Cell(0, 15, mb_convert_encoding($nome, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
            $pdf->Ln(2);

            // Course text and workload
            $pdf->SetFont('Arial', '', 13);
            $pdf->SetTextColor(50, 50, 50);
            $pdf->Cell(0, 8, mb_convert_encoding("concluiu com sucesso o curso online de", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
            
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->SetTextColor(168, 32, 26);
            $pdf->Cell(0, 10, mb_convert_encoding($nome_curso, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
            
            $pdf->SetFont('Arial', '', 13);
            $pdf->SetTextColor(50, 50, 50);
            $pdf->Cell(0, 8, mb_convert_encoding("com carga horária total de $cargaHoraria horas.", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
            $pdf->Ln(6);

            // Date
            $pdf->SetFont('Arial', 'I', 11);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->Cell(0, 10, mb_convert_encoding("Cuiabá - MT, $data", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
            $pdf->Ln(4);

            // Space for signatures
            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(50, 50, 50);
            $y_linha = $pdf->GetY();
            $pdf->Cell(120, 8, "_________________________", 0, 0, 'C');
            $pdf->Cell(40, 8, '', 0, 0, 'C');
            $pdf->Cell(120, 8, "_________________________", 0, 1, 'C');

            // Draw signatures
            $sig_pres_path = __DIR__ . '/../arquivos/assinatura_presidente.png';
            $sig_dir_path = __DIR__ . '/../arquivos/assinatura_diretor.png';
            if (file_exists($sig_pres_path)) {
                $pdf->Image($sig_pres_path, 47.5, $y_linha - 6, 45, 15);
            }
            if (file_exists($sig_dir_path)) {
                $pdf->Image($sig_dir_path, 207.5, $y_linha - 6, 45, 15);
            }

            $pdf->Cell(120, 8, mb_convert_encoding("Presidente do Instituto", 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
            $pdf->Cell(40, 8, '', 0, 0, 'C');
            $pdf->Cell(120, 8, mb_convert_encoding("Diretor Técnico", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

            // Save PDF to file
            $cert_dir = __DIR__ . '/../arquivos/certificados/';
            if (!is_dir($cert_dir)) {
                mkdir($cert_dir, 0777, true);
            }
            $file_name = 'certificado_' . $id_usuario . '_' . $id_curso . '.pdf';
            $abs_file_path = $cert_dir . $file_name;
            $db_file_path = '../arquivos/certificados/' . $file_name;
            $pdf->Output('F', $abs_file_path);

            // Record certificate in the database
            $certificadoModel = new self(
                null,
                $id_usuario,
                $id_curso,
                $codigoVerificacao,
                date('Y-m-d H:i:s'),
                $db_file_path
            );

            return $certificadoModelRepo->criarCertificado($certificadoModel);
        } catch (Exception $e) {
            error_log("Erro ao gerar certificado automático: " . $e->getMessage());
            return false;
        }
    }
}

class PDF extends FPDF
{
    function Header()
    {
        // Background banner (Red WKKA)
        $this->SetFillColor(168, 32, 26);
        $this->Rect(0, 0, 297, 10, 'F');
        
        // Dark grey line below red banner
        $this->SetFillColor(30, 30, 36);
        $this->Rect(0, 10, 297, 2, 'F');
        
        // Centered Institution name below the banner
        $this->SetY(15);
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(30, 30, 36);
        $this->Cell(0, 8, mb_convert_encoding("INSTITUTO DE ARTES MARCIAIS E DEFESA PESSOAL KENSHYDOKAN", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
    }

    function Footer()
    {
        global $codigoVerificacao;
        
        // Bottom decoration
        $this->SetFillColor(30, 30, 36);
        $this->Rect(0, 198, 297, 2, 'F');
        
        $this->SetFillColor(168, 32, 26);
        $this->Rect(0, 200, 297, 10, 'F');

        // Código de verificação no rodapé
        $this->SetY(-16);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(255, 255, 255); // White text
        $texto = "Código de Verificação: " . ($codigoVerificacao ?? '');
        $this->Cell(0, 10, mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    }
}
