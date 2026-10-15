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
    <link rel="stylesheet" href="../libs/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../libs/alertifyjs/css/alertify.min.css">
    <link rel="stylesheet" href="../libs/alertifyjs/css/themes/bootstrap.min.css">
    <link rel="stylesheet" href="../css/custom.css">

    <style>
        body {
            background: radial-gradient(circle at 50% 20%, #1e293b 0%, #0f172a 60%, #020617 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45), 0 0 40px rgba(217, 35, 45, 0.15);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .login-header {
            background: #0f172a;
            border-bottom: 4px solid #d9232d;
            padding: 35px 25px 28px 25px;
            text-align: center;
            color: #ffffff;
            position: relative;
        }
        .login-header i.fa-torii-gate {
            filter: drop-shadow(0 0 15px rgba(217, 35, 45, 0.8));
        }
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
        <form method="POST" action="../controllers/autenticar.php">
            <div class="mb-3">
                <label for="email" class="form-label fw-bold small text-muted text-uppercase" style="font-size: 0.75rem;">E-mail de Acesso:</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control border-start-0" id="email" name="email" placeholder="admin@kenshydokan.org.br" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label for="senha" class="form-label fw-bold small text-muted text-uppercase" style="font-size: 0.75rem;">Senha de Segurança:</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control border-start-0" id="senha" name="senha" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-danger w-100 py-3 fw-bold text-uppercase shadow">
                <i class="fas fa-sign-in-alt me-2"></i> Entrar na Plataforma
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
