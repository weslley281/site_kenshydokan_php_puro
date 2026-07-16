<div class="tab-pane fade show active">
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Gerenciar Filiações</h2>
      <a href="criar_filiacao.php" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-4">
        <i class="fas fa-plus mr-2"></i> Nova Filiação
      </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
      <div class="card-body p-4">
        <div class="table-responsive">
          <table id="tabelaFiliacoes" class="table table-hover align-middle" width="100%" cellspacing="0">
            <thead>
              <tr class="text-secondary small font-weight-bold border-bottom">
                <th scope="col" style="width: 80px;">Código</th>
                <th scope="col" style="width: 100px;">Logo</th>
                <th scope="col">Instituição</th>
                <th scope="col">Website</th>
                <th scope="col" style="width: 120px;">Status</th>
                <th scope="col" class="text-center" style="width: 120px;">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php
              include_once __DIR__ . "/../../models/filiacaoModel.php";
              
              $filModel = new Filiacao();
              $filiacoes = $filModel->listarTodas();

              if (empty($filiacoes)) {
                echo '<tr><td colspan="6" class="text-center text-muted py-4">Nenhuma filiação cadastrada no momento.</td></tr>';
              } else {
                foreach ($filiacoes as $res_fil) {
                  $id_fil = $res_fil["id_filiacao"];
                  $nome = $res_fil["nome"];
                  $logo = $res_fil["logo"];
                  $link = $res_fil["link"];
                  $status = strtolower($res_fil["status"] ?? 'ativo');
              ?>
                  <tr>
                    <td class="align-middle font-weight-bold text-secondary">#<?php echo $id_fil; ?></td>
                    <td class="align-middle">
                      <img src="../../img/<?php echo htmlspecialchars($logo); ?>" class="img-thumbnail rounded bg-white shadow-sm" style="max-height: 50px; max-width: 80px; object-fit: contain;" alt="Logo de <?php echo htmlspecialchars($nome); ?>">
                    </td>
                    <td class="align-middle font-weight-bold text-dark text-capitalize"><?php echo htmlspecialchars($nome); ?></td>
                    <td class="align-middle small">
                      <?php if (!empty($link)): ?>
                        <a href="<?php echo htmlspecialchars($link); ?>" target="_blank" class="text-primary font-weight-bold">
                          <i class="fa-solid fa-up-right-from-square mr-1"></i> Abrir Link
                        </a>
                      <?php else: ?>
                        <span class="text-muted">N/A</span>
                      <?php endif; ?>
                    </td>
                    <td class="align-middle">
                      <span class="badge badge-<?php echo ($status === 'ativo') ? 'success' : 'secondary'; ?> text-uppercase px-2 py-1">
                        <?php echo ($status === 'ativo') ? 'Ativo' : 'Inativo'; ?>
                      </span>
                    </td>
                    <td class="align-middle text-center">
                      <a href="editar_filiacao.php?id=<?php echo $id_fil; ?>" class="btn btn-sm btn-outline-primary border-0 rounded-circle mr-1" title="Editar Filiação" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </a>
                      <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" data-toggle="modal" data-target="#confirmDeleteFilModal" data-id="<?php echo $id_fil; ?>" data-nome="<?php echo htmlspecialchars($nome); ?>" title="Excluir Filiação" style="width: 32px; height: 32px; padding: 5px 0;">
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
<div class="modal fade" id="confirmDeleteFilModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteFilLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg text-left">
      <div class="modal-header bg-danger text-white border-0 py-3">
        <h5 class="modal-title font-weight-bold" id="confirmDeleteFilLabel">
          <i class="fa-solid fa-triangle-exclamation mr-2"></i> Confirmar Exclusão
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-4 text-center">
        <p class="lead mb-2">Tem certeza que deseja excluir esta filiação?</p>
        <h5 class="font-weight-bold text-danger mb-0" id="deleteFilName"></h5>
        <p class="text-muted mt-2 small">Atenção: A exclusão removerá permanentemente a filiação e o respectivo arquivo de imagem do servidor.</p>
      </div>
      <div class="modal-footer border-0 bg-light py-3 text-center d-flex justify-content-center">
        <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
        <form action="../../controllers/filiacaoController.php" method="post" class="d-inline">
          <input type="hidden" name="tipo" value="excluir_filiacao">
          <input type="hidden" name="id_filiacao" id="deleteFilId" value="">
          <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Excluir</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        if (!$.fn.DataTable.isDataTable('#tabelaFiliacoes')) {
            $('#tabelaFiliacoes').DataTable({
                "order": [[0, "desc"]],
                "pageLength": 10,
                "searching": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
                }
            });
        }
    }
    
    $('#confirmDeleteFilModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nome = button.data('nome');
        
        var modal = $(this);
        modal.find('#deleteFilId').val(id);
        modal.find('#deleteFilName').text(nome);
    });
});
</script>
