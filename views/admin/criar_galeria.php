<?php
include_once "menu.php";

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {
    ?>
<div class="container">
  <div class="row">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
      <div class="card card-signin my-5">
        <div class="card-body">
          <h5 class="card-title text-center">Criar Galeria</h5>
          <form class="form-signin" action="../../controllers/galeriaController.php" method="post">
            <input type="hidden" name="tipo" value="criar_galeria">

            <div class="form-group">
              <label for="nome">Nome: </label>
              <input id="nome" type="text" class="form-control" name="nome" required autofocus>
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