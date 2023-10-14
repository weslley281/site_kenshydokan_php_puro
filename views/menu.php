<!DOCTYPE html>
<html lang="pt-br">

<?php
session_start();

// Obtém o caminho da URL atual
$url_atual = $_SERVER['REQUEST_URI'];

// Define um array associativo com os URLs das páginas e seus nomes no menu
$paginas = array(
    '/views/inicio.php' => 'Início',
    '/views/sobre.php' => 'Sobre Nós',
    '/views/login.php' => 'Sistema',
    '/views/postagens.php' => 'Postagens',
    '/views/ver_perfil.php' => 'Perfil',
    '/views/perfil.php' => 'Perfil',
    '/views/galeria.php' => 'Galeria',
    '/views/filiar.php' => 'Filiar-se',
    '/views/filiados.php' => 'Filiados',
    '/views/exame_graduacao.php' => 'Exame Graduação',
    '/views/editar_postagem' => 'Editar Postagem',
    '/views/editar_perfil.php' => 'Perfil',
    '/views/documentos.php' => 'Documentos',
    '/views/criar_postagem.php' => 'Postagem',
    '/views/contato.php' => 'Contato',
    '/views/campeonatos.php' => 'Campeonatos',
);
?>

<head>

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-167475784-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-167475784-1');
  </script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Weslley Henrique Vieira Ferraz" />
  <meta name="owner" content="Federação de Karate de Contato do Estado de Mato Grosso" />
  <meta name="copyright" content="Weslley Henrique Vieira Ferraz" />
  <meta name="keywords" content="kenshydokan, kyokushin, federação, karate, carate, karatê, caratê, de contato, full, contact, luta, aula, aulas, Karatê, kata, kumite, mato grosso, cuiaba, varzea grande, weslley ferraz, weslley, ferraz, judo, judô, kodokan, jiu, jiu jitsu, muay thai, muay boran, kickboxing">
  <meta name="description" content="Somos uma federação, criada com o intuito de divulgar o karate kenshydokan e outras artes marciais.">
  <meta http-equiv="refresh" content="3600">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />


  <?php
// Itera sobre as páginas e adiciona a classe "active" se a URL atual corresponder
foreach ($paginas as $url => $nome_da_pagina) {
    if ($url_atual === $url) {
        echo "<title>Ferraz Dojos | $nome_da_pagina </title>";
    }
}
?>

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>

  <link rel="icon" href="../img/kenshydokan.jpg" type="image/jpg">

  <!-- Custom styles for this template -->
  <link href="../css/scrolling-nav.css" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="../assets/css/material-kit.css?v=2.0.7" rel="stylesheet" />

  <!-- Custom JavaScript for this theme -->
  <script type="text/javascript" src="../js/datatables.min.js"></script>
  <script type="text/javascript" src="../js/dataTables.bootstrap4.min.js"></script>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js" type="text/javascript"></script>
  <script src="../assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
  <!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-kit.js?v=2.0.7" type="text/javascript"></script>

</head>

<body class="landing-page sidebar-collapse">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top mb-5" id="mainNav">
    <div class="container">
      <a class="navbar-brand js-scroll-trigger" href="inicio.php"><i class="bi bi-house-fill"></i> Kenshydokan</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        <span class="navbar-toggler-icon"></span>
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="sobre.php"><i class="bi bi-sticky-fill"></i> Sobre</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="bi bi-journal-text"></i>
              Filiação
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="filiar.php"><i class="bi bi-journal-plus"></i> &nbspFiliar-se</a>
              <a class="dropdown-item" href="filiados.php"><i class="bi bi-gem"></i> &nbspFiliados</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="galeria.php"><i class="bi bi-card-image"></i> Galeria</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="postagens.php"><i class="bi bi-receipt"></i> Postagens</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="bi bi-award-fill"></i>
              Campeonatos
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="campeonatos.php"><i class="bi bi-calendar2-month-fill"></i> &nbsp Agenda</a>
              <a class="dropdown-item" href="">Se Inscreva</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Artes Marciais
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="campeonatos.php">Karate Kenshydokan</a>
              <a class="dropdown-item" href="https://drive.google.com/file/d/1shnBSaktdRG5_w6GgwemSOA4Xcg-X4rX/view?usp=sharing">Judo Kodokan</a>
              <a class="dropdown-item" href="ju_jitsu.php">Ju Jitsu</a>
              <a class="dropdown-item" href="">Muay Thai</a>
              <a class="dropdown-item" href="">Kick Boxing</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="contato.php"><i class="bi bi-telephone-fill"></i> Contato</a>
          </li>
          <li class="nav-item">

          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="bi bi-person-circle"></i> &nbsp
              Minha Conta
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <?php if (isset($_SESSION['user_id'])) {?>
                <a class="nav-link js-scroll-trigger" href="filiado/perfil.php"><i class="bi bi-person-lines-fill"></i> &nbspMinha Conta</a>
                <a class="nav-link js-scroll-trigger" href="funcoes/sair.php"><i class="bi bi-person-x-fill"></i> &nbspSair</a>
              <?php } else {?>
                <a class="nav-link js-scroll-trigger" href="login.php"><i class="bi bi-person-circle"> </i>&nbspLogin</a>
              <?php }?>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <br>
  <br>