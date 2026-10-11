<?php
namespace App\Core;

use Exception;

/**
 * Roteador MVC Limpo com suporte a Parâmetros de Rota e Subdomínios
 */
class Router
{
    private static array $routes = [];
    private static string $basePath = '';

    public static function get(string $path, array|callable $handler): void
    {
        self::addRoute('GET', $path, $handler);
    }

    public static function post(string $path, array|callable $handler): void
    {
        self::addRoute('POST', $path, $handler);
    }

    private static function addRoute(string $method, string $path, array|callable $handler): void
    {
        $path = '/' . trim($path, '/');
        self::$routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    public static function setBasePath(string $basePath): void
    {
        self::$basePath = rtrim($basePath, '/');
    }

    public static function getBasePath(): string
    {
        return self::$basePath;
    }

    /**
     * Resolve a URL atual com base no script name e request uri
     */
    public static function resolveCurrentPath(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $scriptName = dirname($_SERVER['SCRIPT_NAME'] ?? '');

        if ($scriptName !== '/' && $scriptName !== '\\' && !empty($scriptName)) {
            self::setBasePath($scriptName);
            if (str_starts_with($uri, $scriptName)) {
                $uri = substr($uri, strlen($scriptName));
            }
        }

        $uri = '/' . trim($uri, '/');
        return empty($uri) ? '/' : $uri;
    }

    /**
     * Despacha a requisição HTTP para o controlador correspondente
     */
    public static function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $currentPath = self::resolveCurrentPath();

        foreach (self::$routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            // Converte rota com parâmetros como {id} para regex
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $currentPath, $matches)) {
                $params = array_filter($matches, fn($key) => !is_numeric($key), ARRAY_FILTER_USE_KEY);
                self::executeHandler($route['handler'], $params);
                return;
            }
        }

        // Rota não encontrada (404)
        http_response_code(404);
        try {
            View::render('errors/404', ['path' => $currentPath], 'layouts/main');
        } catch (Exception) {
            echo "<h1>404 - Página Não Encontrada</h1><p>O recurso solicitado ({$currentPath}) não foi localizado.</p>";
        }
    }

    private static function executeHandler(array|callable $handler, array $params): void
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
            return;
        }

        if (is_array($handler) && count($handler) === 2) {
            [$class, $method] = $handler;
            if (!class_exists($class)) {
                throw new Exception("Controlador '{$class}' não encontrado.");
            }
            $controller = new $class();
            if (!method_exists($controller, $method)) {
                throw new Exception("Método '{$method}' não existe no controlador '{$class}'.");
            }
            call_user_func_array([$controller, $method], $params);
            return;
        }

        throw new Exception("Formato inválido de manipulador de rota.");
    }
}
