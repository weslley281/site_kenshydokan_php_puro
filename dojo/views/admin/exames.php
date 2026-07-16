<?php
// views/admin/exames.php
include_once __DIR__ . "/../../models/exameGraduacaoModel.php";
include_once __DIR__ . "/../../models/filiadoModel.php";
include_once __DIR__ . "/../../models/arteModel.php";
include_once __DIR__ . "/../../models/graduacaoModel.php";

$exameRepo = new ExameGraduacaoModel();
$filiadoRepo = new FiliadoModel();
$arteRepo = new ArteMarcial();
$gradRepo = new Graduacao();

$exames = $exameRepo->listarTodos();
$filiados = $filiadoRepo->listarFiliadosAtivos(); // filiados com confirmacao = 'confirmado'
$modalidades = $arteRepo->listarArtes();
$graduacoes = $gradRepo->listarGraduacoes();
?>

<div class="tab-pane fade show active">
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Gerenciar Exames / Graduações</h2>
      <button type="button" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-4" data-toggle="modal" data-target="#modalAdicionarExame">
        <i class="fas fa-plus mr-2"></i> Lançar Exame
      </button>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
      <div class="card-body p-4">
        <div class="table-responsive">
          <table id="tabelaExames" class="table table-hover align-middle" width="100%" cellspacing="0">
            <thead>
              <tr class="text-secondary small font-weight-bold border-bottom">
                <th scope="col" style="width: 80px;">Código</th>
                <th scope="col">Filiado</th>
                <th scope="col">Modalidade</th>
                <th scope="col">Graduação Atual</th>
                <th scope="col">Pretendida</th>
                <th scope="col" class="text-center">Nota</th>
                <th scope="col">Situação</th>
                <th scope="col" class="text-center" style="width: 120px;">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($exames as $ex): 
                $id = $ex['id'];
                $situacao = strtolower($ex['situacao']);
                $badge_class = ($situacao === 'aprovado') ? 'success' : (($situacao === 'reprovado') ? 'danger' : 'warning');
              ?>
                <tr>
                  <td class="align-middle font-weight-bold text-secondary">#<?php echo htmlspecialchars($ex['aluno_codigo'] ?? $ex['id_filiado']); ?></td>
                  <td class="align-middle font-weight-bold text-dark text-capitalize"><?php echo htmlspecialchars($ex['aluno_nome']); ?></td>
                  <td class="align-middle text-dark small text-capitalize"><?php echo htmlspecialchars($ex['modalidade']); ?></td>
                  <td class="align-middle text-muted small text-capitalize"><?php echo htmlspecialchars($ex['graduacao_atual']); ?></td>
                  <td class="align-middle text-dark font-weight-bold small text-capitalize"><?php echo htmlspecialchars($ex['graduacao_pretendida']); ?></td>
                  <td class="align-middle text-center font-weight-bold text-dark"><?php echo ($ex['nota'] !== null) ? number_format($ex['nota'], 2, ',', '.') : '-'; ?></td>
                  <td class="align-middle">
                    <span class="badge badge-<?php echo $badge_class; ?> text-uppercase px-2 py-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                      <?php echo htmlspecialchars($ex['situacao']); ?>
                    </span>
                  </td>
                  <td class="align-middle text-center">
                    <!-- Botão Editar -->
                    <button type="button" class="btn btn-sm btn-outline-primary border-0 rounded-circle mr-1" data-toggle="modal" data-target="#modalEditarExame<?php echo $id; ?>" title="Editar Exame" style="width: 32px; height: 32px; padding: 5px 0;">
                      <i class="fa-solid fa-pen-to-square"></i>
                    </button>

                    <!-- Botão Excluir -->
                    <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" data-toggle="modal" data-target="#modalExcluirExame<?php echo $id; ?>" title="Excluir Exame" style="width: 32px; height: 32px; padding: 5px 0;">
                      <i class="fa-solid fa-trash-can"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modais de Edição e Exclusão -->
<?php foreach ($exames as $ex): 
  $id = $ex['id'];
  $id_filiado = $ex['id_filiado'];
  $id_arte = $ex['id_arte'];
  $id_grad_atual = $ex['id_graduacao_atual'];
  $id_grad_pret = $ex['id_graduacao_pretendida'];
  $nota = $ex['nota'];
  $comentarios = $ex['comentarios'];
  $data_exame = $ex['data_exame'];
  $situacao = $ex['situacao'];
