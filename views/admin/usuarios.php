<div class="tab-pane fade show active">
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Gerenciar Usuários</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
      <div class="card-body p-4">
        <div class="table-responsive">
          <table id="minhaTabela" class="table table-hover align-middle" width="100%" cellspacing="0">
            <thead>
              <tr class="text-secondary small font-weight-bold border-bottom">
                <th scope="col">Nome</th>
                <th scope="col">E-mail</th>
                <th scope="col">Nível</th>
                <th scope="col">Telefone</th>
                <th scope="col">Criação</th>
                <th scope="col">Alteração</th>
                <th scope="col" class="text-center" style="width: 120px;">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php
              include_once __DIR__ . "/../../models/usuarioModel.php";
              
              $usuarios = Usuario::buscarTodosUsuarios();

              if (empty($usuarios)) {
                echo '<tr><td colspan="7" class="text-center text-muted py-4">Nenhum usuário cadastrado no momento.</td></tr>';
              } else {
                foreach ($usuarios as $res_usuario) {
                  $id_usuario = $res_usuario["id_usuario"];
                  $nome = $res_usuario["nome"];
                  $nivel = strtolower($res_usuario["nivel"]);
                  $id_fil = $res_usuario["id_fil"];
                  $email = $res_usuario["email"];
                  $telefone = $res_usuario["telefone"];
                  $dataCriacao = $res_usuario["dataCriacao"];
                  $dataMudanca = $res_usuario["dataMudanca"];

                  // Resolve o badge do nível
                  $badge_class = 'secondary';
                  if ($nivel == 'admin') {
                    $badge_class = 'danger';
                  } elseif ($nivel == 'sensei') {
                    $badge_class = 'warning';
                  } elseif ($nivel == 'aluno') {
                    $badge_class = 'success';
                  }
              ?>
                  <tr>
                    <td class="align-middle font-weight-bold text-dark text-capitalize"><?php echo htmlspecialchars($nome); ?></td>
                    <td class="align-middle text-muted small"><?php echo htmlspecialchars($email); ?></td>
                    <td class="align-middle">
                      <span class="badge badge-<?php echo $badge_class; ?> text-uppercase px-2 py-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                        <?php echo htmlspecialchars($nivel); ?>
                      </span>
                    </td>
                    <td class="align-middle text-dark small"><?php echo htmlspecialchars(!empty($telefone) ? $telefone : 'N/A'); ?></td>
                    <td class="align-middle text-muted small"><?php echo date_format(date_create($dataCriacao), "d/m/Y"); ?></td>
                    <td class="align-middle text-muted small"><?php echo date_format(date_create($dataMudanca), "d/m/Y"); ?></td>
                    <td class="align-middle text-center">
                      <a href="editar_usuario.php?id=<?php echo $id_usuario; ?>" class="btn btn-sm btn-outline-primary border-0 rounded-circle mr-1" title="Editar Usuário" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </a>
                      <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle delete-user-btn" data-toggle="modal" data-target="#confirmDeleteModal" data-id="<?php echo $id_usuario; ?>" data-nome="<?php echo htmlspecialchars($nome); ?>" title="Excluir Usuário" style="width: 32px; height: 32px; padding: 5px 0;">
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
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-danger text-white border-0 py-3">
        <h5 class="modal-title font-weight-bold" id="confirmDeleteLabel">
          <i class="fa-solid fa-triangle-exclamation mr-2"></i> Confirmar Exclusão
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-4 text-center">
        <p class="lead mb-2">Tem certeza que deseja excluir este usuário?</p>
        <h4 class="font-weight-bold text-danger mb-0" id="deleteUserName"></h4>
        <p class="text-muted mt-2 small">Esta ação não poderá ser desfeita.</p>
      </div>
      <div class="modal-footer border-0 bg-light py-3 text-center d-flex justify-content-center">
        <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
        <form action="../../controllers/usuarioController.php" method="post" class="d-inline">
          <input type="hidden" name="tipo" value="deletar">
          <input type="hidden" name="id_usuario" id="deleteUserId" value="">
          <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Excluir</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quando o modal for exibido
    $('#confirmDeleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Botão que acionou o modal
        var id = button.data('id'); // Extrai info dos atributos data-*
        var nome = button.data('nome');
        
        var modal = $(this);
        modal.find('#deleteUserId').val(id);
        modal.find('#deleteUserName').text(nome);
    });
});
</script>