<?php
session_start();
include_once __DIR__ . "/../controllers/verificar_lembrar_me.php";
include_once __DIR__ . "/../controllers/contador_paginas.php";
// Obtém o caminho da URL atual
$url_atual = $_SERVER['REQUEST_URI'];

// Query registered martial arts for menu
$artes_marciais_menu = [];
try {
    include_once __DIR__ . "/../db/conexao.php";
    if (class_exists('Conexao')) {
        $dbConnMenu = new Conexao();
        $conexaoMenu = $dbConnMenu->conectar();
        if ($conexaoMenu) {
            $resultMenu = $conexaoMenu->query("SELECT id_arte, nome FROM artes_marciais ORDER BY id_arte ASC");
            if ($resultMenu) {
                while ($rowMenu = $resultMenu->fetch_assoc()) {
                    $artes_marciais_menu[] = $rowMenu;
                }
            }
            $conexaoMenu->close();
        }
    }
} catch (Throwable $t) {
    // Fail silently
}

// Define um array associativo com os URLs das páginas e seus nomes no menu
$paginas = array(
    '/views/inicio.php' => 'Início',
    '/views/sobre.php' => 'Sobre Nós',
    '/views/login.php' => 'Sistema',
    '/views/postagens.php' => 'Postagens',
    '/views/transparencia.php' => 'Transparência',
    '/views/ver_perfil.php' => 'Perfil',
    '/views/perfil/perfil.php' => 'Perfil',
    '/views/galeria.php' => 'Galeria',
    '/views/filiar.php' => 'Filiar-se',
    '/views/filiados.php' => 'Filiados',
    '/views/perfil/exame_graduacao.php' => 'Exame Graduação',
    '/views/editar_postagem' => 'Editar Postagem',
    '/views/perfil/editar_perfil.php' => 'Perfil',
    '/views/perfil/documentos.php' => 'Documentos',
    '/views/perfil/criar_postagem.php' => 'Postagem',
    '/views/contato.php' => 'Contato',
    '/views/campeonatos.php' => 'Campeonatos',
    '/views/cadastrar.php' => 'Cadastrar',
    '/views/katas.php' => 'Katas',
    '/views/atemi_waza.php' => 'Atemi Waza',
    '/views/nage_waza.php' => 'Nage Waza',
    '/views/katame_waza.php' => 'Katame Waza',
);

// --- Lógica para Meta Tags Dinâmicas ---
$pageTitle = isset($pageTitle) ? $pageTitle : 'Kenshydokan Karatê';
$pageDescription = isset($pageDescription) ? $pageDescription : 'Somos uma instituição, criada com o intuito de divulgar o karate kenshydokan e outras artes marciais.';
$ogImage = isset($ogImage) ? $ogImage : 'https://www.kenshydokan.com/img/logo_instituto.jpg'; // Imagem padrão
$pageUrl = 'https://www.SEUSITE.com.br' . $_SERVER['REQUEST_URI'];
// --- Fim da Lógica ---

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=TAG_ID

