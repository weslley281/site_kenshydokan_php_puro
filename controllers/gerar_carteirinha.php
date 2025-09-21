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
require_once '../repositorios/filiadoRepositorio.php';
require_once '../repositorios/graduacaoRepositorio.php';
require_once '../db/conexao.php'; // Para a busca da imagem do usuário

// Pega o ID do filiado da sessão do usuário
$id_filiado = $_SESSION['id_fil'] ?? null;

if (!$id_filiado) {
    die("ID de filiado não encontrado na sua sessão.");
}

// Busca os dados do filiado
$filiadoRepo = new FiliadoRepositorio();
$filiado = $filiadoRepo->buscarFiliadoPorId($id_filiado);

if (!$filiado) {
    die("Filiado não encontrado.");
}

if ($filiado->getConfirmacao() !== 'sim') {
    die("Você não tem uma filiação confirmada para gerar a carteirinha.");
}

$graduacaoRepositorio = new GraduacaoRepositorio();
$graduacao = $graduacaoRepositorio->buscarGraduacaoPorId($filiado->getIdGraduacao());

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
$largura_assinatura = 20;
$x_assinatura = 58;
$y_assinatura = 35;

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

// Adiciona uma borda
$pdf->Rect(1, 1, 83.6, 52);

// Logo do Instituto
$pdf->Image('../arquivos/logo_instituto.png', 4, 4, 15); // X, Y, Largura

// Título
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetXY(22, 3);
$pdf->MultiCell(65, 1, mb_convert_encoding('Instituto de Artes Marciais e Defesa Pessoal Kenshydokan', 'ISO-8859-1', 'UTF-8'), 0, 'C');

// Foto do Filiado
$pdf->Image($caminho_foto, 4, 20, 22, 28); // X, Y, Largura, Altura

// Informações do Filiado
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(28, 6);
$pdf->Cell(65, 4, mb_convert_encoding(strtoupper($filiado->getNome()), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

$pdf->SetFont('Arial', '', 6);
// Linha 1
$pdf->SetXY(28, 9);
$pdf->Cell(55, 3.5, mb_convert_encoding('Dojo: ' . $filiado->getDojo(), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
// Linha 2
$pdf->SetXY(28, 12);
$pdf->Cell(55, 3.5, mb_convert_encoding('Código: ' . str_pad($filiado->getCodigo(), 5, '0', STR_PAD_LEFT), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
// Linha 3
$pdf->SetXY(28, 15);
$pdf->Cell(55, 3.5, mb_convert_encoding('Válido até: 31/12/' . date('Y'), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
// Linha 4
$pdf->SetXY(28, 18);
$pdf->Cell(55, 3.5, mb_convert_encoding('Graduação: ' . $graduacao->getGraduacao(), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
// Linha 5
$pdf->SetXY(28, 21);
$pdf->Cell(55, 3.5, mb_convert_encoding('Data de Nascimento: ' . date('d/m/Y', strtotime($filiado->getDataNascimento())), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

// Desenha a imagem
$pdf->Image('../arquivos/assinatura_presidente.png', $x_assinatura, $y_assinatura, $largura_assinatura);

// Descobre altura proporcional da imagem em mm
list($largura_px, $altura_px) = getimagesize('../arquivos/assinatura_presidente.png');
$altura_assinatura = ($altura_px / $largura_px) * $largura_assinatura;

// Define posição do texto logo abaixo da assinatura
$y_presidente = $y_assinatura + $altura_assinatura + 2; // margem de 2mm

$pdf->SetXY($x_assinatura, $y_presidente);
//$pdf->SetFont('Arial', 'I', 7);
//$pdf->Cell($largura_assinatura, 4, 'Presidente', 0, 0, 'C');


$pdf->Output('I', 'carteirinha_kenshydokan.pdf'); // 'I' para inline, 'D' para download
