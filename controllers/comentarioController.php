<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Exigir autenticacao
if (!isset($_SESSION["id_usuario"])) {
    echo json_encode(["status" => "error", "message" => "Voce precisa estar logado para comentar."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/comentarioModel.php";

    $action = isset($_POST["action"]) ? trim($_POST["action"]) : "";

    if ($action === "criar") {
        $id_postagem = isset($_POST["id_postagem"]) ? intval($_POST["id_postagem"]) : 0;
        $parent_id = isset($_POST["parent_id"]) && !empty($_POST["parent_id"]) ? intval($_POST["parent_id"]) : null;
        $texto = isset($_POST["texto"]) ? trim($_POST["texto"]) : "";

        if (empty($texto) || $id_postagem <= 0) {
            echo json_encode(["status" => "error", "message" => "O texto do comentario nao pode estar vazio."]);
            exit();
        }

        $comentarioModel = new Comentario();
        $novoComentario = new Comentario(null, $id_postagem, $_SESSION["id_usuario"], $parent_id, $texto);

        if ($comentarioModel->adicionarComentario($novoComentario)) {
            echo json_encode(["status" => "success", "message" => "Comentario publicado com sucesso!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erro ao salvar o comentario. Tente novamente."]);
        }
        exit();

    } elseif ($action === "excluir") {
        $id_comentario = isset($_POST["id_comentario"]) ? intval($_POST["id_comentario"]) : 0;

        if ($id_comentario <= 0) {
            echo json_encode(["status" => "error", "message" => "Comentario invalido."]);
            exit();
        }

        $comentarioModel = new Comentario();
        
        // Conexao direta para verificar posse do comentario
        $db = new Conexao();
        $conn = $db->conectar();
        $stmt = $conn->prepare("SELECT id_usuario FROM comentarios_postagens WHERE id_comentario = ?");
        $stmt->bind_param("i", $id_comentario);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$res) {
            echo json_encode(["status" => "error", "message" => "Comentario nao encontrado."]);
            exit();
        }

        // Permite excluir se for o autor ou for admin
        if ($res["id_usuario"] == $_SESSION["id_usuario"] || $_SESSION["nivel"] === "admin") {
            if ($comentarioModel->excluirComentario($id_comentario)) {
                echo json_encode(["status" => "success", "message" => "Comentario excluido com sucesso!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Erro ao excluir o comentario."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Acesso negado. Voce nao tem permissao para excluir este comentario."]);
        }
        exit();
    } else {
        echo json_encode(["status" => "error", "message" => "Acao invalida."]);
        exit();
    }
} else {
    echo json_encode(["status" => "error", "message" => "Metodo de requisicao invalido."]);
    exit();
}
