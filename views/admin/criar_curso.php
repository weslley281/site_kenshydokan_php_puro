<?php
include_once "menu.php";
include_once __DIR__ . "/../../models/categoriaModel.php";

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {
?>
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Criar Novo Curso</h2>
      <a href="index.php?pagina=cursos" class="btn btn-outline-secondary font-weight-bold rounded-pill shadow-sm px-4">
        <i class="fas fa-arrow-left mr-2"></i> Voltar para Cursos
      </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
      <div class="card-body p-4">
        <form enctype="multipart/form-data" action="../../controllers/cursoController.php" method="post">
          <input type="hidden" name="tipo" value="inserir">

          <!-- Preview e Upload da Imagem -->
          <div class="row align-items-center mb-4">
            <div class="col-auto">
              <img id="imagePreview" src="../../arquivos/sem_imagem.png" alt="Prévia da Imagem" class="rounded shadow-sm border" style="width: 100px; height: 100px; object-fit: cover;">
            </div>
            <div class="col-md-6 col-12">
              <label class="text-secondary small font-weight-bold text-uppercase d-block">Imagem do Curso</label>
              <div class="custom-file mb-2">
                <input type="file" class="custom-file-input" id="imagem" name="imagem" accept="image/*" required>
                <label class="custom-file-label text-truncate shadow-sm" for="imagem" data-browse="Escolher">Selecionar imagem...</label>
              </div>
              <small class="form-text text-muted">Formatos recomendados: JPG, PNG. Esta imagem aparecerá no card do curso.</small>
            </div>
          </div>

          <hr class="my-4">

          <h5 class="text-danger font-weight-bold mb-3"><i class="fa-solid fa-graduation-cap mr-2"></i>Detalhes do Curso</h5>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="nome" class="text-secondary small font-weight-bold text-uppercase">Nome do Curso</label>
              <input id="nome" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="nome" placeholder="Digite o nome do curso" required autofocus>
            </div>
            <div class="form-group col-md-6">
              <label for="id_categoria" class="text-secondary small font-weight-bold text-uppercase">Categoria</label>
              <select id="id_categoria" class="form-control form-control-lg bg-light border-0 shadow-sm js-example-basic-single" name="id_categoria" required>
                <option value="">Selecione uma categoria...</option>
                <?php
                $categorias = CategoriaModel::buscarCategorias();
                if (!empty($categorias)) {
                  foreach ($categorias as $dado) {
                    echo '<option value="' . $dado["id_categoria"] . '">' . htmlspecialchars($dado["categoria"]) . '</option>';
                  }
                }
                ?>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="professor" class="text-secondary small font-weight-bold text-uppercase">Professor / Instrutor</label>
              <input id="professor" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="professor" placeholder="Nome do Professor" required>
            </div>
            <div class="form-group col-md-3">
              <label for="cargaHoraria" class="text-secondary small font-weight-bold text-uppercase">Carga Horária (Ex: 40h)</label>
              <input id="cargaHoraria" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="cargaHoraria" placeholder="Ex: 40h" required>
            </div>
            <div class="form-group col-md-3">
              <label for="percentual_conclusao_certificado" class="text-secondary small font-weight-bold text-uppercase">% Conclusão p/ Certificado</label>
              <input id="percentual_conclusao_certificado" type="number" class="form-control form-control-lg bg-light border-0 shadow-sm" name="percentual_conclusao_certificado" min="0" max="100" value="100" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="temCertificado" class="text-secondary small font-weight-bold text-uppercase">Gera Certificado?</label>
              <select id="temCertificado" class="form-control form-control-lg bg-light border-0 shadow-sm" name="temCertificado">
                <option value="nao" selected>Não</option>
                <option value="sim">Sim</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="situacao" class="text-secondary small font-weight-bold text-uppercase">Situação</label>
              <select id="situacao" class="form-control form-control-lg bg-light border-0 shadow-sm" name="situacao">
                <option value="aguardando" selected>Aguardando</option>
                <option value="aprovado">Aprovado</option>
                <option value="removido">Removido</option>
              </select>
            </div>
          </div>

          <hr class="my-4">

          <div class="form-group mb-4">
            <label for="descricao" class="text-secondary small font-weight-bold text-uppercase">Descrição do Curso</label>
            <textarea name="descricao" id="descricao" class="form-control bg-light border-0 shadow-sm" rows="10"></textarea>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2 mr-2">
              <i class="fas fa-save mr-2"></i> Salvar Curso
            </button>
            <a href="index.php?pagina=cursos" class="btn btn-light font-weight-bold rounded-pill shadow-sm px-5 py-2 border">
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
          var fileName = e.target.files[0] ? e.target.files[0].name : 'Selecionar imagem...';
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