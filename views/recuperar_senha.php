<?php
include "menu.php";
include_once "../models/usuarioModel.php";

?>

<div class="container py-5">
  <div class="row">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
      <div class="card border-0 shadow-lg my-5">
        <div class="card-header text-center bg-white pt-4 pb-0 border-0">
          <img src="../img/wkka.jpg" width="60" alt="Logo" class="mb-3 rounded-circle shadow-sm">
          <h3 class="font-weight-bold text-dark mb-0">Recuperar Senha</h3>
        </div>
        <div class="card-body p-4 p-sm-5">
          <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
          <?php if (!empty($_SESSION['recuperar_senha_msg'])): ?>
            <div class="alert alert-info border-0 shadow-sm text-center mb-4">
              <?php
              echo $_SESSION['recuperar_senha_msg'];
              unset($_SESSION['recuperar_senha_msg']);
              ?>
            </div>
          <?php endif; ?>

          <?php
          if (isset($_GET["token"])) {
            $usuario = Usuario::buscarUsuarioPorToken($_GET["token"]);
            if (!$usuario) {
              echo "<script language='javascript'>window.location='../views/recuperar_senha.php'; </script>";
              exit();
            }
          ?>
            <form class="form-signin" action="../controllers/usuarioController.php" method="post">
              <input type="hidden" value="editar_senha" name="tipo">
              <input type="hidden" value="<?php echo $usuario["id_usuario"]; ?>" name="id_usuario">
              
              <div class="form-group mb-4">
                <label for="nova_senha" class="text-muted small font-weight-bold">DIGITE A NOVA SENHA</label>
                <input type="password" id="nova_senha" class="form-control form-control-lg bg-light border-0" placeholder="Nova senha" name="nova_senha" required autofocus style="font-size: 1rem;">
              </div>
              
              <div class="form-group mb-4">
                <label for="senha2" class="text-muted small font-weight-bold">REPETIR A NOVA SENHA</label>
                <input type="password" id="senha2" class="form-control form-control-lg bg-light border-0" placeholder="Repita a senha" name="senha2" required style="font-size: 1rem;">
              </div>
              
              <input class="btn btn-lg btn-danger btn-block text-uppercase font-weight-bold rounded-pill shadow-sm" type="submit" name="entrar" value="Salvar Senha">
              
              <hr class="my-4">
              
              <div class="row text-center">
                <div class="col-6"><a href="login.php" class="text-muted"><small>Fazer Login</small></a></div>
                <div class="col-6"><a href="cadastrar.php" class="font-weight-bold text-danger"><small>Cadastrar-se</small></a></div>
              </div>
            </form>
          <?php } else { ?>
            <form class="form-signin" action="../controllers/recuperar_senha.php" method="post">
              <div class="form-group mb-4">
                <label for="inputEmail" class="text-muted small font-weight-bold">E-MAIL DE CADASTRO</label>
                <input type="email" id="inputEmail" class="form-control form-control-lg bg-light border-0" placeholder="Digite seu e-mail cadastrado" name="email" required autofocus style="font-size: 1rem;">
              </div>
              
              <input class="btn btn-lg btn-danger btn-block text-uppercase font-weight-bold rounded-pill shadow-sm" type="submit" name="entrar" value="Enviar Link de Recuperação">
              
              <hr class="my-4">
              
              <div class="row text-center">
                <div class="col-6"><a href="login.php" class="text-muted"><small>Fazer Login</small></a></div>
                <div class="col-6"><a href="cadastrar.php" class="font-weight-bold text-danger"><small>Cadastrar-se</small></a></div>
              </div>
            </form>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include "rodape.php"; ?>
