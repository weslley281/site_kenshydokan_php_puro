<?php
// db/migrations.php
include_once __DIR__ . "/conexao.php";

class Migration
{
    private $conn;

    public function __construct()
    {
        $connDB = new Database();
        $this->conn = $connDB->getConnection();
    }

    public function executarTodas()
    {
        $this->criarTabelaEstados();
        $this->criarTabelaArtesMarciais();
        $this->criarTabelaGraduacoes();
        $this->criarTabelaImagens();
        $this->criarTabelaFiliados();
        $this->criarTabelaFiliadosGraduacoes();
        $this->criarTabelaDojos();
        $this->criarTabelaDojoAlunosConfig();
        $this->criarTabelaDojoMensalidades();
        $this->criarTabelaDojoFinanceiro();
        $this->criarTabelaListaPresenca();
        $this->criarTabelaExamesGraduacao();
        $this->criarTabelaUsuarios();
        $this->criarTabelaDojoRecorrencias();
    }

    private function criarTabelaEstados()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS estados (
            `id_estado` INT AUTO_INCREMENT PRIMARY KEY,
            `estado` VARCHAR(255) NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        if ($this->conn->query($sql) === true) {
            $check = $this->conn->query("SELECT id_estado FROM estados LIMIT 1");
            if ($check && $check->num_rows == 0) {
                $insert = "
                INSERT INTO estados (id_estado, estado, dataCriacao) VALUES 
                (1, 'Acre', CURDATE()),
                (2, 'Alagoas', CURDATE()),
                (3, 'Amapá', CURDATE()),
                (4, 'Amazonas', CURDATE()),
                (5, 'Bahia', CURDATE()),
                (6, 'Ceará', CURDATE()),
                (7, 'Distrito Federal', CURDATE()),
                (8, 'Espírito Santo', CURDATE()),
                (9, 'Goiás', CURDATE()),
                (10, 'Maranhão', CURDATE()),
                (11, 'Mato Grosso', CURDATE()),
                (12, 'Mato Grosso do Sul', CURDATE()),
                (13, 'Minas Gerais', CURDATE()),
                (14, 'Pará', CURDATE()),
                (15, 'Paraíba', CURDATE()),
                (16, 'Paraná', CURDATE()),
                (17, 'Pernambuco', CURDATE()),
                (18, 'Piauí', CURDATE()),
                (19, 'Rio de Janeiro', CURDATE()),
                (20, 'Rio Grande do Norte', CURDATE()),
                (21, 'Rio Grande do Sul', CURDATE()),
                (22, 'Rondônia', CURDATE()),
                (23, 'Roraima', CURDATE()),
                (24, 'Santa Catarina', CURDATE()),
                (25, 'São Paulo', CURDATE()),
                (26, 'Sergipe', CURDATE()),
                (27, 'Tocantins', CURDATE());
                ";
                $this->conn->query($insert);
            }
        }
    }

