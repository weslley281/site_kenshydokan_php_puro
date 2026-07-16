<?php 
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
  <p class="lead font-weight-normal text-center">Unindo a tradição do Karatê de Contato Jutsu ao respeito e a ética com todos.</p>
</div>

<div class="main main-raised">
  <!-- Começo do Carrossel -->
  <div class="container text-center my-5 shadow-sm rounded overflow-hidden p-0 bg-dark">
    <video id="my-video" class="video-js embed-responsive embed-responsive-16by9" preload="auto" data-setup="{}" controls autoplay="" muted="" loop="">
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

        <!--primeira linha-->
        <div class="row">
          <div class="col-lg-3 col-md-6 text-center mb-4">
            <div class="card h-100 p-4 border-0 shadow-sm align-items-center justify-content-center">
              <img src="../img/logo_instituto.jpg" class="img-fluid mb-3" style="max-height: 120px;" alt="Logo do Instituto">
              <h5 class="h6 mb-0 font-weight-bold">Instituto Kenshydokan</h5>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 text-center mb-4">
            <div class="card h-100 p-4 border-0 shadow-sm align-items-center justify-content-center">
              <a href="https://fmjkodokan.com.br/" target="_blank"><img src="../img/image13.png" class="img-fluid mb-3" style="max-height: 120px;" alt="Logo Federação Mineira de Judô Kodokan"></a>
              <h5 class="h6 mb-0 font-weight-bold">Federação Mineira de Judô Kodokan</h5>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 text-center mb-4">
            <div class="card h-100 p-4 border-0 shadow-sm align-items-center justify-content-center">
              <a href="http://seishinkyokushinsko.comunidades.net/representante-seishin-kyokushin-brasil" target="_blank"><img src="../img/image12.png" class="img-fluid mb-3" style="max-height: 120px;" alt="Logo Federação Brasil Karate Full Contact"></a>
              <h5 class="h6 mb-0 font-weight-bold">Federação Brasil Karate Full Contact</h5>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 text-center mb-4">
            <div class="card h-100 p-4 border-0 shadow-sm align-items-center justify-content-center">
              <a href="http://seishinkyokushinsko.comunidades.net/representante-seishin-kyokushin-brasil" target="_blank"><img src="../img/image10.png" class="img-fluid mb-3" style="max-height: 120px;" alt="Logo International Seishin Kyokushin Organization"></a>
              <h5 class="h6 mb-0 font-weight-bold">International Seishin Kyokushin Organization</h5>
            </div>
          </div>
        </div>
        <!--segunda linha-->
        <div class="row">
          <div class="col-lg-3 col-md-6 text-center mb-4">
            <div class="card h-100 p-4 border-0 shadow-sm align-items-center justify-content-center">
              <img src="../img/image14.png" class="img-fluid mb-3" style="max-height: 120px;" alt="Logo World Association of Brasilian Ju Jitsu">
              <h5 class="h6 mb-0 font-weight-bold">World Association of Brasilian Ju Jitsu</h5>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 text-center mb-4">
            <div class="card h-100 p-4 border-0 shadow-sm align-items-center justify-content-center">
              <img src="../img/thaiboxing.jpeg" class="img-fluid mb-3" style="max-height: 120px;" alt="Logo WKA Muay thai e Thaiboxing">
              <h5 class="h6 mb-0 font-weight-bold">WKA Muay thai e Thaiboxing</h5>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 text-center mb-4">
            <div class="card h-100 p-4 border-0 shadow-sm align-items-center justify-content-center">
              <img src="../img/kickboxing.jpeg" class="img-fluid mb-3" style="max-height: 120px;" alt="Logo South American Kickboxing Association">
              <h5 class="h6 mb-0 font-weight-bold">South American Kickboxing Association</h5>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 text-center mb-4">
            <div class="card h-100 p-4 border-0 shadow-sm align-items-center justify-content-center">
              <img src="../img/wka.jpeg" class="img-fluid mb-3" style="max-height: 120px;" alt="World Kyokushinkai Association">
              <h5 class="h6 mb-0 font-weight-bold">World Kyokushinkai Association</h5>
            </div>
          </div>
        </div>
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