<?php
include_once __DIR__ . "/../db/conexao.php";

class Evento
{
    private $id_evento;
    private $titulo;
    private $descricao;
    private $data_evento;
    private $tipo;
    private $endereco;
    private $link_assistir;
    private $conexao;

    public function __construct($id_evento = null, $titulo = null, $descricao = null, $data_evento = null, $tipo = null, $endereco = null, $link_assistir = null)
    {
        $this->id_evento = $id_evento;
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->data_evento = $data_evento;
        $this->tipo = $tipo;
        $this->endereco = $endereco;
        $this->link_assistir = $link_assistir;

        $c = new Conexao();
        $this->conexao = $c->conectar();

        // Criação automática da tabela se não existir
        if ($this->conexao) {
            $this->conexao->query("CREATE TABLE IF NOT EXISTS eventos (
                id_evento INT AUTO_INCREMENT PRIMARY KEY,
                titulo VARCHAR(255) NOT NULL,
                descricao TEXT,
                data_evento DATETIME NOT NULL,
                tipo ENUM('presencial', 'online') NOT NULL,
                endereco VARCHAR(255) DEFAULT NULL,
                link_assistir VARCHAR(500) DEFAULT NULL,
                data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
    }

    public function getIdEvento() { return $this->id_evento; }
    public function getTitulo() { return $this->titulo; }
    public function getDescricao() { return $this->descricao; }
    public function getDataEvento() { return $this->data_evento; }
    public function getTipo() { return $this->tipo; }
    public function getEndereco() { return $this->endereco; }
    public function getLinkAssistir() { return $this->link_assistir; }

    public function criarEvento(Evento $evento): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO eventos (titulo, descricao, data_evento, tipo, endereco, link_assistir) VALUES (?, ?, ?, ?, ?, ?)");
            $t = $evento->getTitulo();
            $d = $evento->getDescricao();
            $dt = $evento->getDataEvento();
            $tp = $evento->getTipo();
            $ed = $evento->getEndereco();
            $lk = $evento->getLinkAssistir();

            $inserir->bind_param("ssssss", $t, $d, $dt, $tp, $ed, $lk);
            $resultado = $inserir->execute();
            $inserir->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao criar evento: " . $e->getMessage());
            return false;
        }
    }

    public function editarEvento($id_evento, $titulo, $descricao, $data_evento, $tipo, $endereco, $link_assistir): bool
    {
        try {
            $editar = $this->conexao->prepare("UPDATE eventos SET titulo = ?, descricao = ?, data_evento = ?, tipo = ?, endereco = ?, link_assistir = ? WHERE id_evento = ?");
            $editar->bind_param("ssssssi", $titulo, $descricao, $data_evento, $tipo, $endereco, $link_assistir, $id_evento);
            $resultado = $editar->execute();
            $editar->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao editar evento: " . $e->getMessage());
            return false;
        }
    }

    public function excluirEvento($id_evento): bool
    {
        try {
            $deletar = $this->conexao->prepare("DELETE FROM eventos WHERE id_evento = ?");
            $deletar->bind_param("i", $id_evento);
            $resultado = $deletar->execute();
            $deletar->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao excluir evento: " . $e->getMessage());
            return false;
        }
    }

    public function buscarTodosEventos(): array
    {
        $eventos = [];
        try {
            $resultado = $this->conexao->query("SELECT * FROM eventos ORDER BY data_evento DESC");
            if ($resultado) {
                while ($row = $resultado->fetch_assoc()) {
                    $eventos[] = $row;
                }
            }
        } catch (Exception $e) {
            error_log("Erro ao buscar eventos: " . $e->getMessage());
        }
        return $eventos;
    }

    public function buscarEventosFuturos(): array
    {
        $eventos = [];
        try {
            $resultado = $this->conexao->query("SELECT * FROM eventos WHERE data_evento >= NOW() ORDER BY data_evento ASC");
            if ($resultado) {
                while ($row = $resultado->fetch_assoc()) {
                    $eventos[] = $row;
                }
            }
        } catch (Exception $e) {
            error_log("Erro ao buscar eventos futuros: " . $e->getMessage());
        }
        return $eventos;
    }

    public function buscarEventoPorId($id_evento): ?array
    {
        try {
            $procura = $this->conexao->prepare("SELECT * FROM eventos WHERE id_evento = ?");
            $procura->bind_param("i", $id_evento);
            $procura->execute();
            $result = $procura->get_result();
            $evento = $result->fetch_assoc();
            $procura->close();
            return $evento ? $evento : null;
        } catch (Exception $e) {
            error_log("Erro ao buscar evento por id: " . $e->getMessage());
            return null;
        }
    }
}
