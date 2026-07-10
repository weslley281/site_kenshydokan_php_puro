<?php
include_once __DIR__ . "/../db/conexao.php";

class Publicacao
{
    private $id_usuario;
    private $id_imagem;
    private $titulo;
    private $conteudo;
    private $status;
    private $dataCriacao;
    private $dataMudanca;
    private $conexao;

    public function __construct($id_usuario = null, $id_imagem = null, $titulo = null, $conteudo = null, $dataMudanca = null, $status = "aguardando")
    {
        $this->id_usuario = $id_usuario;
        $this->id_imagem = $id_imagem;
        $this->titulo = $titulo;
        $this->conteudo = $conteudo;
        $this->status = $status;
        $this->dataCriacao = date("Y-m-d");
        $this->dataMudanca = $dataMudanca;

        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    // Métodos Getters
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function getIdImagem()
    {
        return $this->id_imagem;
    }

    public function setIdImagem($id_imagem)
    {
        $this->id_imagem = $id_imagem;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function getConteudo()
    {
        return $this->conteudo;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }

    public function getDataMudanca()
    {
        return $this->dataMudanca;
    }

    // Métodos Setters
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function setConteudo($conteudo)
    {
        $this->conteudo = $conteudo;
    }

    public function setStatus($status)
    {
        $this->status = $status;
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

    public function criarPublicacao(Publicacao $publicacao): bool
    {
        $inserir = $this->conexao->prepare("INSERT INTO postagens (id_usuario, id_imagem, titulo, conteudo, status, dataCriacao, dataMudanca) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $id_u = $publicacao->getIdUsuario();
        $id_img = $publicacao->getIdImagem();
        $tit = $publicacao->getTitulo();
        $cont = $publicacao->getConteudo();
        $st = $publicacao->getStatus();
        $dc = $publicacao->getDataCriacao();
        $dm = $publicacao->getDataMudanca();

        $inserir->bind_param("iisssss", $id_u, $id_img, $tit, $cont, $st, $dc, $dm);
        $resultado = $inserir->execute();
        $inserir->close();

        return $resultado;
    }

    public function editar_publicacao(int $id_publicacao, Publicacao $publicacao): bool
    {
        $atualizar = $this->conexao->prepare("UPDATE postagens SET id_imagem = ?, titulo = ?, conteudo = ?, status = ?, dataMudanca = ? WHERE id_publicacao = ?");
        $id_img = $publicacao->getIdImagem();
        $tit = $publicacao->getTitulo();
        $cont = $publicacao->getConteudo();
        $st = $publicacao->getStatus();
        $dm = $publicacao->getDataMudanca();

        $atualizar->bind_param("issssi", $id_img, $tit, $cont, $st, $dm, $id_publicacao);
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

    public static function buscar_autor($id_usuario)
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $busca = "SELECT u.nome, i.caminho as foto FROM usuarios u LEFT JOIN imagens i ON u.id_imagem = i.id_imagem WHERE u.id_usuario = ?";

        $procura = $conexao->prepare($busca);
        $procura->bind_param("i", $id_usuario);
        $procura->execute();
        $resultado = $procura->get_result();
        $autor = null;

        if ($resultado && $row = $resultado->fetch_assoc()) {
            $autor = $row;
        }

        $procura->close();

        return $autor;
    }

    public static function buscarPostagensAprovadas()
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $busca = "SELECT p.*, i.caminho as caminho_imagem FROM postagens p LEFT JOIN imagens i ON p.id_imagem = i.id_imagem WHERE p.status = 'aprovado' ORDER BY p.id_publicacao DESC";
        $resultado = $conexao->query($busca);

        $postagens = [];
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $postagens[] = $row;
            }
        }
        return $postagens;
    }

    public static function buscarTodasPostagens()
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $busca = "SELECT p.*, i.caminho as caminho_imagem FROM postagens p LEFT JOIN imagens i ON p.id_imagem = i.id_imagem ORDER BY p.id_publicacao DESC";
        $resultado = $conexao->query($busca);

        $postagens = [];
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $postagens[] = $row;
            }
        }
        return $postagens;
    }

    public static function buscarPostagemPorId(int $id_publicacao)
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $busca = $conexao->prepare("SELECT p.*, i.caminho as caminho_imagem FROM postagens p LEFT JOIN imagens i ON p.id_imagem = i.id_imagem WHERE p.id_publicacao = ?");
        $busca->bind_param("i", $id_publicacao);
        $busca->execute();
        $resultado = $busca->get_result();

        if ($resultado->num_rows === 0) {
            return null;
        }

        $postagem = $resultado->fetch_assoc();
        $busca->close();

        return $postagem;
    }

    public static function buscarPostagensPorUsuario(int $id_usuario)
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $busca = $conexao->prepare("SELECT p.*, i.caminho as caminho_imagem FROM postagens p LEFT JOIN imagens i ON p.id_imagem = i.id_imagem WHERE p.id_usuario = ? ORDER BY p.id_publicacao DESC");
        $busca->bind_param("i", $id_usuario);
        $busca->execute();
        $resultado = $busca->get_result();

        $postagens = [];
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $postagens[] = $row;
            }
        }
        $busca->close();

        return $postagens;
    }
}
