<?php
include_once "menu.php";
include_once "../models/filiadoModel.php";

if (isset($_SESSION["id_usuario"])) {
  echo "<script language='javascript'>window.location='perfil/perfil.php'; </script>";
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
          <h3 class="font-weight-bold text-dark mb-0">Cadastre-se</h3>
        </div>
        <div class="card-body p-4 p-sm-5">
          <form class="form-signin" enctype="multipart/form-data" action="../controllers/usuarioController.php" method="post">

            <div class="form-group mb-4">
              <label for="nome" class="text-muted small font-weight-bold">NOME COMPLETO</label>
              <input id="nome" type="text" class="form-control form-control-lg bg-light border-0" placeholder="Digite seu nome" name="nome" required autofocus style="font-size: 1rem;">
            </div>

            <div class="form-group mb-4">
              <label for="id_fil" class="text-muted small font-weight-bold">REGISTRO DE FILIADO</label>
              <select id="id_fil" class="form-select form-control form-control-lg bg-light border-0 js-example-basic-single" aria-label="Selecione se for filiado" name="id_fil" style="font-size: 1rem;">
                <option value="0" selected>Não sou filiado / Aluno</option>
                <?php
                $filiadoModel = new FiliadoModel();
                $filiados = $filiadoModel->listarFiliados();
                if ($filiados) {
                  foreach ($filiados as $filiado) {
                    echo '<option value="' . $filiado["id_filiado"] . '">' . $filiado["nome"] . '</option>';
                  }
                } else {
                  echo '<option>Erro ao carregar os dados</option>';
                }
                ?>
              </select>
            </div>

            <div class="form-group mb-4">
              <label for="imagem" class="text-muted small font-weight-bold">IMAGEM DE PERFIL</label>
              <input type="file" class="form-control form-control-lg bg-light border-0" id="imagem" accept="image/*" name="imagem" style="font-size: 0.9rem; padding: 0.375rem 0.75rem;">

              <div class="text-center mt-3">
                <img src="#" class="rounded-circle shadow-sm border border-danger" alt="Prévia da Imagem" id="imagePreview" style="width: 120px; height: 120px; object-fit: cover; display: none;">
              </div>
            </div>

            <div class="form-group mb-4">
              <label for="email" class="text-muted small font-weight-bold">E-MAIL</label>
              <input id="email" type="email" class="form-control form-control-lg bg-light border-0" placeholder="Digite seu e-mail" name="email" required style="font-size: 1rem;">
            </div>

            <div class="form-group mb-4">
              <label for="telefone" class="text-muted small font-weight-bold">TELEFONE / WHATSAPP</label>
              <input id="telefone" type="text" class="form-control form-control-lg bg-light border-0" placeholder="(99) 99999-9999" name="telefone" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" required style="font-size: 1rem;">
            </div>

            <div class="form-group mb-4">
              <label for="senha" class="text-muted small font-weight-bold">SENHA</label>
              <input id="senha" type="password" class="form-control form-control-lg bg-light border-0" placeholder="Crie uma senha segura" name="senha" required style="font-size: 1rem;">
            </div>

            <input type="hidden" name="tipo" value="inserir">

            <div class="custom-control custom-checkbox mb-4">
              <input type="checkbox" class="custom-control-input" id="customCheck1" name="lembrar">
              <label class="custom-control-label text-muted" for="customCheck1">Lembrar meus dados</label>
            </div>

            <input class="btn btn-lg btn-danger btn-block text-uppercase font-weight-bold rounded-pill shadow-sm" type="submit" name="entrar" value="Cadastrar-se">
            
            <hr class="my-4">
            
            <div class="row text-center">
              <div class="col-12 mb-2"><a href="recuperar_senha.php" class="text-muted"><small>Esqueci minha senha</small></a></div>
              <div class="col-12"><a href="login.php" class="font-weight-bold text-danger">Já tem conta? Faça Login</a></div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include "rodape.php"; ?>