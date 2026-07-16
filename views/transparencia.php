<?php
// views/transparencia.php
$pageTitle = "Portal da Transparência | Prestação de Contas Kenshydokan";
$pageDescription = "Acompanhe publicamente os demonstrativos operacionais, receitas e despesas do Instituto Kenshydokan, promovendo ética e transparência.";
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/dojoFinanceiroModel.php";

$financeiroModel = new DojoFinanceiroModel();

// Obter filtros de entrada
$ano_atual = date("Y");
$ano_selecionado = isset($_GET['ano']) ? intval($_GET['ano']) : intval($ano_atual);

$mes_atual = date("Y-m");
$mes_selecionado = isset($_GET['mes']) ? $_GET['mes'] : $mes_atual;

// Se o mês selecionado não pertencer ao ano selecionado, ajusta o mês
if (substr($mes_selecionado, 0, 4) != $ano_selecionado) {
    $mes_selecionado = $ano_selecionado . '-' . date("m");
}

// Buscar dados no banco (excluindo mensalidades de alunos no portal público)
$resumo_mes = $financeiroModel->buscarResumoFinanceiroMes($mes_selecionado, true);
$movimentacoes = $financeiroModel->buscarMovimentacoesMes($mes_selecionado, true);
$resumo_anual = $financeiroModel->buscarResumoAnual($ano_selecionado, true);

// Calcular totais anuais
$total_entradas_ano = 0.00;
$total_saidas_ano = 0.00;
$dados_meses_preenchidos = [];

// Mapeia meses do ano
$nomes_meses = [
    '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
    '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
    '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
];

// Inicializa array para todos os 12 meses do ano selecionado
for ($m = 1; $m <= 12; $m++) {
    $mes_pad = str_pad($m, 2, '0', STR_PAD_LEFT);
    $chave_mes = $ano_selecionado . '-' . $mes_pad;
    $dados_meses_preenchidos[$chave_mes] = [
        'nome' => $nomes_meses[$mes_pad],
        'entradas' => 0.00,
        'saidas' => 0.00,
        'saldo' => 0.00
    ];
}

// Preenche com os dados reais retornados do banco
foreach ($resumo_anual as $r) {
    $chave = $r['mes_ref'];
    if (isset($dados_meses_preenchidos[$chave])) {
        $entradas = floatval($r['entradas']);
        $saidas = floatval($r['saidas']);
        $dados_meses_preenchidos[$chave]['entradas'] = $entradas;
        $dados_meses_preenchidos[$chave]['saidas'] = $saidas;
        $dados_meses_preenchidos[$chave]['saldo'] = $entradas - $saidas;

        $total_entradas_ano += $entradas;
        $total_saidas_ano += $saidas;
    }
}
$saldo_ano = $total_entradas_ano - $total_saidas_ano;

// Determina qual aba exibir por padrão (mensal ou anual)
$aba_ativa = isset($_GET['aba']) ? $_GET['aba'] : 'mensal';
?>
<!-- Custom Style Overrides for Responsiveness -->
<style>
.custom-nav-pills {
    border-radius: 50rem;
}
@media (max-width: 575.98px) {
    .custom-nav-pills {
        border-radius: 16px !important;
        flex-direction: column;
    }
    .custom-nav-pills .nav-item {
        width: 100%;
    }
    .custom-nav-pills .nav-link {
        border-radius: 10px !important;
    }
}
</style>

