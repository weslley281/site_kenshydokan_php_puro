<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\CampeonatoModel;
use App\Models\InscricaoModel;
use App\Models\PesagemModel;
use App\Models\ClassificacaoModel;
use App\Services\KenshyPDF;

/**
 * Controlador de Emissão de Relatórios Oficiais em PDF
 */
class RelatorioController extends Controller
{
    public function generatePdf(string $id, string $tipo): void
    {
        $this->requireAuth();
        $idCampeonato = (int)$id;

        $campeonato = CampeonatoModel::find($idCampeonato);
        if (!$campeonato) {
            echo "Campeonato não encontrado.";
            exit;
        }

        $pdf = new KenshyPDF('P', 'mm', 'A4');
        $pdf->AliasNbPages();

        switch ($tipo) {
            case 'inscritos':
                $inscricoes = InscricaoModel::byCampeonato($idCampeonato);
                $pdf->setEventInfo($campeonato['titulo'], 'RELAÇÃO GERAL DE ATLETAS INSCRITOS');
                $pdf->AddPage();

                $pdf->SetFillColor(241, 245, 249);
                $pdf->SetFont('Arial', 'B', 9);
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
                break;

            case 'pesagem':
                $pesagens = PesagemModel::byCampeonato($idCampeonato);
                $pdf->setEventInfo($campeonato['titulo'], 'RELATÓRIO OFICIAL DE PESAGEM');
                $pdf->AddPage();

                $pdf->SetFillColor(241, 245, 249);
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(65, 7, $pdf->conv('Atleta'), 1, 0, 'L', true);
                $pdf->Cell(50, 7, $pdf->conv('Categoria'), 1, 0, 'L', true);
                $pdf->Cell(25, 7, 'Peso Aferido', 1, 0, 'C', true);
                $pdf->Cell(25, 7, 'Resultado', 1, 0, 'C', true);
                $pdf->Cell(25, 7, $pdf->conv('Horário'), 1, 1, 'C', true);

                $pdf->SetFont('Arial', '', 8.5);
                foreach ($pesagens as $p) {
                    $pdf->Cell(65, 6, $pdf->conv(substr($p['nome_atleta_snapshot'], 0, 32)), 1, 0, 'L');
                    $pdf->Cell(50, 6, $pdf->conv(substr($p['categoria_nome'], 0, 25)), 1, 0, 'L');
                    $pdf->Cell(25, 6, $p['peso_aferido'] . ' kg', 1, 0, 'C');
                    $pdf->Cell(25, 6, strtoupper($p['resultado']), 1, 0, 'C');
                    $pdf->Cell(25, 6, date('H:i', strtotime($p['data_hora'])), 1, 1, 'C');
                }
                break;

            case 'medalhas':
                $tabela = ClassificacaoModel::getMedalTable($idCampeonato);
                $pdf->setEventInfo($campeonato['titulo'], 'QUADRO GERAL DE MEDALHAS POR ACADEMIA');
                $pdf->AddPage();

                $pdf->SetFillColor(241, 245, 249);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(15, 8, 'Pos.', 1, 0, 'C', true);
                $pdf->Cell(95, 8, 'Academia / Dojo', 1, 0, 'L', true);
                $pdf->Cell(25, 8, 'Ouro', 1, 0, 'C', true);
                $pdf->Cell(25, 8, 'Prata', 1, 0, 'C', true);
                $pdf->Cell(25, 8, 'Bronze', 1, 1, 'C', true);

                $pdf->SetFont('Arial', '', 9.5);
                $pos = 1;
                foreach ($tabela as $row) {
                    $pdf->Cell(15, 7, $pos++ . 'º', 1, 0, 'C');
                    $pdf->Cell(95, 7, $pdf->conv($row['dojo_snapshot']), 1, 0, 'L');
                    $pdf->Cell(25, 7, $row['ouro'], 1, 0, 'C');
                    $pdf->Cell(25, 7, $row['prata'], 1, 0, 'C');
                    $pdf->Cell(25, 7, $row['bronze'], 1, 1, 'C');
                }
                break;

            default:
                echo "Tipo de relatório inválido.";
                exit;
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="relatorio_' . $tipo . '.pdf"');
        echo $pdf->Output('S');
        exit;
    }
}
