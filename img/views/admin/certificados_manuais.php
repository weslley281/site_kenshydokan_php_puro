<?php
// views/admin/certificados_manuais.php
include_once __DIR__ . "/../../models/certificadoManualModel.php";
include_once __DIR__ . "/../../models/filiadoModel.php";

$certManualRepo = new CertificadoManualModel();
$filiadoRepo = new FiliadoModel();

$certificados = $certManualRepo->listarTodos();
// Apenas filiados confirmados para emissão
$filiados = $filiadoRepo->listarFiliadosAtivos(); 
?>

<div class="tab-pane fade show active">
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Gerenciar Certificados Manuais</h2>
      <button type="button" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-4" data-toggle="modal" data-target="#modalAdicionarCertificado">
        <i class="fas fa-plus mr-2"></i> Emitir Certificado
      </button>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
      <div class="card-body p-4">
        <div class="table-responsive">
          <table id="tabelaCertificados" class="table table-hover align-middle" width="100%" cellspacing="0">
            <thead>
              <tr class="text-secondary small font-weight-bold border-bottom">
                <th scope="col" style="width: 80px;">Código</th>
                <th scope="col">Filiado</th>
                <th scope="col">Dojô</th>
                <th scope="col">Graduação</th>
                <th scope="col">Título do Certificado</th>
                <th scope="col">Data Emissão</th>
                <th scope="col" class="text-center" style="width: 150px;">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($certificados)): ?>
                <!-- Let DataTables handle empty state -->
              <?php else: ?>
                <?php foreach ($certificados as $cert): 
                  $id = $cert['id'];
                  $id_filiado = $cert['id_filiado'];
                  $nome = $cert['nome'];
                  $codigo_filiado = $cert['codigo'];
                  $dojo = $cert['dojo'];
                  $graduacao = $cert['graduacao'] ?? 'Sem registro';
                  $titulo = $cert['titulo'];
                  $data_emissao = $cert['data_emissao'];
                ?>
                  <tr>
                    <td class="align-middle font-weight-bold text-secondary">#<?php echo htmlspecialchars($codigo_filiado ?? $id_filiado); ?></td>
                    <td class="align-middle font-weight-bold text-dark text-capitalize"><?php echo htmlspecialchars($nome ?? ''); ?></td>
                    <td class="align-middle text-dark small text-capitalize"><?php echo htmlspecialchars($dojo ?? ''); ?></td>
                    <td class="align-middle text-dark small text-capitalize"><?php echo htmlspecialchars($graduacao ?? ''); ?></td>
                    <td class="align-middle text-dark font-weight-bold"><?php echo htmlspecialchars($titulo ?? ''); ?></td>
                    <td class="align-middle text-muted small"><?php echo date("d/m/Y", strtotime($data_emissao)); ?></td>
                    <td class="align-middle text-center">
                      <!-- Botão Gerar PDF -->
                      <a href="../../controllers/gerar_certificado_manual.php?id=<?php echo $id; ?>" target="_blank" class="btn btn-sm btn-outline-success border-0 rounded-circle mr-1" title="Gerar PDF do Certificado" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-file-pdf"></i>
                      </a>

                      <!-- Botão Editar -->
                      <button type="button" class="btn btn-sm btn-outline-primary border-0 rounded-circle mr-1" data-toggle="modal" data-target="#modalEditarCertificado<?php echo $id; ?>" title="Editar Certificado" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </button>

                      <!-- Botão Excluir -->
                      <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" data-toggle="modal" data-target="#modalExcluirCertificado<?php echo $id; ?>" title="Excluir Certificado" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-trash-can"></i>
                      </button>

                      <!-- Modal Editar Certificado -->
                      <div class="modal fade" id="modalEditarCertificado<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="editarLabel<?php echo $id; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content border-0 shadow-lg text-left">
                            <div class="modal-header bg-danger text-white border-0 py-3">
                              <h5 class="modal-title font-weight-bold" id="editarLabel<?php echo $id; ?>">
                                <i class="fa-solid fa-pen-to-square mr-2"></i> Editar Certificado
                              </h5>
                              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form action="../../controllers/certificadoManualController.php" method="post">
                              <input type="hidden" name="tipo" value="editar">
                              <input type="hidden" name="id" value="<?php echo $id; ?>">
                              
                              <div class="modal-body p-4">
                                <div class="form-group mb-3">
                                  <label for="id_filiado_edit<?php echo $id; ?>" class="text-secondary small font-weight-bold text-uppercase">Filiado</label>
                                  <select class="form-control form-control-lg bg-light border-0 shadow-sm js-select2-filiado" id="id_filiado_edit<?php echo $id; ?>" name="id_filiado" required>
                                    <?php foreach ($filiados as $f): ?>
                                      <option value="<?php echo $f['id_filiado']; ?>" <?php echo ($f['id_filiado'] == $id_filiado) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($f['nome']) . ' (Código: ' . htmlspecialchars($f['codigo'] ?? $f['id_filiado']) . ')'; ?>
                                      </option>
                                    <?php endforeach; ?>
                                  </select>
                                </div>

                                <div class="form-group mb-3">
                                  <label for="titulo_edit<?php echo $id; ?>" class="text-secondary small font-weight-bold text-uppercase">Título do Certificado</label>
                                  <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="titulo_edit<?php echo $id; ?>" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>" required>
                                </div>

                                <div class="form-group mb-0">
                                  <label for="data_emissao_edit<?php echo $id; ?>" class="text-secondary small font-weight-bold text-uppercase">Data de Emissão</label>
                                  <input type="date" class="form-control form-control-lg bg-light border-0 shadow-sm" id="data_emissao_edit<?php echo $id; ?>" name="data_emissao" value="<?php echo $data_emissao; ?>" required>
                                </div>
                              </div>

                              <div class="modal-footer border-0 bg-light py-3 d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Salvar Alterações</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>

                      <!-- Modal Excluir Certificado -->
                      <div class="modal fade" id="modalExcluirCertificado<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="excluirLabel<?php echo $id; ?>" aria-hidden="true">
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
                              <p class="lead mb-2">Tem certeza que deseja excluir o certificado de?</p>
                              <h5 class="font-weight-bold text-danger mb-2"><?php echo htmlspecialchars($nome); ?></h5>
                              <p class="text-secondary font-weight-bold mb-0">"<?php echo htmlspecialchars($titulo); ?>"</p>
                              <p class="text-muted mt-3 small">Esta ação não poderá ser desfeita.</p>
                            </div>
                            <div class="modal-footer border-0 bg-light py-3 text-center d-flex justify-content-center">
                              <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
                              <form action="../../controllers/certificadoManualController.php" method="post" class="d-inline">
                                <input type="hidden" name="tipo" value="excluir">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Excluir</button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
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

