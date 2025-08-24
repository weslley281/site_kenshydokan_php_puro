<?php
session_start();

// Include FPDF library (User needs to download and place it in libs/fpdf/)
require(__DIR__ . '/../libs/fpdf/fpdf.php');

include_once __DIR__ . "/../repositorios/AulaRepositorio.php";
include_once __DIR__ . "/../repositorios/CursoRepositorio.php";
include_once __DIR__ . "/../repositorios/CertificadoRepositorio.php";
include_once __DIR__ . "/../models/certificadoModel.php";
include_once __DIR__ . "/../repositorios/usuarioRepositorio.php"; // To get user name

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}

if (!isset($_SESSION["id_usuario"])) {
    exibirMensagemEredirecionar("Você precisa estar logado para gerar certificados.", '../views/login.php');
}

if (!isset($_GET["id_curso"])) {
    exibirMensagemEredirecionar("ID do curso não fornecido.", '../views/perfil/assistir_aulas.php');
}

$id_usuario = $_SESSION["id_usuario"];
$id_curso = $_GET["id_curso"];

$aulaRepositorio = new AulaRepositorio();
$cursoRepositorio = new CursoRepositorio();
$certificadoRepositorio = new CertificadoRepositorio();
$usuarioRepositorio = new UsuarioRepositorio();

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
    // Need to verify if this aula_id belongs to the current course
    // This requires fetching aula details or modifying getAulasAssistidasPorUsuario
    // For simplicity, let's assume getAulasAssistidasPorUsuario returns only for the current course
    // A more robust check would involve joining with the 'aulas' table in the repository method.
    // For now, we'll count if the aula_id is in the list.
    $aula_detail = $aulaRepositorio->buscarAula($aula_id); // This is inefficient, optimize later
    if ($aula_detail && $aula_detail['id_curso'] == $id_curso) {
        $aulas_assistidas_no_curso++;
    }
}

$percentual_conclusao = ($total_aulas > 0) ? ($aulas_assistidas_no_curso / $total_aulas) * 100 : 0;

// Assuming 'percentual_conclusao_certificado' column exists in 'cursos' table
$percentual_necessario = $curso['percentual_conclusao_certificado'] ?? 100; 

if ($percentual_conclusao < $percentual_necessario) {
    exibirMensagemEredirecionar("Você não completou o percentual necessário de aulas para este curso. Conclusão: " . round($percentual_conclusao, 2) . "%. Necessário: " . $percentual_necessario . "%. ", '../views/perfil/assistir_aulas.php?id=' . $id_curso);
}

// Check if certificate already exists for this user and course
$existing_certificates = $certificadoRepositorio->buscarCertificadosPorUsuario($id_usuario);
foreach ($existing_certificates as $cert) {
    if ($cert['id_curso'] == $id_curso) {
        exibirMensagemEredirecionar("Você já possui um certificado para este curso.", '../views/perfil/meus_certificados.php');
    }
}

// Generate unique verification code
$codigo_verificacao = uniqid('CERT_') . bin2hex(random_bytes(8));

// --- FPDF Certificate Generation --- 
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Certificado de Conclusão',0,1,'C');
$pdf->Ln(10);
$pdf->SetFont('Arial','',12);
$pdf->MultiCell(0,10,utf8_decode('Certificamos que ') . utf8_decode($usuario['nome']) . utf8_decode(' concluiu com sucesso o curso de ') . utf8_decode($curso['nome']) . utf8_decode('.'),0,'C');
$pdf->Ln(10);
$pdf->Cell(0,10,utf8_decode('Data de Emissão: ') . date('d/m/Y'),0,1,'C');
$pdf->Ln(5);
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,10,'Código de Verificação: ' . $codigo_verificacao,0,1,'C');

// Save PDF
$cert_dir = '../arquivos/certificados/';
if (!is_dir($cert_dir)) {
    mkdir($cert_dir, 0777, true);
}
$file_name = 'certificado_' . $id_usuario . '_' . $id_curso . '.pdf';
$file_path = $cert_dir . $file_name;
$pdf->Output('F', $file_path);

// Record certificate in database
$certificadoModel = new CertificadoModel(
    null, // ID will be auto-incremented
    $id_usuario,
    $id_curso,
    $codigo_verificacao,
    date('Y-m-d H:i:s'),
    $file_path
);

if ($certificadoRepositorio->criarCertificado($certificadoModel)) {
    // Serve the generated PDF to the user
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $file_name . '"');
    readfile($file_path);
    exit;
} else {
    exibirMensagemEredirecionar("Erro ao registrar o certificado no banco de dados.", '../views/perfil/assistir_aulas.php?id=' . $id_curso);
}
?>