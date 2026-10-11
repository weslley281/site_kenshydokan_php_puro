-- ==============================================================================
-- CARGA INICIAL (SEEDS) DO SISTEMA DE CAMPEONATOS KENSHYDOKAN JUTSU
-- BANCO DE DADOS: campeonatos
-- ==============================================================================

USE `campeonatos`;

-- 1. USUÁRIO ADMINISTRADOR PADRÃO
-- Senha inicial: Admin@Kenshy2026
INSERT INTO `campeonato_usuarios` (`id_usuario`, `nome`, `email`, `senha_hash`, `papel`, `status`)
VALUES 
(1, 'Administrador Kenshydokan', 'admin@kenshydokan.org.br', '$2y$12$fdjX22/57gqDRq9D78BZ8uqMXx9zyhqezXFXMlWThW34AMhR9dapC', 'admin', 'ativo')
ON DUPLICATE KEY UPDATE `nome` = VALUES(`nome`);

-- 2. CAMPEONATO EXEMPLO (OFICIAL)
INSERT INTO `campeonatos` (`id_campeonato`, `titulo`, `edicao`, `ano`, `local`, `cidade`, `estado`, `data_inicio`, `data_fim`, `data_referencia_idade`, `organizador`, `status`, `modalidades_habilitadas`)
VALUES
(1, '1° Campeonato Oficial Kenshydokan Jutsu 2026', '1ª Edição', 2026, 'Ginásio Poliesportivo Fiotão', 'Várzea Grande', 'MT', '2026-11-15', '2026-11-15', '2026-11-15', 'Instituto Kenshydokan de Artes Marciais', 'planejamento', 'kumite,kata,tameshiwari')
ON DUPLICATE KEY UPDATE `titulo` = VALUES(`titulo`);

-- 3. TATAMES DO EVENTO
INSERT INTO `campeonato_tatames` (`id_campeonato`, `numero`, `nome`, `status`)
VALUES
(1, 1, 'Tatame 1 - Principal', 'disponivel'),
(1, 2, 'Tatame 2 - Apoio', 'disponivel')
ON DUPLICATE KEY UPDATE `nome` = VALUES(`nome`);

-- 4. CATEGORIAS MODELO
INSERT INTO `campeonato_categorias` (`id_campeonato`, `modalidade`, `nome`, `sexo`, `idade_min`, `idade_max`, `peso_min`, `peso_max`, `graduacao_inicio_id`, `graduacao_fim_id`, `nivel_competitivo`, `exige_pesagem`)
VALUES
-- Kumite Infantil & Juvenil (< 14 anos com protetor de tórax e caneleiras)
(1, 'kumite', 'Kumite Infantil Misto (Até 11 anos)', 'misto', 6, 11, NULL, 45.00, 2, 8, 'Iniciante/Intermediário', 1),
(1, 'kumite', 'Kumite Juvenil Masculino (12 a 13 anos)', 'M', 12, 13, NULL, 55.00, 2, 10, 'Geral', 1),
-- Kumite Adulto (>= 14 anos sem protetor de tórax nem caneleira)
(1, 'kumite', 'Kumite Adulto Masculino Leve (-70kg)', 'M', 18, 35, 50.00, 70.00, 2, 20, 'Geral', 1),
(1, 'kumite', 'Kumite Adulto Masculino Médio (-80kg)', 'M', 18, 35, 70.01, 80.00, 2, 20, 'Geral', 1),
(1, 'kumite', 'Kumite Adulto Masculino Pesado (+80kg)', 'M', 18, 35, 80.01, 130.00, 2, 20, 'Geral', 1),
(1, 'kumite', 'Kumite Adulto Feminino Aberto', 'F', 18, 35, 45.00, 100.00, 2, 20, 'Geral', 1),
(1, 'kumite', 'Kumite Master Masculino (+35 anos)', 'M', 36, 65, NULL, NULL, 2, 20, 'Master', 0),
-- Kata (universalidade de estilos)
(1, 'kata', 'Kata Infantil Misto (< 14 anos)', 'misto', 6, 13, NULL, NULL, 2, 10, 'Base', 0),
(1, 'kata', 'Kata Adulto Geral (>= 14 anos)', 'misto', 14, 99, NULL, NULL, 2, 20, 'Geral', 0),
-- Tameshiwari (quebramento 4 oficinas)
(1, 'tameshiwari', 'Tameshiwari Adulto Masculino Oficial', 'M', 18, 99, NULL, NULL, 7, 20, 'Avançado/Mestre', 0);
