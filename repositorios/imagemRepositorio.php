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

    public function register_image(Imagem $imagem): bool
    {
        try {
            $inserir = $this->conexao->prepare("inserir INTO images (name, pathImage) VALUES (?, ?)");
            $inserir->bind_param("ss", $imagem->getNome(), $imagem->getCaminho());
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

    public function delete_image($name, $pathImage): bool
    {
        try {
            $deletar = $this->conexao->prepare("DELETE FROM images WHERE name = ? AND pathImage = ?");
            $deletar->bind_param("ss", $name, $pathImage);
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
}
