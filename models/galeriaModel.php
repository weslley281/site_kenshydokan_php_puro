<?php
include_once __DIR__ . "/../db/conexao.php";

class Galeria
{
    private $id_galeria;
    private $nome;
    private $conexao;

    public function __construct($id_galeria = null, $nome = null)
    {
        $this->id_galeria = $id_galeria;
        $this->nome = $nome;

        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function getIdGaleria()
    {
        return $this->id_galeria;
    }

    public function getNome()
    {
        return $this->nome;
    }

    // Métodos vindos do Repositório

    public function criarGaleria(Galeria $galeria): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO galeria (nome) VALUES (?)");
            $nome = $galeria->getNome();
            $inserir->bind_param("s", $nome);
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
            $deletar_fotos = $this->conexao->prepare("DELETE FROM fotos WHERE id_galeria = ?");
            $deletar_fotos->bind_param("i", $id_galeria);
            $deletar_fotos->execute();
            $deletar_fotos->close();

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

    public function buscarGaleria($id_galeria)
    {
        $busca = $this->conexao->prepare("SELECT * FROM galeria WHERE id_galeria = ?");
        $busca->bind_param("i", $id_galeria);
        $busca->execute();
        $resultado = $busca->get_result();
        
        $galeria = null;
        if ($resultado->num_rows > 0) {
            $galeria = $resultado->fetch_assoc();
        }
        $busca->close();
        
        return $galeria;
    }

    public function buscarGaleriasComFotos()
    {
        $galleries_data = [];
        $busca_galleries = "SELECT * FROM galeria ORDER BY id_galeria ASC";
        $resultado_galleries = $this->conexao->query($busca_galleries);

        if ($resultado_galleries) {
            while ($gallery = $resultado_galleries->fetch_assoc()) {
                $id_galeria = $gallery["id_galeria"];
                $photos_data = [];

                $busca_photos = $this->conexao->prepare("SELECT * FROM fotos WHERE id_galeria = ? ORDER BY id_foto ASC");
                $busca_photos->bind_param("i", $id_galeria);
                $busca_photos->execute();
                $resultado_photos = $busca_photos->get_result();

                if ($resultado_photos) {
                    while ($photo = $resultado_photos->fetch_assoc()) {
                        $photos_data[] = $photo;
                    }
                }
                $busca_photos->close();

                $gallery['photos'] = $photos_data;
                $galleries_data[] = $gallery;
            }
        }
        return $galleries_data;
    }
}
