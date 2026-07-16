<?php
include_once __DIR__ . "/../db/conexao.php";

class Comentario
{
    private $id_comentario;
    private $id_postagem;
    private $id_usuario;
    private $parent_id;
    private $texto;
    private $data_criacao;
    private $conexao;

    public function __construct($id_comentario = null, $id_postagem = null, $id_usuario = null, $parent_id = null, $texto = null, $data_criacao = null)
    {
        $this->id_comentario = $id_comentario;
        $this->id_postagem = $id_postagem;
        $this->id_usuario = $id_usuario;
        $this->parent_id = $parent_id;
        $this->texto = $texto;
        $this->data_criacao = $data_criacao;

        $c = new Conexao();
        $this->conexao = $c->conectar();

        // Criacao automatica da tabela se nao existir
        if ($this->conexao) {
            $this->conexao->query("CREATE TABLE IF NOT EXISTS comentarios_postagens (
                id_comentario INT AUTO_INCREMENT PRIMARY KEY,
                id_postagem INT NOT NULL,
                id_usuario INT NOT NULL,
                parent_id INT DEFAULT NULL,
                texto TEXT NOT NULL,
                data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (id_postagem) REFERENCES postagens(id_publicacao) ON DELETE CASCADE,
                FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
                FOREIGN KEY (parent_id) REFERENCES comentarios_postagens(id_comentario) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
    }

    public function getIdComentario() { return $this->id_comentario; }
    public function getIdPostagem() { return $this->id_postagem; }
    public function getIdUsuario() { return $this->id_usuario; }
    public function getParentId() { return $this->parent_id; }
    public function getTexto() { return $this->texto; }
    public function getDataCriacao() { return $this->data_criacao; }

    public function adicionarComentario(Comentario $comentario): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO comentarios_postagens (id_postagem, id_usuario, parent_id, texto) VALUES (?, ?, ?, ?)");
            $post = $comentario->getIdPostagem();
            $user = $comentario->getIdUsuario();
            $parent = $comentario->getParentId();
            $txt = $comentario->getTexto();

            $inserir->bind_param("iiis", $post, $user, $parent, $txt);
            $resultado = $inserir->execute();
            $inserir->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao adicionar comentario: " . $e->getMessage());
            return false;
        }
    }

    public function buscarComentariosPorPostagem($id_postagem): array
    {
        $comentarios = [];
        try {
            // Busca comentarios e faz JOIN com usuarios e suas fotos
            $sql = "SELECT c.*, u.nome as autor_nome, img.caminho as autor_foto 
                    FROM comentarios_postagens c 
                    INNER JOIN usuarios u ON c.id_usuario = u.id_usuario 
                    LEFT JOIN imagens img ON u.id_imagem = img.id_imagem
                    WHERE c.id_postagem = ? 
                    ORDER BY c.id_comentario ASC";
            $busca = $this->conexao->prepare($sql);
            $busca->bind_param("i", $id_postagem);
            $busca->execute();
            $resultado = $busca->get_result();
            if ($resultado) {
                while ($row = $resultado->fetch_assoc()) {
                    $comentarios[] = $row;
                }
            }
            $busca->close();
        } catch (Exception $e) {
            error_log("Erro ao buscar comentarios: " . $e->getMessage());
        }
        return $comentarios;
    }

    public function excluirComentario($id_comentario): bool
    {
        try {
            $deletar = $this->conexao->prepare("DELETE FROM comentarios_postagens WHERE id_comentario = ?");
            $deletar->bind_param("i", $id_comentario);
            $resultado = $deletar->execute();
            $deletar->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao excluir comentario: " . $e->getMessage());
            return false;
        }
    }
}
