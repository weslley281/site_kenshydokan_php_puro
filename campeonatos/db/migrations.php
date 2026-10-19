<?php
/**
 * Migrações do Banco de Dados de Campeonatos
 * Padrão IDÊNTICO ao site principal (db/migrations.php)
 */

include_once __DIR__ . "/conexao.php";

class Migration {

    private $conexao;

    public function __construct() {
        $db = new Conexao();
        $this->conexao = $db->conectar();
    }

    private function executarTabela(string $tabela, string $sql): void {
        try {
            $this->conexao->query($sql);
            Conexao::adicionarDiagnostico('Tabela processada', [
                'tabela' => $tabela,
                'status' => 'sucesso'
            ]);
        } catch (mysqli_sql_exception $erro) {
            Conexao::adicionarDiagnostico('Falha ao processar tabela', [
                'tabela' => $tabela,
                'erro' => $erro->getMessage(),
                'codigo' => $erro->getCode(),
                'sqlstate' => $erro->getSqlState()
            ]);
            throw $erro;
        }
    }

    private function adicionarColunaSeAusente(string $tabela, string $coluna, string $definicao): void {
        $stmt = $this->conexao->prepare(
            "SELECT COUNT(*) AS total
             FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?"
        );
        $stmt->bind_param("ss", $tabela, $coluna);
        $stmt->execute();
        $existe = (int)$stmt->get_result()->fetch_assoc()['total'] > 0;
        $stmt->close();

        if (!$existe) {
            $this->executarTabela(
                $tabela,
                "ALTER TABLE `{$tabela}` ADD COLUMN `{$coluna}` {$definicao}"
            );
        }
    }

    public function executarTodas(): void {
        $this->criarTabelaUsuarios();
        $this->criarTabelaCampeonatos();
        $this->criarTabelaCategorias();
        $this->criarTabelaInscricoes();
        $this->criarTabelaPesagens();
        $this->criarTabelaChaves();
        $this->criarTabelaTatames();
        $this->criarTabelaLutas();
        $this->criarTabelaSumulaKumite();
        $this->criarTabelaEventosKumite();
        $this->criarTabelaNotasKata();
        $this->criarTabelaTameshiwari();
        $this->criarTabelaClassificacao();
        $this->criarTabelaCertificados();
        $this->criarTabelaAuditoria();
    }

    public function criarTabelaUsuarios(): void {
        $sql = "CREATE TABLE IF NOT EXISTS campeonato_usuarios (
            id_usuario INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(150) NOT NULL,
            email VARCHAR(150) NOT NULL UNIQUE,
            senha VARCHAR(255) NOT NULL,
            nivel ENUM('admin', 'arbitro', 'mesario', 'leitor') NOT NULL DEFAULT 'mesario',
            ativo TINYINT(1) NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->executarTabela('campeonato_usuarios', $sql);
    }

    public function criarTabelaCampeonatos(): void {
        $sql = "CREATE TABLE IF NOT EXISTS campeonato_eventos (
            id_campeonato INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(200) NOT NULL,
            subtitulo VARCHAR(255) NULL,
            descricao TEXT NULL,
            edicao VARCHAR(50) NULL,
            ano INT NOT NULL,
            data_inicio DATE NOT NULL,
            data_fim DATE NOT NULL,
            data_pesagem DATE NULL,
            data_referencia_idade DATE NOT NULL,
            local_nome VARCHAR(200) NOT NULL,
            local_endereco TEXT NOT NULL,
            cidade VARCHAR(100) NOT NULL,
            estado VARCHAR(2) NOT NULL,
            status ENUM('rascunho', 'inscricoes_abertas', 'inscricoes_encerradas', 'pesagem', 'chaveamento', 'em_andamento', 'finalizado', 'cancelado') NOT NULL DEFAULT 'rascunho',
            banner_url VARCHAR(255) NULL,
            regulamento_pdf VARCHAR(255) NULL,
            limite_atletas INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->executarTabela('campeonato_eventos', $sql);
    }

    public function criarTabelaCategorias(): void {
        $sql = "CREATE TABLE IF NOT EXISTS campeonato_categorias (
            id_categoria INT AUTO_INCREMENT PRIMARY KEY,
            id_campeonato INT NOT NULL,
            nome VARCHAR(150) NOT NULL,
            modalidade ENUM('kumite', 'kata', 'tameshiwari') NOT NULL DEFAULT 'kumite',
            sexo ENUM('M', 'F', 'misto') NOT NULL,
            idade_min INT NOT NULL,
            idade_max INT NOT NULL,
            peso_min DECIMAL(5,2) NULL,
            peso_max DECIMAL(5,2) NULL,
            tolerancia_peso_kg DECIMAL(4,2) NOT NULL DEFAULT 0.00,
            graduacao_min_id INT NULL,
            graduacao_max_id INT NULL,
            duracao_luta_segundos INT NOT NULL DEFAULT 180,
            tipo_chaveamento ENUM('eliminatoria_simples', 'todos_contra_todos', 'notas_ranking') NOT NULL DEFAULT 'eliminatoria_simples',
            exige_pesagem TINYINT(1) NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->executarTabela('campeonato_categorias', $sql);
    }

