<?php
// db/conexao.php

class Conexao {
    private $host = 'localhost';
    private $user = 'u515961161_dojo';
    private $pass = 'Wesv@g28';
    private $db   = 'u515961161_dojo';
    private $conn = null;
    private static $migrationsExecutadas = false;

    public function conectar() {
        if ($this->conn === null) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            try {
                // Tenta conectar ao MySQL
                $this->conn = new mysqli($this->host, $this->user, $this->pass);
                
                // Cria o banco de dados se não existir
                $this->conn->query("CREATE DATABASE IF NOT EXISTS `{$this->db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
                
                // Seleciona o banco
                $this->conn->select_db($this->db);
                $this->conn->set_charset('utf8mb4');

                // Executa as migrações automaticamente se ainda não foram executadas nesta requisição
                if (!self::$migrationsExecutadas) {
                    self::$migrationsExecutadas = true;
                    if (file_exists(__DIR__ . "/migrations.php")) {
                        include_once __DIR__ . "/migrations.php";
                        $migration = new Migration();
                        $migration->executarTodas();
                    }
                }
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
