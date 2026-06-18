<div class="tab-pane fade show active">
  <div class="container text-center">
    <h2>Todas as Galerias</h2>
    <div class="row my-4">
      <div class="col mx-1 my-1">
        <a href="criar_galeria.php" class="btn btn-outline-success btn-lg btn-block">Criar Galeria</a>
      </div>
    </div>
    <table id="minhaTabela3" class="table table-bordered" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th scope="col">Nome</th>
          <th scope="col">Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php
        include_once __DIR__ . "/../../models/galeriaModel.php";
        
        $galeriaModelRepo = new Galeria();
        $galerias = $galeriaModelRepo->buscarGaleriasComFotos(); // Reuse method

        if (empty($galerias)) {
          echo "<h3> Não foram encontrados dados Cadastrados no Banco!! </h3>";
        } else {
          foreach ($galerias as $res_galeria) {
            $id_galeria = $res_galeria["id_galeria"];
            $nome = $res_galeria["nome"];
        ?>
            <tr>
              <td class="text-capitalize"><?php echo $nome; ?></td>
              <td class="text-capitalize">
                <div class="form-group">
                  <a class="btn btn-success" href="adicionar_foto.php?id=<?php echo $id_galeria; ?>" title="Adicionar Fotos"><i class="fa-solid fa-photo-film"></i></a>
                </div>
                <div class="form-group">
                  <a class="btn btn-primary" href="editar_galeria.php?id=<?php echo $id_galeria; ?>" title="Editar Galeria"><i class="fa-solid fa-pen-to-square"></i></a>
                </div>
                <div class="form-group">
                  <button class="btn btn-danger" data-toggle="modal" data-target="#modalExcluirGaleria<?php echo $id_galeria; ?>" title="Excluir Galeria"><i class="fa-regular fa-trash-can"></i></button>
                </div>
              </td>
            </tr>

            <!-- Modal -->
            <div class="modal fade" id="modalExcluirGaleria<?php echo $id_galeria; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Você tem certeza que deseja excluir:</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <p><?php echo $nome; ?></p>
                  </div>
                  <div class="modal-footer">
                    <form action="../controllers/galeriaController.php" method="post">
                      <input type="hidden" name="tipo" value="excluir_galeria">
                      <input type="hidden" name="id_galeria" value="<?php echo $id_galeria; ?>">

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