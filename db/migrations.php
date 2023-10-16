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

    public function criarTabelaUsuarios()
    {
        $sql = "

        CREATE TABLE IF NOT EXISTS usuarios (
            `id_usuario` int(11) NOT NULL,
            `id_fil` int(11) DEFAULT NULL,
            `id_imagem` int(11) DEFAULT NULL,
            `nome` VARCHAR(255) NOT NULL,
            `email` VARCHAR(255) NOT NULL,
            `nivel` VARCHAR(255) NOT NULL,
            `telefone` VARCHAR(255) NOT NULL,
            `senha` VARCHAR(300) NOT NULL
        );
        ";

        if ($this->conexao->query($sql) === true) {
            //echo "Tabela 'usuarios' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de usuarios: " . $this->conexao->error;
        }
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
            //echo "Tabela 'postagens' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de postagens: " . $this->conexao->error;
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
        `endereco` VARCHAR(255),
        `cidade` VARCHAR(255),
        `id_estado` INT,
        `confirmacao` VARCHAR(255) NOT NULL,
        `dataCriacao` DATE,
        `dataMudanca` DATE
        );
        ";

        if ($this->conexao->query($sql) === true) {
            //echo "Tabela 'filiados' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de filiados: " . $this->conexao->error;
        }
    }

    public function criarTabelaGraduacoes()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS graduacao (
            `id_graduacao` INT AUTO_INCREMENT PRIMARY KEY,
            `graduacao` VARCHAR(255) NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        );
        ";

        if ($this->conexao->query($sql) === true) {
            //echo "Tabela 'graduacao' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de graduação: " . $this->conexao->error;
        }
    }

    public function criarTabelaGaleria()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS galeria (
            `id_galeria` INT AUTO_INCREMENT PRIMARY KEY,
            `nome` VARCHAR(255) NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        );
        ";

        if ($this->conexao->query($sql) === true) {
            //echo "Tabela 'galeria' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de galeria: " . $this->conexao->error;
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
            `dataUpload` DATE NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        );
        ";

        if ($this->conexao->query($sql) === true) {
            //echo "Tabela 'fotos' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de fotos: " . $this->conexao->error;
        }
    }

    public function criarTabelaCampeonatos()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS campeonatos (
            `id_campeonato` INT AUTO_INCREMENT PRIMARY KEY,
            `titulo` VARCHAR(255) NOT NULL,
            `subtitulo` VARCHAR(255) NOT NULL,
            `endereco` VARCHAR(255) NOT NULL,
            `ativo` VARCHAR(255) NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        );
        ";

        if ($this->conexao->query($sql) === true) {
            //echo "Tabela 'campeonatos' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de campeonatos: " . $this->conexao->error;
        }
    }

    public function criarTabelaImagens()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS imagens (
            `id_imagem` INT AUTO_INCREMENT PRIMARY KEY,
            `nome` VARCHAR(255) NOT NULL,
            `caminho` VARCHAR(255) NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        );
        ";

        if ($this->conexao->query($sql) === true) {
            //echo "Tabela 'imagens' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de imagens: " . $this->conexao->error;
        }
    }

    public function criarTabelaCursos()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS cursos (
            `id_curso` INT AUTO_INCREMENT PRIMARY KEY,
            `id_categoria` INT,
            `nome` VARCHAR(255) NOT NULL,
            `descricao` TEXT NOT NULL,
            `professor` VARCHAR(255) NOT NULL,
            `id_imagem` VARCHAR(255) NOT NULL,
            `situacao` VARCHAR(255) NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        );
        ";

        if ($this->conexao->query($sql) === true) {
            //echo "Tabela 'cursos' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de cursos: " . $this->conexao->error;
        }
    }

    public function criarTabelaAulas()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS aulas (
            `id_aula` INT AUTO_INCREMENT PRIMARY KEY,
            `id_curso` INT,
            `titulo` VARCHAR(255) NOT NULL,
            `link` VARCHAR(255) NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        );
        ";

        if ($this->conexao->query($sql) === true) {
            //echo "Tabela 'aulas' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de aulas: " . $this->conexao->error;
        }
    }

    public function criarTabelaCategorias()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS categorias (
            `id_categoria` INT AUTO_INCREMENT PRIMARY KEY,
            `categoria`  VARCHAR(255) NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        );
        ";

        if ($this->conexao->query($sql) === true) {
            //echo "Tabela 'categorias' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de categorias: " . $this->conexao->error;
        }
    }
}
