<?php

use Svg\Tag\Line;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Proteção da página
if (!isset($_SESSION["id_usuario"])) {
    die("Acesso negado. Por favor, faça o login.");
}

require_once '../libs/fpdf/fpdf.php';
require_once '../models/filiadoModel.php';
require_once '../models/graduacaoModel.php';
require_once '../db/conexao.php'; // Para a busca da imagem do usuário

// Pega o ID do filiado da sessão do usuário
$id_filiado = $_SESSION['id_fil'] ?? null;

if (!$id_filiado) {
    die("ID de filiado não encontrado na sua sessão.");
}

// Busca os dados do filiado
$filiadoModel = new FiliadoModel();
$filiado = $filiadoModel->buscarFiliadoPorId($id_filiado);

if (!$filiado) {
    die("Filiado não encontrado.");
}

if ($filiado->getConfirmacao() !== 'sim') {
    die("Você não tem uma filiação confirmada para gerar a carteirinha.");
}

$graduacaoModel = new Graduacao();
$graduacao = $graduacaoModel->buscarGraduacaoPorId($filiado->getIdGraduacao());

// --- Busca a imagem do usuário (lógica similar a _perfil_auth.php) ---
$conn = new Conexao();
$conexao = $conn->conectar();
$id_usuario = $_SESSION['id_usuario'];
$busca_usuario = $conexao->prepare("SELECT id_imagem FROM usuarios WHERE id_usuario = ?");
$busca_usuario->bind_param("i", $id_usuario);
$busca_usuario->execute();
$resultado_usuario = $busca_usuario->get_result();
$usuario = $resultado_usuario->fetch_assoc();
$id_imagem = $usuario['id_imagem'] ?? null;
$largura_assinatura = 18;
$x_assinatura = 61;
$y_assinatura = 33;

$caminho_foto = '../img/sem_foto.png'; // Padrão
if ($id_imagem) {
    $busca_imagem = $conexao->prepare("SELECT nome FROM imagens WHERE id_imagem = ?");
    $busca_imagem->bind_param("i", $id_imagem);
    $busca_imagem->execute();
    $resultado_imagem = $busca_imagem->get_result();
    $imagem = $resultado_imagem->fetch_assoc();
    if ($imagem && file_exists('../img/' . $imagem['nome'])) {
        $caminho_foto = '../img/' . $imagem['nome'];
    }
}
$conexao->close();
// --- Fim da busca de imagem ---

// --- Início da Geração do PDF ---
$pdf = new FPDF('L', 'mm', [85.6, 54]); // L=Landscape, tamanho de cartão de crédito
$pdf->AddPage();
$pdf->SetMargins(3, 3, 3);
$pdf->SetAutoPageBreak(false);

// Fundo do Cartão (Branco)
$pdf->SetFillColor(255, 255, 255);
$pdf->Rect(1, 1, 83.6, 52, 'F');

// 1. Faixa do Cabeçalho (Vermelho Kenshydokan)
$pdf->SetFillColor(217, 35, 45); // Primary Red (RGB)
$pdf->Rect(1, 1, 83.6, 11, 'F');

// Logo no Cabeçalho
$pdf->Image('../arquivos/logo_instituto.png', 3.5, 2, 8, 8); // X, Y, Largura, Altura

// Texto do Cabeçalho em Branco
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 5.5);
$pdf->SetXY(13, 2.5);
$pdf->MultiCell(70, 2.8, mb_convert_encoding("INSTITUTO DE ARTES MARCIAIS E DEFESA PESSOAL\nKENSHYDOKAN", 'ISO-8859-1', 'UTF-8'), 0, 'L');

// 2. Faixa do Rodapé (Preto/Charcoal)
$pdf->SetFillColor(26, 26, 26); // Dark Charcoal
$pdf->Rect(1, 49, 83.6, 4, 'F');

// Texto do Rodapé
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 4.5);
$pdf->SetXY(1, 49.5);
$pdf->Cell(83.6, 3, mb_convert_encoding('WKKA - WORLD KENSHYDOKAN KARATE ASSOCIATION', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');

// 3. Moldura da Foto do Filiado
$pdf->SetDrawColor(217, 35, 45);
$pdf->SetLineWidth(0.35);
$pdf->Rect(3.8, 13.8, 21.4, 28.4); // Moldura ligeiramente maior que a foto

// Foto do Filiado
$pdf->Image($caminho_foto, 4, 14, 21, 28); // X, Y, Largura, Altura

// 4. Informações do Filiado
// Nome do Aluno (Em destaque, caixa alta, cor escura)
$pdf->SetTextColor(26, 26, 26);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(28, 14);
$pdf->Cell(53, 4, mb_convert_encoding(strtoupper($filiado->getNome()), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

// Linha divisória abaixo do nome
$pdf->SetDrawColor(217, 35, 45);
$pdf->SetLineWidth(0.2);
$pdf->Line(28, 18.5, 81, 18.5);

// Lista de Informações Alinhadas (Estilo Tabela)
$detalhes = [
    'Dojo' => $filiado->getDojo(),
    'Registro' => str_pad($filiado->getCodigo(), 5, '0', STR_PAD_LEFT),
    'Nascimento' => date('d/m/Y', strtotime($filiado->getDataNascimento())),
    'Graduação' => $graduacao->getGraduacao(),
    'Validade' => '31/12/' . date('Y')
];

$y_detalhe = 20.2;
foreach ($detalhes as $label => $value) {
    $pdf->SetXY(28, $y_detalhe);
    
    // Label (Cinza, Negrito)
    $pdf->SetFont('Arial', 'B', 5.5);
    $pdf->SetTextColor(110, 110, 110);
    $pdf->Cell(15, 3, mb_convert_encoding($label . ':', 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
    
    // Valor (Escuro, Normal)
    $pdf->SetFont('Arial', '', 5.5);
    $pdf->SetTextColor(26, 26, 26);
    $pdf->Cell(38, 3, mb_convert_encoding($value, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
    
    $y_detalhe += 2.8;
}

// 5. Assinatura do Presidente
// Desenha a imagem da assinatura
$pdf->Image('../arquivos/assinatura_presidente.png', $x_assinatura, $y_assinatura, $largura_assinatura);

// Descobre altura proporcional da imagem em mm para colocar a linha e o texto abaixo
list($largura_px, $altura_px) = getimagesize('../arquivos/assinatura_presidente.png');
$altura_assinatura = ($altura_px / $largura_px) * $largura_assinatura;

// Linha fina para a assinatura
$y_linha = $y_assinatura + $altura_assinatura + 0.5;
$pdf->SetDrawColor(180, 180, 180);
$pdf->SetLineWidth(0.15);
$pdf->Line($x_assinatura - 2, $y_linha, $x_assinatura + $largura_assinatura + 2, $y_linha);

// Texto "PRESIDENTE"
$y_presidente = $y_linha + 0.5;
$pdf->SetXY($x_assinatura - 2, $y_presidente);
$pdf->SetFont('Arial', 'B', 4);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell($largura_assinatura + 4, 2.5, mb_convert_encoding('PRESIDENTE', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');

// 6. Borda geral do cartão
$pdf->SetDrawColor(217, 35, 45);
$pdf->SetLineWidth(0.5);
$pdf->Rect(1, 1, 83.6, 52);

$pdf->Output('I', 'carteirinha_kenshydokan.pdf'); // 'I' para inline, 'D' para download
