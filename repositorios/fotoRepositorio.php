<?php
include_once "../db/conexao.php";
include_once "../models/fotoModel.php";

class FotoRepositorio
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function criarFoto(FotoModel $foto): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO fotos (id_galeria, nome, foto, dataUpload, dataCriacao, dataMudanca) VALUES (?, ?, ?, ?, ?, ?)");
            $inserir->bind_param("isssss", $foto->getIdGaleria(), $foto->getNome(), $foto->getFoto(), $foto->getDataUpload(), $foto->getDataCriacao(), $foto->getDataMudanca());
            $resultado = $inserir->execute();
            $inserir->close();

            if (!$resultado) {
                throw new Exception("Erro ao criar a foto.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao criar a foto: " . $e->getMessage());
            return false;
        }
    }

    public function editarFoto($id_foto, FotoModel $foto): bool
    {
        try {
            $editar = $this->conexao->prepare("UPDATE fotos SET id_galeria = ?, nome = ?, foto = ?, dataUpload = ?, dataMudanca = ? WHERE id_foto = ?");
            $editar->bind_param("issssi", $foto->getIdGaleria(), $foto->getNome(), $foto->getFoto(), $foto->getDataUpload(), $foto->getDataMudanca(), $id_foto);
            $resultado = $editar->execute();
            $editar->close();

            if (!$resultado) {
                throw new Exception("Erro ao editar a foto.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao editar a foto: " . $e->getMessage());
            return false;
        }
    }

    public function excluirFoto($id_foto): bool
    {
        try {
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

    public static function buscarFoto($id_foto)
    {
        try {
            $c = new Conexao();
            $conexao = $c->conectar();

            $busca = "SELECT * FROM fotos WHERE id_foto = ?";
            $procura = $conexao->prepare($busca);
            $procura->bind_param("i", $id_foto);
            $procura->execute();
            $result = $procura->get_result();

            if ($result->num_rows === 0) {
                return null;
            }

            $foto = $result->fetch_assoc();
            $procura->close();

            return $foto;
        } catch (Exception $e) {
            error_log("Erro ao buscar a foto: " . $e->getMessage());
            return null;
        }
    }
}
