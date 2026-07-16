<?php
$pageTitle = "Blog & Notícias | Instituto Kenshydokan";
$pageDescription = "Leia artigos sobre artes marciais, conceitos técnicos de karatê e fique por dentro dos eventos e novidades de nossa associação.";
include_once "menu.php";
include_once "../models/publicacaoModel.php";
?>

<!-- Hero Banner Section -->
<section class="blog-hero py-5 text-center text-white">
  <div class="container py-4">
    <h1 class="display-4 font-weight-bold mb-3 hero-title">Blog & Notícias</h1>
    <p class="lead hero-subtitle text-muted mb-0">Acompanhe as novidades, ensinamentos e eventos do Instituto Kenshydokan</p>
  </div>
</section>

<!-- Search and Filter Bar -->
<div class="container search-container mb-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-12">
      <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white border border-light">
        <div class="input-group-prepend bg-white border-0">
          <span class="input-group-text bg-white border-0 pl-4 pr-1 text-muted">
            <i class="fa-solid fa-magnifying-glass"></i>
          </span>
        </div>
        <input type="text" id="blogSearchInput" class="form-control border-0 py-4 search-input" placeholder="Pesquisar artigos por título ou conteúdo..." aria-label="Pesquisa">
      </div>
    </div>
  </div>
</div>

<!-- Main Feed -->
<section class="container mt-4 mb-5">
  <div class="row" id="blogFeedContainer">
    <?php
    $postagens = Publicacao::buscarPostagensAprovadas();

    if (count($postagens) > 0) {
        foreach ($postagens as $postagem) {
            $autor_dados = Publicacao::buscar_autor($postagem["id_usuario"]);
            $nome_autor = htmlspecialchars($autor_dados["nome"] ?? "Autor Desconhecido");
            $caminho_foto = $autor_dados["foto"] ?? "";
            
            // Calcula tempo de leitura (média de 200 palavras por minuto)
            $wordCount = str_word_count(strip_tags($postagem['conteudo']));
            $readTime = max(1, ceil($wordCount / 200));

            // Resumo do texto
            $resumo = substr(html_entity_decode(strip_tags($postagem['conteudo'])), 0, 150);
            $cover_img = $postagem["caminho_imagem"] ?? "";
            ?>
            <div class="col-md-6 col-lg-4 mb-4 blog-post-card-wrapper" data-title="<?php echo strtolower(htmlspecialchars($postagem['titulo'])); ?>" data-content="<?php echo strtolower(htmlspecialchars(strip_tags($postagem['conteudo']))); ?>">
              <div class="card h-100 blog-card">
                
                <!-- Imagem de Capa -->
                <div class="blog-card-img-container" style="position: relative; height: 200px; overflow: hidden; background: #eaeaea;">
                  <?php if (!empty($cover_img)): ?>
                    <img src="<?php echo htmlspecialchars($cover_img); ?>" class="blog-card-img" alt="<?php echo htmlspecialchars($postagem['titulo']); ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                  <?php else: ?>
                    <div class="blog-card-img-placeholder" style="width: 100%; height: 100%; background: linear-gradient(45deg, #1f1f1f, #2d2d2d); display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.15);">
                      <i class="fa-solid fa-image" style="font-size: 3rem;"></i>
                    </div>
                  <?php endif; ?>
                  <span class="blog-card-badge">Notícias</span>
                </div>

                <!-- Corpo do Card -->
                <div class="card-body d-flex flex-column p-4">
                  <!-- Autor e Data (Topo) -->
                  <div class="d-flex align-items-center mb-3">
                    <?php if (!empty($caminho_foto) && file_exists(__DIR__ . "/../" . ltrim(str_replace("../", "", $caminho_foto), "/"))): ?>
                      <img src="<?php echo htmlspecialchars($caminho_foto); ?>" class="avatar-img rounded-circle mr-2" alt="<?php echo $nome_autor; ?>" style="width: 24px; height: 24px; object-fit: cover; border: 1.5px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <?php else: ?>
                      <span class="avatar-placeholder rounded-circle mr-2" style="width: 24px; height: 24px; font-size: 0.7rem; display: inline-flex; align-items: center; justify-content: center; background: #e9ecef; color: #495057; font-weight: bold; border: 1.5px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"><?php echo strtoupper(substr($nome_autor, 0, 1)); ?></span>
                    <?php endif; ?>
                    <div class="small">
                      <a href="ver_perfil.php?id_usuario=<?php echo $postagem["id_usuario"]; ?>" class="font-weight-bold text-dark text-decoration-none hover-red mr-2" style="font-size: 0.85rem;"><?php echo $nome_autor; ?></a>
                      <span class="text-muted" style="font-size: 0.8rem;">• <?php echo date('d/m/Y', strtotime($postagem['dataCriacao'])); ?></span>
                    </div>
                  </div>

                  <h5 class="blog-card-title font-weight-bold text-dark mb-2"><?php echo htmlspecialchars($postagem['titulo']); ?></h5>
                  <p class="blog-card-text mb-4" style="font-size: 0.85rem; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; height: 58px;"><?php echo htmlspecialchars($resumo); ?>...</p>
                  
                  <div class="d-flex align-items-center justify-content-between mt-auto pt-2 border-top" style="border-top: 1px solid rgba(0,0,0,0.05) !important;">
                    <a href="postagem.php?<?php echo !empty($postagem['slug']) ? 'slug=' . $postagem['slug'] : 'id=' . $postagem['id_publicacao']; ?>" class="btn btn-sm btn-outline-danger font-weight-bold rounded-pill px-3">Ler Artigo</a>
                    <span class="text-muted small" style="font-size: 0.8rem;">
                      <i class="fa-regular fa-clock mr-1"></i> <?php echo $readTime; ?> min
                    </span>
                  </div>
                </div>

              </div>
            </div>
            <?php
        }
    } else {
        echo "<div class='col-12 text-center py-5'><p class='lead text-muted'><i class='fa-solid fa-newspaper mr-2'></i>Nenhuma postagem publicada no momento.</p></div>";
    }
    ?>
  </div>
  
  <!-- No Results Message -->
  <div id="noSearchResults" class="text-center py-5 d-none">
    <h3 class="text-muted"><i class="fa-solid fa-magnifying-glass mr-2"></i>Nenhum artigo encontrado</h3>
    <p class="text-secondary">Tente digitar outras palavras-chave.</p>
  </div>
</section>

<!-- Search Filter Script -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("blogSearchInput");
    const cards = document.querySelectorAll(".blog-post-card-wrapper");
    const noResults = document.getElementById("noSearchResults");

    if (searchInput) {
        searchInput.addEventListener("input", function() {
            const query = this.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").trim();
            let hasVisibleCards = false;

            cards.forEach(card => {
                const title = card.getAttribute("data-title").normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                const content = card.getAttribute("data-content").normalize("NFD").replace(/[\u0300-\u036f]/g, "");

                if (title.includes(query) || content.includes(query)) {
                    card.style.display = "block";
                    hasVisibleCards = true;
                } else {
                    card.style.display = "none";
                }
            });

            if (hasVisibleCards) {
                noResults.classList.add("d-none");
            } else {
                noResults.classList.remove("d-none");
            }
        });
    }
});
</script>

<?php include "rodape.php"; ?>