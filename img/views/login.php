<?php
include "menu.php";
if (isset($_SESSION["id_usuario"])) {
  echo "<script language='javascript'>window.location='../views/perfil.php'; </script>";
  exit();
}
?>
<!-- /Navigation -->

<div class="container py-5">
  <div class="row">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
      <div class="card border-0 shadow-lg my-5">
        <div class="card-header text-center bg-white pt-4 pb-0 border-0">
          <img src="../img/wkka.jpg" width="60" alt="Logo" class="mb-3 rounded-circle shadow-sm">
          <h3 class="font-weight-bold text-dark mb-0">Área de Acesso</h3>
        </div>
        <div class="card-body p-4 p-sm-5">
          <form class="form-signin" action="../controllers/autenticar.php" method="post">
            
            <div class="form-group mb-4">
              <label for="inputEmail" class="text-muted small font-weight-bold">E-MAIL</label>
              <input type="email" id="inputEmail" class="form-control form-control-lg bg-light border-0" placeholder="Digite seu email" name="usuario" value="" required autofocus style="font-size: 1rem;">
            </div>
            
            <div class="form-group mb-4">
              <label for="inputPassword" class="text-muted small font-weight-bold">SENHA</label>
              <input type="password" id="inputPassword" class="form-control form-control-lg bg-light border-0" placeholder="Digite sua senha" name="senha" required style="font-size: 1rem;">
            </div>

            <div class="custom-control custom-checkbox mb-4">
              <input type="checkbox" class="custom-control-input" id="customCheck1" name="lembrar">
              <label class="custom-control-label text-muted" for="customCheck1">Lembrar meu acesso</label>
            </div>
            
            <input class="btn btn-lg btn-danger btn-block text-uppercase font-weight-bold rounded-pill shadow-sm" type="submit" name="entrar" value="Entrar no Sistema">
            
            <div class="text-center my-3">
              <span class="text-muted small font-weight-bold">OU</span>
            </div>

            <a href="../controllers/auth_google.php" class="btn btn-lg btn-outline-dark btn-block text-uppercase font-weight-bold rounded-pill shadow-sm d-flex align-items-center justify-content-center mb-3" style="font-size: 0.85rem; border-color: #ddd; background-color: #fff; color: #495057;">
              <i class="fab fa-google text-danger mr-2" style="font-size: 1.1rem;"></i> Entrar com o Google
            </a>
            
            <hr class="my-4">
            
            <div class="row text-center">
              <div class="col-12 mb-2"><a href="recuperar_senha.php" class="text-muted"><small>Esqueci minha senha</small></a></div>
              <div class="col-12"><a href="cadastrar.php" class="font-weight-bold text-danger">Não tem conta? Cadastre-se</a></div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include "rodape.php"; ?>