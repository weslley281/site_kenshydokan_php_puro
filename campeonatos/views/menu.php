<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nomeUsuario = $_SESSION['nome'] ?? 'Visitante';
$nivelUsuario = $_SESSION['nivel'] ?? 'leitor';
$paginaAtual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= htmlspecialchars($pageTitle ?? 'Sistema Oficial de Campeonatos | Kenshydokan Jutsu') ?></title>

    <!-- Fontes e Ícones -->
    <link rel="stylesheet" href="../libs/fontawesome-free-6.5.2-web/css/all.min.css">
    <link rel="stylesheet" href="../libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../libs/alertifyjs/css/alertify.min.css">
    <link rel="stylesheet" href="../libs/alertifyjs/css/themes/bootstrap.min.css">
    <link rel="stylesheet" href="../libs/select2/css/select2.min.css">
    <link rel="stylesheet" href="../libs/DataTables/datatables.min.css">

    <!-- Estilo Customizado Kenshydokan -->
    <style>
        :root {
            --primary: #dc2626;
            --primary-dark: #b91c1c;
            --dark: #111827;
            --dark-card: #1f2937;
        }
        body {
            background-color: #f3f4f6;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #1f2937;
        }
        .navbar-kenshy {
            background-color: #111827;
            border-bottom: 3px solid #dc2626;
        }
        .navbar-kenshy .nav-link {
            color: #d1d5db !important;
            font-weight: 500;
            padding: 8px 16px;
            transition: all 0.2s;
        }
        .navbar-kenshy .nav-link:hover, .navbar-kenshy .nav-link.active {
            color: #ffffff !important;
            background-color: rgba(220, 38, 38, 0.2);
            border-radius: 6px;
        }
        .card {
            border-radius: 10px;
        }
        .badge {
            font-weight: 600;
        }
        .btn-danger {
            background-color: #dc2626;
            border-color: #dc2626;
        }
        .btn-danger:hover {
            background-color: #b91c1c;
            border-color: #b91c1c;
        }
    </style>
</head>
<body>

<!-- Navbar Principal -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-kenshy shadow-sm sticky-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="inicio.php">
            <i class="fas fa-torii-gate text-danger fa-lg me-2"></i>
            <span>KENSHYDOKAN <span class="badge bg-danger ms-1 fs-7">CAMPEONATOS</span></span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= ($paginaAtual === 'inicio.php') ? 'active' : '' ?>" href="inicio.php">
                        <i class="fas fa-home me-1"></i> Painel Geral
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($paginaAtual === 'campeonatos.php' || $paginaAtual === 'detalhes_campeonato.php') ? 'active' : '' ?>" href="campeonatos.php">
                        <i class="fas fa-trophy me-1"></i> Campeonatos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($paginaAtual === 'validar_certificado.php') ? 'active' : '' ?>" href="validar_certificado.php">
                        <i class="fas fa-qrcode me-1"></i> Validador de Certificados
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (isset($_SESSION['id_usuario'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-lg me-2 text-danger"></i>
                            <span><?= htmlspecialchars($nomeUsuario) ?></span>
                            <span class="badge bg-secondary ms-2 text-uppercase" style="font-size: 0.65rem;"><?= htmlspecialchars($nivelUsuario) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><span class="dropdown-item-text small text-muted">Conectado como <strong><?= htmlspecialchars($nivelUsuario) ?></strong></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="../controllers/sair.php"><i class="fas fa-sign-out-alt me-2"></i>Sair do Sistema</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-outline-danger" href="login.php">
                            <i class="fas fa-sign-in-alt me-1"></i> Acessar Sistema
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid px-4 py-4">
