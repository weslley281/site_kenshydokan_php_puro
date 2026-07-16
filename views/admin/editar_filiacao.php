<?php
include_once "menu.php";
include_once __DIR__ . "/../../models/filiacaoModel.php";

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {
    $id_filiacao = $_GET["id"] ?? null;
    if (!$id_filiacao) {
        echo "<script language='javascript'>window.location='index.php?pagina=filiacoes'; </script>";
        exit;
    }

    $filModel = new Filiacao();
    $fil = $filModel->buscarPorId($id_filiacao);

    if (!$fil) {
        echo "<script language='javascript'>window.location='index.php?pagina=filiacoes'; </script>";
        exit;
    }
?>
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Editar Filiação</h2>
      <a href="index.php?pagina=filiacoes" class="btn btn-outline-secondary font-weight-bold rounded-pill shadow-sm px-4">
        <i class="fas fa-arrow-left mr-2"></i> Voltar para Filiações
      </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg" style="max-width: 600px; margin: 0 auto;">
      <div class="card-body p-4">
        <h5 class="text-danger font-weight-bold mb-4"><i class="fa-solid fa-pen-to-square mr-2"></i>Editar Filiação #<?php echo $id_filiacao; ?></h5>
        
        <form action="../../controllers/filiacaoController.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="tipo" value="editar_filiacao">
          <input type="hidden" name="id_filiacao" value="<?php echo $id_filiacao; ?>">
          <input type="hidden" name="logo_antiga" value="<?php echo htmlspecialchars($fil->getLogo()); ?>">

          <div class="form-group mb-3">
            <label for="nome" class="text-secondary small font-weight-bold text-uppercase">Nome da Instituição <span class="text-danger">*</span></label>
            <input id="nome" type="text" class="form-control bg-light border-0 shadow-sm" name="nome" value="<?php echo htmlspecialchars($fil->getNome()); ?>" placeholder="Ex: Federação Mineira de Judô Kodokan" required>
          </div>

          <div class="form-group mb-3">
            <label for="link" class="text-secondary small font-weight-bold text-uppercase">Website Oficial (URL)</label>
            <input id="link" type="url" class="form-control bg-light border-0 shadow-sm" name="link" value="<?php echo htmlspecialchars($fil->getLink()); ?>" placeholder="Ex: https://fmjkodokan.com.br">
          </div>

          <div class="form-group mb-3">
            <label for="status" class="text-secondary small font-weight-bold text-uppercase">Status <span class="text-danger">*</span></label>
            <select id="status" class="form-control bg-light border-0 shadow-sm" name="status" required>
              <option value="ativo" <?php echo ($fil->getStatus() === 'ativo') ? 'selected' : ''; ?>>Ativo (Visível na página principal)</option>
              <option value="inativo" <?php echo ($fil->getStatus() !== 'ativo') ? 'selected' : ''; ?>>Inativo (Oculto)</option>
            </select>
          </div>

          <div class="form-group mb-4">
            <label for="logo" class="text-secondary small font-weight-bold text-uppercase">Alterar Imagem da Logo (Opcional)</label>
            <input id="logo" type="file" class="form-control-file" name="logo" accept="image/*">
            
            <div class="mt-3">
              <span class="d-block text-muted small mb-2">Logo Atual / Pré-visualização:</span>
              <img id="logoPreview" src="../../img/<?php echo htmlspecialchars($fil->getLogo()); ?>" alt="Pré-visualização da Logo" class="img-thumbnail bg-white shadow-sm" style="max-height: 150px; max-width: 200px; object-fit: contain;">
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
      var originalSrc = previewImg.src;

      logoInput.addEventListener("change", function() {
          if (this.files && this.files[0]) {
              var reader = new FileReader();
              reader.onload = function(e) {
                  previewImg.src = e.target.result;
              }
              reader.readAsDataURL(this.files[0]);
          } else {
              previewImg.src = originalSrc;
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
