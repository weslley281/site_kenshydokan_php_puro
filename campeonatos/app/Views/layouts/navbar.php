<?php
use App\Core\Session;
use App\Core\Router;

$base = Router::getBasePath();
$user = Session::user();
$isLoggedIn = Session::isLoggedIn();
$isAdmin = Session::isAdmin();
$currentUri = Router::resolveCurrentPath();
?>
<nav class="navbar navbar-expand-lg navbar-kenshy sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= $base ?>/">
            <i class="fa-solid fa-trophy text-danger"></i>
            <span>KENSHYDOKAN JUTSU</span>
            <span class="brand-badge">Súmulas & Chaves</span>
        </a>

        <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="navContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <li class="nav-item">
                    <a class="nav-link-kenshy <?= ($currentUri === '/' || $currentUri === '/dashboard') ? 'active' : '' ?>" href="<?= $base ?>/dashboard">
                        <i class="fa-solid fa-chart-line me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link-kenshy <?= str_starts_with($currentUri, '/campeonatos') ? 'active' : '' ?>" href="<?= $base ?>/campeonatos">
                        <i class="fa-solid fa-medal me-1"></i> Campeonatos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link-kenshy" href="<?= $base ?>/validar-certificado">
                        <i class="fa-solid fa-stamp me-1"></i> Validar Certificado
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
                <?php if ($isLoggedIn): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link-kenshy dropdown-toggle text-white d-flex align-items-center gap-2" href="#" id="userDrop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-circle-user fa-lg text-danger"></i>
                            <span><?= htmlspecialchars($user['nome'] ?? 'Operador') ?></span>
                            <span class="badge bg-secondary"><?= htmlspecialchars($user['papel'] ?? '') ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" aria-labelledby="userDrop">
                            <li><h6 class="dropdown-header"><?= htmlspecialchars($user['email'] ?? '') ?></h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= $base ?>/logout"><i class="fa-solid fa-right-from-bracket me-2"></i>Sair do Sistema</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-kenshy btn-sm" href="<?= $base ?>/login">
                            <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Acessar Painel
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
