<?php 
session_start();
$filiado = @$_SESSION['filiado'];
 ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Kenshydokan</title>

  <!-- Bootstrap core CSS -->
  <link href="../vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
  <!--<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>-->
  <script src="https://cdn.tiny.cloud/1/a0nk30p1g63rjh3gknotzn47pzsmxr7n6pfezilpk8lct92z/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  <!-- Custom styles for this template -->
  <link href="../css/scrolling-nav.css" rel="stylesheet">

</head>

<body class="container-fluid mb-5">  
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top mb-5" id="mainNav">
    <div class="container">
      <a class="navbar-brand js-scroll-trigger" href="../inicio.php"><i class="bi bi-house-fill"></i> Kenshydokan</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="../sobre.php"><i class="bi bi-sticky-fill"></i> Sobre</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="bi bi-journal-text"></i>
              Filiação
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="../filiar.php"><i class="bi bi-journal-plus"></i> Filiar-se</a>
              <a class="dropdown-item" href="../filiados.php"><i class="bi bi-gem"></i> Filiados</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="../galeria.php"><i class="bi bi-card-image"></i> Galeria</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="../postagens.php"><i class="bi bi-receipt"></i> Postagens</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="bi bi-award-fill"></i>
              Campeonatos
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="../campeonatos.php"><i class="bi bi-calendar2-month-fill"></i> Agenda</a>
              <a class="dropdown-item" href="">Se Inscreva</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Artes Marciais
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="../campeonatos.php">Karate Kenshydokan</a>
              <a class="dropdown-item" href="https://drive.google.com/file/d/1shnBSaktdRG5_w6GgwemSOA4Xcg-X4rX/view?usp=sharing">Judo Kodokan</a>
              <a class="dropdown-item" href="../ju_jitsu.php">Ju Jitsu</a>
              <a class="dropdown-item" href="">Muay Thai</a>
              <a class="dropdown-item" href="">Kick Boxing</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="../contato.php"><i class="bi bi-telephone-fill"></i> Contato</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="bi bi-person-fill"></i>
              Meu perfil
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <?php if (@$_SESSION['filiado'] != null) { ?>
                <a class="dropdown-item" href="perfil.php"><i class="bi bi-person-lines-fill"></i> Minha Conta</a>
                <a class="dropdown-item" href="../funcoes/sair.php"><i class="bi bi-person-x-fill"></i> Sair</a>
              <?php }else{ ?>
                <a class="dropdown-item" href="../login.php"><i class="bi bi-person-circle"> </i>Login</a>
              <?php } ?>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <br>
  <br>
  <br>
  <br>
