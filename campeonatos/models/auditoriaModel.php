<?php
/**
 * Modelo de Trilha de Auditoria (campeonato_auditoria)
 * Padrão IDÊNTICO ao site principal (models/auditoriaModel.php)
 */

include_once __DIR__ . "/../db/conexao.php";

class Auditoria {

    private $conexao;

    public function __construct() {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public static function log(
        string $acao,
        ?string $tabela = null,
        ?int $registroId = null,
        $dadosAntes = null,
        $dadosDepois = null,
        ?string $justificativa = null,
        ?int $idCampeonato = null
    ): void {
        try {
            $c = new Conexao();
            $conn = $c->conectar();

            $sql = "INSERT INTO campeonato_auditoria (
                id_campeonato, id_usuario, acao, tabela_afetada, registro_id,
                dados_antes, dados_depois, justificativa, ip_address, user_agent
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            if ($stmt) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $userId = $_SESSION['id_usuario'] ?? null;
                $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
                $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

                $strAntes = $dadosAntes !== null ? json_encode($dadosAntes, JSON_UNESCAPED_UNICODE) : null;
                $strDepois = $dadosDepois !== null ? json_encode($dadosDepois, JSON_UNESCAPED_UNICODE) : null;

                $stmt->bind_param(
                    "iississsss",
                    $idCampeonato,
                    $userId,
                    $acao,
                    $tabela,
                    $registroId,
                    $strAntes,
                    $strDepois,
                    $justificativa,
                    $ip,
                    $ua
                );

                $stmt->execute();
                $stmt->close();
            }
        } catch (Throwable $t) {
            error_log("Erro ao gravar log de auditoria: " . $t->getMessage());
        }
    }

    public function buscarPorCampeonato(int $idCampeonato, int $limite = 200): array {
        $sql = "SELECT a.*, u.nome AS usuario_nome
                FROM campeonato_auditoria a
                LEFT JOIN campeonato_usuarios u ON a.id_usuario = u.id_usuario
                WHERE a.id_campeonato = ?
                ORDER BY a.created_at DESC
                LIMIT ?";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("ii", $idCampeonato, $limite);
        $stmt->execute();
        $res = $stmt->get_result();
        $lista = [];
        while ($row = $res->fetch_assoc()) {
            $lista[] = $row;
        }
        $stmt->close();
        return $lista;
    }

    public function buscarRecentes(int $limite = 200): array {
        $sql = "SELECT a.*, u.nome AS usuario_nome, c.titulo AS campeonato_titulo
                FROM campeonato_auditoria a
                LEFT JOIN campeonato_usuarios u ON a.id_usuario = u.id_usuario
                LEFT JOIN campeonato_eventos c ON a.id_campeonato = c.id_campeonato
                ORDER BY a.created_at DESC
                LIMIT ?";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        $res = $stmt->get_result();
        $lista = [];
        while ($row = $res->fetch_assoc()) {
            $lista[] = $row;
        }
        $stmt->close();
        return $lista;
    }
}
