<?php
include_once __DIR__ . "/../db/conexao.php";

class ListaPresensaInstrutorModel
{
    private $id_listaPresensaInstrutor;
    private $id_filiado;
    private $dataPresenca;
    private $status;
    private $conteudoAula;
    private $conexao;

    public function __construct($id_listaPresensaInstrutor = null, $id_filiado = null, $dataPresenca = null, $status = null, $conteudoAula = null)
    {
        $this->id_listaPresensaInstrutor = $id_listaPresensaInstrutor;
        $this->id_filiado = $id_filiado;
        $this->dataPresenca = $dataPresenca;
        $this->status = $status;
        $this->conteudoAula = $conteudoAula;

        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function getIdListaPresensaInstrutor()
    {
        return $this->id_listaPresensaInstrutor;
    }

    public function getIdFiliado()
    {
        return $this->id_filiado;
    }

    public function getDataPresenca()
    {
        return $this->dataPresenca;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getConteudoAula()
    {
        return $this->conteudoAula;
    }

    // Métodos vindos do Repositório

    public function crearListaPresensaInstrutor(ListaPresensaInstrutorModel $listaPresensaInstrutor): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO lista_presenca_instrutor (id_filiado, data_presenca, status, conteudo_aula) VALUES (?, ?, ?, ?)");
            $if = $listaPresensaInstrutor->getIdFiliado();
            $dp = $listaPresensaInstrutor->getDataPresenca();
            $st = $listaPresensaInstrutor->getStatus();
            $ca = $listaPresensaInstrutor->getConteudoAula();

            $inserir->bind_param("isss", $if, $dp, $st, $ca);
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
            $editar = $this->conexao->prepare("UPDATE lista_presenca_instrutor SET id_filiado = ?, data_presenca = ?, status = ?, conteudo_aula = ? WHERE id_listapresensainstrutor = ?");
            $if = $listaPresensaInstrutor->getIdFiliado();
            $dp = $listaPresensaInstrutor->getDataPresenca();
            $st = $listaPresensaInstrutor->getStatus();
            $ca = $listaPresensaInstrutor->getConteudoAula();

            $editar->bind_param("isssi", $if, $dp, $st, $ca, $id_listapresensainstrutor);
            $resultado = $editar->execute();
            $editar->close();

            if (!$resultado) {
                throw new Exception("Erro ao editar a lista de presenca.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao editar a lista de presenca: " . $e->getMessage());
            return false;
        }
    }

    public function excluirListaPresensaInstrutor($id_listapresensainstrutor): bool
    {
        try {
            $deletar = $this->conexao->prepare("DELETE FROM lista_presenca_instrutor WHERE id_listapresensainstrutor = ?");
            $deletar->bind_param("i", $id_listapresensainstrutor);
            $resultado = $deletar->execute();
            $deletar->close();

            if (!$resultado) {
                throw new Exception("Erro ao excluir a lista de presenca.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao excluir a lista de presenca: " . $e->getMessage());
            return false;
        }
    }

    public function listarLista_presenca_instrutors()
    {
        $sql = "SELECT * FROM lista_presenca_instrutor ORDER BY id_listapresensainstrutor ASC";
        $result = $this->conexao->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
