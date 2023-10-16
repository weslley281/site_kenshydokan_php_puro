<?php
include_once "../db/conexao.php";
$c = new Conexao();
$conexao = $c->conectar();
?>
<!-- Navigation -->
<?php include "menu.php";?>
<!-- /Navigation -->

<div class="container">
  <div class="row">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
      <div class="card card-signin my-5">
        <div class="card-body">
          <h5 class="card-title text-center">Entrar</h5>
          <form class="form-signin" action="../controllers/autenticar.php" method="post">
            <div class="form-label-group mb-3">
              <input type="email" id="inputEmail" class="form-control" placeholder="Endereço de Email" name="usuario" required autofocus>
            </div>
            <div class="form-label-group mb-3">
              <input type="password" id="inputPassword" class="form-control" placeholder="Senha" name="senha" required>
            </div>

            <div class="custom-control custom-checkbox mb-3">
              <input type="checkbox" class="custom-control-input" id="customCheck1">
              <label class="custom-control-label" for="customCheck1">Lembrar Senha</label>
            </div>
            <input class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" name="entrar" value="entrar">
            <hr class="my-4">
            <a href="recuperar_senha.php">Esqueci a senha</a>
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