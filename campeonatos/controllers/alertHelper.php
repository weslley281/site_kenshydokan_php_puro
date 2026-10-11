<?php
/**
 * Helper de Alertas e Redirecionamentos com AlertifyJS
 * Padrão IDÊNTICO ao site principal (controllers/alertHelper.php)
 */

if (!function_exists('exibirMensagemEredirecionar')) {
    function exibirMensagemEredirecionar($mensagem, $destino)
    {
        $mensagemEscapada = addslashes($mensagem);
        $destinoEscapado = addslashes($destino);

        echo "<!DOCTYPE html>
<html lang='pt-br'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
    <link rel='stylesheet' href='../libs/bootstrap/css/bootstrap.min.css'>
    <link rel='stylesheet' href='../libs/alertifyjs/css/alertify.min.css' />
    <link rel='stylesheet' href='../libs/alertifyjs/css/themes/bootstrap.min.css' />
    <style>
        .alertify .ajs-header {
            font-weight: bold !important;
            border-bottom: 3px solid #dc2626 !important;
            background: #111827 !important;
            color: white !important;
            font-family: 'Montserrat', sans-serif !important;
            padding: 12px 24px !important;
        }
        .alertify .ajs-footer {
            background: #f8f9fa !important;
            border-top: 1px solid rgba(0,0,0,0.05) !important;
            padding: 12px 24px !important;
        }
        .ajs-content {
            font-size: 1.05rem !important;
            padding: 24px !important;
            font-family: 'Roboto', sans-serif !important;
            color: #1f2937 !important;
        }
        .ajs-button {
            border-radius: 50rem !important;
            font-weight: bold !important;
            padding: 8px 24px !important;
        }
    </style>
</head>
<body class='bg-light'>
    <script src='../libs/bootstrap/js/jquery.min.js'></script>
    <script src='../libs/alertifyjs/alertify.min.js'></script>
    <script>
        alertify.defaults.transition = 'slide';
        alertify.defaults.theme.ok = 'btn btn-danger rounded-pill px-4';
        alertify.defaults.theme.cancel = 'btn btn-secondary rounded-pill px-4';
        alertify.defaults.glossary.title = 'Kenshydokan Campeonatos';

        alertify.alert('Notificação', '{$mensagemEscapada}', function() {
            window.location.href = '{$destinoEscapado}';
        });
    </script>
</body>
</html>";
        exit;
    }
}
