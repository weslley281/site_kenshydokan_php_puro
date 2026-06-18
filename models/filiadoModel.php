<?php
include_once __DIR__ . "/../db/conexao.php";

class FiliadoModel
{
    private $id_filiado;
    private $codigo;
    private $id_graduacao;
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
        return $this->id_graduacao;
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
            $inserir = $this->conexao->prepare("INSERT INTO filiados (codigo, id_graduacao, nome, dojo, telefone, dataNascimento, email, endereco, cidade, id_estado, confirmacao, dataCriacao, dataMudanca) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $codigo = $filiado->getCodigo();
            $ig = $filiado->getIdGraduacao();
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

            $inserir->bind_param("iisssssssisss", $codigo, $ig, $nome, $dojo, $tel, $dn, $email, $end, $cid, $ie, $conf, $dc, $dm);
            $resultado = $inserir->execute();
            $inserir->close();

            if (!$resultado) {
                throw new Exception("Erro ao criar o filiado.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao criar o filiado: " . $e->getMessage() . " - " . $this->conexao->error);
            return false;
        }
    }

    public function editarFiliado($id_filiado, FiliadoModel $filiado): bool
    {
        try {
            $editar = $this->conexao->prepare("UPDATE filiados SET codigo = ?, id_graduacao = ?, nome = ?, dojo = ?, telefone = ?, dataNascimento = ?, email = ?, endereco = ?, cidade = ?, id_estado = ?, confirmacao = ?, dataMudanca = ? WHERE id_filiado = ?");
            
            $codigo = $filiado->getCodigo();
            $ig = $filiado->getIdGraduacao();
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

            $editar->bind_param("iisssssssissi", $codigo, $ig, $nome, $dojo, $tel, $dn, $email, $end, $cid, $ie, $conf, $dm, $id_filiado);
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
            $busca = $this->conexao->prepare("SELECT codigo, id_filiado, id_graduacao, nome, dojo, telefone, dataNascimento, email, endereco, cidade, id_estado, confirmacao, dataMudanca FROM filiados WHERE id_filiado = ?");
            $busca->bind_param("i", $id_filiado);
            $busca->execute();
            $result = $busca->get_result();

            if ($result->num_rows === 0) {
                return null;
            }

            $dados = $result->fetch_assoc();
            $busca->close();

            return new FiliadoModel(
                $dados['id_filiado'],
                $dados['codigo'],
                $dados['id_graduacao'],
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
        $query = "SELECT * FROM filiados WHERE confirmacao = 'sim' ORDER BY id_filiado ASC";
        $resultado = $this->conexao->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
}
