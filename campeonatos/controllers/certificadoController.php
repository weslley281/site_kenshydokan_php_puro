<?php
/**
 * Controlador de Certificados Oficiais
 * Padrão IDÊNTICO ao site principal (controllers/certificadoController.php)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../models/certificadoModel.php";
include_once __DIR__ . "/../models/inscricaoModel.php";
include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/auditoriaModel.php";
include_once __DIR__ . "/alertHelper.php";

$certificadoModel = new Certificado();
$inscricaoModel = new Inscricao();
$campeonatoModel = new Campeonato();

// Emissão individual de certificado em PDF
if (isset($_GET['acao']) && $_GET['acao'] === 'gerar_pdf') {
    $idInscricao = (int)($_GET['id_inscricao'] ?? 0);
    $tipo = $_GET['tipo'] ?? 'participacao';

    $inscricao = $inscricaoModel->buscarPorId($idInscricao);
    if (!$inscricao) {
        exibirMensagemEredirecionar("Inscrição não encontrada.", "../views/campeonatos.php");
    }

    $campeonato = $campeonatoModel->buscarPorId((int)$inscricao['id_campeonato']);

    // Gera hash criptográfico
    $codigoHash = strtoupper(bin2hex(random_bytes(6))) . '-' . date('Y');
    $pdfNome = "cert_{$idInscricao}_" . time() . ".pdf";

    $certificadoModel->criarCertificado([
        'id_campeonato' => (int)$campeonato['id_campeonato'],
        'id_inscricao' => $idInscricao,
        'nome_titular' => $inscricao['nome_atleta_snapshot'],
        'tipo_certificado' => $tipo,
        'codigo_autenticidade' => $codigoHash,
        'arquivo_pdf_nome' => $pdfNome
    ]);

    Auditoria::log("EMITIR_CERTIFICADO", "campeonato_certificados", $idInscricao, null, [
        'atleta' => $inscricao['nome_atleta_snapshot'],
        'hash' => $codigoHash
    ], null, (int)$campeonato['id_campeonato']);

    // Renderiza PDF com FPDF nativo
    require_once __DIR__ . "/../libs/fpdf/fpdf.php";

    $pdf = new FPDF('L', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(false);

    // Borda dupla elegante
    $pdf->SetDrawColor(220, 38, 38);
    $pdf->SetLineWidth(1.5);
    $pdf->Rect(10, 10, 277, 190);
    $pdf->SetDrawColor(17, 24, 39);
    $pdf->SetLineWidth(0.5);
    $pdf->Rect(13, 13, 271, 184);

    // Cabeçalho
    $pdf->SetFont('Arial', 'B', 24);
    $pdf->SetTextColor(17, 24, 39);
    $pdf->Cell(0, 30, utf8_decode('FEDERAÇÃO KENSHYDOKAN JUTSU'), 0, 1, 'C');

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetTextColor(220, 38, 38);
    $pdf->Cell(0, 10, utf8_decode('CERTIFICADO OFICIAL'), 0, 1, 'C');

    // Corpo
    $pdf->Ln(15);
    $pdf->SetFont('Arial', '', 14);
    $pdf->SetTextColor(55, 65, 81);

    $texto = "Certificamos que o(a) atleta " . strtoupper($inscricao['nome_atleta_snapshot']) . ", representando o dojo " . strtoupper($inscricao['dojo_snapshot']) . ", participou oficialmente do campeonato " . strtoupper($campeonato['titulo']) . ", realizado em " . date('d/m/Y', strtotime($campeonato['data_inicio'])) . " na cidade de " . $campeonato['cidade'] . "-" . $campeonato['estado'] . ".";

    $pdf->SetX(25);
    $pdf->MultiCell(247, 10, utf8_decode($texto), 0, 'C');

    // Assinaturas
    $pdf->Ln(25);
    $y = $pdf->GetY();
    $pdf->Line(40, $y + 15, 110, $y + 15);
    $pdf->Line(187, $y + 15, 257, $y + 15);

    $pdf->SetY($y + 18);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetX(40);
    $pdf->Cell(70, 5, utf8_decode('Diretoria Técnica'), 0, 0, 'C');
    $pdf->SetX(187);
    $pdf->Cell(70, 5, utf8_decode('Presidente da Federação'), 0, 1, 'C');

    // Hash de autenticidade
    $pdf->SetY(185);
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetTextColor(156, 163, 175);
    $pdf->Cell(0, 5, utf8_decode('Autenticidade verificável em kenshydokan.org.br | Código Hash: ' . $codigoHash), 0, 1, 'C');

    $pdf->Output('I', $pdfNome);
    exit;
}

// Emissão em lote via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tipo = $_POST["tipo"] ?? "";
    $idCampeonato = (int)($_POST["id_campeonato"] ?? 0);

    if ($tipo === "gerar_lote") {
        $inscricoes = $inscricaoModel->buscarPorCampeonato($idCampeonato);
        $emitidos = 0;

        foreach ($inscricoes as $ins) {
            if ($ins['status_elegibilidade'] === 'aprovada') {
                $codigoHash = strtoupper(bin2hex(random_bytes(6))) . '-' . date('Y');
                $pdfNome = "cert_{$ins['id_inscricao']}_" . time() . ".pdf";

                $certificadoModel->criarCertificado([
                    'id_campeonato' => $idCampeonato,
                    'id_inscricao' => (int)$ins['id_inscricao'],
                    'nome_titular' => $ins['nome_atleta_snapshot'],
                    'tipo_certificado' => 'participacao',
                    'codigo_autenticidade' => $codigoHash,
                    'arquivo_pdf_nome' => $pdfNome
                ]);
                $emitidos++;
            }
        }

        Auditoria::log("EMITIR_LOTE_CERTIFICADOS", "campeonato_certificados", null, null, ['total' => $emitidos], null, $idCampeonato);
        exibirMensagemEredirecionar("Lote de {$emitidos} certificados gerado com sucesso!", "../views/certificados.php?id=" . $idCampeonato);
    }
}
