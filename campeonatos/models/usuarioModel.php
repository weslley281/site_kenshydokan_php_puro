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

    public function criarAdminInicialSeNecessario(?string $email, ?string $senha): bool {
        if ($this->temAdministrador()) {
            return false;
        }

        $resultadoLock = $this->conexao->query("SELECT GET_LOCK('kenshydokan_campeonatos_admin_init', 10)");
        $lockObtido = $resultadoLock->fetch_row()[0] ?? null;
        $resultadoLock->free();

        if ((int) $lockObtido !== 1) {
            throw new RuntimeException('Não foi possível obter o bloqueio para inicializar o administrador.');
        }

        try {
            if ($this->temAdministrador()) {
                return false;
            }

            if ($email === null || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                throw new RuntimeException('Configure um ADMIN_EMAIL válido no arquivo .env para criar o primeiro administrador.');
            }
            if ($senha === null || strlen($senha) < 12) {
                throw new RuntimeException('Configure ADMIN_PASSWORD com pelo menos 12 caracteres no arquivo .env para criar o primeiro administrador.');
            }

            if ($this->buscarPorEmail($email) !== null) {
                throw new RuntimeException('ADMIN_EMAIL já está associado a uma conta que não é administradora.');
            }

            $this->criarUsuario('Administrador Geral', $email, $senha, 'admin');
            return true;
        } finally {
            $resultadoRelease = $this->conexao->query("SELECT RELEASE_LOCK('kenshydokan_campeonatos_admin_init')");
            $resultadoRelease->free();
        }
    }

    private function temAdministrador(): bool {
        $resultado = $this->conexao->query("SELECT id_usuario FROM campeonato_usuarios WHERE nivel = 'admin' LIMIT 1");
        $existe = $resultado->num_rows > 0;
        $resultado->free();
        return $existe;
    }
}
