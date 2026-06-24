<?php
include_once "menu.php";
include_once "../../models/cursoModel.php";
include_once "../../models/categoriaModel.php";
include_once "../../models/imagemModel.php";
include_once "../../models/aulaModel.php";

$id_curso = $_GET["id"];

$cursoRepo = new CursoModel();
$curso = $cursoRepo->buscarCurso($id_curso);

$id_categoria = $curso["id_categoria"];
$categoria = CategoriaModel::buscarCategoria($id_categoria);

$id_imagem = $curso["id_imagem"];
$imagem = Imagem::procura_imagem($id_imagem);

$caminho_imagem = '../../arquivos/sem_imagem.png';
$nome_imagem = 'Sem imagem';
if ($imagem) {
    $nome_imagem = $imagem["nome"];
    $caminho_imagem = $imagem["caminho"];
    if (strpos($caminho_imagem, '../img/') === 0) {
        $caminho_imagem = '../../img/' . substr($caminho_imagem, 7);
    }
}

if (isset($_SESSION["id_usuario"]) && isset($_GET["id"]) && $_SESSION['nivel'] == "admin") {
?>
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Editar Curso</h2>
      <a href="index.php?pagina=cursos" class="btn btn-outline-secondary font-weight-bold rounded-pill shadow-sm px-4">
        <i class="fas fa-arrow-left mr-2"></i> Voltar para Cursos
      </a>
    </div>

    <div class="row">
      <!-- Coluna da Esquerda: Detalhes do Curso e Imagem -->
      <div class="col-lg-6 col-md-12 mb-4">
        
        <!-- Card 1: Detalhes do Curso -->
        <div class="card border-0 shadow-sm rounded-lg mb-4">
          <div class="card-body p-4">
            <h5 class="text-danger font-weight-bold mb-4"><i class="fa-solid fa-graduation-cap mr-2"></i>Detalhes do Curso</h5>
            
            <form action="../../controllers/cursoController.php" method="post">
              <input type="hidden" name="tipo" value="editar">
              <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>">

              <div class="form-group mb-3">
                <label for="nome" class="text-secondary small font-weight-bold text-uppercase">Nome do Curso</label>
                <input id="nome" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" value="<?php echo htmlspecialchars($curso["nome"]); ?>" name="nome" required autofocus>
              </div>

              <div class="form-group mb-3">
                <label for="id_categoria" class="text-secondary small font-weight-bold text-uppercase">Categoria</label>
                <select id="id_categoria" class="form-control form-control-lg bg-light border-0 shadow-sm js-example-basic-single" name="id_categoria" required>
                  <option value="<?php echo $curso["id_categoria"]; ?>"><?php echo htmlspecialchars($categoria["categoria"]); ?></option>
                  <?php
                  $categorias = CategoriaModel::buscarCategorias();
                  if (!empty($categorias)) {
                    foreach ($categorias as $dado) {
                      if ($dado["id_categoria"] != $curso["id_categoria"]) {
                        echo '<option value="' . $dado["id_categoria"] . '">' . htmlspecialchars($dado["categoria"]) . '</option>';
                      }
                    }
                  }
                  ?>
                </select>
              </div>

              <div class="form-row mb-3">
                <div class="form-group col-md-8">
                  <label for="professor" class="text-secondary small font-weight-bold text-uppercase">Professor</label>
                  <input id="professor" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" value="<?php echo htmlspecialchars($curso["professor"]); ?>" name="professor" required>
                </div>
                <div class="form-group col-md-4">
                  <label for="cargaHoraria" class="text-secondary small font-weight-bold text-uppercase">Carga Horária</label>
                  <input id="cargaHoraria" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" value="<?php echo htmlspecialchars($curso["cargaHoraria"]); ?>" name="cargaHoraria" required>
                </div>
              </div>

              <div class="form-row mb-3">
                <div class="form-group col-md-6">
                  <label for="temCertificado" class="text-secondary small font-weight-bold text-uppercase">Gera Certificado?</label>
                  <select id="temCertificado" class="form-control form-control-lg bg-light border-0 shadow-sm" name="temCertificado">
                    <option value="nao" <?php echo ($curso["temCertificado"] == 'nao') ? 'selected' : ''; ?>>Não</option>
                    <option value="sim" <?php echo ($curso["temCertificado"] == 'sim') ? 'selected' : ''; ?>>Sim</option>
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <label for="percentual_conclusao_certificado" class="text-secondary small font-weight-bold text-uppercase">% p/ Certificado</label>
                  <input id="percentual_conclusao_certificado" type="number" class="form-control form-control-lg bg-light border-0 shadow-sm" value="<?php echo htmlspecialchars($curso["percentual_conclusao_certificado"]); ?>" name="percentual_conclusao_certificado" min="0" max="100" required>
                </div>
              </div>

              <div class="form-group mb-4">
                <label for="situacao" class="text-secondary small font-weight-bold text-uppercase">Situação</label>
                <select id="situacao" class="form-control form-control-lg bg-light border-0 shadow-sm" name="situacao">
                  <option value="aguardando" <?php echo ($curso["situacao"] == 'aguardando') ? 'selected' : ''; ?>>Aguardando</option>
                  <option value="aprovado" <?php echo ($curso["situacao"] == 'aprovado') ? 'selected' : ''; ?>>Aprovado</option>
                  <option value="removido" <?php echo ($curso["situacao"] == 'removido') ? 'selected' : ''; ?>>Removido</option>
                </select>
              </div>

              <div class="form-group mb-4">
                <label for="descricao" class="text-secondary small font-weight-bold text-uppercase">Descrição do Curso</label>
                <textarea name="descricao" class="form-control bg-light border-0 shadow-sm" id="descricao" rows="10"><?php echo htmlspecialchars($curso["descricao"]); ?></textarea>
              </div>

              <button class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2" type="submit">
                <i class="fas fa-save mr-2"></i> Salvar Detalhes
              </button>
            </form>
          </div>
        </div>

        <!-- Card 2: Alterar Imagem -->
        <div class="card border-0 shadow-sm rounded-lg">
          <div class="card-body p-4">
            <h5 class="text-danger font-weight-bold mb-4"><i class="fa-solid fa-image mr-2"></i>Imagem do Curso</h5>
            
            <form enctype="multipart/form-data" action="../../controllers/cursoController.php" method="post">
              <input type="hidden" name="tipo" value="editar_imagem">
              <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>">
              
              <div class="row align-items-center mb-4">
                <div class="col-auto">
                  <img id="imagePreview" src="<?php echo htmlspecialchars($caminho_imagem); ?>" alt="<?php echo htmlspecialchars($nome_imagem); ?>" class="rounded shadow-sm border" style="width: 100px; height: 100px; object-fit: cover;">
                </div>
                <div class="col">
                  <label class="text-secondary small font-weight-bold text-uppercase d-block">Nova Imagem</label>
                  <div class="custom-file mb-2">
                    <input type="file" class="custom-file-input" id="imagem" name="imagem" accept="image/*" required>
                    <label class="custom-file-label text-truncate shadow-sm" for="imagem" data-browse="Escolher">Selecionar...</label>
                  </div>
                </div>
              </div>

              <button class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2" type="submit">
                <i class="fas fa-upload mr-2"></i> Alterar Imagem
              </button>
            </form>
          </div>
        </div>

      </div>

      <!-- Coluna da Direita: Adicionar Aula e Grade de Aulas -->
      <div class="col-lg-6 col-md-12 mb-4">
        
        <!-- Card 3: Adicionar Nova Aula -->
        <div class="card border-0 shadow-sm rounded-lg mb-4">
          <div class="card-body p-4">
            <h5 class="text-danger font-weight-bold mb-4"><i class="fa-solid fa-plus mr-2"></i>Adicionar Nova Aula</h5>
            
            <form enctype="multipart/form-data" action="../../controllers/AulaController.php" method="post">
              <input type="hidden" name="tipo" value="inserir">
              <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>">

              <div class="form-row mb-3">
                <div class="form-group col-md-9">
                  <label for="titulo_aula" class="text-secondary small font-weight-bold text-uppercase">Título da Aula</label>
                  <input id="titulo_aula" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="titulo" placeholder="Digite o título" required>
                </div>
                <div class="form-group col-md-3">
                  <label for="num_ordenacao_aula" class="text-secondary small font-weight-bold text-uppercase">Ordem</label>
                  <input id="num_ordenacao_aula" type="number" class="form-control form-control-lg bg-light border-0 shadow-sm" name="num_ordenacao" required>
                </div>
              </div>

              <div class="form-group mb-4">
                <label for="aula_conteudo" class="text-secondary small font-weight-bold text-uppercase">Conteúdo da Aula</label>
                <textarea id="aula_conteudo" class="form-control bg-light border-0 shadow-sm" name="aula" rows="6"></textarea>
              </div>

              <button class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2" type="submit">
                <i class="fas fa-plus-circle mr-2"></i> Adicionar Aula
              </button>
            </form>
          </div>
        </div>

        <!-- Card 4: Lista de Aulas -->
        <div class="card border-0 shadow-sm rounded-lg">
          <div class="card-body p-4">
            <h5 class="text-danger font-weight-bold mb-4"><i class="fa-solid fa-list-ol mr-2"></i>Grade de Aulas</h5>
            
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead>
                  <tr class="text-secondary small font-weight-bold border-bottom">
                    <th scope="col" style="width: 70px;">Ordem</th>
                    <th scope="col">Título</th>
                    <th scope="col" class="text-center" style="width: 100px;">Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $aulaModelRepo = new AulaModel();
                  $aulas_curso = $aulaModelRepo->buscarAulasPorCurso($id_curso);
                  
                  if (empty($aulas_curso)) {
                    echo "<tr><td colspan='3' class='text-center text-muted py-4'>Nenhuma aula cadastrada.</td></tr>";
                  } else {
                    foreach ($aulas_curso as $aula) {
                  ?>
                      <tr>
                        <td class="align-middle font-weight-bold text-secondary text-center"><?php echo $aula["num_ordenacao"]; ?></td>
                        <td class="align-middle font-weight-bold text-dark"><?php echo htmlspecialchars($aula["titulo"]); ?></td>
                        <td class="align-middle text-center">
                          <a class="btn btn-sm btn-outline-primary border-0 rounded-circle mr-1" href="editar_aula.php?id=<?php echo $aula["id_aula"]; ?>&id_curso=<?php echo $id_curso; ?>" title="Editar Aula" style="width: 32px; height: 32px; padding: 5px 0; display: inline-block;">
                            <i class="fa-solid fa-pen-to-square"></i>
                          </a>
                          <button class="btn btn-sm btn-outline-danger border-0 rounded-circle" data-toggle="modal" data-target="#modalExcluirAula<?php echo $aula["id_aula"]; ?>" title="Excluir Aula" style="width: 32px; height: 32px; padding: 5px 0;">
                            <i class="fa-solid fa-trash"></i>
                          </button>

                          <!-- Modal Excluir Aula -->
                          <div class="modal fade" id="modalExcluirAula<?php echo $aula["id_aula"]; ?>" tabindex="-1" role="dialog" aria-labelledby="excluirAulaLabel<?php echo $aula["id_aula"]; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header bg-danger text-white border-0 py-3">
                                  <h5 class="modal-title font-weight-bold" id="excluirAulaLabel<?php echo $aula["id_aula"]; ?>">
                                    <i class="fa-solid fa-triangle-exclamation mr-2"></i> Confirmar Exclusão
                                  </h5>
                                  <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body p-4 text-center">
                                  <p class="lead mb-2">Tem certeza que deseja excluir esta aula?</p>
                                  <h5 class="font-weight-bold text-danger mb-0"><?php echo htmlspecialchars($aula["titulo"]); ?></h5>
                                  <p class="text-muted mt-2 small">Esta ação não poderá ser desfeita.</p>
                                </div>
                                <div class="modal-footer border-0 bg-light py-3 text-center d-flex justify-content-center">
                                  <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
                                  <form action="../../controllers/AulaController.php" method="post" class="d-inline">
                                    <input type="hidden" name="tipo" value="excluir">
                                    <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>">
                                    <input type="hidden" name="id_aula" value="<?php echo $aula["id_aula"]; ?>">
                                    <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Excluir</button>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
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