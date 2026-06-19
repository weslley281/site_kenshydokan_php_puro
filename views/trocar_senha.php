<?php include "menu.php"; ?>

<div class="container py-5">
  <div class="row">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
      <div class="card border-0 shadow-lg my-5">
        <div class="card-header text-center bg-white pt-4 pb-0 border-0">
          <img src="../img/wkka.jpg" width="60" alt="Logo" class="mb-3 rounded-circle shadow-sm">
          <h3 class="font-weight-bold text-dark mb-0">Trocar Senha</h3>
        </div>
        <div class="card-body p-4 p-sm-5">
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
            <input type="hidden" id="id_usuario" value="<?php echo $id_usuario; ?>" name="id_usuario">
            
            <div class="form-group mb-4">
              <label for="senha1" class="text-muted small font-weight-bold">DIGITE A NOVA SENHA</label>
              <input type="password" id="senha1" class="form-control form-control-lg bg-light border-0" placeholder="Digite a nova senha" name="senha1" required autofocus style="font-size: 1rem;">
            </div>

            <div class="form-group mb-4">
              <label for="senha2" class="text-muted small font-weight-bold">REPETIR A NOVA SENHA</label>
              <input type="password" id="senha2" class="form-control form-control-lg bg-light border-0" placeholder="Repita a nova senha" name="senha2" required style="font-size: 1rem;">
            </div>

            <input class="btn btn-lg btn-danger btn-block text-uppercase font-weight-bold rounded-pill shadow-sm" type="submit" name="trocar" value="Salvar Senha">
            
            <hr class="my-4">
            
            <div class="text-center">
              <a href="login.php" class="text-muted"><small>Voltar para o Login</small></a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include "rodape.php"; ?>
