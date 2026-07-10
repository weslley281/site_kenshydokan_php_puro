<?php
include_once "menu.php";
include_once "../models/publicacaoModel.php";

// Pega o ID da postagem da URL de forma segura
$id_postagem = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (empty($id_postagem)) {
    echo "<section class='container mt-5 py-5 text-center'><div class='alert alert-danger shadow-sm rounded-lg d-inline-block px-4'><i class='fa-solid fa-triangle-exclamation mr-2'></i>Postagem não especificada.</div></section>";
    include "rodape.php";
    exit;
}

// Busca a postagem específica usando o Model
$postagem = Publicacao::buscarPostagemPorId($id_postagem);
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
              <p class="text-secondary small mb-0">Instrutor e colaborador do Instituto de Artes Marciais Kenshydokan. Dedicado ao fortalecimento da comunidade e à disseminação do karatê e de seus valores filosóficos.</p>
            </div>
          </div>
          
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
