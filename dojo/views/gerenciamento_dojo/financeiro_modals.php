<?php
// views/gerenciamento_dojo/financeiro_modals.php
?>

<!-- Modal Lançar Movimentação -->
<div class="modal fade" id="modalLancarFinanceiro" tabindex="-1" role="dialog" aria-labelledby="lancarFinanceiroLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg text-left">
      <div class="modal-header bg-danger text-white border-0 py-3">
        <h5 class="modal-title font-weight-bold" id="lancarFinanceiroLabel">
          <i class="fa-solid fa-dollar-sign mr-2"></i> Lançar Movimentação Caixa
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="../../controllers/dojoGerenciamentoController.php" method="post">
        <input type="hidden" name="tipo" value="lancar_financeiro">
        
        <div class="modal-body p-4">
          <div class="form-group mb-3">
            <label for="descricao" class="text-secondary small font-weight-bold text-uppercase">Descrição / Identificação</label>
            <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="descricao" name="descricao" placeholder="Ex: Aluguel do mês, Kimonos novos, etc." required>
          </div>

          <div class="row">
            <div class="col-md-6 form-group mb-3">
              <label for="tipo_movimentacao" class="text-secondary small font-weight-bold text-uppercase">Tipo</label>
              <select class="form-control form-control-lg bg-light border-0 shadow-sm" id="tipo_movimentacao" name="tipo_movimentacao" required>
                <option value="entrada">Receita (Entrada)</option>
                <option value="saida">Despesa (Saída)</option>
              </select>
            </div>
            <div class="col-md-6 form-group mb-3">
              <label for="valor" class="text-secondary small font-weight-bold text-uppercase">Valor (R$)</label>
              <input type="number" step="0.01" class="form-control form-control-lg bg-light border-0 shadow-sm" id="valor" name="valor" placeholder="0,00" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 form-group mb-0">
              <label for="categoria" class="text-secondary small font-weight-bold text-uppercase">Categoria</label>
              <select class="form-control form-control-lg bg-light border-0 shadow-sm" id="categoria" name="categoria" required>
                <option value="mensalidade">Mensalidades</option>
                <option value="aluguel">Aluguel do Dojo</option>
                <option value="luz">Água / Luz / Internet</option>
                <option value="equipamentos">Equipamentos</option>
                <option value="outros">Outros</option>
              </select>
            </div>
            <div class="col-md-6 form-group mb-0">
              <label for="data_movimentacao" class="text-secondary small font-weight-bold text-uppercase">Data</label>
              <input type="date" class="form-control form-control-lg bg-light border-0 shadow-sm" id="data_movimentacao" name="data_movimentacao" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
          </div>
        </div>

        <div class="modal-footer border-0 bg-light py-3 d-flex justify-content-end">
          <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Confirmar Lançamento</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modais colocados fora da tabela para evitar problemas de backdrop e congelamento -->
<?php if (!empty($movimentacoes)): ?>
  <?php foreach ($movimentacoes as $m): 
    $id_financeiro = $m['id'];
    $tipo_mov = strtolower($m['tipo']);
    $text_color = ($tipo_mov == 'entrada') ? 'text-success' : 'text-danger';
  ?>
    <!-- Modal Excluir -->
    <div class="modal fade" id="modalExcluir<?php echo $id_financeiro; ?>" tabindex="-1" role="dialog" aria-labelledby="excluirLabel<?php echo $id_financeiro; ?>" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg text-left">
          <div class="modal-header bg-danger text-white border-0 py-3">
            <h5 class="modal-title font-weight-bold" id="excluirLabel<?php echo $id_financeiro; ?>">
              <i class="fa-solid fa-circle-exclamation mr-2"></i> Confirmar Exclusão
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="../../controllers/dojoGerenciamentoController.php" method="post">
            <input type="hidden" name="tipo" value="excluir_financeiro">
            <input type="hidden" name="id_financeiro" value="<?php echo $id_financeiro; ?>">
            <input type="hidden" name="referencia_mes" value="<?php echo htmlspecialchars($referencia ?? ''); ?>">

            <div class="modal-body p-4">
              <p class="mb-2">Tem certeza que deseja excluir esta movimentação?</p>
              <div class="p-3 bg-light rounded-lg border">
                <h6 class="font-weight-bold text-dark text-capitalize mb-1"><?php echo htmlspecialchars($m['descricao'] ?? ''); ?></h6>
                <span class="font-weight-bold <?php echo $text_color; ?>">
                  <?php echo $tipo_mov == 'saida' ? '- ' : '+ '; ?>R$ <?php echo number_format($m['valor'], 2, ',', '.'); ?>
                </span>
              </div>
              <?php if ($m['categoria'] == 'mensalidade'): ?>
                <div class="alert alert-warning mt-3 mb-0 small">
                  <i class="fa-solid fa-triangle-exclamation mr-1"></i> <strong>Atenção:</strong> Este lançamento foi gerado automaticamente por uma baixa de mensalidade. A exclusão aqui não alterará o status da mensalidade, apenas removerá o registro do caixa.
                </div>
              <?php endif; ?>
            </div>

            <div class="modal-footer border-0 bg-light py-3 d-flex justify-content-end">
              <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Confirmar Exclusão</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
