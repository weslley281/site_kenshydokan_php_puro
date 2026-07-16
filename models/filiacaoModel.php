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

        // Criacao automatica da tabela e populacao de sementes se necessario
        if ($this->conexao) {
            $this->conexao->query("CREATE TABLE IF NOT EXISTS filiacoes (
                id_filiacao INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(255) NOT NULL,
                logo VARCHAR(255) DEFAULT NULL,
                link VARCHAR(255) DEFAULT NULL,
                status VARCHAR(50) NOT NULL DEFAULT 'ativo',
                dataCriacao DATE DEFAULT NULL,
                dataMudanca DATE DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            // Seeder automatico
            $check_empty = $this->conexao->query("SELECT id_filiacao FROM filiacoes LIMIT 1");
            if ($check_empty && $check_empty->num_rows == 0) {
                $default_filiacoes = [
                    ['Instituto Kenshydokan', 'logo_instituto.jpg', null],
                    ['Federação Mineira de Judô Kodokan', 'image13.png', 'https://fmjkodokan.com.br/'],
                    ['Federação Brasil Karate Full Contact', 'image12.png', 'http://seishinkyokushinsko.comunidades.net/representante-seishin-kyokushin-brasil'],
                    ['International Seishin Kyokushin Organization', 'image10.png', 'http://seishinkyokushinsko.comunidades.net/representante-seishin-kyokushin-brasil'],
                    ['World Association of Brasilian Ju Jitsu', 'image14.png', null],
                    ['WKA Muay thai e Thaiboxing', 'thaiboxing.jpeg', null],
                    ['South American Kickboxing Association', 'kickboxing.jpeg', null],
                    ['World Kyokushinkai Association', 'wka.jpeg', null]
                ];
                
                $stmt = $this->conexao->prepare("INSERT INTO filiacoes (nome, logo, link, status, dataCriacao) VALUES (?, ?, ?, 'ativo', CURDATE())");
                foreach ($default_filiacoes as $df) {
                    $stmt->bind_param("sss", $df[0], $df[1], $df[2]);
                    $stmt->execute();
                }
                $stmt->close();
            }
        }
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
            error_log("Erro ao criar filiacao: " . $e->getMessage());
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
            error_log("Erro ao editar filiacao: " . $e->getMessage());
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
            error_log("Erro ao excluir filiacao: " . $e->getMessage());
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
            error_log("Erro ao buscar filiacao por ID: " . $e->getMessage());
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
            error_log("Erro ao listar todas as filiacoes: " . $e->getMessage());
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
            error_log("Erro ao listar filiacoes ativas: " . $e->getMessage());
        }
        return $filiacoes;
    }
}
