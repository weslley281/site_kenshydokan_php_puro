<?php
namespace App\Core;

/**
 * Proteção contra Cross-Site Request Forgery (CSRF)
 */
class Csrf
{
    private const SESSION_KEY = '_csrf_token';

    public static function generateToken(): string
    {
        Session::start();
        $token = bin2hex(random_bytes(32));
        Session::set(self::SESSION_KEY, $token);
        return $token;
    }

    public static function getToken(): string
    {
        Session::start();
        $token = Session::get(self::SESSION_KEY);
        if (!$token) {
            $token = self::generateToken();
        }
        return $token;
    }

    public static function validate(?string $token = null): bool
    {
        Session::start();
        $stored = Session::get(self::SESSION_KEY);
        if (!$stored) {
            return false;
        }

        if ($token === null) {
            $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        }

        if (!is_string($token)) {
            return false;
        }

        return hash_equals($stored, $token);
    }

    public static function field(): string
    {
        $token = self::getToken();
        return '<input type="hidden" name="_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}
