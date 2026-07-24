<?php
$pageTitle = "Instituto Kenshydokan | Karatê de Contato e Defesa Pessoal";
$pageDescription = "Aulas de Karatê de Contato, Defesa Pessoal, Judô e Jiu-Jitsu em Várzea Grande - MT. Venha treinar no Instituto Kenshydokan!";
include "menu.php";
include_once "../models/filiadoModel.php";
?>

<div class="container-fluid py-0 px-0 position-relative">
  <!-- Hero Section -->
  <div style="background-image: url('../img/foto_principal.jpeg'); background-size: cover; background-position: top; height: 60vh; position: relative;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6);"></div>
    <div class="container h-100 d-flex flex-column justify-content-center align-items-center text-center position-relative text-white">
      <h1 class="font-weight-bold text-uppercase text-white mb-4 hero-title" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">Karatê Kenshydokan</h1>
      <h3 class="text-uppercase text-white font-weight-light mb-3 hero-subtitle">Atemi, Nage e Ne Waza</h3>
      <p class="lead font-weight-normal">Instituto de Artes Marciais e Defesa Pessoal Kenshydokan</p>

    </div>
  </div>
  <p class="lead font-weight-normal text-center my-4 py-3" style="background-color: #f8f9fa; border-top: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6; letter-spacing: 0.5px;">
    Kenshydokan: <span class="text-danger font-weight-bold">"Escola do Praticante Dedicado da Filosofia do Punho"</span>
    <br>
    <span class="font-italic">"Unindo a tradição do Karatê de Contato Jutsu ao respeito e a ética com todos."</span> - Princípios do Karatê Kenshydokan
  </p>
</div>