    public function criarTabelaInscricoes(): void {
        $sql = "CREATE TABLE IF NOT EXISTS campeonato_inscricoes (
            id_inscricao INT AUTO_INCREMENT PRIMARY KEY,
            id_campeonato INT NOT NULL,
            id_categoria INT NOT NULL,
            id_filiado INT NOT NULL,
            nome_atleta_snapshot VARCHAR(150) NOT NULL,
            dojo_snapshot VARCHAR(150) NOT NULL,
            graduacao_id_snapshot INT NOT NULL,
            graduacao_nome_snapshot VARCHAR(100) NOT NULL,
            data_nascimento_snapshot DATE NOT NULL,
            idade_evento INT NOT NULL,
            sexo ENUM('M', 'F') NOT NULL,
            peso_declarado DECIMAL(5,2) NULL,
            status_elegibilidade ENUM('pendente', 'aprovada', 'reprovada', 'desclassificada') NOT NULL DEFAULT 'pendente',
            motivo_ineligibilidade TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->executarTabela('campeonato_inscricoes', $sql);
    }

    public function criarTabelaPesagens(): void {
        $sql = "CREATE TABLE IF NOT EXISTS campeonato_pesagens (
            id_pesagem INT AUTO_INCREMENT PRIMARY KEY,
            id_inscricao INT NOT NULL,
            id_campeonato INT NOT NULL,
            peso_aferido DECIMAL(5,2) NOT NULL,
            resultado ENUM('aprovado', 'reprovado') NOT NULL,
            tentativa INT NOT NULL DEFAULT 1,
            operador_id INT NOT NULL,
            observacoes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->executarTabela('campeonato_pesagens', $sql);
    }

    public function criarTabelaChaves(): void {
        $sql = "CREATE TABLE IF NOT EXISTS campeonato_chaves (
            id_chave INT AUTO_INCREMENT PRIMARY KEY,
            id_campeonato INT NOT NULL,
            id_categoria INT NOT NULL,
            tipo ENUM('eliminatoria_simples', 'todos_contra_todos') NOT NULL DEFAULT 'eliminatoria_simples',
            total_atletas INT NOT NULL,
            total_rodadas INT NOT NULL,
            arvore_dados JSON NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->executarTabela('campeonato_chaves', $sql);
    }

    public function criarTabelaTatames(): void {
        $sql = "CREATE TABLE IF NOT EXISTS campeonato_tatames (
            id_tatame INT AUTO_INCREMENT PRIMARY KEY,
            id_campeonato INT NOT NULL,
            numero INT NOT NULL,
            nome VARCHAR(100) NOT NULL,
            status ENUM('disponivel', 'em_luta', 'pausa') NOT NULL DEFAULT 'disponivel',
            luta_em_andamento_id INT NULL,
            operador_id INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->executarTabela('campeonato_tatames', $sql);
    }

    public function criarTabelaLutas(): void {
        $sql = "CREATE TABLE IF NOT EXISTS campeonato_lutas (
            id_luta INT AUTO_INCREMENT PRIMARY KEY,
            id_chave INT NOT NULL,
            id_campeonato INT NOT NULL,
            id_categoria INT NOT NULL,
            id_tatame INT NULL,
            numero_luta INT NOT NULL,
            rodada INT NOT NULL,
            posicao_na_chave INT NOT NULL,
            id_inscricao_aka INT NULL,
            id_inscricao_shiro INT NULL,
            id_vencedor INT NULL,
            forma_vitoria ENUM('pontos', 'ippon', 'golden_score', 'hantei', 'desclassificacao', 'desistencia', 'wo') NULL,
            status ENUM('pendente', 'chamada', 'em_andamento', 'concluida', 'cancelada') NOT NULL DEFAULT 'pendente',
            proxima_luta_id INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->executarTabela('campeonato_lutas', $sql);
    }

    public function criarTabelaSumulaKumite(): void {
        $sql = "CREATE TABLE IF NOT EXISTS campeonato_sumula_kumite (
            id_sumula INT AUTO_INCREMENT PRIMARY KEY,
            id_luta INT NOT NULL UNIQUE,
            tempo_decorrido_segundos INT NOT NULL DEFAULT 0,
            pontos_aka DECIMAL(3,1) NOT NULL DEFAULT 0.0,
            pontos_shiro DECIMAL(3,1) NOT NULL DEFAULT 0.0,
            wazaari_aka INT NOT NULL DEFAULT 0,
            wazaari_shiro INT NOT NULL DEFAULT 0,
            ippon_aka INT NOT NULL DEFAULT 0,
            ippon_shiro INT NOT NULL DEFAULT 0,
            shido_aka INT NOT NULL DEFAULT 0,
            shido_shiro INT NOT NULL DEFAULT 0,
            chui_aka INT NOT NULL DEFAULT 0,
            chui_shiro INT NOT NULL DEFAULT 0,
            saidas_aka INT NOT NULL DEFAULT 0,
            saidas_shiro INT NOT NULL DEFAULT 0,
            hansoku_make_aka TINYINT(1) NOT NULL DEFAULT 0,
            hansoku_make_shiro TINYINT(1) NOT NULL DEFAULT 0,
            golden_score_ativo TINYINT(1) NOT NULL DEFAULT 0,
            vencedor_id INT NULL,
            motivo_encerramento VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->executarTabela('campeonato_sumula_kumite', $sql);
    }

