<?php
/**
 * Validação Pública de Certificados Oficiais
 * Acesso irrestrito (não exige login de administrador)
 */
include_once __DIR__ . "/../models/certificadoModel.php";

$hash = trim($_GET['hash'] ?? ($_POST['hash'] ?? ''));
$resultado = null;
$buscou = false;

if (!empty($hash)) {
    $buscou = true;
    $certModel = new Certificado();
    $resultado = $certModel->buscarPorHash($hash);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validador de Certificados | Federação Kenshydokan Jutsu</title>
    <link rel="stylesheet" href="../libs/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../libs/fontawesome-free-6.5.2-web/css/all.min.css">
    <link rel="stylesheet" href="../css/custom.css">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #334155;
            padding: 20px;
        }
        .card-validador {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            border: none;
            overflow: hidden;
            width: 100%;
            max-width: 650px;
        }
        .header-validador {
            background: #111827;
            padding: 30px;
            text-align: center;
            color: #ffffff;
            border-bottom: 4px solid #dc2626;
        }
    </style>
</head>
<body>

<div class="card card-validador">
    <div class="header-validador">
        <i class="fas fa-shield-alt fa-3x text-danger mb-2"></i>
        <h3 class="fw-bold mb-1 tracking-wide">KENSHYDOKAN JUTSU</h3>
        <p class="text-secondary small mb-0 text-uppercase tracking-wider">Portal Oficial de Autenticidade de Documentos</p>
    </div>

    <div class="p-4 p-md-5">
        <form method="GET" action="validar_certificado.php" class="mb-4">
            <label class="form-label fw-bold text-dark small text-uppercase">Código Hash do Certificado</label>
            <div class="input-group input-group-lg shadow-sm">
                <span class="input-group-text bg-light"><i class="fas fa-barcode text-muted"></i></span>
                <input type="text" name="hash" class="form-control text-uppercase font-monospace" placeholder="Ex: 8F3A2B1C-2026" value="<?= htmlspecialchars($hash) ?>" required autofocus>
                <button type="submit" class="btn btn-danger fw-bold px-4">
                    <i class="fas fa-search me-1"></i> Validar
                </button>
            </div>
            <div class="form-text small text-muted">Insira o código alfanumérico impresso no rodapé do certificado.</div>
        </form>

        <?php if ($buscou): ?>
            <?php if ($resultado): ?>
                <div class="p-4 rounded border border-success bg-success bg-opacity-10 text-center mb-4">
                    <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                    <h4 class="fw-bold text-success mb-1">DOCUMENTO OFICIAL AUTÊNTICO</h4>
                    <p class="text-muted small mb-0">Este certificado foi emitido e homologado pelo Sistema de Campeonatos Kenshydokan.</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th class="bg-light text-muted small text-uppercase" style="width: 35%;">Titular do Certificado</th>
                                <td class="fw-bold text-dark fs-5"><?= htmlspecialchars($resultado['nome_titular']) ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted small text-uppercase">Dojo / Representação</th>
                                <td class="fw-bold text-secondary"><?= htmlspecialchars($resultado['dojo']) ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted small text-uppercase">Campeonato Oficial</th>
                                <td><?= htmlspecialchars($resultado['campeonato_titulo']) ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted small text-uppercase">Categoria</th>
                                <td><?= htmlspecialchars($resultado['categoria_nome']) ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted small text-uppercase">Modalidade / Tipo</th>
                                <td>
                                    <span class="badge bg-danger text-uppercase"><?= htmlspecialchars($resultado['tipo_certificado']) ?></span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted small text-uppercase">Data de Emissão</th>
                                <td class="small font-monospace"><?= date('d/m/Y H:i:s', strtotime($resultado['data_emissao'])) ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted small text-uppercase">Código Registrado</th>
                                <td><code class="text-danger fw-bold"><?= htmlspecialchars($resultado['codigo_autenticidade']) ?></code></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-center mt-4">
                    <a href="../controllers/certificadoController.php?acao=gerar_pdf&id_inscricao=<?= $resultado['id_inscricao'] ?>&tipo=<?= $resultado['tipo_certificado'] ?>" target="_blank" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-file-pdf me-1"></i> Visualizar Via Original do Certificado (PDF)
                    </a>
                </div>
            <?php else: ?>
                <div class="p-4 rounded border border-danger bg-danger bg-opacity-10 text-center">
                    <i class="fas fa-times-circle fa-3x text-danger mb-2"></i>
                    <h5 class="fw-bold text-danger mb-1">CERTIFICADO NÃO LOCALIZADO</h5>
                    <p class="text-muted small mb-0">
                        O código <strong><?= htmlspecialchars($hash) ?></strong> não corresponde a nenhum documento oficial registrado ou homologado na base de dados da Kenshydokan.
                    </p>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="text-center mt-4 pt-3 border-top small text-muted">
            <a href="login.php" class="text-decoration-none text-muted"><i class="fas fa-lock me-1"></i> Painel Administrativo</a>
            &bull;
            <span>Federação Kenshydokan Jutsu &copy; <?= date('Y') ?></span>
        </div>
    </div>
</div>

</body>
</html>
