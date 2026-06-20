<?php
// controllers/gerar_recibo.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Proteção da página
if (!isset($_SESSION["id_usuario"]) || $_SESSION['nivel'] != "admin") {
    die("Acesso negado. Apenas administradores podem gerar recibos.");
}

require_once '../libs/fpdf/fpdf.php';
require_once '../models/dojoMensalidadeModel.php';
require_once '../models/filiadoModel.php';
require_once '../models/graduacaoModel.php';
require_once '../db/conexao.php';

$id_mensalidade = intval($_GET['id'] ?? 0);
if (!$id_mensalidade) {
    die("ID de mensalidade inválido.");
}

$mensalidadeModel = new DojoMensalidadeModel();
$filiadoModel = new FiliadoModel();
$gradModel = new Graduacao();

$m = $mensalidadeModel->buscarMensalidadePorId($id_mensalidade);
if (!$m) {
    die("Mensalidade não encontrada.");
}

if ($m['status_pagamento'] !== 'pago') {
    die("Este recibo não pode ser gerado porque a mensalidade ainda está pendente.");
}

$f = $filiadoModel->buscarFiliadoPorId($m['id_filiado']);
if (!$f) {
    die("Filiado não encontrado.");
}

$g = $gradModel->buscarGraduacaoPorId($f->getIdGraduacao());
$grad_nome = $g ? $g->getGraduacao() : 'Sem graduação';

// --- Inicialização do FPDF (Formato A5 deitado - Landscape - ideal para recibo) ---
$pdf = new FPDF('L', 'mm', 'A5');
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(false);

// Codificação do texto para ISO-8859-1 (FPDF nativo)
function fix($txt) {
    return mb_convert_encoding($txt, 'ISO-8859-1', 'UTF-8');
}

// 1. Moldura Decorativa do Recibo
$pdf->SetDrawColor(217, 35, 45); // Vermelho Principal
$pdf->SetLineWidth(0.8);
$pdf->Rect(5, 5, 200, 138);

$pdf->SetDrawColor(50, 50, 50); // Borda interna cinza escura
$pdf->SetLineWidth(0.2);
$pdf->Rect(7, 7, 196, 134);

// 2. Cabeçalho / Logo e Identidade
$pdf->Image('../img/wkka.jpg', 10, 10, 20); // Logo WKKA

