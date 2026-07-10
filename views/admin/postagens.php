<div class="tab-pane fade show active">
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Gerenciar Postagens</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
      <div class="card-body p-4">
        <div class="table-responsive">
          <table id="minhaTabela2" class="table table-hover align-middle" width="100%" cellspacing="0">
            <thead>
              <tr class="text-secondary small font-weight-bold border-bottom">
                <th scope="col" style="width: 80px;">Código</th>
                <th scope="col">Título</th>
                <th scope="col">Criação</th>
                <th scope="col">Alteração</th>
                <th scope="col">Status</th>
                <th scope="col" class="text-center" style="width: 150px;">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php
              include_once __DIR__ . "/../../models/publicacaoModel.php";
              
              $postagens = Publicacao::buscarTodasPostagens();

              if (empty($postagens)) {
                echo '<tr><td colspan="6" class="text-center text-muted py-4">Nenhuma postagem cadastrada no momento.</td></tr>';
              } else {
                foreach ($postagens as $res_postagem) {
                  $id_publicacao = $res_postagem["id_publicacao"];
                  $titulo = $res_postagem["titulo"];
                  $status = strtolower($res_postagem["status"]);
                  $dataCriacao = $res_postagem["dataCriacao"];
                  $dataMudanca = $res_postagem["dataMudanca"];

                  // Resolve o badge do status
                  $badge_class = 'secondary';
                  $status_label = 'Desconhecido';
                  if ($status == 'aguardando') {
                    $badge_class = 'warning';
                    $status_label = 'Aguardando';
                  } elseif ($status == 'aprovado' || $status == 'ativo') {
                    $badge_class = 'success';
                    $status_label = 'Publicado';
                  }
              ?>
                  <tr>
                    <td class="align-middle font-weight-bold text-secondary">#<?php echo $id_publicacao; ?></td>
                    <td class="align-middle font-weight-bold text-dark text-capitalize"><?php echo htmlspecialchars($titulo); ?></td>
                    <td class="align-middle text-muted small"><?php echo date_format(date_create($dataCriacao), "d/m/Y"); ?></td>
                    <td class="align-middle text-muted small"><?php echo date_format(date_create($dataMudanca), "d/m/Y"); ?></td>
                    <td class="align-middle">
                      <span class="badge badge-<?php echo $badge_class; ?> text-uppercase px-2 py-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                        <?php echo htmlspecialchars($status_label); ?>
                      </span>
                    </td>
                    <td class="align-middle text-center">
                      <a href="editar_postagem.php?id=<?php echo $id_publicacao; ?>" class="btn btn-sm btn-outline-primary border-0 rounded-circle mr-1" title="Editar Postagem" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </a>
                      
                      <!-- Alterar Status -->
                      <a href="../../controllers/mudar_status_postagem.php?id=<?php echo $id_publicacao; ?>" class="btn btn-sm btn-outline-<?php echo $status == 'aguardando' ? 'success' : 'secondary'; ?> border-0 rounded-circle mr-1" title="<?php echo $status == 'aguardando' ? 'Aprovar / Publicar' : 'Rebaixar para Aguardando'; ?>" style="width: 32px; height: 32px; padding: 5px 0;">
                        <?php if ($status == 'aguardando'): ?>
                          <i class="fa-solid fa-check"></i>
                        <?php else: ?>
                          <i class="fa-solid fa-clock"></i>
                        <?php endif; ?>
                      </a>

                      <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" data-toggle="modal" data-target="#confirmDeletePostModal" data-id="<?php echo $id_publicacao; ?>" data-titulo="<?php echo htmlspecialchars($titulo); ?>" title="Excluir Postagem" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-trash-can"></i>
                      </button>
                    </td>
                  </tr>
              <?php }
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Exclusão Único -->
<div class="modal fade" id="confirmDeletePostModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeletePostLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg text-left">
      <div class="modal-header bg-danger text-white border-0 py-3">
        <h5 class="modal-title font-weight-bold" id="confirmDeletePostLabel">
          <i class="fa-solid fa-triangle-exclamation mr-2"></i> Confirmar Exclusão
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-4 text-center">
        <p class="lead mb-2">Tem certeza que deseja excluir esta postagem?</p>
        <h5 class="font-weight-bold text-danger mb-0" id="deletePostTitle"></h5>
        <p class="text-muted mt-2 small">Esta ação não poderá ser desfeita.</p>
      </div>
      <div class="modal-footer border-0 bg-light py-3 text-center d-flex justify-content-center">
        <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
        <form action="../../controllers/postagemController.php" method="post" class="d-inline">
          <input type="hidden" name="tipo" value="excluir">
          <input type="hidden" name="id_publicacao" id="deletePostId" value="">
          <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Excluir</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#confirmDeletePostModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var titulo = button.data('titulo');
        
        var modal = $(this);
        modal.find('#deletePostId').val(id);
        modal.find('#deletePostTitle').text(titulo);
    });
});
</script>