    public function criarTabelaEventosKumite(): void {
        $sql = "CREATE TABLE IF NOT EXISTS campeonato_eventos_kumite (
            id_evento INT AUTO_INCREMENT PRIMARY KEY,
            id_luta INT NOT NULL,
            segundo_luta INT NOT NULL,
            fase ENUM('regulamentar', 'golden_score') NOT NULL DEFAULT 'regulamentar',
            lado ENUM('aka', 'shiro', 'geral') NOT NULL,
            tipo_evento ENUM('ponto', 'penalidade', 'cronometro', 'decisao') NOT NULL,
            pontos_atribuidos DECIMAL(3,1) NOT NULL DEFAULT 0.0,
            descricao VARCHAR(255) NOT NULL,
            id_usuario_operador INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->executarTabela('campeonato_eventos_kumite', $sql);
    }

    public function criarTabelaNotasKata(): void {
        $sql = "CREATE TABLE IF NOT EXISTS campeonato_notas_kata (
            id_nota INT AUTO_INCREMENT PRIMARY KEY,
            id_campeonato INT NOT NULL,
            id_categoria INT NOT NULL,
            id_inscricao INT NOT NULL,
            rodada INT NOT NULL DEFAULT 1,
            is_desempate TINYINT(1) NOT NULL DEFAULT 0,
            kata_nome VARCHAR(100) NOT NULL,
            arbitro_ordem INT NOT NULL,
            arbitro_nome VARCHAR(100) NOT NULL,
            nota DECIMAL(4,2) NOT NULL,
            observacao VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->executarTabela('campeonato_notas_kata', $sql);
    }

    public function criarTabelaTameshiwari(): void {
        $sql = "CREATE TABLE IF NOT EXISTS campeonato_tameshiwari (
            id_tameshiwari INT AUTO_INCREMENT PRIMARY KEY,
            id_campeonato INT NOT NULL,
            id_categoria INT NOT NULL,
            id_inscricao INT NOT NULL,
            tecnica ENUM('seiken', 'shuto', 'hiji', 'chusoku') NOT NULL,
            material VARCHAR(50) NOT NULL DEFAULT 'madeira',
            qtd_declarada INT NOT NULL,
            qtd_quebrada INT NOT NULL DEFAULT 0,
            resultado ENUM('sucesso_total', 'falha_ou_parcial') NOT NULL,
            tempo_segundos INT NOT NULL DEFAULT 0,
            arbitro_responsavel VARCHAR(100) NOT NULL,
            observacoes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->executarTabela('campeonato_tameshiwari', $sql);
    }

    public function criarTabelaClassificacao(): void {
        $sql = "CREATE TABLE IF NOT EXISTS campeonato_classificacao (
            id_classificacao INT AUTO_INCREMENT PRIMARY KEY,
            id_campeonato INT NOT NULL,
            id_categoria INT NOT NULL,
            id_inscricao INT NOT NULL,
            colocacao INT NOT NULL,
            pontos_ranking INT NOT NULL DEFAULT 0,
            medalha ENUM('ouro', 'prata', 'bronze', 'nenhuma') NOT NULL DEFAULT 'nenhuma',
            homologado_por INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->executarTabela('campeonato_classificacao', $sql);
    }

    public function criarTabelaCertificados(): void {
        $sql = "CREATE TABLE IF NOT EXISTS campeonato_certificados (
            id_certificado INT AUTO_INCREMENT PRIMARY KEY,
            id_campeonato INT NOT NULL,
            id_inscricao INT NOT NULL,
            nome_titular VARCHAR(150) NOT NULL,
            tipo_certificado ENUM('participacao', 'campeao', 'arbitro', 'organizacao') NOT NULL DEFAULT 'participacao',
            codigo_autenticidade VARCHAR(64) NOT NULL UNIQUE,
            arquivo_pdf_nome VARCHAR(255) NOT NULL,
            data_emissao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->executarTabela('campeonato_certificados', $sql);
        $this->adicionarColunaSeAusente(
            'campeonato_certificados',
            'resultados_snapshot',
            'JSON NULL AFTER arquivo_pdf_nome'
        );
    }

    public function criarTabelaAuditoria(): void {
        $sql = "CREATE TABLE IF NOT EXISTS campeonato_auditoria (
            id_auditoria INT AUTO_INCREMENT PRIMARY KEY,
            id_campeonato INT NULL,
            id_usuario INT NULL,
            acao VARCHAR(100) NOT NULL,
            tabela_afetada VARCHAR(100) NULL,
            registro_id INT NULL,
            dados_antes JSON NULL,
            dados_depois JSON NULL,
            justificativa TEXT NULL,
            ip_address VARCHAR(45) NULL,
            user_agent TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->executarTabela('campeonato_auditoria', $sql);
    }
}
