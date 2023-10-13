<?php
include_once "conexao.php";

class Migration
{
    private $conexao;

    public function __construct()
    {
        $conexaoDB = new Conexao();
        $this->conexao = $conexaoDB->conectar();
    }

    public function criarTabelaPublicacao()
    {
        $sql = "

        CREATE TABLE IF NOT EXISTS postagens (
            id_publicacao INT AUTO_INCREMENT PRIMARY KEY,
            id_usuario INT,
            titulo VARCHAR(255),
            conteudo TEXT,
            status VARCHAR(255),
            dataCriacao DATE,
            dataMudanca DATE
        );
        ";

        if ($this->conexao->query($sql) === true) {
            echo "Tabela 'postagens' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela: " . $this->conexao->error;
        }
    }

    public function criarTabelaFiliados()
    {
        $sql = "

        CREATE TABLE IF NOT EXISTS filiados (
        `id_filiado` int(11) NOT NULL,
        `id_graduacao` int(11) NOT NULL,
        `nome` varchar(100) NOT NULL,
        `dojo` varchar(100) DEFAULT NULL,
        `telefone` varchar(100) DEFAULT NULL,
        `rg` varchar(100) DEFAULT NULL,
        `email` varchar(100) DEFAULT NULL,
        `endereco` varchar(100) DEFAULT NULL,
        `cidade` varchar(100) DEFAULT NULL,
        `id_estado` int(11) NOT NULL,
        `confirmacao` varchar(3) DEFAULT NULL
        );
        ";

        if ($this->conexao->query($sql) === true) {
            echo "Tabela 'filiados' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela: " . $this->conexao->error;
        }
    }
}
