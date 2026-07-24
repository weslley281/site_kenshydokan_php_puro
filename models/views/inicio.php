<?php include "menu.php"; ?>

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
  </p>
</div>

<div class="main main-raised">
  <!-- Começo do Carrossel -->
  <div class="container text-center my-5 shadow-sm rounded overflow-hidden p-0 bg-dark">
    <video id="my-video" class="video-js embed-responsive embed-responsive-16by9" preload="none" data-setup="{}" controls autoplay="" muted="" loop="">
      <source id="video-source" class="embed-responsive-item" data-src="../videos/slide-kenshydokan.mp4" type="video/mp4">
      <p class="vjs-no-js">
        To view this video please enable JavaScript, and consider upgrading to a
        web browser that
        <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
      </p>
    </video>
    <script>
    window.addEventListener('load', function() {
        var video = document.getElementById('my-video');
        var source = document.getElementById('video-source');
        if (video && source) {
            source.src = source.getAttribute('data-src');
            video.load();
        }
    });
    </script>
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
    <div class="row text-center">
      <div class="col-lg-4 mb-5">
        <div class="card h-100 border-0 shadow-sm pt-4 pb-2 px-3 align-items-center">
          <a href="ver_perfil.php?id_usuario=5">
            <img class="rounded-circle shadow mb-3 border border-danger" src="../img/Jonas.jpg" width="160" height="160" alt="Jonas Teixeira de Andrade" style="border-width: 4px !important; object-fit: cover;">
          </a>
          <h4 class="font-weight-bold mb-1">Jonas Teixeira de Andrade</h4>
          <p class="text-danger font-weight-bold mb-3"><small>PRESIDENTE E FUNDADOR DA KENSHYDOKAN</small></p>
          <ul class="list-unstyled text-muted small text-left ml-4">
            <li><i class="fa-solid fa-check text-danger mr-2"></i> 10° Dan Karatê Kenshydokan</li>
            <li><i class="fa-solid fa-check text-danger mr-2"></i> 7° Dan Ju Jitsu</li>
            <li><i class="fa-solid fa-check text-danger mr-2"></i> 7° Dan em KickBoxing</li>
            <li><i class="fa-solid fa-check text-danger mr-2"></i> 6° Dan Judo Kodokan</li>
            <li><i class="fa-solid fa-check text-danger mr-2"></i> 5° Dan Karate Kyokushin</li>
            <li><i class="fa-solid fa-check text-danger mr-2"></i> 15° Khan Muay Thai</li>
            <li><i class="fa-solid fa-check text-danger mr-2"></i> Faixa Preta 5º Grau BJJ</li>
          </ul>
        </div>
      </div>

      <div class="col-lg-4 mb-5">
        <div class="card h-100 border-0 shadow pt-4 pb-2 px-3 align-items-center" style="transform: scale(1.05); z-index: 1;">
          <a href="ver_perfil.php?id_usuario=1">
            <img class="rounded-circle shadow mb-3 border border-danger" src="../img/sensei_weslley.jpg" width="180" height="180" alt="Weslley Henrique Vieira Ferraz" style="border-width: 4px !important; object-fit: cover;">
          </a>
          <h4 class="font-weight-bold mb-1">Weslley Henrique Vieira Ferraz</h4>
          <p class="text-danger font-weight-bold mb-3"><small>DIRETOR TÉCNICO</small></p>
          <ul class="list-unstyled text-muted small text-left ml-4">
            <li><i class="fa-solid fa-check text-danger mr-2" alt="3° Dan Karatê Kenshydokan"></i> 3° Dan Karatê Kenshydokan</li>
            <li><i class="fa-solid fa-check text-danger mr-2" alt="2° Dan Judo Kodokan"></i> 1° Dan Judo Kodokan</li>
            <li><i class="fa-solid fa-check text-danger mr-2" alt="1° Dan Karatê Kyokushin"></i> 1° Dan Karatê Kyokushin</li>
            <li><i class="fa-solid fa-check text-danger mr-2" alt="12° Khan Muay Thai"></i> 12° Khan Muay Thai</li>
            <li><i class="fa-solid fa-check text-danger mr-2" alt="Faixa Preta BJJ"></i> Faixa Preta BJJ</li>
            <li><i class="fa-solid fa-check text-danger mr-2" alt="Faixa Roxa Ju Jitsu"></i> Faixa Roxa Ju Jitsu</li>
          </ul>
        </div>
      </div>

      <div class="col-lg-4 mb-5">
        <div class="card h-100 border-0 shadow-sm pt-4 pb-2 px-3 align-items-center">
          <img class="rounded-circle shadow mb-3 border border-danger" src="../img/sensei_elyakin.jpg" width="160" height="160" alt="Elyakin Vinicius C de M Metello" style="border-width: 4px !important; object-fit: cover;">
          <h4 class="font-weight-bold mb-1">Elyakin Vinicius C de M Metello</h4>
          <p class="text-danger font-weight-bold mb-3"><small>DIRETOR DE ARBITRAGEM</small></p>
          <ul class="list-unstyled text-muted small text-left ml-4">
            <li><i class="fa-solid fa-check text-danger mr-2"></i> 2° Dan Karatê Kenshydokan</li>
          </ul>
        </div>
      </div>
    </div><!-- /.row -->
  </div>

  <?php include "rodape.php"; ?>