<?php
include_once __DIR__ . "/../db/conexao.php";
include_once __DIR__ .  "/../models/listaPresensaInstrutorModel.php";

class ListaPresensaInstrutorRepositorio
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function criarListaPresensaInstrutor(ListaPresensaInstrutorModel $listaPresensaInstrutor): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO lista_presenca_instrutor (id_filiado, data_presenca, status, conteudo_aula) VALUES (?, ?, ?, ?)");
            $inserir->bind_param("isss", $listaPresensaInstrutor->getIdFiliado(), $listaPresensaInstrutor->getDataPresenca(), $listaPresensaInstrutor->getStatus(), $listaPresensaInstrutor->getConteudoAula());
            $resultado = $inserir->execute();
            $inserir->close();

            if (!$resultado) {
                throw new Exception("Erro ao criar a lista de presenca do instrutor.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao criar a lista de presenca do instrutor: " . $e->getMessage());
            return false;
        }
    }

    public function editarListaPresensaInstrutor($id_listapresensainstrutor, ListaPresensaInstrutorModel $listaPresensaInstrutor): bool
    {
        try {
            $editar = $this->conexao->prepare("UPDATE lista_presenca_instrutors SET id_filiado = ?, data_presenca = ?, status = ?, conteudo_aula = ? WHERE id_listapresensainstrutor = ?");
            $editar->bind_param("isssi",  $listaPresensaInstrutor->getIdFiliado(), $listaPresensaInstrutor->getDataPresenca(), $listaPresensaInstrutor->getStatus(), $listaPresensaInstrutor->getConteudoAula(), $id_listapresensainstrutor);
            $resultado = $editar->execute();
            $editar->close();

            if (!$resultado) {
                throw new Exception("Erro ao editar a graduação.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao editar a graduação: " . $e->getMessage());
            return false;
        }
    }

    public function excluirListaPresensaInstrutor($id_listapresensainstrutor): bool
    {
        try {
            $deletar = $this->conexao->prepare("DELETE FROM lista_presenca_instrutors WHERE id_listapresensainstrutor = ?");
            $deletar->bind_param("i", $id_listapresensainstrutor);
            $resultado = $deletar->execute();
            $deletar->close();

            if (!$resultado) {
                throw new Exception("Erro ao excluir a graduação.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao excluir a graduação: " . $e->getMessage());
            return false;
        }
    }

    public static function buscarGraduacao($id_listapresensainstrutor)
    {
        try {
            $c = new Conexao();
            $conexao = $c->conectar();

            $busca = "SELECT * FROM lista_presenca_instrutors WHERE id_listapresensainstrutor = ?";
            $procura = $conexao->prepare($busca);
            $procura->bind_param("i", $id_listapresensainstrutor);
            $procura->execute();
            $result = $procura->get_result();

            if ($result->num_rows === 0) {
                return null;
            }

            $graduacao = $result->fetch_assoc();
            $procura->close();

            return $graduacao;
        } catch (Exception $e) {
            error_log("Erro ao buscar a graduação: " . $e->getMessage());
            return null;
        }
    }

    public function listarLista_presenca_instrutors()
    {
        $sql = "SELECT * FROM lista_presenca_instrutors ORDER BY id_listapresensainstrutor ASC";
        $result = $this->conexao->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function buscarGraduacaoPorId($id_listapresensainstrutor)
    {
        try {
            $busca = $this->conexao->prepare("SELECT * FROM lista_presenca_instrutors WHERE id_listapresensainstrutor = ?");
            $busca->bind_param("i", $id_listapresensainstrutor);
            $busca->execute();
            $result = $busca->get_result();

            if ($result->num_rows === 0) {
                return null;
            }

            $dados = $result->fetch_assoc();
            $busca->close();

            return new Graduacao($dados['id_listapresensainstrutor'], $dados['graduacao']);
        } catch (Exception $e) {
            error_log("Erro ao buscar a graduação por ID: " . $e->getMessage());
            return null;
        }
    }
}
?>