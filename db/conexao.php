<?php
class Conexao {
    private $servidor = 'kenshydokan.mysql.uhserver.com';
    private $usuario = 'kenshydokan';
    private $senha = 'K@rate12';
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