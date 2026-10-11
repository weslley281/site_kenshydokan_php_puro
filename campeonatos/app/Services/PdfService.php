<?php
namespace App\Services;

// Carrega a biblioteca FPDF local
if (!class_exists('FPDF')) {
    $fpdfPath = dirname(__DIR__, 2) . '/public/assets/vendor/fpdf/fpdf.php';
    if (!file_exists($fpdfPath)) {
        $fpdfPath = dirname(__DIR__, 3) . '/libs/fpdf/fpdf.php';
    }
    require_once $fpdfPath;
}

use FPDF;

/**
 * Classe Estendida do FPDF com Cabeçalho e Rodapé Institucionais Kenshydokan
 */
class KenshyPDF extends FPDF
{
    private string $eventTitle = 'Campeonato Oficial Kenshydokan Jutsu';
    private string $docTitle = 'Documento Oficial';

    public function setEventInfo(string $eventTitle, string $docTitle): void
    {
        $this->eventTitle = $eventTitle;
        $this->docTitle = $docTitle;
    }

    public function conv(string $text): string
    {
        return iconv('UTF-8', 'windows-1252//TRANSLIT', $text) ?: utf8_decode($text);
    }

    public function Header(): void
    {
        // Barra Superior
        $this->SetFillColor(220, 38, 38); // Vermelho Kenshydokan
        $this->Rect(0, 0, $this->GetPageWidth(), 6, 'F');

        $this->SetY(10);
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(30, 41, 59);
        $this->Cell(0, 7, $this->conv('KENSHYDOKAN JUTSU - COMISSÃO NACIONAL DE ARBITRAGEM'), 0, 1, 'C');

        $this->SetFont('Arial', 'B', 11);
        $this->SetTextColor(220, 38, 38);
        $this->Cell(0, 6, $this->conv(mb_strtoupper($this->eventTitle, 'UTF-8')), 0, 1, 'C');

        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(100, 116, 139);
        $this->Cell(0, 5, $this->conv($this->docTitle), 0, 1, 'C');

        $this->SetDrawColor(203, 213, 225);
        $this->Line(10, 30, $this->GetPageWidth() - 10, 30);
        $this->Ln(6);
    }

    public function Footer(): void
    {
        $this->SetY(-15);
        $this->SetDrawColor(203, 213, 225);
        $this->Line(10, $this->GetY(), $this->GetPageWidth() - 10, $this->GetY());

        $this->SetFont('Arial', '', 8);
        $this->SetTextColor(148, 163, 184);
        $this->Cell(0, 8, $this->conv('Sistema Oficial de Gestão de Campeonatos - campeonatos.kenshydokan.org.br | Página ' . $this->PageNo() . ' de {nb}'), 0, 0, 'C');
    }
}

/**
 * Serviço de Geração de PDFs e Certificados Oficiais
 */
class PdfService
{
    /**
     * Gera PDF da Chave de uma Categoria
     */
    public static function generateBracketPdf(array $categoria, array $lutas, string $campeonatoTitulo): string
    {
        $pdf = new KenshyPDF('L', 'mm', 'A4'); // Paisagem para chaves
        $pdf->AliasNbPages();
        $pdf->setEventInfo($campeonatoTitulo, 'CHAVEAMENTO OFICIAL: ' . $categoria['nome']);
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->Cell(0, 8, $pdf->conv('Categoria: ' . $categoria['nome'] . ' (' . strtoupper($categoria['modalidade']) . ')'), 0, 1, 'L');

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(71, 85, 105);
        $infoCat = "Sexo: " . ($categoria['sexo'] === 'M' ? 'Masculino' : ($categoria['sexo'] === 'F' ? 'Feminino' : 'Misto'))
                 . " | Faixa Etária: " . $categoria['idade_min'] . " a " . $categoria['idade_max'] . " anos"
                 . ($categoria['peso_max'] ? " | Limite de Peso: até " . $categoria['peso_max'] . "kg" : "");
        $pdf->Cell(0, 5, $pdf->conv($infoCat), 0, 1, 'L');
        $pdf->Ln(4);

        // Tabela de Confrontos
        $pdf->SetFillColor(241, 245, 249);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->Cell(18, 7, 'Luta #', 1, 0, 'C', true);
        $pdf->Cell(18, 7, 'Rodada', 1, 0, 'C', true);
        $pdf->Cell(95, 7, $pdf->conv('Lado Vermelho (AKA)'), 1, 0, 'L', true);
        $pdf->Cell(95, 7, $pdf->conv('Lado Branco (SHIRO)'), 1, 0, 'L', true);
        $pdf->Cell(50, 7, 'Vencedor / Resultado', 1, 1, 'L', true);

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(30, 41, 59);

        foreach ($lutas as $luta) {
            $akaText = ($luta['aka_nome'] ?? 'BYE') . ($luta['aka_dojo'] ? ' (' . $luta['aka_dojo'] . ')' : '');
            $shiroText = ($luta['shiro_nome'] ?? 'BYE') . ($luta['shiro_dojo'] ? ' (' . $luta['shiro_dojo'] . ')' : '');
            $vencedorText = $luta['id_vencedor'] ? ($luta['id_vencedor'] == $luta['id_inscricao_aka'] ? 'Aka' : 'Shiro') . ' (' . strtoupper($luta['forma_vitoria'] ?? 'OK') . ')' : '-';

            $pdf->Cell(18, 6, '#' . $luta['numero_luta'], 1, 0, 'C');
            $pdf->Cell(18, 6, 'R' . $luta['rodada'], 1, 0, 'C');
            $pdf->Cell(95, 6, $pdf->conv(substr($akaText, 0, 45)), 1, 0, 'L');
            $pdf->Cell(95, 6, $pdf->conv(substr($shiroText, 0, 45)), 1, 0, 'L');
            $pdf->Cell(50, 6, $pdf->conv($vencedorText), 1, 1, 'L');
        }

        return $pdf->Output('S');
    }

