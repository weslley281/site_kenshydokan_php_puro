<div class="tab-pane fade show active">
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Gerenciar Dojôs</h2>
      <a href="criar_dojo.php" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-4">
        <i class="fas fa-plus mr-2"></i> Adicionar Novo Dojô
      </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
      <div class="card-body p-4">
        <div class="table-responsive">
          <table id="minhaTabela3" class="table table-hover align-middle" width="100%" cellspacing="0">
            <thead>
              <tr class="text-secondary small font-weight-bold border-bottom">
                <th scope="col" style="width: 70px;">Logo</th>
                <th scope="col">Razão Social / Fantasia</th>
                <th scope="col">CNPJ</th>
                <th scope="col">Responsável</th>
                <th scope="col">Contato</th>
                <th scope="col">Status</th>
                <th scope="col" class="text-center" style="width: 120px;">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php
              require_once __DIR__ . '/../../models/dojoModel.php';
              require_once __DIR__ . '/../../models/filiadoModel.php';

              $dojoRepo = new DojoModel();
              $filiadoRepo = new FiliadoModel();
              $dojos = $dojoRepo->listarDojos();

              if (empty($dojos)) {
                echo '<tr><td colspan="7" class="text-center text-muted py-4">Nenhum dojô cadastrado no momento.</td></tr>';
              } else {
                foreach ($dojos as $dojo) {
                  $id_dojo = $dojo->getIdDojo();
                  $razao_social = $dojo->getRazaoSocial();
                  $nome_fantasia = $dojo->getNomeFantasia();
                  $cnpj = $dojo->getCnpj();
                  $id_responsavel = $dojo->getIdFiliadoResponsavel();
                  $telefone = $dojo->getTelefone();
                  $celular = $dojo->getCelular();
                  $status = $dojo->getStatus();
                  $imagem = $dojo->getImagem();

                  // Busca o nome do filiado responsável
                  $nome_responsavel = 'Não definido';
                  if ($id_responsavel) {
                    $responsavel = $filiadoRepo->buscarFiliadoPorId($id_responsavel);
                    if ($responsavel) {
                      $nome_responsavel = $responsavel->getNome();
                    }
                  }

                  $caminho_logo = !empty($imagem) ? '../../img/' . $imagem : '../../arquivos/sem_imagem.png';
                  ?>
                  <tr>
                    <td class="align-middle">
                      <img src="<?php echo $caminho_logo; ?>" alt="Logo <?php echo htmlspecialchars($nome_fantasia); ?>" class="rounded-circle border border-danger shadow-sm" style="width: 45px; height: 45px; object-fit: cover; border-width: 2px !important;">
                    </td>
                    <td class="align-middle font-weight-bold text-dark">
                      <?php echo htmlspecialchars($nome_fantasia); ?><br>
                      <small class="text-muted font-weight-normal"><?php echo htmlspecialchars($razao_social); ?></small>
                    </td>
                    <td class="align-middle text-muted small"><?php echo htmlspecialchars($cnpj); ?></td>
                    <td class="align-middle text-dark"><?php echo htmlspecialchars($nome_responsavel); ?></td>
                    <td class="align-middle text-dark small">
                      <?php echo htmlspecialchars(!empty($celular) ? $celular : (!empty($telefone) ? $telefone : 'N/A')); ?>
                    </td>
                    <td class="align-middle">
                      <span class="badge badge-<?php echo $status == 'ativo' ? 'success' : 'danger'; ?> text-uppercase px-2 py-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                        <?php echo $status == 'ativo' ? 'Ativo' : 'Inativo'; ?>
                      </span>
                    </td>
                    <td class="align-middle text-center">
                      <a href="editar_dojo.php?id=<?php echo $id_dojo; ?>" class="btn btn-sm btn-outline-primary border-0 rounded-circle mr-1" title="Editar Dojô" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </a>
                      <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" data-toggle="modal" data-target="#excluirModal<?php echo $id_dojo; ?>" title="Excluir Dojô" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-trash-can"></i>
                      </button>

                      <!-- Modal de Exclusão -->
                      <div class="modal fade" id="excluirModal<?php echo $id_dojo; ?>" tabindex="-1" role="dialog" aria-labelledby="excluirLabel<?php echo $id_dojo; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-danger text-white border-0 py-3">
                              <h5 class="modal-title font-weight-bold" id="excluirLabel<?php echo $id_dojo; ?>">
                                <i class="fa-solid fa-triangle-exclamation mr-2"></i> Confirmar Exclusão
                              </h5>
                              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body p-4 text-center">
                              <p class="lead mb-2">Tem certeza que deseja excluir este dojô?</p>
                              <h4 class="font-weight-bold text-danger mb-0"><?php echo htmlspecialchars($nome_fantasia); ?></h4>
                              <p class="text-muted mt-2 small">Esta ação não poderá ser desfeita.</p>
                            </div>
                            <div class="modal-footer border-0 bg-light py-3">
                              <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold" data-dismiss="modal">Cancelar</button>
                              <form action="../../controllers/dojoController.php" method="post" class="d-inline">
                                <input type="hidden" name="id_dojo" value="<?php echo $id_dojo; ?>">
                                <input type="hidden" name="tipo" value="excluir">
                                <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Excluir</button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <?php
                }
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>