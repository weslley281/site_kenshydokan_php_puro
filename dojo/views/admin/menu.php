<?php
// views/admin/menu.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Garante que apenas Sensei (Administrador) tenha acesso à área administrativa
if (!isset($_SESSION['id_usuario']) || $_SESSION['nivel'] !== 'sensei') {
    header("Location: ../login.php");
    exit();
}

$url_atual = $_SERVER['REQUEST_URI'];
$pagina_ativa = $_GET['pagina'] ?? 'gerenciamento_dojo';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Administração - Gerenciador de Dojô</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    
    <!-- Select2 -->
    <link rel="stylesheet" href="../../libs/select2/css/select2.min.css" />
    
    <!-- DataTables -->
    <link rel="stylesheet" href="../../libs/DataTables/datatables.css" />
    
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/e880bf5077.js" crossorigin="anonymous"></script>

    <!-- Google Font Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../css/custom.css" />
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #1f1f1f 0%, #111111 100%);
            border-bottom: 3px solid #dc3545;
        }
        .navbar-brand img {
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-3 shadow">
        <div class="container-fluid px-4">
            <a class="navbar-brand font-weight-bold" href="index.php?pagina=gerenciamento_dojo">
                <i class="fa-solid fa-store mr-2"></i> Gerenciador de Dojô
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item <?php echo ($pagina_ativa == 'gerenciamento_dojo') ? 'active' : ''; ?>">
                        <a class="nav-link font-weight-bold" href="index.php?pagina=gerenciamento_dojo"><i class="fa-solid fa-calculator mr-1"></i> Gestão Financeira</a>
                    </li>
                    <li class="nav-item <?php echo ($pagina_ativa == 'chamada') ? 'active' : ''; ?>">
                        <a class="nav-link font-weight-bold" href="index.php?pagina=chamada"><i class="fa-solid fa-clipboard-user mr-1"></i> Frequência</a>
                    </li>
                    <li class="nav-item <?php echo ($pagina_ativa == 'filiados') ? 'active' : ''; ?>">
                        <a class="nav-link font-weight-bold" href="index.php?pagina=filiados"><i class="fa-solid fa-users mr-1"></i> Alunos</a>
                    </li>
                    <li class="nav-item <?php echo ($pagina_ativa == 'exames') ? 'active' : ''; ?>">
                        <a class="nav-link font-weight-bold" href="index.php?pagina=exames"><i class="fa-solid fa-medal mr-1"></i> Exames</a>
                    </li>
                    <li class="nav-item <?php echo ($pagina_ativa == 'dojos') ? 'active' : ''; ?>">
                        <a class="nav-link font-weight-bold" href="index.php?pagina=dojos"><i class="fa-solid fa-store mr-1"></i> Dojos</a>
                    </li>
                    <li class="nav-item <?php echo ($pagina_ativa == 'usuarios') ? 'active' : ''; ?>">
                        <a class="nav-link font-weight-bold" href="index.php?pagina=usuarios"><i class="fa-solid fa-user-lock mr-1"></i> Operadores</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle font-weight-bold text-white-50" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa-solid fa-gears mr-1"></i> Tabelas
                        </a>
                        <div class="dropdown-menu border-0 shadow" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item font-weight-bold <?php echo ($pagina_ativa == 'graduacoes') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=graduacoes">Faixas / Graduações</a>
                            <a class="dropdown-item font-weight-bold <?php echo ($pagina_ativa == 'artes') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=artes">Modalidades / Artes</a>
                        </div>
                    </li>
                </ul>
                
                <div class="navbar-nav ml-auto align-items-center">
                    <span class="text-white-50 mr-3 small">Sensei: <strong><?php echo htmlspecialchars($_SESSION['nome']); ?></strong></span>
                    <a href="../../controllers/sair.php" class="btn btn-outline-light btn-sm rounded-pill px-3 font-weight-bold"><i class="fa-solid fa-sign-out-alt mr-1"></i> Sair</a>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="py-4">
