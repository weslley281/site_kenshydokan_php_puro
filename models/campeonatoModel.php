<?php
include_once __DIR__ . "/../db/conexao.php";

class Campeonato
{
    private $id_campeonato;
    private $titulo;
    private $subtitulo;
    private $endereco;
    private $ativo; // "sim" ou "nao"
    private $dataCriacao; // Data do campeonato
    private $dataMudanca; // Data de modificacao
    private $tipo; // "interno" ou "externo"
    private $link_externo; // Link para inscricao externa
    private $conexao;

    public function __construct($id_campeonato = null, $titulo = null, $subtitulo = null, $endereco = null, $ativo = null, $dataCriacao = null, $dataMudanca = null, $tipo = null, $link_externo = null)
    {
        $this->id_campeonato = $id_campeonato;
        $this->titulo = $titulo;
        $this->subtitulo = $subtitulo;
        $this->endereco = $endereco;
        $this->ativo = $ativo;
        $this->dataCriacao = $dataCriacao ? $dataCriacao : date("Y-m-d");
        $this->dataMudanca = $dataMudanca;
        $this->tipo = $tipo ? $tipo : "interno";
        $this->link_externo = $link_externo;

        $c = new Conexao();
        $this->conexao = $c->conectar();

        // Atualizacao automatica da tabela se necessario
        if ($this->conexao) {
            try {
                // Verificar e adicionar coluna 'tipo' se nao existir
                $check = $this->conexao->query("SHOW COLUMNS FROM campeonatos LIKE 'tipo'");
                if ($check && $check->num_rows == 0) {
                    @$this->conexao->query("ALTER TABLE campeonatos ADD COLUMN tipo VARCHAR(50) NOT NULL DEFAULT 'interno'");
                }
                // Verificar e adicionar coluna 'link_externo' se nao existir
                $check_link = $this->conexao->query("SHOW COLUMNS FROM campeonatos LIKE 'link_externo'");
                if ($check_link && $check_link->num_rows == 0) {
                    @$this->conexao->query("ALTER TABLE campeonatos ADD COLUMN link_externo VARCHAR(500) DEFAULT NULL");
                }
            } catch (Throwable $t) {
                error_log("Aviso: Falha nas colunas de campeonatos: " . $t->getMessage());
            }
        }
    }

    public function getIdCampeonato() { return $this->id_campeonato; }
    public function getTitulo() { return $this->titulo; }
    public function getSubtitulo() { return $this->subtitulo; }
    public function getEndereco() { return $this->endereco; }
    public function getAtivo() { return $this->ativo; }
    public function getDataCriacao() { return $this->dataCriacao; }
    public function getDataMudanca() { return $this->dataMudanca; }
    public function getTipo() { return $this->tipo; }
    public function getLinkExterno() { return $this->link_externo; }

    public function criarCampeonato(Campeonato $campeonato): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO campeonatos (titulo, subtitulo, endereco, ativo, dataCriacao, tipo, link_externo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $t = $campeonato->getTitulo();
            $s = $campeonato->getSubtitulo();
            $e = $campeonato->getEndereco();
            $a = $campeonato->getAtivo();
            $d = $campeonato->getDataCriacao();
            $tp = $campeonato->getTipo();
            $lk = $campeonato->getLinkExterno();

            $inserir->bind_param("sssssss", $t, $s, $e, $a, $d, $tp, $lk);
            $resultado = $inserir->execute();
            $inserir->close();
            return $resultado;
        } catch (Exception $ex) {
            error_log("Erro ao criar campeonato: " . $ex->getMessage());
            return false;
        }
    }

    public function editarCampeonato($id_campeonato, $titulo, $subtitulo, $endereco, $ativo, $dataCriacao, $tipo, $link_externo): bool
    {
        try {
            $dataMudanca = date("Y-m-d");
            $editar = $this->conexao->prepare("UPDATE campeonatos SET titulo = ?, subtitulo = ?, endereco = ?, ativo = ?, dataCriacao = ?, dataMudanca = ?, tipo = ?, link_externo = ? WHERE id_campeonato = ?");
            $editar->bind_param("ssssssssi", $titulo, $subtitulo, $endereco, $ativo, $dataCriacao, $dataMudanca, $tipo, $link_externo, $id_campeonato);
            $resultado = $editar->execute();
            $editar->close();
            return $resultado;
        } catch (Exception $ex) {
            error_log("Erro ao editar campeonato: " . $ex->getMessage());
            return false;
        }
    }

    public function excluirCampeonato($id_campeonato): bool
    {
        try {
            $deletar = $this->conexao->prepare("DELETE FROM campeonatos WHERE id_campeonato = ?");
            $deletar->bind_param("i", $id_campeonato);
            $resultado = $deletar->execute();
            $deletar->close();
            return $resultado;
        } catch (Exception $ex) {
            error_log("Erro ao excluir campeonato: " . $ex->getMessage());
            return false;
        }
    }

    public function buscarPorId($id_campeonato): ?array
    {
        try {
            $busca = $this->conexao->prepare("SELECT * FROM campeonatos WHERE id_campeonato = ?");
            $busca->bind_param("i", $id_campeonato);
            $busca->execute();
            $resultado = $busca->get_result()->fetch_assoc();
            $busca->close();
            return $resultado ? $resultado : null;
        } catch (Exception $ex) {
            error_log("Erro ao buscar campeonato por id: " . $ex->getMessage());
            return null;
        }
    }

    public function buscarTodos(): array
    {
        $campeonatos = [];
        $busca = "SELECT * FROM campeonatos ORDER BY dataCriacao DESC";
        $resultado = $this->conexao->query($busca);
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $campeonatos[] = $row;
            }
        }
        return $campeonatos;
    }
}
