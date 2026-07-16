<?php

class Conexao {
    private $host = 'localhost';
    private $user = 'u515961161_kenshydokan';
    private $pass = 'Wesv@g28';
    private $db   = 'u515961161_kenshydokan';
    private $conn = null;

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
