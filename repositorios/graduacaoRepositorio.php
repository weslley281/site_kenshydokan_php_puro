<?php
include_once "../db/conexao.php";
include_once "../models/graduacaoModel.php";

class GraduacaoRepositorio
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function criarGraduacao(GraduacaoModel $graduacao): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO graduacoes (graduacao, dataMudanca, dataCriacao) VALUES (?, ?, ?)");
            $inserir->bind_param("sss", $graduacao->getGraduacao(), $graduacao->getDataMudanca(), $graduacao->getDataCriacao());
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

    public function editarGraduacao($id_graduacao, GraduacaoModel $graduacao): bool
    {
        try {
            $editar = $this->conexao->prepare("UPDATE graduacoes SET graduacao = ?, dataMudanca = ? WHERE id_graduacao = ?");
            $editar->bind_param("ssi", $graduacao->getGraduacao(), $graduacao->getDataMudanca(), $id_graduacao);
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

            $busca = "SELECT * FROM graduacao WHERE id_graduacao = ?";
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
}
