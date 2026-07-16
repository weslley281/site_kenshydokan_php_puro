<?php
if (!function_exists('exibirMensagemEredirecionar')) {
    function exibirMensagemEredirecionar($mensagem, $destino)
    {
        // Calcular profundidade da URL em relação à raiz do site
        $script_name = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
        $project_name = 'site_kenshydokan_php_puro';
        $pos = strpos($script_name, '/' . $project_name . '/');
        if ($pos !== false) {
            $subpath = substr($script_name, $pos + strlen($project_name) + 2);
        } else {
            $subpath = ltrim($script_name, '/');
        }
        $levels = substr_count(dirname($subpath), '/');
        $relative_path = ($levels === 0 && dirname($subpath) === '.') ? '' : str_repeat('../', $levels + 1);

        echo "<!DOCTYPE html>
<html lang='pt-br'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
    <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' integrity='sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO' crossorigin='anonymous'>
    <link rel='stylesheet' href='{$relative_path}libs/alertifyjs/css/alertify.min.css' />
    <link rel='stylesheet' href='{$relative_path}libs/alertifyjs/css/themes/bootstrap.min.css' />
    <style>
        .alertify .ajs-header {
            font-weight: bold !important;
            border-bottom: 3px solid #d9232d !important;
            background: #1a1a1a !important;
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
            font-size: 1.1rem !important;
            padding: 24px !important;
            font-family: 'Roboto', sans-serif !important;
            color: #212529 !important;
        }
        .ajs-button {
            border-radius: 50rem !important;
            font-weight: bold !important;
            padding: 8px 24px !important;
        }
    </style>
</head>
<body class='bg-light'>
    <script src='https://code.jquery.com/jquery-3.3.1.slim.min.js' integrity='sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo' crossorigin='anonymous'></script>
    <script src='{$relative_path}libs/alertifyjs/alertify.min.js'></script>
    <script>
        alertify.defaults.transition = 'slide';
        alertify.defaults.theme.ok = 'btn btn-danger rounded-pill px-4';
        alertify.defaults.theme.cancel = 'btn btn-secondary rounded-pill px-4';
        alertify.defaults.glossary.title = 'Kenshydokan';
        
        $(document).ready(function() {
            alertify.alert('Mensagem', '" . addslashes($mensagem) . "', function(){
                window.location.href = '" . addslashes($destino) . "';
            });
        });
    </script>
</body>
</html>";
        exit;
    }
}