?>
  <!-- Modal Editar Exame -->
  <div class="modal fade" id="modalEditarExame<?php echo $id; ?>" role="dialog" aria-labelledby="editarExameLabel<?php echo $id; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content border-0 shadow-lg text-left">
        <div class="modal-header bg-danger text-white border-0 py-3">
          <h5 class="modal-title font-weight-bold" id="editarExameLabel<?php echo $id; ?>">
            <i class="fa-solid fa-pen-to-square mr-2"></i> Editar Registro de Exame
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="../../controllers/exameController.php" method="post">
          <input type="hidden" name="tipo" value="editar">
          <input type="hidden" name="id" value="<?php echo $id; ?>">
          
          <div class="modal-body p-4">
            <div class="form-group mb-3">
              <label for="id_filiado_edit<?php echo $id; ?>" class="text-secondary small font-weight-bold text-uppercase">Aluno / Filiado</label>
              <select class="form-control form-control-lg bg-light border-0 shadow-sm js-select2-filiado" id="id_filiado_edit<?php echo $id; ?>" name="id_filiado" required>
                <?php foreach ($filiados as $f): ?>
                  <option value="<?php echo $f['id_filiado']; ?>" <?php echo ($f['id_filiado'] == $id_filiado) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($f['nome']) . ' (Código: ' . htmlspecialchars($f['codigo'] ?? $f['id_filiado']) . ')'; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="row">
              <div class="col-md-6 form-group mb-3">
                <label for="id_arte_edit<?php echo $id; ?>" class="text-secondary small font-weight-bold text-uppercase">Modalidade</label>
                <select class="form-control form-control-lg bg-light border-0 shadow-sm" id="id_arte_edit<?php echo $id; ?>" name="id_arte" required>
                  <?php foreach ($modalidades as $m): ?>
                    <option value="<?php echo $m['id_arte']; ?>" <?php echo ($m['id_arte'] == $id_arte) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($m['nome']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-6 form-group mb-3">
                <label for="data_exame_edit<?php echo $id; ?>" class="text-secondary small font-weight-bold text-uppercase">Data Exame</label>
                <input type="date" class="form-control form-control-lg bg-light border-0 shadow-sm" id="data_exame_edit<?php echo $id; ?>" name="data_exame" value="<?php echo $data_exame; ?>" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 form-group mb-3">
                <label for="id_graduacao_atual_edit<?php echo $id; ?>" class="text-secondary small font-weight-bold text-uppercase">Graduação Atual</label>
                <select class="form-control form-control-lg bg-light border-0 shadow-sm" id="id_graduacao_atual_edit<?php echo $id; ?>" name="id_graduacao_atual" required>
                  <?php foreach ($graduacoes as $g): ?>
                    <option value="<?php echo $g['id_graduacao']; ?>" <?php echo ($g['id_graduacao'] == $id_grad_atual) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($g['graduacao']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-6 form-group mb-3">
                <label for="id_graduacao_pretendida_edit<?php echo $id; ?>" class="text-secondary small font-weight-bold text-uppercase">Pretendida</label>
                <select class="form-control form-control-lg bg-light border-0 shadow-sm" id="id_graduacao_pretendida_edit<?php echo $id; ?>" name="id_graduacao_pretendida" required>
                  <?php foreach ($graduacoes as $g): ?>
                    <option value="<?php echo $g['id_graduacao']; ?>" <?php echo ($g['id_graduacao'] == $id_grad_pret) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($g['graduacao']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 form-group mb-3">
                <label for="nota_edit<?php echo $id; ?>" class="text-secondary small font-weight-bold text-uppercase">Nota (Opcional)</label>
                <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="nota_edit<?php echo $id; ?>" name="nota" value="<?php echo ($nota !== null) ? number_format($nota, 2, ',', '.') : ''; ?>" placeholder="Ex: 8,50">
              </div>

              <div class="col-md-6 form-group mb-3">
                <label for="situacao_edit<?php echo $id; ?>" class="text-secondary small font-weight-bold text-uppercase">Situação</label>
                <select class="form-control form-control-lg bg-light border-0 shadow-sm" id="situacao_edit<?php echo $id; ?>" name="situacao" required>
                  <option value="pendente" <?php echo ($situacao === 'pendente') ? 'selected' : ''; ?>>Pendente</option>
                  <option value="aprovado" <?php echo ($situacao === 'aprovado') ? 'selected' : ''; ?>>Aprovado</option>
                  <option value="reprovado" <?php echo ($situacao === 'reprovado') ? 'selected' : ''; ?>>Reprovado</option>
                </select>
              </div>
            </div>

            <div class="form-group mb-0">
              <label for="comentarios_edit<?php echo $id; ?>" class="text-secondary small font-weight-bold text-uppercase">Observações / Feedback</label>
              <textarea class="form-control bg-light border-0 shadow-sm" id="comentarios_edit<?php echo $id; ?>" name="comentarios" rows="3" placeholder="Comentários sobre o desempenho do aluno..."><?php echo htmlspecialchars($comentarios); ?></textarea>
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

  <!-- Modal Excluir Exame -->
  <div class="modal fade" id="modalExcluirExame<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="excluirExameLabel<?php echo $id; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content border-0 shadow-lg text-left">
        <div class="modal-header bg-danger text-white border-0 py-3">
          <h5 class="modal-title font-weight-bold" id="excluirExameLabel<?php echo $id; ?>">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i> Confirmar Exclusão
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4 text-center">
          <p class="lead mb-2">Tem certeza que deseja excluir o registro de exame de?</p>
          <h5 class="font-weight-bold text-danger mb-2"><?php echo htmlspecialchars($ex['aluno_nome']); ?></h5>
          <p class="text-secondary font-weight-bold mb-0">Modalidade: <?php echo htmlspecialchars($ex['modalidade']); ?></p>
          <p class="text-muted mt-3 small">Esta ação não poderá ser desfeita e não removerá a graduação atual do aluno caso ele já tenha sido aprovado.</p>
        </div>
        <div class="modal-footer border-0 bg-light py-3 text-center d-flex justify-content-center">
          <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
          <form action="../../controllers/exameController.php" method="post" class="d-inline">
            <input type="hidden" name="tipo" value="excluir">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Excluir</button>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<!-- Modal Adicionar Exame -->
<div class="modal fade" id="modalAdicionarExame" role="dialog" aria-labelledby="adicionarExameLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-danger text-white border-0 py-3">
        <h5 class="modal-title font-weight-bold" id="adicionarExameLabel">
          <i class="fa-solid fa-medal mr-2"></i> Registrar Exame de Faixa
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="../../controllers/exameController.php" method="post">
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

          <div class="row">
            <div class="col-md-6 form-group mb-3">
              <label for="id_arte" class="text-secondary small font-weight-bold text-uppercase">Modalidade</label>
              <select class="form-control form-control-lg bg-light border-0 shadow-sm" id="id_arte" name="id_arte" required>
                <option value="">Selecione...</option>
                <?php foreach ($modalidades as $m): ?>
                  <option value="<?php echo $m['id_arte']; ?>"><?php echo htmlspecialchars($m['nome']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label for="data_exame" class="text-secondary small font-weight-bold text-uppercase">Data do Exame</label>
              <input type="date" class="form-control form-control-lg bg-light border-0 shadow-sm" id="data_exame" name="data_exame" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 form-group mb-3">
              <label for="id_graduacao_atual" class="text-secondary small font-weight-bold text-uppercase">Graduação Atual</label>
              <select class="form-control form-control-lg bg-light border-0 shadow-sm" id="id_graduacao_atual" name="id_graduacao_atual" required>
                <option value="">Selecione...</option>
                <?php foreach ($graduacoes as $g): ?>
                  <option value="<?php echo $g['id_graduacao']; ?>"><?php echo htmlspecialchars($g['graduacao']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label for="id_graduacao_pretendida" class="text-secondary small font-weight-bold text-uppercase">Pretendida</label>
              <select class="form-control form-control-lg bg-light border-0 shadow-sm" id="id_graduacao_pretendida" name="id_graduacao_pretendida" required>
                <option value="">Selecione...</option>
                <?php foreach ($graduacoes as $g): ?>
                  <option value="<?php echo $g['id_graduacao']; ?>"><?php echo htmlspecialchars($g['graduacao']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 form-group mb-3">
              <label for="nota" class="text-secondary small font-weight-bold text-uppercase">Nota (Opcional)</label>
              <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="nota" name="nota" placeholder="Ex: 8,50">
            </div>

            <div class="col-md-6 form-group mb-3">
              <label for="situacao" class="text-secondary small font-weight-bold text-uppercase">Situação Inicial</label>
              <select class="form-control form-control-lg bg-light border-0 shadow-sm" id="situacao" name="situacao" required>
                <option value="pendente">Pendente</option>
                <option value="aprovado">Aprovado</option>
                <option value="reprovado">Reprovado</option>
              </select>
            </div>
          </div>

          <div class="form-group mb-0">
            <label for="comentarios" class="text-secondary small font-weight-bold text-uppercase">Observações / Feedback</label>
            <textarea class="form-control bg-light border-0 shadow-sm" id="comentarios" name="comentarios" rows="3" placeholder="Feedback sobre o desempenho..."></textarea>
          </div>
        </div>

        <div class="modal-footer border-0 bg-light py-3 d-flex justify-content-end">
          <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Registrar Exame</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        if (!$.fn.DataTable.isDataTable('#tabelaExames')) {
            $('#tabelaExames').DataTable({
                "order": [[7, "desc"]], 
                "pageLength": 10,
                "searching": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
                }
            });
        }
    }

    if (typeof $ !== 'undefined' && $.fn.select2) {
        // Desativa a imposição de foco do Bootstrap modal para evitar loops infinitos de foco com o Select2
        if ($.fn.modal && $.fn.modal.Constructor) {
            $.fn.modal.Constructor.prototype._enforceFocus = function() {};
        }

        // Inicializar Select2 no modal de Adicionar quando for exibido
        $('#modalAdicionarExame').on('shown.bs.modal', function () {
            var $select = $('#id_filiado');
            if (!$select.hasClass('select2-hidden-accessible')) {
                $select.select2({
                    dropdownParent: $('#modalAdicionarExame'),
                    language: 'pt-BR',
                    width: '100%'
                });
            }
        });

        // Inicializar Select2 nos modals de Editar dinamicamente quando forem exibidos
        $('div[id^="modalEditarExame"]').on('shown.bs.modal', function () {
            var modalId = $(this).attr('id');
            var idSuffix = modalId.replace('modalEditarExame', '');
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
