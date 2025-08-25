<?php
class Conexao
{
    private $servidor = "kenshydokan.mysql.uhserver.com";
    private $usuario = "kenshydokan";
    private $senha = "K@rate12";
    private $banco = "kenshydokan";

    public function conectar()
    {
        try {
            $coneccao = mysqli_connect($this->servidor, $this->usuario, $this->senha, $this->banco);

            if (mysqli_connect_errno()) {
                throw new Exception("Falha ao abrir banco de dados: " . mysqli_connect_error());
            }

            mysqli_set_charset($coneccao, "utf8");

            return $coneccao;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }
}