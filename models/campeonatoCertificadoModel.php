<?php

class CampeonatoCertificadoModel {
    private mysqli $conexao;

    public function __construct() {
        $configCampeonatos = $this->lerConfiguracaoCampeonatos();
        $host = $this->valorConfig('CHAMPIONSHIP_DB_HOST', 'DB_HOST', $configCampeonatos, '127.0.0.1');
        $porta = (int)$this->valorConfig('CHAMPIONSHIP_DB_PORT', 'DB_PORT', $configCampeonatos, '3306');
        $banco = $this->valorConfig('CHAMPIONSHIP_DB_NAME', 'DB_NAME_CAMPEONATOS', $configCampeonatos, 'campeonatos');
        $usuario = $this->valorConfig('CHAMPIONSHIP_DB_USER', 'DB_USER', $configCampeonatos, '');
        $senha = $this->valorConfig('CHAMPIONSHIP_DB_PASS', 'DB_PASS', $configCampeonatos, '');

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $this->conexao = new mysqli($host, $usuario, $senha, $banco, $porta);
            $this->conexao->set_charset('utf8mb4');
        } catch (mysqli_sql_exception $erro) {
            error_log(
                'Falha ao conectar ao banco de certificados de campeonatos. '
                . 'SQLSTATE ' . $erro->getSqlState()
                . ', código ' . $erro->getCode()
                . ', mensagem: ' . $erro->getMessage()
            );
            throw new RuntimeException(
                'Os certificados de campeonatos estão temporariamente indisponíveis. '
                . 'Verifique se o banco de campeonatos está configurado e se o usuário possui permissão de leitura.',
                0,
                $erro
            );
        }
    }

    private function lerConfiguracaoCampeonatos(): array {
        $diretorioCampeonatos = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'campeonatos';
        foreach (['.env', '.env.development'] as $nomeArquivo) {
            $caminho = $diretorioCampeonatos . DIRECTORY_SEPARATOR . $nomeArquivo;
            if (!is_readable($caminho)) {
                continue;
            }

            $valores = [];
            foreach (file($caminho, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linha) {
                $linha = trim($linha);
                if ($linha === '' || $linha[0] === '#' || strpos($linha, '=') === false) {
                    continue;
                }
                [$chave, $valor] = explode('=', $linha, 2);
                $chave = trim($chave);
                if (in_array($chave, [
                    'DB_HOST',
                    'DB_PORT',
                    'DB_NAME_CAMPEONATOS',
                    'DB_USER',
                    'DB_PASS'
                ], true)) {
                    $valores[$chave] = trim(trim($valor), "\"'");
                }
            }
            return $valores;
        }
        return [];
    }

    private function valorConfig(
        string $variavelEspecifica,
        string $variavelCompartilhada,
        array $configCampeonatos,
        string $padrao
    ): string {
        $valor = getenv($variavelEspecifica);
        if ($valor !== false && $valor !== '') {
            return $valor;
        }
        if (isset($configCampeonatos[$variavelCompartilhada]) && $configCampeonatos[$variavelCompartilhada] !== '') {
            return $configCampeonatos[$variavelCompartilhada];
        }
        $valor = getenv($variavelCompartilhada);
        return $valor !== false && $valor !== '' ? $valor : $padrao;
    }

    public function buscarPorFiliado(int $idFiliado): array {
        $sql = "SELECT
                    c.id_certificado,
                    c.id_campeonato,
                    c.codigo_autenticidade,
                    c.data_emissao,
                    c.resultados_snapshot,
                    e.titulo AS campeonato_titulo,
                    e.data_inicio AS campeonato_data_inicio,
                    (
                        SELECT COUNT(DISTINCT cl.id_categoria)
                        FROM campeonato_classificacao cl
                        JOIN campeonato_inscricoes ci ON ci.id_inscricao = cl.id_inscricao
                        WHERE cl.id_campeonato = c.id_campeonato
                          AND ci.id_filiado = i.id_filiado
                    ) AS total_resultados
                FROM campeonato_certificados c
                JOIN campeonato_inscricoes i ON i.id_inscricao = c.id_inscricao
                JOIN campeonato_eventos e ON e.id_campeonato = c.id_campeonato
                WHERE i.id_filiado = ? AND e.status = 'finalizado'
                  AND NOT EXISTS (
                      SELECT 1
                      FROM campeonato_certificados c2
                      JOIN campeonato_inscricoes i2 ON i2.id_inscricao = c2.id_inscricao
                      WHERE c2.id_campeonato = c.id_campeonato
                        AND i2.id_filiado = i.id_filiado
                        AND (
                            c2.data_emissao > c.data_emissao
                            OR (c2.data_emissao = c.data_emissao AND c2.id_certificado > c.id_certificado)
                        )
                  )
                ORDER BY e.data_inicio DESC, c.data_emissao DESC";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $idFiliado);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $certificados = [];
        while ($linha = $resultado->fetch_assoc()) {
            if ($linha['resultados_snapshot'] !== null) {
                $snapshot = json_decode($linha['resultados_snapshot'], true, 512, JSON_THROW_ON_ERROR);
                if (!is_array($snapshot)) {
                    throw new UnexpectedValueException('O retrato dos resultados do certificado é inválido.');
                }
                $linha['total_resultados'] = count($snapshot);
            }
            unset($linha['resultados_snapshot']);
            $certificados[] = $linha;
        }
        $stmt->close();
        return $certificados;
    }

    public function buscarCertificadoDoFiliado(int $idCertificado, int $idFiliado): ?array {
        $sql = "SELECT
                    c.*,
                    i.id_filiado,
                    i.dojo_snapshot AS dojo,
                    e.titulo AS campeonato_titulo,
                    e.data_inicio AS campeonato_data_inicio,
                    e.data_fim AS campeonato_data_fim,
                    e.cidade AS campeonato_cidade,
                    e.estado AS campeonato_estado,
                    e.status AS campeonato_status
                FROM campeonato_certificados c
                JOIN campeonato_inscricoes i ON i.id_inscricao = c.id_inscricao
                JOIN campeonato_eventos e ON e.id_campeonato = c.id_campeonato
                WHERE c.id_certificado = ? AND i.id_filiado = ? AND e.status = 'finalizado'
                LIMIT 1";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("ii", $idCertificado, $idFiliado);
        $stmt->execute();
        $certificado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $certificado ?: null;
    }

    public function buscarResultados(int $idCampeonato, int $idFiliado, ?string $snapshot = null): array {
        if ($snapshot !== null) {
            $resultados = json_decode($snapshot, true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($resultados)) {
                throw new UnexpectedValueException('O retrato dos resultados do certificado é inválido.');
            }
            return $resultados;
        }

        $sql = "SELECT cat.nome AS categoria, cat.modalidade, cl.colocacao, cl.medalha
                FROM campeonato_classificacao cl
                JOIN campeonato_inscricoes i ON i.id_inscricao = cl.id_inscricao
                JOIN campeonato_categorias cat ON cat.id_categoria = cl.id_categoria
                WHERE cl.id_campeonato = ? AND i.id_filiado = ?
                ORDER BY cat.modalidade, cat.nome, cl.colocacao";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("ii", $idCampeonato, $idFiliado);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $classificacoes = [];
        while ($linha = $resultado->fetch_assoc()) {
            $classificacoes[] = $linha;
        }
        $stmt->close();
        return $classificacoes;
    }
}
