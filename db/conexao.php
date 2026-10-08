<?php

class Conexao {
<<<<<<< HEAD
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $db   = 'kenshydokan';
=======
    private $host = '';
    private $user = '';
    private $pass = '';
    private $db   = '';
>>>>>>> e4adddcca39c1af8907c74c932273dd34426d427
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
