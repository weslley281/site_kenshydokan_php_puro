<?php
// controllers/imprimir_recibo.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Proteção da página
if (!isset($_SESSION["id_usuario"]) || $_SESSION['nivel'] != "admin") {
    die("Acesso negado. Apenas administradores podem gerar recibos.");
}

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

// Mapeando mês para português
$meses = [
    '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
    '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
    '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
];
$data_extensa = date("d") . " de " . $meses[date("m")] . " de " . date("Y");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Mensalidade - <?php echo htmlspecialchars($f->getNome()); ?></title>
    <style>
        /* CSS reset & styles for printing A5 Landscape */
        @page {
            size: A5 landscape;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 10px;
            font-family: Arial, sans-serif;
            background: #fff;
            color: #333;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .recibo-box {
            position: relative;
            width: 196mm;
            height: 134mm;
            border: 0.8mm solid #d9232d; /* Vermelho principal */
            box-sizing: border-box;
            padding: 10px;
            margin: 0 auto;
        }
        .recibo-inner-border {
            position: absolute;
            top: 2mm;
            left: 2mm;
            right: 2mm;
            bottom: 2mm;
            border: 0.2mm solid #323238; /* Cinza escuro */
            box-sizing: border-box;
            padding: 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 0.5mm solid #d9232d;
            padding-bottom: 8px;
        }
        .header-logo-info {
            display: flex;
            align-items: center;
        }
        .logo {
            width: 20mm;
            height: 20mm;
            object-fit: contain;
            margin-right: 15px;
        }
        .logo-text h1 {
            font-size: 12pt;
            font-weight: bold;
            color: #d9232d;
            margin: 0 0 3px 0;
        }
        .logo-text h2 {
            font-size: 9pt;
            font-weight: bold;
            color: #323238;
            margin: 0 0 3px 0;
        }
        .logo-text p {
            font-size: 7.5pt;
            color: #646464;
            margin: 0;
        }
        .valor-box {
            background-color: #f5f5f5;
            border: 0.2mm solid #dcdcdc;
            padding: 8px 15px;
            text-align: center;
            min-width: 45mm;
            border-radius: 4px;
        }
        .valor-title {
            font-size: 8pt;
            font-weight: bold;
            color: #646464;
            margin-bottom: 3px;
        }
        .valor-num {
            font-size: 14pt;
            font-weight: bold;
            color: #28a745; /* Verde */
        }
        .title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        .title-row h3 {
            font-size: 14pt;
            font-weight: bold;
            color: #1e1e1e;
            margin: 0;
        }
        .title-row .recibo-num {
            font-size: 9pt;
            font-weight: bold;
            color: #646464;
        }
        .declaracao {
            font-size: 10pt;
            line-height: 1.5;
            color: #323238;
            margin-top: 10px;
            text-align: justify;
        }
        .tabela-info {
            background-color: #f8f9fa;
            border-radius: 4px;
            padding: 8px 12px;
            margin-top: 10px;
        }
        .tabela-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr 1.2fr 1fr 1fr;
            gap: 10px;
        }
        .tabela-header {
            font-size: 8pt;
            font-weight: bold;
            color: #787878;
            text-transform: uppercase;
        }
        .tabela-value {
            font-size: 9.5pt;
            font-weight: bold;
            color: #282828;
            margin-top: 2px;
            text-transform: capitalize;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .tabela-nota {
            font-size: 7.5pt;
            font-style: italic;
            color: #6e6e6e;
            margin-top: 6px;
        }
        .footer-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 15px;
        }
        .data-extensa {
            font-size: 9.5pt;
            color: #323238;
        }
        .assinatura-container {
            text-align: center;
            width: 75mm;
        }
        .assinatura-linha {
            border-top: 0.3mm solid #969696;
            margin-bottom: 4px;
        }
        .assinatura-cargo {
            font-size: 7.5pt;
            font-weight: bold;
            color: #646464;
            text-transform: uppercase;
        }
        .assinatura-sub {
            font-size: 7.5pt;
            color: #646464;
        }
        .rodape-institucional {
            text-align: center;
            font-size: 7pt;
            font-weight: bold;
            color: #a0a0a0;
            margin-top: 8px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="recibo-box">
        <div class="recibo-inner-border">
            
            <!-- Cabeçalho -->
            <div class="header">
                <div class="header-logo-info">
                    <img class="logo" src="../img/wkka.jpg" alt="Logo WKKA">
                    <div class="logo-text">
                        <h1>WORLD KENSHYDOKAN KARATE ASSOCIATION</h1>
                        <h2>Instituto de Artes Marciais e Defesa Pessoal Kenshydokan</h2>
                        <p>-----------------------------------------</p>
                    </div>
                </div>
                <div class="valor-box">
                    <div class="valor-title">VALOR RECEBIDO</div>
                    <div class="valor-num">R$ <?php echo number_format($m['valor'], 2, ',', '.'); ?></div>
                </div>
            </div>

            <!-- Título e Número -->
            <div class="title-row">
                <h3>RECIBO DE MENSALIDADE</h3>
                <div class="recibo-num">RECIBO N° <?php echo str_pad($m['id'], 6, '0', STR_PAD_LEFT); ?></div>
            </div>

            <!-- Declaração -->
            <div class="declaracao">
                Declaramos que recebemos de <strong><?php echo htmlspecialchars($f->getNome()); ?></strong>, com CPF/Filiado registrado no sistema, a importância de R$ <?php echo number_format($m['valor'], 2, ',', '.'); ?> referente ao pagamento da mensalidade do dojô, correspondente ao mês de referência <?php echo date("m/Y", strtotime($m['referencia'] . '-01')); ?>.
            </div>

            <!-- Tabela de Detalhes -->
            <div class="tabela-info">
                <div class="tabela-grid">
                    <div>
                        <div class="tabela-header">Aluno</div>
                        <div class="tabela-value" title="<?php echo htmlspecialchars($f->getNome()); ?>">
                            <?php echo htmlspecialchars(explode(' ', trim($f->getNome()))[0]); ?>
                        </div>
                    </div>
                    <div>
                        <div class="tabela-header">Graduação</div>
                        <div class="tabela-value"><?php echo htmlspecialchars($grad_nome); ?></div>
                    </div>
                    <div>
                        <div class="tabela-header">Dojô</div>
                        <div class="tabela-value" title="<?php echo htmlspecialchars($f->getDojo()); ?>">
                            <?php echo htmlspecialchars($f->getDojo()); ?>
                        </div>
                    </div>
                    <div>
                        <div class="tabela-header">Vencimento</div>
                        <div class="tabela-value"><?php echo date("d/m/Y", strtotime($m['data_vencimento'])); ?></div>
                    </div>
                    <div>
                        <div class="tabela-header">Data Pagamento</div>
                        <div class="tabela-value"><?php echo date("d/m/Y", strtotime($m['data_pagamento'])); ?></div>
                    </div>
                </div>
                <div class="tabela-nota">
                    *Este documento serve como comprovante de quitação da referência acima citada.
                </div>
            </div>

            <!-- Data e Assinatura -->
            <div class="footer-row">
                <div class="data-extensa">
                    Cuiabá - MT, <?php echo $data_extensa; ?>.
                </div>
                <div class="assinatura-container">
                    <div class="assinatura-linha"></div>
                    <div class="assinatura-cargo">ASSINATURA DO INSTRUTOR</div>
                    <div class="assinatura-sub">Sensei / Representante WKKA</div>
                </div>
            </div>

            <!-- Rodapé Institucional -->
            <div class="rodape-institucional">
                WORLD KENSHYDOKAN KARATE ASSOCIATION - WKKA
            </div>

        </div>
    </div>

    <!-- Script de Impressão e Auto-Fechamento -->
    <script>
        window.onload = function() {
            window.print();
            if ('onafterprint' in window) {
                window.onafterprint = function() {
                    window.close();
                };
            } else {
                // Fallback para navegadores sem onafterprint
                setTimeout(function() {
                    window.close();
                }, 1000);
            }
        };
    </script>
</body>
</html>
