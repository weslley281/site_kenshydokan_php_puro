<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['id_usuario'])) {
    header("Location: inicio.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Acesso Restrito | Campeonatos Kenshydokan</title>

    <link rel="stylesheet" href="../libs/fontawesome-free-6.5.2-web/css/all.min.css">
    <link rel="stylesheet" href="../libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../libs/alertifyjs/css/alertify.min.css">
    <link rel="stylesheet" href="../libs/alertifyjs/css/themes/bootstrap.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
        }
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            width: 100%;
            max-width: 440px;
        }
        .login-header {
            background: #111827;
            border-bottom: 4px solid #dc2626;
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }
    </style>
</head>
<body>

<div class="login-card mx-3">
    <div class="login-header">
        <i class="fas fa-torii-gate fa-3x text-danger mb-2"></i>
        <h4 class="fw-bold mb-1">KENSHYDOKAN JUTSU</h4>
        <span class="badge bg-danger text-uppercase px-3 py-1">Plataforma de Campeonatos</span>
    </div>

    <div class="p-4 p-sm-5">
        <form method="POST" action="../controllers/autenticar.php">
            <div class="mb-3">
                <label for="email" class="form-label fw-bold small text-muted text-uppercase">E-mail de Acesso:</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                    <input type="email" class="form-control border-start-0" id="email" name="email" placeholder="admin@kenshydokan.org.br" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label for="senha" class="form-label fw-bold small text-muted text-uppercase">Senha:</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" class="form-control border-start-0" id="senha" name="senha" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-danger w-100 py-2 fw-bold text-uppercase shadow-sm">
                <i class="fas fa-sign-in-alt me-1"></i> Entrar no Sistema
            </button>
        </form>

        <div class="text-center mt-4 pt-3 border-top small text-muted">
            <a href="validar_certificado.php" class="text-decoration-none text-secondary">
                <i class="fas fa-qrcode me-1"></i> Validar Certificado sem Login
            </a>
        </div>
    </div>
</div>

<script src="../libs/bootstrap/js/jquery.min.js"></script>
<script src="../libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../libs/alertifyjs/alertify.min.js"></script>
</body>
</html>
