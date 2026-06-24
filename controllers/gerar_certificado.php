<?php
session_start();

// Include necessary files
include_once __DIR__ . "/../models/aulaModel.php";
include_once __DIR__ . "/../models/cursoModel.php";
include_once __DIR__ . "/../models/certificadoModel.php";
include_once __DIR__ . "/../models/usuarioModel.php";

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

// Instantiate models (which now handle database logic)
$aulaModelRepo = new AulaModel();
$cursoModelRepo = new CursoModel();
$certificadoModelRepo = new CertificadoModel();
$usuarioModelRepo = new Usuario();

// Fetch course and user data
$curso = $cursoModelRepo->buscarCurso($id_curso);
$usuario = $usuarioModelRepo->buscarUsuario($id_usuario);

if (!$curso || !$usuario) {
    exibirMensagemEredirecionar("Curso ou usuário não encontrado.", '../views/perfil/assistir_aulas.php');
}

if ($curso['temCertificado'] !== 'sim') {
    exibirMensagemEredirecionar("Este curso não oferece certificado.", '../views/perfil/assistir_aulas.php?id=' . $id_curso);
}

// Check eligibility
$total_aulas = $aulaModelRepo->getTotalAulasPorCurso($id_curso);
$aulas_assistidas = $aulaModelRepo->getAulasAssistidasPorUsuario($id_usuario);
$aulas_assistidas_no_curso = 0;
foreach ($aulas_assistidas as $aula_id) {
    $aula_detail = $aulaModelRepo->buscarAula($aula_id);
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
$existing_certificates = $certificadoModelRepo->buscarCertificadosPorUsuario($id_usuario);
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
$logo_path = '../arquivos/logo_instituto.png';
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
if (file_exists('../arquivos/assinatura_presidente.png')) {
    $pdf->Image('../arquivos/assinatura_presidente.png', 47.5, $y_linha - 6, 45, 15);
}
if (file_exists('../arquivos/assinatura_diretor.png')) {
    $pdf->Image('../arquivos/assinatura_diretor.png', 207.5, $y_linha - 6, 45, 15);
}

$pdf->Cell(120, 8, mb_convert_encoding("Presidente do Instituto", 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
$pdf->Cell(40, 8, '', 0, 0, 'C');
$pdf->Cell(120, 8, mb_convert_encoding("Diretor Técnico", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

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

if ($certificadoModelRepo->criarCertificado($certificadoModel)) {
    // Serve the generated PDF to the user for download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $file_name . '"');
    readfile($file_path);
    exibirMensagemEredirecionar("Certificado Gerado com sucesso.", '../views/perfil/assistir_aulas.php?id=' . $id_curso);
} else {
    exibirMensagemEredirecionar("Erro ao registrar o certificado no banco de dados.", '../views/perfil/assistir_aulas.php?id=' . $id_curso);
}
