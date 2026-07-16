<?php
include_once __DIR__ . "/../db/conexao.php";

class Imagem
{
    private $nome;
    private $caminho;
    private $dataMudanca;
    private $dataCriacao;
    private $conexao;

    public function __construct($nome = null, $caminho = null, $dataMudanca = null)
    {
        $this->nome = $nome;
        $this->caminho = $caminho;
        $this->dataMudanca = $dataMudanca;
        $this->dataCriacao = date("Y-m-d");

        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getCaminho()
    {
        return $this->caminho;
    }

    public function setCaminho($caminho)
    {
        $this->caminho = $caminho;
    }

    public function getDataMudanca()
    {
        return $this->dataMudanca;
    }

    public function setDataMudanca($dataMudanca)
    {
        $this->dataMudanca = $dataMudanca;
    }

    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }

    // Métodos vindos do Repositório

    public function registrar_imagem(Imagem $imagem): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO imagens (nome, caminho, dataCriacao, dataMudanca) VALUES (?, ?, ?, ?)");
            $nome = $imagem->getNome();
            $cam = $imagem->getCaminho();
            $dc = $imagem->getDataCriacao();
            $dm = $imagem->getDataMudanca();

            $inserir->bind_param("ssss", $nome, $cam, $dc, $dm);
            $resultado = $inserir->execute();
            $inserir->close();

            if (!$resultado) {
                throw new Exception("Erro ao registrar a imagem.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao registrar a imagem: " . $e->getMessage());
            return false;
        }
    }

    public function deleta_imagem($id_imagem): bool
    {
        try {
            $deletar = $this->conexao->prepare("DELETE FROM imagens WHERE id_imagem = ?");
            $deletar->bind_param("i", $id_imagem);
            $resultado = $deletar->execute();
            $deletar->close();

            if (!$resultado) {
                throw new Exception("Erro ao excluir a imagem.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao excluir a imagem: " . $e->getMessage());
            return false;
        }
    }

    public static function procura_id_imagem($nome)
    {
        try {
            $c = new Conexao();
            $conexao = $c->conectar();

            $busca = "SELECT id_imagem FROM imagens WHERE nome = ?";

            $procura = $conexao->prepare($busca);
            $procura->bind_param("s", $nome);
            $procura->execute();
            $procura->bind_result($resultado);

            $imagem = null;

            if ($procura->fetch()) {
                $imagem = $resultado;
            }

            $procura->close();

            return $imagem;
        } catch (Exception $e) {

            error_log("Erro ao procurar o ID da imagem: " . $e->getMessage());
            return null;
        }
    }

    public static function procura_imagem($id_imagem)
    {
        try {
            $c = new Conexao();
            $conexao = $c->conectar();

            $busca = "SELECT * FROM imagens WHERE id_imagem = ?";

            $procura = $conexao->prepare($busca);
            $procura->bind_param("i", $id_imagem);
            $procura->execute();

            $result = $procura->get_result();

            if ($result->num_rows === 0) {
                return null;
            }

            $imagem = $result->fetch_assoc();

            $procura->close();

            return $imagem;
        } catch (Exception $e) {
            error_log("Erro ao procurar o ID da imagem: " . $e->getMessage());
            return null;
        }
    }
}