    private function criarTabelaArtesMarciais()
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
        }
    }

    private function criarTabelaGraduacoes()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS graduacoes (
            `id_graduacao` INT AUTO_INCREMENT PRIMARY KEY,
            `graduacao` VARCHAR(255) NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        if ($this->conn->query($sql) === true) {
            $check = $this->conn->query("SELECT id_graduacao FROM graduacoes LIMIT 1");
            if ($check && $check->num_rows == 0) {
                $insert = "
                INSERT INTO graduacoes (id_graduacao, graduacao, dataCriacao) VALUES
                (2, 'Faixa Branca 8º Kyu', CURDATE()),
                (3, 'Faixa Azul 7º Kyu', CURDATE()),
                (4, 'Faixa Amarela 6º Kyu', CURDATE()),
                (5, 'Faixa Vermelha 5º Kyu', CURDATE()),
                (6, 'Faixa Laranja 4º Kyu', CURDATE()),
                (7, 'Faixa Verde 3º Kyu', CURDATE()),
                (8, 'Faixa Roxa 2º Kyu', CURDATE()),
                (9, 'Faixa Marrom 1º Dan', CURDATE()),
                (10, 'Faixa Preta 1º Dan', CURDATE()),
                (11, 'Faixa Preta 2º Dan', CURDATE()),
                (12, 'Faixa Preta 3º Dan', CURDATE()),
                (13, 'Faixa Preta 4º Dan', CURDATE()),
                (14, 'Faixa Preta 5º Dan', CURDATE()),
                (15, 'Faixa Preta 6º Dan', CURDATE()),
                (16, 'Faixa Preta 7º Dan', CURDATE()),
                (17, 'Faixa Preta 8º Dan', CURDATE());
                ";
                $this->conn->query($insert);
            }
        }
    }

    private function criarTabelaImagens()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS imagens (
            `id_imagem` INT AUTO_INCREMENT PRIMARY KEY,
            `nome` VARCHAR(255) NOT NULL,
            `caminho` VARCHAR(255) NOT NULL,
            `dataCriacao` DATE,
            `dataMudanca` DATE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        $this->conn->query($sql);
    }

    private function criarTabelaFiliados()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS filiados (
            `id_filiado` INT AUTO_INCREMENT PRIMARY KEY,
            `codigo` VARCHAR(100) DEFAULT NULL,
            `nome` VARCHAR(255) NOT NULL,
            `dojo` VARCHAR(255) NOT NULL,
            `telefone` VARCHAR(255) NOT NULL,
            `dataNascimento` DATE DEFAULT NULL,
            `email` VARCHAR(255) NOT NULL,
            `endereco` VARCHAR(255) DEFAULT NULL,
            `cidade` VARCHAR(255) DEFAULT NULL,
            `id_estado` INT DEFAULT NULL,
            `confirmacao` VARCHAR(255) NOT NULL DEFAULT 'pendente',
            `dataCriacao` DATE,
            `dataMudanca` DATE,
            FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        if ($this->conn->query($sql) === true) {
            // Garante que a coluna dataNascimento exista se a tabela já existia antes
            $check = $this->conn->query("SHOW COLUMNS FROM filiados LIKE 'dataNascimento'");
            if ($check && $check->num_rows == 0) {
                $this->conn->query("ALTER TABLE filiados ADD COLUMN dataNascimento DATE DEFAULT NULL AFTER telefone");
            }
        }
    }

    private function criarTabelaFiliadosGraduacoes()
    {
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
        $this->conn->query($sql);
    }

    private function criarTabelaDojos()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS dojos (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `razao_social` VARCHAR(255) DEFAULT NULL,
            `nome_fantasia` VARCHAR(255) DEFAULT NULL,
            `cnpj` VARCHAR(20) DEFAULT NULL,
            `id_filiado_responsavel` INT DEFAULT NULL,
            `telefone` VARCHAR(20) DEFAULT NULL,
            `celular` VARCHAR(20) DEFAULT NULL,
            `email` VARCHAR(255) DEFAULT NULL,
            `cep` VARCHAR(10) DEFAULT NULL,
            `endereco` VARCHAR(255) DEFAULT NULL,
            `cidade` VARCHAR(100) DEFAULT NULL,
            `estado` VARCHAR(50) DEFAULT NULL,
            `data_filiacao` DATE DEFAULT NULL,
            `status` VARCHAR(50) DEFAULT 'ativo',
            `imagem` VARCHAR(255) DEFAULT NULL,
            FOREIGN KEY (`id_filiado_responsavel`) REFERENCES `filiados` (`id_filiado`) ON DELETE SET NULL ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        $this->conn->query($sql);
    }

    private function criarTabelaDojoAlunosConfig()
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
        $this->conn->query($sql);
    }

    private function criarTabelaDojoRecorrencias()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS `dojo_recorrencias` (
          `id` INT AUTO_INCREMENT PRIMARY KEY,
          `id_filiado` INT NOT NULL,
          `stripe_customer_id` VARCHAR(255) DEFAULT NULL,
          `stripe_subscription_id` VARCHAR(255) DEFAULT NULL,
          `valor` DECIMAL(10,2) NOT NULL,
          `status` VARCHAR(50) NOT NULL DEFAULT 'pendente',
          `termo_versao` VARCHAR(10) DEFAULT NULL,
          `ip_aceite` VARCHAR(45) DEFAULT NULL,
          `data_aceite` DATETIME DEFAULT NULL,
          `data_cancelamento` DATETIME DEFAULT NULL,
          FOREIGN KEY (`id_filiado`) REFERENCES `filiados` (`id_filiado`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        $this->conn->query($sql);
    }

    private function criarTabelaDojoMensalidades()
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
        $this->conn->query($sql);
    }

    private function criarTabelaDojoFinanceiro()
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
        $this->conn->query($sql);
    }

    private function criarTabelaListaPresenca()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS `lista_presenca` (
          `id` INT AUTO_INCREMENT PRIMARY KEY,
          `id_filiado` INT NOT NULL,
          `id_arte` INT NOT NULL,
          `data_presenca` DATE NOT NULL,
          `status` CHAR(1) NOT NULL DEFAULT 'P', -- 'P' (presente), 'F' (falta)
          `conteudo_aula` VARCHAR(255) DEFAULT NULL,
          FOREIGN KEY (`id_filiado`) REFERENCES `filiados` (`id_filiado`) ON DELETE CASCADE,
          FOREIGN KEY (`id_arte`) REFERENCES `artes_marciais` (`id_arte`) ON DELETE CASCADE,
          UNIQUE KEY `filiado_arte_data` (`id_filiado`, `id_arte`, `data_presenca`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        $this->conn->query($sql);
    }

    private function criarTabelaExamesGraduacao()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS `exames_graduacao` (
          `id` INT AUTO_INCREMENT PRIMARY KEY,
          `id_filiado` INT NOT NULL,
          `id_arte` INT NOT NULL,
          `id_graduacao_atual` INT NOT NULL,
          `id_graduacao_pretendida` INT NOT NULL,
          `nota` DECIMAL(4,2) DEFAULT NULL,
          `comentarios` TEXT DEFAULT NULL,
          `data_exame` DATE NOT NULL,
          `situacao` VARCHAR(20) NOT NULL DEFAULT 'pendente', -- 'aprovado', 'reprovado', 'pendente'
          FOREIGN KEY (`id_filiado`) REFERENCES `filiados` (`id_filiado`) ON DELETE CASCADE,
          FOREIGN KEY (`id_arte`) REFERENCES `artes_marciais` (`id_arte`) ON DELETE CASCADE,
          FOREIGN KEY (`id_graduacao_atual`) REFERENCES `graduacoes` (`id_graduacao`),
          FOREIGN KEY (`id_graduacao_pretendida`) REFERENCES `graduacoes` (`id_graduacao`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        $this->conn->query($sql);
    }

    private function criarTabelaUsuarios()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS usuarios (
            id_usuario INT AUTO_INCREMENT PRIMARY KEY,
            id_fil INT DEFAULT NULL,
            id_imagem INT DEFAULT NULL,
            nome VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            nivel VARCHAR(50) NOT NULL, -- sensei, sempai, kohai
            telefone VARCHAR(50) NOT NULL,
            senha VARCHAR(300) NOT NULL,
            primeiro_acesso TINYINT(1) DEFAULT 1,
            dataCriacao DATE,
            dataMudanca DATE,
            FOREIGN KEY (id_fil) REFERENCES filiados (id_filiado) ON DELETE SET NULL,
            FOREIGN KEY (id_imagem) REFERENCES imagens (id_imagem) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        if ($this->conn->query($sql) === true) {
            $check = $this->conn->query("SELECT id_usuario FROM usuarios LIMIT 1");
            if ($check && $check->num_rows == 0) {
                // Insere usuário Sensei administrativo padrão (senha: admin)
                $senhaHash = password_hash('admin', PASSWORD_DEFAULT);
                $insert = "
                INSERT INTO usuarios (nome, email, nivel, telefone, senha, primeiro_acesso, dataCriacao) 
                VALUES ('Sensei Geral', 'admin@dojo.com', 'sensei', '0000000000', '{$senhaHash}', 1, CURDATE());
                ";
                $this->conn->query($insert);
            }
        }
    }
}
?>
