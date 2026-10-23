<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['id_usuario'])) {
    header("Location: inicio.php");
    exit;
}

$erroInicializacao = false;
$detalheInicializacao = null;
require_once __DIR__ . '/../db/conexao.php';
try {
    (new Conexao())->conectar();
} catch (Throwable $erro) {
    $erroInicializacao = true;
    $debug = $_ENV['APP_DEBUG'] ?? getenv('APP_DEBUG');
    if ($debug === 'true' || $debug === '1') {
        $detalheInicializacao = $erro->getMessage();
        foreach (['DB_USER', 'DB_PASS', 'ADMIN_EMAIL', 'ADMIN_PASSWORD'] as $chave) {
            $segredo = $_ENV[$chave] ?? getenv($chave);
            if (is_string($segredo) && $segredo !== '') {
                $detalheInicializacao = str_replace($segredo, '[redigido]', $detalheInicializacao);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Acesso Restrito | Campeonatos Kenshydokan</title>

    <link rel="stylesheet" href="../libs/fontawesome-free-6.5.2-web/css/all.min.css">
    <link rel="stylesheet" href="../libs/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../libs/alertifyjs/css/alertify.min.css">
    <link rel="stylesheet" href="../libs/alertifyjs/css/themes/bootstrap.min.css">
    <link rel="stylesheet" href="../css/custom.css">

    <style>
        body {
            background: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.12);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            border: 1px solid #dee2e6;
        }
        .login-header {
            background: #1a1a1a;
            border-bottom: 4px solid #d9232d;
            padding: 28px 25px 24px;
            text-align: center;
            color: #ffffff;
            position: relative;
        }
        .login-header i.fa-torii-gate { color: #d9232d !important; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <span class="p-3 rounded-circle bg-danger bg-opacity-25 border border-danger border-opacity-50 d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
            <i class="fas fa-torii-gate fa-2x text-danger"></i>
        </span>
        <h4 class="fw-bold mb-1 tracking-wider text-white">KENSHYDOKAN JUTSU</h4>
        <span class="badge bg-danger text-uppercase px-3 py-1 mt-1" style="font-size: 0.72rem; letter-spacing: 0.1em;">
            GESTÃO OFICIAL DE CAMPEONATOS
        </span>
    </div>

    <div class="p-4 p-sm-5">
        <h1 class="h4 mb-4">Entrar no sistema</h1>
        <?php if ($erroInicializacao): ?>
            <div class="alert alert-danger" role="alert">
                A configuração inicial do sistema não foi concluída. Verifique no arquivo <code>.env</code> as credenciais dos bancos e, se ainda não houver administrador, defina <code>ADMIN_EMAIL</code> e <code>ADMIN_PASSWORD</code> (mínimo de 12 caracteres).
                <?php if ($detalheInicializacao !== null): ?>
                    <hr>
                    <strong>Detalhe:</strong> <?= htmlspecialchars($detalheInicializacao, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php require_once __DIR__ . '/../controllers/csrfHelper.php'; ?><form method="POST" action="../controllers/autenticar.php"><?= csrfCampo() ?>
            <div class="mb-3">
                <label for="email" class="form-label fw-bold">E-mail</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control border-start-0" id="email" name="email" placeholder="admin@kenshydokan.org.br" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label for="senha" class="form-label fw-bold">Senha</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control border-start-0" id="senha" name="senha" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-danger w-100 btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i> Entrar
            </button>
        </form>

        <div class="text-center mt-4 pt-3 border-top small text-muted">
            <a href="validar_certificado.php" target="_blank" class="text-decoration-none text-secondary fw-semibold">
                <i class="fas fa-shield-alt text-danger me-1"></i> Validar Autenticidade de Certificado
            </a>
        </div>
    </div>
</div>

<script src="../libs/bootstrap/jquery.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../libs/alertifyjs/alertify.min.js"></script>
</body>
</html>
