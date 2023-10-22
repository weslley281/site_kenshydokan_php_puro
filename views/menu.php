<?php
session_start();
include_once "../controllers/contador_paginas.php";
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
<!DOCTYPE html>
<html lang="pt-br">
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

    <!-- Video JS -->
    <link href="https://vjs.zencdn.net/8.6.0/video-js.css" rel="stylesheet" />

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../libs/DataTables/datatables.css" />
    <?php
// Itera sobre as páginas e adiciona a classe "active" se a URL atual corresponder
foreach ($paginas as $url => $nome_da_pagina) {
    if ($url_atual === $url) {
        echo "<title>Ferraz Dojos | $nome_da_pagina </title>";
    }
}

contar_pagina($url_atual);
?>

    <script src="../libs/tinymce/tinymce.min.js"></script>
  </head>
  <body class="bg-light">
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
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Filiação</a>
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
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-journal-text"></i> Campeonatos</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item <?php echo ($url_atual == "/views/campeonatos.php") ? 'active' : ''; ?>" href="campeonatos.php">Agenda de Campeonatos</a>
                        <a class="dropdown-item <?php echo ($url_atual == "/views/campeonatos.php") ? 'active' : ''; ?>" href="campeonatos.php">Se Inscreva</a>
                </li>

                <li class="nav-item <?php echo ($url_atual == "/views/contato.php") ? 'active' : ''; ?>">
                    <a class="nav-link" href="contato.php">Contato</a>
                </li>

                <li class="nav-item dropdown <?php echo ($url_atual == "/views/perfil.php" || $url_atual == "/views/login.php") ? 'active' : ''; ?>">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Minha Conta
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php if (isset($_SESSION['id_usuario'])) {?>
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