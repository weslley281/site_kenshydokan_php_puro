<?php
include_once "../db/conexao.php";
include_once "../models/galeriaModel.php";

class GaleriaRepositorio
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function criarGaleria(GaleriaModel $galeria): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO galeria (nome, dataCriacao, dataMudanca) VALUES (?, ?, ?)");
            $inserir->bind_param("sss", $galeria->getNome(), $galeria->getDataCriacao(), $galeria->getDataMudanca());
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

    public function editarGaleria($id_galeria, GaleriaModel $galeria): bool
    {
        try {
            $editar = $this->conexao->prepare("UPDATE galeria SET nome = ?, dataMudanca = ? WHERE id_galeria = ?");
            $editar->bind_param("ssi", $galeria->getNome(), $galeria->getDataMudanca(), $id_galeria);
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

    public static function buscarGaleria($id_galeria)
    {
        try {
            $c = new Conexao();
            $conexao = $c->conectar();

            $busca = "SELECT * FROM galeria WHERE id_galeria = ?";
            $procura = $conexao->prepare($busca);
            $procura->bind_param("i", $id_galeria);
            $procura->execute();
            $result = $procura->get_result();

            if ($result->num_rows === 0) {
                return null;
            }

            $galeria = $result->fetch_assoc();
            $procura->close();

            return $galeria;
        } catch (Exception $e) {
            error_log("Erro ao buscar a galeria: " . $e->getMessage());
            return null;
        }
    }
}
