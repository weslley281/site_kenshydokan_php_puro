<?php
// models/estadoModel.php
include_once __DIR__ . "/../db/conexao.php";

class EstadoModel
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    /**
     * Retorna a lista de todos os estados cadastrados.
     * 
     * @return array
     */
    public function listarTodos()
    {
        try {
            $sql = "SELECT id_estado, estado FROM estados ORDER BY estado ASC";
            $result = $this->conexao->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao listar estados: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca um estado pelo seu ID.
     * 
     * @param int $id_estado
     * @return array|null
     */
    public function buscarPorId($id_estado)
    {
        if (empty($id_estado)) {
            return null;
        }

        try {
            $busca = $this->conexao->prepare("SELECT id_estado, estado FROM estados WHERE id_estado = ?");
            $busca->bind_param("i", $id_estado);
            $busca->execute();
            $result = $busca->get_result();

            if ($result->num_rows === 0) {
                return null;
            }

            $dados = $result->fetch_assoc();
            $busca->close();

            return $dados;
        } catch (Exception $e) {
            error_log("Erro ao buscar estado por ID: " . $e->getMessage());
            return null;
        }
    }
}
?>
