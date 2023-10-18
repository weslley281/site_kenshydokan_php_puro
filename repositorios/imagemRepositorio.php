<?php
include_once "../db/conexao.php";
include_once "../models/imagemModel.php";

class ImagemRepositorio
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function registrar_imagem(Imagem $imagem): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO imagens (nome, caminho, dataCriacao, dataMudanca) VALUES (?, ?, ?, ?)");
            $inserir->bind_param("ssss", $imagem->getNome(), $imagem->getCaminho(), $imagem->getDataCriacao(), $imagem->getDataMudanca());
            $resultado = $inserir->execute();
            $inserir->close();

            if (!$resultado) {
                throw new Exception("Erro ao registrar a imagem.");
            }

            return true;
        } catch (Exception $e) {
            // Você pode lidar com o erro aqui, como logá-lo ou lançar uma exceção personalizada.
            error_log("Erro ao registrar a imagem: " . $e->getMessage());
            return false;
        }
    }

    public function deleta_imagem($nome, $caminho): bool
    {
        try {
            $deletar = $this->conexao->prepare("DELETE FROM imagens WHERE nome = ? AND caminho = ?");
            $deletar->bind_param("ss", $nome, $caminho);
            $resultado = $deletar->execute();
            $deletar->close();

            if (!$resultado) {
                throw new Exception("Erro ao excluir a imagem.");
            }

            return true;
        } catch (Exception $e) {
            // Você pode lidar com o erro aqui, como logá-lo ou lançar uma exceção personalizada.
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

}
