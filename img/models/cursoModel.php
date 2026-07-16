<?php
include_once __DIR__ . "/../db/conexao.php";

class CursoModel
{
    private $id_curso;
    private $id_categoria;
    private $nome;
    private $descricao;
    private $professor;
    private $id_imagem;
    private $cargaHoraria;
    private $situacao;
    private $dataCriacao;
    private $dataMudanca;
    private $percentual_conclusao_certificado;
    private $temCertificado;
    private $conexao;

    public function __construct($id_curso = null, $id_categoria = null, $nome = null, $descricao = null, $professor = null, $id_imagem = null, $cargaHoraria = null, $situacao = null, $dataMudanca = null, $percentual_conclusao_certificado = null, $temCertificado = null)
    {
        $this->id_curso = $id_curso;
        $this->id_categoria = $id_categoria;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->professor = $professor;
        $this->id_imagem = $id_imagem;
        $this->cargaHoraria = $cargaHoraria;
        $this->situacao = $situacao;
        $this->dataCriacao = date("Y-m-d");
        $this->dataMudanca = $dataMudanca;
        $this->percentual_conclusao_certificado = $percentual_conclusao_certificado;
        $this->temCertificado = $temCertificado;

        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function getIdCurso()
    {
        return $this->id_curso;
    }

    public function setIdCurso($id_curso)
    {
        $this->id_curso = $id_curso;
    }

    public function getIdCategoria()
    {
        return $this->id_categoria;
    }

    public function setIdCategoria($id_categoria)
    {
        $this->id_categoria = $id_categoria;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getProfessor()
    {
        return $this->professor;
    }

    public function setProfessor($professor)
    {
        $this->professor = $professor;
    }

    public function getIdImagem()
    {
        return $this->id_imagem;
    }

    public function setIdImagem($id_imagem)
    {
        $this->id_imagem = $id_imagem;
    }

    public function getCargaHoraria()
    {
        return $this->cargaHoraria;
    }

    public function setCargaHoraria($cargaHoraria)
    {
        $this->cargaHoraria = $cargaHoraria;
    }

    public function getSituacao()
    {
        return $this->situacao;
    }

    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
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

    public function getPercentualConclusaoCertificado()
    {
        return $this->percentual_conclusao_certificado;
    }

    public function setPercentualConclusaoCertificado($percentual_conclusao_certificado)
    {
        $this->percentual_conclusao_certificado = $percentual_conclusao_certificado;
    }

    public function getTemCertificado()
    {
        return $this->temCertificado;
    }

    public function setTemCertificado($temCertificado)
    {
        $this->temCertificado = $temCertificado;
    }

    // Métodos vindos do Repositório

    public function criarCurso(CursoModel $curso): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO cursos (id_categoria, nome, descricao, professor, id_imagem, cargaHoraria, situacao, dataMudanca, dataCriacao, percentual_conclusao_certificado, temCertificado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $id_cat = $curso->getIdCategoria();
            $nome = $curso->getNome();
            $desc = $curso->getDescricao();
            $prof = $curso->getProfessor();
            $id_img = $curso->getIdImagem();
            $ch = $curso->getCargaHoraria();
            $sit = $curso->getSituacao();
            $dm = $curso->getDataMudanca();
            $dc = $curso->getDataCriacao();
            $pcc = $curso->getPercentualConclusaoCertificado();
            $tc = $curso->getTemCertificado();

            $inserir->bind_param("isssiisssis", $id_cat, $nome, $desc, $prof, $id_img, $ch, $sit, $dm, $dc, $pcc, $tc);
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
            $editar = $this->conexao->prepare("UPDATE cursos SET id_categoria = ?, nome = ?, descricao = ?, professor = ?, cargaHoraria = ?, situacao = ?, dataMudanca = ?, percentual_conclusao_certificado = ?, temCertificado = ? WHERE id_curso = ?");
            $id_cat = $curso->getIdCategoria();
            $nome = $curso->getNome();
            $desc = $curso->getDescricao();
            $prof = $curso->getProfessor();
            $ch = $curso->getCargaHoraria();
            $sit = $curso->getSituacao();
            $dm = $curso->getDataMudanca();
            $pcc = $curso->getPercentualConclusaoCertificado();
            $tc = $curso->getTemCertificado();

            $editar->bind_param("isssissisi", $id_cat, $nome, $desc, $prof, $ch, $sit, $dm, $pcc, $tc, $id_curso);
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

    public static function buscarCursosAprovados()
    {
        try {
            $c = new Conexao();
            $conexao = $c->conectar();

            $query = "SELECT * FROM cursos WHERE situacao = 'aprovado' ORDER BY nome ASC";
            $resultado = $conexao->query($query);
            return $resultado->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar os cursos aprovados: " . $e->getMessage());
            return [];
        }
    }

    public static function buscarTodosCursos()
    {
        try {
            $c = new Conexao();
            $conexao = $c->conectar();

            $query = "SELECT * FROM cursos ORDER BY nome ASC";
            $resultado = $conexao->query($query);
            return $resultado->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar todos os cursos: " . $e->getMessage());
            return [];
        }
    }
}