"></script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-118512913-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-118512913-1');
    </script>

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "SportsOrganization",
            "name": "Instituto de Artes Marciais e Defesa Pessoal Kenshydokan",
            "alternateName": "Kenshydokan Karate Institute",
            "url": "https://kenshydokan.com/",
            "description": "Organização dedicada ao ensino do Karatê Kenshydokan.",
            "founder": {
                "@type": "Person",
                "name": "Shihan Jonas Teixeira de Andrade",
                "jobTitle": "Criador e Presidente"
            },
            "member": {
                "@type": "Person",
                "name": "Weslley Henrique Vieira Ferraz",
                "jobTitle": "Discípulo Direto do Fundador e Instrutor Kenshydokan de nivel mais elevado"
            },
            "location": {
                "@type": "Place",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "Rua 24 de Outubro, 154",
                    "addressLocality": "Várzea Grande",
                    "addressRegion": "MT",
                    "postalCode": "78110-350",
                    "addressCountry": "BR"
                }
            }
        }
    </script>



    <meta name="author" content="Weslley Henrique Vieira Ferraz" />
    <meta name="owner" content="Instituto de Artes Marciais e Defesa Pessoal Kenshydokan" />
    <meta name="copyright" content="Weslley Henrique Vieira Ferraz" />
    <meta name="keywords" content="kenshydokan, kyokushin, federação, karate, carate, karatê, caratê, de contato, full, contact, luta, aula, aulas, Karatê, kata, kumite, mato grosso, cuiaba, varzea grande, weslley ferraz, weslley, ferraz, judo, judô, kodokan, jiu, jiu jitsu, muay thai, muay boran, kickboxing">
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta http-equiv="refresh" content="3600">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($ogImage); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($pageUrl); ?>">
    <meta property="og:type" content="website">

    <link rel="icon" href="../img/wkka.jpg" type="image/jpg">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- Video JS -->
    <link href="https://vjs.zencdn.net/8.6.0/video-js.css" rel="stylesheet" />

    <!-- Select2 -->
    <link rel="stylesheet" href="../libs/select2/css/select2.min.css" />
    <link rel="stylesheet" href="../../libs/select2/css/select2.min.css" />
    <link rel="stylesheet" href="../../../libs/select2/css/select2.min.css" />
    <link href="https://vjs.zencdn.net/7.11.4/video-js.css" rel="stylesheet">

    <link rel="stylesheet" href="../libs/DataTables/datatables.css" />
    <link rel="stylesheet" href="../../libs/DataTables/datatables.css" />
    
    <!-- Custom Modern Theme CSS -->
    <link rel="stylesheet" href="../css/custom.css" />
    <link rel="stylesheet" href="../../css/custom.css" />
    <?php
    // Itera sobre as páginas e adiciona a classe "active" se a URL atual corresponder
    // foreach ($paginas as $url => $nome_da_pagina) {
    //     if ($url_atual === $url) {
    //         echo "<title>Kenshydokan | $nome_da_pagina </title>";
    //     }
    // }

    contar_pagina($url_atual);
    ?>

    <script src="../libs/tinymce/tinymce.min.js"></script>
    <script src="../../libs/tinymce/tinymce.min.js"></script>
    
    <style>
        /* Estilização Premium do Google Translate */
        body {
            top: 0px !important;
            position: static !important;
        }
        .goog-te-banner-frame.skiptranslate, 
        .goog-te-banner-frame,
        #goog-gt-tt,
        .goog-te-balloon-frame {
            display: none !important;
        }
        .goog-logo-link {
            display: none !important;
        }
        .goog-te-gadget {
            color: transparent !important;
            font-size: 0px !important;
            display: flex !important;
            align-items: center !important;
        }
        .goog-te-combo {
            background-color: #2b3035 !important;
            color: #f8f9fa !important;
            border: 1px solid #495057 !important;
            border-radius: 30px !important;
            padding: 5px 12px !important;
            font-size: 0.85rem !important;
            font-family: inherit !important;
            outline: none !important;
            cursor: pointer !important;
            font-weight: 600 !important;
            transition: all 0.25s ease-in-out !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1) !important;
        }
        .goog-te-combo:hover {
            border-color: #dc3545 !important;
            background-color: #343a40 !important;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.25) !important;
        }
        .goog-te-combo option {
            background-color: #212529 !important;
            color: #fff !important;
        }
        /* Oculta barra de ferramentas do Google no topo */
        .skiptranslate {
            margin-top: 0px !important;
        }
    </style>
</head>

