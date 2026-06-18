<?php
include_once "menu.php";
include_once "../../db/conexao.php";
include_once "../../models/cursoModel.php";

$c = new Conexao();
$conexao = $c->conectar();

if (isset($_SESSION["id_usuario"]) && isset($_GET["id"]) && $_SESSION['nivel'] == "admin") {
  $id_curso = $_GET["id"];
  $cursoRepo = new CursoModel();
  $curso = $cursoRepo->buscarCurso($id_curso);
?>
  <div class="container">
    <div class="row">
      <div class="col-sm-12 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body">
            <h5 class="card-title text-center">Criar Aula para o curso <?php echo htmlspecialchars($curso['nome'], ENT_QUOTES, 'UTF-8'); ?></h5>
            <form class="form-signin" enctype="multipart/form-data" action="/controllers/aulaController.php" method="post">
              <input type="hidden" name="tipo" value="inserir">
              <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>">

              <div class="form-group">
                <label for="titulo">Nome: </label>
                <input id="titulo" type="text" class="form-control" name="titulo" required autofocus>
              </div>

              <div class="form-group">
                <label for="aula">Aula: </label>
                <textarea id="aula" class="form-control" name="aula" rows="30"></textarea>
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