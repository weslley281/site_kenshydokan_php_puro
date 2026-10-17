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
include_once __DIR__ . "/../models/sumulaKumiteModel.php";
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

    public function resumoPenalidades(array $sumula, string $lado): string {
        $penalidades = [];
        foreach ([
            'shido' => 'Shido',
            'chui' => 'Chui',
            'saidas' => 'Saídas'
        ] as $campo => $rotulo) {
            $quantidade = (int)($sumula[$campo . '_' . $lado] ?? 0);
            if ($quantidade > 0) {
                $penalidades[] = $rotulo . ': ' . $quantidade;
            }
        }

        if (!empty($sumula['hansoku_make_' . $lado])) {
            $penalidades[] = 'Desclassificado';
        }

        return $penalidades ? implode(' | ', $penalidades) : 'Nenhuma';
    }

    public function resumoTemporal(array $eventos, string $lado): string {
        $pontos = [];
        $penalidades = [];

        foreach ($eventos as $evento) {
            $segundo = max(0, (int)($evento['segundo_luta'] ?? 0));
            $tempo = sprintf('%02d:%02d', intdiv($segundo, 60), $segundo % 60);
            $descricao = (string)($evento['descricao'] ?? '');
            $tipo = $evento['tipo_evento'] ?? '';

            if ($tipo === 'ponto' && ($evento['lado'] ?? '') === $lado) {
                if (stripos($descricao, 'waza-ari') !== false) {
                    $pontos[] = $tempo . ' Waza-ari (+0,5)';
                } elseif (stripos($descricao, 'ippon') !== false) {
                    $pontos[] = $tempo . ' Ippon (+1,0)';
                } else {
                    $pontos[] = $tempo . ' ' . $descricao;
                }
            } elseif ($tipo === 'penalidade') {
                if (($evento['lado'] ?? '') === $lado) {
                    if (stripos($descricao, 'shido') !== false) {
                        $penalidades[] = $tempo . ' Shido';
                    } elseif (stripos($descricao, 'chui') !== false) {
                        $penalidades[] = $tempo . ' Chui';
                    } elseif (stripos($descricao, 'saída') !== false) {
                        $penalidades[] = $tempo . ' Saída';
                    } elseif (stripos($descricao, 'desclassificação') !== false) {
                        $penalidades[] = $tempo . ' Desclassificação';
                    } else {
                        $penalidades[] = $tempo . ' ' . $descricao;
                    }
                }

                $ladoPenalizado = $evento['lado'] ?? '';
                $ladoBeneficiado = $ladoPenalizado === 'aka' ? 'shiro' : 'aka';
                if ((float)($evento['pontos_atribuidos'] ?? 0) > 0 && $ladoBeneficiado === $lado) {
                    $oponente = stripos($descricao, 'chui') !== false
                        ? 'Ponto por Chui (+0,5)'
                        : 'Ponto por saída (+0,5)';
                    $pontos[] = $tempo . ' ' . $oponente;
                }
            }
        }

        return 'Pontos: ' . ($pontos ? implode('; ', $pontos) : 'nenhum')
            . ' | Penalidades: ' . ($penalidades ? implode('; ', $penalidades) : 'nenhuma');
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
    $sumulaModel = new SumulaKumite();

    $pdf = new KenshyPDF('L', 'mm', 'A4');
    $pdf->AliasNbPages();
    $pdf->setEventInfo($campeonato['titulo'], 'CHAVE ELIMINATÓRIA: ' . strtoupper($categoria['nome']));
    $pdf->AddPage();

    $desenharCabecalhoLutas = static function () use ($pdf): void {
        $pdf->SetFillColor(241, 245, 249);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(17, 24, 39);
        $pdf->Cell(19, 8, 'Luta #', 1, 0, 'C', true);
        $pdf->Cell(23, 8, 'Rodada', 1, 0, 'C', true);
        $pdf->Cell(105, 8, $pdf->conv('Atleta'), 1, 0, 'L', true);
        $pdf->Cell(20, 8, 'Pontos', 1, 0, 'C', true);
        $pdf->Cell(70, 8, $pdf->conv('Penalidades'), 1, 0, 'L', true);
        $pdf->Cell(40, 8, 'Resultado', 1, 1, 'C', true);
        $pdf->SetFont('Arial', '', 8);
    };
    $desenharCabecalhoLutas();

    foreach ($lutas as $l) {
        $sumula = $sumulaModel->buscarPorLuta((int)$l['id_luta']) ?? [];
        $eventos = $sumula ? $sumulaModel->buscarEventos((int)$l['id_luta']) : [];
        $lados = [
            'aka' => ['rotulo' => 'AKA', 'nome' => $l['nome_aka'] ?? 'Aguardando'],
            'shiro' => ['rotulo' => 'SHIRO', 'nome' => $l['nome_shiro'] ?? 'Aguardando']
        ];

        foreach ($lados as $lado => $atleta) {
            $linhaTemporal = $pdf->conv($pdf->resumoTemporal($eventos, $lado));
            $alturaTemporal = max(6, (int)ceil($pdf->GetStringWidth($linhaTemporal) / 270) * 6);
            if ($pdf->GetY() + 7 + $alturaTemporal > 185) {
                $pdf->AddPage();
                $desenharCabecalhoLutas();
            }

            $idInscricao = $lado === 'aka' ? ($l['id_inscricao_aka'] ?? 0) : ($l['id_inscricao_shiro'] ?? 0);
            $vencedor = !empty($l['id_vencedor']) && (int)$l['id_vencedor'] === (int)$idInscricao;
            $pdf->Cell(19, 7, $lado === 'aka' ? '#' . $l['numero_luta'] : '', 1, 0, 'C');
            $pdf->Cell(23, 7, $lado === 'aka' ? $l['rodada'] . 'a' : '', 1, 0, 'C');
            $pdf->Cell(105, 7, $pdf->conv($atleta['rotulo'] . ' - ' . substr($atleta['nome'], 0, 38)), 1, 0, 'L');
            $pdf->Cell(20, 7, number_format((float)($sumula['pontos_' . $lado] ?? 0), 1, ',', '.'), 1, 0, 'C');
            $pdf->Cell(70, 7, $pdf->conv($pdf->resumoPenalidades($sumula, $lado)), 1, 0, 'L');
            $pdf->Cell(38, 7, $pdf->conv($vencedor ? 'Vencedor' : '-'), 1, 1, 'C');

            $pdf->SetFont('Arial', 'I', 7.5);
            $pdf->MultiCell(277, 6, $linhaTemporal, 1, 'L');
            $pdf->SetFont('Arial', '', 8);
        }
    }

    $pdf->Output('I', 'chave_' . $idCategoria . '.pdf');
    exit;
}
