<?php
/**
 * Controlador de Relatórios Oficiais em PDF
 * Padrão IDÊNTICO ao site principal (controllers/relatorioController.php)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/authHelper.php";
exigirLogin();

include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/inscricaoModel.php";
include_once __DIR__ . "/../models/pesagemModel.php";
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/chaveModel.php";
include_once __DIR__ . "/../models/lutaModel.php";
include_once __DIR__ . "/alertHelper.php";

require_once __DIR__ . "/../libs/fpdf/fpdf.php";

class KenshyPDF extends FPDF {
    private $eventoTitulo = 'KENSHYDOKAN JUTSU';
    private $relatorioSubtitulo = 'RELATÓRIO OFICIAL';

    public function setEventInfo($titulo, $subtitulo) {
        $this->eventoTitulo = $titulo;
        $this->relatorioSubtitulo = $subtitulo;
    }

    public function conv($str) {
        $texto = (string)($str ?? '');
        $convertido = iconv('UTF-8', 'windows-1252//TRANSLIT', $texto);
        return $convertido !== false ? $convertido : $texto;
    }

    function Header() {
        $this->SetFillColor(17, 24, 39);
        $this->Rect(0, 0, $this->GetPageWidth(), 24, 'F');

        $this->SetFillColor(220, 38, 38);
        $this->Rect(0, 24, $this->GetPageWidth(), 2, 'F');

        $this->SetY(5);
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 6, $this->conv($this->eventoTitulo), 0, 1, 'C');

        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(209, 213, 219);
        $this->Cell(0, 5, $this->conv($this->relatorioSubtitulo), 0, 1, 'C');

        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(156, 163, 175);
        $this->Cell(0, 10, $this->conv('Kenshydokan Jutsu Oficial &bull; Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$idCampeonato = (int)($_GET['id_campeonato'] ?? 0);
$tipo = $_GET['tipo'] ?? 'inscritos';

$campeonatoModel = new Campeonato();
$campeonato = $campeonatoModel->buscarPorId($idCampeonato);

if (!$campeonato) {
    die("Campeonato não encontrado.");
}

$pdf = new KenshyPDF('P', 'mm', 'A4');
$pdf->AliasNbPages();

if ($tipo === 'inscritos') {
    $inscricaoModel = new Inscricao();
    $inscricoes = $inscricaoModel->buscarPorCampeonato($idCampeonato);

    $pdf->setEventInfo($campeonato['titulo'], 'RELAÇÃO GERAL DE ATLETAS INSCRITOS');
    $pdf->AddPage();

    $pdf->SetFillColor(241, 245, 249);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(17, 24, 39);
    $pdf->Cell(65, 7, $pdf->conv('Nome do Atleta'), 1, 0, 'L', true);
    $pdf->Cell(45, 7, $pdf->conv('Dojo / Academia'), 1, 0, 'L', true);
    $pdf->Cell(45, 7, $pdf->conv('Graduação'), 1, 0, 'L', true);
    $pdf->Cell(35, 7, 'Status', 1, 1, 'C', true);

    $pdf->SetFont('Arial', '', 8.5);
    foreach ($inscricoes as $i) {
        $pdf->Cell(65, 6, $pdf->conv(substr($i['nome_atleta_snapshot'], 0, 32)), 1, 0, 'L');
        $pdf->Cell(45, 6, $pdf->conv(substr($i['dojo_snapshot'], 0, 22)), 1, 0, 'L');
        $pdf->Cell(45, 6, $pdf->conv(substr($i['graduacao_nome_snapshot'], 0, 22)), 1, 0, 'L');
        $pdf->Cell(35, 6, strtoupper($i['status_elegibilidade']), 1, 1, 'C');
    }

    $pdf->Output('I', 'inscritos_' . $idCampeonato . '.pdf');
    exit;

} elseif ($tipo === 'pesagem') {
    $pesagemModel = new Pesagem();
    $pesagens = $pesagemModel->buscarPorCampeonato($idCampeonato);

    $pdf->setEventInfo($campeonato['titulo'], 'RELATÓRIO OFICIAL DE PESAGEM');
    $pdf->AddPage();

    $pdf->SetFillColor(241, 245, 249);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(17, 24, 39);
    $pdf->Cell(65, 7, $pdf->conv('Atleta'), 1, 0, 'L', true);
    $pdf->Cell(50, 7, $pdf->conv('Categoria'), 1, 0, 'L', true);
    $pdf->Cell(25, 7, 'Tentativa', 1, 0, 'C', true);
    $pdf->Cell(25, 7, 'Peso (kg)', 1, 0, 'C', true);
    $pdf->Cell(25, 7, 'Resultado', 1, 1, 'C', true);

    $pdf->SetFont('Arial', '', 8.5);
    foreach ($pesagens as $p) {
        $pdf->Cell(65, 6, $pdf->conv(substr($p['nome_atleta'], 0, 32)), 1, 0, 'L');
        $pdf->Cell(50, 6, $pdf->conv(substr($p['categoria_nome'], 0, 25)), 1, 0, 'L');
        $pdf->Cell(25, 6, $p['tentativa'] . 'a', 1, 0, 'C');
        $pdf->Cell(25, 6, number_format($p['peso_aferido'], 2, ',', '.'), 1, 0, 'C');
        $pdf->Cell(25, 6, strtoupper($p['resultado']), 1, 1, 'C');
    }

    $pdf->Output('I', 'pesagem_' . $idCampeonato . '.pdf');
    exit;

} elseif ($tipo === 'chave') {
    $idCategoria = (int)($_GET['id_categoria'] ?? 0);
    $categoriaModel = new Categoria();
    $chaveModel = new Chave();
    $lutaModel = new Luta();

    $categoria = $categoriaModel->buscarPorId($idCategoria);
    $chave = $chaveModel->buscarPorCategoria($idCategoria);

    if (!$chave) {
        die("Chave ainda não sorteada para esta categoria.");
    }

    $lutas = $lutaModel->buscarPorChave((int)$chave['id_chave']);

    $pdf = new KenshyPDF('L', 'mm', 'A4');
    $pdf->AliasNbPages();
    $pdf->setEventInfo($campeonato['titulo'], 'CHAVE ELIMINATÓRIA: ' . strtoupper($categoria['nome']));
    $pdf->AddPage();

    $pdf->SetFillColor(241, 245, 249);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor(17, 24, 39);
    $pdf->Cell(25, 8, 'Luta #', 1, 0, 'C', true);
    $pdf->Cell(30, 8, 'Rodada', 1, 0, 'C', true);
    $pdf->Cell(85, 8, 'Canto Vermelho (AKA)', 1, 0, 'L', true);
    $pdf->Cell(85, 8, 'Canto Branco (SHIRO)', 1, 0, 'L', true);
    $pdf->Cell(52, 8, 'Vencedor', 1, 1, 'L', true);

    $pdf->SetFont('Arial', '', 9);
    foreach ($lutas as $l) {
        $pdf->Cell(25, 7, '#' . $l['numero_luta'], 1, 0, 'C');
        $pdf->Cell(30, 7, $l['rodada'] . 'a Rodada', 1, 0, 'C');
        $pdf->Cell(85, 7, $pdf->conv(substr($l['nome_aka'] ?? 'Aguardando', 0, 38)), 1, 0, 'L');
        $pdf->Cell(85, 7, $pdf->conv(substr($l['nome_shiro'] ?? 'Aguardando', 0, 38)), 1, 0, 'L');
        $pdf->Cell(52, 7, $pdf->conv(substr($l['nome_vencedor'] ?? '-', 0, 24)), 1, 1, 'L');
    }

    $pdf->Output('I', 'chave_' . $idCategoria . '.pdf');
    exit;
}
