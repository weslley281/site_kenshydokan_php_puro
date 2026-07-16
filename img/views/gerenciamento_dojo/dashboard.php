<?php
// views/gerenciamento_dojo/dashboard.php
$total_entradas = $resumo_financeiro['entradas'];
$total_saidas = $resumo_financeiro['saidas'];
$saldo = $resumo_financeiro['saldo'];
?>

<div class="row">
  <!-- Cartões Financeiros -->
  <div class="col-md-4 mb-4">
    <div class="card border-0 shadow-sm rounded-lg bg-white h-100">
      <div class="card-body p-4 d-flex align-items-center">
        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mr-3" style="width: 55px; height: 55px; font-size: 1.5rem;">
          <i class="fa-solid fa-arrow-trend-up"></i>
        </div>
        <div>
          <p class="text-secondary small font-weight-bold text-uppercase mb-1">Receitas (Entradas)</p>
          <h3 class="font-weight-bold text-success mb-0">R$ <?php echo number_format($total_entradas, 2, ',', '.'); ?></h3>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4 mb-4">
    <div class="card border-0 shadow-sm rounded-lg bg-white h-100">
      <div class="card-body p-4 d-flex align-items-center">
        <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center mr-3" style="width: 55px; height: 55px; font-size: 1.5rem;">
          <i class="fa-solid fa-arrow-trend-down"></i>
        </div>
        <div>
          <p class="text-secondary small font-weight-bold text-uppercase mb-1">Despesas (Saídas)</p>
          <h3 class="font-weight-bold text-danger mb-0">R$ <?php echo number_format($total_saidas, 2, ',', '.'); ?></h3>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4 mb-4">
    <div class="card border-0 shadow-sm rounded-lg bg-white h-100">
      <div class="card-body p-4 d-flex align-items-center">
        <div class="rounded-circle bg-<?php echo $saldo >= 0 ? 'primary' : 'danger'; ?> text-white d-flex align-items-center justify-content-center mr-3" style="width: 55px; height: 55px; font-size: 1.5rem;">
          <i class="fa-solid fa-scale-balanced"></i>
        </div>
        <div>
          <p class="text-secondary small font-weight-bold text-uppercase mb-1">Saldo Líquido</p>
          <h3 class="font-weight-bold text-<?php echo $saldo >= 0 ? 'primary' : 'danger'; ?> mb-0">R$ <?php echo number_format($saldo, 2, ',', '.'); ?></h3>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Cartões de Status de Alunos -->
  <div class="col-md-4 mb-4">
    <div class="card border-0 shadow-sm rounded-lg bg-white h-100 border-left border-success" style="border-left-width: 4px !important;">
      <div class="card-body p-4 d-flex align-items-center justify-content-between">
        <div>
          <p class="text-secondary small font-weight-bold text-uppercase mb-1">Alunos Adimplentes</p>
          <h2 class="font-weight-bold text-success mb-0"><?php echo $counts_alunos['adimplente']; ?></h2>
        </div>
        <i class="fa-solid fa-user-check text-success-50" style="font-size: 2.5rem; opacity: 0.15;"></i>
      </div>
    </div>
  </div>

  <div class="col-md-4 mb-4">
    <div class="card border-0 shadow-sm rounded-lg bg-white h-100 border-left border-danger" style="border-left-width: 4px !important;">
      <div class="card-body p-4 d-flex align-items-center justify-content-between">
        <div>
          <p class="text-secondary small font-weight-bold text-uppercase mb-1">Alunos Inadimplentes</p>
          <h2 class="font-weight-bold text-danger mb-0"><?php echo $counts_alunos['inadimplente']; ?></h2>
        </div>
        <i class="fa-solid fa-user-xmark text-danger-50" style="font-size: 2.5rem; opacity: 0.15;"></i>
      </div>
    </div>
  </div>

  <div class="col-md-4 mb-4">
    <div class="card border-0 shadow-sm rounded-lg bg-white h-100 border-left border-warning" style="border-left-width: 4px !important;">
      <div class="card-body p-4 d-flex align-items-center justify-content-between">
        <div>
          <p class="text-secondary small font-weight-bold text-uppercase mb-1">Alunos Pausados</p>
          <h2 class="font-weight-bold text-warning mb-0"><?php echo $counts_alunos['pausado']; ?></h2>
        </div>
        <i class="fa-solid fa-user-slash text-warning-50" style="font-size: 2.5rem; opacity: 0.15;"></i>
      </div>
    </div>
  </div>
</div>

<!-- Card Informativo/Tutorial -->
<div class="card border-0 shadow-sm rounded-lg mb-4">
  <div class="card-body p-4">
    <h5 class="text-danger font-weight-bold mb-3"><i class="fa-solid fa-circle-question mr-2"></i>Como Funciona a Gestão do Dojô?</h5>
    <p class="text-muted leading-relaxed">
      Bem-vindo ao Painel de Controle de Gestão Kenshydokan. Aqui você pode gerenciar todos os aspectos operacionais do dojô de forma integrada:
    </p>
    <div class="row mt-4">
      <div class="col-md-4 mb-3">
        <h6 class="font-weight-bold text-dark"><i class="fa-solid fa-sliders text-danger mr-2"></i>1. Config. Alunos</h6>
        <p class="text-muted small">Defina o valor da mensalidade e dia de vencimento customizado de cada filiado confirmado. Você também pode pausar filiados temporariamente.</p>
      </div>
      <div class="col-md-4 mb-3">
        <h6 class="font-weight-bold text-dark"><i class="fa-solid fa-list-check text-danger mr-2"></i>2. Mensalidades</h6>
        <p class="text-muted small">Gere faturas mensais em lote para todos os alunos ativos e dê baixa nos pagamentos recebidos com apenas um clique.</p>
      </div>
      <div class="col-md-4 mb-3">
        <h6 class="font-weight-bold text-dark"><i class="fa-solid fa-book text-danger mr-2"></i>3. Livro Caixa</h6>
        <p class="text-muted small">Gerencie as receitas avulsas e as despesas operacionais do dojô (como aluguel, energia e tatames) para acompanhar o saldo líquido.</p>
      </div>
    </div>
  </div>
</div>
