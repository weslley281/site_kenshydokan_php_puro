<?php
include_once "../models/usuarioModel.php";
include_once "../db/conexao.php";

class UsuarioRepositorio
{
    private $conexao;

    public function __construct()
    {
        $conexaoDB = new Conexao();
        $this->conexao = $conexaoDB->conectar();
    }

    public function criarUsuario(Usuario $usuario): bool
    {
        $inserir = $this->conexao->prepare("INSERT INTO usuarios (nome, id_fil, id_imagem, email, nivel, telefone, senha, dataCriacao, dataMudanca) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $inserir->bind_param("siissssss", $usuario->getNome(), $usuario->getIdFil(), $usuario->getIdImagem(), $usuario->getEmail(), $usuario->getNivel(), $usuario->getTelefone(), $usuario->getSenha(), $usuario->getDataCriacao(), $usuario->getDataMudanca());

        $resultado = $inserir->execute();
        $inserir->close();

        return $resultado;
    }

    public function editarUsuario(int $id_usuario, Usuario $usuario): bool
    {
        $atualizar = $this->conexao->prepare("UPDATE usuarios SET nome = ?, id_fil = ?, id_imagem = ?, email = ?, nivel = ?, telefone = ?, dataMudanca = ? WHERE id_usuario = ?");
        var_dump($atualizar);
        $atualizar->bind_param("siissssi", $usuario->getNome(), $usuario->getIdFil(), $usuario->getIdImagem(), $usuario->getEmail(), $usuario->getNivel(), $usuario->getTelefone(), $usuario->getDataMudanca(), $id_usuario);
        $resultado = $atualizar->execute();
        $atualizar->close();

        return $resultado;
    }

    public static function editarImagemUsuario(int $id_usuario, $id_imagem, $dataMudanca): bool
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $atualizar = $conexao->prepare("UPDATE usuarios SET id_imagem = ?, dataMudanca = ? WHERE id_usuario = ?");
        $atualizar->bind_param("isi", $id_imagem, $dataMudanca, $id_usuario);
        $resultado = $atualizar->execute();
        $atualizar->close();

        return $resultado;
    }

    public static function editarNivelUsuario(int $id_usuario, string $nivel, string $dataMudanca): bool
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $atualizar = $conexao->prepare("UPDATE usuarios SET nivel = ?, dataMudanca = ? WHERE id_usuario = ?");
        $atualizar->bind_param("ssi", $nivel, $dataMudanca, $id_usuario);
        $resultado = $atualizar->execute();
        $atualizar->close();

        return $resultado;
    }

    public static function editarTokenUsuario(int $id_usuario, string $token): bool
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $atualizar = $conexao->prepare("UPDATE usuarios SET token = ? WHERE id_usuario = ?");
        $atualizar->bind_param("si", $token, $id_usuario);
        $resultado = $atualizar->execute();
        $atualizar->close();

        return $resultado;
    }

    public function editarSenhaUsuario(int $id_usuario, string $nova_senha): bool
    {
        $atualizar = $this->conexao->prepare("UPDATE usuarios SET senha = ? WHERE id_usuario = ?");
        $atualizar->bind_param("si", $nova_senha, $id_usuario);

        $resultado = $atualizar->execute();
        $atualizar->close();

        return $resultado;
    }



    public static function excluirUsuario(int $id_usuario): bool
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $excluir = $conexao->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $excluir->bind_param("i", $id_usuario);
        $resultado = $excluir->execute();
        $excluir->close();

        return $resultado;
    }

    public static function buscarUsuario($id_usuario)
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $busca = "SELECT * FROM usuarios WHERE id_usuario = ?";

        $procura = $conexao->prepare($busca);
        $procura->bind_param("i", $id_usuario);
        $procura->execute();
        $procura->bind_result($nome);

        $usuario = null;

        while ($procura->fetch()) {
            $usuario = $nome;
        }

        $procura->close();

        return $usuario;
    }

    public static function buscarUsuarioExistente($busca)
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $query = "SELECT * FROM usuarios WHERE nome LIKE ? OR email = ? OR token = ?";
        $procura = $conexao->prepare($query);

        $parametro = "%$busca%";
        $procura->bind_param("sss", $parametro, $busca, $busca);

        $procura->execute();
        $result = $procura->get_result();

        $usuario = null;

        if ($row = $result->fetch_assoc()) {
            $usuario = $row;
        }

        $procura->close();

        return $usuario;
    }
}
