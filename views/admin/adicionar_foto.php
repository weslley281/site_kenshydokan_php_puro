<?php
include_once "menu.php";
include_once "../../db/conexao.php";

$c = new Conexao();
$conexao = $c->conectar();

if (isset($_SESSION["id_usuario"]) && isset($_GET["id"]) && $_SESSION['nivel'] == "admin") {
    $id_galeria = $_GET["id"];
    
    $busca_galeria = "SELECT * FROM galeria WHERE id_galeria = ?";
    $stmt = mysqli_prepare($conexao, $busca_galeria);
    mysqli_stmt_bind_param($stmt, "i", $id_galeria);
    mysqli_stmt_execute($stmt);
    $resultado_galeria = mysqli_stmt_get_result($stmt);
    $galeria = mysqli_fetch_array($resultado_galeria);
    ?>
<div class="container">
  <div class="row">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
      <div class="card card-signin my-5">
        <div class="card-body">
          <h5 class="card-title text-center">Adicionar Foto na Galeria <?php echo htmlspecialchars($galeria['nome'], ENT_QUOTES, 'UTF-8'); ?></h5>
          <form class="form-signin" enctype="multipart/form-data" action="../../controllers/galeriaController.php" method="post">
            <input type="hidden" name="tipo" value="adicionar_foto">
            <input type="hidden" name="id_galeria" value="<?php echo $id_galeria; ?>">

            <div class="form-group">
              <label for="nome">Nome da foto (opcional): </label>
              <input id="nome" type="text" class="form-control" name="nome" autofocus>
            </div>

            <div class="form-group">
              <label for="foto">Foto: </label>
              <input type="file" class="form-control" id="foto" accept="image/*" required name="foto">

              <div class="text-center mt-2">
                <img src="#" class="img-thumbnail" alt="Prévia da Imagem" id="imagePreview" style="max-width: 100%; display: none;">
              </div>
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