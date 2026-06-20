<?php
// views/gerenciamento_dojo/mensalidades_modals.php
if (!empty($mensalidades)):
  foreach ($mensalidades as $m): 
    $id_mensalidade = $m['id'];
    $status_pag = strtolower($m['status_pagamento']);
    if ($status_pag !== 'pago'):
  ?>
    <!-- Modal Receber -->
    <div class="modal fade" id="modalReceber<?php echo $id_mensalidade; ?>" tabindex="-1" role="dialog" aria-labelledby="receberLabel<?php echo $id_mensalidade; ?>" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg text-left">
          <div class="modal-header bg-success text-white border-0 py-3">
            <h5 class="modal-title font-weight-bold" id="receberLabel<?php echo $id_mensalidade; ?>">
              <i class="fa-solid fa-receipt mr-2"></i> Receber Mensalidade
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="../../controllers/dojoGerenciamentoController.php" method="post">
            <input type="hidden" name="tipo" value="receber_mensalidade">
            <input type="hidden" name="id_mensalidade" value="<?php echo $id_mensalidade; ?>">

            <div class="modal-body p-4">
              <p class="lead mb-3">Confirmar o recebimento da mensalidade de:</p>
              <h5 class="font-weight-bold text-success text-capitalize mb-3"><?php echo htmlspecialchars($m['nome'] ?? ''); ?></h5>
              
              <div class="p-3 bg-light rounded-lg border mb-3">
                <div class="row">
                  <div class="col-6">
                    <small class="text-secondary font-weight-bold text-uppercase d-block">Referência</small>
                    <span class="font-weight-bold"><?php echo htmlspecialchars($m['referencia'] ?? ''); ?></span>
                  </div>
                  <div class="col-6">
                    <small class="text-secondary font-weight-bold text-uppercase d-block">Valor da Cobrança</small>
                    <span class="font-weight-bold text-success">R$ <?php echo number_format($m['valor'], 2, ',', '.'); ?></span>
                  </div>
                </div>
              </div>

              <div class="form-group mb-0">
                <label for="data_pagamento<?php echo $id_mensalidade; ?>" class="text-secondary small font-weight-bold text-uppercase">Data do Pagamento</label>
                <input type="date" class="form-control form-control-lg bg-light border-0 shadow-sm" id="data_pagamento<?php echo $id_mensalidade; ?>" name="data_pagamento" value="<?php echo date('Y-m-d'); ?>" required>
              </div>
            </div>

            <div class="modal-footer border-0 bg-light py-3 d-flex justify-content-end">
              <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success px-4 rounded-pill font-weight-bold shadow-sm">Confirmar Recebimento</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php endif; ?>
  <?php endforeach; ?>
<?php endif; ?>