$pdf->SetXY(33, 10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(217, 35, 45);
$pdf->Cell(110, 5, fix('WORLD KENSHYDOKAN KARATE ASSOCIATION'), 0, 1, 'L');

$pdf->SetX(33);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(50, 50, 50);
$pdf->Cell(110, 4, fix('Instituto de Artes Marciais e Defesa Pessoal Kenshydokan'), 0, 1, 'L');

$pdf->SetX(33);
$pdf->SetFont('Arial', '', 7.5);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(110, 4, fix('-----------------------------------------'), 0, 1, 'L');

// 3. Valor em Destaque (Top-Right)
$pdf->SetXY(150, 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetDrawColor(220, 220, 220);
$pdf->SetLineWidth(0.2);
$pdf->Cell(50, 14, '', 1, 0, '', true);

$pdf->SetXY(150, 11);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(50, 3, fix('VALOR RECEBIDO'), 0, 1, 'C');

$pdf->SetXY(150, 15);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(40, 167, 69); // Verde Success
$pdf->Cell(50, 7, fix('R$ ' . number_format($m['valor'], 2, ',', '.')), 0, 0, 'C');

// Divisor
$pdf->SetDrawColor(217, 35, 45);
$pdf->SetLineWidth(0.5);
$pdf->Line(10, 33, 200, 33);

// 4. Título do Documento
$pdf->SetXY(10, 36);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(30, 30, 30);
$pdf->Cell(130, 6, fix('RECIBO DE MENSALIDADE'), 0, 0, 'L');

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(60, 6, fix('RECIBO N° ' . str_pad($m['id'], 6, '0', STR_PAD_LEFT)), 0, 1, 'R');

// 5. Corpo de Declaração do Recibo
$pdf->SetXY(10, 47);
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(50, 50, 50);

$texto_declaracao = "Declaramos que recebemos de " . $f->getNome() . ", com CPF/Filiado registrado no sistema, a importância de R$ " . number_format($m['valor'], 2, ',', '.') . " referente ao pagamento da mensalidade do dojô, correspondente ao mês de referência " . date("m/Y", strtotime($m['referencia'] . '-01')) . ".";

$pdf->MultiCell(190, 5.5, fix($texto_declaracao), 0, 'L');

// 6. Dados Detalhados em Tabela Limpa
$pdf->SetXY(10, 68);
$pdf->SetFillColor(248, 249, 250);
$pdf->Cell(190, 30, '', 0, 0, '', true);

// Linhas internas da tabela
$pdf->SetXY(12, 70);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(120, 120, 120);
$pdf->Cell(35, 4, fix('ALUNO'), 0, 0, 'L');
$pdf->Cell(45, 4, fix('GRADUAÇÃO'), 0, 0, 'L');
$pdf->Cell(40, 4, fix('DOJÔ'), 0, 0, 'L');
$pdf->Cell(35, 4, fix('VENCIMENTO'), 0, 0, 'L');
$pdf->Cell(35, 4, fix('DATA PAGAMENTO'), 0, 1, 'L');

$pdf->SetX(12);
$pdf->SetFont('Arial', 'B', 9.5);
$pdf->SetTextColor(40, 40, 40);
$pdf->Cell(35, 6, fix(explode(' ', trim($f->getNome()))[0]), 0, 0, 'L'); // Apenas o primeiro nome pra caber na grid
$pdf->Cell(45, 6, fix($grad_nome), 0, 0, 'L');
$pdf->Cell(40, 6, fix($f->getDojo()), 0, 0, 'L');
$pdf->Cell(35, 6, fix(date("d/m/Y", strtotime($m['data_vencimento']))), 0, 0, 'L');
$pdf->Cell(35, 6, fix(date("d/m/Y", strtotime($m['data_pagamento']))), 0, 1, 'L');

// Linha de aviso
$pdf->SetXY(12, 86);
$pdf->SetFont('Arial', 'I', 7.5);
$pdf->SetTextColor(110, 110, 110);
$pdf->Cell(186, 4, fix('*Este documento serve como comprovante de quitação da referência acima citada.'), 0, 1, 'L');

// 7. Data e Assinatura
$data_hoje = date("d") . " de " . date("m") . " de " . date("Y");
// Mapeando mês para português
$meses = [
    '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
    '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
    '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
];
$data_extensa = date("d") . " de " . $meses[date("m")] . " de " . date("Y");

$pdf->SetXY(10, 105);
$pdf->SetFont('Arial', '', 9.5);
$pdf->SetTextColor(50, 50, 50);
$pdf->Cell(100, 5, fix('Cuiabá - MT, ' . $data_extensa . '.'), 0, 0, 'L');

// Assinatura do Responsável
$pdf->SetXY(120, 112);
$pdf->SetDrawColor(150, 150, 150);
$pdf->Line(120, 112, 195, 112);

$pdf->SetXY(120, 113.5);
$pdf->SetFont('Arial', 'B', 7.5);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(75, 4, fix('ASSINATURA DO INSTRUTOR'), 0, 0, 'C');

$pdf->SetXY(120, 117);
$pdf->SetFont('Arial', '', 7.5);
$pdf->Cell(75, 4, fix('Sensei / Representante WKKA'), 0, 0, 'C');

// RodapéWKKA
$pdf->SetXY(10, 129);
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetTextColor(160, 160, 160);
$pdf->Cell(190, 4, fix('WORLD KENSHYDOKAN KARATE ASSOCIATION - WKKA'), 0, 0, 'C');

$pdf->Output('I', 'recibo_mensalidade_' . $m['id'] . '.pdf');
?>
