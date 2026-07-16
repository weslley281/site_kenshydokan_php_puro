<?php
include_once __DIR__ . "/../db/conexao.php";

class Filiacao
{
    private $id_filiacao;
    private $nome;
    private $logo;
    private $link;
    private $status;
    private $dataCriacao;
    private $dataMudanca;
    private $conexao;

    public function __construct($id_filiacao = null, $nome = null, $logo = null, $link = null, $status = 'ativo', $dataMudanca = null)
    {
        $this->id_filiacao = $id_filiacao;
        $this->nome = $nome;
        $this->logo = $logo;
        $this->link = $link;
        $this->status = $status;
        $this->dataCriacao = date("Y-m-d");
        $this->dataMudanca = $dataMudanca;

        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    // Getters
    public function getIdFiliacao() { return $this->id_filiacao; }
    public function getNome() { return $this->nome; }
    public function getLogo() { return $this->logo; }
    public function getLink() { return $this->link; }
    public function getStatus() { return $this->status; }
    public function getDataCriacao() { return $this->dataCriacao; }
    public function getDataMudanca() { return $this->dataMudanca; }

    // Setters
    public function setNome($nome) { $this->nome = $nome; }
    public function setLogo($logo) { $this->logo = $logo; }
    public function setLink($link) { $this->link = $link; }
    public function setStatus($status) { $this->status = $status; }
    public function setDataCriacao($dataCriacao) { $this->dataCriacao = $dataCriacao; }
    public function setDataMudanca($dataMudanca) { $this->dataMudanca = $dataMudanca; }

    public function criarFiliacao(Filiacao $filiacao): bool
    {
        try {
            $stmt = $this->conexao->prepare("INSERT INTO filiacoes (nome, logo, link, status, dataCriacao) VALUES (?, ?, ?, ?, ?)");
            $nome = $filiacao->getNome();
            $logo = $filiacao->getLogo();
            $link = $filiacao->getLink();
            $status = $filiacao->getStatus();
            $dataCriacao = $filiacao->getDataCriacao();

            $stmt->bind_param("sssss", $nome, $logo, $link, $status, $dataCriacao);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        } catch (Exception $e) {
            error_log("Erro ao criar filiação: " . $e->getMessage());
            return false;
        }
    }

    public function editarFiliacao($id_filiacao, $nome, $logo, $link, $status): bool
    {
        try {
            $stmt = $this->conexao->prepare("UPDATE filiacoes SET nome = ?, logo = ?, link = ?, status = ?, dataMudanca = CURDATE() WHERE id_filiacao = ?");
            $stmt->bind_param("ssssi", $nome, $logo, $link, $status, $id_filiacao);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        } catch (Exception $e) {
            error_log("Erro ao editar filiação: " . $e->getMessage());
            return false;
        }
    }

    public function excluirFiliacao($id_filiacao): bool
    {
        try {
            $stmt = $this->conexao->prepare("DELETE FROM filiacoes WHERE id_filiacao = ?");
            $stmt->bind_param("i", $id_filiacao);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        } catch (Exception $e) {
            error_log("Erro ao excluir filiação: " . $e->getMessage());
            return false;
        }
    }

    public function buscarPorId($id_filiacao)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM filiacoes WHERE id_filiacao = ?");
            $stmt->bind_param("i", $id_filiacao);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                return null;
            }
            $dados = $result->fetch_assoc();
            $stmt->close();

            $filiacao = new Filiacao(
                $dados['id_filiacao'],
                $dados['nome'],
                $dados['logo'],
                $dados['link'],
                $dados['status'],
                $dados['dataMudanca']
            );
            $filiacao->setDataCriacao($dados['dataCriacao']);
            return $filiacao;
        } catch (Exception $e) {
            error_log("Erro ao buscar filiação por ID: " . $e->getMessage());
            return null;
        }
    }

    public function listarTodas(): array
    {
        $filiacoes = [];
        try {
            $result = $this->conexao->query("SELECT * FROM filiacoes ORDER BY id_filiacao DESC");
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $filiacoes[] = $row;
                }
            }
        } catch (Exception $e) {
            error_log("Erro ao listar todas as filiações: " . $e->getMessage());
        }
        return $filiacoes;
    }

    public function listarAtivas(): array
    {
        $filiacoes = [];
        try {
            $result = $this->conexao->query("SELECT * FROM filiacoes WHERE status = 'ativo' ORDER BY id_filiacao ASC");
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $filiacoes[] = $row;
                }
            }
        } catch (Exception $e) {
            error_log("Erro ao listar filiações ativas: " . $e->getMessage());
        }
        return $filiacoes;
    }
}
