<?php
namespace App\Models;

use App\Core\Database;

/**
 * Model de Usuários e Operadores do Sistema de Campeonatos
 */
class UsuarioModel
{
    public static function findByEmail(string $email): ?array
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM campeonato_usuarios WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();
        return $user ?: null;
    }

    public static function findById(int $id): ?array
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT id_usuario, nome, email, papel, status, ultimo_acesso, data_criacao FROM campeonato_usuarios WHERE id_usuario = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();
        return $user ?: null;
    }

    public static function updateLastLogin(int $id): void
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE campeonato_usuarios SET ultimo_acesso = NOW() WHERE id_usuario = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    public static function all(): array
    {
        $conn = Database::getConnection();
        $res = $conn->query("SELECT id_usuario, nome, email, papel, status, ultimo_acesso, data_criacao FROM campeonato_usuarios ORDER BY nome ASC");
        return $res->fetch_all(MYSQLI_ASSOC);
    }
}
