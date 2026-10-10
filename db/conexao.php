<?php

class Conexao {

    private $host;
    private $user; 
    private $pass;
    private $db;

    private static $conn = null;

    public function __construct() {
        $this->carregarEnv();

        // Se houver .env ou .env.development, usa as variaveis dele.
        // Se nao houver .env ou nao estiver definido, usa como padrao as credenciais da Hostinger.
        $this->host = getenv('DB_HOST') ?: '127.0.0.1';
        $this->user = getenv('DB_USER') ?: 'u515961161_kenshydokan';
        $this->pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'Wesv@g28';
        $this->db   = getenv('DB_NAME') ?: 'u515961161_kenshydokan';
    }

    private function carregarEnv() {
        $envPaths = [
            __DIR__ . '/../.env',
            __DIR__ . '/../.env.development'
        ];

        foreach ($envPaths as $envPath) {
            if (file_exists($envPath)) {
                $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (empty($line) || substr($line, 0, 1) === '#') {
                        continue;
                    }
                    if (strpos($line, '=') !== false) {
                        list($key, $value) = explode('=', $line, 2);
                        $key = trim($key);
                        $value = trim($value, " \t\n\r\0\x0B\"'");
                        putenv("$key=$value");
                        $_ENV[$key] = $value;
                    }
                }
                break;
            }
        }
    }

    public function conectar() {
        $connectionValid = false;
        if (self::$conn !== null) {
            try {
                $connectionValid = @self::$conn->ping();
            } catch (Throwable $t) {
                $connectionValid = false;
            }
        }

        if (self::$conn === null || !$connectionValid) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            // Tenta primeiro com o host principal (ex: 127.0.0.1 ou localhost)
            try {
                self::$conn = new mysqli($this->host, $this->user, $this->pass, $this->db);
                self::$conn->set_charset('utf8mb4');
            } catch (Exception $e) {
                // Se falhar no primeiro host, tenta o alternativo (fallback automatico entre 127.0.0.1 e localhost)
                $altHost = ($this->host === '127.0.0.1') ? 'localhost' : '127.0.0.1';
                try {
                    self::$conn = new mysqli($altHost, $this->user, $this->pass, $this->db);
                    self::$conn->set_charset('utf8mb4');
                } catch (Exception $e2) {
                    die('Erro na conexao com o banco de dados: ' . $e2->getMessage());
                }
            }
        }
        return self::$conn;
    }
}

class Database extends Conexao {
    public function getConnection() {
        return $this->conectar();
    }
}
?>



