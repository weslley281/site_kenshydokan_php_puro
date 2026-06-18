<?php
include_once "menu.php";
include_once "../../models/graduacaoModel.php";

$id_graduacao = $_GET["id"];

$graduacao = Graduacao::buscarGraduacao($id_graduacao);

if (isset($_SESSION["id_usuario"]) && isset($_GET["id"]) && $_SESSION['nivel'] == "admin") {
?>
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body">
            <h5 class="card-title text-center">Editar Graduação</h5>
            <form class="form-signin" action="../../controllers/graduacaoController.php" method="post">
              <input type="hidden" name="tipo" value="editar_graduacao">
              <input type="hidden" name="id_graduacao" value="<?php echo $id_graduacao; ?>">

              <div class="form-group">
                <label for="graduacao">Graduação: </label>
                <input id="graduacao" type="text" class="form-control" value="<?php echo $graduacao["graduacao"]; ?>" name="graduacao" required autofocus>
              </div>

              <input class="btn btn-lg btn-success btn-block text-uppercase" type="submit" value="Salvar">
            </form>
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