<div class="container my-5">
    <!-- Banner de Apresentação com Gradiente Kenshydokan -->
    <div class="card border-0 shadow-lg mb-4 text-white rounded-lg overflow-hidden" style="background: linear-gradient(135deg, #1e1e24 0%, #a8201a 100%);">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex flex-column flex-sm-row align-items-center text-center text-sm-left mb-3">
                <div class="bg-light rounded-circle p-2 mb-3 mb-sm-0 mr-0 mr-sm-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; min-width: 60px; box-shadow: 0 4px 15px rgba(0,0,0,0.25);">
                    <img src="../img/wkka.jpg" width="45" height="45" alt="logo kenshydokan" class="rounded-circle">
                </div>
                <h1 class="font-weight-bold mb-0" style="letter-spacing: 0.5px; font-size: calc(1.6rem + 1.2vw);">Portal da Transparência</h1>
            </div>
            <h5 class="text-white-50 font-weight-normal mb-3 text-center text-sm-left" style="font-size: calc(0.95rem + 0.2vw);">Demonstrativo Operacional e Responsabilidade Financeira — Kenshydokan</h5>
            <p class="mb-0 text-white-50 text-center text-sm-left" style="max-width: 800px; line-height: 1.6; font-size: calc(0.85rem + 0.15vw);">
                Acompanhe publicamente as receitas, despesas e a saúde financeira do dojô principal Kenshydokan. Este canal visa demonstrar o compromisso ético, a prestação de contas aos filiados e a sustentabilidade operacional da nossa instituição.
            </p>
        </div>
    </div>

    <!-- Navegação por Abas (Pills Premium) -->
    <ul class="nav nav-pills nav-fill mb-4 p-1 bg-white shadow-sm border custom-nav-pills" style="gap: 5px;">
        <li class="nav-item">
            <a class="nav-link rounded-pill font-weight-bold <?php echo ($aba_ativa == 'mensal') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="?aba=mensal&mes=<?php echo $mes_selecionado; ?>&ano=<?php echo $ano_selecionado; ?>">
                <i class="fa-solid fa-calendar-days mr-2"></i>Demonstrativo Mensal
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill font-weight-bold <?php echo ($aba_ativa == 'anual') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="?aba=anual&mes=<?php echo $mes_selecionado; ?>&ano=<?php echo $ano_selecionado; ?>">
                <i class="fa-solid fa-chart-line mr-2"></i>Demonstrativo Anual
            </a>
        </li>
    </ul>

    <!-- Conteúdo da Aba Mensal -->
    <?php if ($aba_ativa == 'mensal'): ?>
        <!-- Cartões de Resumo Financeiro Mensal -->
        <div class="row mb-4">
            <!-- Entradas -->
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm rounded-lg bg-white h-100 border-left border-success" style="border-left-width: 5px !important; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small font-weight-bold text-uppercase mb-1 d-block">Receitas (Entradas)</span>
                            <h3 class="font-weight-bold text-success mb-0">R$ <?php echo number_format($resumo_mes['entradas'], 2, ',', '.'); ?></h3>
                            <small class="text-muted">Período: <?php echo date("m/Y", strtotime($mes_selecionado . '-01')); ?></small>
                        </div>
                        <i class="fa-solid fa-circle-arrow-down text-success" style="font-size: 2.2rem; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>

            <!-- Saídas -->
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm rounded-lg bg-white h-100 border-left border-danger" style="border-left-width: 5px !important; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small font-weight-bold text-uppercase mb-1 d-block">Despesas (Saídas)</span>
                            <h3 class="font-weight-bold text-danger mb-0">R$ <?php echo number_format($resumo_mes['saidas'], 2, ',', '.'); ?></h3>
                            <small class="text-muted">Período: <?php echo date("m/Y", strtotime($mes_selecionado . '-01')); ?></small>
                        </div>
                        <i class="fa-solid fa-circle-arrow-up text-danger" style="font-size: 2.2rem; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>

            <!-- Saldo -->
            <div class="col-md-4 mb-3">
                <?php $saldo_positivo = $resumo_mes['saldo'] >= 0; ?>
                <div class="card border-0 shadow-sm rounded-lg bg-white h-100 border-left border-<?php echo $saldo_positivo ? 'primary' : 'warning'; ?>" style="border-left-width: 5px !important; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small font-weight-bold text-uppercase mb-1 d-block">Saldo Líquido</span>
                            <h3 class="font-weight-bold text-<?php echo $saldo_positivo ? 'primary' : 'warning'; ?> mb-0">R$ <?php echo number_format($resumo_mes['saldo'], 2, ',', '.'); ?></h3>
                            <small class="text-muted">Período: <?php echo date("m/Y", strtotime($mes_selecionado . '-01')); ?></small>
                        </div>
                        <i class="fa-solid fa-scale-balanced text-<?php echo $saldo_positivo ? 'primary' : 'warning'; ?>" style="font-size: 2.2rem; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proporção do Fluxo -->
        <?php
        $total_fluxo = $resumo_mes['entradas'] + $resumo_mes['saidas'];
        $perc_entradas = $total_fluxo > 0 ? ($resumo_mes['entradas'] / $total_fluxo) * 100 : 0;
        $perc_saidas = $total_fluxo > 0 ? ($resumo_mes['saidas'] / $total_fluxo) * 100 : 0;
        ?>
        <div class="card border-0 shadow-sm rounded-lg bg-white mb-4">
            <div class="card-body p-4">
                <h6 class="font-weight-bold text-dark mb-3"><i class="fa-solid fa-chart-pie mr-2 text-danger"></i>Proporção Operacional (Entradas vs Saídas)</h6>
                <?php if ($total_fluxo > 0): ?>
                    <div class="progress rounded-pill shadow-inner" style="height: 24px;">
                        <div class="progress-bar bg-success font-weight-bold text-uppercase d-flex align-items-center justify-content-center" role="progressbar" style="width: <?php echo $perc_entradas; ?>%;" aria-valuenow="<?php echo $perc_entradas; ?>" aria-valuemin="0" aria-valuemax="100">
                            <?php echo number_format($perc_entradas, 1); ?>% Receitas
                        </div>
                        <div class="progress-bar bg-danger font-weight-bold text-uppercase d-flex align-items-center justify-content-center" role="progressbar" style="width: <?php echo $perc_saidas; ?>%;" aria-valuenow="<?php echo $perc_saidas; ?>" aria-valuemin="0" aria-valuemax="100">
                            <?php echo number_format($perc_saidas, 1); ?>% Despesas
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0 small"><i class="fa-solid fa-circle-info mr-2"></i>Nenhuma movimentação registrada no mês selecionado.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Filtro e Tabela Detalhada -->
        <div class="card border-0 shadow-sm rounded-lg bg-white">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-4" style="gap: 15px;">
                    <h5 class="text-danger font-weight-bold mb-0">
                        <i class="fa-solid fa-list-check mr-2"></i>Detalhamento do Livro Caixa
                    </h5>
                    
                    <!-- Seletor de Mês -->
                    <form method="get" action="" class="form-inline bg-light p-2 rounded-pill border">
                        <input type="hidden" name="aba" value="mensal">
                        <label for="mes" class="mr-2 ml-1 text-secondary small font-weight-bold text-uppercase">Mês de Referência:</label>
                        <input type="month" id="mes" name="mes" value="<?php echo htmlspecialchars($mes_selecionado); ?>" class="form-control form-control-sm bg-white border-0 mr-2 rounded-pill px-3" onchange="this.form.submit()">
                    </form>
                </div>

                <div class="table-responsive">
                    <table id="tabelaTransparencia" class="table table-hover align-middle" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-secondary small font-weight-bold border-bottom">
                                <th scope="col">Data</th>
                                <th scope="col">Descrição</th>
                                <th scope="col">Categoria</th>
                                <th scope="col" class="text-center">Tipo</th>
                                <th scope="col" class="text-right">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($movimentacoes)): ?>
                                <?php foreach ($movimentacoes as $mov): ?>
                                    <tr>
                                        <td class="align-middle small font-weight-bold text-secondary">
                                            <?php echo date("d/m/Y", strtotime($mov['data_movimentacao'])); ?>
                                        </td>
                                        <td class="align-middle font-weight-bold text-dark text-capitalize">
                                            <?php echo htmlspecialchars($mov['descricao']); ?>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge badge-light text-secondary border px-2 py-1 small text-uppercase">
                                                <?php echo htmlspecialchars($mov['categoria']); ?>
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <?php if ($mov['tipo'] == 'entrada'): ?>
                                                <span class="badge badge-success text-uppercase px-2 py-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Receita</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger text-uppercase px-2 py-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Despesa</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle text-right font-weight-bold <?php echo $mov['tipo'] == 'entrada' ? 'text-success' : 'text-danger'; ?>">
                                            <?php echo ($mov['tipo'] == 'entrada' ? '+ ' : '- ') . 'R$ ' . number_format($mov['valor'], 2, ',', '.'); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <!-- Conteúdo da Aba Anual -->
    <?php else: ?>
        <!-- Cartões de Resumo Financeiro Anual -->
        <div class="row mb-4">
            <!-- Entradas Anuais -->
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm rounded-lg bg-white h-100 border-left border-success" style="border-left-width: 5px !important; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small font-weight-bold text-uppercase mb-1 d-block">Receitas Acumuladas</span>
                            <h3 class="font-weight-bold text-success mb-0">R$ <?php echo number_format($total_entradas_ano, 2, ',', '.'); ?></h3>
                            <small class="text-muted">Ano de Exercício: <?php echo $ano_selecionado; ?></small>
                        </div>
                        <i class="fa-solid fa-chart-line text-success" style="font-size: 2.2rem; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>

            <!-- Saídas Anuais -->
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm rounded-lg bg-white h-100 border-left border-danger" style="border-left-width: 5px !important; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small font-weight-bold text-uppercase mb-1 d-block">Despesas Acumuladas</span>
                            <h3 class="font-weight-bold text-danger mb-0">R$ <?php echo number_format($total_saidas_ano, 2, ',', '.'); ?></h3>
                            <small class="text-muted">Ano de Exercício: <?php echo $ano_selecionado; ?></small>
                        </div>
                        <i class="fa-solid fa-chart-bar text-danger" style="font-size: 2.2rem; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>

            <!-- Saldo Anual -->
            <div class="col-md-4 mb-3">
                <?php $saldo_anual_positivo = $saldo_ano >= 0; ?>
                <div class="card border-0 shadow-sm rounded-lg bg-white h-100 border-left border-<?php echo $saldo_anual_positivo ? 'primary' : 'warning'; ?>" style="border-left-width: 5px !important; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small font-weight-bold text-uppercase mb-1 d-block">Superávit / Déficit Anual</span>
                            <h3 class="font-weight-bold text-<?php echo $saldo_anual_positivo ? 'primary' : 'warning'; ?> mb-0">R$ <?php echo number_format($saldo_ano, 2, ',', '.'); ?></h3>
                            <small class="text-muted">Ano de Exercício: <?php echo $ano_selecionado; ?></small>
                        </div>
                        <i class="fa-solid fa-scale-balanced text-<?php echo $saldo_anual_positivo ? 'primary' : 'warning'; ?>" style="font-size: 2.2rem; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela do Resumo Mensal do Ano -->
        <div class="card border-0 shadow-sm rounded-lg bg-white">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-4" style="gap: 15px;">
                    <h5 class="text-danger font-weight-bold mb-0">
                        <i class="fa-solid fa-chart-column mr-2"></i>Fechamento Mensal (Ano: <?php echo $ano_selecionado; ?>)
                    </h5>
                    
                    <!-- Seletor de Ano -->
                    <form method="get" action="" class="form-inline bg-light p-2 rounded-pill border">
                        <input type="hidden" name="aba" value="anual">
                        <label for="ano" class="mr-2 ml-1 text-secondary small font-weight-bold text-uppercase">Exercício:</label>
                        <select id="ano" name="ano" class="form-control form-control-sm bg-white border-0 mr-2 rounded-pill px-3" onchange="this.form.submit()">
                            <?php 
                            for ($y = intval($ano_atual) - 2; $y <= intval($ano_atual) + 1; $y++): 
                                $selected = ($y == $ano_selecionado) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $y; ?>" <?php echo $selected; ?>><?php echo $y; ?></option>
                            <?php endfor; ?>
                        </select>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-secondary small font-weight-bold border-bottom">
                                <th scope="col">Mês</th>
                                <th scope="col" class="text-right">Receitas</th>
                                <th scope="col" class="text-right">Despesas</th>
                                <th scope="col" class="text-right">Saldo do Mês</th>
                                <th scope="col" class="text-center" style="width: 150px;">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dados_meses_preenchidos as $chave_mes => $info): ?>
                                <tr>
                                    <td class="align-middle font-weight-bold text-dark">
                                        <?php echo $info['nome']; ?>
                                    </td>
                                    <td class="align-middle text-right text-success font-weight-bold">
                                        R$ <?php echo number_format($info['entradas'], 2, ',', '.'); ?>
                                    </td>
                                    <td class="align-middle text-right text-danger font-weight-bold">
                                        R$ <?php echo number_format($info['saidas'], 2, ',', '.'); ?>
                                    </td>
                                    <td class="align-middle text-right font-weight-bold <?php echo $info['saldo'] >= 0 ? 'text-success' : 'text-danger'; ?>">
                                        R$ <?php echo number_format($info['saldo'], 2, ',', '.'); ?>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="?aba=mensal&mes=<?php echo $chave_mes; ?>&ano=<?php echo $ano_selecionado; ?>" class="btn btn-xs btn-outline-danger px-3 py-1 rounded-pill small font-weight-bold border-0" style="font-size: 0.75rem;">
                                            <i class="fa-solid fa-magnifying-glass mr-1"></i>Ver Detalhes
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal / Scripts Específicos para a Tabela com DataTables -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Configura o DataTable na tabela de detalhamento mensal
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#tabelaTransparencia').DataTable({
            "order": [[0, "desc"]], // Ordena por data decrescente
            "pageLength": 10,
            "searching": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
            }
        });
    }
});
</script>

<?php
include_once __DIR__ . "/rodape.php";
?>
