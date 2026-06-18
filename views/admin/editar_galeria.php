<?php
include_once "menu.php";
include_once "../../models/galeriaModel.php";
include_once "../../models/fotoModel.php";

$id_galeria = $_GET["id"];

$galeriaRepo = new Galeria();
$galeria = $galeriaRepo->buscarGaleria($id_galeria);

if (isset($_SESSION["id_usuario"]) && isset($_GET["id"]) && $_SESSION['nivel'] == "admin") {
?>
  <div class="container">
    <div class="row">
      <!-- esquerda -->
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body">
            <h5 class="card-title text-center">Editar Galeria</h5>
            <form class="form-signin" action="../../controllers/galeriaController.php" method="post">
              <input type="hidden" name="tipo" value="editar_galeria">
              <input type="hidden" name="id_galeria" value="<?php echo $id_galeria; ?>">

              <div class="form-group">
                <label for="nome">Nome: </label>
                <input id="nome" type="text" class="form-control" value="<?php echo htmlspecialchars($galeria["nome"]); ?>" name="nome" required autofocus>
              </div>

              <input class="btn btn-lg btn-success btn-block text-uppercase" type="submit" value="Salvar">
            </form>
          </div>
        </div>
      </div>

      <!-- Direita -->
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body">
            <h5 class="card-title text-center">Fotos</h5>
            <a href="adicionar_foto.php?id=<?php echo $id_galeria; ?>" class="btn btn-outline-success btn-lg btn-block">Adicionar Foto</a>
            <table class="table table-hover">
              <thead>
                <tr>
                  <th scope="col">Foto</th>
                  <th scope="col">Nome</th>
                  <th scope="col">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $fotoRepo = new Foto();
                $fotos = $fotoRepo->buscarFotosPorGaleria($id_galeria);
                
                if (empty($fotos)) {
                  echo "<h3> Não foram encontradas fotos nesta galeria!! </h3>";
                } else {
                  foreach ($fotos as $foto) {
                ?>
                    <tr>
                      <th scope="row"><img src="../../slides/<?php echo $foto["foto"]; ?>" width="50" height="50"></th>
                      <td><?php echo $foto["nome"]; ?></td>
                      <td>
                        <div class="form-group">
                          <button class="btn btn-danger" data-toggle="modal" data-target="#modalExcluirFoto<?php echo $foto["id_foto"]; ?>" title="Excluir"><i class="fa-regular fa-trash-can"></i></button>
                        </div>
                      </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="modalExcluirFoto<?php echo $foto["id_foto"]; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Você tem certeza que deseja excluir:</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p><?php echo $foto["nome"]; ?></p>
                          </div>
                          <div class="modal-footer">
                            <form action="../../controllers/galeriaController.php" method="post">
                              <input type="hidden" name="tipo" value="excluir_foto">
                              <input type="hidden" name="id_foto" value="<?php echo $foto["id_foto"]; ?>">
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
      </div>
    </div>
  </div>

<?php
  include "../rodape.php";
} else {
  echo "<script language='javascript'>window.location='login.php'; </script>";
}
?>