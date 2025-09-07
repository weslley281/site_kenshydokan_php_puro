<?php
include_once "menu.php";
include_once "../../db/conexao.php";

$c = new Conexao();
$conexao = $c->conectar();

$id_aula = $_GET["id"];

$busca = "SELECT * FROM aulas WHERE id_aula = ?";
$stmt = mysqli_prepare($conexao, $busca);
mysqli_stmt_bind_param($stmt, "i", $id_aula);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$aula = mysqli_fetch_array($resultado);

if (isset($_SESSION["id_usuario"]) && isset($_GET["id"]) && $_SESSION['nivel'] == "admin") {
?>
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body">
            <h5 class="card-title text-center">Editar Aula</h5>
            <form class="form-signin" enctype="multipart/form-data" action="../../controllers/aulaController.php" method="post">
              <input type="hidden" name="tipo" value="editar">
              <input type="hidden" name="id_aula" value="<?php echo $_GET["id"]; ?>">
              <input type="hidden" name="id_curso" value="<?php echo $_GET["id_curso"]; ?>">

              <div class="form-group">
                <label for="titulo">Nome: </label>
                <input id="titulo" type="text" class="form-control" value="<?php echo $aula["titulo"]; ?>" name="titulo" required autofocus>
              </div>

              <div class="form-group">
                <label for="aula">Aula: </label>
                <textarea id="aula" class="form-control" name="aula" rows="30" required><?php echo $aula["aula"]; ?></textarea>
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