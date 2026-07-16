<?php
include_once __DIR__ . "/../db/conexao.php";

class FiliadoModel
{
    private $id_filiado;
    private $codigo;
    private $id_graduacao; // Mantido para compatibilidade em memória
    private $nome;
    private $dojo;
    private $telefone;
    private $dataNascimento;
    private $email;
    private $endereco;
    private $cidade;
    private $id_estado;
    private $confirmacao;
    private $dataCriacao;
    private $dataMudanca;
    private $conexao;

    public function __construct(
        $id_filiado = null,
        $codigo = null,
        $id_graduacao = null,
        $nome = null,
        $dojo = null,
        $telefone = null,
        $dataNascimento = null,
        $email = null,
        $endereco = null,
        $cidade = null,
        $id_estado = null,
        $confirmacao = null,
        $dataMudanca = null
    ) {
        $this->id_filiado = $id_filiado;
        $this->codigo = $codigo;
        $this->id_graduacao = $id_graduacao;
        $this->nome = $nome;
        $this->dojo = $dojo;
        $this->telefone = $telefone;
        $this->dataNascimento = $dataNascimento;
        $this->email = $email;
        $this->endereco = $endereco;
        $this->cidade = $cidade;
        $this->id_estado = $id_estado;
        $this->confirmacao = $confirmacao;
        $this->dataCriacao = date("Y-m-d");
        $this->dataMudanca = $dataMudanca;

        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function getIdFiliado()
    {
        return $this->id_filiado;
    }

    public function setIdFiliado($id_filiado)
    {
        $this->id_filiado = $id_filiado;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function getIdGraduacao()
    {
        // Se já temos a propriedade instanciada em memória, retorna ela
        if ($this->id_graduacao !== null && $this->id_graduacao != 0) {
            return $this->id_graduacao;
        }
        
        // Caso contrário, busca a graduação da arte padrão (ID 1 - Karatê Kenshydokan) ou qualquer outra
        if ($this->id_filiado) {
            $query = "
                SELECT id_graduacao FROM filiados_graduacoes 
                WHERE id_filiado = ? 
                ORDER BY CASE WHEN id_arte = 1 THEN 0 ELSE 1 END ASC 
                LIMIT 1
            ";
            try {
                $stmt = $this->conexao->prepare($query);
                $stmt->bind_param("i", $this->id_filiado);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $this->id_graduacao = intval($row['id_graduacao']);
                    $stmt->close();
                    return $this->id_graduacao;
                }
                $stmt->close();
            } catch (Exception $e) {
                error_log("Erro ao buscar id_graduacao em FiliadoModel: " . $e->getMessage());
            }
        }
        return 0;
    }

    public function setIdGraduacao($id_graduacao)
    {
        $this->id_graduacao = $id_graduacao;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getDojo()
    {
        return $this->dojo;
    }

    public function setDojo($dojo)
    {
        $this->dojo = $dojo;
    }

    public function getTelefone()
    {
        return $this->telefone;
    }

    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    public function getDataNascimento()
    {
        return $this->dataNascimento;
    }

    public function setDataNascimento($data_nascimento)
    {
        $this->dataNascimento = $data_nascimento;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEndereco()
    {
        return $this->endereco;
    }

    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
    }

    public function getCidade()
    {
        return $this->cidade;
    }

    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }

    public function getIdEstado()
    {
        return $this->id_estado;
    }

    public function setIdEstado($id_estado)
    {
        $this->id_estado = $id_estado;
    }

    public function getConfirmacao()
    {
        return $this->confirmacao;
    }

    public function setConfirmacao($confirmacao)
    {
        $this->confirmacao = $confirmacao;
    }

    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }

    public function setDataCriacao($dataCriacao)
    {
        $this->dataCriacao = $dataCriacao;
    }

    public function getDataMudanca()
    {
        return $this->dataMudanca;
    }

    public function setDataMudanca($dataMudanca)
    {
        $this->dataMudanca = $dataMudanca;
    }

    // Métodos vindos do Repositório

    public function criarFiliado(FiliadoModel $filiado): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO filiados (codigo, nome, dojo, telefone, dataNascimento, email, endereco, cidade, id_estado, confirmacao, dataCriacao, dataMudanca) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $codigo = $filiado->getCodigo();
            $nome = $filiado->getNome();
            $dojo = $filiado->getDojo();
            $tel = $filiado->getTelefone();
            $dn = $filiado->getDataNascimento();
            $email = $filiado->getEmail();
            $end = $filiado->getEndereco();
            $cid = $filiado->getCidade();
            $ie = $filiado->getIdEstado();
            $conf = $filiado->getConfirmacao();
            $dc = $filiado->getDataCriacao();
            $dm = $filiado->getDataMudanca();

