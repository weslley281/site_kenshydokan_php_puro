<?php
// views/gerenciamento_dojo/link_stripe.php
$alunos = $alunoConfigModel->listarAlunosConfig();
$ref_atual = date("m/Y");
?>

<div class="card border-0 shadow-sm rounded-lg">
  <div class="card-body p-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
      <div>
        <h5 class="text-danger font-weight-bold mb-1"><i class="fa-brands fa-stripe mr-2" style="font-size: 1.5rem; vertical-align: middle;"></i>Gestão de Links e Assinaturas Stripe</h5>
        <p class="text-muted small mb-0">Envie cobranças avulsas diretamente por e-mail ou proponha assinaturas recorrentes com débito automático no cartão do aluno.</p>
      </div>
      <div class="mt-2 mt-md-0">
        <span class="badge badge-light border text-secondary font-weight-bold p-2 px-3 rounded-pill">
          <i class="fa-regular fa-clock mr-1"></i> Referência Padrão: <?php echo $ref_atual; ?>
        </span>
      </div>
    </div>

    <!-- Formulários ocultos para associação dos inputs da tabela via atributo form (HTML5) -->
    <?php if (!empty($alunos)): ?>
      <?php foreach ($alunos as $aluno): 
          $id_filiado = $aluno['id_filiado'];
      ?>
        <form id="form_stripe_<?php echo $id_filiado; ?>" data-nome="<?php echo htmlspecialchars($aluno['nome']); ?>" action="../../controllers/dojoGerenciamentoController.php" method="post" onsubmit="return confirmarStripe('form_stripe_<?php echo $id_filiado; ?>');">
          <input type="hidden" name="tipo" value="enviar_link_stripe">
          <input type="hidden" name="id_filiado" value="<?php echo $id_filiado; ?>">
        </form>
      <?php endforeach; ?>
    <?php endif; ?>

    <div class="table-responsive">
      <table class="table table-hover align-middle js-datatable" width="100%" cellspacing="0">
        <thead>
          <tr class="text-secondary small font-weight-bold border-bottom">
            <th scope="col">Nome do Aluno / E-mail / Recorrência</th>
            <th scope="col">Graduação</th>
            <th scope="col" style="width: 160px;">Tipo de Cobrança</th>
            <th scope="col" style="width: 140px;">Valor (R$)</th>
            <th scope="col">Descrição da Cobrança</th>
            <th scope="col" class="text-center" style="width: 180px;">Ação</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($alunos)): ?>
            <?php foreach ($alunos as $aluno): 
              $id_filiado = $aluno['id_filiado'];
              $valor_mensal = $aluno['valor_mensalidade'];
              $rec_status = $aluno['recorrencia_status'] ?? '';
              
              // Resolve o badge de status da recorrência
              if ($rec_status === 'ativo') {
                  $rec_badge = '<span class="badge badge-success small px-2 py-1 mt-1 d-inline-block" style="font-size: 0.65rem;"><i class="fa-solid fa-arrows-rotate mr-1"></i> Assinatura Ativa</span>';
              } elseif ($rec_status === 'pendente') {
                  $rec_badge = '<span class="badge badge-warning small px-2 py-1 mt-1 d-inline-block" style="font-size: 0.65rem;"><i class="fa-solid fa-hourglass-half mr-1"></i> Pendente de Aceite</span>';
              } else {
                  $rec_badge = '<span class="badge badge-light border text-secondary small px-2 py-1 mt-1 d-inline-block" style="font-size: 0.65rem;"><i class="fa-solid fa-slash mr-1"></i> Sem Recorrência</span>';
              }
            ?>
              <tr>
                <td class="align-middle font-weight-bold text-dark text-capitalize">
                  <?php echo htmlspecialchars($aluno['nome'] ?? ''); ?><br>
                  <small class="text-muted font-weight-normal"><?php echo htmlspecialchars($aluno['email'] ?? ''); ?></small><br>
                  <?php echo $rec_badge; ?>
                </td>
                <td class="align-middle text-dark small text-capitalize"><?php echo htmlspecialchars($aluno['graduacao'] ?? ''); ?></td>
                <td class="align-middle">
                  <select form="form_stripe_<?php echo $id_filiado; ?>" name="cobranca_tipo" class="form-control form-control-sm text-dark font-weight-bold" onchange="document.getElementById('btn_stripe_<?php echo $id_filiado; ?>').innerHTML = (this.value === 'recorrente') ? '<i class=&quot;fa-solid fa-arrows-rotate mr-1&quot;></i> Propor Recorrência' : '<i class=&quot;fa-brands fa-stripe mr-1&quot;></i> Enviar Link'">
                    <option value="avulso">Avulso (Único)</option>
                    <option value="recorrente" <?php echo ($rec_status === 'pendente' || $rec_status === 'ativo') ? 'selected' : ''; ?>>Recorrente (Mensal)</option>
                  </select>
                </td>
                <td class="align-middle">
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-light text-secondary font-weight-bold">R$</span>
                    </div>
                    <input type="number" form="form_stripe_<?php echo $id_filiado; ?>" name="valor" step="0.01" min="1.00" value="<?php echo number_format($valor_mensal, 2, '.', ''); ?>" class="form-control font-weight-bold text-dark" required style="max-width: 100px;">
                  </div>
                </td>
                <td class="align-middle">
                  <input type="text" form="form_stripe_<?php echo $id_filiado; ?>" name="descricao" value="Mensalidade - Ref: <?php echo $ref_atual; ?>" class="form-control form-control-sm text-secondary" required placeholder="Ex: Mensalidade - Ref: <?php echo $ref_atual; ?>">
                </td>
                <td class="align-middle text-center">
                  <?php if ($rec_status === 'ativo'): ?>
                    <button type="button" disabled class="btn btn-sm btn-outline-success font-weight-bold rounded-pill px-3 shadow-sm d-inline-flex align-items-center">
                      <i class="fa-solid fa-check-double mr-1"></i> Assinatura Ativa
                    </button>
                  <?php else: ?>
                    <button type="submit" id="btn_stripe_<?php echo $id_filiado; ?>" form="form_stripe_<?php echo $id_filiado; ?>" class="btn btn-sm btn-danger font-weight-bold rounded-pill px-3 shadow-sm d-inline-flex align-items-center">
                      <?php if ($rec_status === 'pendente'): ?>
                        <i class="fa-solid fa-arrows-rotate mr-1"></i> Propor Recorrência
                      <?php else: ?>
                        <i class="fa-brands fa-stripe mr-1" style="font-size: 1.1rem;"></i> Enviar Link
                      <?php endif; ?>
                    </button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center text-muted py-4">Nenhum aluno registrado ou ativo encontrado.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function confirmarStripe(formId) {
    var select = document.querySelector('select[form="' + formId + '"]');
    var tipo = select ? select.value : 'avulso';
    var form = document.getElementById(formId);
    var nome = form.getAttribute('data-nome');
    
    if (tipo === 'recorrente') {
        return confirm('Deseja propor uma cobrança recorrente mensal para ' + nome + '? A cobrança ficará pendente e o aluno deverá aceitar e cadastrar o cartão em seu painel.');
    } else {
        return confirm('Deseja realmente gerar e enviar o link de cobrança avulsa Stripe por e-mail para ' + nome + '?');
    }
}
</script>
