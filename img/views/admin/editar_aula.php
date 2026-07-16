<?php
include_once "menu.php";
include_once "../../models/aulaModel.php";

$id_aula = $_GET["id"];

$aulaRepo = new AulaModel();
$aula = $aulaRepo->buscarAula($id_aula);

if (isset($_SESSION["id_usuario"]) && isset($_GET["id"]) && $_SESSION['nivel'] == "admin") {
?>
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Editar Aula</h2>
      <a href="index.php?pagina=cursos" class="btn btn-outline-secondary font-weight-bold rounded-pill shadow-sm px-4">
        <i class="fas fa-arrow-left mr-2"></i> Voltar para Cursos
      </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
      <div class="card-body p-4">
        <h5 class="text-danger font-weight-bold mb-4"><i class="fa-solid fa-person-chalkboard mr-2"></i>Editar Aula: <?php echo htmlspecialchars($aula['titulo'], ENT_QUOTES, 'UTF-8'); ?></h5>
        
        <form enctype="multipart/form-data" action="../../controllers/AulaController.php" method="post">
          <input type="hidden" name="tipo" value="editar">
          <input type="hidden" name="id_aula" value="<?php echo $_GET["id"]; ?>">
          <input type="hidden" name="id_curso" value="<?php echo $_GET["id_curso"]; ?>">

          <div class="form-row mb-4">
            <div class="form-group col-md-9">
              <label for="titulo" class="text-secondary small font-weight-bold text-uppercase">Título da Aula</label>
              <input id="titulo" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" value="<?php echo htmlspecialchars($aula["titulo"]); ?>" name="titulo" placeholder="Digite o título da aula" required autofocus>
            </div>
            <div class="form-group col-md-3">
              <label for="num_ordenacao" class="text-secondary small font-weight-bold text-uppercase">Ordem de Exibição</label>
              <input id="num_ordenacao" type="number" class="form-control form-control-lg bg-light border-0 shadow-sm" value="<?php echo htmlspecialchars($aula["num_ordenacao"]); ?>" name="num_ordenacao" required>
            </div>
          </div>

          <div class="form-group mb-4">
            <label for="aula" class="text-secondary small font-weight-bold text-uppercase">Conteúdo da Aula</label>
            <textarea id="aula" class="form-control bg-light border-0 shadow-sm" name="aula" rows="15" required><?php echo htmlspecialchars($aula["aula"]); ?></textarea>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2 mr-2">
              <i class="fas fa-save mr-2"></i> Salvar Alterações
            </button>
            <a href="index.php?pagina=cursos" class="btn btn-light font-weight-bold rounded-pill shadow-sm px-5 py-2 border">
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