<?php
include_once __DIR__ . "/../db/conexao.php";

class DojoModel
{
    private $id_dojo;
    private $razao_social;
    private $nome_fantasia;
    private $cnpj;
    private $id_filiado_responsavel;
    private $telefone;
    private $celular;
    private $email;
    private $cep;
    private $endereco;
    private $cidade;
    private $estado;
    private $data_filiacao;
    private $status;
    private $imagem;
    private $conexao;

    public function __construct(
        $id_dojo = null,
        $razao_social = null,
        $nome_fantasia = null,
        $cnpj = null,
        $id_filiado_responsavel = null,
        $telefone = null,
        $celular = null,
        $email = null,
        $cep = null,
        $endereco = null,
        $cidade = null,
        $estado = null,
        $data_filiacao = null,
        $status = null,
        $imagem = null
    ) {
        $this->id_dojo = $id_dojo;
        $this->razao_social = $razao_social;
        $this->nome_fantasia = $nome_fantasia;
        $this->cnpj = $cnpj;
        $this->id_filiado_responsavel = $id_filiado_responsavel;
        $this->telefone = $telefone;
        $this->celular = $celular;
        $this->email = $email;
        $this->cep = $cep;
        $this->endereco = $endereco;
        $this->cidade = $cidade;
        $this->estado = $estado;
        $this->data_filiacao = $data_filiacao;
        $this->status = $status;
        $this->imagem = $imagem;

        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    // Getters
    public function getIdDojo()
    {
        return $this->id_dojo;
    }

    public function getRazaoSocial()
    {
        return $this->razao_social;
    }

    public function getNomeFantasia()
    {
        return $this->nome_fantasia;
    }

    public function getCnpj()
    {
        return $this->cnpj;
    }

    public function getIdFiliadoResponsavel()
    {
        return $this->id_filiado_responsavel;
    }

    public function getTelefone()
    {
        return $this->telefone;
    }

    public function getCelular()
    {
        return $this->celular;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getCep()
    {
        return $this->cep;
    }

    public function getEndereco()
    {
        return $this->endereco;
    }

    public function getCidade()
    {
        return $this->cidade;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getDataFiliacao()
    {
        return $this->data_filiacao;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getImagem()
    {
        return $this->imagem;
    }

    // Setters
    public function setIdDojo($id_dojo)
    {
        $this->id_dojo = $id_dojo;
    }

    public function setRazaoSocial($razao_social)
    {
        $this->razao_social = $razao_social;
    }

    public function setNomeFantasia($nome_fantasia)
    {
        $this->nome_fantasia = $nome_fantasia;
    }

    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;
    }

    public function setIdFiliadoResponsavel($id_filiado_responsavel)
    {
        $this->id_filiado_responsavel = $id_filiado_responsavel;
    }

    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    public function setCelular($celular)
    {
        $this->celular = $celular;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setCep($cep)
    {
        $this->cep = $cep;
    }

    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
    }

    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function setDataFiliacao($data_filiacao)
    {
        $this->data_filiacao = $data_filiacao;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
    }

    // Métodos vindos do Repositório

    public function criarDojo(DojoModel $dojo): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO dojos (id, razao_social, nome_fantasia, cnpj, id_filiado_responsavel, telefone, celular, email, cep, endereco, cidade, estado, data_filiacao, status, imagem) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $id = $dojo->getIdDojo();
            $rs = $dojo->getRazaoSocial();
            $nf = $dojo->getNomeFantasia();
            $cnpj = $dojo->getCnpj();
            $ifr = $dojo->getIdFiliadoResponsavel();
            $tel = $dojo->getTelefone();
            $cel = $dojo->getCelular();
            $email = $dojo->getEmail();
            $cep = $dojo->getCep();
            $end = $dojo->getEndereco();
            $cid = $dojo->getCidade();
            $est = $dojo->getEstado();
            $df = $dojo->getDataFiliacao();
            $st = $dojo->getStatus();
            $img = $dojo->getImagem();

            $inserir->bind_param("sssssssssssssss", $id, $rs, $nf, $cnpj, $ifr, $tel, $cel, $email, $cep, $end, $cid, $est, $df, $st, $img);
            $resultado = $inserir->execute();
            $inserir->close();

            if (!$resultado) {
                throw new Exception("Erro ao criar o dojo.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao criar o dojo: " . $e->getMessage());
            return false;
        }
    }

    public function editarDojo(DojoModel $dojo): bool
    {
        try {
            $atualizar = $this->conexao->prepare("UPDATE dojos SET razao_social = ?, nome_fantasia = ?, cnpj = ?, id_filiado_responsavel = ?, telefone = ?, celular = ?, email = ?, cep = ?, endereco = ?, cidade = ?, estado = ?, data_filiacao = ?, status = ?, imagem = ? WHERE id = ?");
            
            $rs = $dojo->getRazaoSocial();
            $nf = $dojo->getNomeFantasia();
            $cnpj = $dojo->getCnpj();
            $ifr = $dojo->getIdFiliadoResponsavel();
            $tel = $dojo->getTelefone();
            $cel = $dojo->getCelular();
            $email = $dojo->getEmail();
            $cep = $dojo->getCep();
            $end = $dojo->getEndereco();
            $cid = $dojo->getCidade();
            $est = $dojo->getEstado();
            $df = $dojo->getDataFiliacao();
            $st = $dojo->getStatus();
            $img = $dojo->getImagem();
            $id = $dojo->getIdDojo();

            $atualizar->bind_param("sssssssssssssss", $rs, $nf, $cnpj, $ifr, $tel, $cel, $email, $cep, $end, $cid, $est, $df, $st, $img, $id);
            $resultado = $atualizar->execute();
            $atualizar->close();

            if (!$resultado) {
                throw new Exception("Erro ao editar o dojo.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao editar o dojo: " . $e->getMessage());
            return false;
        }
    }

    public function excluirDojo($id_dojo): bool
    {
        try {
            $excluir = $this->conexao->prepare("DELETE FROM dojos WHERE id = ?");
            $excluir->bind_param("s", $id_dojo);
            $resultado = $excluir->execute();
            $excluir->close();

            if (!$resultado) {
                throw new Exception("Erro ao excluir o dojo.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao excluir o dojo: " . $e->getMessage());
            return false;
        }
    }

    public function listarDojos(): array
    {
        $dojos = [];
        $resultado = $this->conexao->query("SELECT * FROM dojos");

        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $dojo = new DojoModel(
                    $row['id'],
                    $row['razao_social'],
                    $row['nome_fantasia'],
                    $row['cnpj'],
                    $row['id_filiado_responsavel'],
                    $row['telefone'],
                    $row['celular'],
                    $row['email'],
                    $row['cep'],
                    $row['endereco'],
                    $row['cidade'],
                    $row['estado'],
                    $row['data_filiacao'],
                    $row['status'],
                    $row['imagem']
                );
                $dojos[] = $dojo;
            }
            $resultado->free();
        } else {
            error_log("Erro ao listar dojos: " . $this->conexao->error);
        }

        return $dojos;
    }

    public static function getDojoById($id_dojo): ?DojoModel
    {
        $c = new Conexao();
        $conexao = $c->conectar();
        $stmt = $conexao->prepare("SELECT * FROM dojos WHERE id = ?");
        $stmt->bind_param("s", $id_dojo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            return new DojoModel(
                $row['id'],
                $row['razao_social'],
                $row['nome_fantasia'],
                $row['cnpj'],
                $row['id_filiado_responsavel'],
                $row['telefone'],
                $row['celular'],
                $row['email'],
                $row['cep'],
                $row['endereco'],
                $row['cidade'],
                $row['estado'],
                $row['data_filiacao'],
                $row['status'],
                $row['imagem']
            );
        }

        return null;
    }

    public static function getDojoByCnpj($cnpj): ?DojoModel
    {
        $c = new Conexao();
        $conexao = $c->conectar();
        $stmt = $conexao->prepare("SELECT * FROM dojos WHERE cnpj = ?");
        $stmt->bind_param("s", $cnpj);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            $stmt->close();
            return new DojoModel(
                $row['id'],
                $row['razao_social'],
                $row['nome_fantasia'],
                $row['cnpj'],
                $row['id_filiado_responsavel'],
                $row['telefone'],
                $row['celular'],
                $row['email'],
                $row['cep'],
                $row['endereco'],
                $row['cidade'],
                $row['estado'],
                $row['data_filiacao'],
                $row['status'],
                $row['imagem']
            );
        }

        $stmt->close();
        return null;
    }

    public static function editarStatusDojo($id_dojo, $novo_status): bool
    {
        try {
            $c = new Conexao();
            $conexao = $c->conectar();
            $stmt = $conexao->prepare("UPDATE dojos SET status = ? WHERE id = ?");
            $stmt->bind_param("ss", $novo_status, $id_dojo);
            $resultado = $stmt->execute();
            $stmt->close();

            if (!$resultado) {
                throw new Exception("Erro ao atualizar o status do dojo.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao atualizar o status do dojo: " . $e->getMessage());
            return false;
        }
    }

    public static function editarImagemDojo($id_dojo, $nova_imagem): bool
    {
        try {
            $c = new Conexao();
            $conexao = $c->conectar();
            $stmt = $conexao->prepare("UPDATE dojos SET imagem = ? WHERE id = ?");
            $stmt->bind_param("ss", $nova_imagem, $id_dojo);
            $resultado = $stmt->execute();
            $stmt->close();

            if (!$resultado) {
                throw new Exception("Erro ao atualizar a imagem do dojo.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao atualizar a imagem do dojo: " . $e->getMessage());
            return false;
        }
    }
}
