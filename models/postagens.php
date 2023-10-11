<?php
include_once "conexao.php";

class Publicacao
{
    private $conexao;
    private $id_usuario;
    private $titulo;
    private $conteudo;
    private $status;
    private $dataCriacao;
    private $dataMudanca;

    public function __construct()
    {
        $conexaoDB = new Conexao();
        $this->conexao = $conexaoDB->conectar();
    }

    public function criar_publicacao(): bool
    {
        $inserir = $this->conexao->prepare("INSERT INTO postagens (id_usuario, titulo, conteudo, status, dataCriacao, dataMudanca) VALUES (?, ?, ?, ?, ?, ?)");
        $inserir->bind_param("issss", $this->id_usuario, $this->titulo, $this->conteudo, $this->status, $this->dataCriacao, $this->dataMudanca);
        $resultado = $inserir->execute();
        $inserir->close();

        return $resultado;
    }

    public function editar_publicacao(int $id_publicacao, string $titulo, string $conteudo, string $status, string $dataCriacao): bool
    {
        $atualizar = $this->conexao->prepare("UPDATE postagens SET titulo = ?, conteudo = ?, status = ?, dataMudanca = ? WHERE id_publicacao = ?");
        $atualizar->bind_param("ssssi", $this->titulo, $this->conteudo, $this->status, $this->dataMudanca, $this->id_publicacao);
        $resultado = $atualizar->execute();
        $atualizar->close();

        return $resultado;
    }

    public static function editar_status_publicacao(int $id_publicacao, string $status): bool
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $atualizar = $conexao->prepare("UPDATE postagens SET status = ? WHERE id_publicacao = ?");
        $atualizar->bind_param("si", $status, $id_publicacao);
        $resultado = $atualizar->execute();
        $atualizar->close();

        return $resultado;
    }

    public static function excluir_publicacao(int $id_publicacao): bool
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $excluir = $conexao->prepare("DELETE FROM postagens WHERE id_publicacao = ?");
        $excluir->bind_param("i", $id_publicacao);
        $resultado = $excluir->execute();
        $excluir->close();

        return $resultado;
    }

    public static function registrar_imagem_publicacao(string $nome, string $caminho): bool
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $inserir = $conexao->prepare("INSERT INTO imagens (nome, caminho) VALUES (?, ?)");
        $inserir->bind_param("ss", $nome, $caminho);
        $resultado = $inserir->execute();
        $inserir->close();

        return $resultado;
    }

    public static function buscar_nome_autor($id_usuario)
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $busca = "SELECT nome FROM usuarios WHERE id_usuario = ?";

        $procura = $conexao->prepare($busca);
        $procura->bind_param("i", $id_usuario);
        $procura->execute();
        $procura->bind_result($nome);

        $autor = null;

        while ($procura->fetch()) {
            $autor = $nome;
        }

        $procura->close();

        return $autor;
    }
}
