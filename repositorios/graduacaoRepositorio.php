<?php
include_once __DIR__ . "/../db/conexao.php";
include_once __DIR__ .  "/../models/graduacaoModel.php";

class GraduacaoRepositorio
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function criarGraduacao(Graduacao $graduacao): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO graduacoes (graduacao) VALUES (?)");
            $inserir->bind_param("s", $graduacao->getGraduacao());
            $resultado = $inserir->execute();
            $inserir->close();

            if (!$resultado) {
                throw new Exception("Erro ao criar a graduação.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao criar a graduação: " . $e->getMessage());
            return false;
        }
    }

    public function editarGraduacao($id_graduacao, $graduacao): bool
    {
        try {
            $editar = $this->conexao->prepare("UPDATE graduacoes SET graduacao = ? WHERE id_graduacao = ?");
            $editar->bind_param("si", $graduacao, $id_graduacao);
            $resultado = $editar->execute();
            $editar->close();

            if (!$resultado) {
                throw new Exception("Erro ao editar a graduação.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao editar a graduação: " . $e->getMessage());
            return false;
        }
    }

    public function excluirGraduacao($id_graduacao): bool
    {
        try {
            $deletar = $this->conexao->prepare("DELETE FROM graduacoes WHERE id_graduacao = ?");
            $deletar->bind_param("i", $id_graduacao);
            $resultado = $deletar->execute();
            $deletar->close();

            if (!$resultado) {
                throw new Exception("Erro ao excluir a graduação.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao excluir a graduação: " . $e->getMessage());
            return false;
        }
    }

    public static function buscarGraduacao($id_graduacao)
    {
        try {
            $c = new Conexao();
            $conexao = $c->conectar();

            $busca = "SELECT * FROM graduacoes WHERE id_graduacao = ?";
            $procura = $conexao->prepare($busca);
            $procura->bind_param("i", $id_graduacao);
            $procura->execute();
            $result = $procura->get_result();

            if ($result->num_rows === 0) {
                return null;
            }

            $graduacao = $result->fetch_assoc();
            $procura->close();

            return $graduacao;
        } catch (Exception $e) {
            error_log("Erro ao buscar a graduação: " . $e->getMessage());
            return null;
        }
    }

    public function listarGraduacoes()
    {
        $sql = "SELECT * FROM graduacoes ORDER BY id_graduacao ASC";
        $result = $this->conexao->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function buscarGraduacaoPorId($id_graduacao)
    {
        try {
            $busca = $this->conexao->prepare("SELECT * FROM graduacoes WHERE id_graduacao = ?");
            $busca->bind_param("i", $id_graduacao);
            $busca->execute();
            $result = $busca->get_result();

            if ($result->num_rows === 0) {
                return null;
            }

            $dados = $result->fetch_assoc();
            $busca->close();

            return new Graduacao($dados['id_graduacao'], $dados['graduacao']);
        } catch (Exception $e) {
            error_log("Erro ao buscar a graduação por ID: " . $e->getMessage());
            return null;
        }
    }
}
?>