<?php
class Conexao {
    private $servidor = "localhost";
    private $usuario = "root";
    private $senha = "";
    private $bd = "u696382984_kenshydokan";

    public function conectar() {
        $conexao = mysqli_connect($this->servidor, $this->usuario, $this->senha, $this->bd);

        if (mysqli_connect_errno()) {
            die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
        }

        mysqli_set_charset($conexao, "utf8");

        return $conexao;
    }
}
