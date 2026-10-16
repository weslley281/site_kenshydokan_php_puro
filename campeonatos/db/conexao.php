<?php
/**
 * Conexão com o Banco de Dados do Sistema de Campeonatos Kenshydokan
 * Padrão IDÊNTICO ao site principal (mysqli nativo, fallback de host e suporte a .env)
 */

class Conexao {

    private $host;
    private $user; 
    private $pass;
    private $db;
    private $dbPrincipal;

    private static $conn = null;
    private static $connPrincipal = null;

    public function __construct() {
        $this->carregarEnv();

        $this->host = $_ENV['DB_HOST'] ?? (getenv('DB_HOST') ?: '127.0.0.1');
        $this->user = $_ENV['DB_USER'] ?? (getenv('DB_USER') ?: 'u515961161_kenshydokan');
        $this->pass = isset($_ENV['DB_PASS']) ? $_ENV['DB_PASS'] : (getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'Wesv@g28');
        
        // Banco específico de campeonatos
        $this->db = $_ENV['DB_NAME_CAMPEONATOS'] ?? (getenv('DB_NAME_CAMPEONATOS') ?: 'campeonatos');

        // Banco do domínio principal (somente para leitura de filiados)
        $this->dbPrincipal = $_ENV['MAIN_DB_NAME'] ?? (getenv('MAIN_DB_NAME') ?: ($_ENV['DB_NAME'] ?? (getenv('DB_NAME') ?: 'u515961161_kenshydokan')));
    }

    private function carregarEnv() {
        $envPaths = [
            __DIR__ . '/../.env',
            __DIR__ . '/../.env.development',
            __DIR__ . '/../../.env',
            __DIR__ . '/../../.env.development'
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

    /**
     * Conexão com o banco de dados 'campeonatos' (Leitura e Escrita)
     */
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

            try {
                self::$conn = new mysqli($this->host, $this->user, $this->pass, $this->db);
                self::$conn->set_charset('utf8mb4');
            } catch (Exception $e) {
                // Se o banco ainda não existe, cria automaticamente
                if (stripos($e->getMessage(), 'Unknown database') !== false) {
                    try {
                        $temp = new mysqli($this->host, $this->user, $this->pass);
                        $temp->query("CREATE DATABASE IF NOT EXISTS `{$this->db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                        $temp->close();
                        self::$conn = new mysqli($this->host, $this->user, $this->pass, $this->db);
                        self::$conn->set_charset('utf8mb4');
                        return self::$conn;
                    } catch (Exception $eCreate) {
                        // continua para o fallback
                    }
                }

                // Fallback automático entre 127.0.0.1 e localhost
                $altHost = ($this->host === '127.0.0.1') ? 'localhost' : '127.0.0.1';
                try {
                    self::$conn = new mysqli($altHost, $this->user, $this->pass, $this->db);
                    self::$conn->set_charset('utf8mb4');
                } catch (Exception $e2) {
                    if (stripos($e2->getMessage(), 'Unknown database') !== false) {
                        try {
                            $temp = new mysqli($altHost, $this->user, $this->pass);
                            $temp->query("CREATE DATABASE IF NOT EXISTS `{$this->db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                            $temp->close();
                            self::$conn = new mysqli($altHost, $this->user, $this->pass, $this->db);
                            self::$conn->set_charset('utf8mb4');
                            return self::$conn;
                        } catch (Exception $eCreate2) {}
                    }
                    die('Erro na conexão com o banco de dados de campeonatos: ' . $e2->getMessage());
                }
            }
        }
        return self::$conn;
    }

    /**
     * Conexão com o banco principal do site (SOMENTE LEITURA para filiados)
     */
    public function conectarPrincipal() {
        $connectionValid = false;
        if (self::$connPrincipal !== null) {
            try {
                $connectionValid = @self::$connPrincipal->ping();
            } catch (Throwable $t) {
                $connectionValid = false;
            }
        }

        if (self::$connPrincipal === null || !$connectionValid) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            try {
                self::$connPrincipal = new mysqli($this->host, $this->user, $this->pass, $this->dbPrincipal);
                self::$connPrincipal->set_charset('utf8mb4');
            } catch (Exception $e) {
                $altHost = ($this->host === '127.0.0.1') ? 'localhost' : '127.0.0.1';
                try {
                    self::$connPrincipal = new mysqli($altHost, $this->user, $this->pass, $this->dbPrincipal);
                    self::$connPrincipal->set_charset('utf8mb4');
                } catch (Exception $e2) {
                    die('Erro na conexão com o banco de dados principal: ' . $e2->getMessage());
                }
            }
        }
        return self::$connPrincipal;
    }
}

class Database extends Conexao {
    public function getConnection() {
        return $this->conectar();
    }
}
