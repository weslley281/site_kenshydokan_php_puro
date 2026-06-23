<?php
include_once "menu.php";

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {
?>
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Adicionar Arte Marcial</h2>
      <a href="index.php?pagina=artes" class="btn btn-outline-secondary font-weight-bold rounded-pill shadow-sm px-4">
        <i class="fas fa-arrow-left mr-2"></i> Voltar para Artes
      </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg" style="max-width: 600px; margin: 0 auto;">
      <div class="card-body p-4">
        <h5 class="text-danger font-weight-bold mb-4"><i class="fa-solid fa-hand-fist mr-2"></i>Nova Arte Marcial</h5>
        
        <form action="../../controllers/arteController.php" method="post">
          <input type="hidden" name="tipo" value="criar_arte">

          <div class="form-group mb-4">
            <label for="nome" class="text-secondary small font-weight-bold text-uppercase">Nome da Arte Marcial</label>
            <input id="nome" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="nome" placeholder="Ex: Karatê Kenshydokan" required autofocus>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2 mr-2">
              <i class="fas fa-save mr-2"></i> Salvar
            </button>
            <a href="index.php?pagina=artes" class="btn btn-light font-weight-bold rounded-pill shadow-sm px-5 py-2 border">
              Cancelar
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php
  include "rodape.php";
} else {
  echo "<script language='javascript'>window.location='login.php'; </script>";
}
?>
