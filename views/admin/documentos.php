<?php
// views/admin/documentos.php
include_once __DIR__ . "/../../models/documentoModel.php";

$docRepo = new DocumentoModel();
$documentos = $docRepo->listarTodos();
?>

<div class="tab-pane fade show active">
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Gerenciar Documentos (PDF)</h2>
      <button type="button" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-4" data-toggle="modal" data-target="#modalAdicionarDocumento">
        <i class="fas fa-upload mr-2"></i> Enviar Documento
      </button>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
      <div class="card-body p-4">
        <div class="table-responsive">
          <table id="minhaTabela" class="table table-hover align-middle" width="100%" cellspacing="0">
            <thead>
              <tr class="text-secondary small font-weight-bold border-bottom">
                <th scope="col" style="width: 80px;">ID</th>
                <th scope="col">Título</th>
                <th scope="col">Descrição</th>
                <th scope="col">Arquivo</th>
                <th scope="col">Data de Envio</th>
                <th scope="col" class="text-center" style="width: 150px;">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($documentos)): ?>
                <?php foreach ($documentos as $doc): 
                  $id = $doc['id_documento'];
                  $titulo = $doc['titulo'];
                  $descricao = $doc['descricao'];
                  $arquivo = $doc['arquivo'];
                  $dataCriacao = $doc['dataCriacao'];
                ?>
                  <tr>
                    <td class="align-middle font-weight-bold text-secondary">#<?php echo htmlspecialchars($id); ?></td>
                    <td class="align-middle font-weight-bold text-dark text-capitalize"><?php echo htmlspecialchars($titulo ?? ''); ?></td>
                    <td class="align-middle text-dark small"><?php echo htmlspecialchars($descricao ?? 'Sem descrição'); ?></td>
                    <td class="align-middle text-dark small font-italic"><?php echo htmlspecialchars($arquivo ?? ''); ?></td>
                    <td class="align-middle text-muted small"><?php echo date("d/m/Y", strtotime($dataCriacao)); ?></td>
                    <td class="align-middle text-center">
                      <!-- Visualizar PDF -->
                      <a href="../../arquivos/<?php echo htmlspecialchars($arquivo); ?>" target="_blank" class="btn btn-sm btn-outline-success border-0 rounded-circle mr-1" title="Visualizar Documento" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-eye"></i>
                      </a>

                      <!-- Excluir -->
                      <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" data-toggle="modal" data-target="#modalExcluirDocumento<?php echo $id; ?>" title="Excluir Documento" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-trash-can"></i>
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
  </div>
</div>

<?php if (!empty($documentos)): ?>
  <?php foreach ($documentos as $doc): 
    $id = $doc['id_documento'];
    $titulo = $doc['titulo'];
    $arquivo = $doc['arquivo'];
  ?>
    <!-- Modal Excluir Documento -->
    <div class="modal fade" id="modalExcluirDocumento<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="excluirLabel<?php echo $id; ?>" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg text-left">
          <div class="modal-header bg-danger text-white border-0 py-3">
            <h5 class="modal-title font-weight-bold" id="excluirLabel<?php echo $id; ?>">
              <i class="fa-solid fa-triangle-exclamation mr-2"></i> Confirmar Exclusão
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body p-4 text-center">
            <p class="lead mb-2">Tem certeza que deseja excluir o documento?</p>
            <h5 class="font-weight-bold text-danger mb-2"><?php echo htmlspecialchars($titulo); ?></h5>
            <p class="text-secondary font-italic small mb-3">"<?php echo htmlspecialchars($arquivo); ?>"</p>
            <p class="text-muted small">Esta ação apagará o arquivo físico permanentemente e não poderá ser desfeita.</p>
          </div>
          <div class="modal-footer border-0 bg-light py-3 text-center d-flex justify-content-center">
            <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
            <form action="../../controllers/documentoController.php" method="post" class="d-inline">
              <input type="hidden" name="tipo" value="excluir">
              <input type="hidden" name="id_documento" value="<?php echo $id; ?>">
              <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Excluir</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<!-- Modal Adicionar Documento -->
<div class="modal fade" id="modalAdicionarDocumento" role="dialog" aria-labelledby="adicionarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-danger text-white border-0 py-3">
        <h5 class="modal-title font-weight-bold" id="adicionarLabel">
          <i class="fa-solid fa-upload mr-2"></i> Enviar Documento (PDF)
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="../../controllers/documentoController.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="tipo" value="inserir">
        
        <div class="modal-body p-4 text-left">
          <div class="form-group mb-3">
            <label for="titulo" class="text-secondary small font-weight-bold text-uppercase">Título do Documento</label>
            <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="titulo" name="titulo" placeholder="Ex: Regras de Campeonatos" required>
          </div>

          <div class="form-group mb-3">
            <label for="descricao" class="text-secondary small font-weight-bold text-uppercase">Descrição (Opcional)</label>
            <textarea class="form-control bg-light border-0 shadow-sm" id="descricao" name="descricao" rows="3" placeholder="Disponível para os filiados no painel..."></textarea>
          </div>

          <div class="form-group mb-4">
            <label for="documento" class="text-secondary small font-weight-bold text-uppercase">Arquivo PDF</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="documento" name="documento" accept=".pdf" required>
              <label class="custom-file-label bg-light border-0 shadow-sm" for="documento">Escolher arquivo...</label>
            </div>
            <small class="form-text text-muted mt-2">Apenas formato PDF é aceito.</small>
          </div>
        </div>
        
        <div class="modal-footer border-0 bg-light py-3 d-flex justify-content-end">
          <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Salvar e Enviar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Script para atualizar o label do input file customizado do Bootstrap
  document.querySelector('.custom-file-input').addEventListener('change', function(e) {
    var fileName = e.target.files[0].name;
    var nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = fileName;
  });
</script>
