<?php
include_once "menu.php";
include_once "../db/conexao.php";

$c = new Conexao();
$conexao = $c->conectar();

$id_curso = $_GET["id"];

if (isset($_SESSION["id_usuario"]) && isset($_GET["id"]) && $_SESSION['nivel'] == "admin") {
    ?>
<div class="container">
  <div class="row">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
      <div class="card card-signin my-5">
        <div class="card-body">
          <h5 class="card-title text-center">Editar Aula</h5>
          <form class="form-signin" enctype="multipart/form-data" action="../controllers/cursoController.php" method="post">
            <input type="hidden" name="tipo" value="inserir">
            <input type="hidden" name="id_curso" value="<?php echo $_GET["id"]; ?>">

            <div class="form-group">
              <label for="titulo">Nome: </label>
              <input id="titulo" type="text" class="form-control" name="titulo" required autofocus>
            </div>

            <div class="form-group">
              <label for="link">link da Aula: </label>
              <input id="link" type="text" class="form-control" name="link required autofocus">
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