<?php
namespace App\Core;

/**
 * Controlador Base da Arquitetura MVC
 */
abstract class Controller
{
    /**
     * Renderiza uma view
     */
    protected function render(string $view, array $data = [], ?string $layout = 'layouts/main'): void
    {
        View::render($view, $data, $layout);
    }

    /**
     * Retorna uma resposta em JSON
     */
    protected function json(mixed $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Redireciona para outra URL
     */
    protected function redirect(string $url): void
    {
        // Se a URL for relativa e começar com '/', ajusta para a base da aplicação
        if (str_starts_with($url, '/')) {
            $base = Router::getBasePath();
            $url = $base . $url;
        }
        header("Location: {$url}");
        exit;
    }

    /**
     * Exige que o usuário esteja autenticado
     */
    protected function requireAuth(): void
    {
        if (!Session::isLoggedIn()) {
            Session::flash('error', 'Acesso restrito. Faça login para continuar.');
            $this->redirect('/login');
        }
    }

    /**
     * Exige perfil de administrador
     */
    protected function requireAdmin(): void
    {
        $this->requireAuth();
        if (!Session::isAdmin()) {
            http_response_code(403);
            $this->render('errors/403', ['message' => 'Permissão insuficiente. Apenas administradores podem acessar esta função.'], 'layouts/main');
            exit;
        }
    }

    /**
     * Valida token CSRF em requisições de modificação (POST, PUT, DELETE)
     */
    protected function validateCsrf(): void
    {
        if (!Csrf::validate()) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'error' => 'Token CSRF inválido ou expirado.'], 403);
            }
            Session::flash('error', 'Sessão expirada ou requisição inválida. Tente novamente.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
    }

    /**
     * Verifica se a requisição atual é assíncrona (AJAX)
     */
    protected function isAjax(): bool
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    }

    /**
     * Captura parâmetro de entrada sanitizado
     */
    protected function input(string $key, mixed $default = null): mixed
    {
        return $_REQUEST[$key] ?? $default;
    }

    /**
     * Captura parâmetro POST sanitizado
     */
    protected function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }
}
