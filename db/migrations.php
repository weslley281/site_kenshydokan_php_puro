<?php
include_once "conexao.php";

class Migration
{
    private $conn;

    public function __construct()
    {
        $connDB = new Database();
        $this->conn = $connDB->getConnection();
    }

    public function criarTabelaUsuarios()
    {
        $sql = "

        CREATE TABLE IF NOT EXISTS usuarios (
            id_usuario int(11) NOT NULL,
            id_fil int(11) DEFAULT NULL,
            id_imagem int(11) DEFAULT NULL,
            nome VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            nivel VARCHAR(255) NOT NULL,
            telefone VARCHAR(255) NOT NULL,
            senha VARCHAR(300) NOT NULL,
            dataCriacao DATE,
            dataMudanca DATE
        );
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'usuarios' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de usuarios: " . $this->conn->error;
        }
    }

    public function criarTabelaPublicacao()
    {
        $sql = "

        CREATE TABLE IF NOT EXISTS postagens (
            id_publicacao INT AUTO_INCREMENT PRIMARY KEY,
            id_usuario INT,
            titulo VARCHAR(255) NOT NULL,
            conteudo TEXT,
            status VARCHAR(255) NOT NULL,
            dataCriacao DATE,
            dataMudanca DATE
        );
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'postagens' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de postagens: " . $this->conn->error;
        }
    }

    public function criarTabelaFiliados()
    {
        $sql = "

        CREATE TABLE IF NOT EXISTS filiados (
        `id_filiado` INT AUTO_INCREMENT PRIMARY KEY,
        `nome` VARCHAR(255) NOT NULL,
        `dojo` VARCHAR(255) NOT NULL,
        `telefone` VARCHAR(255) NOT NULL,
        `email` VARCHAR(255) NOT NULL,
        `endereco` VARCHAR(255),
        `cidade` VARCHAR(255),
        `id_estado` INT,
        `confirmacao` VARCHAR(255) NOT NULL,
        `dataCriacao` DATE,
        `dataMudanca` DATE
        );
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'filiados' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de filiados: " . $this->conn->error;
        }
    }

    public function criarTabelaGraduacoes()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS graduacoes (
            `id_graduacao` INT AUTO_INCREMENT PRIMARY KEY,
            `graduacao` VARCHAR(255) NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        );
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'graduacao' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de graduação: " . $this->conn->error;
        }
    }

    public function criarTabelaGaleria()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS galeria (
            `id_galeria` INT AUTO_INCREMENT PRIMARY KEY,
            `nome` VARCHAR(255) NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        );
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'galeria' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de galeria: " . $this->conn->error;
        }
    }

    public function criarTabelaFotos()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS fotos (
            `id_foto` INT AUTO_INCREMENT PRIMARY KEY,
            `id_galeria` INT NOT NULL,
            `nome` VARCHAR(255) NOT NULL,
            `foto` VARCHAR(255) NOT NULL,
            `dataUpload` DATE NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        );
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'fotos' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de fotos: " . $this->conn->error;
        }
    }

    public function criarTabelaCampeonatos()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS campeonatos (
            `id_campeonato` INT AUTO_INCREMENT PRIMARY KEY,
            `titulo` VARCHAR(255) NOT NULL,
            `subtitulo` VARCHAR(255) NOT NULL,
            `endereco` VARCHAR(255) NOT NULL,
            `ativo` VARCHAR(255) NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        );
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'campeonatos' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de campeonatos: " . $this->conn->error;
        }
    }

    public function criarTabelaImagens()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS imagens (
            `id_imagem` INT AUTO_INCREMENT PRIMARY KEY,
            `nome` VARCHAR(255) NOT NULL,
            `caminho` VARCHAR(255) NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        );
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'imagens' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de imagens: " . $this->conn->error;
        }
    }

    public function criarTabelaCursos()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS cursos (
            `id_curso` INT AUTO_INCREMENT PRIMARY KEY,
            `id_categoria` INT,
            `nome` VARCHAR(255) NOT NULL,
            `descricao` TEXT NOT NULL,
            `professor` VARCHAR(255) NOT NULL,
            `id_imagem` VARCHAR(255) NOT NULL,
            `cargaHoraria` INT(11) NOT NULL,
            `temCertificado` enum('sim','nao') NOT NULL DEFAULT 'nao',
            `situacao` enum('aprovado','aguardando','removido') NOT NULL DEFAULT 'aguardando',
            `dataCriacao` DATE,
            `dataMudanca` DATE
        );
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'cursos' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de cursos: " . $this->conn->error;
        }
    }

    public function criarTabelaAvaliacoes()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS avaliacoes (
            `id_avaliacao` INT AUTO_INCREMENT PRIMARY KEY,
            `id_curso` INT,
            `id_usuario` INT,
            `nota` INT,
            `comentario` VARCHAR(255) NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        );
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'avaliacoes' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de avaliacoes: " . $this->conn->error;
        }
    }

    public function criarTabelaAulas()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS aulas (
            `id_aula` INT AUTO_INCREMENT PRIMARY KEY,
            `id_curso` INT,
            `titulo` VARCHAR(255) NOT NULL,
            `aula` TEXT NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        );
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'aulas' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de aulas: " . $this->conn->error;
        }
    }

    public function criarTabelaCategorias()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS categorias (
            `id_categoria` INT AUTO_INCREMENT PRIMARY KEY,
            `categoria`  VARCHAR(255) NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        );
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'categorias' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de categorias: " . $this->conn->error;
        }
    }

    public function criarTabelaEstados()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS estados (
            `id_estado` INT AUTO_INCREMENT PRIMARY KEY,
            `estado` VARCHAR(255) NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        );
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'estados' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de estados: " . $this->conn->error;
        }
    }

    public function criarTabelaCertificados()
    {
        $sql = "

        CREATE TABLE IF NOT EXISTS certificados (
            `id_certificado` INT AUTO_INCREMENT PRIMARY KEY,
            `id_usuario` INT,
            `id_curso` INT,
            `percentual_conclusao_certificado` int(11) NOT NULL DEFAULT '100',
            `dataCriacao` DATE,
            `dataMudanca` DATE
        );
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'certificados' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de certificados: " . $this->conn->error;
        }
    }

    public function criarTabelaDojos()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS dojos (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `razao_social` varchar(255) DEFAULT NULL,
            `nome_fantasia` varchar(255) DEFAULT NULL,
            `cnpj` varchar(20) DEFAULT NULL,
            `id_filiado_responsavel` int(11) DEFAULT NULL,
            `telefone` varchar(20) DEFAULT NULL,
            `celular` varchar(20) DEFAULT NULL,
            `email` varchar(255) DEFAULT NULL,
            `cep` varchar(10) DEFAULT NULL,
            `endereco` varchar(255) DEFAULT NULL,
            `cidade` varchar(100) DEFAULT NULL,
            `estado` varchar(50) DEFAULT NULL,
            `data_filiacao` date DEFAULT NULL,
            `status` varchar(50) DEFAULT 'ativo',
            `imagem` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `fk_dojo_filiado_responsavel` (`id_filiado_responsavel`),
            CONSTRAINT `fk_dojo_filiado_responsavel` FOREIGN KEY (`id_filiado_responsavel`) REFERENCES `filiados` (`id_filiado`) ON DELETE SET NULL ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'dojos' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de dojos: " . $this->conn->error;
        }
    }

    public function criarTabelaDojoAlunosConfig()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS `dojo_alunos_config` (
          `id_filiado` INT NOT NULL,
          `valor_mensalidade` DECIMAL(10,2) NOT NULL DEFAULT 100.00,
          `dia_vencimento` INT NOT NULL DEFAULT 10,
          `status_aluno` VARCHAR(20) NOT NULL DEFAULT 'adimplente',
          PRIMARY KEY (`id_filiado`),
          FOREIGN KEY (`id_filiado`) REFERENCES `filiados` (`id_filiado`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'dojo_alunos_config' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de configurações de alunos: " . $this->conn->error;
        }
    }

    public function criarTabelaDojoMensalidades()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS `dojo_mensalidades` (
          `id` INT AUTO_INCREMENT PRIMARY KEY,
          `id_filiado` INT NOT NULL,
          `referencia` VARCHAR(7) NOT NULL,
          `valor` DECIMAL(10,2) NOT NULL,
          `data_vencimento` DATE NOT NULL,
          `data_pagamento` DATE NULL,
          `status_pagamento` VARCHAR(20) NOT NULL DEFAULT 'pendente',
          FOREIGN KEY (`id_filiado`) REFERENCES `filiados` (`id_filiado`) ON DELETE CASCADE,
          UNIQUE KEY `filiado_referencia` (`id_filiado`, `referencia`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'dojo_mensalidades' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de mensalidades: " . $this->conn->error;
        }
    }

    public function criarTabelaDojoFinanceiro()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS `dojo_financeiro` (
          `id` INT AUTO_INCREMENT PRIMARY KEY,
          `descricao` VARCHAR(255) NOT NULL,
          `tipo` VARCHAR(10) NOT NULL,
          `valor` DECIMAL(10,2) NOT NULL,
          `data_movimentacao` DATE NOT NULL,
          `categoria` VARCHAR(50) NOT NULL,
          `id_referencia` INT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'dojo_financeiro' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela do financeiro: " . $this->conn->error;
        }
    }

    public function criarTabelaCertificadosManuais()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS `certificados_manuais` (
          `id` INT AUTO_INCREMENT PRIMARY KEY,
          `id_filiado` INT NOT NULL,
          `titulo` VARCHAR(255) NOT NULL,
          `data_emissao` DATE NOT NULL,
          FOREIGN KEY (`id_filiado`) REFERENCES `filiados` (`id_filiado`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'certificados_manuais' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de certificados manuais: " . $this->conn->error;
        }
    }

    public function criarTabelaCertificadosUpload()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS `certificados_upload` (
          `id` INT AUTO_INCREMENT PRIMARY KEY,
          `id_filiado` INT NOT NULL,
          `titulo` VARCHAR(255) NOT NULL,
          `imagem` VARCHAR(255) NOT NULL,
          `data_upload` DATE NOT NULL,
          FOREIGN KEY (`id_filiado`) REFERENCES `filiados` (`id_filiado`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        if ($this->conn->query($sql) === true) {
            //echo "Tabela 'certificados_upload' criada com sucesso!";
        } else {
            echo "Erro ao criar tabela de certificados upload: " . $this->conn->error;
        }
    }


    public function criarTabelaArtesMarciais()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS artes_marciais (
            id_arte INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            dataCriacao DATE,
            dataMudanca DATE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        if ($this->conn->query($sql) === true) {
            // Verificar se tabela está vazia para inserir registros padrão
            $check = $this->conn->query("SELECT id_arte FROM artes_marciais LIMIT 1");
            if ($check && $check->num_rows == 0) {
                $insert = "
                INSERT INTO artes_marciais (nome, dataCriacao) VALUES 
                ('Karatê Kenshydokan', CURDATE()),
                ('Judô Kodokan', CURDATE()),
                ('Brazilian Jiu-Jitsu', CURDATE()),
                ('Muay Thai', CURDATE()),
                ('Kickboxing', CURDATE());
                ";
                $this->conn->query($insert);
            }
        } else {
            echo "Erro ao criar tabela artes_marciais: " . $this->conn->error;
        }
    }

    public function criarTabelaFiliadosGraduacoes()
    {
        // 1. Criar tabela associativa
        $sql = "
        CREATE TABLE IF NOT EXISTS filiados_graduacoes (
            id_filiado INT NOT NULL,
            id_arte INT NOT NULL,
            id_graduacao INT NOT NULL,
            PRIMARY KEY (id_filiado, id_arte),
            FOREIGN KEY (id_filiado) REFERENCES filiados(id_filiado) ON DELETE CASCADE,
            FOREIGN KEY (id_arte) REFERENCES artes_marciais(id_arte) ON DELETE RESTRICT,
            FOREIGN KEY (id_graduacao) REFERENCES graduacoes(id_graduacao) ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        if ($this->conn->query($sql) === true) {
            // 2. Verificar se a coluna id_graduacao ainda existe na tabela filiados (indica que não migramos ainda)
            $checkSql = "
                SELECT COLUMN_NAME 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() 
                  AND TABLE_NAME = 'filiados' 
                  AND COLUMN_NAME = 'id_graduacao'
            ";
            $res = $this->conn->query($checkSql);
            if ($res && $res->num_rows > 0) {
                // A coluna existe, vamos migrar os dados!
                // Primeiro, precisamos garantir que temos o id_arte do Karatê Kenshydokan
                $arteRes = $this->conn->query("SELECT id_arte FROM artes_marciais WHERE nome = 'Karatê Kenshydokan' LIMIT 1");
                if ($arteRes && $arteRes->num_rows > 0) {
                    $arteRow = $arteRes->fetch_assoc();
                    $id_arte = intval($arteRow['id_arte']);

                    // Migrar dados: inserir na tabela associativa onde id_graduacao é válido e não é 0
                    $migracaoSql = "
                        INSERT IGNORE INTO filiados_graduacoes (id_filiado, id_arte, id_graduacao)
                        SELECT id_filiado, {$id_arte}, id_graduacao 
                        FROM filiados 
                        WHERE id_graduacao IS NOT NULL AND id_graduacao != 0
                    ";
                    $this->conn->query($migracaoSql);
                }

                // Agora podemos remover com segurança a coluna
                $this->conn->query("ALTER TABLE filiados DROP COLUMN id_graduacao");
            }
        } else {
            echo "Erro ao criar tabela filiados_graduacoes: " . $this->conn->error;
        }
    }

    public function criarTabelaListaPresenca()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS lista_presenca (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_filiado INT NOT NULL,
            id_arte INT NOT NULL,
            data_presenca DATE NOT NULL,
            status CHAR(1) NOT NULL,
            conteudo_aula TEXT,
            FOREIGN KEY (id_filiado) REFERENCES filiados(id_filiado) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        if ($this->conn->query($sql) === true) {
            // Sucesso
        } else {
            echo "Erro ao criar tabela de lista_presenca: " . $this->conn->error;
        }
    }
}
?>
