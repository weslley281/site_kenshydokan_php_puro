<?php
// views/gerenciamento_dojo/alunos_modals.php
if (!empty($alunos)):
  foreach ($alunos as $aluno): 
    $id_filiado = $aluno['id_filiado'];
    $status = strtolower($aluno['status_aluno']);
  ?>
    <!-- Modal de Configuração -->
    <div class="modal fade" id="modalConfigAluno<?php echo $id_filiado; ?>" tabindex="-1" role="dialog" aria-labelledby="configLabel<?php echo $id_filiado; ?>" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg text-left">
          <div class="modal-header bg-danger text-white border-0 py-3">
            <h5 class="modal-title font-weight-bold" id="configLabel<?php echo $id_filiado; ?>">
              <i class="fa-solid fa-sliders mr-2"></i> Configurar Mensalidade
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="../../controllers/dojoGerenciamentoController.php" method="post">
            <input type="hidden" name="tipo" value="salvar_aluno_config">
            <input type="hidden" name="id_filiado" value="<?php echo $id_filiado; ?>">

            <div class="modal-body p-4">
              <h6 class="font-weight-bold text-dark mb-3 text-capitalize"><?php echo htmlspecialchars($aluno['nome'] ?? ''); ?></h6>
              
              <div class="form-group mb-3">
                <label for="valor_mensalidade<?php echo $id_filiado; ?>" class="text-secondary small font-weight-bold text-uppercase">Valor da Mensalidade (R$)</label>
                <input type="number" step="0.01" class="form-control form-control-lg bg-light border-0 shadow-sm" id="valor_mensalidade<?php echo $id_filiado; ?>" name="valor_mensalidade" value="<?php echo $aluno['valor_mensalidade']; ?>" required>
              </div>

              <div class="form-group mb-3">
                <label for="dia_vencimento<?php echo $id_filiado; ?>" class="text-secondary small font-weight-bold text-uppercase">Dia do Vencimento (1 a 28)</label>
                <input type="number" min="1" max="28" class="form-control form-control-lg bg-light border-0 shadow-sm" id="dia_vencimento<?php echo $id_filiado; ?>" name="dia_vencimento" value="<?php echo $aluno['dia_vencimento']; ?>" required>
              </div>

              <div class="form-group mb-0">
                <label for="status_aluno<?php echo $id_filiado; ?>" class="text-secondary small font-weight-bold text-uppercase">Situação do Aluno</label>
                <select class="form-control form-control-lg bg-light border-0 shadow-sm" id="status_aluno<?php echo $id_filiado; ?>" name="status_aluno" required>
                  <option value="adimplente" <?php echo ($status == 'adimplente') ? 'selected' : ''; ?>>Adimplente (Ativo)</option>
                  <option value="inadimplente" <?php echo ($status == 'inadimplente') ? 'selected' : ''; ?>>Inadimplente (Em atraso)</option>
                  <option value="pausado" <?php echo ($status == 'pausado') ? 'selected' : ''; ?>>Pausado (Suspenso)</option>
                </select>
              </div>
            </div>

            <div class="modal-footer border-0 bg-light py-3 d-flex justify-content-end">
              <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Salvar Config.</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php 
  endforeach;
endif;
?>
