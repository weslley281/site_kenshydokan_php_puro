<?php
include_once __DIR__ .  "/../conexao.php";
include_once __DIR__ .  "/../models/dojoModel.php";

class DojoRepositorio
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function criarDojo(DojoModel $dojo): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO dojos (id_dojo, razao_social, nome_fantasia, cnpj, id_filiado_responsavel, telefone, celular, email, cep, endereco, cidade, estado, data_filiacao, status, imagem) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $inserir->bind_param("sssssssssssssssss", $dojo->getIdDojo(), $dojo->getRazaoSocial(), $dojo->getNomeFantasia(), $dojo->getCnpj(), $dojo->getIdFiliadoResponsavel(), $dojo->getTelefone(), $dojo->getCelular(), $dojo->getEmail(), $dojo->getCep(), $dojo->getEndereco(), $dojo->getCidade(), $dojo->getEstado(), $dojo->getDataFiliacao(), $dojo->getStatus(), $dojo->getImagem());
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
            $atualizar = $this->conexao->prepare("UPDATE dojos SET razao_social = ?, nome_fantasia = ?, cnpj = ?, id_filiado_responsavel = ?, telefone = ?, celular = ?, email = ?, cep = ?, endereco = ?, cidade = ?, estado = ?, data_filiacao = ?, status = ?, imagem = ? WHERE id_dojo = ?");
            $atualizar->bind_param("sssssssssssssssss", $dojo->getRazaoSocial(), $dojo->getNomeFantasia(), $dojo->getCnpj(), $dojo->getIdFiliadoResponsavel(), $dojo->getTelefone(), $dojo->getCelular(), $dojo->getEmail(), $dojo->getCep(), $dojo->getEndereco(), $dojo->getCidade(), $dojo->getEstado(), $dojo->getDataFiliacao(), $dojo->getStatus(), $dojo->getImagem(), $dojo->getIdDojo());
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
            $excluir = $this->conexao->prepare("DELETE FROM dojos WHERE id_dojo = ?");
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
                    $row['id_dojo'],
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
        $stmt = $conexao->prepare("SELECT * FROM dojos WHERE id_dojo = ?");
        $stmt->bind_param("s", $id_dojo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            return new DojoModel(
                $row['id_dojo'],
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
            return new DojoModel(
                $row['id_dojo'],
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
    
    public static function editarStatusDojo($id_dojo, $novo_status): bool
    {
        try {
            $c = new Conexao();
            $conexao = $c->conectar();
            $stmt = $conexao->prepare("UPDATE dojos SET status = ? WHERE id_dojo = ?");
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
            $stmt = $conexao->prepare("UPDATE dojos SET imagem = ? WHERE id_dojo = ?");
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