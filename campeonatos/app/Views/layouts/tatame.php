<?php
use App\Core\Router;
$base = Router::getBasePath();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Súmula Digital de Tatame | Kenshydokan Jutsu') ?></title>

    <!-- Fontes e Ícones -->
    <link rel="stylesheet" href="<?= $base ?>/assets/vendor/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= $base ?>/assets/vendor/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="<?= $base ?>/assets/vendor/alertifyjs/css/alertify.min.css">
    <link rel="stylesheet" href="<?= $base ?>/assets/vendor/alertifyjs/css/themes/default.min.css">

    <!-- CSS de Tatame -->
    <link rel="stylesheet" href="<?= $base ?>/assets/css/app.css">
    <link rel="stylesheet" href="<?= $base ?>/assets/css/tatame.css">
</head>
<body class="bg-black">
    <div class="tatame-container">
        <?= $content ?>
    </div>

    <!-- Scripts Locais -->
    <script src="<?= $base ?>/assets/vendor/bootstrap/jquery.js"></script>
    <script src="<?= $base ?>/assets/vendor/bootstrap/bootstrap.js"></script>
    <script src="<?= $base ?>/assets/vendor/alertifyjs/alertify.min.js"></script>
</body>
</html>
