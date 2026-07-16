<?php
include_once __DIR__ . "/../db/conexao.php";

class Usuario
{
    private $nome;
    private $id_fil;
    private $id_imagem;
    private $email;
    private $nivel;
    private $telefone;
    private $senha;
    private $dataCriacao;
    private $dataMudanca;
    private $conexao;

    public function __construct($nome = null, $id_fil = null, $id_imagem = null, $email = null, $telefone = null, $dataMudanca = null, $senha = "", $nivel = "aluno")
    {
        $this->nome = $nome;
        $this->id_fil = $id_fil;
        $this->id_imagem = $id_imagem;
        $this->email = $email;
        $this->nivel = $nivel;
        $this->telefone = $telefone;
        $this->senha = $senha;
        $this->dataCriacao = date("Y-m-d");
        $this->dataMudanca = $dataMudanca;

        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function getConnection()
    {
        return $this->conexao;
    }

    // Métodos getters
    public function getNome()
    {
        return $this->nome;
    }

    public function getIdFil()
    {
        return $this->id_fil;
    }

    public function getIdImagem()
    {
        return $this->id_imagem;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getNivel()
    {
        return $this->nivel;
    }

    public function getTelefone()
    {
        return $this->telefone;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }

    public function getDataMudanca()
    {
        return $this->dataMudanca;
    }

    // Métodos setters
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function setIdFil($id_fil)
    {
        $this->id_fil = $id_fil;
    }

    public function setIdImagem($id_imagem)
    {
        $this->id_imagem = $id_imagem;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setNivel($nivel)
    {
        $this->nivel = $nivel;
    }

    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    public function setDataCriacao($dataCriacao)
    {
        $this->dataCriacao = $dataCriacao;
    }

    public function setDataMudanca($dataMudanca)
    {
        $this->dataMudanca = $dataMudanca;
    }

    // Métodos vindos do Repositório

    public function criarUsuario(Usuario $usuario): bool
    {
        $inserir = $this->conexao->prepare("INSERT INTO usuarios (nome, id_fil, id_imagem, email, nivel, telefone, senha, dataCriacao, dataMudanca) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $nome = $usuario->getNome();
        $if = $usuario->getIdFil();
        $ii = $usuario->getIdImagem();
        $em = $usuario->getEmail();
        $ni = $usuario->getNivel();
        $te = $usuario->getTelefone();
        $se = $usuario->getSenha();
        $dc = $usuario->getDataCriacao();
        $dm = $usuario->getDataMudanca();

        $inserir->bind_param("siissssss", $nome, $if, $ii, $em, $ni, $te, $se, $dc, $dm);

        $resultado = $inserir->execute();
        $inserir->close();

        return $resultado;
    }

    public function editarUsuario(int $id_usuario, Usuario $usuario): bool
    {
        $atualizar = $this->conexao->prepare("UPDATE usuarios SET nome = ?, id_fil = ?, id_imagem = ?, email = ?, nivel = ?, telefone = ?, dataMudanca = ? WHERE id_usuario = ?");
        $nome = $usuario->getNome();
        $if = $usuario->getIdFil();
        $ii = $usuario->getIdImagem();
        $em = $usuario->getEmail();
        $ni = $usuario->getNivel();
        $te = $usuario->getTelefone();
        $dm = $usuario->getDataMudanca();

        $atualizar->bind_param("siissssi", $nome, $if, $ii, $em, $ni, $te, $dm, $id_usuario);
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

    public function updateRememberToken(int $id_usuario, string $token_hash, string $expires): bool
    {
        $atualizar = $this->conexao->prepare("UPDATE usuarios SET remember_token = ?, remember_token_expires_at = ? WHERE id_usuario = ?");
        $atualizar->bind_param("ssi", $token_hash, $expires, $id_usuario);
        $resultado = $atualizar->execute();
        $atualizar->close();

        return $resultado;
    }

    public function findUserByRememberToken(string $token_hash)
    {
        $busca = "SELECT * FROM usuarios WHERE remember_token = ?";

        $procura = $this->conexao->prepare($busca);
        $procura->bind_param("s", $token_hash);
        $procura->execute();
        $result = $procura->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        $usuario = $result->fetch_assoc();
        $procura->close();

        return $usuario;
    }

    public function editarTokenUsuario(int $id_usuario, string $token): bool
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
        $result = $procura->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        $usuario = $result->fetch_assoc();
        $procura->close();

        return $usuario;
    }

    public static function buscarUsuarioPorToken($token)
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        $usuario = null;
        if ($row = $result->fetch_assoc()) {
            $usuario = $row;
        }

        $stmt->close();
        return $usuario;
    }

    public static function buscarUsuarioExistente($busca)
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $query = "SELECT * FROM usuarios WHERE nome LIKE ? OR email = ?";
        $procura = $conexao->prepare($query);

        $parametro = "%$busca%";
        $procura->bind_param("ss", $parametro, $busca);

        $procura->execute();
        $result = $procura->get_result();

        $usuario = null;

        if ($row = $result->fetch_assoc()) {
            $usuario = $row;
        }

        $procura->close();

        return $usuario;
    }

    public static function buscarUsuarioPorEmail($email)
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $usuario = null;
        if ($row = $result->fetch_assoc()) {
            $usuario = $row;
        }

        $stmt->close();
        return $usuario;
    }

    public static function gerarTokenRecuperacao($id_usuario)
    {
        $token = bin2hex(random_bytes(32));
        $c = new Conexao();
        $conexao = $c->conectar();

        $stmt = $conexao->prepare("UPDATE usuarios SET token = ? WHERE id_usuario = ?");
        $stmt->bind_param("si", $token, $id_usuario);
        $stmt->execute();
        $stmt->close();

        return $token;
    }

    public static function buscarTodosUsuarios()
    {
        $c = new Conexao();
        $conexao = $c->conectar();
        $busca = "SELECT * FROM usuarios";
        $resultado = $conexao->query($busca);
        $usuarios = [];
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $usuarios[] = $row;
            }
        }
        return $usuarios;
    }
}
