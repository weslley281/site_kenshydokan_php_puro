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
          <h5 class="card-title text-center">Cadastrar-se</h5>
          <form class="form-signin" enctype="multipart/form-data" action="../controllers/usuarioController.php" method="post">

            <div class="form-group">
              <label for="nome">Nome: </label>
              <input id="nome" type="text" class="form-control" placeholder="Nome" name="nome" required autofocus>
            </div>

            <div class="form-group">
              <label for="id_fil">Registro de Filiado: </label>
                <select id="id_fil" class="form-select form-control js-example-basic-single"" aria-label="Default select example" name="id_fil">
                <option value="0" selected>Não sou filiado</option>
            <?php
$consulta = "SELECT id_filiado, nome FROM filiados ORDER BY nome";
$resultado = mysqli_query($conexao, $consulta);
if ($resultado) {
    while ($dado = mysqli_fetch_array($resultado)) {
        echo '<option value="' . $dado["id_filiado"] . '">' . $dado["nome"] . '</option>';
    }
} else {
    echo '<option>Erro ao carregar os dados</option>';
}
?>
                </select>
            </div>

            <div class="form-group">
              <label for="imagem">Imagem de Perfil</label>
                <input type="file" class="form-control" id="imagem" accept="image/*" required name="imagem">

                <div class="text-center mt-2">
                  <img src="#" class="img-thumbnail" alt="Prévia da Imagem" id="imagePreview" style="max-width: 100%; display: none;">
                </div>
            </div>


            <div class="form-group">
              <label for="email">Email: </label>
                <input id="email" type="email" class="form-control" placeholder="Endereço de Email" name="email" required>
            </div>

            <div class="form-group">
              <label for="telefone">Telefone: </label>
              <input id="telefone" type="text" class="form-control" placeholder="Nome" name="telefone" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" required>
            </div>

            <div class="form-group">
              <label for="senha">Senha</label>
              <input id="senha" type="password" class="form-control" placeholder="Senha" name="senha" required>
            </div>

            <input type="hidden" name="tipo" value="inserir">

            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="customCheck1">
              <label class="custom-control-label" for="customCheck1">Lembrar Senha</label>
            </div>

            <input class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" name="entrar" value="Cadastrar-se">
            <hr class="my-4">
            <div class="row">
              <div class="col"><a href="recuperar_senha.php">Esqueci a senha</a></div>
              <div class="col"><a href="login.php">Fazer Login</a></div>
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