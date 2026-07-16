<?php
// views/login.php
session_start();

if (isset($_SESSION["id_usuario"])) {
    $redirectUrl = ($_SESSION['nivel'] === 'sensei') ? 'admin/index.php' : 'dashboard_aluno.php';
    header("Location: {$redirectUrl}");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Acesso - Gerenciador de Dojô</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/e880bf5077.js" crossorigin="anonymous"></script>
    <!-- Google Font Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #1f1f1f 0%, #111111 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-login {
            border: 0;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
            background-color: #ffffff;
            overflow: hidden;
            width: 100%;
            max-width: 420px;
        }
        .card-header-login {
            background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 0;
        }
        .btn-pill {
            border-radius: 50px;
            font-weight: bold;
            padding: 12px 30px;
            transition: all 0.3s ease;
        }
        .btn-pill:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }
        .form-control-lg {
            font-size: 1rem;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 d-flex justify-content-center">
                <div class="card-login">
                    <div class="card-header-login">
                        <i class="fa-solid fa-store fa-2xl mb-3"></i>
                        <h4 class="font-weight-bold mb-1">Área de Acesso</h4>
                        <p class="mb-0 text-white-50">Gerenciador de Dojô Standalone</p>
                    </div>
                    <div class="card-body p-4 p-sm-5">
                        <form action="../controllers/autenticar.php" method="POST">
                            <div class="form-group mb-4">
                                <label for="usuario" class="text-secondary small font-weight-bold text-uppercase">E-mail</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                                    </div>
                                    <input type="email" class="form-control form-control-lg bg-light border-0" id="usuario" name="usuario" placeholder="Digite seu email" required autofocus>
                                </div>
                            </div>
                            
                            <div class="form-group mb-4">
                                <label for="senha" class="text-secondary small font-weight-bold text-uppercase">Senha</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-0"><i class="fa-solid fa-lock text-muted"></i></span>
                                    </div>
                                    <input type="password" class="form-control form-control-lg bg-light border-0" id="senha" name="senha" placeholder="Digite sua senha" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-danger btn-block btn-pill shadow-sm text-uppercase">Entrar no Sistema</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
