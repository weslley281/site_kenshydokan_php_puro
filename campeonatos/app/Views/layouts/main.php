<?php
use App\Core\Session;
use App\Core\Router;

$base = Router::getBasePath();
$flashSuccess = Session::flash('success');
$flashError = Session::flash('error');
$flashWarning = Session::flash('warning');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Sistema de Campeonatos e Súmulas Kenshydokan Jutsu') ?></title>

    <!-- Fontes e Ícones Locais -->
    <link rel="stylesheet" href="<?= $base ?>/assets/vendor/fontawesome/css/all.min.css">

    <!-- CSS Vendor Local -->
    <link rel="stylesheet" href="<?= $base ?>/assets/vendor/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="<?= $base ?>/assets/vendor/datatables/datatables.css">
    <link rel="stylesheet" href="<?= $base ?>/assets/vendor/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?= $base ?>/assets/vendor/alertifyjs/css/alertify.min.css">
    <link rel="stylesheet" href="<?= $base ?>/assets/vendor/alertifyjs/css/themes/default.min.css">

    <!-- CSS do Sistema -->
    <link rel="stylesheet" href="<?= $base ?>/assets/css/app.css">
</head>
<body>

    <?php include __DIR__ . '/navbar.php'; ?>

    <main class="container-fluid py-4 px-lg-5 flex-grow-1">
        <?php if ($flashSuccess): ?>
            <div class="alert alert-success alert-dismissible fade show card-kenshy mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2 text-success"></i>
                <strong>Sucesso:</strong> <?= htmlspecialchars($flashSuccess) ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($flashError): ?>
            <div class="alert alert-danger alert-dismissible fade show card-kenshy mb-4" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i>
                <strong>Atenção:</strong> <?= htmlspecialchars($flashError) ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($flashWarning): ?>
            <div class="alert alert-warning alert-dismissible fade show card-kenshy mb-4" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2 text-warning"></i>
                <strong>Aviso:</strong> <?= htmlspecialchars($flashWarning) ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?= $content ?>
    </main>

    <?php include __DIR__ . '/footer.php'; ?>

    <!-- JS Vendor Local -->
    <script src="<?= $base ?>/assets/vendor/bootstrap/jquery.js"></script>
    <script src="<?= $base ?>/assets/vendor/bootstrap/popper.js"></script>
    <script src="<?= $base ?>/assets/vendor/bootstrap/bootstrap.js"></script>
    <script src="<?= $base ?>/assets/vendor/datatables/datatables.js"></script>
    <script src="<?= $base ?>/assets/vendor/select2/js/select2.min.js"></script>
    <script src="<?= $base ?>/assets/vendor/chartjs/chart.umd.js"></script>
    <script src="<?= $base ?>/assets/vendor/alertifyjs/alertify.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicialização padrão do Select2
            $('.select2').select2({
                width: '100%'
            });

            // Inicialização padrão de DataTables
            $('.datatable').DataTable({
                language: {
                    url: false,
                    "emptyTable": "Nenhum registro encontrado",
                    "info": "Exibindo _START_ até _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "infoFiltered": "(filtrado de _MAX_ registros no total)",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "loadingRecords": "Carregando...",
                    "processing": "Processando...",
                    "search": "Pesquisar:",
                    "zeroRecords": "Nenhum registro compatível encontrado",
                    "paginate": {
                        "first": "Primeiro",
                        "last": "Último",
                        "next": "Próximo",
                        "previous": "Anterior"
                    }
                },
                responsive: true
            });
        });
    </script>
</body>
</html>
