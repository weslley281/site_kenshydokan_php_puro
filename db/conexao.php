<?php
class Conexao {
    private $servidor = 'localhost';
    private $usuario = 'root';
    private $senha = '';
    private $banco = 'kenshydokan';

    public function conectar()
    {
        $coneccao = mysqli_connect($this->servidor, $this->usuario, $this->senha, $this->banco);

        if (mysqli_connect_errno()) {
            die("Falha ao abrir banco de dados: " . mysqli_connect_error());
        }

        mysqli_set_charset($coneccao, "utf8");

        return $coneccao;
    }
}
?>