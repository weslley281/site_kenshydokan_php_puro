<div class="tab-pane fade show active">
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Gerenciar Cursos</h2>
      <div>
        <a href="criar_categoria.php" class="btn btn-outline-danger font-weight-bold rounded-pill shadow-sm px-4 mr-2">
          <i class="fas fa-tags mr-2"></i> Nova Categoria
        </a>
        <a href="criar_curso.php" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-4">
          <i class="fas fa-plus mr-2"></i> Adicionar Curso
        </a>
      </div>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
      <div class="card-body p-4">
        <div class="table-responsive">
          <table id="minhaTabela3" class="table table-hover align-middle" width="100%" cellspacing="0">
            <thead>
              <tr class="text-secondary small font-weight-bold border-bottom">
                <th scope="col" style="width: 80px;">Imagem</th>
                <th scope="col">Nome</th>
                <th scope="col">Professor</th>
                <th scope="col">Situação</th>
                <th scope="col">Criação</th>
                <th scope="col">Alteração</th>
                <th scope="col" class="text-center" style="width: 180px;">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php
              include_once __DIR__ . "/../../models/cursoModel.php";
              include_once __DIR__ . "/../../models/imagemModel.php";

              $cursos = CursoModel::buscarTodosCursos();
              if (empty($cursos)) {
                echo '<tr><td colspan="7" class="text-center text-muted py-4">Nenhum curso cadastrado no momento.</td></tr>';
              } else {
                foreach ($cursos as $res_curso) {
                  $id_curso = $res_curso["id_curso"];
                  $id_imagem = $res_curso["id_imagem"];
                  $nome = $res_curso["nome"];
                  $status = strtolower($res_curso["situacao"]);
                  $professor = $res_curso["professor"];
                  $dataCriacao = $res_curso["dataCriacao"];
                  $dataMudanca = $res_curso["dataMudanca"];

                  $res_imagem = Imagem::procura_imagem($id_imagem);
                  $caminho_imagem = '../../arquivos/sem_imagem.png';
                  $nome_imagem = 'Sem imagem';
                  if ($res_imagem) {
                    $nome_imagem = $res_imagem["nome"];
                    $caminho_imagem = $res_imagem["caminho"];
                    if (strpos($caminho_imagem, '../img/') === 0) {
                      $caminho_imagem = '../../img/' . substr($caminho_imagem, 7);
                    }
                  }

                  // Resolve o badge do status
                  $badge_class = 'secondary';
                  if ($status == 'ativo' || $status == 'confirmado' || $status == 'publicado') {
                    $badge_class = 'success';
                  } elseif ($status == 'inativo' || $status == 'desativado') {
                    $badge_class = 'danger';
                  }
              ?>
                  <tr>
                    <td class="align-middle">
                      <img src="<?php echo htmlspecialchars($caminho_imagem); ?>" alt="<?php echo htmlspecialchars($nome_imagem); ?>" class="rounded shadow-sm border" style="width: 45px; height: 45px; object-fit: cover;">
                    </td>
                    <td class="align-middle font-weight-bold text-dark text-capitalize"><?php echo htmlspecialchars($nome); ?></td>
                    <td class="align-middle text-dark text-capitalize"><?php echo htmlspecialchars($professor); ?></td>
                    <td class="align-middle">
                      <span class="badge badge-<?php echo $badge_class; ?> text-uppercase px-2 py-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                        <?php echo htmlspecialchars($status); ?>
                      </span>
                    </td>
                    <td class="align-middle text-muted small"><?php echo date_format(date_create($dataCriacao), "d/m/Y"); ?></td>
                    <td class="align-middle text-muted small"><?php echo date_format(date_create($dataMudanca), "d/m/Y"); ?></td>
                    <td class="align-middle text-center">
                      <a href="" class="btn btn-sm btn-outline-success border-0 rounded-circle mr-1" title="Emitir Certificado" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-graduation-cap"></i>
                      </a>
                      <a href="criar_aula.php?id=<?php echo $id_curso; ?>" class="btn btn-sm btn-outline-info border-0 rounded-circle mr-1" title="Adicionar Aulas" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-person-chalkboard"></i>
                      </a>
                      <a href="editar_curso.php?id=<?php echo $id_curso; ?>" class="btn btn-sm btn-outline-primary border-0 rounded-circle mr-1" title="Editar Curso" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </a>
                      <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" data-toggle="modal" data-target="#modalExcluirCurso<?php echo $id_curso; ?>" title="Excluir Curso" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-trash-can"></i>
                      </button>

                      <!-- Modal de Exclusão -->
                      <div class="modal fade" id="modalExcluirCurso<?php echo $id_curso; ?>" tabindex="-1" role="dialog" aria-labelledby="excluirLabel<?php echo $id_curso; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-danger text-white border-0 py-3">
                              <h5 class="modal-title font-weight-bold" id="excluirLabel<?php echo $id_curso; ?>">
                                <i class="fa-solid fa-triangle-exclamation mr-2"></i> Confirmar Exclusão
                              </h5>
                              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body p-4 text-center">
                              <p class="lead mb-2">Tem certeza que deseja excluir este curso?</p>
                              <h5 class="font-weight-bold text-danger mb-0"><?php echo htmlspecialchars($nome); ?></h5>
                              <p class="text-muted mt-2 small">Esta ação não poderá ser desfeita.</p>
                            </div>
                            <div class="modal-footer border-0 bg-light py-3 text-center d-flex justify-content-center">
                              <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
                              <form action="../../controllers/cursoController.php" method="post" class="d-inline">
                                <input type="hidden" name="tipo" value="excluir">
                                <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>">
                                <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Excluir</button>
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