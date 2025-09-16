<?php
include_once __DIR__ . "/../db/conexao.php";
include_once __DIR__ .  "/../models/fotoModel.php";

class FotoRepositorio
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function adicionarFoto(Foto $foto): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO fotos (id_galeria, nome, foto, dataUpload) VALUES (?, ?, ?, ?)");
            $inserir->bind_param("ssss", $foto->getIdGaleria(), $foto->getNome(), $foto->getFoto(), $foto->getDataUpload());
            $resultado = $inserir->execute();
            $inserir->close();

            if (!$resultado) {
                throw new Exception("Erro ao adicionar a foto.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao adicionar a foto: " . $e->getMessage());
            return false;
        }
    }

    public function excluirFoto($id_foto): bool
    {
        try {
            // First, get the photo filename to delete the file
            $procura = $this->conexao->prepare("SELECT foto FROM fotos WHERE id_foto = ?");
            $procura->bind_param("i", $id_foto);
            $procura->execute();
            $resultado = $procura->get_result();
            $foto = $resultado->fetch_assoc();
            $procura->close();

            if ($foto) {
                $caminho_foto = __DIR__ . "/../../slides/" . $foto['foto'];
                if (file_exists($caminho_foto)) {
                    unlink($caminho_foto);
                }
            }

            $deletar = $this->conexao->prepare("DELETE FROM fotos WHERE id_foto = ?");
            $deletar->bind_param("i", $id_foto);
            $resultado = $deletar->execute();
            $deletar->close();

            if (!$resultado) {
                throw new Exception("Erro ao excluir a foto.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao excluir a foto: " . $e->getMessage());
            return false;
        }
    }
}
