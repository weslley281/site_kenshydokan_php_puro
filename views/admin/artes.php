<div class="tab-pane fade show active">
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Gerenciar Artes Marciais</h2>
      <a href="criar_arte.php" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-4">
        <i class="fas fa-plus mr-2"></i> Criar Arte Marcial
      </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
      <div class="card-body p-4">
        <div class="table-responsive">
          <table id="tabelaArtes" class="table table-hover align-middle" width="100%" cellspacing="0">
            <thead>
              <tr class="text-secondary small font-weight-bold border-bottom">
                <th scope="col" style="width: 80px;">Código</th>
                <th scope="col">Nome da Arte Marcial</th>
                <th scope="col" class="text-center" style="width: 120px;">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php
              include_once __DIR__ . "/../../models/arteModel.php";
              
              $arteModelRepo = new ArteMarcial();
              $artes = $arteModelRepo->listarArtes();

              if (empty($artes)) {
                echo '<tr><td colspan="3" class="text-center text-muted py-4">Nenhuma arte marcial cadastrada no momento.</td></tr>';
              } else {
                foreach ($artes as $res_arte) {
                  $id_arte = $res_arte["id_arte"];
                  $nome = $res_arte["nome"];
              ?>
                  <tr>
                    <td class="align-middle font-weight-bold text-secondary">#<?php echo $id_arte; ?></td>
                    <td class="align-middle font-weight-bold text-dark text-capitalize"><?php echo htmlspecialchars($nome); ?></td>
                    <td class="align-middle text-center">
                      <a href="editar_arte.php?id=<?php echo $id_arte; ?>" class="btn btn-sm btn-outline-primary border-0 rounded-circle mr-1" title="Editar Arte Marcial" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </a>
                      <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" data-toggle="modal" data-target="#confirmDeleteArteModal" data-id="<?php echo $id_arte; ?>" data-nome="<?php echo htmlspecialchars($nome); ?>" title="Excluir Arte Marcial" style="width: 32px; height: 32px; padding: 5px 0;">
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
<div class="modal fade" id="confirmDeleteArteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteArteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg text-left">
      <div class="modal-header bg-danger text-white border-0 py-3">
        <h5 class="modal-title font-weight-bold" id="confirmDeleteArteLabel">
          <i class="fa-solid fa-triangle-exclamation mr-2"></i> Confirmar Exclusão
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-4 text-center">
        <p class="lead mb-2">Tem certeza que deseja excluir esta arte marcial?</p>
        <h5 class="font-weight-bold text-danger mb-0" id="deleteArteName"></h5>
        <p class="text-muted mt-2 small">Atenção: A exclusão poderá falhar caso haja filiados vinculados a esta arte marcial.</p>
      </div>
      <div class="modal-footer border-0 bg-light py-3 text-center d-flex justify-content-center">
        <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
        <form action="../../controllers/arteController.php" method="post" class="d-inline">
          <input type="hidden" name="tipo" value="excluir_arte">
          <input type="hidden" name="id_arte" id="deleteArteId" value="">
          <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Excluir</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        if (!$.fn.DataTable.isDataTable('#tabelaArtes')) {
            $('#tabelaArtes').DataTable({
                "order": [[0, "asc"]],
                "pageLength": 10,
                "searching": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
                }
            });
        }
    }
    
    $('#confirmDeleteArteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nome = button.data('nome');
        
        var modal = $(this);
        modal.find('#deleteArteId').val(id);
        modal.find('#deleteArteName').text(nome);
    });
});
</script>
