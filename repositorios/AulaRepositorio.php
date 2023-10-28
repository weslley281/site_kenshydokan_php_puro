<?php
include_once "../db/conexao.php";
include_once "../models/aulaModel.php";

class AulaRepositorio
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function criarAula(AulaModel $aula): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO aulas (id_curso, titulo, link, dataMudanca, dataCriacao) VALUES (?, ?, ?, ?, ?)");
            $inserir->bind_param("issss", $aula->getIdCurso(), $aula->getTitulo(), $aula->getLink(), $aula->getDataMudanca(), $aula->getDataCriacao());
            $resultado = $inserir->execute();
            $inserir->close();

            if (!$resultado) {
                throw new Exception("Erro ao criar a aula.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao criar a aula: " . $e->getMessage());
            return false;
        }
    }

    public function editarAula($id_aula, AulaModel $aula): bool
    {
        try {
            $editar = $this->conexao->prepare("UPDATE aulas SET id_curso = ?, titulo = ?, link = ?, dataMudanca = ? WHERE id_aula = ?");
            $editar->bind_param("ssssi", $aula->getIdCurso(), $aula->getTitulo(), $aula->getLink(), $aula->getDataMudanca(), $id_aula);
            $resultado = $editar->execute();
            $editar->close();

            if (!$resultado) {
                throw new Exception("Erro ao editar a aula.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao editar a aula: " . $e->getMessage());
            return false;
        }
    }

    public function excluirAula($id_aula): bool
    {
        try {
            $deletar = $this->conexao->prepare("DELETE FROM aulas WHERE id_aula = ?");
            $deletar->bind_param("i", $id_aula);
            $resultado = $deletar->execute();
            $deletar->close();

            if (!$resultado) {
                throw new Exception("Erro ao excluir a aula.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao excluir a aula: " . $e->getMessage());
            return false;
        }
    }

    public function buscarAula($id_aula)
    {
        try {
            $busca = "SELECT * FROM aulas WHERE id_aula = ?";
            $procura = $this->conexao->prepare($busca);
            $procura->bind_param("i", $id_aula);
            $procura->execute();
            $result = $procura->get_result();

            if ($result->num_rows === 0) {
                return null;
            }

            $aula = $result->fetch_assoc();
            $procura->close();

            return $aula;
        } catch (Exception $e) {
            error_log("Erro ao buscar a aula: " . $e->getMessage());
            return null;
        }
    }
}
