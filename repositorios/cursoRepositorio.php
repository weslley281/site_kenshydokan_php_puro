<?php
include_once "../db/conexao.php";
include_once "../models/cursoModel.php";

class CursoRepositorio
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function criarCurso(CursoModel $curso): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO cursos (id_categoria, nome, descricao, professor, id_imagem, cargaHoraria, situacao, dataMudanca, dataCriacao) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $inserir->bind_param("isssiisss", $curso->getIdCategoria(), $curso->getNome(), $curso->getDescricao(), $curso->getProfessor(), $curso->getIdImagem(), $curso->getCargaHoraria(), $curso->getSituacao(), $curso->getDataMudanca(), $curso->getDataCriacao());
            $resultado = $inserir->execute();
            $inserir->close();

            if (!$resultado) {
                throw new Exception("Erro ao criar o curso.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao criar o curso: " . $e->getMessage());
            return false;
        }
    }

    public function editarCurso($id_curso, CursoModel $curso): bool
    {
        try {
            $editar = $this->conexao->prepare("UPDATE cursos SET id_categoria = ?, nome = ?, descricao = ?, professor = ?, cargaHoraria = ? situacao = ?, dataMudanca = ? WHERE id_curso = ?");
            $editar->bind_param("isssissi", $curso->getIdCategoria(), $curso->getNome(), $curso->getDescricao(), $curso->getProfessor(), $curso->getCargaHoraria(), $curso->getSituacao(), $curso->getDataMudanca(), $id_curso);
            $resultado = $editar->execute();
            $editar->close();

            if (!$resultado) {
                throw new Exception("Erro ao editar o curso.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao editar o curso: " . $e->getMessage());
            return false;
        }
    }

    public static function editarImagemCurso(int $id_curso, $id_imagem, $dataMudanca): bool
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $atualizar = $conexao->prepare("UPDATE cursos SET id_imagem = ?, dataMudanca = ? WHERE id_curso = ?");
        $atualizar->bind_param("isi", $id_imagem, $dataMudanca, $id_curso);
        $resultado = $atualizar->execute();
        $atualizar->close();

        return $resultado;
    }

    public function excluirCurso($id_curso): bool
    {
        try {
            $deletar = $this->conexao->prepare("DELETE FROM cursos WHERE id_curso = ?");
            $deletar->bind_param("i", $id_curso);
            $resultado = $deletar->execute();
            $deletar->close();

            if (!$resultado) {
                throw new Exception("Erro ao excluir o curso.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao excluir o curso: " . $e->getMessage());
            return false;
        }
    }

    public static function buscarCurso($id_curso)
    {
        try {
            $c = new Conexao();
            $conexao = $c->conectar();

            $busca = "SELECT * FROM cursos WHERE id_curso = ?";
            $procura = $conexao->prepare($busca);
            $procura->bind_param("i", $id_curso);
            $procura->execute();
            $result = $procura->get_result();

            if ($result->num_rows === 0) {
                return null;
            }

            $curso = $result->fetch_assoc();
            $procura->close();

            return $curso;
        } catch (Exception $e) {
            error_log("Erro ao buscar o curso: " . $e->getMessage());
            return null;
        }
    }
}
