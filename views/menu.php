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
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="author" content="Weslley Henrique Vieira Ferraz" />
    <meta name="owner" content="Federação de Karate de Contato do Estado de Mato Grosso" />
    <meta name="copyright" content="Weslley Henrique Vieira Ferraz" />
    <meta name="keywords" content="kenshydokan, kyokushin, federação, karate, carate, karatê, caratê, de contato, full, contact, luta, aula, aulas, Karatê, kata, kumite, mato grosso, cuiaba, varzea grande, weslley ferraz, weslley, ferraz, judo, judô, kodokan, jiu, jiu jitsu, muay thai, muay boran, kickboxing">
    <meta name="description" content="Somos uma federação, criada com o intuito de divulgar o karate kenshydokan e outras artes marciais.">
    <meta http-equiv="refresh" content="3600">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <link rel="icon" href="../img/kenshydokan.jpg" type="image/jpg">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/solid.css" integrity="sha384-Tv5i09RULyHKMwX0E8wJUqSOaXlyu3SQxORObAI08iUwIalMmN5L6AvlPX2LMoSE" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/fontawesome.css" integrity="sha384-jLKHWM3JRmfMU0A5x5AkjWkw/EYfGUAGagvnfryNV3F9VqM98XiIH7VBGVoxVSc7" crossorigin="anonymous"/>

    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/solid.js" integrity="sha384-/BxOvRagtVDn9dJ+JGCtcofNXgQO/CCCVKdMfL115s3gOgQxWaX/tSq5V8dRgsbc" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/fontawesome.js" integrity="sha384-dPBGbj4Uoy1OOpM4+aRGfAOc0W37JkROT+3uynUgTHZCHZNMHfGXsmmvYTffZjYO" crossorigin="anonymous"></script>

    <?php
// Itera sobre as páginas e adiciona a classe "active" se a URL atual corresponder
foreach ($paginas as $url => $nome_da_pagina) {
    if ($url_atual === $url) {
        echo "<title>Ferraz Dojos | $nome_da_pagina </title>";
    }
}
?>

    <script src="https://cdn.tiny.cloud/1/a0nk30p1g63rjh3gknotzn47pzsmxr7n6pfezilpk8lct92z/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand bg-light rounded px-1 py-1 text-dark" href="inicio.php"><img src="../img/kenshydokan.jpg" width="30" height="30" alt="logo da kenshydokan"> Kenshydokan</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#conteudoNavbarSuportado" aria-controls="conteudoNavbarSuportado" aria-expanded="false" aria-label="Alterna navegação">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="conteudoNavbarSuportado">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item <?php echo ($url_atual == "/views/inicio.php") ? 'active' : ''; ?>">
                <a class="nav-link" href="inicio.php">Home <span class="sr-only">(página atual)</span></a>
            </li>
            <li class="nav-item <?php echo ($url_atual == "/views/sobre.php") ? 'active' : ''; ?>">
                <a class="nav-link" href="sobre.php">Sobre</a>
            </li>
            <li class="nav-item dropdown <?php echo ($url_atual == "/views/filiar.php" || $url_atual == "/views/filiados.php") ? 'active' : ''; ?>">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Filiação</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item <?php echo ($url_atual == "/views/filiar.php") ? 'active' : ''; ?>" href="filiar.php">Filiar-se</a>
                    <a class="dropdown-item <?php echo ($url_atual == "/views/filiados.php") ? 'active' : ''; ?>" href="filiados.php">Filiados</a>
            </li>
            <li class="nav-item <?php echo ($url_atual == "/views/galeria.php") ? 'active' : ''; ?>">
                <a class="nav-link" href="galeria.php">Galeria</a>
            </li>
            <li class="nav-item <?php echo ($url_atual == "/views/postagens.php") ? 'active' : ''; ?>">
                <a class="nav-link" href="postagens.php">Postagens</a>
            </li>
            <li class="nav-item <?php echo ($url_atual == "/views/artes_marciais.php") ? 'active' : ''; ?>">
                <a class="nav-link disabled" href="#">Artes Marciais</a>
            </li>
            <li class="nav-item dropdown <?php echo ($url_atual == "/views/campeonatos.php") ? 'active' : ''; ?>">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-journal-text"></i> Campeonatos</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item <?php echo ($url_atual == "/views/campeonatos.php") ? 'active' : ''; ?>" href="campeonatos.php">Agenda de Campeonatos</a>
                    <a class="dropdown-item <?php echo ($url_atual == "/views/campeonatos.php") ? 'active' : ''; ?>" href="campeonatos.php">Se Inscreva</a>
            </li>
            <li class="nav-item <?php echo ($url_atual == "/views/contato.php") ? 'active' : ''; ?>">
                <a class="nav-link" href="contato.php">Contato</a>
            </li>
            <li class="nav-item dropdown <?php echo ($url_atual == "/views/perfil.php" || $url_atual == "/views/login.php") ? 'active' : ''; ?>">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Minha Conta
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <?php if (isset($_SESSION['user_id'])) {?>
                <a class="dropdown-item <?php echo ($url_atual == "/views/perfil.php") ? 'active' : ''; ?>" href="perfil.php">Perfil</a>
                <a class="dropdown-item" href="../controllers/sair.php">Sair</a>
                <?php } else {?>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item <?php echo ($url_atual == "/views/login.php") ? 'active' : ''; ?>" href="login.php">Login</a>
                <?php }?>
                </div>
            </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Pesquisar" aria-label="Pesquisar">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Pesquisar</button>
            </form>
        </div>
    </nav>