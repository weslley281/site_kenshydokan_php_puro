<?php
// views/admin/criar_usuario.php
include_once "menu.php";
include_once "../../models/usuarioModel.php";
include_once "../../models/filiadoModel.php";

$filiadoModelRepo = new FiliadoModel();

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "sensei") {
?>
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Cadastrar Novo Usuário</h2>
      <a href="index.php?pagina=usuarios" class="btn btn-outline-secondary font-weight-bold rounded-pill shadow-sm px-4">
        <i class="fas fa-arrow-left mr-2"></i> Voltar para Usuários
      </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg" style="max-width: 650px; margin: 0 auto;">
      <div class="card-body p-4">
        <h5 class="text-danger font-weight-bold mb-4"><i class="fa-solid fa-user-plus mr-2"></i>Informações do Usuário</h5>
        
        <form enctype="multipart/form-data" action="../../controllers/usuarioController.php" method="post">
          <input type="hidden" name="tipo" value="inserir">

          <div class="form-group mb-3">
            <label for="nome" class="text-secondary small font-weight-bold text-uppercase">Nome do Usuário</label>
            <input id="nome" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="nome" placeholder="Nome completo" required autofocus>
          </div>

          <div class="form-group mb-3">
            <label for="id_fil" class="text-secondary small font-weight-bold text-uppercase">Vincular Registro de Filiado (Opcional)</label>
            <select id="id_fil" class="form-control form-control-lg bg-light border-0 shadow-sm js-example-basic-single" name="id_fil">
              <option value="0">Nenhum filiado vinculado</option>
              <?php
              $todos_filiados = $filiadoModelRepo->listarFiliados();
              if (!empty($todos_filiados)) {
                foreach ($todos_filiados as $dado) {
                  echo '<option value="' . $dado["id_filiado"] . '">' . htmlspecialchars($dado["nome"]) . '</option>';
                }
              }
              ?>
            </select>
          </div>

          <div class="form-row mb-3">
            <div class="form-group col-md-6 mb-0">
              <label for="nivel" class="text-secondary small font-weight-bold text-uppercase">Nível de Acesso</label>
              <select id="nivel" class="form-control form-control-lg bg-light border-0 shadow-sm" name="nivel" required>
                <option value="kohai" selected>Kohai</option>
                <option value="sempai">Sempai</option>
                <option value="sensei">Sensei</option>
              </select>
            </div>
            <div class="form-group col-md-6 mb-0">
              <label for="telefone" class="text-secondary small font-weight-bold text-uppercase">Telefone</label>
              <input id="telefone" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="telefone" placeholder="(00) 00000-0000" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" required>
            </div>
          </div>

          <div class="form-group mb-3">
            <label for="email" class="text-secondary small font-weight-bold text-uppercase">Email</label>
            <input id="email" type="email" class="form-control form-control-lg bg-light border-0 shadow-sm" name="email" placeholder="email@exemplo.com" required>
          </div>

          <div class="form-group mb-4">
            <label for="imagem" class="text-secondary small font-weight-bold text-uppercase">Foto de Perfil (Opcional)</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="imagem" name="imagem" accept="image/*">
              <label class="custom-file-label bg-light border-0 text-muted" for="imagem">Selecionar foto...</label>
            </div>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2 mr-2">
              <i class="fas fa-save mr-2"></i> Criar Usuário
            </button>
            <a href="index.php?pagina=usuarios" class="btn btn-light font-weight-bold rounded-pill shadow-sm px-5 py-2 border">
              Cancelar
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Script para atualizar o texto do input de imagem -->
  <script>
      document.addEventListener("DOMContentLoaded", function() {
          var fileInput = document.getElementById('imagem');
          if (fileInput) {
              fileInput.addEventListener('change', function(e) {
                  var fileName = e.target.files[0] ? e.target.files[0].name : 'Selecionar foto...';
                  var nextLabel = e.target.nextElementSibling;
                  if (nextLabel) {
                      nextLabel.innerHTML = fileName;
                  }
              });
          }
      });
  </script>

<?php
  include "rodape.php";
} else {
  echo "<script language='javascript'>window.location='../login.php'; </script>";
}
?>
