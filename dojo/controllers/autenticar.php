<?php
// controllers/autenticar.php
session_start();
include_once "../db/conexao.php";

$c = new Conexao();
$conexao = $c->conectar();

$limiteTentativas = 5;

if (!isset($_SESSION['tentativas'])) {
    $_SESSION['tentativas'] = 0;
}

if (isset($_SESSION['bloqueio']) && $_SESSION['bloqueio'] > time()) {
    $tempoRestante = $_SESSION['bloqueio'] - time();
    $mensagem = "Bloqueado por excesso de tentativas. Tente novamente em " . gmdate("i:s", $tempoRestante) . " minutos.";
    echo "<script>alert('$mensagem'); window.location='../views/login.php';</script>";
    exit();
}

if (isset($_POST['usuario'], $_POST['senha'])) {
    $email = mysqli_real_escape_string($conexao, strtolower(trim($_POST['usuario'])));
    $senha = $_POST['senha'];

    $consulta = "SELECT id_usuario, email, nome, id_fil, nivel, senha, primeiro_acesso FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conexao, $consulta);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($resultado && $dado = mysqli_fetch_assoc($resultado)) {
        if (password_verify($senha, $dado["senha"])) {
            // Login correto!
            unset($_SESSION['tentativas']);
            unset($_SESSION['bloqueio']);

            if (intval($dado['primeiro_acesso']) === 1) {
                // Se for primeiro acesso, armazena ID na sessão temporária e obriga a redefinir a senha
                $_SESSION['temp_id_usuario'] = $dado['id_usuario'];
                $_SESSION['temp_nome'] = $dado['nome'];
                header("Location: ../views/primeiro_acesso.php");
                exit();
            }

            // Acesso normal
            $_SESSION['id_usuario'] = $dado['id_usuario'];
            $_SESSION['email'] = $dado['email'];
            $_SESSION['nome'] = $dado['nome'];
            $_SESSION['id_fil'] = $dado['id_fil'];
            $_SESSION['nivel'] = $dado['nivel']; // sensei, sempai, kohai

            // Redireciona com base no nível de acesso
            if ($dado['nivel'] === 'sensei') {
                header("Location: ../views/admin/index.php");
            } else {
                header("Location: ../views/dashboard_aluno.php");
            }
            exit();
        }
    }

    // Login falhou
    $_SESSION['tentativas']++;
    if ($_SESSION['tentativas'] >= $limiteTentativas) {
        $_SESSION['bloqueio'] = time() + 300; // bloqueia por 5 minutos
    }

    echo "<script>alert('E-mail ou senha incorretos.'); window.location='../views/login.php';</script>";
    exit();
}

header("Location: ../views/login.php");
exit();
?>
