<?php
/**
 * Helper de Proteção CSRF (Cross-Site Request Forgery)
 * Mesmo estilo dos demais helpers (alertHelper.php / authHelper.php):
 * funções simples protegidas por function_exists, sem namespaces.
 *
 * Uso nas views:        <?= csrfCampo() ?>             (dentro de cada <form method="POST">)
 * Uso nos controllers:  csrfExigir();                   (no início do tratamento do POST)
 * Uso em AJAX:          cabeçalho X-CSRF-Token com csrfToken()
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('csrfToken')) {
    /**
     * Retorna o token da sessão atual, criando um novo se ainda não existir.
     * O token dura a sessão inteira (e não é descartável) para não quebrar
     * a súmula digital, que faz várias chamadas AJAX seguidas.
     */
    function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrfCampo')) {
    /** Campo hidden pronto para colocar dentro de um formulário POST. */
    function csrfCampo(): string
    {
        return '<input type="hidden" name="_token" value="'
            . htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') . '">';
    }
}

if (!function_exists('csrfValido')) {
    /** Confere o token recebido (campo _token ou cabeçalho X-CSRF-Token). */
    function csrfValido(): bool
    {
        $guardado = $_SESSION['csrf_token'] ?? '';
        $recebido = $_POST['_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');

        if (!is_string($guardado) || $guardado === '' || !is_string($recebido)) {
            return false;
        }
        return hash_equals($guardado, $recebido);
    }
}

if (!function_exists('csrfExigir')) {
    /**
     * Interrompe a requisição se o token for inválido.
     *
     * @param string $destino Para onde mandar o usuário (requisições comuns).
     * @param bool   $json    true em chamadas AJAX: responde 419 em JSON em vez de redirecionar.
     */
    function csrfExigir(string $destino = '../views/inicio.php', bool $json = false): void
    {
        if (csrfValido()) {
            return;
        }

        if ($json) {
            http_response_code(419);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'error' => 'Sessão expirada. Recarregue a página e tente de novo.',
            ]);
            exit;
        }

        include_once __DIR__ . '/alertHelper.php';
        exibirMensagemEredirecionar(
            'Sua sessão expirou ou a requisição é inválida. Tente novamente.',
            $destino
        );
    }
}
