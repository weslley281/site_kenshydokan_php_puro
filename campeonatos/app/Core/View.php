<?php
namespace App\Core;

use Exception;

/**
 * Mecanismo de Renderização de Views com Suporte a Layouts
 */
class View
{
    private static string $viewsPath = '';

    public static function setViewsPath(string $path): void
    {
        self::$viewsPath = rtrim($path, '/\\');
    }

    public static function getViewsPath(): string
    {
        if (empty(self::$viewsPath)) {
            self::$viewsPath = dirname(__DIR__) . '/Views';
        }
        return self::$viewsPath;
    }

    /**
     * Renderiza uma view dentro de um layout
     */
    public static function render(string $view, array $data = [], ?string $layout = 'layouts/main'): void
    {
        $baseDir = self::getViewsPath();
        $viewFile = $baseDir . '/' . ltrim($view, '/') . '.php';

        if (!file_exists($viewFile)) {
            throw new Exception("View não encontrada: {$viewFile}");
        }

        // Extrai as variáveis para o escopo
        extract($data, EXTR_SKIP);

        // Captura o conteúdo da view
        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        // Se houver layout especificado, inclui o layout com a variável $content
        if ($layout !== null) {
            $layoutFile = $baseDir . '/' . ltrim($layout, '/') . '.php';
            if (!file_exists($layoutFile)) {
                throw new Exception("Layout não encontrado: {$layoutFile}");
            }
            include $layoutFile;
        } else {
            echo $content;
        }
    }

    /**
     * Helper de escape seguro contra XSS
     */
    public static function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}
