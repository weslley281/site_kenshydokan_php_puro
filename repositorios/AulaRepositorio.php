<?php
include_once __DIR__ . "/../db/conexao.php";
include_once __DIR__ . "/../models/aulaModel.php";

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
            $inserir = $this->conexao->prepare("INSERT INTO aulas (id_curso, titulo, aula, dataMudanca, dataCriacao) VALUES (?, ?, ?, ?, ?)");
            $inserir->bind_param("issss", $aula->getIdCurso(), $aula->getTitulo(), $aula->getAula(), $aula->getDataMudanca(), $aula->getDataCriacao());
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
            $editar = $this->conexao->prepare("UPDATE aulas SET id_curso = ?, titulo = ?, aula = ?, dataMudanca = ? WHERE id_aula = ?");
            $editar->bind_param("isssi", $aula->getIdCurso(), $aula->getTitulo(), $aula->getAula(), $aula->getDataMudanca(), $id_aula);
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

    public function marcarAulaAssistida(int $id_usuario, int $id_aula): bool
    {
        try {
            // Check if already marked as watched
            if ($this->verificarAulaAssistida($id_usuario, $id_aula)) {
                return true; // Already marked, consider it a success
            }

            $inserir = $this->conexao->prepare("INSERT INTO aulas_assistidas (id_usuario, id_aula, data_assistido) VALUES (?, ?, NOW())");
            $inserir->bind_param("ii", $id_usuario, $id_aula);
            $resultado = $inserir->execute();
            $inserir->close();

            if (!$resultado) {
                throw new Exception("Erro ao marcar aula como assistida.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao marcar aula como assistida: " . $e->getMessage());
            return false;
        }
    }

    public function verificarAulaAssistida(int $id_usuario, int $id_aula): bool
    {
        try {
            $busca = "SELECT COUNT(*) FROM aulas_assistidas WHERE id_usuario = ? AND id_aula = ?";
            $procura = $this->conexao->prepare($busca);
            $procura->bind_param("ii", $id_usuario, $id_aula);
            $procura->execute();
            $procura->bind_result($count);
            $procura->fetch();
            $procura->close();

            return $count > 0;
        } catch (Exception $e) {
            error_log("Erro ao verificar aula assistida: " . $e->getMessage());
            return false;
        }
    }

    public function getAulasAssistidasPorUsuario(int $id_usuario): array
    {
        try {
            $aulas_assistidas = [];
            $busca = "SELECT id_aula FROM aulas_assistidas WHERE id_usuario = ?";
            $procura = $this->conexao->prepare($busca);
            $procura->bind_param("i", $id_usuario);
            $procura->execute();
            $result = $procura->get_result();

            while ($row = $result->fetch_assoc()) {
                $aulas_assistidas[] = $row['id_aula'];
            }
            $procura->close();

            return $aulas_assistidas;
        } catch (Exception $e) {
            error_log("Erro ao buscar aulas assistidas por usuário: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalAulasPorCurso(int $id_curso): int
    {
        try {
            $busca = "SELECT COUNT(*) FROM aulas WHERE id_curso = ?";
            $procura = $this->conexao->prepare($busca);
            $procura->bind_param("i", $id_curso);
            $procura->execute();
            $procura->bind_result($count);
            $procura->fetch();
            $procura->close();

            return $count;
        } catch (Exception $e) {
            error_log("Erro ao buscar o total de aulas por curso: " . $e->getMessage());
            return 0;
        }
    }
}
