-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 17-Jun-2021 às 23:27
-- Versão do servidor: 10.4.19-MariaDB-cll-lve
-- versão do PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `u696382984_kenshydokan`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `adms`
--

CREATE TABLE `adms` (
  `id_adm` int(11) NOT NULL,
  `email` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `nome` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `sobrenome` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `senha` varchar(300) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `adms`
--

INSERT INTO `adms` (`id_adm`, `email`, `nome`, `sobrenome`, `senha`) VALUES
(2, 'weslleyhenrique800@gmail.com', 'Weslley', 'Ferraz', '$2y$10$QHnkoUa3PyqR/UvzLVP9HOlyFjPhE.DxgwgJdoflu5B69z7cIMAVa');

-- --------------------------------------------------------

--
-- Estrutura da tabela `aulas`
--

CREATE TABLE `aulas` (
  `id_aula` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `titulo` varchar(300) NOT NULL,
  `link` varchar(300) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `aulas`
--

INSERT INTO `aulas` (`id_aula`, `id_curso`, `titulo`, `link`) VALUES
(8, 7, 'Como iniciar a Aula', 'https://drive.google.com/file/d/1cnCuU5vcKzfVa2OPoxRJymiFq9PX_sG-/view'),
(9, 7, 'Como se portar no tatame', 'Em breve'),
(10, 12, 'Aula 1', 'https://1drv.ms/u/s!AjCLaHewcp0zjrQQdwBMgcLf745C-w?e=jWmVOR');

-- --------------------------------------------------------

--
-- Estrutura da tabela `campeonatos`
--

CREATE TABLE `campeonatos` (
  `id_camp` int(11) NOT NULL,
  `titulo` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subtitulo` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `endereco` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` date DEFAULT NULL,
  `ativo` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `campeonatos`
--

INSERT INTO `campeonatos` (`id_campeonato`, `titulo`, `subtitulo`, `endereco`, `dataCriacao`, `ativo`) VALUES
(4, 'XXVIII Campeonato Paulista de Karate Do FBKK.', 'Campeonato de Karate', 'No Ginasio Municipal Pintasilgo. IV - Avenida Soldado Policia Militar Gilberto Augustinho 948 - Jardim Hitoshi. Itacepecira da Serra - SP.', '2019-06-22', 'não'),
(3, '1° Copa Mas Oyama de Karate de Contato', 'Kumite, Kata e Quebramentos.', 'Avenida Iara no Jardin Glória 2 no ginasio ao lado do mercado Gama.', '2018-05-20', 'não'),
(5, '1° Open de Karate Kyokushinkai', 'Campeonato de Karate', 'Campo Grande - MS Avenida Marinha 725 08:00 h da manhã', '2018-06-17', 'não'),
(6, 'Torneo Internacional karate Contacto', 'Campeonato de Karate', 'Gimnasio Municipal la Florida Alonso de Ercilla 1276 - Chile VI Campeonato Paulista', '2018-07-02', 'não'),
(7, 'Seishin Kyokushin Open Karate Full Contact 2018', 'Campeonato de Karate', 'Ginásio de Esportes ', '2018-08-19', 'não'),
(8, 'VI Campeonato Estadual de Karate de Contato Kenshydokan', 'Campeonato de Karate', 'No Várzea Grande Shopping Endereço: Av. Presidente Artur Bernardes, 43 - Centro Sul, Várzea Grande - MT, 78125-905', '2018-09-15', 'não'),
(9, 'VII Campeonato Brasileiro Seshin Kyokushin Open Karate Full Contact 2019', 'Campeonato de Karate', 'Ginásio de Esportes Eden Rua Salvador Leite Marques, 1030 - Aden - Sorocaba/SP', '2019-08-04', 'não'),
(10, 'VII Campeonato Estadual de Karate de Contato Kenshydokan', 'Campeonato de Karate', 'No Várzea Grande Shopping Endereço: Av. Presidente Artur Bernardes, 43 - Centro Sul, Várzea Grande - MT, 78125-905', '2019-09-29', 'não');

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `categoria` varchar(300) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `categoria`) VALUES
(2, 'Karate'),
(3, 'Judô Kodokan'),
(4, 'Ju Jitsu'),
(5, 'Muay Thai'),
(6, 'KickBoxing');

-- --------------------------------------------------------

--
-- Estrutura da tabela `curso`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `cargaHoraria` int(11) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `situacao` enum('aprovado','aguardando','removido') NOT NULL DEFAULT 'aguardando',
  `dataMudanca` date DEFAULT NULL,
  `dataCriacao` date DEFAULT NULL,
  `temCertificado` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `percentual_conclusao_certificado` int(11) NOT NULL DEFAULT 100,
  `professor` varchar(255) NOT NULL,
  `id_imagem` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `curso`
--



-- --------------------------------------------------------

--
-- Estrutura da tabela `estados`
--

CREATE TABLE `estados` (
  `id_estado` int(11) NOT NULL,
  `estado` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `estados`
--

INSERT INTO `estados` (`id_estado`, `estado`) VALUES
(1, 'Acre'),
(2, 'Alagoas'),
(3, 'Amapá'),
(4, 'Amazonas'),
(5, 'Bahia'),
(6, 'Ceará'),
(7, 'Distrito Federal'),
(8, 'Espírito Santo'),
(9, 'Goiás'),
(10, 'Maranhão'),
(11, 'Mato Grosso'),
(12, 'Mato Grosso do Sul'),
(13, 'Minas Gerais'),
(14, 'Pará'),
(15, 'Paraíba'),
(16, 'Paraná'),
(17, 'Pernambuco'),
(18, 'Piauí'),
(19, 'Rio de Janeiro'),
(20, 'Rio Grande do Norte'),
(21, 'Rio Grande do Sul'),
(22, 'Rondônia'),
(23, 'Roraima'),
(24, 'Santa Catarina'),
(25, 'São Paulo'),
(26, 'Sergipe'),
(27, 'Tocantins');

-- --------------------------------------------------------

--
-- Estrutura da tabela `filiados`
--

CREATE TABLE `filiados` (
  `id_filiado` int(11) NOT NULL,
  `id_graduacao` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `dojo` varchar(100) DEFAULT NULL,
  `telefone` varchar(100) DEFAULT NULL,
  `rg` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `id_estado` int(11) NOT NULL,
  `confirmacao` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `filiados`
--

INSERT INTO `filiados` (`id_filiado`, `id_graduacao`, `nome`, `dojo`, `telefone`, `rg`, `email`, `endereco`, `cidade`, `id_estado`, `confirmacao`) VALUES
(14, 20, 'Jonas Teixeira de Andrade', 'kenshydokan', '(65) 99293-2986', '12345', 'kenshydokan@gmail.com', 'vg', 'VÃ¡rzea Grande', 11, 'sim'),
(15, 14, 'Reinaldo Jose Gomes', 'Kenshydokan', '123456', '1234567', 'naosei@gmail.com', '', '', 25, 'sim'),
(17, 13, 'Mauro Pellegrini do Amaral Trigo', 'Kenshydokan', '12345', '12345', 'weslleyhenrique800@hotmail.com', '', '', 11, 'sim'),
(20, 12, 'Everson Jones Batista Leite', 'Kenshydokan', '(65) 99936-6510', '20751117', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(21, 10, 'Patrick jordhan dos Santos', 'Kenshydokan', '(65) 99208-1481', '123', 'naosei@gmail.com', 'aaaaaa', 'Várzea Grande', 11, 'sim'),
(22, 10, 'Elyakin Vinicius Mettelo', 'Kenshydokan', '(65) 9990-31993', '23693916', 'naosei@gmail.com', '', '', 11, 'sim'),
(23, 10, 'Weslley Henrique Vieira Ferraz', 'Kenshydokan', '(65) 98123-3996', '2506499-1', 'weslleyhenrique800@gmail.com', 'Av. Castelo Branco, 754', 'VÃ¡rzea Grande', 11, 'sim'),
(24, 8, 'Marcelo Francisco de Campos', 'Kenshydokan', '12345', '16568397', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(25, 8, 'Rafael Carlos de Almeida Faria', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(26, 8, 'Orlando Manoel da Silva', 'Kenshydokan', '12345', '35812', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(27, 8, 'Matheus Henrique Campos Silva', 'Kenshydokan', '12345', '274371107', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(28, 8, 'Douglas Giovani de Campos', 'Kenshydokan', '(65) 98401-0825', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(29, 8, 'Erick Weslley Bridi', 'Kenshydokan', '12345', '3198690-0', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(30, 7, 'Ryan Jackson Silva Prado', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(31, 6, 'José Matheus Leite Martins', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(32, 5, 'Fernanda Surd Silva', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(33, 5, 'Roset de Almeida Lobo', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(34, 5, 'Emanuel Cristhian C da Cruz', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(35, 5, 'Herique Grabriel Brid', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(36, 5, 'Lethycia França de Melo', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(37, 4, 'Carlos Eduardo da Silva Alves', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(38, 4, 'Giovanna Neves da Silva', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(39, 4, 'Lucas Gabriel Costa', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(40, 3, 'João Gabriel Dutra Andrade', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, '', 0, 'sim'),
(41, 3, 'Gabriel Jaivona da Silva', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(42, 3, 'Daianny Amabilly da Silva Gomes dos', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(43, 3, 'Luis Henrique Germano', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(44, 3, 'Luis Henrique Rodrigues dos Santos ', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(45, 3, 'Luis Henrique de Arruda Stropa', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(46, 3, 'Pedro Uarley Oliveira Florêncio Nas', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(47, 3, 'Flavio Paixão de Alencar Junior', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(48, 3, 'Heron Jones Figueiredo Leite', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(49, 3, 'Davi Pereira dos Santos', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(50, 3, 'João Victor da conceição', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(51, 3, 'Nicolly Winy Sena de Oliveira', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(52, 3, 'Welberth Domingos S. de O.', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(53, 3, 'Alvaro Guilherme S. Ramos', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(54, 3, 'João Vitor Curvo Gomes', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(55, 3, 'Kauã Luigi Cinadon', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(56, 3, 'Kamili Vitoria S. Marquezin', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', '', '', 0, 'sim'),
(57, 3, 'Lucas Bohr de Araujo', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', '', '', 0, 'sim'),
(58, 3, 'Sophia Pinheiro de Souza', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(59, 3, 'Bruno Duarte da Silva', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(60, 3, 'Lucas Cassio Pinho dos Santos', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(61, 3, 'Davi Gabriel do Amaral Moraes', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(62, 3, 'Ana clara Souza leite', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(63, 3, 'Leandro Paulo Santana Silva', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(64, 3, 'Victor Garcia Rodrigues', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(65, 3, 'George Garcia Rodrigues', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(66, 3, 'Erick Gabriel Medeiros Amaral', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(67, 3, 'Gabriel Souza Bordin', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(68, 3, 'Lucas Ferreira Matos', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(69, 3, 'Kauã Tiago da Silva Magalhães', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(70, 3, 'Rayanna Victoria de Souza Rodrigues', 'Kenshydokan', '12345', '1234567', 'naosei@gmail.com', NULL, NULL, 11, 'sim'),
(78, 3, 'weslley gostoso', 'Quebra Dentes', '123', '64444614', 'weslleyhenrique800@hotmail.com', 'Rua Castelo Branco 123', 'VÃ¡rzea Grande', 11, 'nao'),
(79, 10, 'Murilo Cardoso de Resende', 'Warriors', '12345', '2821781-0', 'naosei@gmail.com', 'aaaa', 'aaaa', 11, 'sim'),
(81, 16, 'Valbson Jorge Teixeira dos Santos', 'Gladiadores Kyokushin Karatê', '91987259549', '2477686', 'valbson.jorge@gmail.com', 'Conjunto Satelite Teve', 'Belém', 14, 'sim'),
(82, 12, 'Alex de Melo Garcia', 'Kenshydokan', '123', '1517035', 'naosei@gmail.com', 'aaaaaaaaaaa', 'aaaaaa', 11, 'sim');

-- --------------------------------------------------------

--
-- Estrutura da tabela `fotos`
--

CREATE TABLE `fotos` (
  `id_foto` int(11) NOT NULL,
  `id_galeria` int(11) NOT NULL,
  `nome` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dataUpload` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `fotos`
--

INSERT INTO `fotos` (`id_foto`, `id_galeria`, `nome`, `foto`, `dataUpload`) VALUES
(5, 4, '', 'open-03.jpeg', '2020-08-26'),
(6, 4, '', 'open-02.jpeg', '2020-08-26'),
(7, 4, '', 'open-01.jpeg', '2020-08-26'),
(8, 4, '', 'open-04.jpeg', '2020-08-26'),
(9, 4, '', 'open-06.jpeg', '2020-08-26'),
(10, 4, '', 'open-07.jpeg', '2020-08-26'),
(12, 4, '', 'open-09.jpeg', '2020-08-30'),
(13, 4, '', 'open-11.jpeg', '2020-08-30'),
(14, 4, '', 'open-12.jpeg', '2020-08-30'),
(15, 4, '', 'open-14.jpeg', '2020-08-30'),
(16, 4, '', 'open-15.jpeg', '2020-08-30'),
(17, 4, '', 'open-16.jpeg', '2020-08-30'),
(18, 4, '', 'open-18.jpeg', '2020-08-30'),
(19, 4, '', 'open-19.jpeg', '2020-08-30'),
(20, 4, '', 'open-20.jpeg', '2020-08-30'),
(21, 4, '', 'open-23.jpeg', '2020-08-30'),
(22, 4, '', 'open-30.jpeg', '2020-08-30'),
(23, 4, '', 'open-31.jpeg', '2020-08-30'),
(24, 4, '', 'open-32.jpeg', '2020-08-30'),
(25, 4, '', 'open-33.jpeg', '2020-08-30'),
(26, 4, '', 'open-34.jpeg', '2020-08-30'),
(34, 4, '', 'open-05.jpeg', '2020-08-31'),
(35, 2, '', 'copa-01.jpg', '2020-09-16'),
(36, 2, '', 'copa-02.jpg', '2020-09-16'),
(37, 2, '', 'copa-03.jpg', '2020-09-16'),
(38, 2, '', 'copa-04.jpg', '2020-09-16'),
(39, 2, '', 'copa-05.jpg', '2020-09-16'),
(40, 2, '', 'copa-06.jpg', '2020-09-16'),
(41, 2, '', 'copa-07.jpg', '2020-09-16'),
(42, 2, '', 'copa-08.jpg', '2020-09-16'),
(43, 2, '', 'copa-09.jpg', '2020-09-16'),
(44, 2, '', 'copa-10.jpg', '2020-09-16'),
(45, 2, '', 'copa-11.jpg', '2020-09-16'),
(46, 2, '', 'copa-12.jpg', '2020-09-16'),
(47, 2, '', 'copa-13.jpg', '2020-09-16'),
(48, 2, '', 'copa-14.jpg', '2020-09-16'),
(49, 2, '', 'copa-15.jpg', '2020-09-16'),
(50, 2, '', 'copa-16.jpg', '2020-09-16'),
(51, 2, '', 'copa-17.jpg', '2020-09-16'),
(52, 2, '', 'copa-18.jpg', '2020-09-16'),
(53, 2, '', 'copa-19.jpg', '2020-09-16'),
(54, 2, '', 'copa-20.jpg', '2020-09-16'),
(55, 2, '', 'copa-21.jpg', '2020-09-16'),
(56, 2, '', 'copa-22.jpg', '2020-09-16'),
(57, 2, '', 'copa-23.jpg', '2020-09-16'),
(58, 5, '', 'estadual 1.jpg', '2020-09-17'),
(59, 5, '', 'estadual 2.jpg', '2020-09-17'),
(60, 5, '', 'estadual 3.jpg', '2020-09-17'),
(61, 5, '', 'estadual 4.jpg', '2020-09-17'),
(62, 5, '', 'estadual 5.jpg', '2020-09-17'),
(63, 5, '', 'estadual 6.jpg', '2020-09-17'),
(64, 5, '', 'estadual 07.jpg', '2020-09-17'),
(65, 5, '', 'estadual 08.jpg', '2020-09-17'),
(66, 5, '', 'estadual 09.jpg', '2020-09-17'),
(67, 5, '', 'estadual 10.jpg', '2020-09-17'),
(68, 5, '', 'estadual 11.jpg', '2020-09-17'),
(69, 5, '', 'estadual 12.jpg', '2020-09-17'),
(70, 5, '', 'estadual 13.jpg', '2020-09-17'),
(71, 5, '', 'estadual 14.jpg', '2020-09-17'),
(72, 5, '', 'estadual 15.jpg', '2020-09-17'),
(73, 5, '', 'estadual 16.jpg', '2020-09-17'),
(74, 5, '', 'estadual 17.jpg', '2020-09-17'),
(75, 5, '', 'estadual 18.jpg', '2020-09-17'),
(76, 5, '', 'estadual 19.jpg', '2020-09-17'),
(77, 5, '', 'estadual 20.jpg', '2020-09-17'),
(78, 5, '', 'estadual 21.jpg', '2020-09-17'),
(79, 5, '', 'estadual 22.jpg', '2020-09-17'),
(80, 5, '', 'estadual 23.jpg', '2020-09-17'),
(81, 5, '', 'estadual 24.jpg', '2020-09-17'),
(82, 5, '', 'estadual 25.jpg', '2020-09-17'),
(83, 5, '', 'estadual 26.jpg', '2020-09-17'),
(84, 5, '', 'estadual 27.jpg', '2020-09-17'),
(85, 5, '', 'estadual 28.jpg', '2020-09-17'),
(86, 5, '', 'estadual 29.jpg', '2020-09-17'),
(87, 5, '', 'estadual 30.jpg', '2020-09-17'),
(88, 5, '', 'estadual 31.jpg', '2020-09-18'),
(89, 5, '', 'estadual 32.jpg', '2020-09-18'),
(90, 5, '', 'estadual 33.jpg', '2020-09-18'),
(91, 5, '', 'estadual 34.jpg', '2020-09-18'),
(92, 5, '', 'estadual 35.jpg', '2020-09-18'),
(93, 5, '', 'estadual 36.jpg', '2020-09-18'),
(94, 5, '', 'estadual 37.jpg', '2020-09-18'),
(95, 5, '', 'estadual 38.jpg', '2020-09-18'),
(96, 5, '', 'estadual 39.jpg', '2020-09-18'),
(97, 5, '', 'estadual 40.jpg', '2020-09-18'),
(98, 5, '', 'estadual 41.jpg', '2020-09-18'),
(99, 5, '', 'estadual 42.jpg', '2020-09-18'),
(100, 5, '', 'estadual 43.jpg', '2020-09-18'),
(101, 5, '', 'estadual 44.jpg', '2020-09-18'),
(102, 5, '', 'estadual 45.jpg', '2020-09-18'),
(103, 5, '', 'estadual 46.jpg', '2020-09-18'),
(104, 5, '', 'estadual 47.jpg', '2020-09-18'),
(105, 5, '', 'estadual 48.jpg', '2020-09-18'),
(106, 5, '', 'estadual 49.jpg', '2020-09-18'),
(107, 5, '', 'estadual 50.jpg', '2020-09-18'),
(112, 5, '', 'estadual 51.jpg', '2020-09-27'),
(113, 5, '', 'estadual 52.jpg', '2020-09-27'),
(114, 5, '', 'estadual 53.jpg', '2020-09-27'),
(115, 5, '', 'estadual 54.jpg', '2020-09-27'),
(116, 5, '', 'estadual 55.jpg', '2020-09-27'),
(117, 5, '', 'estadual 56.jpg', '2020-09-27'),
(118, 5, '', 'estadual 57.jpg', '2020-09-27'),
(119, 5, '', 'estadual 58.jpg', '2020-09-27'),
(120, 5, '', 'estadual 59.jpg', '2020-09-27'),
(121, 5, '', 'estadual 60.jpg', '2020-09-27'),
(122, 5, '', 'estadual 70.jpg', '2020-09-27'),
(123, 5, '', 'estadual 61.jpg', '2020-09-27'),
(124, 5, '', 'estadual 62.jpg', '2020-09-27'),
(125, 5, '', 'estadual 63.jpg', '2020-09-27'),
(126, 5, '', 'estadual 64.jpg', '2020-09-27'),
(127, 5, '', 'estadual 65.jpg', '2020-09-27'),
(128, 5, '', 'estadual 66.jpg', '2020-09-27'),
(129, 5, '', 'estadual 67.jpg', '2020-09-27'),
(130, 5, '', 'estadual 68.jpg', '2020-09-27'),
(131, 5, '', 'estadual 69.jpg', '2020-09-27'),
(132, 5, '', 'estadual 71.jpg', '2020-09-27'),
(133, 5, '', 'estadual 72.jpg', '2020-09-27'),
(134, 5, '', 'estadual 73.jpg', '2020-09-27'),
(135, 5, '', 'estadual 74.jpg', '2020-09-27'),
(136, 5, '', 'estadual 75.jpg', '2020-09-27'),
(137, 5, '', 'estadual 76.jpg', '2020-09-27'),
(138, 5, '', 'estadual 77.jpg', '2020-09-27'),
(139, 5, '', 'estadual 78.jpg', '2020-09-27'),
(140, 5, '', 'estadual 79.jpg', '2020-09-27'),
(141, 5, '', 'estadual 80.jpg', '2020-09-27'),
(142, 5, '', 'estadual 91.jpg', '2020-09-27'),
(143, 5, '', 'estadual 81.jpg', '2020-09-27'),
(144, 5, '', 'estadual 82.jpg', '2020-09-27'),
(145, 5, '', 'estadual 83.jpg', '2020-09-27'),
(146, 5, '', 'estadual 84.jpg', '2020-09-27'),
(147, 5, '', 'estadual 85.jpg', '2020-09-27'),
(148, 5, '', 'estadual 86.jpg', '2020-09-27'),
(149, 5, '', 'estadual 87.jpg', '2020-09-27'),
(150, 5, '', 'estadual 88.jpg', '2020-09-27'),
(151, 5, '', 'estadual 89.jpg', '2020-09-27'),
(152, 5, '', 'estadual 90.jpg', '2020-09-27'),
(153, 5, '', 'estadual 90.jpg', '2020-09-27'),
(154, 5, '', 'estadual 92.jpg', '2020-09-27'),
(156, 2, '', '40438206280_21da941d65_k.jpg', '2020-09-27'),
(157, 2, '', '40438274580_d16e9b6fc1_k.jpg', '2020-09-27'),
(158, 2, '', '40438428110_2dcd20a384_k.jpg', '2020-09-27'),
(159, 2, '', '40438505510_d46dab96a2_k.jpg', '2020-09-27'),
(160, 2, '', '40438782840_9e6f2918b4_m.jpg', '2020-09-27'),
(161, 2, '', '40438782840_acbe5a87b2_k.jpg', '2020-09-27'),
(162, 2, '', '40438808210_9db79cbcb5_k.jpg', '2020-09-27'),
(163, 2, '', '40439125130_fe73c109bf_k.jpg', '2020-09-27'),
(164, 2, '', '40439168270_df0e412b36_k.jpg', '2020-09-27'),
(165, 2, '', '40439191860_7e0e63fb03_k.jpg', '2020-09-27'),
(166, 2, '', '40439216370_1d77a73a46_k.jpg', '2020-09-27'),
(167, 2, '', '40439419880_6b87b62d31_k.jpg', '2020-09-27'),
(168, 2, '', '40439461550_42a16b0141_k.jpg', '2020-09-27'),
(169, 2, '', '40439513500_d2c7cc10b4_k.jpg', '2020-09-27'),
(170, 2, '', '40439572250_1a14fe7b6f_k.jpg', '2020-09-27'),
(171, 2, '', '41343938905_35ed2f5279_k.jpg', '2020-09-27'),
(172, 2, '', '41344344355_c535d2433e_k.jpg', '2020-09-27'),
(173, 2, '', '41345036435_a8852b4a76_k.jpg', '2020-09-27'),
(174, 2, '', '41345615525_4ecfc324a7_k.jpg', '2020-09-27'),
(175, 2, '', '41345815905_28bd287d00_k.jpg', '2020-09-27'),
(176, 2, '', '41346210405_977e53a884_k.jpg', '2020-09-27'),
(177, 2, '', '41346299095_293c7ecb6c_k.jpg', '2020-09-27'),
(178, 2, '', '41523773914_93d0dc2560_k.jpg', '2020-09-27'),
(179, 2, '', '41523773914_93d0dc2560_k.jpg', '2020-09-27'),
(180, 2, '', '41523897254_8bf96a4a7b_k.jpg', '2020-09-27'),
(181, 2, '', '41524117924_c7e38ff9e0_k.jpg', '2020-09-27'),
(182, 2, '', '41524462624_4947c2a45a_k.jpg', '2020-09-27'),
(183, 2, '', '41524462624_c30edd3eea_m.jpg', '2020-09-27'),
(184, 2, '', '41524624654_255930aa18_k.jpg', '2020-09-27'),
(185, 2, '', '41524796604_c2636bf1ff_k.jpg', '2020-09-27'),
(186, 2, '', '41525031604_de39ebfae6_k.jpg', '2020-09-27'),
(187, 2, '', '41525069134_f38a8c8d56_k.jpg', '2020-09-27'),
(188, 2, '', '41525107734_45073a0766_k.jpg', '2020-09-27'),
(189, 2, '', '41525269404_3faf7411c1_k.jpg', '2020-09-27'),
(190, 2, '', '41525429654_b96e1efe5f_k.jpg', '2020-09-27'),
(191, 2, '', '41525614104_b4406d83b1_k.jpg', '2020-09-27'),
(192, 2, '', '41525712394_8aec912f2a_k.jpg', '2020-09-27'),
(193, 2, '', '41525966214_d9a46ecb1c_k.jpg', '2020-09-27'),
(194, 2, '', '42198616872_e2be46df9a_k.jpg', '2020-09-27'),
(195, 2, '', '42198790942_cf959fd9a6_k.jpg', '2020-09-27'),
(196, 2, '', '42199099062_7100abfa1e_k.jpg', '2020-09-27'),
(197, 2, '', '42199121622_bd659b6873_k.jpg', '2020-09-27'),
(198, 2, '', '42199700332_545053051b_k.jpg', '2020-09-27'),
(199, 2, '', '42199952782_8521058fa9_k.jpg', '2020-09-27'),
(200, 2, '', '42199982862_cc0f590bea_k.jpg', '2020-09-27'),
(201, 2, '', '42200485192_43a3adf999_k.jpg', '2020-09-27'),
(202, 2, '', '42200766422_efc88097e6_k.jpg', '2020-09-27'),
(203, 2, '', '42200805142_9d936a988c_k.jpg', '2020-09-27'),
(204, 2, '', '42247267681_ca6137df2d_k.jpg', '2020-09-27'),
(205, 2, '', '42247352431_c3f641c52d_k.jpg', '2020-09-27'),
(206, 2, '', '42247505201_7df0c8d3cc_k.jpg', '2020-09-27'),
(207, 2, '', '42247648691_03cdd2382d_k.jpg', '2020-09-27'),
(208, 2, '', '42200380762_8cbe8fe4ad_k.jpg', '2020-09-27'),
(209, 6, '', 'WhatsApp Image 2018-06-30 at 21.41.18.jpeg', '2020-09-27'),
(210, 6, '', 'WhatsApp Image 2018-06-30 at 21.41.20.jpeg', '2020-09-27'),
(211, 6, '', 'WhatsApp Image 2018-06-30 at 21.41.21.jpeg', '2020-09-27'),
(212, 6, '', 'WhatsApp Image 2018-06-30 at 21.41.22.jpeg', '2020-09-27'),
(213, 6, '', 'WhatsApp Image 2018-06-30 at 21.41.23.jpeg', '2020-09-27'),
(214, 6, '', 'WhatsApp Image 2018-07-01 at 09.21.29.jpeg', '2020-09-27'),
(215, 6, '', 'WhatsApp Image 2018-07-01 at 09.21.30.jpeg', '2020-09-27'),
(216, 6, '', 'WhatsApp Image 2018-07-01 at 09.21.30.jpeg', '2020-09-27'),
(217, 6, '', 'WhatsApp Image 2018-07-01 at 09.21.31.jpeg', '2020-09-27'),
(218, 6, '', 'WhatsApp Image 2018-07-01 at 09.21.33 (1).jpeg', '2020-09-27'),
(219, 6, '', 'WhatsApp Image 2018-07-01 at 09.21.33.jpeg', '2020-09-27'),
(220, 6, '', 'WhatsApp Image 2018-07-01 at 09.55.38.jpeg', '2020-09-27'),
(221, 6, '', 'WhatsApp Image 2018-07-03 at 20.08.01.jpeg', '2020-09-27'),
(222, 6, '', 'WhatsApp Image 2018-07-03 at 20.10.07.jpeg', '2020-09-27'),
(223, 6, '', 'WhatsApp Image 2018-07-03 at 20.10.10.jpeg', '2020-09-27'),
(224, 6, '', 'WhatsApp Image 2018-07-03 at 20.10.15.jpeg', '2020-09-27'),
(225, 6, '', 'WhatsApp Image 2018-07-03 at 20.10.17.jpeg', '2020-09-27'),
(226, 6, '', 'WhatsApp Image 2018-07-03 at 20.10.21.jpeg', '2020-09-27'),
(227, 6, '', 'WhatsApp Image 2018-07-03 at 20.10.23.jpeg', '2020-09-27'),
(228, 6, '', 'WhatsApp Image 2018-07-04 at 17.14.07.jpeg', '2020-09-27'),
(229, 6, '', 'WhatsApp Image 2018-07-04 at 17.16.16.jpeg', '2020-09-27'),
(230, 6, '', 'WhatsApp Image 2018-07-04 at 17.16.39.jpeg', '2020-09-27'),
(231, 6, '', 'mundial 1.jpg', '2020-09-27'),
(232, 6, '', 'mundial 2.jpg', '2020-09-27'),
(233, 6, '', 'mundial 3.jpg', '2020-09-27'),
(234, 6, '', 'mundial 6.jpg', '2020-09-27'),
(235, 6, '', 'mundial 7.jpg', '2020-09-27'),
(236, 6, '', 'mundial 8.jpg', '2020-09-27');

-- --------------------------------------------------------

--
-- Estrutura da tabela `galeria`
--

CREATE TABLE `galeria` (
  `id_galeria` int(11) NOT NULL,
  `nome` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `galeria`
--

INSERT INTO `galeria` (`id_galeria`, `nome`) VALUES
(2, '1° Copa Várzea Grandense'),
(4, '1° Open de kyokushinkai'),
(5, 'Campeonado estadual de karate de contato kenshydokan'),
(6, 'Mundial no Chile');

-- --------------------------------------------------------

--
-- Estrutura da tabela `graduacao`
--

CREATE TABLE `graduacao` (
  `id_graduacao` int(11) NOT NULL,
  `graduacao` varchar(300) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `graduacao`
--

INSERT INTO `graduacao` (`id_graduacao`, `graduacao`) VALUES
(2, 'Faixa Branca 8° Kyu'),
(3, 'Faixa Azul 7° Kyu'),
(4, 'Faixa Amarela 6° Kyu'),
(5, 'Faixa Vermelha 5° Kyu'),
(6, 'Faixa Laranja 4° Kyu'),
(7, 'Faixa Verde 3° Kyu'),
(8, 'Faixa Roxa 2° Kyu'),
(9, 'Faixa Marrom 1° Kyu'),
(10, 'Faixa Preta 1° Dan'),
(11, 'Faixa Preta 2° Dan'),
(12, 'Faixa Preta 3° Dan'),
(13, 'Faixa Preta 4° Dan'),
(14, 'Faixa Preta 5° Dan'),
(15, 'Faixa Preta 6° Dan'),
(16, 'Faixa Preta 7° Dan'),
(17, 'Faixa Preta 8° Dan'),
(19, 'Faixa Preta 9° Dan'),
(20, 'Faixa Preta 10° Dan');

-- --------------------------------------------------------

--
-- Estrutura da tabela `imagens`
--

CREATE TABLE `imagens` (
  `id_imagem` int(11) NOT NULL,
  `nome` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `caminho` varchar(200) CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `imagens`
--

INSERT INTO `imagens` (`id_imagem`, `nome`, `caminho`) VALUES
(281, 'weslley2.jpg', '../../imagens/weslley2.jpg'),
(282, '', '../../imagens_produtos/'),
(286, 'black-belt-894190_640.jpg', '../../imagens_produtos/black-belt-894190_640.jpg'),
(285, 'bow-295101_640.png', '../../imagens_produtos/bow-295101_640.png'),
(287, 'sensei_elyakin.jpg', '../imagens/sensei_elyakin.jpg'),
(288, 'sensei-everson.jpg', '../imagens/sensei-everson.jpg'),
(289, 'Logo Federação kenshydokan.jpg', '../imagens/Logo Federação kenshydokan.jpg'),
(290, 'sensei_weslley.jpg', '../imagens/sensei_weslley.jpg'),
(291, 'Kano_Jigoro.jpg', '../../imagens_produtos/Kano_Jigoro.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id_mensagem` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_destinatário` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `paginas`
--

CREATE TABLE `paginas` (
  `id_pag` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `conteudo` text NOT NULL,
  `dataCriacao` DATE NOT NULL
  `dataMudanca` DATE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `paginas`
--

INSERT INTO `paginas` (`id_pag`, `titulo`, `conteudo`, `dataCriacao`) VALUES
(2, 'Ju Jitsu', '<div class="container">
<div class="dmBody u_dmStyle_template_jiu-jitsu" id="dmFirstContainer">
<div class="allWrapper" id="allWrapper">
<div class="dmContent" id="dm_content">
<div class="dmDefaultRespTmpl" id="1994795488">
<div class="dmDefaultPage dmRespRowsWrapper dmRespRowsWrapperSize1 innerPageTmplBox" id="1538663754">
<div class="dmDefaultListContentRow dmRespRow" id="1493656194">
<div class="dmRespColsWrapper" id="1668211504">
<div class="dmRespCol large-12 medium-12 small-12" id="1150745240">
<h1 style="text-align:center"><u><strong><span style="color:#c0392b">kansetsu waza (T&eacute;cnicas no Solo)</span></strong></u></h1>

<p>&nbsp;</p>

<h2 style="text-align:center"><span style="font-size:36px">Shime Waza - T&eacute;cnicas de Estrangulamento</span></h2>

<p style="text-align:center">&nbsp;<span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><strong>Gyaku Juji Jime</strong></span></span></p>

<div style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif">estrangulamento cruzado invertido</span></span></div>

<div class="separator" style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><a href="https://3.bp.blogspot.com/-1MEHwoz8OYo/Wi2VC87Y4RI/AAAAAAAABfE/T0rpoFZ70Ash3dASmTa4KWinZQ7t-5PAQCEwYBhgL/s1600/Gyaku%2BJuji%2BJime.png"><img height="190" src="https://3.bp.blogspot.com/-1MEHwoz8OYo/Wi2VC87Y4RI/AAAAAAAABfE/T0rpoFZ70Ash3dASmTa4KWinZQ7t-5PAQCEwYBhgL/s320/Gyaku%2BJuji%2BJime.png" width="561" /></a></span></span></div>

<div class="separator" style="text-align:center">&nbsp;</div>

<p style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><strong>Hadaka Jime</strong></span></span></p>
</div>

<div class="dmRespCol large-12 medium-12 small-12">
<p style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif">estrangulamento sem roupa</span></span></p>

<div class="separator" style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><a href="https://3.bp.blogspot.com/-4EmXH0_zhls/WjbTtSv7T6I/AAAAAAAABiU/r5g8eUs9lfAPj6VWKJIUWX8TnWTYPI1NgCLcBGAs/s320/hadakajime-judo-technique.gif"><img height="186" src="https://3.bp.blogspot.com/-4EmXH0_zhls/WjbTtSv7T6I/AAAAAAAABiU/r5g8eUs9lfAPj6VWKJIUWX8TnWTYPI1NgCLcBGAs/s320/hadakajime-judo-technique.gif" width="483" /></a></span></span></div>

<div class="separator" style="text-align:center">&nbsp;</div>

<p style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><strong>Kata Ha Jime</strong></span></span></p>
</div>

<div class="dmRespCol large-12 medium-12 small-12">
<p style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif">estrangulamento com um ombro</span></span></p>

<div class="separator" style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><a href="https://4.bp.blogspot.com/-fzSaohiyZ_8/WjbU2gHRcLI/AAAAAAAABig/Ew0p9scg9fYuXQsXXB-c1Nq5K3vlFFEhQCLcBGAs/s1600/Kata-ha-jime.jpg"><img height="219" src="https://4.bp.blogspot.com/-fzSaohiyZ_8/WjbU2gHRcLI/AAAAAAAABig/Ew0p9scg9fYuXQsXXB-c1Nq5K3vlFFEhQCLcBGAs/s320/Kata-ha-jime.jpg" width="463" /></a></span></span></div>

<div style="text-align:center">&nbsp;</div>

<p style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><strong>Kata Juji Jime</strong></span></span></p>
</div>

<div class="dmRespCol large-12 medium-12 small-12">
<p style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif">estrangulamento cruzado pelo ombro</span></span></p>

<p style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><a href="https://4.bp.blogspot.com/-O5J4LiFHFFs/WjbZEyqfwPI/AAAAAAAABiw/nk4rPVtKCjAvtueuAf50K8YlO1QH6x3RgCLcBGAs/s1600/kata-juji-jime.jpg"><img height="320" src="https://4.bp.blogspot.com/-O5J4LiFHFFs/WjbZEyqfwPI/AAAAAAAABiw/nk4rPVtKCjAvtueuAf50K8YlO1QH6x3RgCLcBGAs/s320/kata-juji-jime.jpg" width="463" /></a></span></span></p>

<p style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif">&nbsp;</span></span></p>
</div>

<p style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><strong>Kata Te Jime</strong> estrangulamento de gola</span></span></p>

<div class="separator" style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><a href="https://3.bp.blogspot.com/-MpLhwd9t-kc/Wjba4Hot4GI/AAAAAAAABi8/5QJQrvc0shMvoVQ8_iG4C0n_fJdUTiy6gCLcBGAs/s1600/Katate-Jime.jpg"><img height="158" src="https://3.bp.blogspot.com/-MpLhwd9t-kc/Wjba4Hot4GI/AAAAAAAABi8/5QJQrvc0shMvoVQ8_iG4C0n_fJdUTiy6gCLcBGAs/s320/Katate-Jime.jpg" width="534" /></a></span></span></div>

<div style="text-align:center">&nbsp;</div>

<p style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><strong>Nami Juji Jime</strong> estrangulamento cruzado comum</span></span></p>

<div class="separator" style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><a href="https://3.bp.blogspot.com/-zlLHDclfqR8/Wjbcb5CSRAI/AAAAAAAABjI/Cwg6Eur2Q6sIxDMvmO18QDWf1jGiGsnQwCLcBGAs/s320/Nami-Juji-Jime.jpg"><img height="358" src="https://3.bp.blogspot.com/-zlLHDclfqR8/Wjbcb5CSRAI/AAAAAAAABjI/Cwg6Eur2Q6sIxDMvmO18QDWf1jGiGsnQwCLcBGAs/s320/Nami-Juji-Jime.jpg" width="508" /></a></span></span></div>

<div style="text-align:center">&nbsp;</div>

<p style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><strong>Okuri Eri Jime</strong></span></span></p>

<div style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif">estrangulamento deslizando pelo pesco&ccedil;o/gola</span></span></div>

<div class="separator" style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><a href="https://4.bp.blogspot.com/-rjYv1MozIoU/WjbdWji5_vI/AAAAAAAABjQ/IahO53A2EQs5twuIVZHoQ95kxJIydxSJACLcBGAs/s320/okuri%2Beri%2Bjime.jpeg"><img height="145" src="https://4.bp.blogspot.com/-rjYv1MozIoU/WjbdWji5_vI/AAAAAAAABjQ/IahO53A2EQs5twuIVZHoQ95kxJIydxSJACLcBGAs/s320/okuri%2Beri%2Bjime.jpeg" width="458" /></a></span></span></div>

<div style="text-align:center">&nbsp;</div>

<p style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><strong>Ryo Te Jime</strong></span></span></p>

<div style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif">estrangulamento com as duas m&atilde;os</span></span></div>

<div class="separator" style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><a href="https://1.bp.blogspot.com/-23HC34oEcWE/WjbgFXRI_1I/AAAAAAAABjc/P9-St0vrPrQQAKMKQz5s_tnn6cC79wBUACLcBGAs/s1600/ryote_jime.gif"><img height="324" src="https://1.bp.blogspot.com/-23HC34oEcWE/WjbgFXRI_1I/AAAAAAAABjc/P9-St0vrPrQQAKMKQz5s_tnn6cC79wBUACLcBGAs/s1600/ryote_jime.gif" width="351" /></a></span></span></div>

<div style="text-align:center">&nbsp;</div>

<div>
<p style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><strong>Sankaku Jime</strong> estrangulamento triangular</span></span></p>

<div class="separator" style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><a href="https://1.bp.blogspot.com/-nEOSiiELI6A/WjbhfZsaHEI/AAAAAAAABjo/7c7ksm0S1mwCo_ZNU5XXKQgAMXBOJZwowCLcBGAs/s320/sankaku_jime.gif"><img height="327" src="https://1.bp.blogspot.com/-nEOSiiELI6A/WjbhfZsaHEI/AAAAAAAABjo/7c7ksm0S1mwCo_ZNU5XXKQgAMXBOJZwowCLcBGAs/s320/sankaku_jime.gif" width="364" /></a></span></span></div>

<div style="text-align:center">&nbsp;</div>
</div>

<p style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><strong>Sode Guruma Jime</strong> estrangulamento com giro da manga</span></span></p>

<div class="separator" style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><a href="https://2.bp.blogspot.com/-GZSJEbykqoU/Wjbi6Ln8nYI/AAAAAAAABj0/IVdYNWm4P9ctyTq1GeMT7cLUTAma0Em7QCLcBGAs/s1600/shime-waza-sodegurumajime.png"><img height="250" src="https://2.bp.blogspot.com/-GZSJEbykqoU/Wjbi6Ln8nYI/AAAAAAAABj0/IVdYNWm4P9ctyTq1GeMT7cLUTAma0Em7QCLcBGAs/s1600/shime-waza-sodegurumajime.png" width="682" /></a></span></span></div>

<div class="separator" style="text-align:center">&nbsp;</div>

<div style="text-align:center">&nbsp;</div>

<div class="dmCustomHtml u_1212167956" id="1212167956">
<h2 style="text-align:center"><span style="font-size:36px"><span style="font-family:Verdana,Geneva,sans-serif">Kansetsu Waza - T&eacute;cnicas de Deslocamento</span></span></h2>

<p style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif">&nbsp; <strong>Ude-garami</strong></span></span></p>

<div style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif">chaves de bra&ccedil;o</span></span></div>

<div class="separator" style="text-align:center"><span style="font-size:18px"><span style="font-family:Verdana,Geneva,sans-serif"><a href="https://2.bp.blogspot.com/-L2kux1cDvjA/WvB3UzoBn9I/AAAAAAAADCM/J1a55dV8-gkMoO4RG1p41cGVy4FOunP1ACLcBGAs/s1600/ude_garami.gif"><img height="273" src="https://2.bp.blogspot.com/-L2kux1cDvjA/WvB3UzoBn9I/AAAAAAAADCM/J1a55dV8-gkMoO4RG1p41cGVy4FOunP1ACLcBGAs/s1600/ude_garami.gif" width="4', '2020-08-16'),
(4, 'Muay Thai', '<div class="container">
<div class="dmNewParagraph u_1440505187" id="1440505187">
<h3 style="text-align:center"><strong>CHUTES - TEIs</strong></h3>

<div>Chute Frontal: <strong>Tei-trong</strong></div>

<div>Chute Circular ao Tronco: <strong>Tei-chiyang</strong></div>

<div>Chute Circular Alto: <strong>Tei-kan-kro</strong></div>

<div>Chute Circular Baixo: <strong>Tei-tat</strong></div>

<div>Chute Semi-Circular: <strong>Tei-rid</strong></div>

<div>Chute Circular Cima e em Baixo: <strong>Tei-kot</strong></div>

<div>Chute Circular em Escada: <strong>Yiep-tei</strong></div>

<div>Chute Circular com Salto: <strong>Kra-tote-teii</strong></div>

<div>Chute de Lateral: <strong>Tip-kang</strong></div>

<div>Chute Rotativo de Calcanhar: <strong>Tip klap lang</strong></div>

<div>Chute Rotativo de Calcanhar em Gancho: <strong>Tei klap lang</strong></div>

<div>&nbsp;</div>

<h3 style="text-align:center"><strong>JOELHADA - KHAOs</strong></h3>

<div>Joelhada Frontal: <strong>Khao trong</strong></div>

<div>Joelhada Lateral: <strong>Khao Tat</strong></div>

<div>Joelhada Circular: <strong>Khao chiyang</strong></div>

<div>Joelhada com Salto: <strong>Khao loy</strong></div>

<div>Joelhada Frontal Penetrante: <strong>Khao-youn</strong></div>

<div>Joelhada em Escada: <strong>Yiep-khao</strong></div>

<div>&nbsp;</div>

<h3 style="text-align:center"><strong>SOCOS - MATs</strong></h3>

<div>Jab: <strong>Mat nueng</strong></div>

<div>Direto: <strong>Mat trong</strong></div>

<div>Gancho: <strong>Mat wiang san</strong></div>

<div>Cruzado:<strong>Mat trong</strong></div>

<div>Soco em Salto: <strong>Kradot chok</strong></div>

<div>Punho Rotativo: <strong>Mat wiang soi</strong></div>

<div>&nbsp;</div>

<h3 style="text-align:center"><strong>COTOVELADAS - SOKs</strong></h3>

<div>Cotovelo Horizontal: <strong>Sok tat</strong></div>

<div>Cotovelo Obl&iacute;quo: <strong>Khao chiang</strong></div>

<div>Cotovelo Circular ao Tronco: <strong>Sok ti</strong></div>

<div>Cotovelo de cima para baixo: <strong>Sok ti</strong></div>

<div>Cotovelo de baixo para cima: <strong>Sok Ngat</strong></div>

<div>Cotovelo Frontal: <strong>Sok phung</strong></div>

<div>Reverso de Cotovelo Horizontal: <strong>Sok wiang klap</strong></div>

<div>Cotovelo Rotativo: <strong>Mat wiang klap</strong></div>

<div>Cotovelo Rotativo com Retorno: <strong>Sok klap</strong></div>

<div>Dupla Cotovelada: <strong>Sok klap khu</strong></div>

<div>Cotovelo em salto descendo: <strong>Kradot sok</strong></div>
</div>
</div>
', '2020-08-17'),
(5, 'Karate', '<div class="container text-center">
<h1 style="text-align:center"><u><strong><span style="color:#c0392b">T&eacute;cnicas do karate Kenshydokan</span></strong></u></h1>
</div>

<div class="container">
<h2 style="text-align:center"><strong>Tsuki Waza - T&eacute;cnicas de Soco</strong></h2>

<p style="text-align:center">&nbsp;</p>

<h3 style="text-align:center"><u><strong>Seiken Oi Tsuki:</strong></u></h3>

<h2 style="text-align:center"><strong><a href="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/78a5261.jpg" target="_blank"><img alt="" src="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/78a5261.jpg" style="height:250px; width:333px" /></a></strong></h2>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">&Eacute; um soco reto que parte da cintura com a palma da m&atilde;o para cima que ao percorrer o trajeto o punho rotaciona at&eacute; a palma da m&atilde;o ficar virada para baixo, atingindo seu oponente.</span></span></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">Existem tr&ecirc;s maneiras principais de executar esse soco. S&atilde;o elas:</span></span></p>

<ol>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Jodan Seiken Oi Tsuki:</span></strong><span style="font-size:12.0pt"> Soco reto avan&ccedil;ando do pesco&ccedil;o pra cima.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Chudan Seiken Oi Tsuki:</span></strong><span style="font-size:12.0pt"> Soco reto avan&ccedil;ando na altura do t&oacute;rax.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Guedan Seiken Oi Tsuki:</span></strong><span style="font-size:12.0pt"> Soco reto avan&ccedil;ando da cintura pra baixo.</span></span></li>
</ol>

<p style="text-align:center">&nbsp;</p>

<h3 style="text-align:center"><u><strong>Seiken Gyaku Oi Tsuki:</strong></u></h3>

<p style="text-align:center"><a href="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/8b20fbf.jpg" target="_blank"><img alt="" src="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/8b20fbf.jpg" style="height:250px; width:333px" /></a></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">&Eacute; um outro soco reto que parte da cintura com a palma da m&atilde;o para cima que ao percorrer o trajeto o punho rotaciona at&eacute; a palma da m&atilde;o ficar virada para baixo, atingindo seu oponente, s&oacute; que dessa vez com a m&atilde;o inversa a da perna que est&aacute; avan&ccedil;ada.</span></span></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">Tamb&eacute;m existem tr&ecirc;s maneiras principais de executar esse soco. S&atilde;o elas:</span></span></p>

<ol>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Jodan Seiken Gyaku Oi Tsuki:</span></strong><span style="font-size:12.0pt"> Soco reto avan&ccedil;ando do pesco&ccedil;o pra cima.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Chudan Seiken Gyaku Oi Tsuki:</span></strong><span style="font-size:12.0pt"> Soco reto avan&ccedil;ando na altura do t&oacute;rax.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Guedan Seiken&nbsp;Gyaku Oi Tsuki:</span></strong><span style="font-size:12.0pt"> Soco reto avan&ccedil;ando da cintura pra baixo.</span></span></li>
</ol>

<p style="text-align:center">&nbsp;</p>

<h3 style="text-align:center"><u><strong>Tate Tsuki</strong></u></h3>

<p style="text-align:center"><a href="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/9a56495.jpg" target="_blank"><img alt="" src="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/9a56495.jpg" style="height:250px; width:333px" /></a></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">&Eacute; um soco semi-rotacionado que parte da cintura com a palma da m&atilde;o para cima que ao percorrer o trajeto o punho e ele semi-rotaciona at&eacute; a palma da m&atilde;o ficar virada para o lado, atingindo seu oponente com o punho em p&eacute;.</span></span></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">Existem tr&ecirc;s maneiras principais de executar esse soco. S&atilde;o elas:</span></span></p>

<ol>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Jodan Seiken Tate Tsuki:</span></strong><span style="font-size:12.0pt"> Soco semi-rotacionado avan&ccedil;ando do pesco&ccedil;o pra cima.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Chudan Seiken Tate Tsuki:</span></strong><span style="font-size:12.0pt"> Soco semi-rotacionado avan&ccedil;ando na altura do t&oacute;rax.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Guedan Seiken&nbsp;Tate Tsuki:</span></strong><span style="font-size:12.0pt"> Soco semi-rotacionado avan&ccedil;ando da cintura pra baixo.</span></span></li>
</ol>

<p style="text-align:center">&nbsp;</p>

<h3 style="text-align:center"><u><strong><span style="font-size:11pt"><span style="font-size:12.0pt">Gyaku Tate Tsuki</span></span></strong></u></h3>

<p style="text-align:center"><a href="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/6b34e82.jpg" target="_blank"><img alt="" src="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/6b34e82.jpg" style="height:250px; width:333px" /></a></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">&Eacute; um soco semi-rotacionado que parte da cintura com a palma da m&atilde;o para cima que ao percorrer o trajeto o punho e ele semi-rotaciona at&eacute; a palma da m&atilde;o ficar virada para o lado, atingindo seu oponente com o punho em p&eacute;, s&oacute; que dessa vez com a m&atilde;o inversa a da perna que est&aacute; avan&ccedil;ada.</span></span></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">Existem tr&ecirc;s maneiras principais de executar esse soco. S&atilde;o elas:</span></span></p>

<ol>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Jodan Seiken Gyaku Tate Tsuki:</span></strong><span style="font-size:12.0pt"> Soco semi-rotacionado avan&ccedil;ando do pesco&ccedil;o pra cima.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Chudan Seiken&nbsp;Gyaku Tate Tsuki:</span></strong><span style="font-size:12.0pt"> Soco semi-rotacionado avan&ccedil;ando na altura do t&oacute;rax.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Guedan Seiken&nbsp;Gyaku Tate Tsuki:</span></strong><span style="font-size:12.0pt"> Soco semi-rotacionado avan&ccedil;ando da cintura pra baixo.</span></span></li>
</ol>

<p style="text-align:center">&nbsp;</p>

<h3 style="text-align:center"><u><strong>Seiken Mawashi Tsuki</strong></u></h3>

<p style="text-align:center"><a href="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/10f20e90.jpg"><img alt="" src="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/10f20e90.jpg" style="height:250px; width:333px" /></a></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">&Eacute; um soco com curva que parte do kamae(guarda de lutas em p&eacute;) que ao percorrer o trajeto o punho rotaciona at&eacute; a palma da m&atilde;o ficar virada para baixo, atingindo seu oponente na lateral.</span></span></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">Existem duas maneiras principais de executar esse soco pois abaixo da cintura esse soco n&atilde;o tem efeito. S&atilde;o elas:</span></span></p>

<ol>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Jodan Seiken Mawashi Tsuki:</span></strong><span style="font-size:12.0pt"> Soco com curva do pesco&ccedil;o pra cima.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Chudan Seiken Mawashi Tsuki:</span></strong><span style="font-size:12.0pt"> Soco com curva na altura do t&oacute;rax.</span></span></li>
</ol>

<p style="text-align:center">&nbsp;</p>

<h3 style="text-align:center"><u><strong><span style="font-size:11pt"><span style="font-size:12.0pt">Shita Tsuki</span></span></strong></u></h3>

<p style="text-align:center"><a href="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/11584795.jpg" target="_blank"><img alt="" src="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/11584795.jpg" style="height:250px; width:333px" /></a></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">&Eacute; um soco n&atilde;o rotacionado que parte da cintura com a palma da m&atilde;o para cima que ao percorrer o trajeto o punho n&atilde;o </span></span><span style="font-size:11pt"><span style="font-size:12.0pt">rotaciona mantendo se na mesma posi&ccedil;&atilde;o, atingindo seu oponente com o punho na posi&ccedil;&atilde;o inicial.</span></span></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">Existem tr&ecirc;s maneiras principais de executar esse soco. S&atilde;o elas:</span></span></p>

<ol>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Jodan Seiken Shita Tsuki:</span></strong><span style="font-size:12.0pt"> Soco sem rotacionar o punho avan&ccedil;ando do pesco&ccedil;o pra cima.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Chudan Seiken&nbsp;ShitaTsuki:</span></strong><span style="font-size:12.0pt"> Soco sem rotacionar o punho avan&ccedil;ando na altura do t&oacute;rax.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Guedan Seiken&nbsp;Shita Tsuki:</span></strong><span style="font-size:12.0pt"> Socosem rotacionar o punho avan&ccedil;ando da cintura pra baixo.</span></span></li>
</ol>

<p style="text-align:center">&nbsp;</p>

<h2 style="text-align:center"><strong>Uke Waza - T&eacute;cnicas de Defesa</strong></h2>

<h3 style="text-align:center"><u><strong>Guedan Barai</strong></u></h3>

<p style="text-align:center"><a href="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/12334168.jpg" target="_blank"><img alt="" src="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/12334168.jpg" style="height:250px; width:333px" /></a></p>

<p style="text-align:center">Normalmente essa &eacute; a primeira t&eacute;cnica ou primeira defesa que aprendemos no karate, ao executar voc&ecirc; sobe o bra&ccedil;o na autura do ombro, e logo em seguida desse como uma varrida defendendo de dentro para fora.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>
</div>
', '2020-08-18'),
(6, 'Kickboxing', '<div class="container">
<h1 style="text-align:center"><strong><u><span style="color:#c0392b">Kickboxing</span></u></strong></h1>

<p><strong>Full contact - Contato total</strong> Regras de contato total, ou kickboxing americano, &eacute; essencialmente uma mistura de boxe ocidental e karat&ecirc; tradicional. Os kickboxers masculinos s&atilde;o de peito nu vestindo cal&ccedil;as de kickboxing e equipamentos de prote&ccedil;&atilde;o, incluindo: protetor de boca, m&atilde;o-wraps, 10 oz (280 g). luvas de boxe, guarda-costas, caneleiras e chuteiras e capacete de prote&ccedil;&atilde;o (para amadores e menores de 16 anos). Os kickboxers femininos usar&atilde;o um suti&atilde; esportivo e prote&ccedil;&atilde;o para o peito, al&eacute;m do vestu&aacute;rio masculino / equipamento de prote&ccedil;&atilde;o.<br />
Lutadores not&aacute;veis â€‹â€‹sob regras de contato total incluem Marek Piotrowski, Dennis Alexio, Joe Lewis, Rick Roufus, Jean-Yves Theriault, Benny Urquidez, Bill Wallace e Don &quot;The Dragon&quot; Wilson.<br />
Regras:<br />
Os oponentes podem bater uns nos outros com socos e chutes, golpeando acima da cintura.<br />
Cotovelos e joelhos s&atilde;o proibidos e o uso das canelas raramente &eacute; permitido.<br />
Luta de clinch e luta s&atilde;o proibidos, mas as varreduras s&atilde;o legais, mas variam dependendo do &aacute;rbitro.<br />
As lutas s&atilde;o geralmente de 3 a 12 rodadas (com dura&ccedil;&atilde;o de 2 a 3 minutos cada), com um descanso de 1 minuto entre as rodadas.<br />
<strong>Semi-Contact - Semi-Contato</strong> Regras de semi-contato ou Pontos de Combate, &eacute; a variante do kickboxing americano mais parecido com o karat&ecirc;, pois consiste em lutar com o objetivo de marcar pontos com &ecirc;nfase na entrega, velocidade e t&eacute;cnica. Sob tais regras, as lutas s&atilde;o realizadas no tatami, apresentando os cintos para classificar os lutadores em ordem de experi&ecirc;ncia e habilidade. Os kickboxers masculinos usam camisas e cal&ccedil;as de kickboxing, bem como equipamentos de prote&ccedil;&atilde;o, incluindo: protetor de boca, envolt&oacute;rios de m&atilde;o, 10 oz (280 g). luvas de boxe, guarda-costas, caneleiras, chuteiras e arn&ecirc;s. Os kickboxers femininos usar&atilde;o um suti&atilde; esportivo e prote&ccedil;&atilde;o para o peito, al&eacute;m do vestu&aacute;rio masculino / equipamento de prote&ccedil;&atilde;o.<br />
Lutadores not&aacute;veis â€‹â€‹sob regras de semi-contato incluem Raymond Daniels, Michael Page e Gregorio Di Leo.<br />
Regras:<br />
Os lutadores podem marcar atrav&eacute;s de socos ou chutes, golpeando acima da cintura e varreduras de p&eacute;, executado abaixo do tornozelo.<br />
Socos, pontap&eacute;s e varreduras de p&eacute; recebem 1 ponto. Pontap&eacute;s na cabe&ccedil;a ou chutes no corpo recebem 2 pontos. Chutes saltantes na cabe&ccedil;a recebem 3 pontos.<br />
Pontap&eacute;s e machadadas s&atilde;o permitidos, mas devem ser executados com a sola do p&eacute;.<br />
O uso das canelas raramente &eacute; permitido, exceto para t&eacute;cnicas de salto e fia&ccedil;&atilde;o.<br />
Cotovelos, joelhos e backfists girat&oacute;rios s&atilde;o proibidos.<br />
Luta de clinch, lances e varreduras (com exce&ccedil;&atilde;o de varreduras de p&eacute;) s&atilde;o proibidos.<br />
As lutas geralmente duram 3 rodadas (com dura&ccedil;&atilde;o de 2 a 3 minutos cada) com um descanso de 1 minuto entre as rodadas.<br />
<strong>Shoot boxing</strong>Shoot boxing &eacute; um estilo &uacute;nico de kickboxing popular no Jap&atilde;o que utiliza submiss&otilde;es em p&eacute;, como estrangulamentos, armlock e pulseiras, al&eacute;m de chutes, socos, joelhos e arremessos. Os lutadores masculinos t&ecirc;m o peito nu usando cal&ccedil;as apertadas e equipamento de prote&ccedil;&atilde;o, incluindo: protetores bucais, protetores de m&atilde;o, 280 g (10 oz). luvas de boxe e guarda-costas. Os kickboxers femininos usar&atilde;o um suti&atilde; esportivo e prote&ccedil;&atilde;o para o peito, al&eacute;m do vestu&aacute;rio masculino / equipamento de prote&ccedil;&atilde;o.<br />
Lutadores not&aacute;veis sob as regras do boxe incluem Rena Kubota, Kenichi Ogata, Hiroki Shishido, Andy Souwer e Ai Takahashi.<br />
Regras:<br />
Os opositores podem atacar um ao outro com socos, chutes, incluindo chutes abaixo da cintura, exceto na virilha e nos joelhos.<br />
Cotovelos s&atilde;o proibidos.<br />
Luta de clinch, lances e varreduras s&atilde;o permitidos.<br />
Submiss&otilde;es permanentes s&atilde;o permitidas.<br />
As lutas s&atilde;o de 3 rodadas (dura&ccedil;&atilde;o de 3 minutos cada) com um descanso de 1 minuto entre as rodadas.</p>

<h3><strong>T&eacute;cnicas</strong></h3>

<p><strong>T&eacute;cnicas de Soco</strong></p>

<p>Obs.: No Ingl&ecirc;s as palavras s&atilde;o normalmente pronunciadas de forma diferente do portugu&ecirc;s.</p>

<p><br />
<strong>Jab</strong> - soco direto da m&atilde;o da frente<br />
<strong>Cross</strong> - soco direto da m&atilde;o de tr&aacute;s<br />
<strong>hook</strong> - gancho<br />
<strong>Uppercut</strong> - soco ascendente atingindo o queixo<br />
<strong>Cross counter</strong> - soco cruzado<br />
<strong>Flying punch</strong> - soco voador<br />
<strong>Over hand</strong> - soco semi-circular<br />
<strong>T&eacute;cnicas de ChuteAxe kick</strong> - chute de pis&atilde;o (sola do p&eacute;)<br />
<strong>Back kick</strong> - chute para tr&aacute;s<br />
<strong>Front kick</strong> - Chute frontal<br />
<strong>Hook kick</strong> - chute estendendo a perna para desser o calcanhar<br />
<strong>Roundhouse kick</strong> or <strong>circle kick</strong> - chute circular<br />
<strong>Semi-circular kick</strong> - Chute semi-circular<br />
<strong>Sweeping</strong> - varredura (dar rastera)<br />
<strong>Side kick</strong> - chute lateral (yoko geri)<br />
<strong>T&eacute;cnicas de JoelhadaFlying knee</strong> - joelhada voadora<br />
<strong>Side knee</strong> - Joelhada lateral<br />
<strong>Straight Knee</strong> - joelhada frontal<br />
<strong>T&eacute;cnicas de CotoveladaDownward Elbow</strong> - Cotovelada para baixo<br />
<strong>Side Elbow</strong> - Cotovelada lateral<br />
<strong>Upward elbow</strong> - Cotovelada para cima</p>
</div>
', '2020-08-18');

-- --------------------------------------------------------

--
-- Estrutura da tabela `postagens`
--

CREATE TABLE `postagens` (
  `id_postagem` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `titulo` varchar(200) NOT NULL,
  `conteudo` text NOT NULL,
  `situacao` varchar(4) NOT NULL,
  `data` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `postagens`
--

INSERT INTO `postagens` (`id_postagem`, `id_usuario`, `titulo`, `conteudo`, `situacao`, `data`) VALUES
(4, 1, 'O Kiai', '<div class="border container mt-5">
<h1 style="text-align:center"><u><strong><span style="color:#c0392b">O Kiai</span></strong></u></h1>

<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; O kiai consiste em um Mantra ou Vibra&ccedil;&atilde;o sonora que ajuda a liberar a mente dos seus condicionamentos que vibra e auxilia na concentra&ccedil;&atilde;o do Hara, com o sentido de desenvolver a sua for&ccedil;a e assim direcionar o fluxo do ki. O lutador, ao efetuar seu golpe, <strong>grita Kiai,</strong> o som vigoroso que se origina no baixo abd&ocirc;men e concentra a energia nos movimentos, liberando um poder de ordem f&iacute;sica e espiritual. Miyamoto Musashi , ensinava que o grito emitido durante uma luta e uma express&atilde;o de for&ccedil;a e demonstra a energia do lutador . Ele classificou tr&ecirc;s tipo de gritos de acordo com o seu objetivo. O grito inicial em tom grave, para assustar o adversaria, o grito no meio da luta, que deve sair do ventre, do Hara, com toda a sua for&ccedil;a,e o ultimo grito e de vitoria.</p>
</div>
', 'sim', '2020-08-16'),
(5, 1, 'O Bushi-Do', '<div class="border container mt-5">
<h1 style="text-align:center"><u><span style="color:#c0392b">O Bushi-D&ocirc;</span></u></h1>

<p style="text-align:center"><u><span style="color:#c0392b"><a href="https://kenshydokan.org.br/ckeditor/plugins/imageuploader/uploads/8f1727f.jpg" target="_blank"><img alt="" class="img-fluid" src="https://kenshydokan.org.br/ckeditor/plugins/imageuploader/uploads/8f1727f.jpg" style="height:285px; width:229px" /></a></span></u></p>

<p>Aos samurais &eacute; atribu&iacute;do o C&oacute;digo de Honra (Bushi-D&ocirc;), que pode ser traduzido da seguinte forma:<br />
<strong><em>&ldquo;N&atilde;o tenho pais, fa&ccedil;o do c&eacute;u e da terra os meus pais. </em> </strong><br />
<em><strong>N&atilde;o tenho lar, fa&ccedil;o do Tandem sede do meu esp&iacute;rito, o meu lar. </strong> </em><br />
<strong><em>N&atilde;o tenho poder divino, fa&ccedil;o da honestidade o meu poder. </em> </strong><br />
<em><strong>N&atilde;o tenho meios, fa&ccedil;o da docilidade os meus meios. </strong> </em><br />
<strong><em>N&atilde;o tenho poder m&aacute;gico, fa&ccedil;o da minha for&ccedil;a interior a minha magia. </em> </strong><br />
<strong><em>N&atilde;o tenho vida nem morte, fa&ccedil;o do eterno a minha exist&ecirc;ncia, minha vida e minha morte. </em> </strong><br />
<strong><em>N&atilde;o tenho corpo, fa&ccedil;o da coragem o meu corpo. </em> </strong><br />
<strong><em>N&atilde;o tenho olhos, onde est&aacute; a luz est&atilde;o os meus olhos. </em> </strong><br />
<strong><em>N&atilde;o tenho ouvidos, fa&ccedil;o da sensibilidade a minha audi&ccedil;&atilde;o. </em> </strong><br />
<strong><em>N&atilde;o tenho membros, os fa&ccedil;o da prontid&atilde;o dos meus movimentos espont&acirc;neos. N&atilde;o tenho leis, fa&ccedil;o da autoprote&ccedil;&atilde;o a minha lei. </em> </strong><br />
<strong><em>N&atilde;o tenho estrat&eacute;gias, fa&ccedil;o do acaso os meus prop&oacute;sitos. </em> </strong><br />
<strong><em>N&atilde;o tenho forma, fa&ccedil;o da ast&uacute;cia a minha forma. </em> </strong><br />
<em><strong>N&atilde;o tenho princ&iacute;pios, fa&ccedil;o da adaptabilidade os meus princ&iacute;pios. </strong> </em><br />
<strong><em>N&atilde;o tenho milagres, fa&ccedil;o da justi&ccedil;a os meus milagres. </em> </strong><br />
<strong><em>N&atilde;o tenho t&aacute;ticas, fa&ccedil;o da rapidez a minha t&aacute;tica. </em> </strong><br />
<strong><em>N&atilde;o tenho amigos, fa&ccedil;o da minha mente os meus amigos. </em> </strong><br />
<strong><em>N&atilde;o tenho inimigos, fa&ccedil;o da imprud&ecirc;ncia o meu inimigo. </em> </strong><br />
<strong><em>N&atilde;o tenho armaduras, fa&ccedil;o da benevol&ecirc;ncia e retid&atilde;o a minha armadura. </em> </strong><br />
<strong><em>N&atilde;o tenho castelo, fa&ccedil;o da mente im&oacute;vel, o Grande Esp&iacute;rito, o meu castelo. </em> </strong><br />
<strong><em>N&atilde;o tenho arma, fa&ccedil;o do sonho onde fica o al&eacute;m dos pensamentos a minha espada&rdquo;.</em> </strong><br />
De um modo geral, o Bushi-D&ocirc;, encerra toda a hist&oacute;ria das artes marciais. A palavra Bushi-D&ocirc;, quer dizer: &ldquo;Caminho do Guerreiro&rdquo; ou &ldquo;Caminho das Artes Marciais&rdquo;.<br />
<strong>OS SETE PRINC&Iacute;PIOS DO BUSHI-D&Ocirc;</strong><br />
(O Caminho do Guerreiro) O &ldquo;Caminho do Samurai&rdquo; ou o &ldquo;Caminho do Guerreiro&rdquo; &eacute; influenciado pela fus&atilde;o Budo-Xinto&iacute;sta, pode ser resumida em sete princ&iacute;pios essenciais, sendo eles:<br />
<strong>1. GI</strong> - A verdade. A atitude justa. Quando devemos dormir, devemos dormir, quando devemos lutar, devemos lutar;<br />
<strong>2. YU</strong> - Bravura;
(4, 'Muay Thai', '<div class="container">
<div class="dmNewParagraph u_1440505187" id="1440505187">
<h3 style="text-align:center"><strong>CHUTES - TEIs</strong></h3>

<div>Chute Frontal: <strong>Tei-trong</strong></div>

<div>Chute Circular ao Tronco: <strong>Tei-chiyang</strong></div>

<div>Chute Circular Alto: <strong>Tei-kan-kro</strong></div>

<div>Chute Circular Baixo: <strong>Tei-tat</strong></div>

<div>Chute Semi-Circular: <strong>Tei-rid</strong></div>

<div>Chute Circular Cima e em Baixo: <strong>Tei-kot</strong></div>

<div>Chute Circular em Escada: <strong>Yiep-tei</strong></div>

<div>Chute Circular com Salto: <strong>Kra-tote-teii</strong></div>

<div>Chute de Lateral: <strong>Tip-kang</strong></div>

<div>Chute Rotativo de Calcanhar: <strong>Tip klap lang</strong></div>

<div>Chute Rotativo de Calcanhar em Gancho: <strong>Tei klap lang</strong></div>

<div>&nbsp;</div>

<h3 style="text-align:center"><strong>JOELHADA - KHAOs</strong></h3>

<div>Joelhada Frontal: <strong>Khao trong</strong></div>

<div>Joelhada Lateral: <strong>Khao Tat</strong></div>

<div>Joelhada Circular: <strong>Khao chiyang</strong></div>

<div>Joelhada com Salto: <strong>Khao loy</strong></div>

<div>Joelhada Frontal Penetrante: <strong>Khao-youn</strong></div>

<div>Joelhada em Escada: <strong>Yiep-khao</strong></div>

<div>&nbsp;</div>

<h3 style="text-align:center"><strong>SOCOS - MATs</strong></h3>

<div>Jab: <strong>Mat nueng</strong></div>

<div>Direto: <strong>Mat trong</strong></div>

<div>Gancho: <strong>Mat wiang san</strong></div>

<div>Cruzado:<strong>Mat trong</strong></div>

<div>Soco em Salto: <strong>Kradot chok</strong></div>

<div>Punho Rotativo: <strong>Mat wiang soi</strong></div>

<div>&nbsp;</div>

<h3 style="text-align:center"><strong>COTOVELADAS - SOKs</strong></h3>

<div>Cotovelo Horizontal: <strong>Sok tat</strong></div>

<div>Cotovelo Obl&iacute;quo: <strong>Khao chiang</strong></div>

<div>Cotovelo Circular ao Tronco: <strong>Sok ti</strong></div>

<div>Cotovelo de cima para baixo: <strong>Sok ti</strong></div>

<div>Cotovelo de baixo para cima: <strong>Sok Ngat</strong></div>

<div>Cotovelo Frontal: <strong>Sok phung</strong></div>

<div>Reverso de Cotovelo Horizontal: <strong>Sok wiang klap</strong></div>

<div>Cotovelo Rotativo: <strong>Mat wiang klap</strong></div>

<div>Cotovelo Rotativo com Retorno: <strong>Sok klap</strong></div>

<div>Dupla Cotovelada: <strong>Sok klap khu</strong></div>

<div>Cotovelo em salto descendo: <strong>Kradot sok</strong></div>
</div>
</div>
', '2020-08-17'),
(5, 'Karate', '<div class="container text-center">
<h1 style="text-align:center"><u><strong><span style="color:#c0392b">T&eacute;cnicas do karate Kenshydokan</span></strong></u></h1>
</div>

<div class="container">
<h2 style="text-align:center"><strong>Tsuki Waza - T&eacute;cnicas de Soco</strong></h2>

<p style="text-align:center">&nbsp;</p>

<h3 style="text-align:center"><u><strong>Seiken Oi Tsuki:</strong></u></h3>

<h2 style="text-align:center"><strong><a href="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/78a5261.jpg" target="_blank"><img alt="" src="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/78a5261.jpg" style="height:250px; width:333px" /></a></strong></h2>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">&Eacute; um soco reto que parte da cintura com a palma da m&atilde;o para cima que ao percorrer o trajeto o punho rotaciona at&eacute; a palma da m&atilde;o ficar virada para baixo, atingindo seu oponente.</span></span></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">Existem tr&ecirc;s maneiras principais de executar esse soco. S&atilde;o elas:</span></span></p>

<ol>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Jodan Seiken Oi Tsuki:</span></strong><span style="font-size:12.0pt"> Soco reto avan&ccedil;ando do pesco&ccedil;o pra cima.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Chudan Seiken Oi Tsuki:</span></strong><span style="font-size:12.0pt"> Soco reto avan&ccedil;ando na altura do t&oacute;rax.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Guedan Seiken Oi Tsuki:</span></strong><span style="font-size:12.0pt"> Soco reto avan&ccedil;ando da cintura pra baixo.</span></span></li>
</ol>

<p style="text-align:center">&nbsp;</p>

<h3 style="text-align:center"><u><strong>Seiken Gyaku Oi Tsuki:</strong></u></h3>

<p style="text-align:center"><a href="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/8b20fbf.jpg" target="_blank"><img alt="" src="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/8b20fbf.jpg" style="height:250px; width:333px" /></a></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">&Eacute; um outro soco reto que parte da cintura com a palma da m&atilde;o para cima que ao percorrer o trajeto o punho rotaciona at&eacute; a palma da m&atilde;o ficar virada para baixo, atingindo seu oponente, s&oacute; que dessa vez com a m&atilde;o inversa a da perna que est&aacute; avan&ccedil;ada.</span></span></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">Tamb&eacute;m existem tr&ecirc;s maneiras principais de executar esse soco. S&atilde;o elas:</span></span></p>

<ol>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Jodan Seiken Gyaku Oi Tsuki:</span></strong><span style="font-size:12.0pt"> Soco reto avan&ccedil;ando do pesco&ccedil;o pra cima.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Chudan Seiken Gyaku Oi Tsuki:</span></strong><span style="font-size:12.0pt"> Soco reto avan&ccedil;ando na altura do t&oacute;rax.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Guedan Seiken&nbsp;Gyaku Oi Tsuki:</span></strong><span style="font-size:12.0pt"> Soco reto avan&ccedil;ando da cintura pra baixo.</span></span></li>
</ol>

<p style="text-align:center">&nbsp;</p>

<h3 style="text-align:center"><u><strong>Tate Tsuki</strong></u></h3>

<p style="text-align:center"><a href="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/9a56495.jpg" target="_blank"><img alt="" src="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/9a56495.jpg" style="height:250px; width:333px" /></a></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">&Eacute; um soco semi-rotacionado que parte da cintura com a palma da m&atilde;o para cima que ao percorrer o trajeto o punho e ele semi-rotaciona at&eacute; a palma da m&atilde;o ficar virada para o lado, atingindo seu oponente com o punho em p&eacute;.</span></span></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">Existem tr&ecirc;s maneiras principais de executar esse soco. S&atilde;o elas:</span></span></p>

<ol>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Jodan Seiken Tate Tsuki:</span></strong><span style="font-size:12.0pt"> Soco semi-rotacionado avan&ccedil;ando do pesco&ccedil;o pra cima.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Chudan Seiken Tate Tsuki:</span></strong><span style="font-size:12.0pt"> Soco semi-rotacionado avan&ccedil;ando na altura do t&oacute;rax.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Guedan Seiken&nbsp;Tate Tsuki:</span></strong><span style="font-size:12.0pt"> Soco semi-rotacionado avan&ccedil;ando da cintura pra baixo.</span></span></li>
</ol>

<p style="text-align:center">&nbsp;</p>

<h3 style="text-align:center"><u><strong><span style="font-size:11pt"><span style="font-size:12.0pt">Gyaku Tate Tsuki</span></span></strong></u></h3>

<p style="text-align:center"><a href="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/6b34e82.jpg" target="_blank"><img alt="" src="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/6b34e82.jpg" style="height:250px; width:333px" /></a></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">&Eacute; um soco semi-rotacionado que parte da cintura com a palma da m&atilde;o para cima que ao percorrer o trajeto o punho e ele semi-rotaciona at&eacute; a palma da m&atilde;o ficar virada para o lado, atingindo seu oponente com o punho em p&eacute;, s&oacute; que dessa vez com a m&atilde;o inversa a da perna que est&aacute; avan&ccedil;ada.</span></span></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">Existem tr&ecirc;s maneiras principais de executar esse soco. S&atilde;o elas:</span></span></p>

<ol>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Jodan Seiken Gyaku Tate Tsuki:</span></strong><span style="font-size:12.0pt"> Soco semi-rotacionado avan&ccedil;ando do pesco&ccedil;o pra cima.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Chudan Seiken&nbsp;Gyaku Tate Tsuki:</span></strong><span style="font-size:12.0pt"> Soco semi-rotacionado avan&ccedil;ando na altura do t&oacute;rax.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Guedan Seiken&nbsp;Gyaku Tate Tsuki:</span></strong><span style="font-size:12.0pt"> Soco semi-rotacionado avan&ccedil;ando da cintura pra baixo.</span></span></li>
</ol>

<p style="text-align:center">&nbsp;</p>

<h3 style="text-align:center"><u><strong>Seiken Mawashi Tsuki</strong></u></h3>

<p style="text-align:center"><a href="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/10f20e90.jpg"><img alt="" src="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/10f20e90.jpg" style="height:250px; width:333px" /></a></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">&Eacute; um soco com curva que parte do kamae(guarda de lutas em p&eacute;) que ao percorrer o trajeto o punho rotaciona at&eacute; a palma da m&atilde;o ficar virada para baixo, atingindo seu oponente na lateral.</span></span></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">Existem duas maneiras principais de executar esse soco pois abaixo da cintura esse soco n&atilde;o tem efeito. S&atilde;o elas:</span></span></p>

<ol>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Jodan Seiken Mawashi Tsuki:</span></strong><span style="font-size:12.0pt"> Soco com curva do pesco&ccedil;o pra cima.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Chudan Seiken Mawashi Tsuki:</span></strong><span style="font-size:12.0pt"> Soco com curva na altura do t&oacute;rax.</span></span></li>
</ol>

<p style="text-align:center">&nbsp;</p>

<h3 style="text-align:center"><u><strong><span style="font-size:11pt"><span style="font-size:12.0pt">Shita Tsuki</span></span></strong></u></h3>

<p style="text-align:center"><a href="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/11584795.jpg" target="_blank"><img alt="" src="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/11584795.jpg" style="height:250px; width:333px" /></a></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">&Eacute; um soco n&atilde;o rotacionado que parte da cintura com a palma da m&atilde;o para cima que ao percorrer o trajeto o punho n&atilde;o </span></span><span style="font-size:11pt"><span style="font-size:12.0pt">rotaciona mantendo se na mesma posi&ccedil;&atilde;o, atingindo seu oponente com o punho na posi&ccedil;&atilde;o inicial.</span></span></p>

<p style="text-align:center"><span style="font-size:11pt"><span style="font-size:12.0pt">Existem tr&ecirc;s maneiras principais de executar esse soco. S&atilde;o elas:</span></span></p>

<ol>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Jodan Seiken Shita Tsuki:</span></strong><span style="font-size:12.0pt"> Soco sem rotacionar o punho avan&ccedil;ando do pesco&ccedil;o pra cima.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Chudan Seiken&nbsp;ShitaTsuki:</span></strong><span style="font-size:12.0pt"> Soco sem rotacionar o punho avan&ccedil;ando na altura do t&oacute;rax.</span></span></li>
	<li style="text-align:center"><span style="font-size:11pt"><strong><span style="font-size:12.0pt">Guedan Seiken&nbsp;Shita Tsuki:</span></strong><span style="font-size:12.0pt"> Socosem rotacionar o punho avan&ccedil;ando da cintura pra baixo.</span></span></li>
</ol>

<p style="text-align:center">&nbsp;</p>

<h2 style="text-align:center"><strong>Uke Waza - T&eacute;cnicas de Defesa</strong></h2>

<h3 style="text-align:center"><u><strong>Guedan Barai</strong></u></h3>

<p style="text-align:center"><a href="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/12334168.jpg" target="_blank"><img alt="" src="https://kenshydokan.org.br/admin/ckeditor/plugins/imageuploader/uploads/12334168.jpg" style="height:250px; width:333px" /></a></p>

<p style="text-align:center">Normalmente essa &eacute; a primeira t&eacute;cnica ou primeira defesa que aprendemos no karate, ao executar voc&ecirc; sobe o bra&ccedil;o na autura do ombro, e logo em seguida desse como uma varrida defendendo de dentro para fora.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>
</div>
', '2020-08-18'),
(6, 'Kickboxing', '<div class="container">
<h1 style="text-align:center"><strong><u><span style="color:#c0392b">Kickboxing</span></u></strong></h1>

<p><strong>Full contact - Contato total</strong> Regras de contato total, ou kickboxing americano, &eacute; essencialmente uma mistura de boxe ocidental e karat&ecirc; tradicional. Os kickboxers masculinos s&atilde;o de peito nu vestindo cal&ccedil;as de kickboxing e equipamentos de prote&ccedil;&atilde;o, incluindo: protetor de boca, m&atilde;o-wraps, 10 oz (280 g). luvas de boxe, guarda-costas, caneleiras e chuteiras e capacete de prote&ccedil;&atilde;o (para amadores e menores de 16 anos). Os kickboxers femininos usar&atilde;o um suti&atilde; esportivo e prote&ccedil;&atilde;o para o peito, al&eacute;m do vestu&aacute;rio masculino / equipamento de prote&ccedil;&atilde;o.<br />
Lutadores not&aacute;veis â€‹â€‹sob regras de contato total incluem Marek Piotrowski, Dennis Alexio, Joe Lewis, Rick Roufus, Jean-Yves Theriault, Benny Urquidez, Bill Wallace e Don &quot;The Dragon&quot; Wilson.<br />
Regras:<br />
Os oponentes podem bater uns nos outros com socos e chutes, golpeando acima da cintura.<br />
Cotovelos e joelhos s&atilde;o proibidos e o uso das canelas raramente &eacute; permitido.<br />
Luta de clinch e luta s&atilde;o proibidos, mas as varreduras s&atilde;o legais, mas variam dependendo do &aacute;rbitro.<br />
As lutas s&atilde;o geralmente de 3 a 12 rodadas (com dura&ccedil;&atilde;o de 2 a 3 minutos cada), com um descanso de 1 minuto entre as rodadas.<br />
<strong>Semi-Contact - Semi-Contato</strong> Regras de semi-contato ou Pontos de Combate, &eacute; a variante do kickboxing americano mais parecido com o karat&ecirc;, pois consiste em lutar com o objetivo de marcar pontos com &ecirc;nfase na entrega, velocidade e t&eacute;cnica. Sob tais regras, as lutas s&atilde;o realizadas no tatami, apresentando os cintos para classificar os lutadores em ordem de experi&ecirc;ncia e habilidade. Os kickboxers masculinos usam camisas e cal&ccedil;as de kickboxing, bem como equipamentos de prote&ccedil;&atilde;o, incluindo: protetor de boca, envolt&oacute;rios de m&atilde;o, 10 oz (280 g). luvas de boxe, guarda-costas, caneleiras, chuteiras e arn&ecirc;s. Os kickboxers femininos usar&atilde;o um suti&atilde; esportivo e prote&ccedil;&atilde;o para o peito, al&eacute;m do vestu&aacute;rio masculino / equipamento de prote&ccedil;&atilde;o.<br />
Lutadores not&aacute;veis â€‹â€‹sob regras de semi-contato incluem Raymond Daniels, Michael Page e Gregorio Di Leo.<br />
Regras:<br />
Os lutadores podem marcar atrav&eacute;s de socos ou chutes, golpeando acima da cintura e varreduras de p&eacute;, executado abaixo do tornozelo.<br />
Socos, pontap&eacute;s e varreduras de p&eacute; recebem 1 ponto. Pontap&eacute;s na cabe&ccedil;a ou chutes no corpo recebem 2 pontos. Chutes saltantes na cabe&ccedil;a recebem 3 pontos.<br />
Pontap&eacute;s e machadadas s&atilde;o permitidos, mas devem ser executados com a sola do p&eacute;.<br />
O uso das canelas raramente &eacute; permitido, exceto para t&eacute;cnicas de salto e fia&ccedil;&atilde;o.<br />
Cotovelos, joelhos e backfists girat&oacute;rios s&atilde;o proibidos.<br />
Luta de clinch, lances e varreduras (com exce&ccedil;&atilde;o de varreduras de p&eacute;) s&atilde;o proibidos.<br />
As lutas geralmente duram 3 rodadas (com dura&ccedil;&atilde;o de 2 a 3 minutos cada) com um descanso de 1 minuto entre as rodadas.<br />
<strong>Shoot boxing</strong>Shoot boxing &eacute; um estilo &uacute;nico de kickboxing popular no Jap&atilde;o que utiliza submiss&otilde;es em p&eacute;, como estrangulamentos, armlock e pulseiras, al&eacute;m de chutes, socos, joelhos e arremessos. Os lutadores masculinos t&ecirc;m o peito nu usando cal&ccedil;as apertadas e equipamento de prote&ccedil;&atilde;o, incluindo: protetores bucais, protetores de m&atilde;o, 280 g (10 oz). luvas de boxe e guarda-costas. Os kickboxers femininos usar&atilde;o um suti&atilde; esportivo e prote&ccedil;&atilde;o para o peito, al&eacute;m do vestu&aacute;rio masculino / equipamento de prote&ccedil;&atilde;o.<br />
Lutadores not&aacute;veis sob as regras do boxe incluem Rena Kubota, Kenichi Ogata, Hiroki Shishido, Andy Souwer e Ai Takahashi.<br />
Regras:<br />
Os opositores podem atacar um ao outro com socos, chutes, incluindo chutes abaixo da cintura, exceto na virilha e nos joelhos.<br />
Cotovelos s&atilde;o proibidos.<br />
Luta de clinch, lances e varreduras s&atilde;o permitidos.<br />
Submiss&otilde;es permanentes s&atilde;o permitidas.<br />
As lutas s&atilde;o de 3 rodadas (dura&ccedil;&atilde;o de 3 minutos cada) com um descanso de 1 minuto entre as rodadas.</p>

<h3><strong>T&eacute;cnicas</strong></h3>

<p><strong>T&eacute;cnicas de Soco</strong></p>

<p>Obs.: No Ingl&ecirc;s as palavras s&atilde;o normalmente pronunciadas de forma diferente do portugu&ecirc;s.</p>

<p><br />
<strong>Jab</strong> - soco direto da m&atilde;o da frente<br />
<strong>Cross</strong> - soco direto da m&atilde;o de tr&aacute;s<br />
<strong>hook</strong> - gancho<br />
<strong>Uppercut</strong> - soco ascendente atingindo o queixo<br />
<strong>Cross counter</strong> - soco cruzado<br />
<strong>Flying punch</strong> - soco voador<br />
<strong>Over hand</strong> - soco semi-circular<br />
<strong>T&eacute;cnicas de ChuteAxe kick</strong> - chute de pis&atilde;o (sola do p&eacute;)<br />
<strong>Back kick</strong> - chute para tr&aacute;s<br />
<strong>Front kick</strong> - Chute frontal<br />
<strong>Hook kick</strong> - chute estendendo a perna para desser o calcanhar<br />
<strong>Roundhouse kick</strong> or <strong>circle kick</strong> - chute circular<br />
<strong>Semi-circular kick</strong> - Chute semi-circular<br />
<strong>Sweeping</strong> - varredura (dar rastera)<br />
<strong>Side kick</strong> - chute lateral (yoko geri)<br />
<strong>T&eacute;cnicas de JoelhadaFlying knee</strong> - joelhada voadora<br />
<strong>Side knee</strong> - Joelhada lateral<br />
<strong>Straight Knee</strong> - joelhada frontal<br />
<strong>T&eacute;cnicas de CotoveladaDownward Elbow</strong> - Cotovelada para baixo<br />
<strong>Side Elbow</strong> - Cotovelada lateral<br />
<strong>Upward elbow</strong> - Cotovelada para cima</p>
</div>
', '2020-08-18');

-- --------------------------------------------------------

--
-- Estrutura da tabela `postagens`
--

CREATE TABLE `postagens` (
  `id_postagem` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `titulo` varchar(200) NOT NULL,
  `conteudo` text NOT NULL,
  `situacao` varchar(4) NOT NULL,
  `data` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `postagens`
--

INSERT INTO `postagens` (`id_postagem`, `id_usuario`, `titulo`, `conteudo`, `situacao`, `data`) VALUES
(4, 1, 'O Kiai', '<div class="border container mt-5">
<h1 style="text-align:center"><u><strong><span style="color:#c0392b">O Kiai</span></strong></u></h1>

<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; O kiai consiste em um Mantra ou Vibra&ccedil;&atilde;o sonora que ajuda a liberar a mente dos seus condicionamentos que vibra e auxilia na concentra&ccedil;&atilde;o do Hara, com o sentido de desenvolver a sua for&ccedil;a e assim direcionar o fluxo do ki. O lutador, ao efetuar seu golpe, <strong>grita Kiai,</strong> o som vigoroso que se origina no baixo abd&ocirc;men e concentra a energia nos movimentos, liberando um poder de ordem f&iacute;sica e espiritual. Miyamoto Musashi , ensinava que o grito emitido durante uma luta e uma express&atilde;o de for&ccedil;a e demonstra a energia do lutador . Ele classificou tr&ecirc;s tipo de gritos de acordo com o seu objetivo. O grito inicial em tom grave, para assustar o adversaria, o grito no meio da luta, que deve sair do ventre, do Hara, com toda a sua for&ccedil;a,e o ultimo grito e de vitoria.</p>
</div>
', 'sim', '2020-08-16'),
(5, 1, 'O Bushi-Do', '<div class="border container mt-5">
<h1 style="text-align:center"><u><span style="color:#c0392b">O Bushi-D&ocirc;</span></u></h1>

<p style="text-align:center"><u><span style="color:#c0392b"><a href="https://kenshydokan.org.br/ckeditor/plugins/imageuploader/uploads/8f1727f.jpg" target="_blank"><img alt="" class="img-fluid" src="https://kenshydokan.org.br/ckeditor/plugins/imageuploader/uploads/8f1727f.jpg" style="height:285px; width:229px" /></a></span></u></p>

<p>Aos samurais &eacute; atribu&iacute;do o C&oacute;digo de Honra (Bushi-D&ocirc;), que pode ser traduzido da seguinte forma:<br />
<strong><em>&ldquo;N&atilde;o tenho pais, fa&ccedil;o do c&eacute;u e da terra os meus pais. </em> </strong><br />
<em><strong>N&atilde;o tenho lar, fa&ccedil;o do Tandem sede do meu esp&iacute;rito, o meu lar. </strong> </em><br />
<strong><em>N&atilde;o tenho poder divino, fa&ccedil;o da honestidade o meu poder. </em> </strong><br />
<em><strong>N&atilde;o tenho meios, fa&ccedil;o da docilidade os meus meios. </strong> </em><br />
<strong><em>N&atilde;o tenho poder m&aacute;gico, fa&ccedil;o da minha for&ccedil;a interior a minha magia. </em> </strong><br />
<strong><em>N&atilde;o tenho vida nem morte, fa&ccedil;o do eterno a minha exist&ecirc;ncia, minha vida e minha morte. </em> </strong><br />
<strong><em>N&atilde;o tenho corpo, fa&ccedil;o da coragem o meu corpo. </em> </strong><br />
<strong><em>N&atilde;o tenho olhos, onde est&aacute; a luz est&atilde;o os meus olhos. </em> </strong><br />
<strong><em>N&atilde;o tenho ouvidos, fa&ccedil;o da sensibilidade a minha audi&ccedil;&atilde;o. </em> </strong><br />
<strong><em>N&atilde;o tenho membros, os fa&ccedil;o da prontid&atilde;o dos meus movimentos espont&acirc;neos. N&atilde;o tenho leis, fa&ccedil;o da autoprote&ccedil;&atilde;o a minha lei. </em> </strong><br />
<strong><em>N&atilde;o tenho estrat&eacute;gias, fa&ccedil;o do acaso os meus prop&oacute;sitos. </em> </strong><br />
<strong><em>N&atilde;o tenho forma, fa&ccedil;o da ast&uacute;cia a minha forma. </em> </strong><br />
<em><strong>N&atilde;o tenho princ&iacute;pios, fa&ccedil;o da adaptabilidade os meus princ&iacute;pios. </strong> </em><br />
<strong><em>N&atilde;o tenho milagres, fa&ccedil;o da justi&ccedil;a os meus milagres. </em> </strong><br />
<strong><em>N&atilde;o tenho t&aacute;ticas, fa&ccedil;o da rapidez a minha t&aacute;tica. </em> </strong><br />
<strong><em>N&atilde;o tenho amigos, fa&ccedil;o da minha mente os meus amigos. </em> </strong><br />
<strong><em>N&atilde;o tenho inimigos, fa&ccedil;o da imprud&ecirc;ncia o meu inimigo. </em> </strong><br />
<strong><em>N&atilde;o tenho armaduras, fa&ccedil;o da benevol&ecirc;ncia e retid&atilde;o a minha armadura. </em> </strong><br />
<strong><em>N&atilde;o tenho castelo, fa&ccedil;o da mente im&oacute;vel, o Grande Esp&iacute;rito, o meu castelo. </em> </strong><br />
<strong><em>N&atilde;o tenho arma, fa&ccedil;o do sonho onde fica o al&eacute;m dos pensamentos a minha espada&rdquo;.</em> </strong><br />
De um modo geral, o Bushi-D&ocirc;, encerra toda a hist&oacute;ria das artes marciais. A palavra Bushi-D&ocirc;, quer dizer: &ldquo;Caminho do Guerreiro&rdquo; ou &ldquo;Caminho das Artes Marciais&rdquo;.<br />
<strong>OS SETE PRINC&Iacute;PIOS DO BUSHI-D&Ocirc;</strong><br />
(O Caminho do Guerreiro) O &ldquo;Caminho do Samurai&rdquo; ou o &ldquo;Caminho do Guerreiro&rdquo; &eacute; influenciado pela fus&atilde;o Budo-Xinto&iacute;sta, pode ser resumida em sete princ&iacute;pios essenciais, sendo eles:<br />
<strong>1. GI</strong> - A verdade. A atitude justa. Quando devemos dormir, devemos dormir, quando devemos lutar, devemos lutar;<br />
<strong>2. YU</strong> - Bravura;
(6, 1, 'Shihan, Sensei, Sempai e Kohai.', '<h1 style="text-align:center"><span style="color:#c0392b"><u><strong>Shihan, Sensei, Sempai e Kohai.</strong></u></span></h1>

<p style="text-align:center"><img alt="" class="img-fluid" src="https://kenshydokan.org.br/ckeditor/plugins/imageuploader/uploads/7dcdd1f.jpg" style="height:311px; width:416px" /></p>

<div>
<p>Hoje em dia algumas pessoas ficam perdidas sobre como se referir a um mestre ou companheiro de treino, hoje tentarei dar uma esclarecida sobre isso.</p>

<ul>
	<li><strong>Kohai :</strong> Esse &eacute; um aluno em fase de aprendizagem, um praticamente iniciante, estes alunos recebem esse titulo assim que decidem treinar karat&ecirc;, &eacute; um exemplo a ser seguido, pois saiu de sua zona de conforto para praticar algo que s&oacute; trar&aacute; benef&iacute;cios para sua vida mental e f&iacute;sica.</li>
	<li><strong>Sempai:</strong> Esse &eacute; o aluno exemplar, &eacute; aquele aluno mais graduado e mais antigo no dojo, esse tipo de aluno &eacute; o mais respeitado, n&atilde;o s&oacute; por sua gradua&ccedil;&atilde;o mas sim pelo seu car&aacute;ter e dedica&ccedil;&atilde;o, e com isso vem responsabilidades tais como auxiliar os outros alunos e etc.</li>
	<li><strong>Sensei</strong><strong> :</strong> Esse aluno que agora tamb&eacute;m &eacute; um professor se formou e agora &eacute; um graduado que pode dar aulas completas e independente para os seus alunos, mas n&atilde;o acaba por a&iacute;, agora que esse aluno &eacute; faixa preta as responsabilidades aumentaram e agora come&ccedil;a um novo ciclo de aprendizagem para ele inclusive na pratica.</li>
	<li><strong>Shihan</strong><strong>:</strong> Esse &eacute; um titulo conseguido por poucos, s&oacute; aqueles que perseveraram no karat&ecirc; consegue chegar aqui, o Shihan significa &quot;Mestre Exemplar&quot;, ou seja voc&ecirc; que &eacute; aluno deve ser a sombra desse professor pois assim que voc&ecirc; conseguir&aacute; vencer no karat&ecirc;.</li>
</ul>
</div>
', 'sim', '2020-08-16'),
(7, 1, 'O Zen no Karatê', '<div class="border container mt-5">
<h1 style="text-align:center"><u><strong><span style="color:#c0392b">O Zen no Karat&ecirc;</span></strong></u></h1>

<p>Os tribunais guerreiros do jap&atilde;o do per&iacute;odo Kamakura ao per&iacute;odo Muromachi incentivaram o estudo austero do Zen pelos Samurais, e o Zen andava de bra&ccedil;os dados com a arte de combate. No Zen n&atilde;o ha elabora&ccedil;&atilde;o nem misticismo, ele vai direto &aacute; natureza das coisas. N&atilde;o h&aacute; cerimonias nem prega&ccedil;&otilde;es. A promessa do Zen a de car&aacute;ter exclusivamente pessoal.</p>

<p>A ilumina&ccedil;&atilde;o do Zen n&atilde;o implica em modifica&ccedil;&otilde;es de comportamento, mas sim, na compreens&atilde;o da natureza na vida comum. O seu objetivo, o seu ponto final &eacute; inicio, e a grande virtude da simplicidade. Devemos Aplica o golpe no oponente tal como ele nos aplica. Isso implica no equil&iacute;brio absoluto, na aus&ecirc;ncia da raiva. O inimigo deve ser tratado como um convidado de honra. A vida deve ser abandonada e o medo deve ser descartado.</p>

<p>A primeira t&eacute;cnica &eacute; a ultima, o disc&iacute;pulo e o mestre se comportam da mesma maneira. O conhecimento &eacute; um ciclo completo. Os ensinamentos do karat&ecirc; s&atilde;o muitas vezes semelhante as viol&ecirc;ncias e agress&otilde;es verbais a que os aprendizes do zen se sujeitam. Assolada por duvidas e infelicidade sua mente e seu espirito se desorientam e os aprendizes s&atilde;o levados paulatinamente a percep&ccedil;&atilde;o e a compreens&atilde;o por seu mestre.</p>
</div>
', 'sim', '2020-08-16'),
(14, 1, 'Kihon Geiko', '<h1 style="text-align:center"><span style="font-size:11pt"><span style="font-size:36px"><u><span style="color:#c0392b"><strong>Kihon Geiko</strong></span></u></span> </span></h1>

<p><span style="font-size:11pt">O <strong>Kihon Geiko</strong> s&atilde;o t&eacute;cnicas repetitivas executadas sem avan&ccedil;ar, na base <em>Sanchin Dachi</em>. Essas t&eacute;cnicas s&atilde;o fundamentais para aprimoramento de novas t&eacute;cnicas aprendidas, para treina-las antes de usa-las em um treino com parceiro. &Eacute; essencial que o aluno j&aacute; vai aprendendo as nomenclaturas das t&eacute;cnicas falada em japon&ecirc;s pelo seu professor. Normalmente cada T&eacute;cnica &eacute; executada de 20 &aacute; 30 vezes.</span></p>
', 'sim', '2020-09-26'),
(15, 1, 'UchiKomi', '<h1 style="text-align:center"><u><span style="color:#c0392b"><span style="font-size:36px">UchiKomi</span></span></u></h1>

<p><span style="font-size:11pt"><span style="font-size:12.0pt">O Uchikomi &eacute; uma das varias maneiras de se treinar proje&ccedil;&atilde;o(Nage Waza), e existem v&aacute;ria maneiras de se treinar uchikomi tamb&eacute;m. Ao treinar voc&ecirc; executa a t&eacute;cnica de proje&ccedil;&atilde;o v&aacute;rias vezes sozinho ou com um parceiro antes de completa-la totalmente, isso &eacute; feito varias vezes, normalmente acima de 30 vezes.</span></span></p>

<p><span style="font-size:11pt"><span style="font-size:12.0pt">Ao executar o uchikomi de modo est&aacute;tico, voc&ecirc; executas as t&eacute;cnicas de proje&ccedil;&otilde;es sem avan&ccedil;ar ou recuar, &eacute; sempre feito naquele mesmo ponto; Ao executar de modo em movimento e que voc&ecirc; vai se movimentando circularmente. Ao executar de modo em sombra voc&ecirc; executa sozinho avan&ccedil;ando e recuando.</span></span></p>
', 'sim', '2020-09-26'),
(16, 1, 'Randori', '<h1 style="text-align:center"><span style="color:#c0392b"><u><span style="font-size:36px">Randori</span></u></span></h1>

<p><span style="font-size:12pt">Randori &eacute; um modo de treinar que evolui tanto a mente quanto o f&iacute;sico, nesse tipo de treinamento voc&ecirc; usa toda a sua habilidade e conhecimento em um treino de luta a onde n&atilde;o existe pontua&ccedil;&atilde;o e nem juiz, mesmo que voc&ecirc; ou seu parceiro execute um ippon a luta continuara com voc&ecirc;s treinando. &Eacute; uma &oacute;tima hora para voc&ecirc; colocar as suas t&eacute;cnicas em pr&aacute;tica. Esse tipo de treinamento &eacute; usada muito no Jud&ocirc; e Ju Jitsu.</span></p>
', 'sim', '2020-09-26'),
(17, 1, 'Renraku Renka Waza', '<h1 style="text-align:center"><span style="color:#c0392b"><span style="font-size:36px">Renraku Renka Waza</span></span></h1>

<p><span style="font-size:12pt">Nesse tipo de treinamento usamos tanto t&eacute;cnicas de proje&ccedil;&atilde;o(Nage Waza) quanto t&eacute;cnicas de Imobiliza&ccedil;&atilde;o(OssaeKomi Waza), nesse treinamento usamos uma variedade grande de t&eacute;cnicas come&ccedil;ando com uma proje&ccedil;&atilde;o que supostamente n&atilde;o funcionou passando para outra proje&ccedil;&atilde;o que resulta numa queda, partindo para uma imobiliza&ccedil;&atilde;o.</span></p>
', 'sim', '2020-09-26'),
(18, 1, 'Kihon', '<h1 style="text-align:center"><span style="color:#c0392b"><span style="font-size:36px">Kihon</span></span></h1>

<p style="text-align:center"><span style="color:#c0392b"><span style="font-size:36px"><a href="https://kenshydokan.org.br/ckeditor/plugins/imageuploader/uploads/5413095.png" target="_blank"><img alt="" class="img-fluid" src="https://kenshydokan.org.br/ckeditor/plugins/imageuploader/uploads/5413095.png" style="height:320px; width:640px" /></a></span></span></p>

<p>No treino de Kihon, aprimoramos as nossas t&eacute;cnicas que ser&atilde;o usadas no Kata. Kihon significa fundamento, ou seja, &eacute; aquilo que &eacute; essencial no karat&ecirc;. Nesse tipo de treinamento o professor diz os nomes de cada uma das t&eacute;cnicas que ser&atilde;o executadas a cada passo, e o nome dessas t&eacute;cnicas ser&aacute; dita em japon&ecirc;s, j&aacute; &eacute; bom o aluno ir aprendendo as nomenclaturas dessas t&eacute;cnicas. As t&eacute;cnicas do Kihon s&atilde;o executadas avan&ccedil;ando ou recuando.</p>
', 'sim', '2020-09-26'),
(25, 1, 'Tameshiwari', '<h1 style="text-align:center"><span style="color:#c0392b"><strong>Tameshiwari</strong></span></h1>

<p style="text-align:center"><span style="color:#c0392b"><strong><img alt="" class="img-fluid" src="https://kenshydokan.org.br/ckeditor/plugins/imageuploader/uploads/62c4441.jpg" style="height:162px; width:253px" /></strong></span></p>

<p><span style="font-size:11pt"><span style="font-family:&quot;Calibri&quot;,sans-serif">Para executarmos esse tipo de treinamento, temos que estar com o corpo e mente preparado. Esse &eacute; o tipo de treinamento que te prepara para conseguir fazer quebramentos, por exemplo de tabua, telha, gelo e etc. para isso voc&ecirc; tem que estar com a sua mente preparada, para n&atilde;o ter medo e executar a t&eacute;cnica errado e acabar se lesionando ou nem executa-la. Tamb&eacute;m precisa estar com o corpo preparado, com resist&ecirc;ncia o suficiente para aguentar o impacto e for&ccedil;a para poder quebrar.</span></span></p>
', 'sim', '2020-10-03');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `adms`
--
ALTER TABLE `adms`
  ADD PRIMARY KEY (`id_adm`);

--
-- Índices para tabela `aulas`
--
ALTER TABLE `aulas`
  ADD PRIMARY KEY (`id_aula`);

--
-- Índices para tabela `campeonatos`
--
ALTER TABLE `campeonatos`
  ADD PRIMARY KEY (`id_camp`);

--
-- Índices para tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Índices para tabela `curso`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`);

--
-- Índices para tabela `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id_estado`);

--
-- Índices para tabela `filiados`
--
ALTER TABLE `filiados`
  ADD PRIMARY KEY (`id_filiado`);

--
-- Índices para tabela `fotos`
--
ALTER TABLE `fotos`
  ADD PRIMARY KEY (`id_foto`);

--
-- Índices para tabela `galeria`
--
ALTER TABLE `galeria`
  ADD PRIMARY KEY (`id_galeria`);

--
-- Índices para tabela `graduacao`
--
ALTER TABLE `graduacao`
  ADD PRIMARY KEY (`id_graduacao`);

--
-- Índices para tabela `imagens`
--
ALTER TABLE `imagens`
  ADD PRIMARY KEY (`id_imagem`);

--
-- Índices para tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id_mensagem`);

--
-- Índices para tabela `paginas`
--
ALTER TABLE `paginas`
  ADD PRIMARY KEY (`id_pag`);

--
-- Índices para tabela `postagens`
--
ALTER TABLE `postagens`
  ADD PRIMARY KEY (`id_postagem`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `adms`
--
ALTER TABLE `adms`
  MODIFY `id_adm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `aulas`
--
ALTER TABLE `aulas`
  MODIFY `id_aula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `campeonatos`
--
ALTER TABLE `campeonatos`
  MODIFY `id_camp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `estados`
--
ALTER TABLE `estados`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `filiados`
--
ALTER TABLE `filiados`
  MODIFY `id_filiado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT de tabela `fotos`
--
ALTER TABLE `fotos`
  MODIFY `id_foto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=237;

--
-- AUTO_INCREMENT de tabela `galeria`
--
ALTER TABLE `galeria`
  MODIFY `id_galeria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `graduacao`
--
ALTER TABLE `graduacao`
  MODIFY `id_graduacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `imagens`
--
ALTER TABLE `imagens`
  MODIFY `id_imagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=292;

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id_mensagem` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `paginas`
--
ALTER TABLE `paginas`
  MODIFY `id_pag` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `postagens`
--
ALTER TABLE `postagens`
  MODIFY `id_postagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

--
-- Estrutura da tabela `dojos`
--

CREATE TABLE `dojos` (
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