    /**
     * Gera Certificado Oficial em Formato Paisagem com Borda Nobre e Hash
     */
    public static function generateCertificatePdf(array $campeonato, array $inscricao, string $tipoCertificado, string $codigoHash): string
    {
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();

        $w = $pdf->GetPageWidth();
        $h = $pdf->GetPageHeight();

        // Moldura Dupla Elegante
        $pdf->SetDrawColor(220, 38, 38); // Vermelho
        $pdf->SetLineWidth(2.5);
        $pdf->Rect(8, 8, $w - 16, $h - 16);

        $pdf->SetDrawColor(217, 119, 6); // Dourado
        $pdf->SetLineWidth(0.8);
        $pdf->Rect(12, 12, $w - 24, $h - 24);

        // Cabeçalho do Certificado
        $pdf->SetY(22);
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->SetTextColor(30, 41, 59);
        $pdf->Cell(0, 8, iconv('UTF-8', 'windows-1252//TRANSLIT', 'INSTITUTO KENSHYDOKAN DE ARTES MARCIAIS'), 0, 1, 'C');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetTextColor(220, 38, 38);
        $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252//TRANSLIT', 'COMISSÃO NACIONAL DE ARBITRAGEM'), 0, 1, 'C');

        $pdf->SetY(48);
        $pdf->SetFont('Times', 'B', 32);
        $pdf->SetTextColor(15, 23, 42);
        $tituloCert = ($tipoCertificado === 'campeao') ? 'CERTIFICADO DE CAMPEÃO' :
                     (($tipoCertificado === 'vice_campeao') ? 'CERTIFICADO DE VICE-CAMPEÃO' :
                     (($tipoCertificado === 'terceiro_lugar') ? 'CERTIFICADO DE 3º LUGAR' : 'CERTIFICADO DE PARTICIPAÇÃO'));
        $pdf->Cell(0, 12, iconv('UTF-8', 'windows-1252//TRANSLIT', $tituloCert), 0, 1, 'C');

        // Texto de Concessão
        $pdf->SetY(75);
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetTextColor(51, 65, 85);
        $pdf->Cell(0, 8, iconv('UTF-8', 'windows-1252//TRANSLIT', 'Certificamos que o atleta'), 0, 1, 'C');

        $pdf->SetY(86);
        $pdf->SetFont('Arial', 'B', 22);
        $pdf->SetTextColor(220, 38, 38);
        $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252//TRANSLIT', mb_strtoupper($inscricao['nome_atleta_snapshot'], 'UTF-8')), 0, 1, 'C');

        $pdf->SetY(100);
        $pdf->SetFont('Arial', '', 13);
        $pdf->SetTextColor(51, 65, 85);
        $dojo = $inscricao['dojo_snapshot'] ?: 'Kenshydokan';
        $cat = $inscricao['categoria_nome'] ?? 'Geral';
        $evento = $campeonato['titulo'];
        $dataEvento = date('d/m/Y', strtotime($campeonato['data_inicio']));
        $local = $campeonato['cidade'] . ' - ' . $campeonato['estado'];

        $texto = "Representando o dojo {$dojo}, participou oficialmente da categoria {$cat}\n"
               . "no evento {$evento}, realizado em {$dataEvento} na cidade de {$local}.";
        $pdf->MultiCell(0, 7, iconv('UTF-8', 'windows-1252//TRANSLIT', $texto), 0, 'C');

        // Linhas de Assinatura
        $pdf->SetY(148);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(30, 41, 59);

        $colWidth = ($w - 40) / 2;
        $pdf->SetX(20);
        $pdf->Cell($colWidth, 5, '____________________________________________', 0, 0, 'C');
        $pdf->Cell($colWidth, 5, '____________________________________________', 0, 1, 'C');

        $pdf->SetX(20);
        $pdf->Cell($colWidth, 5, iconv('UTF-8', 'windows-1252//TRANSLIT', 'Diretoria Técnica Kenshydokan'), 0, 0, 'C');
        $pdf->Cell($colWidth, 5, iconv('UTF-8', 'windows-1252//TRANSLIT', 'Árbitro Geral da Competição'), 0, 1, 'C');

        // Código de Autenticidade no Rodapé
        $pdf->SetY(175);
        $pdf->SetFont('Courier', '', 8);
        $pdf->SetTextColor(148, 163, 184);
        $pdf->Cell(0, 5, "Autenticidade Verificavel em campeonatos.kenshydokan.org.br/validar-certificado | Codigo: {$codigoHash}", 0, 1, 'C');

        return $pdf->Output('S');
    }
}
