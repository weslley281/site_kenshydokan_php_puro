<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('exigirLogin')) {
    function exigirLogin(string $destino = '../views/login.php'): void
    {
        if (!empty($_SESSION['id_usuario'])) {
            return;
        }

        header('Location: ' . $destino);
        exit;
    }
}

/**
 * Perfis do sistema (campeonato_usuarios.nivel):
 *   admin   - acesso total (sempre aceito em exigirPerfil)
 *   mesario - opera inscrições, pesagem, tatames e súmulas
 *   arbitro - opera/assina súmulas
 *   leitor  - somente consulta
 */
if (!function_exists('nivelAtual')) {
    function nivelAtual(): string
    {
        return (string)($_SESSION['nivel'] ?? '');
    }
}

if (!function_exists('usuarioTemPerfil')) {
    function usuarioTemPerfil(array $perfisPermitidos): bool
    {
        $nivel = nivelAtual();
        return $nivel === 'admin' || in_array($nivel, $perfisPermitidos, true);
    }
}

if (!function_exists('exigirPerfil')) {
    /**
     * Exige login e um dos perfis informados (o admin sempre passa).
     *
     * @param array  $perfisPermitidos Ex.: ['mesario', 'arbitro']; [] = somente admin.
     * @param string $destino          Para onde voltar quando negar (requisições comuns).
     * @param bool   $json             true em AJAX: responde 401/403 em JSON.
     */
    function exigirPerfil(array $perfisPermitidos = [], string $destino = '../views/inicio.php', bool $json = false): void
    {
        if (empty($_SESSION['id_usuario'])) {
            if ($json) {
                http_response_code(401);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'error' => 'Entre no sistema para continuar.']);
                exit;
            }
            header('Location: ../views/login.php');
            exit;
        }

        if (usuarioTemPerfil($perfisPermitidos)) {
            return;
        }

        if ($json) {
            http_response_code(403);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'error' => 'Seu perfil não tem permissão para esta ação.']);
            exit;
        }

        include_once __DIR__ . '/alertHelper.php';
        exibirMensagemEredirecionar('Seu perfil não tem permissão para esta ação.', $destino);
    }
}

require_once __DIR__ . "/csrfHelper.php";
