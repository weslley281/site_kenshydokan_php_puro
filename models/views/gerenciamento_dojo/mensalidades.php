<?php
// views/gerenciamento_dojo/mensalidades.php
$mensalidades = $mensalidadeModel->buscarMensalidadesReferencia($referencia);
?>

<div class="card border-0 shadow-sm rounded-lg mb-4">
  <div class="card-body p-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
      <div>
        <h5 class="text-danger font-weight-bold mb-1"><i class="fa-solid fa-sack-dollar mr-2"></i>Controle de Mensalidades</h5>
        <p class="text-muted small mb-0">Gerenciamento de cobranças de <strong><?php echo date("m/Y", strtotime($referencia . '-01')); ?></strong>.</p>
      </div>
      <div class="mt-2 mt-md-0">
        <!-- Botão para Gerar Mensalidades em Lote -->
        <form action="../../controllers/dojoGerenciamentoController.php" method="post" class="d-inline">
          <input type="hidden" name="tipo" value="lancar_mensalidades_mes">
          <input type="hidden" name="referencia" value="<?php echo htmlspecialchars($referencia); ?>">
          <button type="submit" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-4 py-2">
            <i class="fa-solid fa-file-invoice-dollar mr-2"></i> Gerar Mensalidades do Mês
          </button>
        </form>
      </div>
    </div>

    <div class="table-responsive">
      <table id="minhaTabela3" class="table table-hover align-middle" width="100%" cellspacing="0">
        <thead>
          <tr class="text-secondary small font-weight-bold border-bottom">
            <th scope="col">Aluno</th>
            <th scope="col">Vencimento</th>
            <th scope="col">Referência</th>
            <th scope="col">Valor</th>
            <th scope="col">Data Pagto.</th>
            <th scope="col">Status</th>
            <th scope="col" class="text-center" style="width: 120px;">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($mensalidades)): ?>
            <?php foreach ($mensalidades as $m): 
              $id_mensalidade = $m['id'];
              $status_pag = strtolower($m['status_pagamento']);
              
              // Resolve badges
              $badge_class = 'secondary';
              $label_pag = 'Pendente';
              if ($status_pag == 'pago') {
                $badge_class = 'success';
                $label_pag = 'Pago';
              } elseif ($status_pag == 'atrasado') {
                $badge_class = 'danger';
                $label_pag = 'Atrasado';
              } elseif ($status_pag == 'pendente') {
                $badge_class = 'warning';
                $label_pag = 'Pendente';
              }
            ?>
              <tr>
                <td class="align-middle font-weight-bold text-dark text-capitalize"><?php echo htmlspecialchars($m['nome'] ?? ''); ?></td>
                <td class="align-middle text-dark small"><?php echo date("d/m/Y", strtotime($m['data_vencimento'])); ?></td>
                <td class="align-middle text-secondary font-weight-bold small"><?php echo htmlspecialchars($m['referencia'] ?? ''); ?></td>
                <td class="align-middle font-weight-bold text-dark">R$ <?php echo number_format($m['valor'], 2, ',', '.'); ?></td>
                <td class="align-middle text-muted small">
                  <?php echo !empty($m['data_pagamento']) ? date("d/m/Y", strtotime($m['data_pagamento'])) : '--'; ?>
                </td>
                <td class="align-middle">
                  <span class="badge badge-<?php echo $badge_class; ?> text-uppercase px-2 py-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                    <?php echo $label_pag; ?>
                  </span>
                </td>
                <td class="align-middle text-center">
                  <?php if ($status_pag !== 'pago'): ?>
                    <!-- Botão Receber Pagamento -->
                    <button type="button" class="btn btn-sm btn-outline-success border-0 rounded-circle" data-toggle="modal" data-target="#modalReceber<?php echo $id_mensalidade; ?>" title="Dar Baixa no Pagamento" style="width: 32px; height: 32px; padding: 5px 0;">
                      <i class="fa-solid fa-cash-register"></i>
                    </button>
                  <?php else: ?>
                    <!-- Botão de Imprimir Recibo -->
                    <a href="../../controllers/gerar_recibo.php?id=<?php echo $id_mensalidade; ?>" target="_blank" class="btn btn-sm btn-outline-danger border-0 rounded-circle" title="Imprimir Recibo" style="width: 32px; height: 32px; padding: 5px 0; display: inline-flex; align-items: center; justify-content: center;">
                      <i class="fa-solid fa-file-pdf"></i>
                    </a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
