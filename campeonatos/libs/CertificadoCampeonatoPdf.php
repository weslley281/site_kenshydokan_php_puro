<?php

require_once __DIR__ . '/fpdf/fpdf.php';

class CertificadoCampeonatoPdf extends FPDF {
    private function texto(string $texto): string {
        $convertido = iconv('UTF-8', 'windows-1252//TRANSLIT', $texto);
        return $convertido !== false ? $convertido : $texto;
    }

    public static function emitir(array $certificado, array $resultados): void {
        $pdf = new self('P', 'mm', 'A4');
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 18);
        $pdf->AddPage();

        $pdf->SetDrawColor(217, 35, 45);
        $pdf->SetLineWidth(1.2);
        $pdf->Rect(8, 8, 194, 281);
        $pdf->SetDrawColor(17, 24, 39);
        $pdf->SetLineWidth(0.4);
        $pdf->Rect(11, 11, 188, 275);

        $pdf->SetY(28);
        $pdf->SetTextColor(17, 24, 39);
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(0, 12, $pdf->texto('KENSHYDOKAN JUTSU'), 0, 1, 'C');

        $pdf->SetTextColor(217, 35, 45);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, $pdf->texto('CERTIFICADO DE PARTICIPACAO E RESULTADOS'), 0, 1, 'C');

        $pdf->Ln(10);
        $pdf->SetTextColor(55, 65, 81);
        $pdf->SetFont('Arial', '', 12);
        $texto = 'Certificamos que ' . $certificado['nome_titular']
            . ', representando ' . ($certificado['dojo'] ?: 'seu dojo')
            . ', participou do campeonato ' . $certificado['campeonato_titulo']
            . ', realizado de ' . date('d/m/Y', strtotime($certificado['campeonato_data_inicio']))
            . ' a ' . date('d/m/Y', strtotime($certificado['campeonato_data_fim']))
            . ', em ' . $certificado['campeonato_cidade'] . ' - ' . $certificado['campeonato_estado'] . '.';
        $pdf->SetX(23);
        $pdf->MultiCell(164, 8, $pdf->texto($texto), 0, 'C');

        $pdf->Ln(9);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(17, 24, 39);
        $pdf->Cell(0, 8, $pdf->texto('RESULTADOS HOMOLOGADOS NO CAMPEONATO'), 0, 1, 'C');
        $pdf->Ln(2);

        $pdf->SetFillColor(241, 245, 249);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(87, 8, $pdf->texto('Categoria'), 1, 0, 'L', true);
        $pdf->Cell(35, 8, $pdf->texto('Modalidade'), 1, 0, 'C', true);
        $pdf->Cell(25, 8, $pdf->texto('Colocacao'), 1, 0, 'C', true);
        $pdf->Cell(33, 8, $pdf->texto('Premiacao'), 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(31, 41, 55);
        if (!$resultados) {
            $pdf->Cell(180, 10, $pdf->texto('Participacao registrada; ainda nao ha classificacoes homologadas.'), 1, 1, 'C');
        } else {
            foreach ($resultados as $resultado) {
                if ($pdf->GetY() > 255) {
                    $pdf->AddPage();
                    $pdf->SetFillColor(241, 245, 249);
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(87, 8, $pdf->texto('Categoria'), 1, 0, 'L', true);
                    $pdf->Cell(35, 8, $pdf->texto('Modalidade'), 1, 0, 'C', true);
                    $pdf->Cell(25, 8, $pdf->texto('Colocacao'), 1, 0, 'C', true);
                    $pdf->Cell(33, 8, $pdf->texto('Premiacao'), 1, 1, 'C', true);
                    $pdf->SetFont('Arial', '', 9);
                }
                $premiacao = [
                    'ouro' => 'Ouro',
                    'prata' => 'Prata',
                    'bronze' => 'Bronze',
                    'nenhuma' => '-'
                ][$resultado['medalha']] ?? '-';
                $pdf->Cell(87, 7, $pdf->texto($resultado['categoria']), 1, 0, 'L');
                $pdf->Cell(35, 7, $pdf->texto(ucfirst($resultado['modalidade'])), 1, 0, 'C');
                $pdf->Cell(25, 7, (int)$resultado['colocacao'] . 'o lugar', 1, 0, 'C');
                $pdf->Cell(33, 7, $pdf->texto($premiacao), 1, 1, 'C');
            }
        }

        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(107, 114, 128);
        $pdf->MultiCell(0, 5, $pdf->texto(
            'Codigo de autenticidade: ' . $certificado['codigo_autenticidade']
            . "\nValidacao: https://kenshydokan.org.br/campeonatos/views/validar_certificado.php"
        ), 0, 'C');

        $nomeArquivo = 'certificado_campeonato_' . (int)$certificado['id_certificado'] . '.pdf';
        $pdf->Output('I', $nomeArquivo);
    }
}
