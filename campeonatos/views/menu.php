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

    <!-- Folha de Estilos Exclusiva Kenshydokan Campeonatos -->
    <link rel="stylesheet" href="../css/custom.css">
</head>
<body>

<!-- Navbar Principal com Design Marcial Premium -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-kenshy sticky-top">
    <div class="container-fluid px-lg-4 px-3">
        <a class="navbar-brand" href="inicio.php">
            <span class="p-2 me-2 rounded-circle bg-danger bg-opacity-25 border border-danger d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <i class="fas fa-torii-gate text-danger"></i>
            </span>
            <div class="d-flex flex-column lh-1">
                <span class="fw-bold tracking-wider fs-6">KENSHYDOKAN JUTSU</span>
                <span class="text-danger small fw-bold" style="font-size: 0.68rem; letter-spacing: 0.14em;">CAMPEONATOS &bull; SÚMULA DIGITAL</span>
            </div>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <i class="fas fa-bars text-white"></i>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <li class="nav-item">
                    <a class="nav-link <?= ($paginaAtual === 'inicio.php') ? 'active' : '' ?>" href="inicio.php">
                        <i class="fas fa-home me-1"></i> Painel Geral
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= in_array($paginaAtual, ['campeonatos.php', 'detalhes_campeonato.php', 'criar_campeonato.php', 'editar_campeonato.php']) ? 'active' : '' ?>" href="campeonatos.php">
                        <i class="fas fa-trophy me-1"></i> Campeonatos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($paginaAtual === 'validar_certificado.php') ? 'active' : '' ?>" href="validar_certificado.php" target="_blank">
                        <i class="fas fa-shield-alt me-1"></i> Validador Público
                    </a>
                </li>
                <?php if (isset($_SESSION['id_usuario'])): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($paginaAtual === 'auditoria.php') ? 'active' : '' ?>" href="auditoria.php">
                            <i class="fas fa-history me-1"></i> Auditoria
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <?php if (isset($_SESSION['id_usuario'])): ?>
                    <li class="nav-item dropdown">
                        <a class="navbar-user-pill dropdown-toggle text-decoration-none" href="#" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-shield text-danger"></i>
                            <span><?= htmlspecialchars($nomeUsuario) ?></span>
                            <span class="badge bg-danger text-uppercase ms-1" style="font-size: 0.62rem;"><?= htmlspecialchars($nivelUsuario) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="border-radius: 12px;">
                            <li class="px-3 py-2 border-bottom">
                                <div class="fw-bold small text-dark"><?= htmlspecialchars($nomeUsuario) ?></div>
                                <div class="small text-muted text-uppercase" style="font-size: 0.7rem;">Operador: <?= htmlspecialchars($nivelUsuario) ?></div>
                            </li>
                            <li><a class="dropdown-item py-2 text-danger fw-bold" href="../controllers/sair.php"><i class="fas fa-sign-out-alt me-2"></i>Sair do Sistema</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-danger" href="login.php">
                            <i class="fas fa-sign-in-alt me-1"></i> Acessar Sistema
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid px-lg-4 px-3 py-4 flex-grow-1">
