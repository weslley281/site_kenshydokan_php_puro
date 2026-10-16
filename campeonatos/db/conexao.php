<?php
/**
 * Conexão com o Banco de Dados do Sistema de Campeonatos Kenshydokan
 * Padrão IDÊNTICO ao site principal (mysqli nativo, fallback de host e suporte a .env)
 */

class Conexao {

    private $host;
    private $hostPrincipal;
    private $port;
    private $user; 
    private $userPrincipal;
    private $pass;
    private $passPrincipal;
    private $db;
    private $dbPrincipal;
    private $portPrincipal;
    private $arquivoEnv = 'variáveis do ambiente do servidor';

    private static $conn = null;
    private static $connPrincipal = null;
    private static $migrationsExecutadas = false;
    private static $diagnosticos = [];

    public static function adicionarDiagnostico(string $etapa, array $detalhes = []): void {
        self::$diagnosticos[] = ['etapa' => $etapa, 'detalhes' => $detalhes];
    }

    public static function obterDiagnosticos(): array {
        return self::$diagnosticos;
    }

    public static function exibirDiagnosticoSeAtivo(): void {
        if (!self::debugAtivo()) {
            return;
        }

        ini_set('display_errors', '1');
        error_reporting(E_ALL);
        echo '<pre>Diagnóstico do banco de campeonatos (sem credenciais):' . "\n";
        ob_start();
        var_dump(self::$diagnosticos);
        echo htmlspecialchars(ob_get_clean(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        echo '</pre>';
    }

    private static function debugAtivo(): bool {
        $debug = $_ENV['APP_DEBUG'] ?? getenv('APP_DEBUG');
        return $debug === 'true' || $debug === '1';
    }

    public function __construct() {
        $this->carregarEnv();

        $this->host = $_ENV['DB_HOST'] ?? (getenv('DB_HOST') ?: '127.0.0.1');
        $this->port = (int) ($_ENV['DB_PORT'] ?? (getenv('DB_PORT') ?: 3306));
        $this->user = $_ENV['DB_USER'] ?? (getenv('DB_USER') ?: 'u515961161_kenshydokan');
        $this->pass = isset($_ENV['DB_PASS']) ? $_ENV['DB_PASS'] : (getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'Wesv@g28');
        $this->hostPrincipal = $_ENV['MAIN_DB_HOST'] ?? (getenv('MAIN_DB_HOST') ?: $this->host);
        $this->userPrincipal = $_ENV['MAIN_DB_USER'] ?? (getenv('MAIN_DB_USER') ?: $this->user);
        $this->passPrincipal = isset($_ENV['MAIN_DB_PASS']) ? $_ENV['MAIN_DB_PASS'] : (getenv('MAIN_DB_PASS') !== false ? getenv('MAIN_DB_PASS') : $this->pass);
        
        // Banco específico de campeonatos
        $this->db = $_ENV['DB_NAME_CAMPEONATOS'] ?? (getenv('DB_NAME_CAMPEONATOS') ?: 'campeonatos');

        // Banco do domínio principal (somente para leitura de filiados)
        $this->dbPrincipal = $_ENV['MAIN_DB_NAME'] ?? (getenv('MAIN_DB_NAME') ?: ($_ENV['DB_NAME'] ?? (getenv('DB_NAME') ?: 'u515961161_kenshydokan')));
        $this->portPrincipal = (int) ($_ENV['MAIN_DB_PORT'] ?? (getenv('MAIN_DB_PORT') ?: $this->port));

        self::adicionarDiagnostico('Configuração carregada', [
            'arquivo_env' => $this->arquivoEnv,
            'host' => $this->host,
            'porta' => $this->port,
            'banco_campeonatos' => $this->db,
            'usuario_configurado' => $this->user !== '',
            'senha_configurada' => $this->pass !== ''
        ]);
    }

    private function carregarEnv() {
        $envPaths = [
            __DIR__ . '/../.env',
            __DIR__ . '/../.env.development',
            __DIR__ . '/../../.env',
            __DIR__ . '/../../.env.development'
        ];

        foreach ($envPaths as $envPath) {
            if (file_exists($envPath)) {
                $this->arquivoEnv = $envPath;
                $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (empty($line) || substr($line, 0, 1) === '#') {
                        continue;
                    }
                    if (strpos($line, '=') !== false) {
                        list($key, $value) = explode('=', $line, 2);
                        $key = trim($key);
                        $value = trim($value, " \t\n\r\0\x0B\"'");
                        putenv("$key=$value");
                        $_ENV[$key] = $value;
                    }
                }
                break;
            }
        }

        if (self::debugAtivo()) {
            ini_set('display_errors', '1');
            error_reporting(E_ALL);
        }
    }

    private function mensagemSemCredenciais(Throwable $erro): string {
        $mensagem = $erro->getMessage();
        foreach ([$this->user, $this->pass] as $valorSensivel) {
            if ($valorSensivel !== '') {
                $mensagem = str_replace($valorSensivel, '[redigido]', $mensagem);
            }
        }
        return $mensagem;
    }

    /**
     * Conexão com o banco de dados 'campeonatos' (Leitura e Escrita)
     */
    public function conectar() {
        $connectionValid = false;
        if (self::$conn !== null) {
            try {
                $connectionValid = @self::$conn->ping();
            } catch (Throwable $t) {
                $connectionValid = false;
            }
        }

        if (self::$conn === null || !$connectionValid) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            try {
                self::$conn = new mysqli($this->host, $this->user, $this->pass, $this->db, $this->port);
                self::$conn->set_charset('utf8mb4');
            } catch (Exception $e) {
                self::adicionarDiagnostico('Conexão com o banco configurado', [
                    'host' => $this->host,
                    'banco' => $this->db,
                    'erro' => $this->mensagemSemCredenciais($e),
                    'codigo' => $e->getCode(),
                    'sqlstate' => $e instanceof mysqli_sql_exception ? $e->getSqlState() : null
                ]);
                // Se o banco ainda não existe, cria automaticamente
                if (stripos($e->getMessage(), 'Unknown database') !== false) {
                    try {
                        $temp = new mysqli($this->host, $this->user, $this->pass, '', $this->port);
                        $temp->query("CREATE DATABASE IF NOT EXISTS `{$this->db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                        $temp->close();
                        self::$conn = new mysqli($this->host, $this->user, $this->pass, $this->db, $this->port);
                        self::$conn->set_charset('utf8mb4');
                    } catch (Exception $eCreate) {
                        self::adicionarDiagnostico('Criação/conexão do banco no host configurado', [
                            'host' => $this->host,
                            'banco' => $this->db,
                            'erro' => $this->mensagemSemCredenciais($eCreate),
                            'codigo' => $eCreate->getCode(),
                            'sqlstate' => $eCreate instanceof mysqli_sql_exception ? $eCreate->getSqlState() : null
                        ]);
                    }
                }

                if (self::$conn !== null) {
                    $this->garantirEstrutura();
                    return self::$conn;
                }

                if (!in_array($this->host, ['127.0.0.1', 'localhost'], true)) {
                    self::exibirDiagnosticoSeAtivo();
                    throw new RuntimeException('Não foi possível conectar ao banco de campeonatos no host configurado. Consulte o diagnóstico de inicialização.', 0, $e);
                }

                // Fallback automático entre 127.0.0.1 e localhost
                $altHost = ($this->host === '127.0.0.1') ? 'localhost' : '127.0.0.1';
                try {
                    self::$conn = new mysqli($altHost, $this->user, $this->pass, $this->db, $this->port);
                    self::$conn->set_charset('utf8mb4');
                } catch (Exception $e2) {
                    self::adicionarDiagnostico('Conexão com o host alternativo', [
                        'host' => $altHost,
                        'banco' => $this->db,
                        'erro' => $this->mensagemSemCredenciais($e2),
                        'codigo' => $e2->getCode(),
                        'sqlstate' => $e2 instanceof mysqli_sql_exception ? $e2->getSqlState() : null
                    ]);
                    if (stripos($e2->getMessage(), 'Unknown database') !== false) {
                        try {
                            $temp = new mysqli($altHost, $this->user, $this->pass, '', $this->port);
                            $temp->query("CREATE DATABASE IF NOT EXISTS `{$this->db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                            $temp->close();
                            self::$conn = new mysqli($altHost, $this->user, $this->pass, $this->db, $this->port);
                            self::$conn->set_charset('utf8mb4');
                        } catch (Exception $eCreate2) {
                            self::adicionarDiagnostico('Criação/conexão do banco no host alternativo', [
                                'host' => $altHost,
                                'banco' => $this->db,
                                'erro' => $this->mensagemSemCredenciais($eCreate2),
                                'codigo' => $eCreate2->getCode(),
                                'sqlstate' => $eCreate2 instanceof mysqli_sql_exception ? $eCreate2->getSqlState() : null
                            ]);
                        }
                    }
                    if (self::$conn !== null) {
                        $this->garantirEstrutura();
                        return self::$conn;
                    }
                    self::exibirDiagnosticoSeAtivo();
                    throw new RuntimeException('Não foi possível conectar ao banco de campeonatos. Consulte o diagnóstico de inicialização.', 0, $e2);
                }
            }
        }
        $this->garantirEstrutura();
        return self::$conn;
    }

    private function garantirEstrutura(): void {
        if (self::$migrationsExecutadas) {
            return;
        }

        self::$migrationsExecutadas = true;
        try {
            require_once __DIR__ . "/migrations.php";
            $migration = new Migration();
            $migration->executarTodas();
            self::adicionarDiagnostico('Migrações concluídas', [
                'host' => $this->host,
                'porta' => $this->port,
                'banco' => $this->db
            ]);
            if (self::debugAtivo() && ($_GET['db_debug'] ?? '') === '1') {
                $tabelas = self::$conn->query('SHOW TABLES');
                $nomesTabelas = [];
                while ($linha = $tabelas->fetch_row()) {
                    $nomesTabelas[] = $linha[0];
                }
                self::adicionarDiagnostico('Tabelas existentes após as migrações', [
                    'tabelas' => $nomesTabelas
                ]);
                self::exibirDiagnosticoSeAtivo();
            }
        } catch (Throwable $erro) {
            self::$migrationsExecutadas = false;
            self::adicionarDiagnostico('Falha ao criar tabelas', [
                'host' => $this->host,
                'banco' => $this->db,
                'erro' => $erro->getMessage(),
                'codigo' => $erro->getCode(),
                'sqlstate' => $erro instanceof mysqli_sql_exception ? $erro->getSqlState() : null
            ]);
            if (self::$conn instanceof mysqli) {
                try {
                    $resultado = self::$conn->query('SHOW TABLES');
                    $tabelas = [];
                    while ($linha = $resultado->fetch_row()) {
                        $tabelas[] = $linha[0];
                    }
                    self::adicionarDiagnostico('Tabelas presentes no banco após a falha', [
                        'tabelas' => $tabelas
                    ]);
                } catch (mysqli_sql_exception $erroListagem) {
                    self::adicionarDiagnostico('Falha ao listar tabelas existentes', [
                        'erro' => $this->mensagemSemCredenciais($erroListagem),
                        'codigo' => $erroListagem->getCode(),
                        'sqlstate' => $erroListagem->getSqlState()
                    ]);
                }
            }
            self::exibirDiagnosticoSeAtivo();
            throw $erro;
        }
    }

    /**
     * Conexão com o banco principal do site (SOMENTE LEITURA para filiados)
     */
    public function conectarPrincipal() {
        $connectionValid = false;
        if (self::$connPrincipal !== null) {
            try {
                $connectionValid = @self::$connPrincipal->ping();
            } catch (Throwable $t) {
                $connectionValid = false;
            }
        }

        if (self::$connPrincipal === null || !$connectionValid) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            try {
                self::$connPrincipal = new mysqli($this->hostPrincipal, $this->userPrincipal, $this->passPrincipal, $this->dbPrincipal, $this->portPrincipal);
                self::$connPrincipal->set_charset('utf8mb4');
            } catch (Exception $e) {
                if (!in_array($this->hostPrincipal, ['127.0.0.1', 'localhost'], true)) {
                    throw new RuntimeException('Não foi possível conectar ao banco principal no host configurado.', 0, $e);
                }
                $altHost = ($this->hostPrincipal === '127.0.0.1') ? 'localhost' : '127.0.0.1';
                try {
                    self::$connPrincipal = new mysqli($altHost, $this->userPrincipal, $this->passPrincipal, $this->dbPrincipal, $this->portPrincipal);
                    self::$connPrincipal->set_charset('utf8mb4');
                } catch (Exception $e2) {
                    throw new RuntimeException('Não foi possível conectar ao banco de dados principal.', 0, $e2);
                }
            }
        }
        return self::$connPrincipal;
    }
}

class Database extends Conexao {
    public function getConnection() {
        return $this->conectar();
    }
}
