<?php
include "menu.php";
include_once "../db/conexao.php";
$c = new Conexao();
$conexao = $c->conectar();
?>

<div class="container">
  <div class="row">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
      <div class="card card-signin my-5">
        <div class="card-body">
          <h5 class="card-title text-center">Recuperar Senha</h5>
          <form class="form-signin" action="../controllers/autenticar.php" method="post">
            <div class="form-group mb-3">
              <input type="email" id="inputEmail" class="form-control" placeholder="Endereço de Email" name="usuario" required autofocus>
            </div>
            <input class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" name="entrar" value="entrar">
            <hr class="my-4">
            <div class="row">
              <div class="col"><a href="login.php">Fazer Login</a></div>
              <div class="col"><a href="cadastrar.php">Cadastrar-se</a></div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include "rodape.php";?>