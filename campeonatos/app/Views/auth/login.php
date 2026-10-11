<?php
use App\Core\Router;
use App\Core\Csrf;
use App\Core\Session;

$base = Router::getBasePath();
$flashError = Session::flash('error');
$flashSuccess = Session::flash('success');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Acesso ao Sistema') ?></title>
    <link rel="stylesheet" href="<?= $base ?>/assets/vendor/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= $base ?>/assets/vendor/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="<?= $base ?>/assets/css/app.css">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card-kenshy p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex p-3 rounded-circle bg-danger bg-opacity-10 text-danger mb-3">
                            <i class="fa-solid fa-trophy fa-3x"></i>
                        </div>
                        <h4 class="fw-bold text-white mb-1">KENSHYDOKAN JUTSU</h4>
                        <p class="text-muted small">Gestão de Campeonatos & Súmulas</p>
                    </div>

                    <?php if ($flashError): ?>
                        <div class="alert alert-danger py-2 small mb-4" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> <?= htmlspecialchars($flashError) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($flashSuccess): ?>
                        <div class="alert alert-success py-2 small mb-4" role="alert">
                            <i class="fa-solid fa-circle-check me-1"></i> <?= htmlspecialchars($flashSuccess) ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= $base ?>/login" method="POST">
                        <?= Csrf::field() ?>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold">E-mail Cadastrado</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="admin@kenshydokan.org.br" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-semibold">Senha de Acesso</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="senha" class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-kenshy w-100 py-2">
                            <i class="fa-solid fa-arrow-right-to-bracket me-2"></i> Entrar no Sistema
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="<?= $base ?>/validar-certificado" class="text-muted small text-decoration-none">
                            <i class="fa-solid fa-stamp me-1"></i> Validar Autenticidade de Certificado
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
