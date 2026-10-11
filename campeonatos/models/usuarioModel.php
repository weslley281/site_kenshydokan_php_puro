<?php
/**
 * Modelo de Usuários do Sistema de Campeonatos (campeonato_usuarios)
 * Padrão IDÊNTICO ao site principal (models/usuarioModel.php)
 */

include_once __DIR__ . "/../db/conexao.php";

class Usuario {

    private $conexao;

    public function __construct() {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function buscarPorId(int $id): ?array {
        $stmt = $this->conexao->prepare("SELECT id_usuario, nome, email, nivel, ativo, created_at FROM campeonato_usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function buscarPorEmail(string $email): ?array {
        $stmt = $this->conexao->prepare("SELECT * FROM campeonato_usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function autenticar(string $email, string $senha): ?array {
        $user = $this->buscarPorEmail($email);
        if (!$user || empty($user['ativo'])) {
            return null;
        }

        if (password_verify($senha, $user['senha'])) {
            return $user;
        }

        return null;
    }

    public function criarUsuario(string $nome, string $email, string $senha, string $nivel = 'mesario'): int {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $this->conexao->prepare("INSERT INTO campeonato_usuarios (nome, email, senha, nivel) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $email, $hash, $nivel);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }
}
