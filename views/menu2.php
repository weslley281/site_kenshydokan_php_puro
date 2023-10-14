<?php
session_start();
$filiado = @$_SESSION['filiado'];
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
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


    <title>Kenshydokan</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="../css/bootstrap.css">

    <link rel="icon" href="caminho-para-seu-favicon.png" type="image/png">
  </head>

  <body class="landing-page sidebar-collapse">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top mb-5" id="mainNav">
          <div class="container">
              <a class="navbar-brand" href="inicio.php"><i class="bi bi-house-fill"></i> Kenshydokan</a>
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                  <span class="navbar-toggler-icon"></span>
                  <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarResponsive">
                  <ul class="navbar-nav ml-auto">
                      <li class="nav-item">
                          <a class="nav-link" href="sobre.php"><i class="bi bi-sticky-fill"></i> Sobre</a>
                      </li>
                      <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownFiliacao" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="bi bi-journal-text"></i>
                              Filiação
                          </a>
                          <div class="dropdown-menu" aria-labelledby="navbarDropdownFiliacao">
                              <a class="dropdown-item" href="filiar.php"><i class="bi bi-journal-plus"></i> Filiar-se</a>
                              <a class="dropdown-item" href="filiados.php"><i class="bi bi-gem"></i> Filiados</a>
                          </div>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" href="galeria.php"><i class="bi bi-card-image"></i> Galeria</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" href="postagens.php"><i class="bi bi-receipt"></i> Postagens</a>
                      </li>
                      <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCampeonatos" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="bi bi-award-fill"></i>
                              Campeonatos
                          </a>
                          <div class="dropdown-menu" aria-labelledby="navbarDropdownCampeonatos">
                              <a class="dropdown-item" href="campeonatos.php"><i class="bi bi-calendar2-month-fill"></i> Agenda</a>
                              <a class="dropdown-item" href="#">Se Inscreva</a>
                          </div>
                      </li>
                      <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownArtesMarciais" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Artes Marciais
                          </a>
                          <div class="dropdown-menu" aria-labelledby="navbarDropdownArtesMarciais">
                              <a class="dropdown-item" href="campeonatos.php">Karate Kenshydokan</a>
                              <a class="dropdown-item" href="https://drive.google.com/file/d/1shnBSaktdRG5_w6GgwemSOA4Xcg-X4rX/view?usp=sharing">Judo Kodokan</a>
                              <a class="dropdown-item" href="ju_jitsu.php">Ju Jitsu</a>
                              <a class="dropdown-item" href="#">Muay Thai</a>
                              <a class="dropdown-item" href="#">Kick Boxing</a>
                          </div>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" href="contato.php"><i class="bi bi-telephone-fill"></i> Contato</a>
                      </li>
                      <li class="nav-item">
                      </li>
                      <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownConta" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="bi bi-person-circle"></i> &nbsp
                              Minha Conta
                          </a>
                          <div class="dropdown-menu" aria-labelledby="navbarDropdownConta">
                              <?php if ($filiado != null) {?>
                                  <a class="nav-link" href="filiado/perfil.php"><i class="bi bi-person-lines-fill"></i> &nbspMinha Conta</a>
                                  <a class="nav-link" href="funcoes/sair.php"><i class="bi bi-person-x-fill"></i> &nbspSair</a>
                              <?php } else {?>
                                  <a class="nav-link" href="login.php"><i class="bi bi-person-circle"></i> &nbspLogin</a>
                              <?php }?>
                          </div>
                      </li>
                  </ul>
              </div>
          </div>
      </nav>
    <br>
    <br>