            $inserir->bind_param("isssssssisss", $codigo, $nome, $dojo, $tel, $dn, $email, $end, $cid, $ie, $conf, $dc, $dm);
            $resultado = $inserir->execute();
            $inserir->close();

            if (!$resultado) {
                throw new Exception("Erro ao criar o filiado.");
            }

            // Define o ID gerado no próprio objeto
            $filiado->setIdFiliado($this->conexao->insert_id);

            return true;
        } catch (Exception $e) {
            error_log("Erro ao criar o filiado: " . $e->getMessage() . " - " . $this->conexao->error);
            return false;
        }
    }

    public function editarFiliado($id_filiado, FiliadoModel $filiado): bool
    {
        try {
            $editar = $this->conexao->prepare("UPDATE filiados SET codigo = ?, nome = ?, dojo = ?, telefone = ?, dataNascimento = ?, email = ?, endereco = ?, cidade = ?, id_estado = ?, confirmacao = ?, dataMudanca = ? WHERE id_filiado = ?");
            
            $codigo = $filiado->getCodigo();
            $nome = $filiado->getNome();
            $dojo = $filiado->getDojo();
            $tel = $filiado->getTelefone();
            $dn = $filiado->getDataNascimento();
            $email = $filiado->getEmail();
            $end = $filiado->getEndereco();
            $cid = $filiado->getCidade();
            $ie = $filiado->getIdEstado();
            $conf = $filiado->getConfirmacao();
            $dm = $filiado->getDataMudanca();

            $editar->bind_param("isssssssissi", $codigo, $nome, $dojo, $tel, $dn, $email, $end, $cid, $ie, $conf, $dm, $id_filiado);
            $resultado = $editar->execute();
            $editar->close();

            if (!$resultado) {
                throw new Exception("Erro ao editar o filiado.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao editar o filiado: " . $e->getMessage() . " - " . $this->conexao->error);
            return false;
        }
    }

    public function excluirFiliado($id_filiado): bool
    {
        try {
            $deletar = $this->conexao->prepare("DELETE FROM filiados WHERE id_filiado = ?");
            $deletar->bind_param("i", $id_filiado);
            $resultado = $deletar->execute();
            $deletar->close();

            if (!$resultado) {
                throw new Exception("Erro ao excluir o filiado.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao excluir o filiado: " . $e->getMessage());
            return false;
        }
    }

    public function buscarFiliadoPorId($id_filiado)
    {
        try {
            $busca = $this->conexao->prepare("SELECT codigo, id_filiado, nome, dojo, telefone, dataNascimento, email, endereco, cidade, id_estado, confirmacao, dataMudanca FROM filiados WHERE id_filiado = ?");
            $busca->bind_param("i", $id_filiado);
            $busca->execute();
            $result = $busca->get_result();

            if ($result->num_rows === 0) {
                return null;
            }

            $dados = $result->fetch_assoc();
            $busca->close();

            // Buscar a graduação padrão para compatibilidade em memória
            $id_graduacao = 0;
            $gradQuery = $this->conexao->prepare("
                SELECT id_graduacao FROM filiados_graduacoes 
                WHERE id_filiado = ? 
                ORDER BY CASE WHEN id_arte = 1 THEN 0 ELSE 1 END ASC 
                LIMIT 1
            ");
            $gradQuery->bind_param("i", $id_filiado);
            $gradQuery->execute();
            $gradResult = $gradQuery->get_result();
            if ($gradResult->num_rows > 0) {
                $gradRow = $gradResult->fetch_assoc();
                $id_graduacao = intval($gradRow['id_graduacao']);
            }
            $gradQuery->close();

            return new FiliadoModel(
                $dados['id_filiado'],
                $dados['codigo'],
                $id_graduacao,
                $dados['nome'],
                $dados['dojo'],
                $dados['telefone'],
                $dados['dataNascimento'],
                $dados['email'],
                $dados['endereco'],
                $dados['cidade'],
                $dados['id_estado'],
                $dados['confirmacao'],
                $dados['dataMudanca']
            );
        } catch (Exception $e) {
            error_log("Erro ao buscar o filiado por ID: " . $e->getMessage());
            return null;
        }
    }

    public function listarFiliados()
    {
        $query = "SELECT id_filiado, nome FROM filiados ORDER BY nome ASC";
        $resultado = $this->conexao->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function listarFiliadosAtivos()
    {
        $query = "
            SELECT f.*, 
                   COALESCE(
                       (SELECT id_graduacao FROM filiados_graduacoes WHERE id_filiado = f.id_filiado AND id_arte = 1 LIMIT 1), 
                       (SELECT id_graduacao FROM filiados_graduacoes WHERE id_filiado = f.id_filiado LIMIT 1),
                       0
                   ) AS id_graduacao
            FROM filiados f 
            WHERE f.confirmacao = 'sim' 
            ORDER BY f.id_filiado ASC
        ";
        $resultado = $this->conexao->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function listarTodosFiliadosCompleto()
    {
        $query = "
            SELECT f.*, 
                   COALESCE(
                       (SELECT id_graduacao FROM filiados_graduacoes WHERE id_filiado = f.id_filiado AND id_arte = 1 LIMIT 1), 
                       (SELECT id_graduacao FROM filiados_graduacoes WHERE id_filiado = f.id_filiado LIMIT 1),
                       0
                   ) AS id_graduacao
            FROM filiados f 
            ORDER BY f.id_filiado ASC
        ";
        $resultado = $this->conexao->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Novos Métodos para Múltiplas Graduações

    public function salvarGraduacoes($id_filiado, array $graduacoes): bool
    {
        try {
            // 1. Remover graduacoes existentes para este filiado
            $deletar = $this->conexao->prepare("DELETE FROM filiados_graduacoes WHERE id_filiado = ?");
            $deletar->bind_param("i", $id_filiado);
            $deletar->execute();
            $deletar->close();

            // 2. Inserir as novas graduacoes
            if (!empty($graduacoes)) {
                $inserir = $this->conexao->prepare("
                    INSERT INTO filiados_graduacoes (id_filiado, id_arte, id_graduacao) 
                    VALUES (?, ?, ?)
                ");
                
                foreach ($graduacoes as $g) {
                    $id_arte = intval($g['id_arte']);
                    $id_graduacao = intval($g['id_graduacao']);
                    
                    if ($id_arte > 0 && $id_graduacao > 0) {
                        $inserir->bind_param("iii", $id_filiado, $id_arte, $id_graduacao);
                        $inserir->execute();
                    }
                }
                $inserir->close();
            }
            return true;
        } catch (Exception $e) {
            error_log("Erro ao salvar graduações do filiado: " . $e->getMessage());
            return false;
        }
    }

    public function buscarGraduacoesFiliado($id_filiado): array
    {
        $dados = [];
        $query = "
            SELECT fg.id_arte, fg.id_graduacao, am.nome AS arte_nome, g.graduacao AS graduacao_nome
            FROM filiados_graduacoes fg
            JOIN artes_marciais am ON fg.id_arte = am.id_arte
            JOIN graduacoes g ON fg.id_graduacao = g.id_graduacao
            WHERE fg.id_filiado = ?
            ORDER BY am.nome ASC
        ";
        try {
            $stmt = $this->conexao->prepare($query);
            $stmt->bind_param("i", $id_filiado);
            $stmt->execute();
            $result = $stmt->get_result();
            $dados = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } catch (Exception $e) {
            error_log("Erro ao buscar graduações do filiado: " . $e->getMessage());
        }
        return $dados;
    }

    public function listarFiliadosPorArte($id_arte): array
    {
        $query = "
            SELECT f.id_filiado, f.nome, f.dojo, g.graduacao AS graduacao_nome
            FROM filiados f
            INNER JOIN filiados_graduacoes fg ON f.id_filiado = fg.id_filiado
            INNER JOIN graduacoes g ON fg.id_graduacao = g.id_graduacao
            WHERE fg.id_arte = ? AND f.confirmacao = 'sim'
            ORDER BY f.nome ASC
        ";
        try {
            $stmt = $this->conexao->prepare($query);
            $stmt->bind_param("i", $id_arte);
            $stmt->execute();
            $result = $stmt->get_result();
            $dados = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $dados;
        } catch (Exception $e) {
            error_log("Erro ao listar filiados por arte: " . $e->getMessage());
            return [];
        }
    }
}
