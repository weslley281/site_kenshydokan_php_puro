<?php
include_once __DIR__ . "/../repositorios/CertificadoRepositorio.php";
include_once __DIR__ . "/../repositorios/usuarioRepositorio.php";
include_once __DIR__ . "/../repositorios/cursoRepositorio.php";

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}

$codigo_verificacao = $_GET['codigo'] ?? '';

if (empty($codigo_verificacao)) {
    exibirMensagemEredirecionar("Código de verificação não fornecido.", '../index.php');
}

$certificadoRepositorio = new CertificadoRepositorio();
$usuarioRepositorio = new UsuarioRepositorio();
$cursoRepositorio = new CursoRepositorio();

$certificado = $certificadoRepositorio->buscarCertificadoPorCodigo($codigo_verificacao);

if (!$certificado) {
    exibirMensagemEredirecionar("Certificado não encontrado ou código inválido.", '../index.php');
}

$usuario = $usuarioRepositorio->buscarUsuario($certificado['id_usuario']);
$curso = $cursoRepositorio->buscarCurso($certificado['id_curso']);

if (!$usuario || !$curso) {
    exibirMensagemEredirecionar("Dados associados ao certificado não encontrados.", '../index.php');
}

// Display certificate details
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Certificado</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .certificate-container { 
            background-color: #fff; 
            border: 1px solid #dee2e6; 
            border-radius: .25rem; 
            padding: 2rem; 
            margin-top: 50px; 
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .certificate-title { color: #007bff; }
        .certificate-info { font-size: 1.1rem; margin-bottom: 1rem; }
        .certificate-code { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="certificate-container text-center">
                    <h2 class="certificate-title mb-4">Certificado Verificado</h2>
                    <p class="certificate-info">Este certificado foi emitido para:</p>
                    <h3 class="mb-3"><?php echo htmlspecialchars($usuario['nome']); ?></h3>
                    <p class="certificate-info">Pela conclusão do curso:</p>
                    <h4 class="mb-3"><?php echo htmlspecialchars($curso['nome']); ?></h4>
                    <p class="certificate-info">Emitido em: <?php echo date('d/m/Y', strtotime($certificado['data_emissao'])); ?></p>
                    <p class="certificate-info certificate-code">Código de Verificação: <?php echo htmlspecialchars($certificado['codigo_verificacao']); ?></p>
                    <?php if (!empty($certificado['caminho_arquivo'])): ?>
                        <a href="<?php echo htmlspecialchars($certificado['caminho_arquivo']); ?>" class="btn btn-primary mt-3" download>Baixar Certificado</a>
                    <?php endif; ?>
                    <p class="mt-4">Este é um certificado válido.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
