<?php
// controllers/gerar_certificado_manual.php
session_start();

// Include necessary files
require_once(__DIR__ . '/../libs/fpdf/fpdf.php');
include_once __DIR__ . "/../models/certificadoManualModel.php";
include_once __DIR__ . "/../models/filiadoModel.php";
include_once __DIR__ . "/../models/usuarioModel.php";

// Helper function for messages and redirection
function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}

if (!isset($_SESSION["id_usuario"])) {
    exibirMensagemEredirecionar("Você precisa estar logado para visualizar certificados.", '../views/login.php');
}

if (!isset($_GET["id"])) {
    exibirMensagemEredirecionar("ID do certificado não fornecido.", '../views/inicio.php');
}

$id_certificado = intval($_GET["id"]);
$id_usuario = $_SESSION["id_usuario"];

$manualModel = new CertificadoManualModel();
$cert = $manualModel->buscarPorId($id_certificado);

if (!$cert) {
    exibirMensagemEredirecionar("Certificado não encontrado.", '../views/inicio.php');
}

// Check authorization: must be admin OR the owner filiado
$usuarioModelRepo = new Usuario();
$usuario = $usuarioModelRepo->buscarUsuario($id_usuario);

if ($usuario['nivel'] !== 'admin') {
    if (empty($usuario['id_fil']) || $usuario['id_fil'] != $cert['id_filiado']) {
        exibirMensagemEredirecionar("Acesso negado.", '../views/inicio.php');
    }
}

// Fetch filiado data
$filiadoModelRepo = new FiliadoModel();
$filiado_data_obj = $filiadoModelRepo->buscarFiliadoPorId($cert['id_filiado']);

if (!$filiado_data_obj) {
    exibirMensagemEredirecionar("Filiado não encontrado.", '../views/inicio.php');
}

$nome = $filiado_data_obj->getNome();
$codigo = $filiado_data_obj->getCodigo();
$dojo = $filiado_data_obj->getDojo();
$titulo = $cert['titulo'];
$data_emissao = date('d/m/Y', strtotime($cert['data_emissao']));

// Setup FPDF Class
class PDF_Certificado extends FPDF
{
    function Header()
    {
        // Background banner
        $this->SetFillColor(168, 32, 26); // Red WKKA
        $this->Rect(0, 0, 297, 10, 'F');
        
        $this->SetFillColor(30, 30, 36); // Charcoal
        $this->Rect(0, 10, 297, 2, 'F');
    }

    function Footer()
    {
        // Bottom decoration
        $this->SetFillColor(30, 30, 36);
        $this->Rect(0, 198, 297, 2, 'F');
        
        $this->SetFillColor(168, 32, 26);
        $this->Rect(0, 200, 297, 10, 'F');
    }
}

$pdf = new PDF_Certificado('L', 'mm', 'A4');
$pdf->AddPage();

// Draw a beautiful double border
$pdf->SetDrawColor(168, 32, 26); // WKKA Red
$pdf->SetLineWidth(1.5);
$pdf->Rect(12, 12, 273, 186);

$pdf->SetDrawColor(30, 30, 36); // Charcoal
$pdf->SetLineWidth(0.5);
$pdf->Rect(14, 14, 269, 182);

// Title
$pdf->SetFont('Arial', 'B', 24);
$pdf->SetTextColor(168, 32, 26);
$pdf->Ln(15);
$pdf->Cell(0, 15, mb_convert_encoding("CERTIFICADO", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

// Logo
if (file_exists('../img/wkka.jpg')) {
    $pdf->Image('../img/wkka.jpg', 133, 42, 30, 30);
}
$pdf->Ln(30);

// Subtitle / Certificate Title
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(30, 30, 36);
$pdf->Cell(0, 10, mb_convert_encoding($titulo ?? '', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Ln(5);

// Main Content
$pdf->SetFont('Arial', '', 13);
$pdf->SetTextColor(50, 50, 50);

$texto = "Certificamos que o filiado abaixo identificado está regularmente\nregistrado sob os regulamentos do Instituto de Artes Marciais e Defesa Pessoal Kenshydokan.";
$pdf->MultiCell(0, 8, mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8'), 0, 'C');
$pdf->Ln(8);

// Table/Grid of Filiado details
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(30, 30, 36);

// Left align details block
$pdf->SetX(35);
$pdf->Cell(60, 10, mb_convert_encoding("Nome do Filiado: ", 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, mb_convert_encoding($nome ?? '', 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

$pdf->SetX(35);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, mb_convert_encoding("Código de Registro: ", 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, mb_convert_encoding($codigo ?? '', 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

$pdf->SetX(35);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, mb_convert_encoding("Dojô de Origem: ", 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, mb_convert_encoding($dojo ?? '', 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

$pdf->SetX(35);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, mb_convert_encoding("Data de Emissão: ", 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, mb_convert_encoding($data_emissao ?? '', 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

$pdf->Ln(15);

// Space for signatures
$pdf->SetFont('Arial', '', 10);
$y_linha = $pdf->GetY();
$pdf->Cell(110, 5, "___________________________________", 0, 0, 'C');
$pdf->Cell(57, 5, '', 0, 0, 'C');
$pdf->Cell(110, 5, "___________________________________", 0, 1, 'C');

// Draw signature images on top of the lines if they exist
if (file_exists(__DIR__ . '/../arquivos/assinatura_presidente.png')) {
    $pdf->Image(__DIR__ . '/../arquivos/assinatura_presidente.png', 42.5, $y_linha - 8, 45, 15);
}
if (file_exists(__DIR__ . '/../arquivos/assinatura_diretor.png')) {
    $pdf->Image(__DIR__ . '/../arquivos/assinatura_diretor.png', 209.5, $y_linha - 8, 45, 15);
}

$pdf->Cell(110, 5, mb_convert_encoding("Shihan Jonas Teixeira de Andrade", 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
$pdf->Cell(57, 5, '', 0, 0, 'C');
$pdf->Cell(110, 5, mb_convert_encoding("Diretoria Técnica WKKA", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(110, 5, mb_convert_encoding("Presidente do Instituto", 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
$pdf->Cell(57, 5, '', 0, 0, 'C');
$pdf->Cell(110, 5, mb_convert_encoding("Homologação Kenshydokan", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

// Output PDF inline
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="certificado_manual_' . $id_certificado . '.pdf"');
$pdf->Output('I');
