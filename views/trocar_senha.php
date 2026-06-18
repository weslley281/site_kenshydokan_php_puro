<?php include "menu.php";?>

<body>

  <!-- Navigation -->
  <?php include "menu.php";?>

  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body">
            <h5 class="card-title text-center">Trocar a Senha</h5>
            <?php
include_once "../models/usuarioModel.php";
$id_usuario = $_GET["id_usuario"];
$usuarioModelRepo = new Usuario();
$res = $usuarioModelRepo->buscarUsuario($id_usuario);
if ($res) {
    $id_usuario = $res["id_usuario"];
    $nome = $res["nome"];
}
?>
            <form class="form-signin" action="../controllers/registrar_usuario.php" method="post">
              <input type="hidden" id="id_usuario" class="form-control" value="<?php echo $id_usuario; ?>" name="id_usuario">
              <div class="form-label-group">
                <input type="password" id="senha1" class="form-control" placeholder="Senha" name="senha1" required autofocus>
                <label for="senha1">Digite a Nova Senha</label>
              </div>

              <div class="form-label-group">
                <input type="password" id="senha2" class="form-control" placeholder="Senha" name="senha2" required autofocus>
                <label for="senha2">Repita a Nova Senha</label>
              </div>

              <input class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" name="trocar" value="trocar">
              <hr class="my-4">
              <a href="login.php">Voltar</a>
              <!--
              <button class="btn btn-lg btn-google btn-block text-uppercase" type="submit"><i class="fab fa-google mr-2"></i> Entrar com Google</button>
              <button class="btn btn-lg btn-facebook btn-block text-uppercase" type="submit"><i class="fab fa-facebook-f mr-2"></i> Entrar com Facebook</button>
            -->
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php include "rodape.php";?>
