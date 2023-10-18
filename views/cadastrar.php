<?php
include_once "menu.php";
include_once "../db/conexao.php";
$c = new Conexao;
$conexao = $c->conectar();

if (isset($_SESSION["id_usuario"])) {
    echo "<script language='javascript'>window.location='../views/perfil.php'; </script>";
    exit();
}
?>
<!-- /Navigation -->

<div class="container">
  <div class="row">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
      <div class="card card-signin my-5">
        <div class="card-body">
          <h5 class="card-title text-center">Entrar</h5>
          <form class="form-signin" action="../controllers/autenticar.php" method="post">

            <div class="form-group">
              <input type="text" class="form-control" placeholder="Nome" name="nome" required autofocus>
            </div>

            <div class="form-group">
                <select class="form-select form-control" aria-label="Default select example">
                <option selected>Não sou filiado</option>
            <?php
$consulta = "SELECT id_filiado, nome FROM filiados ORDER BY nome";
var_dump($consulta);
$resultado = mysqli_query($conexao, $consulta);
var_dump($resultado);
// Verifica se a consulta foi bem-sucedida
if ($resultado) {
    while ($dado = mysqli_fetch_array($resultado)) {
        echo '<option value="' . $dado["id_fil"] . '">' . $dado["nome"] . '</option>';
    }
} else {
    echo '<option>Erro ao carregar os dados</option>';
}
?>
                </select>
            </div>


            <div class="form-group mb-3">
              <input type="email" class="form-control" placeholder="Endereço de Email" name="email" required autofocus>
            </div>

            <div class="form-group mb-3">
              <input type="password" class="form-control" placeholder="Senha" name="senha" required>
            </div>

            <div class="custom-control custom-checkbox mb-3">
              <input type="checkbox" class="custom-control-input" id="customCheck1">
              <label class="custom-control-label" for="customCheck1">Lembrar Senha</label>
            </div>
            <input class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" name="entrar" value="entrar">
            <hr class="my-4">
            <div class="row">
              <div class="col"><a href="recuperar_senha.php">Esqueci a senha</a></div>
              <div class="col"><a href="cadastro.php">Cadastrar-se</a></div>
            </div>
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