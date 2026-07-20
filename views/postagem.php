<?php
include_once "../models/publicacaoModel.php";
include_once "../models/comentarioModel.php";

$slug = filter_input(INPUT_GET, 'slug', FILTER_DEFAULT);
$id_postagem = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$postagem = null;
if (!empty($slug)) {
    $postagem = Publicacao::buscarPostagemPorSlug($slug);
} elseif (!empty($id_postagem)) {
    $postagem = Publicacao::buscarPostagemPorId($id_postagem);
}

if ($postagem && $postagem['status'] === 'aprovado') {
    $pageTitle = htmlspecialchars($postagem['titulo']) . " | Blog Kenshydokan";
    $pageDescription = htmlspecialchars(mb_substr(strip_tags($postagem['conteudo']), 0, 160)) . "...";
    if (!empty($postagem['caminho_imagem'])) {
        $ogImage = 'https://kenshydokan.org.br/views/' . $postagem['caminho_imagem'];
    }
}

include_once "menu.php";

if (!$postagem || $postagem['status'] !== 'aprovado') {
    echo "<section class='container mt-5 py-5 text-center'><div class='alert alert-danger shadow-sm rounded-lg d-inline-block px-4'><i class='fa-solid fa-triangle-exclamation mr-2'></i>Postagem não especificada ou não encontrada.</div></section>";
    include "rodape.php";
    exit;
}

$comentarioModel = new Comentario();
$todos_comentarios = $comentarioModel->buscarComentariosPorPostagem($postagem['id_publicacao']);
?>