<body class="bg-light">
    <!-- <div class="alert alert-warning text-center mb-0" role="alert">
        <strong>Aviso:</strong> O site estará em manutenção entre os dias 24/08/2025 e 30/08/2025, sendo assim poderá haver algumas inconcistencias nas páginas. Pedimos desculpas pelo transtorno.
    </div> -->

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand bg-light rounded px-1 py-1 text-dark" href="inicio.php"><img src="../img/wkka.jpg" width="30" height="30" alt="logo da kenshydokan"> Kenshydokan</a>
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

                <li class="nav-item <?php echo ($url_atual == "/views/transparencia.php") ? 'active' : ''; ?>">
                    <a class="nav-link" href="transparencia.php">Transparência</a>
                </li>

                <li class="nav-item dropdown <?php echo ($url_atual == "/views/artes_marciais.php") ? 'active' : ''; ?>">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-journal-text"></i> Artes Marciais</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item <?php echo ($url_atual == "/views/artes_marciais.php") ? 'active' : ''; ?>" href="katas.php">Kata</a>
                        <a class="dropdown-item <?php echo ($url_atual == "/views/artes_marciais.php") ? 'active' : ''; ?>" href="atemi_waza.php">Atemi Waza</a>
                        <a class="dropdown-item <?php echo ($url_atual == "/views/artes_marciais.php") ? 'active' : ''; ?>" href="nage_waza.php">Nage Waza</a>
                        <a class="dropdown-item <?php echo ($url_atual == "/views/artes_marciais.php") ? 'active' : ''; ?>" href="katame_waza.php">Katame Waza</a>
                        <?php if (!empty($artes_marciais_menu)): ?>
                            <div class="dropdown-divider"></div>
                            <?php foreach ($artes_marciais_menu as $am): ?>
                                <a class="dropdown-item" href="arte_marcial.php?id=<?php echo $am['id_arte']; ?>"><?php echo htmlspecialchars($am['nome']); ?></a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </li>

                <li class="nav-item dropdown <?php echo ($url_atual == "/views/campeonatos.php") ? 'active' : ''; ?>">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-journal-text"></i> Campeonatos</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item <?php echo ($url_atual == "/views/campeonatos.php") ? 'active' : ''; ?>" href="campeonatos.php">Agenda de Campeonatos</a>
                        <a class="dropdown-item <?php echo ($url_atual == "/views/campeonatos.php") ? 'active' : ''; ?>" href="campeonatos.php">Se Inscreva</a>
                    </div>
                </li>

                <li class="nav-item <?php echo ($url_atual == "/views/contato.php") ? 'active' : ''; ?>">
                    <a class="nav-link" href="contato.php">Contato</a>
                </li>

                <li class="nav-item dropdown <?php echo (strpos($url_atual, "/views/perfil/") !== false || $url_atual == "/views/login.php") ? 'active' : ''; ?>">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Minha Conta
                    </a>

                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php if (isset($_SESSION['id_usuario'])) { ?>
                            <a class="dropdown-item <?php echo (strpos($url_atual, "/views/perfil/") !== false) ? 'active' : ''; ?>" href="perfil/perfil.php">Perfil</a>
                            <a class="dropdown-item" href="../controllers/sair.php">Sair</a>

                        <?php } else { ?>

                            <a class="dropdown-item <?php echo ($url_atual == "/views/login.php") ? 'active' : ''; ?>" href="login.php">Login</a>

                            <a class="dropdown-item <?php echo ($url_atual == "/views/cadastrar.php") ? 'active' : ''; ?>" href="cadastrar.php">Cadastrar-se</a>

                        <?php } ?>
                    </div>
                </li>
            </ul>
            
            <!-- Google Translate Element Integrado -->
            <div class="d-flex align-items-center ml-lg-3 my-2 my-lg-0" id="google_translate_container">
                <i class="fa-solid fa-language text-white mr-2" style="font-size: 1.15rem; opacity: 0.85;"></i>
                <div id="google_translate_element"></div>
            </div>
            
            <script type="text/javascript">
                function googleTranslateElementInit() {
                    new google.translate.TranslateElement({
                        pageLanguage: 'pt',
                        includedLanguages: 'pt,en,es,fr,it,ja'
                    }, 'google_translate_element');
                }
            </script>
            <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        </div>
    </nav>