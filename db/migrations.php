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
        )";

        if ($this->conexao->query($sql) === true) {
            echo "Tabela 'postagens' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela: " . $this->conexao->error;
        }
    }
}
