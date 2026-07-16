<?php
include_once "menu.php";

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {
?>
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Adicionar Filiação</h2>
      <a href="index.php?pagina=filiacoes" class="btn btn-outline-secondary font-weight-bold rounded-pill shadow-sm px-4">
        <i class="fas fa-arrow-left mr-2"></i> Voltar para Filiações
      </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg" style="max-width: 600px; margin: 0 auto;">
      <div class="card-body p-4">
        <h5 class="text-danger font-weight-bold mb-4"><i class="fa-solid fa-handshake mr-2"></i>Nova Filiação</h5>
        
        <form action="../../controllers/filiacaoController.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="tipo" value="criar_filiacao">

          <div class="form-group mb-3">
            <label for="nome" class="text-secondary small font-weight-bold text-uppercase">Nome da Instituição <span class="text-danger">*</span></label>
            <input id="nome" type="text" class="form-control bg-light border-0 shadow-sm" name="nome" placeholder="Ex: Federação Mineira de Judô Kodokan" required autofocus>
          </div>

          <div class="form-group mb-3">
            <label for="link" class="text-secondary small font-weight-bold text-uppercase">Website Oficial (URL)</label>
            <input id="link" type="url" class="form-control bg-light border-0 shadow-sm" name="link" placeholder="Ex: https://fmjkodokan.com.br">
          </div>

          <div class="form-group mb-3">
            <label for="status" class="text-secondary small font-weight-bold text-uppercase">Status <span class="text-danger">*</span></label>
            <select id="status" class="form-control bg-light border-0 shadow-sm" name="status" required>
              <option value="ativo" selected>Ativo (Visível na página principal)</option>
              <option value="inativo">Inativo (Oculto)</option>
            </select>
          </div>

          <div class="form-group mb-4">
            <label for="logo" class="text-secondary small font-weight-bold text-uppercase">Imagem da Logo (PNG, JPG, WEBP) <span class="text-danger">*</span></label>
            <input id="logo" type="file" class="form-control-file" name="logo" accept="image/*" required>
            <div class="mt-3">
              <img id="logoPreview" src="#" alt="Pré-visualização" class="img-thumbnail bg-white" style="display: none; max-height: 150px; max-width: 200px; object-fit: contain;">
            </div>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2 mr-2">
              <i class="fas fa-save mr-2"></i> Salvar
            </button>
            <a href="index.php?pagina=filiacoes" class="btn btn-light font-weight-bold rounded-pill shadow-sm px-5 py-2 border">
              Cancelar
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
  document.addEventListener("DOMContentLoaded", function() {
      var logoInput = document.getElementById("logo");
      var previewImg = document.getElementById("logoPreview");

      logoInput.addEventListener("change", function() {
          if (this.files && this.files[0]) {
              var reader = new FileReader();
              reader.onload = function(e) {
                  previewImg.src = e.target.result;
                  previewImg.style.display = "block";
              }
              reader.readAsDataURL(this.files[0]);
          } else {
              previewImg.style.display = "none";
          }
      });
  });
  </script>

<?php
  include "rodape.php";
} else {
  echo "<script language='javascript'>window.location='../login.php'; </script>";
}
?>