<section class="mb-5">
  <?php
  // Verifica se a postagem existe e está aprovada
  if ($postagem && $postagem['status'] === 'aprovado') {
      $autor_dados = Publicacao::buscar_autor($postagem["id_usuario"]);
      $nome_autor = htmlspecialchars($autor_dados["nome"] ?? "Autor Desconhecido");
      $caminho_foto = $autor_dados["foto"] ?? "";
      $cover_img = $postagem["caminho_imagem"] ?? "";

      // Calcula tempo de leitura (média de 200 palavras por minuto)
      $wordCount = str_word_count(strip_tags($postagem['conteudo']));
      $readTime = max(1, ceil($wordCount / 200));
      ?>

      <!-- Header Hero Artigo -->
      <div class="post-header-hero py-5 text-left bg-dark text-white" style="background: linear-gradient(135deg, #121212 0%, #1a1a1a 60%, #2b0b0d 100%); border-bottom: 4px solid #c0392b; position: relative;">
        <div class="container py-4">
          <div class="mb-3">
            <a href="postagens.php" class="text-danger font-weight-bold text-uppercase small" style="transition: color 0.2s ease;">
              <i class="fa-solid fa-arrow-left mr-2"></i> Voltar para o Blog
            </a>
          </div>
          <h1 class="display-4 font-weight-bold text-white mb-4" style="line-height: 1.2; font-size: clamp(2rem, 5vw, 3rem);"><?php echo htmlspecialchars($postagem["titulo"]); ?></h1>
          
          <!-- Meta Info -->
          <div class="d-flex flex-wrap align-items-center text-light small">
            <div class="d-flex align-items-center mr-4 mb-2">
              <?php if (!empty($caminho_foto) && file_exists(__DIR__ . "/../" . ltrim(str_replace("../", "", $caminho_foto), "/"))): ?>
                <img src="<?php echo htmlspecialchars($caminho_foto); ?>" class="avatar-img mr-2 rounded-circle" alt="<?php echo $nome_autor; ?>" style="width: 28px; height: 28px; object-fit: cover;">
              <?php else: ?>
                <span class="avatar-placeholder mr-2" style="width: 28px; height: 28px; font-size: 0.75rem;"><?php echo strtoupper(substr($nome_autor, 0, 1)); ?></span>
              <?php endif; ?>
              <span>Por: <strong class="text-white"><?php echo $nome_autor; ?></strong></span>
            </div>
            <div class="mr-4 mb-2">
              <i class="fa-regular fa-calendar mr-1"></i> 
              <?php echo date('d/m/Y', strtotime($postagem['dataCriacao'])); ?>
            </div>
            <div class="mb-2">
              <i class="fa-regular fa-clock mr-1"></i> 
              <?php echo $readTime; ?> min de leitura
            </div>
          </div>
        </div>
      </div>

      <!-- Article Container -->
      <div class="container post-container p-0 overflow-hidden mb-5 bg-white shadow" style="max-width: 800px; border-radius: 12px; margin-top: -80px; position: relative; z-index: 20;">
        
        <!-- Imagem de Capa -->
        <?php if (!empty($cover_img)): ?>
          <div class="w-100 text-center bg-light border-bottom">
            <img src="<?php echo htmlspecialchars($cover_img); ?>" class="post-cover-image img-fluid" alt="<?php echo htmlspecialchars($postagem['titulo']); ?>">
          </div>
        <?php endif; ?>

        <!-- Conteúdo do Artigo -->
        <div class="p-4 p-md-5">
          <article class="post-body">
            <?php echo $postagem["conteudo"]; ?>
          </article>
          
          <hr class="my-5" style="opacity: 0.15;">
          
          <!-- Box do Autor -->
          <div class="author-bio-card p-4 d-flex flex-column flex-sm-row align-items-center text-center text-sm-left bg-light rounded-right">
            <div class="mb-3 mb-sm-0 mr-sm-4">
              <?php if (!empty($caminho_foto) && file_exists(__DIR__ . "/../" . ltrim(str_replace("../", "", $caminho_foto), "/"))): ?>
                <img src="<?php echo htmlspecialchars($caminho_foto); ?>" class="avatar-img rounded-circle" alt="<?php echo $nome_autor; ?>" style="width: 80px; height: 80px; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
              <?php else: ?>
                <span class="avatar-placeholder d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; font-size: 2rem; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1);"><?php echo strtoupper(substr($nome_autor, 0, 1)); ?></span>
              <?php endif; ?>
            </div>
            <div>
              <span class="text-uppercase text-muted font-weight-bold tracking-wider" style="font-size: 0.7rem;">Sobre o Autor</span>
              <h5 class="font-weight-bold text-dark mb-2"><?php echo $nome_autor; ?></h5>
              <p class="text-secondary small mb-0"><?php if($postagem["id_usuario"] == 1) { echo "Diretor Técnico e de Ensino do Instituto Kenshydokan."; } else { echo "Instrutor e colaborador do Instituto de Artes Marciais Kenshydokan. Dedicado ao fortalecimento da comunidade e à disseminação do karatê e de seus valores filosóficos."; } ?></p>
            </div>
          </div>

          <!-- Secao de Comentarios -->
          <hr class="my-5" style="opacity: 0.15;">
          
          <div class="comments-section">
              <h4 class="font-weight-bold text-dark mb-4">
                  <i class="fa-solid fa-comments text-danger mr-2"></i> Discussao (<?php echo count($todos_comentarios); ?>)
              </h4>

              <!-- Form Comentario Principal -->
              <?php if (isset($_SESSION["id_usuario"])): ?>
                  <div class="d-flex mb-4">
                      <?php 
                      $usuario_logado_dados = Publicacao::buscar_autor($_SESSION["id_usuario"]);
                      $nome_logado = htmlspecialchars($usuario_logado_dados["nome"] ?? "Membro");
                      $foto_logada = $usuario_logado_dados["foto"] ?? "";
                      if (!empty($foto_logada) && file_exists(__DIR__ . "/../" . ltrim(str_replace("../", "", $foto_logada), "/"))): ?>
                          <img src="<?php echo htmlspecialchars($foto_logada); ?>" class="avatar-img rounded-circle mr-3" alt="<?php echo $nome_logado; ?>" style="width: 40px; height: 40px; object-fit: cover;">
                      <?php else: ?>
                          <span class="avatar-placeholder rounded-circle mr-3" style="width: 40px; height: 40px; font-size: 1.1rem; display: inline-flex; align-items: center; justify-content: center; background: #e9ecef; color: #495057; font-weight: bold; border: 1.5px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"><?php echo strtoupper(substr($nome_logado, 0, 1)); ?></span>
                      <?php endif; ?>

                      <div class="flex-grow-1">
                          <form id="formComentarioPrincipal" class="w-100">
                              <input type="hidden" name="action" value="criar">
                              <input type="hidden" name="id_postagem" value="<?php echo $postagem['id_publicacao']; ?>">
                              <input type="hidden" name="parent_id" value="">
                              <div class="form-group mb-2">
                                  <textarea class="form-control border-0 bg-light shadow-sm" name="texto" rows="3" style="border-radius: 12px; resize: none; font-size: 0.9rem;" placeholder="Escreva seu comentario..." required></textarea>
                              </div>
                              <div class="text-right">
                                  <button type="submit" class="btn btn-danger font-weight-bold rounded-pill px-4 shadow-sm btn-sm" id="btn_submit_comentario">Publicar Comentario</button>
                              </div>
                          </form>
                      </div>
                  </div>
              <?php else: ?>
                  <div class="alert alert-light border text-center p-4 rounded-lg mb-4">
                      <p class="text-secondary mb-2 small"><i class="fa-solid fa-circle-info mr-2 text-danger"></i>Voce precisa estar logado para participar da discussão.</p>
                      <a href="login.php" class="btn btn-sm btn-outline-danger font-weight-bold rounded-pill px-4">Entrar na minha Conta</a>
                  </div>
              <?php endif; ?>

              <!-- Lista de Comentarios -->
              <div id="listaComentarios" class="mt-4">
                  <?php 
                  $comentarios_principais = [];
                  $respostas = [];
                  foreach ($todos_comentarios as $c) {
                      if ($c['parent_id'] === null) {
                          $comentarios_principais[] = $c;
                      } else {
                          $respostas[$c['parent_id']][] = $c;
                      }
                  }
                  ?>

                  <?php if (!empty($comentarios_principais)): ?>
                      <?php foreach ($comentarios_principais as $c_pr): 
                          $id_c = $c_pr['id_comentario'];
                          $c_nome = htmlspecialchars($c_pr['autor_nome']);
                          $c_foto = $c_pr['autor_foto'];
                          $c_texto = nl2br(htmlspecialchars($c_pr['texto']));
                          $c_data = date("d/m/Y H:i", strtotime($c_pr['data_criacao']));
                          $pode_excluir = (isset($_SESSION["id_usuario"]) && ($_SESSION["id_usuario"] == $c_pr['id_usuario'] || $_SESSION["nivel"] === "admin"));
                      ?>
                          <!-- Comentario Principal -->
                          <div class="comentario-block mb-4 pb-3 border-bottom text-left" id="comentario-<?php echo $id_c; ?>">
                              <div class="d-flex align-items-start">
                                  <?php if (!empty($c_foto) && file_exists(__DIR__ . "/../" . ltrim(str_replace("../", "", $c_foto), "/"))): ?>
                                      <img src="<?php echo htmlspecialchars($c_foto); ?>" class="avatar-img rounded-circle mr-3" alt="<?php echo $c_nome; ?>" style="width: 38px; height: 38px; object-fit: cover;">
                                  <?php else: ?>
                                      <span class="avatar-placeholder rounded-circle mr-3" style="width: 38px; height: 38px; font-size: 0.95rem; display: inline-flex; align-items: center; justify-content: center; background: #e9ecef; color: #495057; font-weight: bold; border: 1.5px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"><?php echo strtoupper(substr($c_nome, 0, 1)); ?></span>
                                  <?php endif; ?>

                                  <div class="flex-grow-1">
                                      <div class="d-flex justify-content-between align-items-center mb-1">
                                          <div>
                                              <span class="font-weight-bold text-dark small mr-2" style="font-size: 0.85rem;"><?php echo $c_nome; ?></span>
                                              <span class="text-muted small" style="font-size: 0.75rem;"><?php echo $c_data; ?></span>
                                          </div>
                                          <?php if ($pode_excluir): ?>
                                              <button type="button" class="btn btn-link text-danger p-0 border-0 btn-excluir-comentario" data-id="<?php echo $id_c; ?>" title="Excluir Comentario" aria-label="Excluir comentário">
                                                  <i class="fa-solid fa-trash-can small" style="font-size: 0.8rem;"></i>
                                              </button>
                                          <?php endif; ?>
                                      </div>
                                      
                                      <p class="text-secondary small mb-2" style="line-height: 1.5; font-size: 0.88rem;"><?php echo $c_texto; ?></p>
                                      
                                      <?php if (isset($_SESSION["id_usuario"])): ?>
                                          <button type="button" class="btn btn-link text-secondary p-0 border-0 btn-trigger-resposta small font-weight-bold" data-id="<?php echo $id_c; ?>" style="font-size: 0.75rem; text-decoration: none;" aria-label="Responder ao comentário de <?php echo $c_nome; ?>">
                                              <i class="fa-solid fa-reply mr-1"></i>Responder
                                          </button>
                                      <?php endif; ?>

                                      <!-- Form Resposta (Oculto) -->
                                      <?php if (isset($_SESSION["id_usuario"])): ?>
                                          <div class="box-resposta mt-3" id="resposta-form-<?php echo $id_c; ?>" style="display: none;">
                                              <form class="form-comentario-resposta">
                                                  <input type="hidden" name="action" value="criar">
                                                  <input type="hidden" name="id_postagem" value="<?php echo $postagem['id_publicacao']; ?>">
                                                  <input type="hidden" name="parent_id" value="<?php echo $id_c; ?>">
                                                  <div class="form-group mb-2">
                                                      <textarea class="form-control border-0 bg-light shadow-sm" name="texto" rows="2" style="border-radius: 8px; resize: none; font-size: 0.85rem;" placeholder="Escreva sua resposta..." required></textarea>
                                                  </div>
                                                  <div class="text-right">
                                                      <button type="button" class="btn btn-link text-secondary btn-sm mr-2 btn-cancelar-resposta font-weight-bold" data-id="<?php echo $id_c; ?>" style="text-decoration: none; font-size: 0.8rem;">Cancelar</button>
                                                      <button type="submit" class="btn btn-danger font-weight-bold rounded-pill px-3 btn-sm" style="font-size: 0.8rem;">Responder</button>
                                                  </div>
                                              </form>
                                          </div>
                                      <?php endif; ?>

                                      <!-- Respostas Aninhadas -->
                                      <div class="respostas-list mt-3 border-left pl-3" style="border-left-width: 3px !important; border-left-color: rgba(0,0,0,0.06) !important;">
                                          <?php if (isset($respostas[$id_c])): ?>
                                              <?php foreach ($respostas[$id_c] as $resp): 
                                                  $id_r = $resp['id_comentario'];
                                                  $r_nome = htmlspecialchars($resp['autor_nome']);
                                                  $r_foto = $resp['autor_foto'];
                                                  $r_texto = nl2br(htmlspecialchars($resp['texto']));
                                                  $r_data = date("d/m/Y H:i", strtotime($resp['data_criacao']));
                                                  $r_pode_excluir = (isset($_SESSION["id_usuario"]) && ($_SESSION["id_usuario"] == $resp['id_usuario'] || $_SESSION["nivel"] === "admin"));
                                              ?>
                                                  <div class="resposta-item mb-3 text-left" id="comentario-<?php echo $id_r; ?>">
                                                      <div class="d-flex align-items-start">
                                                          <?php if (!empty($r_foto) && file_exists(__DIR__ . "/../" . ltrim(str_replace("../", "", $r_foto), "/"))): ?>
                                                              <img src="<?php echo htmlspecialchars($r_foto); ?>" class="avatar-img rounded-circle mr-2" alt="<?php echo $r_nome; ?>" style="width: 32px; height: 32px; object-fit: cover;">
                                                          <?php else: ?>
                                                              <span class="avatar-placeholder rounded-circle mr-2" style="width: 32px; height: 32px; font-size: 0.85rem; display: inline-flex; align-items: center; justify-content: center; background: #e9ecef; color: #495057; font-weight: bold; border: 1.5px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"><?php echo strtoupper(substr($r_nome, 0, 1)); ?></span>
                                                          <?php endif; ?>

                                                          <div class="flex-grow-1">
                                                              <div class="d-flex justify-content-between align-items-center mb-1">
                                                                  <div>
                                                                      <span class="font-weight-bold text-dark small mr-2" style="font-size: 0.8rem;"><?php echo $r_nome; ?></span>
                                                                      <span class="text-muted small" style="font-size: 0.7rem;"><?php echo $r_data; ?></span>
                                                                  </div>
                                                                  <?php if ($r_pode_excluir): ?>
                                                                      <button type="button" class="btn btn-link text-danger p-0 border-0 btn-excluir-comentario" data-id="<?php echo $id_r; ?>" title="Excluir Resposta" aria-label="Excluir resposta">
                                                                          <i class="fa-solid fa-trash-can small" style="font-size: 0.75rem;"></i>
                                                                      </button>
                                                                  <?php endif; ?>
                                                              </div>
                                                              <p class="text-secondary small mb-0" style="line-height: 1.4; font-size: 0.82rem;"><?php echo $r_texto; ?></p>
                                                          </div>
                                                      </div>
                                                  </div>
                                              <?php endforeach; ?>
                                          <?php endif; ?>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      <?php endforeach; ?>
                  <?php else: ?>
                      <div class="text-center py-4" id="semComentariosMsg">
                          <i class="fa-regular fa-comments text-secondary fa-2x mb-2" style="opacity: 0.3;"></i>
                          <p class="text-muted small mb-0">Nenhum comentario publicado. Seja o primeiro a opinar!</p>
                      </div>
                  <?php endif; ?>
              </div>
          </div>

          <script>
          document.addEventListener("DOMContentLoaded", function() {
              // Abrir/Fechar caixas de resposta
              $(document).on("click", ".btn-trigger-resposta", function() {
                  var id = $(this).data("id");
                  $("#resposta-form-" + id).slideToggle(200);
              });

              $(document).on("click", ".btn-cancelar-resposta", function() {
                  var id = $(this).data("id");
                  $("#resposta-form-" + id).slideUp(200);
              });

              // Submissao do Comentario Principal
              var formPrincipal = document.getElementById("formComentarioPrincipal");
              if (formPrincipal) {
                  formPrincipal.addEventListener("submit", function(e) {
                      e.preventDefault();
                      var submitBtn = document.getElementById("btn_submit_comentario");
                      submitBtn.setAttribute("disabled", "disabled");
                      
                      var formData = new FormData(formPrincipal);
                      
                      fetch("../controllers/comentarioController.php", {
                          method: "POST",
                          body: formData
                      })
                      .then(response => response.json())
                      .then(data => {
                          submitBtn.removeAttribute("disabled");
                          if (data.status === "success") {
                              location.reload();
                          } else {
                              alert(data.message);
                          }
                      })
                      .catch(error => {
                          submitBtn.removeAttribute("disabled");
                          console.error("Erro ao enviar comentario:", error);
                          alert("Erro ao publicar comentario.");
                      });
                  });
              }

              // Submissao de Respostas
              $(document).on("submit", ".form-comentario-resposta", function(e) {
                  e.preventDefault();
                  var form = this;
                  var formData = new FormData(form);
                  var submitBtn = form.querySelector('button[type="submit"]');
                  submitBtn.setAttribute("disabled", "disabled");

                  fetch("../controllers/comentarioController.php", {
                      method: "POST",
                      body: formData
                  })
                  .then(response => response.json())
                  .then(data => {
                      submitBtn.removeAttribute("disabled");
                      if (data.status === "success") {
                          location.reload();
                      } else {
                          alert(data.message);
                      }
                  })
                  .catch(error => {
                      submitBtn.removeAttribute("disabled");
                      console.error("Erro ao enviar resposta:", error);
                      alert("Erro ao publicar resposta.");
                  });
              });

              // Exclusao de Comentarios
              $(document).on("click", ".btn-excluir-comentario", function() {
                  var id = $(this).data("id");
                  if (confirm("Tem certeza que deseja excluir este comentario?")) {
                      var formData = new FormData();
                      formData.append("action", "excluir");
                      formData.append("id_comentario", id);

                      fetch("../controllers/comentarioController.php", {
                          method: "POST",
                          body: formData
                      })
                      .then(response => response.json())
                      .then(data => {
                          if (data.status === "success") {
                              $("#comentario-" + id).fadeOut(300, function() {
                                  $(this).remove();
                                  if ($("#listaComentarios").children().length === 0) {
                                      location.reload();
                                  }
                              });
                          } else {
                              alert(data.message);
                          }
                      })
                      .catch(error => {
                          console.error("Erro ao excluir comentario:", error);
                          alert("Erro ao excluir comentario.");
                      });
                  }
              });
          });
          </script>
          
          <div class="text-center mt-5">
            <a href="postagens.php" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2">
              <i class="fa-solid fa-arrow-left mr-2"></i> Voltar para Todas as Postagens
            </a>
          </div>

        </div>
      </div>

      <?php
  } else {
      echo "<div class='container py-5 my-5 text-center'><div class='alert alert-warning shadow-sm rounded-lg d-inline-block px-4'><i class='fa-solid fa-circle-exclamation mr-2'></i>A postagem solicitada não foi encontrada ou ainda está sob revisão de um administrador.</div><br><br><a href='postagens.php' class='btn btn-danger rounded-pill px-4 mt-2'>Ver Postagens Públicas</a></div>";
  }
  ?>
</section>

<?php include "rodape.php"; ?>
