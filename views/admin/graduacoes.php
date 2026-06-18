<div class="tab-pane fade show active">
  <div class="container text-center">
    <h2>Todas as Graduações</h2>
    <div class="row my-4">
      <div class="col mx-1 my-1">
        <a href="criar_graduacao.php" class="btn btn-outline-success btn-lg btn-block">Criar Graduação</a>
      </div>
    </div>
    <table id="minhaTabela3" class="table table-bordered" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th scope="col">Graduação</th>
          <th scope="col">Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php
        include_once __DIR__ . "/../../models/graduacaoModel.php";
        
        $graduacaoModelRepo = new Graduacao();
        $graduacoes = $graduacaoModelRepo->listarGraduacoes();

        if (empty($graduacoes)) {
          echo "<h3> Não foram encontrados dados Cadastrados no Banco!! </h3>";
        } else {
          foreach ($graduacoes as $res_graduacao) {
            $id_graduacao = $res_graduacao["id_graduacao"];
            $graduacao = $res_graduacao["graduacao"];
        ?>
            <tr>
              <td class="text-capitalize"><?php echo $graduacao; ?></td>
              <td class="text-capitalize">
                <div class="form-group">
                  <a class="btn btn-primary" href="editar_graduacao.php?id=<?php echo $id_graduacao; ?>" title="Editar Graduação"><i class="fa-solid fa-pen-to-square"></i></a>
                </div>
                <div class="form-group">
                  <button class="btn btn-danger" data-toggle="modal" data-target="#modalExcluirGraduacao<?php echo $id_graduacao; ?>" title="Excluir Graduação"><i class="fa-regular fa-trash-can"></i></button>
                </div>
              </td>
            </tr>

            <!-- Modal -->
            <div class="modal fade" id="modalExcluirGraduacao<?php echo $id_graduacao; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Você tem certeza que deseja excluir:</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <p><?php echo $graduacao; ?></p>
                  </div>
                  <div class="modal-footer">
                    <form action="../controllers/graduacaoController.php" method="post">
                      <input type="hidden" name="tipo" value="excluir_graduacao">
                      <input type="hidden" name="id_graduacao" value="<?php echo $id_graduacao; ?>">

                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                      <button type="submit" class="btn btn-danger">Sim</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
        <?php } 
        } ?>
      </tbody>
    </table>
  </div>
</div>