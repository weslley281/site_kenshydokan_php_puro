<div class="tab-pane fade show active">
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Gerenciar Filiados</h2>
      <a href="criar_filiado.php" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-4">
        <i class="fas fa-plus mr-2"></i> Adicionar Filiado
      </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
      <div class="card-body p-4">
        <div class="table-responsive">
          <table id="minhaTabela4" class="table table-hover align-middle" width="100%" cellspacing="0">
            <thead>
              <tr class="text-secondary small font-weight-bold border-bottom">
                <th scope="col" style="width: 80px;">Código</th>
                <th scope="col">Nome</th>
                <th scope="col">Graduação</th>
                <th scope="col">Dojô</th>
                <th scope="col">Cidade/UF</th>
                <th scope="col">Confirmação</th>
                <th scope="col">Criação</th>
                <th scope="col" class="text-center" style="width: 150px;">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php
              include_once __DIR__ . "/../../models/filiadoModel.php";
              include_once __DIR__ . "/../../models/graduacaoModel.php";
              include_once __DIR__ . "/../../models/estadoModel.php";

              $estadoModel = new EstadoModel();
              $filiadoModelRepo = new FiliadoModel();
              $filiadosData = $filiadoModelRepo->listarTodosFiliadosCompleto();
              
              if (empty($filiadosData)) {
                echo '<tr><td colspan="8" class="text-center text-muted py-4">Nenhum filiado cadastrado no momento.</td></tr>';
              } else {
                foreach ($filiadosData as $res_filiado) {
                  $id_filiado = $res_filiado["id_filiado"];
                  $nome = $res_filiado["nome"];
                  $id_graduacao = $res_filiado["id_graduacao"];
                  $dojo = $res_filiado["dojo"];
                  $email = $res_filiado["email"];
                  $telefone = $res_filiado["telefone"];
                  $cidade = $res_filiado["cidade"];
                  $id_estado = $res_filiado["id_estado"];
                  $confirmacao = strtolower($res_filiado["confirmacao"]);
                  $dataCriacao = $res_filiado["dataCriacao"];
                  $dataMudanca = $res_filiado["dataMudanca"];

                  $res_graduacao = Graduacao::buscarGraduacao($id_graduacao);
                  $graduacao = $res_graduacao ? $res_graduacao["graduacao"] : "Sem graduação";

                  $estado = $estadoModel->buscarPorId($id_estado);
                  $uf = ($estado && !empty($estado["estado"])) ? htmlspecialchars($estado["estado"]) : "";
                  $localidade = htmlspecialchars($cidade ?? '') . (!empty($uf) ? '/' . $uf : '');

                  // Resolve o badge de confirmação
                  $badge_class = ($confirmacao == 'sim') ? 'success' : 'warning';
                  $label_conf = ($confirmacao == 'sim') ? 'Confirmado' : 'Pendente';
              ?>
                  <tr>
                    <td class="align-middle font-weight-bold text-secondary">#<?php echo $id_filiado; ?></td>
                    <td class="align-middle font-weight-bold text-dark text-capitalize"><?php echo htmlspecialchars($nome ?? ''); ?></td>
                    <td class="align-middle text-dark small text-capitalize"><?php echo htmlspecialchars($graduacao ?? ''); ?></td>
                    <td class="align-middle text-dark small text-capitalize"><?php echo htmlspecialchars($dojo ?? ''); ?></td>
                    <td class="align-middle text-dark small text-capitalize"><?php echo $localidade; ?></td>
                    <td class="align-middle">
                      <span class="badge badge-<?php echo $badge_class; ?> text-uppercase px-2 py-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                        <?php echo $label_conf; ?>
                      </span>
                    </td>
                    <td class="align-middle text-muted small"><?php echo date_format(date_create($dataCriacao), "d/m/Y"); ?></td>
                    <td class="align-middle text-center">
                      <a href="editar_filiado.php?id=<?php echo $id_filiado; ?>" class="btn btn-sm btn-outline-primary border-0 rounded-circle mr-1" title="Editar Filiado" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </a>
                      
                      <?php if ($confirmacao == 'sim'): ?>
                        <button type="button" class="btn btn-sm btn-outline-warning border-0 rounded-circle mr-1" data-toggle="modal" data-target="#modalDesconfirmarFiliado<?php echo $id_filiado; ?>" title="Desconfirmar Filiado" style="width: 32px; height: 32px; padding: 5px 0;">
                          <i class="fa-solid fa-user-xmark"></i>
                        </button>
                      <?php endif; ?>

                      <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" data-toggle="modal" data-target="#modalExcluirFiliado<?php echo $id_filiado; ?>" title="Excluir Filiado" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-trash-can"></i>
                      </button>

                      <!-- Modal Excluir -->
                      <div class="modal fade" id="modalExcluirFiliado<?php echo $id_filiado; ?>" tabindex="-1" role="dialog" aria-labelledby="excluirLabel<?php echo $id_filiado; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-danger text-white border-0 py-3">
                              <h5 class="modal-title font-weight-bold" id="excluirLabel<?php echo $id_filiado; ?>">
                                <i class="fa-solid fa-triangle-exclamation mr-2"></i> Confirmar Exclusão
                              </h5>
                              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body p-4 text-center">
                              <p class="lead mb-2">Tem certeza que deseja excluir este filiado?</p>
                              <h5 class="font-weight-bold text-danger mb-0"><?php echo htmlspecialchars($nome ?? ''); ?></h5>
                              <p class="text-muted mt-2 small">Esta ação não poderá ser desfeita.</p>
                            </div>
                            <div class="modal-footer border-0 bg-light py-3 text-center d-flex justify-content-center">
                              <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
                              <form action="../../controllers/filiadoController.php" method="post" class="d-inline">
                                <input type="hidden" name="tipo" value="excluir">
                                <input type="hidden" name="id_filiado" value="<?php echo $id_filiado; ?>">
                                <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Excluir</button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Modal Desconfirmar -->
                      <div class="modal fade" id="modalDesconfirmarFiliado<?php echo $id_filiado; ?>" tabindex="-1" role="dialog" aria-labelledby="desconfirmarLabel<?php echo $id_filiado; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-warning text-dark border-0 py-3">
                              <h5 class="modal-title font-weight-bold" id="desconfirmarLabel<?php echo $id_filiado; ?>">
                                <i class="fa-solid fa-user-slash mr-2"></i> Desconfirmar Filiado
                              </h5>
                              <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body p-4 text-center">
                              <p class="lead mb-2">Tem certeza que deseja remover a confirmação deste filiado?</p>
                              <h5 class="font-weight-bold text-warning mb-0"><?php echo htmlspecialchars($nome ?? ''); ?></h5>
                              <p class="text-muted mt-2 small">O status do filiado retornará para pendente.</p>
                            </div>
                            <div class="modal-footer border-0 bg-light py-3 text-center d-flex justify-content-center">
                              <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
                              <form action="../../controllers/filiadoController.php" method="post" class="d-inline">
                                <input type="hidden" name="tipo" value="desconfirmar">
                                <input type="hidden" name="id_filiado" value="<?php echo $id_filiado; ?>">
                                <button type="submit" class="btn btn-warning px-4 rounded-pill font-weight-bold shadow-sm text-dark">Desconfirmar</button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
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