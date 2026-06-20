<?php
include_once "menu.php";
include_once "../../models/galeriaModel.php";

if (isset($_SESSION["id_usuario"]) && isset($_GET["id"]) && $_SESSION['nivel'] == "admin") {
  $id_galeria = $_GET["id"];

  $galeriaRepo = new Galeria();
  $galeria = $galeriaRepo->buscarGaleria($id_galeria);
?>
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Adicionar Foto</h2>
      <a href="editar_galeria.php?id=<?php echo $id_galeria; ?>" class="btn btn-outline-secondary font-weight-bold rounded-pill shadow-sm px-4">
        <i class="fas fa-arrow-left mr-2"></i> Voltar para Galeria
      </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg" style="max-width: 600px; margin: 0 auto;">
      <div class="card-body p-4">
        <h5 class="text-danger font-weight-bold mb-4"><i class="fa-solid fa-image mr-2"></i>Nova Foto para a Galeria: <?php echo htmlspecialchars($galeria['nome']); ?></h5>
        
        <form enctype="multipart/form-data" action="../../controllers/galeriaController.php" method="post">
          <input type="hidden" name="tipo" value="adicionar_foto">
          <input type="hidden" name="id_galeria" value="<?php echo $id_galeria; ?>">

          <div class="form-group mb-3">
            <label for="nome" class="text-secondary small font-weight-bold text-uppercase">Nome da Foto (Opcional)</label>
            <input id="nome" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="nome" placeholder="Ex: Treino Geral, Exame, etc." autofocus>
          </div>

          <!-- Preview e Upload da Foto -->
          <div class="row align-items-center mb-4">
            <div class="col-auto">
              <img id="imagePreview" src="../../arquivos/sem_imagem.png" alt="Prévia da Foto" class="rounded shadow-sm border" style="width: 100px; height: 100px; object-fit: cover;">
            </div>
            <div class="col">
              <label class="text-secondary small font-weight-bold text-uppercase d-block">Arquivo da Foto</label>
              <div class="custom-file mb-2">
                <input type="file" class="custom-file-input" id="imagem" name="foto" accept="image/*" required>
                <label class="custom-file-label text-truncate shadow-sm" for="imagem" data-browse="Escolher">Selecionar foto...</label>
              </div>
              <small class="form-text text-muted">Formatos aceitos: JPG, PNG. Tamanho máximo: 2MB.</small>
            </div>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2 mr-2">
              <i class="fas fa-upload mr-2"></i> Enviar Foto
            </button>
            <a href="editar_galeria.php?id=<?php echo $id_galeria; ?>" class="btn btn-light font-weight-bold rounded-pill shadow-sm px-5 py-2 border">
              Cancelar
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Atualiza o texto do input file ao selecionar um arquivo
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
  echo "<script language='javascript'>window.location='login.php'; </script>";
}
?>