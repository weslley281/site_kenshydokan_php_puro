<?php
// views/primeiro_acesso.php
session_start();
if (!isset($_SESSION['temp_id_usuario'])) {
    header("Location: login.php");
    exit();
}

include_once "../db/conexao.php";
$erro = "";
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    if (strlen($senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres.";
    } elseif ($senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem.";
    } else {
        $c = new Conexao();
        $conexao = $c->conectar();

        $id_usuario = $_SESSION['temp_id_usuario'];
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $conexao->prepare("UPDATE usuarios SET senha = ?, primeiro_acesso = 0 WHERE id_usuario = ?");
        $stmt->bind_param("si", $senhaHash, $id_usuario);
        $res = $stmt->execute();
        $stmt->close();

        if ($res) {
            // Busca dados completos do usuário para logar
            $stmtUser = $conexao->prepare("SELECT id_usuario, email, nome, id_fil, nivel FROM usuarios WHERE id_usuario = ?");
            $stmtUser->bind_param("i", $id_usuario);
            $stmtUser->execute();
            $dado = $stmtUser->get_result()->fetch_assoc();
            $stmtUser->close();

            // Configura sessão final
            $_SESSION['id_usuario'] = $dado['id_usuario'];
            $_SESSION['email'] = $dado['email'];
            $_SESSION['nome'] = $dado['nome'];
            $_SESSION['id_fil'] = $dado['id_fil'];
            $_SESSION['nivel'] = $dado['nivel'];

            // Limpa dados temporários
            unset($_SESSION['temp_id_usuario']);
            unset($_SESSION['temp_nome']);

            $sucesso = true;
            
            // Redireciona
            $redirectUrl = ($dado['nivel'] === 'sensei') ? "admin/index.php" : "dashboard_aluno.php";
            echo "<script>alert('Senha alterada com sucesso! Bem-vindo.'); window.location='{$redirectUrl}';</script>";
            exit();
        } else {
            $erro = "Erro ao atualizar senha no banco de dados.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Primeiro Acesso - Gerenciador de Dojô</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/custom.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #1f1f1f 0%, #111111 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-reset {
            border: 0;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
            background-color: #ffffff;
            overflow: hidden;
            width: 100%;
            max-width: 450px;
        }
        .card-header-reset {
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
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 d-flex justify-content-center">
                <div class="card-reset">
                    <div class="card-header-reset">
                        <h4 class="font-weight-bold mb-1">Primeiro Acesso</h4>
                        <p class="mb-0 text-white-50">Por segurança, altere sua senha temporária</p>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small text-center mb-4">Olá, <strong><?php echo htmlspecialchars($_SESSION['temp_nome']); ?></strong>! Escolha uma nova senha de no mínimo 6 caracteres para os seus próximos acessos ao sistema.</p>

                        <?php if (!empty($erro)): ?>
                            <div class="alert alert-danger border-0 shadow-sm rounded mb-4" role="alert">
                                <?php echo htmlspecialchars($erro); ?>
                            </div>
                        <?php endif; ?>

                        <form action="primeiro_acesso.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="senha" class="text-secondary small font-weight-bold text-uppercase">Nova Senha</label>
                                <input type="password" class="form-control form-control-lg bg-light border-0 shadow-sm" id="senha" name="senha" placeholder="Mínimo 6 caracteres" required minlength="6">
                            </div>
                            
                            <div class="form-group mb-4">
                                <label for="confirmar_senha" class="text-secondary small font-weight-bold text-uppercase">Confirmar Nova Senha</label>
                                <input type="password" class="form-control form-control-lg bg-light border-0 shadow-sm" id="confirmar_senha" name="confirmar_senha" placeholder="Repita a nova senha" required minlength="6">
                            </div>

                            <button type="submit" class="btn btn-danger btn-block btn-pill shadow-sm">Salvar Nova Senha</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
