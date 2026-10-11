<?php
namespace App\Core;

use mysqli;
use Exception;
use Throwable;

/**
 * Gerenciador de Conexões de Banco de Dados (Dual-Database)
 * Utiliza estritamente a extensão nativa mysqli com charset utf8mb4.
 */
class Database
{
    private static ?mysqli $defaultConnection = null;
    private static ?mysqli $mainConnection = null;

    /**
     * Carrega variáveis do arquivo .env
     */
    public static function loadEnv(string $envPath = ''): void
    {
        if (empty($envPath)) {
            $envPath = dirname(__DIR__, 2) . '/.env';
        }

        if (!file_exists($envPath)) {
            return;
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#')) {
                continue;
            }
            if (strpos($line, '=') !== false) {
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value, " \t\n\r\0\x0B\"'");
                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }

    /**
     * Retorna a conexão com o banco de dados dedicado aos campeonatos
     */
    public static function getConnection(): mysqli
    {
        if (self::$defaultConnection !== null) {
            try {
                if (@self::$defaultConnection->ping()) {
                    return self::$defaultConnection;
                }
            } catch (Throwable) {
                self::$defaultConnection = null;
            }
        }

        self::loadEnv();
        $config = require dirname(__DIR__, 2) . '/config/database.php';
        $dbConfig = $config['default'];

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $conn = new mysqli(
                $dbConfig['host'],
                $dbConfig['username'],
                $dbConfig['password'],
                $dbConfig['database'],
                (int)$dbConfig['port']
            );
            $conn->set_charset($dbConfig['charset']);
            self::$defaultConnection = $conn;
        } catch (Exception $e) {
            // Fallback entre 127.0.0.1 e localhost
            $altHost = ($dbConfig['host'] === '127.0.0.1') ? 'localhost' : '127.0.0.1';
            try {
                $conn = new mysqli(
                    $altHost,
                    $dbConfig['username'],
                    $dbConfig['password'],
                    $dbConfig['database'],
                    (int)$dbConfig['port']
                );
                $conn->set_charset($dbConfig['charset']);
                self::$defaultConnection = $conn;
            } catch (Exception $e2) {
                throw new Exception("Falha na conexão com o banco de campeonatos: " . $e2->getMessage());
            }
        }

        return self::$defaultConnection;
    }

    /**
     * Retorna a conexão com o banco do site raiz (somente leitura para filiados)
     */
    public static function getMainConnection(): mysqli
    {
        if (self::$mainConnection !== null) {
            try {
                if (@self::$mainConnection->ping()) {
                    return self::$mainConnection;
                }
            } catch (Throwable) {
                self::$mainConnection = null;
            }
        }

        self::loadEnv();
        $config = require dirname(__DIR__, 2) . '/config/database.php';
        $mainConfig = $config['main'];

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $conn = new mysqli(
                $mainConfig['host'],
                $mainConfig['username'],
                $mainConfig['password'],
                $mainConfig['database'],
                (int)$mainConfig['port']
            );
            $conn->set_charset($mainConfig['charset']);
            self::$mainConnection = $conn;
        } catch (Exception $e) {
            $altHost = ($mainConfig['host'] === '127.0.0.1') ? 'localhost' : '127.0.0.1';
            try {
                $conn = new mysqli(
                    $altHost,
                    $mainConfig['username'],
                    $mainConfig['password'],
                    $mainConfig['database'],
                    (int)$mainConfig['port']
                );
                $conn->set_charset($mainConfig['charset']);
                self::$mainConnection = $conn;
            } catch (Exception $e2) {
                throw new Exception("Falha na conexão de leitura do banco de filiados: " . $e2->getMessage());
            }
        }

        return self::$mainConnection;
    }

    public static function beginTransaction(?mysqli $conn = null): void
    {
        $conn = $conn ?: self::getConnection();
        $conn->begin_transaction();
    }

    public static function commit(?mysqli $conn = null): void
    {
        $conn = $conn ?: self::getConnection();
        $conn->commit();
    }

    public static function rollback(?mysqli $conn = null): void
    {
        $conn = $conn ?: self::getConnection();
        $conn->rollback();
    }
}
