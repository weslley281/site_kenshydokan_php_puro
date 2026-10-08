<?php

class Conexao {

    private $host;
    private $user;
    private $pass;
    private $db;

    private $conn = null;

    public function __construct() {
        $this->carregarEnv();

        $envHost = getenv('DB_HOST') ?: '127.0.0.1';
        // Em hospedagens como Hostinger, 'localhost' pode causar erro de permissao de socket Unix (Operation not permitted).
        // Se for localhost, altera para 127.0.0.1 para forçar conexao TCP/IP.
        $this->host = ($envHost === 'localhost') ? '127.0.0.1' : $envHost;
        $this->user = getenv('DB_USER') !== false ? getenv('DB_USER') : 'root';
        $this->pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
        $this->db   = getenv('DB_NAME') ?: 'kenshydokan';
    }

    private function carregarEnv() {
        $envPath = __DIR__ . '/../.env';
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
        }
    }

    public function conectar() {
        if ($this->conn === null) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            try {
                $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);
                $this->conn->set_charset('utf8mb4');
            } catch (Exception $e) {
                die('Erro na conexao com o banco de dados: ' . $e->getMessage());
            }
        }
        return $this->conn;
    }
}

class Database extends Conexao {
    public function getConnection() {
        return $this->conectar();
    }
}
?>

