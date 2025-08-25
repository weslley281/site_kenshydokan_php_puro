<?php
session_start();

// Include FPDF library and other necessary files
//require_once('../libs/fpdf/fpdf.php'); // Assuming fpdf is in libs/fpdf
include_once __DIR__ . "/../repositorios/AulaRepositorio.php";
include_once __DIR__ . "/../repositorios/CursoRepositorio.php";
include_once __DIR__ . "/../repositorios/CertificadoRepositorio.php";
include_once __DIR__ . "/../models/certificadoModel.php";
include_once __DIR__ . "/../repositorios/usuarioRepositorio.php";

// Helper function for messages and redirection
function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}

// Check user session and course ID
if (!isset($_SESSION["id_usuario"])) {
    exibirMensagemEredirecionar("Você precisa estar logado para gerar certificados.", '../views/login.php');
}

if (!isset($_GET["id_curso"])) {
    exibirMensagemEredirecionar("ID do curso não fornecido.", '../views/perfil/assistir_aulas.php');
}

$id_usuario = $_SESSION["id_usuario"];
$id_curso = $_GET["id_curso"];

// Instantiate repositories
$aulaRepositorio = new AulaRepositorio();
$cursoRepositorio = new CursoRepositorio();
$certificadoRepositorio = new CertificadoRepositorio();
$usuarioRepositorio = new UsuarioRepositorio();

// Fetch course and user data
$curso = $cursoRepositorio->buscarCurso($id_curso);
$usuario = $usuarioRepositorio->buscarUsuario($id_usuario);

if (!$curso || !$usuario) {
    exibirMensagemEredirecionar("Curso ou usuário não encontrado.", '../views/perfil/assistir_aulas.php');
}

// Check eligibility
$total_aulas = $aulaRepositorio->getTotalAulasPorCurso($id_curso);
$aulas_assistidas = $aulaRepositorio->getAulasAssistidasPorUsuario($id_usuario);
$aulas_assistidas_no_curso = 0;
foreach ($aulas_assistidas as $aula_id) {
    $aula_detail = $aulaRepositorio->buscarAula($aula_id);
    if ($aula_detail && $aula_detail['id_curso'] == $id_curso) {
        $aulas_assistidas_no_curso++;
    }
}

$percentual_conclusao = ($total_aulas > 0) ? ($aulas_assistidas_no_curso / $total_aulas) * 100 : 0;
$percentual_necessario = $curso['percentual_conclusao_certificado'] ?? 100;

if ($percentual_conclusao < $percentual_necessario) {
    exibirMensagemEredirecionar("Você não completou o percentual necessário de aulas para este curso. Conclusão: " . round($percentual_conclusao, 2) . "%. Necessário: " . $percentual_necessario . "%. ", '../views/perfil/assistir_aulas.php?id=' . $id_curso);
}

// Check if certificate already exists
$existing_certificates = $certificadoRepositorio->buscarCertificadosPorUsuario($id_usuario);
foreach ($existing_certificates as $cert) {
    if ($cert['id_curso'] == $id_curso) {
        exibirMensagemEredirecionar("Você já possui um certificado para este curso.", '../views/perfil/meus_certificados.php');
    }
}

// Generate unique verification code
$codigoVerificacao = uniqid('CERT_') . bin2hex(random_bytes(8));


// Prepare data for the certificate
$nome = $usuario['nome'];
$nome_curso = $curso['nome'];
$cargaHoraria = $curso['cargaHoraria'];
$data = date('d \d\e F \d\e Y');

$pdf = new PDF('L', 'mm', 'A4');
$pdf->AddPage();

// Border
$pdf->Rect(10, 10, 277, 190);

// Centered Title
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(0, 20, mb_convert_encoding('CERTIFICADO DE CONCLUSÃO', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Ln(3);

// Centered logo
$pdf->Image('../arquivos/logo_instituto.png', 120, 40, 50, 50);
$pdf->Ln(45);

// Main text
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 10, mb_convert_encoding("Certificamos que o(a) Sr(a).", 'ISO-8859-1', 'UTF-8'), 0, 'C');
$pdf->Ln(3);

// Highlighted name
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(0, 15, mb_convert_encoding($nome, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Ln(3);

// Course text and workload (justified)
$pdf->SetFont('Arial', '', 12);
$texto_curso = "concluiu com sucesso o curso de $nome_curso, com carga horária de $cargaHoraria horas.";
$pdf->MultiCell(0, 10, mb_convert_encoding($texto_curso, 'ISO-8859-1', 'UTF-8'), 0, 'J');
$pdf->Ln(10);

// Date on the bottom right
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 10, mb_convert_encoding("Cuiabá - MT, $data", 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
$pdf->Ln(10);

// Space for signatures
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(120, 10, "_________________________", 0, 0, 'C');
$pdf->Cell(40, 10, '', 0, 0, 'C');
$pdf->Cell(120, 10, "_________________________", 0, 1, 'C');

$pdf->Cell(120, 10, mb_convert_encoding("Presidente do Instituto", 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
$pdf->Cell(40, 10, '', 0, 0, 'C');
$pdf->Cell(120, 10, mb_convert_encoding("Diretor Técnico", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

// Save PDF to file
$cert_dir = '../arquivos/certificados/';
if (!is_dir($cert_dir)) {
    mkdir($cert_dir, 0777, true);
}
$file_name = 'certificado_' . $id_usuario . '_' . $id_curso . '.pdf';
$file_path = $cert_dir . $file_name;
$pdf->Output('F', $file_path);

// Record certificate in the database
$certificadoModel = new CertificadoModel(
    null,
    $id_usuario,
    $id_curso,
    $codigoVerificacao,
    date('Y-m-d H:i:s'),
    $file_path
);

if ($certificadoRepositorio->criarCertificado($certificadoModel)) {
    // Serve the generated PDF to the user for download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $file_name . '"');
    readfile($file_path);
    exit;
} else {
    exibirMensagemEredirecionar("Erro ao registrar o certificado no banco de dados.", '../views/perfil/assistir_aulas.php?id=' . $id_curso);
}