<?php
include_once __DIR__ . "/../db/conexao.php";

class Foto
{
    private $id_foto;
    private $id_galeria;
    private $nome;
    private $foto;
    private $dataUpload;
    private $conexao;

    public function __construct($id_foto = null, $id_galeria = null, $nome = null, $foto = null, $dataUpload = null)
    {
        $this->id_foto = $id_foto;
        $this->id_galeria = $id_galeria;
        $this->nome = $nome;
        $this->foto = $foto;
        $this->dataUpload = $dataUpload;

        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function getIdFoto()
    {
        return $this->id_foto;
    }

    public function getIdGaleria()
    {
        return $this->id_galeria;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getFoto()
    {
        return $this->foto;
    }

    public function getDataUpload()
    {
        return $this->dataUpload;
    }

    // Métodos vindos do Repositório

    public function adicionarFoto(Foto $foto): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO fotos (id_galeria, nome, foto, dataUpload) VALUES (?, ?, ?, ?)");
            $id_g = $foto->getIdGaleria();
            $nome = $foto->getNome();
            $ft = $foto->getFoto();
            $du = $foto->getDataUpload();

            $inserir->bind_param("ssss", $id_g, $nome, $ft, $du);
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

    public function buscarFotosPorGaleria($id_galeria)
    {
        $busca = $this->conexao->prepare("SELECT * FROM fotos WHERE id_galeria = ? ORDER BY id_foto ASC");
        $busca->bind_param("i", $id_galeria);
        $busca->execute();
        $resultado = $busca->get_result();
        
        $fotos = [];
        while ($foto = $resultado->fetch_assoc()) {
            $fotos[] = $foto;
        }
        $busca->close();
        
        return $fotos;
    }

    public function excluirFoto($id_foto): bool
    {
        try {
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
