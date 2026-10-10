<?php
include_once "menu.php";
include_once "../../models/galeriaModel.php";
include_once "../../models/fotoModel.php";

$id_galeria = $_GET["id"];

$galeriaRepo = new Galeria();
$galeria = $galeriaRepo->buscarGaleria($id_galeria);

if (isset($_SESSION["id_usuario"]) && isset($_GET["id"]) && $_SESSION['nivel'] == "admin") {
?>
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Editar Galeria</h2>
      <a href="index.php?pagina=galerias" class="btn btn-outline-secondary font-weight-bold rounded-pill shadow-sm px-4">
        <i class="fas fa-arrow-left mr-2"></i> Voltar para Galerias
      </a>
    </div>

    <div class="row">
      <!-- Coluna da Esquerda: Detalhes -->
      <div class="col-lg-5 col-md-12 mb-4">
        <div class="card border-0 shadow-sm rounded-lg">
          <div class="card-body p-4">
            <h5 class="text-danger font-weight-bold mb-4"><i class="fa-solid fa-photo-film mr-2"></i>Informações da Galeria</h5>
            
            <form action="../../controllers/galeriaController.php" method="post">
              <input type="hidden" name="tipo" value="editar_galeria">
              <input type="hidden" name="id_galeria" value="<?php echo $id_galeria; ?>">

              <div class="form-group mb-4">
                <label for="nome" class="text-secondary small font-weight-bold text-uppercase">Nome da Galeria</label>
                <input id="nome" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" value="<?php echo htmlspecialchars($galeria["nome"]); ?>" name="nome" required autofocus>
              </div>

              <button class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2 btn-block" type="submit">
                <i class="fas fa-save mr-2"></i> Salvar Alterações
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Coluna da Direita: Gerenciamento de Fotos -->
      <div class="col-lg-7 col-md-12 mb-4">
        <div class="card border-0 shadow-sm rounded-lg">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
              <h5 class="text-danger font-weight-bold mb-0"><i class="fa-solid fa-images mr-2"></i>Fotos cadastradas</h5>
              <a href="adicionar_foto.php?id=<?php echo $id_galeria; ?>" class="btn btn-sm btn-danger font-weight-bold rounded-pill shadow-sm px-3">
                <i class="fas fa-plus mr-1"></i> Adicionar Foto
              </a>
            </div>

            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead>
                  <tr class="text-secondary small font-weight-bold border-bottom">
                    <th scope="col" style="width: 80px;">Miniatura</th>
                    <th scope="col">Nome da Foto</th>
                    <th scope="col" class="text-center" style="width: 80px;">Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $fotoRepo = new Foto();
                  $fotos = $fotoRepo->buscarFotosPorGaleria($id_galeria);
                  
                  if (empty($fotos)) {
                    echo "<tr><td colspan='3' class='text-center text-muted py-4'>Nenhuma foto cadastrada nesta galeria.</td></tr>";
                  } else {
                    foreach ($fotos as $foto) {
                  ?>
                      <tr>
                        <td class="align-middle">
                          <img src="../../slides/<?php echo htmlspecialchars($foto["foto"]); ?>" alt="<?php echo htmlspecialchars($foto["nome"]); ?>" class="rounded shadow-sm border" style="width: 45px; height: 45px; object-fit: cover;">
                        </td>
                        <td class="align-middle font-weight-bold text-dark"><?php echo htmlspecialchars($foto["nome"]); ?></td>
                        <td class="align-middle text-center">
                          <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" data-toggle="modal" data-target="#confirmDeleteFotoModal" data-id="<?php echo $foto["id_foto"]; ?>" data-nome="<?php echo htmlspecialchars($foto["nome"]); ?>" data-foto="../../slides/<?php echo htmlspecialchars($foto["foto"]); ?>" title="Excluir Foto" style="width: 32px; height: 32px; padding: 5px 0;">
                            <i class="fa-solid fa-trash-can"></i>
                          </button>
                        </td>
                      </tr>
                  <?php } 
                  } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Excluir Foto Único -->
  <div class="modal fade" id="confirmDeleteFotoModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteFotoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content border-0 shadow-lg text-left">
        <div class="modal-header bg-danger text-white border-0 py-3">
          <h5 class="modal-title font-weight-bold" id="confirmDeleteFotoLabel">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i> Confirmar Exclusão
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4 text-center">
          <p class="lead mb-2">Tem certeza que deseja excluir esta foto?</p>
          <div class="mb-3">
            <img id="deleteFotoThumb" src="" alt="Miniatura" class="img-thumbnail shadow-sm" style="max-width: 150px; max-height: 150px; object-fit: cover;">
          </div>
          <h5 class="font-weight-bold text-danger mb-0" id="deleteFotoNome"></h5>
        </div>
        <div class="modal-footer border-0 bg-light py-3 text-center d-flex justify-content-center">
          <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
          <form action="../../controllers/galeriaController.php" method="post" class="d-inline">
            <input type="hidden" name="tipo" value="excluir_foto">
            <input type="hidden" name="id_foto" id="deleteFotoId" value="">
            <input type="hidden" name="id_galeria" value="<?php echo $id_galeria; ?>">
            <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Excluir</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      $(document).on('show.bs.modal', '#confirmDeleteFotoModal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nome = button.data('nome');
        var foto = button.data('foto');

        var modal = $(this);
        modal.find('#deleteFotoNome').text(nome);
        modal.find('#deleteFotoId').val(id);
        modal.find('#deleteFotoThumb').attr('src', foto);
      });
    });
  </script>

<?php
  include "rodape.php";
} else {
  echo "<script language='javascript'>window.location='../login.php'; </script>";
}
?>