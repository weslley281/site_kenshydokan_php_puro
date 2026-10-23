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

    /**
     * RF01.3: segundos restantes de bloqueio para o e-mail informado.
     * Bloqueia após $maxFalhas falhas seguidas (desde o último login com sucesso)
     * dentro da janela de $minutos. Retorna 0 quando não está bloqueado.
     */
    public function segundosDeBloqueio(string $email, int $maxFalhas = 5, int $minutos = 15): int {
        $sql = "SELECT COUNT(*) AS total,
                       TIMESTAMPDIFF(SECOND, NOW(), DATE_ADD(MAX(created_at), INTERVAL ? MINUTE)) AS restante
                FROM campeonato_login_tentativas
                WHERE email = ?
                  AND sucesso = 0
                  AND created_at > DATE_SUB(NOW(), INTERVAL ? MINUTE)
                  AND created_at > COALESCE(
                        (SELECT MAX(created_at) FROM campeonato_login_tentativas WHERE email = ? AND sucesso = 1),
                        '1970-01-01 00:00:01')";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("isis", $minutos, $email, $minutos, $email);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ((int)($row['total'] ?? 0) >= $maxFalhas) {
            return max(0, (int)($row['restante'] ?? 0));
        }
        return 0;
    }

    public function registrarTentativaLogin(string $email, bool $sucesso): void {
        $ip = substr($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1', 0, 45);
        $flag = $sucesso ? 1 : 0;
        $stmt = $this->conexao->prepare("INSERT INTO campeonato_login_tentativas (email, ip_address, sucesso) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $email, $ip, $flag);
        $stmt->execute();
        $stmt->close();
    }

    public function listarTodos(): array {
        $resultado = $this->conexao->query(
            "SELECT id_usuario, nome, email, nivel, ativo, created_at FROM campeonato_usuarios ORDER BY ativo DESC, nome ASC"
        );
        $usuarios = [];
        while ($row = $resultado->fetch_assoc()) {
            $usuarios[] = $row;
        }
        $resultado->free();
        return $usuarios;
    }

    /** E-mail já usado por outro usuário? (ignora o próprio id ao editar) */
    public function emailEmUso(string $email, int $ignorarId = 0): bool {
        $stmt = $this->conexao->prepare("SELECT id_usuario FROM campeonato_usuarios WHERE email = ? AND id_usuario <> ? LIMIT 1");
        $stmt->bind_param("si", $email, $ignorarId);
        $stmt->execute();
        $existe = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    public function atualizarDados(int $id, string $nome, string $email, string $nivel): bool {
        $stmt = $this->conexao->prepare("UPDATE campeonato_usuarios SET nome = ?, email = ?, nivel = ? WHERE id_usuario = ?");
        $stmt->bind_param("sssi", $nome, $email, $nivel, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function alterarAtivo(int $id, bool $ativo): bool {
        $flag = $ativo ? 1 : 0;
        $stmt = $this->conexao->prepare("UPDATE campeonato_usuarios SET ativo = ? WHERE id_usuario = ?");
        $stmt->bind_param("ii", $flag, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function redefinirSenha(int $id, string $novaSenha): bool {
        $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $stmt = $this->conexao->prepare("UPDATE campeonato_usuarios SET senha = ? WHERE id_usuario = ?");
        $stmt->bind_param("si", $hash, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function contarAdminsAtivos(): int {
        $resultado = $this->conexao->query("SELECT COUNT(*) AS total FROM campeonato_usuarios WHERE nivel = 'admin' AND ativo = 1");
        $total = (int)($resultado->fetch_assoc()['total'] ?? 0);
        $resultado->free();
        return $total;
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