<!-- Modal Adicionar Certificado -->
<div class="modal fade" id="modalAdicionarCertificado" tabindex="-1" role="dialog" aria-labelledby="adicionarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-danger text-white border-0 py-3">
        <h5 class="modal-title font-weight-bold" id="adicionarLabel">
          <i class="fa-solid fa-certificate mr-2"></i> Emitir Certificado Manual
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="../../controllers/certificadoManualController.php" method="post">
        <input type="hidden" name="tipo" value="inserir">
        
        <div class="modal-body p-4">
          <div class="form-group mb-3">
            <label for="id_filiado" class="text-secondary small font-weight-bold text-uppercase">Selecionar Filiado</label>
            <select class="form-control form-control-lg bg-light border-0 shadow-sm js-select2-filiado" id="id_filiado" name="id_filiado" required>
              <option value="">Selecione um filiado...</option>
              <?php foreach ($filiados as $f): ?>
                <option value="<?php echo $f['id_filiado']; ?>">
                  <?php echo htmlspecialchars($f['nome']) . ' (Código: ' . htmlspecialchars($f['codigo'] ?? $f['id_filiado']) . ')'; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group mb-3">
            <label for="titulo" class="text-secondary small font-weight-bold text-uppercase">Título do Certificado</label>
            <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="titulo" name="titulo" placeholder="Ex: Certificado de Faixa Preta 1º Dan" required>
          </div>

          <div class="form-group mb-0">
            <label for="data_emissao" class="text-secondary small font-weight-bold text-uppercase">Data de Emissão</label>
            <input type="date" class="form-control form-control-lg bg-light border-0 shadow-sm" id="data_emissao" name="data_emissao" value="<?php echo date('Y-m-d'); ?>" required>
          </div>
        </div>

        <div class="modal-footer border-0 bg-light py-3 d-flex justify-content-end">
          <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Emitir Certificado</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        // Inicializa o DataTable para a tabela de certificados se não estiver inicializado
        if (!$.fn.DataTable.isDataTable('#tabelaCertificados')) {
            $('#tabelaCertificados').DataTable({
                "order": [[5, "desc"]], // Ordena por data de emissão decrescente
                "pageLength": 10,
                "searching": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
                }
            });
        }
    }

    if (typeof $ !== 'undefined' && $.fn.select2) {
        // Inicializar Select2 no modal de Adicionar quando for exibido
        $('#modalAdicionarCertificado').on('shown.bs.modal', function () {
            var $select = $('#id_filiado');
            if (!$select.hasClass('select2-hidden-accessible')) {
                $select.select2({
                    dropdownParent: $('#modalAdicionarCertificado'),
                    language: 'pt-BR',
                    width: '100%'
                });
            }
        });

        // Inicializar Select2 nos modals de Editar dinamicamente quando forem exibidos
        $('div[id^="modalEditarCertificado"]').on('shown.bs.modal', function () {
            var modalId = $(this).attr('id');
            var idSuffix = modalId.replace('modalEditarCertificado', '');
            var $select = $('#id_filiado_edit' + idSuffix);
            if (!$select.hasClass('select2-hidden-accessible')) {
                $select.select2({
                    dropdownParent: $('#' + modalId),
                    language: 'pt-BR',
                    width: '100%'
                });
            }
        });
    }
});
</script>
