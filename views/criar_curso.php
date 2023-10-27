<?php
include_once "menu.php";
include_once "../db/conexao.php";

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {
    ?>
<div class="container">
  <div class="row">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
      <div class="card card-signin my-5">
        <div class="card-body">
          <h5 class="card-title text-center">Criar Curso</h5>
          <form class="form-signin" enctype="multipart/form-data" action="../controllers/usuarioController.php" method="post">
            <input type="hidden" name="tipo" value="edidar_admin">
            <input type="hidden" name="id_imagem" value="<?php echo $id_imagem; ?>">
            <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">

            <div class="form-group">
              <label for="nome">Nome: </label>
              <input id="nome" type="text" class="form-control" name="nome" required autofocus>
            </div>

            <div class="form-group">
              <label for="id_categoria">Categoria: </label>
                <select id="id_categoria" class="form-select form-control js-example-basic-single" aria-label="Default select example" name="id_categoria">
            <?php
$consulta = "SELECT id_categoria, categoria FROM categorias";
    $resultado = mysqli_query($conexao, $consulta);
    if ($resultado) {
        while ($dado = mysqli_fetch_array($resultado)) {
            echo '<option value="' . $dado["id_categoria"] . '">' . $dado["categoria"] . '</option>';
        }
    } else {
        echo '<option>Erro ao carregar os dados</option>';
    }
    ?>
                </select>
            </div>

            <div class="form-group">
              <label for="descricao">Descrição: </label>
              <input id="descricao" type="text" class="form-control" name="descricao" required autofocus>
            </div>

            <div class="form-group">
              <label for="professor">Professor: </label>
              <input id="professor" type="text" class="form-control" name="professor" required autofocus>
            </div>

            <div class="form-group">
              <label for="imagem">Imagem de Perfil</label>
                <input type="file" class="form-control" id="imagem" accept="image/*" required name="imagem">

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
include "rodape.php";
} else {
    echo "<script language='javascript'>window.location='login.php'; </script>";
}
?>