<div class="main main-raised">
  <!-- Começo do Carrossel -->
  <div class="container text-center my-5 shadow-sm rounded overflow-hidden p-0 bg-dark">
    <video id="my-video" class="video-js embed-responsive embed-responsive-16by9" preload="auto" data-setup="{}" controls autoplay="" muted="" loop="" aria-label="Vídeo de apresentação institucional com treinamentos e atividades do Instituto Kenshydokan">
      <source class="embed-responsive-item" src="../videos/slide-kenshydokan.mp4" type="video/mp4">
      <p class="vjs-no-js">
        To view this video please enable JavaScript, and consider upgrading to a
        web browser that
        <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
      </p>
    </video>
  </div>
  <hr class="my-5 w-75">

  <!--Filiações-->
  <div class="container py-4">
    <section class="page-section" id="services">
      <div class="container">
        <div class="text-center mb-5">
          <h2 class="font-weight-bold">Somos Filiados a</h2>
        </div>

        <?php
        require_once __DIR__ . '/../models/filiacaoModel.php';
        $filiacaoRepo = new Filiacao();
        $filiacoesAtivas = $filiacaoRepo->listarAtivas();

        if (!empty($filiacoesAtivas)) {
          echo '<div class="row justify-content-center">';
          foreach ($filiacoesAtivas as $f_ativa) {
            $f_nome = htmlspecialchars($f_ativa['nome']);
            $f_logo = htmlspecialchars($f_ativa['logo']);
            $f_link = htmlspecialchars($f_ativa['link'] ?? '');
        ?>
            <div class="col-lg-3 col-md-6 text-center mb-4">
              <div class="card h-100 p-4 border-0 shadow-sm align-items-center justify-content-center" style="border-radius: 12px; transition: transform 0.2s ease;">
                <?php if (!empty($f_link)): ?>
                  <a href="<?php echo $f_link; ?>" target="_blank" class="d-block text-decoration-none">
                    <img src="../img/<?php echo $f_logo; ?>" class="img-fluid mb-3" style="max-height: 120px; object-fit: contain;" alt="Logo de <?php echo $f_nome; ?>">
                  </a>
                <?php else: ?>
                  <img src="../img/<?php echo $f_logo; ?>" class="img-fluid mb-3" style="max-height: 120px; object-fit: contain;" alt="Logo de <?php echo $f_nome; ?>">
                <?php endif; ?>
                <h5 class="h6 mb-0 font-weight-bold text-dark"><?php echo $f_nome; ?></h5>
              </div>
            </div>
        <?php
          }
          echo '</div>';
        } else {
          echo '<p class="text-center text-muted">Nenhuma filiação cadastrada.</p>';
        }
        ?>
      </div>
    </section>
  </div>
  <!--Fim das Filiações-->

  <div class="container py-4">
    <div class="card border-0 shadow bg-white p-4">
      <div class="text-center mb-4">
        <h2 class="font-weight-bold">Dojôs Filiados</h2>
      </div>
      <?php
      require_once __DIR__ . '/../models/dojoModel.php';
      $dojoRepositorio = new DojoModel();
      $dojosAfiliados = $dojoRepositorio->listarDojos();

      if (!empty($dojosAfiliados)) {
        echo '<div class="row">';
        foreach ($dojosAfiliados as $dojo) {
          echo '<div class="col-md-4 mb-3">';
          echo '<div class="card border-0 shadow-sm p-3 text-center bg-light font-weight-bold h-100 justify-content-center">' . htmlspecialchars($dojo['nome_fantasia']) . '</div>';
          echo '</div>';
        }
        echo '</div>';
      } else {
        echo '<p class="text-center text-muted">Nenhum dojô cadastrado.</p>';
      }
      ?>
    </div>
  </div>

  <!-- Últimas Postagens do Blog -->
  <div class="container py-4">
    <div class="card border-0 shadow bg-white p-4">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <h2 class="font-weight-bold mb-3 mb-md-0"><i class="fa-solid fa-newspaper text-danger mr-2"></i>Últimas do Blog</h2>
        <a href="postagens.php" class="btn btn-sm btn-outline-danger font-weight-bold rounded-pill px-4 shadow-sm">
          Ver todas as postagens <i class="fa-solid fa-arrow-right ml-1"></i>
        </a>
      </div>

      <?php
      require_once __DIR__ . '/../models/publicacaoModel.php';
      $postagens_todas = Publicacao::buscarPostagensAprovadas();
      $postagens_recentes = array_slice($postagens_todas, 0, 3);

      if (!empty($postagens_recentes)) {
        echo '<div class="row">';
        foreach ($postagens_recentes as $post_rec) {
          $autor_dados = Publicacao::buscar_autor($post_rec["id_usuario"]);
          $nome_autor = htmlspecialchars($autor_dados["nome"] ?? "Autor Desconhecido");
          $caminho_foto = $autor_dados["foto"] ?? "";

          $wordCount = str_word_count(strip_tags($post_rec['conteudo']));
          $readTime = max(1, ceil($wordCount / 200));
          $resumo = mb_substr(html_entity_decode(strip_tags($post_rec['conteudo'])), 0, 100, 'UTF-8');
          $cover_img = $post_rec["caminho_imagem"] ?? "";
          $link_post = !empty($post_rec['slug']) ? 'postagem.php?slug=' . $post_rec['slug'] : 'postagem.php?id=' . $post_rec['id_publicacao'];
      ?>
          <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm overflow-hidden bg-light" style="border-radius: 12px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
              <!-- Imagem de Capa -->
              <div style="height: 150px; overflow: hidden; position: relative; background: #eaeaea;">
                <?php if (!empty($cover_img)): ?>
                  <img src="<?php echo htmlspecialchars($cover_img); ?>" alt="<?php echo htmlspecialchars($post_rec['titulo']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                  <div style="width: 100%; height: 100%; background: linear-gradient(45deg, #1f1f1f, #2d2d2d); display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.15);">
                    <i class="fa-solid fa-image" style="font-size: 2.5rem;"></i>
                  </div>
                <?php endif; ?>
              </div>

              <!-- Corpo do Card -->
              <div class="card-body d-flex flex-column p-3 text-left">
                <div class="d-flex align-items-center mb-2">
                  <span class="text-muted small" style="font-size: 0.75rem;"><i class="fa-regular fa-calendar mr-1"></i> <?php echo date('d/m/Y', strtotime($post_rec['dataCriacao'])); ?></span>
                  <span class="text-muted small ml-auto" style="font-size: 0.75rem;"><i class="fa-regular fa-clock mr-1"></i> <?php echo $readTime; ?> min</span>
                </div>

                <h6 class="font-weight-bold text-dark mb-2 text-truncate" title="<?php echo htmlspecialchars($post_rec['titulo']); ?>"><?php echo htmlspecialchars($post_rec['titulo']); ?></h6>
                <p class="text-secondary small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 38px; line-height: 1.4;"><?php echo htmlspecialchars($resumo); ?>...</p>

                <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                  <span class="text-muted small text-truncate" style="max-width: 60%; font-size: 0.75rem;">Por: <?php echo $nome_autor; ?></span>
                  <a href="<?php echo $link_post; ?>" class="btn btn-sm btn-danger font-weight-bold rounded-pill px-3" style="font-size: 0.75rem;" aria-label="Ler artigo: <?php echo htmlspecialchars($post_rec['titulo']); ?>">Ler Artigo</a>
                </div>
              </div>
            </div>
          </div>
      <?php
        }
        echo '</div>';
      } else {
        echo '<p class="text-center text-muted py-3">Nenhuma postagem publicada recentemente.</p>';
      }
      ?>
    </div>
  </div>

  <div class="container py-4">
    <div class="card border-0 shadow bg-dark text-white p-5">
      <div class="text-center mb-4">
        <h2 class="font-weight-bold text-white">Dojo kun Kenshydokan</h2>
      </div>
      <ul class="list-unstyled text-center lead font-weight-light" style="line-height: 2;">
        <li class="mb-2">Eu juro cultivar o espírito de benevolência, e conter o espírito de violência.</li>
        <li class="mb-2">Eu juro cultivar em meu coração um espírito inabalável, fortalecido e o verdadeiro significado do Karatê.</li>
        <li class="mb-2">Eu juro semear na vida o respeito para com todos os seres vivos e com nossos superiores e mestres, buscando caráter, harmonia e perfeição nos treinos.</li>
        <li class="mb-2">Eu juro honrar a disciplina do karatê kenshydokan e nunca usar o karatê de forma errada, assim eu juro.</li>
        <li class="mb-2">Eu juro buscar força e sabedoria, humildade e cortesia no karatê.</li>
        <li class="mt-4 font-italic">Dōmo arigatōgozaimashita</li>
        <li class="font-weight-bold text-danger">Oss!</li>
      </ul>
    </div>
  </div>

  <!-- Três colunas de texto-->
  <div class="container py-5">
    <div class="text-center mb-5">
      <h2 class="font-weight-bold">Equipe Diretiva</h2>
    </div>
    <div class="row text-center justify-content-center">
      <?php
      $filiadoModel = new FiliadoModel();
      $dbConn = new Conexao();
      $conexao = $dbConn->conectar();

      $diretores = [
        [
          'id_filiado' => 20,
          'nome' => 'Everson Jones Batista Leite',
          'cargo' => 'DIRETOR TÉCNICO',
          'default_img' => '../img/sensei-everson.jpg',
          'destaque' => false
        ],
        [
          'id_filiado' => 14,
          'nome' => 'Jonas Teixeira de Andrade',
          'cargo' => 'PRESIDENTE E FUNDADOR DA KENSHYDOKAN',
          'default_img' => '../img/Jonas.jpg',
          'destaque' => true
        ],
        [
          'id_filiado' => 23,
          'nome' => 'Weslley Henrique Vieira Ferraz',
          'cargo' => 'DIRETOR TÉCNICO',
          'default_img' => '../img/sensei_weslley.jpg',
          'destaque' => false
        ],
        [
          'id_filiado' => 98,
          'nome' => 'Nilson Egues',
          'cargo' => 'DIRETOR DE ARBITRAGEM',
          'default_img' => '../img/sensei_nilson.jpg',
          'destaque' => false
        ],
        [
          'id_filiado' => 22,
          'nome' => 'Elyakin Vinicius C de M Metello',
          'cargo' => 'DIRETOR DE SAÚDE E APOIO MÉDICO',
          'default_img' => '../img/sensei_elyakin.jpg',
          'destaque' => false
        ]
      ];

      foreach ($diretores as $d) {
        $id_filiado = $d['id_filiado'];
        $nome = $d['nome'];
        $cargo = $d['cargo'];
        $default_img = $d['default_img'];
        $destaque = $d['destaque'];

        // Busca id_usuario e imagem do usuário vinculado
        $id_usuario = null;
        $img_nome_db = null;
        $stmt = $conexao->prepare("
              SELECT u.id_usuario, img.nome AS imagem_nome
              FROM usuarios u
              LEFT JOIN imagens img ON u.id_imagem = img.id_imagem
              WHERE u.id_fil = ?
              LIMIT 1
          ");
        $stmt->bind_param("i", $id_filiado);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
          $row = $res->fetch_assoc();
          $id_usuario = $row['id_usuario'];
          $img_nome_db = $row['imagem_nome'];
        }
        $stmt->close();

        $caminho_foto = $default_img;
        if (!empty($img_nome_db) && file_exists(__DIR__ . '/../img/' . $img_nome_db)) {
          $caminho_foto = '../img/' . $img_nome_db;
        }

        $graduacoes = $filiadoModel->buscarGraduacoesFiliado($id_filiado);
      ?>
        <div class="col-lg-4 col-md-6 mb-5 d-flex justify-content-center">
          <div class="<?php echo $destaque ? 'card h-100 border-0 shadow pt-4 pb-2 px-3 align-items-center w-100' : 'card h-100 border-0 shadow-sm pt-4 pb-2 px-3 align-items-center w-100'; ?>" <?php echo $destaque ? 'style="transform: scale(1.05); z-index: 1;"' : ''; ?>>
            <?php if ($id_usuario): ?>
              <a href="ver_perfil.php?id_usuario=<?php echo $id_usuario; ?>">
                <img class="rounded-circle shadow mb-3 border border-danger" src="<?php echo $caminho_foto; ?>" width="<?php echo $destaque ? '180' : '160'; ?>" height="<?php echo $destaque ? '180' : '160'; ?>" alt="<?php echo htmlspecialchars($nome); ?>" style="border-width: 4px !important; object-fit: cover;">
              </a>
            <?php else: ?>
              <img class="rounded-circle shadow mb-3 border border-danger" src="<?php echo $caminho_foto; ?>" width="160" height="160" alt="<?php echo htmlspecialchars($nome); ?>" style="border-width: 4px !important; object-fit: cover;">
            <?php endif; ?>
            <h4 class="font-weight-bold mb-1"><?php echo htmlspecialchars($nome); ?></h4>
            <p class="text-danger font-weight-bold mb-3" style="min-height: 30px;"><small><?php echo htmlspecialchars($cargo); ?></small></p>
            <ul class="list-unstyled text-muted small text-left ml-4 w-100">
              <?php if (!empty($graduacoes)): ?>
                <?php foreach ($graduacoes as $g): ?>
                  <li><i class="fa-solid fa-check text-danger mr-2"></i> <?php echo htmlspecialchars($g['graduacao_nome']) . ' em ' . htmlspecialchars($g['arte_nome']); ?></li>
                <?php endforeach; ?>
              <?php else: ?>
                <li><i class="fa-solid fa-check text-danger mr-2"></i> Sem registro de graduação</li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      <?php
      }
      $conexao->close();
      ?>
    </div><!-- /.row -->
  </div>

  <?php include "rodape.php"; ?>