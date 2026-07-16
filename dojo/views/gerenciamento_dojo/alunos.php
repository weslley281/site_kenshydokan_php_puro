<?php
// views/gerenciamento_dojo/alunos.php
$alunos = $alunoConfigModel->listarAlunosConfig();
?>

<div class="card border-0 shadow-sm rounded-lg">
  <div class="card-body p-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h5 class="text-danger font-weight-bold mb-0"><i class="fa-solid fa-users-gear mr-2"></i>Configuração de Cobrança dos Alunos</h5>
      <small class="text-muted">Apenas filiados confirmados aparecem nesta lista.</small>
    </div>

    <div class="table-responsive">
      <table id="minhaTabela" class="table table-hover align-middle" width="100%" cellspacing="0">
        <thead>
          <tr class="text-secondary small font-weight-bold border-bottom">
            <th scope="col">Nome do Aluno</th>
            <th scope="col">Graduação</th>
            <th scope="col">Telefone</th>
            <th scope="col">Valor Mensal</th>
            <th scope="col">Dia Venc.</th>
            <th scope="col">Status Fin.</th>
            <th scope="col" class="text-center" style="width: 120px;">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($alunos)): ?>
            <?php foreach ($alunos as $aluno): 
              $id_filiado = $aluno['id_filiado'];
              $status = strtolower($aluno['status_aluno']);
              
              // Resolve o badge de status
              $badge_class = 'secondary';
              $label_status = 'Desconhecido';
              if ($status == 'adimplente') {
                $badge_class = 'success';
                $label_status = 'Adimplente';
              } elseif ($status == 'inadimplente') {
                $badge_class = 'danger';
                $label_status = 'Inadimplente';
              } elseif ($status == 'pausado') {
                $badge_class = 'warning';
                $label_status = 'Pausado';
              }
            ?>
              <tr>
                <td class="align-middle font-weight-bold text-dark text-capitalize">
                  <?php echo htmlspecialchars($aluno['nome'] ?? ''); ?><br>
                  <small class="text-muted font-weight-normal"><?php echo htmlspecialchars($aluno['email'] ?? ''); ?></small>
                </td>
                <td class="align-middle text-dark small text-capitalize"><?php echo htmlspecialchars($aluno['graduacao'] ?? ''); ?></td>
                <td class="align-middle text-muted small"><?php echo htmlspecialchars($aluno['telefone'] ?? ''); ?></td>
                <td class="align-middle font-weight-bold text-dark">R$ <?php echo number_format($aluno['valor_mensalidade'], 2, ',', '.'); ?></td>
                <td class="align-middle text-secondary font-weight-bold text-center">Dia <?php echo $aluno['dia_vencimento']; ?></td>
                <td class="align-middle">
                  <span class="badge badge-<?php echo $badge_class; ?> text-uppercase px-2 py-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                    <?php echo $label_status; ?>
                  </span>
                </td>
                <td class="align-middle text-center">
                  <!-- Botão Editar Configuração -->
                  <button type="button" class="btn btn-sm btn-outline-primary border-0 rounded-circle mr-1" data-toggle="modal" data-target="#modalConfigAluno<?php echo $id_filiado; ?>" title="Configurar Cobrança" style="width: 32px; height: 32px; padding: 5px 0;">
                    <i class="fa-solid fa-gear"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
