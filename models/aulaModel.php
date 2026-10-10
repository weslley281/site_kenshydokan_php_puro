<?php
include_once __DIR__ . "/../db/conexao.php";

class AulaModel
{
    private $id_aula;
    private $id_curso;
    private $titulo;
    private $aula;
    private $num_ordenacao;
    private $dataCriacao;
    private $dataMudanca;
    private $conexao;

    public function __construct($id_aula = null, $id_curso = null, $titulo = null, $aula = null, $num_ordenacao = null, $dataMudanca = null)
    {
        $this->id_aula = $id_aula;
        $this->id_curso = $id_curso;
        $this->titulo = $titulo;
        $this->aula = $aula;
        $this->num_ordenacao = $num_ordenacao;
        $this->dataCriacao = date("Y-m-d");
        $this->dataMudanca = $dataMudanca;

        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function getIdAula()
    {
        return $this->id_aula;
    }

    public function setIdAula($id_aula)
    {
        $this->id_aula = $id_aula;
    }

    public function getIdCurso()
    {
        return $this->id_curso;
    }

    public function setIdCurso($id_curso)
    {
        $this->id_curso = $id_curso;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function getAula()
    {
        return $this->aula;
    }

    public function setAula($aula)
    {
        $this->aula = $aula;
    }

    public function getNumOrdenacao()
    {
        return $this->num_ordenacao;
    }

    public function setNumOrdenacao($num_ordenacao)
    {
        $this->num_ordenacao = $num_ordenacao;
    }

    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }

    public function setDataCriacao($dataCriacao)
    {
        $this->dataCriacao = $dataCriacao;
    }

    public function getDataMudanca()
    {
        return $this->dataMudanca;
    }

    public function setDataMudanca($dataMudanca)
    {
        $this->dataMudanca = $dataMudanca;
    }

    // Métodos vindos do Repositório

    public function verificarOrdemExistente(int $id_curso, int $num_ordenacao, ?int $id_aula_excluir = null): bool
    {
        try {
            $busca = "SELECT COUNT(*) FROM aulas WHERE id_curso = ? AND num_ordenacao = ?";
            if ($id_aula_excluir !== null) {
                $busca .= " AND id_aula != ?";
            }
            $procura = $this->conexao->prepare($busca);

            if ($id_aula_excluir !== null) {
                $procura->bind_param("iii", $id_curso, $num_ordenacao, $id_aula_excluir);
            } else {
                $procura->bind_param("ii", $id_curso, $num_ordenacao);
            }

            $procura->execute();
            $procura->bind_result($count);
            $procura->fetch();
            $procura->close();

            return $count > 0;
        } catch (Exception $e) {
            error_log("Erro ao verificar ordem da aula: " . $e->getMessage());
            return true;
        }
    }

    public function criarAula(AulaModel $aula): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO aulas (id_curso, titulo, aula, num_ordenacao, dataMudanca, dataCriacao) VALUES (?, ?, ?, ?, ?, ?)");
            $id_curso = $aula->getIdCurso();
            $titulo = $aula->getTitulo();
            $aula_content = $aula->getAula();
            $num_ordenacao = $aula->getNumOrdenacao();
            $dataMudanca = $aula->getDataMudanca();
            $dataCriacao = $aula->getDataCriacao();

            $inserir->bind_param("ississ", $id_curso, $titulo, $aula_content, $num_ordenacao, $dataMudanca, $dataCriacao);
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
            $editar = $this->conexao->prepare("UPDATE aulas SET id_curso = ?, titulo = ?, aula = ?, num_ordenacao = ?, dataMudanca = ? WHERE id_aula = ?");
            $id_curso = $aula->getIdCurso();
            $titulo = $aula->getTitulo();
            $aula_content = $aula->getAula();
            $num_ordenacao = $aula->getNumOrdenacao();
            $dataMudanca = $aula->getDataMudanca();

            $editar->bind_param("issisi", $id_curso, $titulo, $aula_content, $num_ordenacao, $dataMudanca, $id_aula);
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
            if ($this->verificarAulaAssistida($id_usuario, $id_aula)) {
                return true;
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

    public function buscarAulasPorCurso($id_curso)
    {
        try {
            $aulas = [];
            $busca = "SELECT * FROM aulas WHERE id_curso = ? ORDER BY num_ordenacao ASC";
            $procura = $this->conexao->prepare($busca);
            $procura->bind_param("i", $id_curso);
            $procura->execute();
            $result = $procura->get_result();

            while ($row = $result->fetch_assoc()) {
                $aulas[] = $row;
            }
            $procura->close();

            return $aulas;
        } catch (Exception $e) {
            error_log("Erro ao buscar aulas por curso: " . $e->getMessage());
            return [];
        }
    }

    public function reordenarAulas(array $ordemAulas): bool
    {
        try {
            $this->conexao->begin_transaction();
            $stmt = $this->conexao->prepare("UPDATE aulas SET num_ordenacao = ? WHERE id_aula = ?");
            foreach ($ordemAulas as $item) {
                $num = (int)$item['num_ordenacao'];
                $id  = (int)$item['id_aula'];
                $stmt->bind_param("ii", $num, $id);
                $stmt->execute();
            }
            $stmt->close();
            $this->conexao->commit();
            return true;
        } catch (Exception $e) {
            $this->conexao->rollback();
            error_log("Erro ao reordenar aulas: " . $e->getMessage());
            return false;
        }
    }
}
