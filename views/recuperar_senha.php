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
          <h5 class="card-title text-center">Recuperar Senha</h5>
          <form class="form-signin" action="../controllers/autenticar.php" method="post">
            <div class="form-label-group mb-3">
              <input type="email" id="inputEmail" class="form-control" placeholder="Endereço de Email" name="usuario" required autofocus>
            </div>
            <input class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" name="entrar" value="entrar">
            <hr class="my-4">
            <a href="login.php">Fazer Login</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include "rodape.php";?>