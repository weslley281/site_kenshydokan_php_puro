<div class="tab-pane fade show active">
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Gerenciar Seminários e Eventos</h2>
      <a href="criar_evento.php" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-4">
        <i class="fas fa-plus mr-2"></i> Criar Novo Evento
      </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
      <div class="card-body p-4">
        <div class="table-responsive">
          <table id="tabelaEventos" class="table table-hover align-middle" width="100%" cellspacing="0">
            <thead>
              <tr class="text-secondary small font-weight-bold border-bottom">
                <th scope="col" style="width: 80px;">Código</th>
                <th scope="col">Título</th>
                <th scope="col">Data do Evento</th>
                <th scope="col">Tipo</th>
                <th scope="col">Local / Link</th>
                <th scope="col" class="text-center" style="width: 120px;">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php
              include_once __DIR__ . "/../../models/eventoModel.php";
              
              $eventoModelRepo = new Evento();
              $eventos = $eventoModelRepo->buscarTodosEventos();

              if (empty($eventos)) {
                echo '<tr><td colspan="6" class="text-center text-muted py-4">Nenhum evento cadastrado no momento.</td></tr>';
              } else {
                foreach ($eventos as $e) {
                  $id_evento = $e["id_evento"];
                  $titulo = $e["titulo"];
                  $data_evento = date_format(date_create($e["data_evento"]), "d/m/Y H:i");
                  $tipo = $e["tipo"];
                  $endereco = $e["endereco"];
                  $link = $e["link_assistir"];
              ?>
                  <tr>
                    <td class="align-middle font-weight-bold text-secondary">#<?php echo $id_evento; ?></td>
                    <td class="align-middle font-weight-bold text-dark text-capitalize"><?php echo htmlspecialchars($titulo); ?></td>
                    <td class="align-middle text-muted small"><?php echo $data_evento; ?></td>
                    <td class="align-middle">
                      <span class="badge badge-<?php echo ($tipo == 'online') ? 'success' : 'primary'; ?> font-weight-bold px-2 py-1 text-uppercase" style="font-size: 0.75rem;">
                        <?php echo $tipo; ?>
                      </span>
                    </td>
                    <td class="align-middle text-dark small" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                      <?php 
                      if ($tipo == 'online') {
                        echo !empty($link) ? '<a href="'.htmlspecialchars($link).'" target="_blank" class="text-info font-weight-bold"><i class="fa-solid fa-link mr-1"></i>Link Assistir</a>' : '<span class="text-muted">Sem link</span>';
                      } else {
                        echo !empty($endereco) ? htmlspecialchars($endereco) : '<span class="text-muted">Sem endereço</span>';
                      }
                      ?>
                    </td>
                    <td class="align-middle text-center">
                      <a href="editar_evento.php?id=<?php echo $id_evento; ?>" class="btn btn-sm btn-outline-primary border-0 rounded-circle mr-1" title="Editar Evento" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </a>
                      <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" data-toggle="modal" data-target="#confirmDeleteEventoModal" data-id="<?php echo $id_evento; ?>" data-titulo="<?php echo htmlspecialchars($titulo); ?>" title="Excluir Evento" style="width: 32px; height: 32px; padding: 5px 0;">
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
<div class="modal fade" id="confirmDeleteEventoModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteEventoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg text-left">
      <div class="modal-header bg-danger text-white border-0 py-3">
        <h5 class="modal-title font-weight-bold" id="confirmDeleteEventoLabel">
          <i class="fa-solid fa-triangle-exclamation mr-2"></i> Confirmar Exclusão
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-4 text-center">
        <p class="lead mb-2">Tem certeza que deseja excluir este evento?</p>
        <h5 class="font-weight-bold text-danger mb-0" id="deleteEventoTitle"></h5>
      </div>
      <div class="modal-footer border-0 bg-light py-3 text-center d-flex justify-content-center">
        <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
        <form action="../../controllers/eventoController.php" method="post" class="d-inline">
          <input type="hidden" name="tipo" value="excluir_evento">
          <input type="hidden" name="id_evento" id="deleteEventoId" value="">
          <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Excluir</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Inicialização do DataTable com idioma em Português
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        if (!$.fn.DataTable.isDataTable('#tabelaEventos')) {
            $('#tabelaEventos').DataTable({
                "order": [[0, "desc"]],
                "pageLength": 10,
                "searching": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
                }
            });
        }
    }
    
    // Vinculação do Modal de Confirmação Único
    $('#confirmDeleteEventoModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var titulo = button.data('titulo');
        var modal = $(this);
        
        modal.find('#deleteEventoId').val(id);
        modal.find('#deleteEventoTitle').text(titulo);
    });
});
</script>
