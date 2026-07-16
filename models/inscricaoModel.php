<?php
include_once __DIR__ . "/../db/conexao.php";

class Inscricao
{
    private $id_inscricao;
    private $id_campeonato;
    private $id_usuario;
    private $modalidade;
    private $categoria_peso;
    private $data_inscricao;
    private $status_inscricao;
    private $conexao;

    public function __construct($id_inscricao = null, $id_campeonato = null, $id_usuario = null, $modalidade = null, $categoria_peso = null, $data_inscricao = null, $status_inscricao = 'pendente')
    {
        $this->id_inscricao = $id_inscricao;
        $this->id_campeonato = $id_campeonato;
        $this->id_usuario = $id_usuario;
        $this->modalidade = $modalidade;
        $this->categoria_peso = $categoria_peso;
        $this->data_inscricao = $data_inscricao ? $data_inscricao : date("Y-m-d");
        $this->status_inscricao = $status_inscricao;

        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    // Getters
    public function getIdInscricao() { return $this->id_inscricao; }
    public function getIdCampeonato() { return $this->id_campeonato; }
    public function getIdUsuario() { return $this->id_usuario; }
    public function getModalidade() { return $this->modalidade; }
    public function getCategoriaPeso() { return $this->categoria_peso; }
    public function getDataInscricao() { return $this->data_inscricao; }
    public function getStatusInscricao() { return $this->status_inscricao; }

    // Setters
    public function setModalidade($modalidade) { $this->modalidade = $modalidade; }
    public function setCategoriaPeso($categoria_peso) { $this->categoria_peso = $categoria_peso; }
    public function setStatusInscricao($status_inscricao) { $this->status_inscricao = $status_inscricao; }

    public function criarInscricao(Inscricao $inscricao): bool
    {
        try {
            $stmt = $this->conexao->prepare("INSERT INTO campeonato_inscricoes (id_campeonato, id_usuario, modalidade, categoria_peso, data_inscricao, status_inscricao) VALUES (?, ?, ?, ?, ?, ?)");
            $id_campeonato = $inscricao->getIdCampeonato();
            $id_usuario = $inscricao->getIdUsuario();
            $modalidade = $inscricao->getModalidade();
            $categoria_peso = $inscricao->getCategoriaPeso();
            $data_inscricao = $inscricao->getDataInscricao();
            $status_inscricao = $inscricao->getStatusInscricao();

            $stmt->bind_param("iissss", $id_campeonato, $id_usuario, $modalidade, $categoria_peso, $data_inscricao, $status_inscricao);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao criar inscrição: " . $e->getMessage());
            return false;
        }
    }

    public function buscarInscricoesPorCampeonato($id_campeonato): array
    {
        $inscritos = [];
        try {
            $sql = "SELECT ci.*, u.nome as nome_usuario, u.email as email_usuario, u.telefone as telefone_usuario, u.nivel as nivel_usuario 
                    FROM campeonato_inscricoes ci
                    JOIN usuarios u ON ci.id_usuario = u.id_usuario
                    WHERE ci.id_campeonato = ?
                    ORDER BY u.nome ASC";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bind_param("i", $id_campeonato);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $inscritos[] = $row;
            }
            $stmt->close();
        } catch (Exception $e) {
            error_log("Erro ao buscar inscrições por campeonato: " . $e->getMessage());
        }
        return $inscritos;
    }

    public function buscarInscricaoUsuario($id_campeonato, $id_usuario)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM campeonato_inscricoes WHERE id_campeonato = ? AND id_usuario = ?");
            $stmt->bind_param("ii", $id_campeonato, $id_usuario);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                return null;
            }
            $row = $result->fetch_assoc();
            $stmt->close();
            return new Inscricao(
                $row['id_inscricao'],
                $row['id_campeonato'],
                $row['id_usuario'],
                $row['modalidade'],
                $row['categoria_peso'],
                $row['data_inscricao'],
                $row['status_inscricao']
            );
        } catch (Exception $e) {
            error_log("Erro ao buscar inscrição do usuário: " . $e->getMessage());
            return null;
        }
    }

    public function excluirInscricao($id_inscricao): bool
    {
        try {
            $stmt = $this->conexao->prepare("DELETE FROM campeonato_inscricoes WHERE id_inscricao = ?");
            $stmt->bind_param("i", $id_inscricao);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao excluir inscrição: " . $e->getMessage());
            return false;
        }
    }

    public function alterarStatus($id_inscricao, $status): bool
    {
        try {
            $stmt = $this->conexao->prepare("UPDATE campeonato_inscricoes SET status_inscricao = ? WHERE id_inscricao = ?");
            $stmt->bind_param("si", $status, $id_inscricao);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao alterar status da inscrição: " . $e->getMessage());
            return false;
        }
    }
}
