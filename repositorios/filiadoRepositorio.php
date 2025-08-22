<?php
include_once "../db/conexao.php";
include_once "../models/filiadoModel.php";

class FiliadoRepositorio
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function criarFiliado(FiliadoModel $filiado): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO filiados (id_graduacao, nome, dojo, telefone, rg, email, endereco, cidade, id_estado, confirmacao, dataCriacao, dataMudanca) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $inserir->bind_param("isssssssisss", $filiado->getIdGraduacao(), $filiado->getNome(), $filiado->getDojo(), $filiado->getTelefone(), $filiado->getRg(), $filiado->getEmail(), $filiado->getEndereco(), $filiado->getCidade(), $filiado->getIdEstado(), $filiado->getConfirmacao(), $filiado->getDataCriacao(), $filiado->getDataMudanca());
            $resultado = $inserir->execute();
            $inserir->close();

            if (!$resultado) {
                throw new Exception("Erro ao criar o filiado.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao criar o filiado: " . $e->getMessage());
            return false;
        }
    }

    public function editarFiliado($id_filiado, FiliadoModel $filiado): bool
    {
        try {
            $editar = $this->conexao->prepare("UPDATE filiados SET id_graduacao = ?, nome = ?, dojo = ?, telefone = ?, rg = ?, email = ?, endereco = ?, cidade = ?, id_estado = ?, confirmacao = ?, dataMudanca = ? WHERE id_filiado = ?");
            $editar->bind_param("isssssssissi", $filiado->getIdGraduacao(), $filiado->getNome(), $filiado->getDojo(), $filiado->getTelefone(), $filiado->getRg(), $filiado->getEmail(), $filiado->getEndereco(), $filiado->getCidade(), $filiado->getIdEstado(), $filiado->getConfirmacao(), $filiado->getDataMudanca(), $id_filiado);
            $resultado = $editar->execute();
            $editar->close();

            if (!$resultado) {
                throw new Exception("Erro ao editar o filiado.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao editar o filiado: " . $e->getMessage());
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
            $busca = $this->conexao->prepare("SELECT * FROM filiados WHERE id_filiado = ?");
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
                $dados['id_graduacao'],
                $dados['nome'],
                $dados['dojo'],
                $dados['telefone'],
                $dados['rg'],
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
}
