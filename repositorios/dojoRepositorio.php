<?php

require_once __DIR__ . '/../config.php';
include_once __DIR__ .  "/../models/dojoModel.php";

class DojoRepositorio {
    private $conexao;

    public function __construct() {
        require_once __DIR__ . "/../db/conexao.php";
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function listarDojos() {
        $query = "SELECT d.*, f.nome as nome_responsavel 
                  FROM dojos d 
                  LEFT JOIN filiados f ON d.id_filiado_responsavel = f.id_filiado";
        $resultado = $this->conexao->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function criarDojo(DojoModel $dojo) {
        $query = "INSERT INTO dojos (razao_social, nome_fantasia, cnpj, id_filiado_responsavel, telefone, celular, email, cep, endereco, cidade, estado, data_filiacao, status, imagem) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conexao->prepare($query);
        $razao_social = $dojo->getRazaoSocial();
        $nome_fantasia = $dojo->getNomeFantasia();
        $cnpj = $dojo->getCnpj();
        $id_filiado_responsavel = $dojo->getIdFiliadoResponsavel();
        $telefone = $dojo->getTelefone();
        $celular = $dojo->getCelular();
        $email = $dojo->getEmail();
        $cep = $dojo->getCep();
        $endereco = $dojo->getEndereco();
        $cidade = $dojo->getCidade();
        $estado = $dojo->getEstado();
        $data_filiacao = $dojo->getDataFiliacao();
        $status = $dojo->getStatus();
        $imagem = $dojo->getImagem();

        $stmt->bind_param(
            "sssissssssssss",
            $razao_social,
            $nome_fantasia,
            $cnpj,
            $id_filiado_responsavel,
            $telefone,
            $celular,
            $email,
            $cep,
            $endereco,
            $cidade,
            $estado,
            $data_filiacao,
            $status,
            $imagem
        );

        if ($stmt->execute()) {
            return true;
        } else {
            // Em um ambiente de produção, seria bom logar o erro: error_log($stmt->error);
            return false;
        }
    }

    public function editarDojo(DojoModel $dojo) {
        $query = "UPDATE dojos SET 
                    razao_social = ?, 
                    nome_fantasia = ?, 
                    cnpj = ?, 
                    id_filiado_responsavel = ?, 
                    telefone = ?, 
                    celular = ?, 
                    email = ?, 
                    cep = ?, 
                    endereco = ?, 
                    cidade = ?, 
                    estado = ?, 
                    data_filiacao = ?, 
                    status = ?, 
                    imagem = ? 
                  WHERE id = ?";
                  
        $stmt = $this->conexao->prepare($query);
        $razao_social = $dojo->getRazaoSocial();
        $nome_fantasia = $dojo->getNomeFantasia();
        $cnpj = $dojo->getCnpj();
        $id_filiado_responsavel = $dojo->getIdFiliadoResponsavel();
        $telefone = $dojo->getTelefone();
        $celular = $dojo->getCelular();
        $email = $dojo->getEmail();
        $cep = $dojo->getCep();
        $endereco = $dojo->getEndereco();
        $cidade = $dojo->getCidade();
        $estado = $dojo->getEstado();
        $data_filiacao = $dojo->getDataFiliacao();
        $status = $dojo->getStatus();
        $imagem = $dojo->getImagem();
        $id = $dojo->getId();

        $stmt->bind_param(
            "sssissssssssssi",
            $razao_social,
            $nome_fantasia,
            $cnpj,
            $id_filiado_responsavel,
            $telefone,
            $celular,
            $email,
            $cep,
            $endereco,
            $cidade,
            $estado,
            $data_filiacao,
            $status,
            $imagem,
            $id
        );

        return $stmt->execute();
    }

    public function excluirDojo($id) {
        $query = "DELETE FROM dojos WHERE id = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function buscarDojoPorId($id) {
        $query = "SELECT * FROM dojos WHERE id = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            $dojo = new DojoModel();
            $dojo->setId($row['id']);
            $dojo->setRazaoSocial($row['razao_social']);
            $dojo->setNomeFantasia($row['nome_fantasia']);
            $dojo->setCnpj($row['cnpj']);
            $dojo->setIdFiliadoResponsavel($row['id_filiado_responsavel']);
            $dojo->setTelefone($row['telefone']);
            $dojo->setCelular($row['celular']);
            $dojo->setEmail($row['email']);
            $dojo->setCep($row['cep']);
            $dojo->setEndereco($row['endereco']);
            $dojo->setCidade($row['cidade']);
            $dojo->setEstado($row['estado']);
            $dojo->setDataFiliacao($row['data_filiacao']);
            $dojo->setStatus($row['status']);
            $dojo->setImagem($row['imagem']);
            return $dojo;
        }
        return null;
    }
}
