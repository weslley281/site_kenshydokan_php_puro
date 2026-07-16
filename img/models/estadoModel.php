<?php
include_once __DIR__ . "/../db/conexao.php";

class EstadoModel
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function listarTodos()
    {
        $query = "SELECT * FROM estados";
        $resultado = $this->conexao->query($query);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function buscarPorId($id_estado)
    {
        $busca = $this->conexao->prepare("SELECT * FROM estados WHERE id_estado = ?");
        $busca->bind_param("i", $id_estado);
        $busca->execute();
        $result = $busca->get_result();

        $estado = null;
        if ($result->num_rows > 0) {
            $estado = $result->fetch_assoc();
        }
        $busca->close();
        return $estado;
    }
}
