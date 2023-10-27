<?php
include_once "menu.php";
include_once "../db/conexao.php";

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {
    ?>
<!-- /Navigation -->

<div class="container">
  <div class="row">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
      <div class="card card-signin my-5">
        <div class="card-body">
          <h5 class="card-title text-center">Criar Categoria de Curso</h5>
          <form class="form-signin" enctype="multipart/form-data" action="../controllers/categoriaController.php" method="post">
            <input type="hidden" name="tipo" value="inserir">

            <div class="form-group">
              <label for="categoria">Categoria: </label>
              <input id="categoria" type="text" class="form-control" name="categoria" required autofocus>
            </div>

            <input class="btn btn-lg btn-success btn-block text-uppercase" type="submit" value="Salvar">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
include "rodape.php";
} else {
    echo "<script language='javascript'>window.location='login.php'; </script>";
}
?>