<?php
include_once __DIR__ . "/../../models/exameGraduacaoModel.php";
$exameRepositorio = new ExameGraduacaoModel();
$exames = $exameRepositorio->listarTodos();
?>

<div class="tab-pane fade show active">
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Gerenciar Exames de Graduação</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
      <div class="card-body p-4">
        <div class="table-responsive">
          <table id="minhaTabela" class="table table-hover align-middle" width="100%" cellspacing="0">
            <thead>
              <tr class="text-secondary small font-weight-bold border-bottom">
                <th scope="col" style="width: 70px;">ID</th>
                <th scope="col">Candidato</th>
                <th scope="col">Documento</th>
                <th scope="col">Graduação Atual</th>
                <th scope="col">Pretendida</th>
                <th scope="col">Professor</th>
                <th scope="col">Situação</th>
                <th scope="col" class="text-center" style="width: 100px;">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($exames)): ?>
                <tr><td colspan="8" class="text-center text-muted py-4">Nenhuma solicitação de exame encontrada.</td></tr>
              <?php else: ?>
                <?php foreach ($exames as $exame): 
                  $documento = $exame['documento'];
                  $link_doc = !empty($documento) ? '../../arquivos/' . $documento : '';
                  
                  $situacao = strtolower($exame['situacao']);
                  $badge_class = 'secondary';
                  if ($situacao == 'aprovado' || $situacao == 'confirmado') {
                    $badge_class = 'success';
                  } elseif ($situacao == 'reprovado' || $situacao == 'cancelado') {
                    $badge_class = 'danger';
                  } elseif ($situacao == 'pendente' || $situacao == 'aguardando' || $situacao == 'analise') {
                    $badge_class = 'warning';
                  }
                ?>
                  <tr>
                    <td class="align-middle font-weight-bold text-secondary">#<?php echo $exame['id']; ?></td>
                    <td class="align-middle font-weight-bold text-dark">
                      <?php echo htmlspecialchars($exame['nome']); ?><br>
                      <small class="text-muted font-weight-normal"><?php echo htmlspecialchars($exame['email']); ?></small>
                    </td>
                    <td class="align-middle">
                      <?php if (!empty($documento)): ?>
                        <a href="<?php echo htmlspecialchars($link_doc); ?>" target="_blank" class="btn btn-xs btn-outline-danger font-weight-bold rounded-pill px-3 py-1 shadow-sm" style="font-size: 0.75rem;">
                          <i class="fa-solid fa-file-pdf mr-1"></i> Visualizar
                        </a>
                      <?php else: ?>
                        <span class="text-muted small">Não enviado</span>
                      <?php endif; ?>
                    </td>
                    <td class="align-middle text-dark small text-capitalize"><?php echo htmlspecialchars($exame['graduacao_atual']); ?></td>
                    <td class="align-middle text-dark small text-capitalize"><?php echo htmlspecialchars($exame['graduacao_pretendida']); ?></td>
                    <td class="align-middle text-dark small text-capitalize"><?php echo htmlspecialchars($exame['professor']); ?></td>
                    <td class="align-middle">
                      <span class="badge badge-<?php echo $badge_class; ?> text-uppercase px-2 py-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                        <?php echo htmlspecialchars($exame['situacao']); ?>
                      </span>
                    </td>
                    <td class="align-middle text-center">
                      <a href="editar_exame.php?id=<?php echo $exame['id']; ?>" class="btn btn-sm btn-outline-primary border-0 rounded-circle" title="Editar Solicitação" style="width: 32px; height: 32px; padding: 5px 0; display: inline-block;">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
