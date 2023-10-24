<?php
include "menu.php";
include_once "../db/conexao.php";
include_once "../repositorios/usuarioRepositorio.php";
$c = new Conexao();
$conexao = $c->conectar();
?>

<div class="container">
  <div class="row">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
      <div class="card card-signin my-5">
        <div class="card-body">
          <h5 class="card-title text-center">Recuperar Senha</h5>
          <?php
          if (isset($_GET["id"])) {
            $usuario = UsuarioRepositorio::buscarUsuarioExistente($_GET["id"]);
            if (!$usuario) {
              echo "<script language='javascript'>window.location='../views/recuperar_senha.php'; </script>";
              exit();
            }
            UsuarioRepositorio::editarTokenUsuario($usuario["id_usuario"], "");
          ?>
            <form class="form-signin" action="../controllers/usuarioController.php" method="post">
              <div class="form-group">
                <input type="hidden" value="editar_senha" name="tipo">
                <input type="hidden" value="<?php echo $usuario["id_usuario"]; ?>" name="id_usuario">
                <input type="password" id="nova_senha" class="form-control" placeholder="Digite a senha" name="nova_senha" required autofocus>
              </div>
              <div class="form-group">
                <input type="password" id="senha2" class="form-control" placeholder="Repita a senha" name="senha2" required>
              </div>
              <input class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" name="entrar" value="salvar">
              <hr class="my-4">
              <div class="row">
                <div class="col"><a href="login.php">Fazer Login</a></div>
                <div class="col"><a href="cadastrar.php">Cadastrar-se</a></div>
              </div>
            </form>
          <?php } else { ?>
            <form class="form-signin" action="../controllers/recuperar_senha.php" method="post">
              <div class="form-group">
                <input type="email" id="inputEmail" class="form-control" placeholder="Endereço de Email" name="email" required autofocus>
              </div>
              <input class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" name="entrar" value="enviar">
              <hr class="my-4">
              <div class="row">
                <div class="col"><a href="login.php">Fazer Login</a></div>
                <div class="col"><a href="cadastrar.php">Cadastrar-se</a></div>
              </div>
            </form>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include "rodape.php"; ?>