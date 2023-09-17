<?php
include_once("conexao.php");

class Publicacao
{
    private $conexao;

    public function __construct()
    {
        $conexaoDB = new Conexao();
        $this->conexao = $conexaoDB->conectar();
    }

    public function criar_publicacao(int $id_usuario, string $titulo, string $conteudo, string $status, string $data): bool
    {
        $inserir = $this->conexao->prepare("INSERT INTO publicacoes (id_usuario, titulo, conteudo, status, data) VALUES (?, ?, ?, ?, ?)");
        $inserir->bind_param("issss", $id_usuario, $titulo, $conteudo, $status, $data);
        $resultado = $inserir->execute();
        $inserir->close();

        return $resultado;
    }

    public function editar_publicacao(int $id_publicacao, string $titulo, string $conteudo, string $status, string $data): bool
    {
        $atualizar = $this->conexao->prepare("UPDATE publicacoes SET titulo = ?, conteudo = ?, status = ?, data = ? WHERE id_publicacao = ?");
        $atualizar->bind_param("ssssi", $titulo, $conteudo, $status, $data, $id_publicacao);
        $resultado = $atualizar->execute();
        $atualizar->close();

        return $resultado;
    }

    public function editar_status_publicacao(int $id_publicacao, string $status): bool
    {
        $atualizar = $this->conexao->prepare("UPDATE publicacoes SET status = ? WHERE id_publicacao = ?");
        $atualizar->bind_param("si", $status, $id_publicacao);
        $resultado = $atualizar->execute();
        $atualizar->close();

        return $resultado;
    }

    public function excluir_publicacao(int $id_publicacao): bool
    {
        $excluir = $this->conexao->prepare("DELETE FROM publicacoes WHERE id_publicacao = ?");
        $excluir->bind_param("i", $id_publicacao);
        $resultado = $excluir->execute();
        $excluir->close();

        return $resultado;
    }

    public function registrar_imagem_publicacao(string $nome, string $caminho): bool
    {
        $inserir = $this->conexao->prepare("INSERT INTO imagens (nome, caminho) VALUES (?, ?)");
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
