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
            titulo VARCHAR(255) NOT NULL,
            conteudo TEXT,
            status VARCHAR(255) NOT NULL,
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
        `id_filiado` INT AUTO_INCREMENT PRIMARY KEY,
        `id_graduacao` INT,
        `nome` VARCHAR(255) NOT NULL,
        `dojo` VARCHAR(255) NOT NULL,
        `telefone` VARCHAR(255) NOT NULL,
        `rg` VARCHAR(255) NOT NULL,
        `email` VARCHAR(255) NOT NULL,
        `endereco` VARCHAR(255) NOT NULL,
        `cidade` VARCHAR(255) NOT NULL,
        `id_estado` INT,
        `confirmacao` VARCHAR(255) NOT NULL
        );
        ";

        if ($this->conexao->query($sql) === true) {
            echo "Tabela 'filiados' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela: " . $this->conexao->error;
        }
    }

    public function criarTabelaGraduacoes()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS graduacao (
            `id_graduacao` INT AUTO_INCREMENT PRIMARY KEY,
            `graduacao` VARCHAR(255) NOT NULL
        );
        ";

        if ($this->conexao->query($sql) === true) {
            echo "Tabela 'graduacao' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela: " . $this->conexao->error;
        }
    }

    public function criarTabelaGaleria()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS galeria (
            `id_galeria` INT AUTO_INCREMENT PRIMARY KEY,
            `nome` VARCHAR(255) NOT NULL
        );
        ";

        if ($this->conexao->query($sql) === true) {
            echo "Tabela 'galeria' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela: " . $this->conexao->error;
        }
    }

    public function criarTabelaFotos()
    {
        $sql = "

        CREATE TABLE IF NOT EXISTS fotos (
            `id_foto` INT AUTO_INCREMENT PRIMARY KEY,
            `id_galeria` INT NOT NULL,
            `nome` VARCHAR(255) NOT NULL,
            `foto` VARCHAR(255) NOT NULL,
            `dataUpload` DATE NOT NULL
        );
        ";

        if ($this->conexao->query($sql) === true) {
            echo "Tabela 'fotos' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela: " . $this->conexao->error;
        }
    }
}
