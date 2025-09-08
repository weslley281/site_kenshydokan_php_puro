<?php
include_once __DIR__ . "/../db/conexao.php";
include_once __DIR__ .  "/../models/galeriaModel.php";

class GaleriaRepositorio
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function criarGaleria(Galeria $galeria): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO galeria (nome) VALUES (?)");
            $inserir->bind_param("s", $galeria->getNome());
            $resultado = $inserir->execute();
            $inserir->close();

            if (!$resultado) {
                throw new Exception("Erro ao criar a galeria.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao criar a galeria: " . $e->getMessage());
            return false;
        }
    }

    public function editarGaleria($id_galeria, $nome): bool
    {
        try {
            $editar = $this->conexao->prepare("UPDATE galeria SET nome = ? WHERE id_galeria = ?");
            $editar->bind_param("si", $nome, $id_galeria);
            $resultado = $editar->execute();
            $editar->close();

            if (!$resultado) {
                throw new Exception("Erro ao editar a galeria.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao editar a galeria: " . $e->getMessage());
            return false;
        }
    }

    public function excluirGaleria($id_galeria): bool
    {
        try {
            // First, delete photos associated with the gallery
            $deletar_fotos = $this->conexao->prepare("DELETE FROM fotos WHERE id_galeria = ?");
            $deletar_fotos->bind_param("i", $id_galeria);
            $deletar_fotos->execute();
            $deletar_fotos->close();

            // Then, delete the gallery itself
            $deletar = $this->conexao->prepare("DELETE FROM galeria WHERE id_galeria = ?");
            $deletar->bind_param("i", $id_galeria);
            $resultado = $deletar->execute();
            $deletar->close();

            if (!$resultado) {
                throw new Exception("Erro ao excluir a galeria.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao excluir a galeria: " . $e->getMessage());
            return false;
        }
    }
}
?>