-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 27/04/2024 às 19:40
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `chat_app_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `arquivos`
--

CREATE TABLE `arquivos` (
  `arquivo_id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `nome_arquivo` varchar(255) NOT NULL,
  `caminho_arquivo` varchar(255) NOT NULL,
  `tipo_arquivo` varchar(45) NOT NULL,
  `tamanho_arquivo` int(11) NOT NULL,
  `data_envio` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `arquivos`
--

INSERT INTO `arquivos` (`arquivo_id`, `chat_id`, `nome_arquivo`, `caminho_arquivo`, `tipo_arquivo`, `tamanho_arquivo`, `data_envio`) VALUES
(15, 11, 'nome_do_arquivo', 'caminho/do/arquivo', 'tipo_do_arquivo', 0, '2024-04-11 14:57:56'),
(20, 25, 'chat_app_db (1).sql', 'uploads/chat_app_db (1).sql', 'sql', 4214, '0000-00-00 00:00:00'),
(21, 25, 'arquivos (1).sql', 'uploads/arquivos (1).sql', 'sql', 1374, '0000-00-00 00:00:00'),
(22, 25, 'chat_app_db (1).sql', 'uploads/chat_app_db (1).sql', 'sql', 4214, '0000-00-00 00:00:00'),
(23, 3, 'arquivos (1).sql', 'uploads/arquivos (1).sql', 'sql', 1374, '0000-00-00 00:00:00'),
(24, 25, 'arquivos (1).sql', 'uploads/arquivos (1).sql', 'sql', 1374, '0000-00-00 00:00:00'),
(25, 25, 'chat_app_db (1).sql', 'uploads/chat_app_db (1).sql', 'sql', 4214, '0000-00-00 00:00:00'),
(26, 25, 'arquivos (1).sql', 'uploads/arquivos (1).sql', 'sql', 1374, '0000-00-00 00:00:00'),
(27, 25, 'chat_app_db (1).sql', 'uploads/chat_app_db (1).sql', 'sql', 4214, '0000-00-00 00:00:00'),
(28, 25, 'arquivos (1).sql', 'uploads/arquivos (1).sql', 'sql', 1374, '0000-00-00 00:00:00'),
(29, 25, 'logo.jpg', 'uploads/logo.jpg', 'jpg', 104395, '0000-00-00 00:00:00'),
(30, 25, 'logo.jpg', 'uploads/logo.jpg', 'jpg', 104395, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `chats`
--

CREATE TABLE `chats` (
  `chat_id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_group_message` tinyint(1) NOT NULL DEFAULT 0,
  `opened` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `chats`
--

INSERT INTO `chats` (`chat_id`, `from_id`, `to_id`, `message`, `is_group_message`, `opened`, `created_at`) VALUES
(3, 1, 2, 'oie', 0, 1, '2024-04-10 23:54:06'),
(4, 3, 1, 'opa\n', 0, 1, '2024-04-11 00:45:22'),
(5, 1, 3, 'opa', 0, 1, '2024-04-11 01:23:55'),
(6, 1, 3, 'opa', 0, 1, '2024-04-11 01:36:43'),
(7, 1, 3, 'opa', 0, 1, '2024-04-11 01:39:42'),
(8, 1, 3, 'opa', 0, 1, '2024-04-11 01:40:19'),
(9, 1, 3, 'opa', 0, 1, '2024-04-11 01:46:24'),
(10, 1, 3, 'opa', 0, 1, '2024-04-11 01:51:25'),
(11, 1, 3, 'opa', 0, 1, '2024-04-11 01:54:07'),
(12, 1, 3, 'opa', 0, 1, '2024-04-11 02:04:07'),
(13, 3, 1, 'opa\n', 0, 1, '2024-04-11 02:25:26'),
(14, 1, 2, 'opa\n', 0, 1, '2024-04-11 02:30:40'),
(15, 1, 3, 'opa\n', 0, 1, '2024-04-11 02:30:50'),
(16, 1, 3, '😂', 0, 1, '2024-04-11 02:46:52'),
(17, 1, 3, '😀', 0, 1, '2024-04-11 02:46:54'),
(18, 1, 3, 'opa', 0, 1, '2024-04-11 02:54:15'),
(19, 1, 3, '😂😀', 0, 1, '2024-04-11 02:55:04'),
(21, 1, 3, '😅', 0, 1, '2024-04-11 03:02:51'),
(22, 1, 3, '😚', 0, 1, '2024-04-11 03:13:43'),
(23, 1, 3, 'opa\n', 0, 1, '2024-04-11 03:52:21'),
(24, 2, 1, 'olá', 0, 1, '2024-04-10 19:50:39'),
(25, 1, 2, 'oie bb\n', 0, 1, '2024-04-10 19:50:50'),
(26, 3, 1, '😘', 0, 1, '2024-04-12 20:45:04'),
(28, 5, 7, 'ðŸ˜žperdi minha conta vey\n', 0, 1, '2024-04-12 23:47:00'),
(29, 7, 1, 'Ficou massa ðŸ˜†', 0, 1, '2024-04-12 23:48:32'),
(30, 7, 5, 'Kkkkkkk acontece ', 0, 1, '2024-04-12 23:49:16'),
(31, 2, 5, 'Oi dlc', 0, 1, '2024-04-12 23:50:15'),
(32, 5, 7, 'funcionouuuuuuuuuuuuuuuuuuuuu', 0, 0, '2024-04-12 23:50:16'),
(33, 5, 7, 'coloca uma foto de perfil para mim ver se vai', 0, 0, '2024-04-12 23:50:30'),
(34, 8, 5, 'olÃ¡\n', 0, 1, '2024-04-13 00:03:28'),
(35, 8, 5, 'oi\n', 0, 1, '2024-04-13 00:03:58'),
(36, 8, 5, 'oi', 0, 1, '2024-04-13 00:04:03'),
(37, 8, 5, 'ðŸ¤£', 0, 1, '2024-04-13 00:04:12'),
(38, 8, 5, 'ðŸ˜‡', 0, 1, '2024-04-13 00:04:15'),
(39, 8, 5, 'ðŸ˜€', 0, 1, '2024-04-13 00:04:18'),
(40, 5, 8, 'coe', 0, 1, '2024-04-13 00:05:03'),
(41, 5, 8, 'ðŸ˜', 0, 1, '2024-04-13 00:05:06'),
(42, 8, 5, 'que foda\n', 0, 1, '2024-04-13 00:05:10'),
(43, 8, 5, 'fds o wpp agr', 0, 1, '2024-04-13 00:05:16'),
(44, 5, 8, 'hheheheh', 0, 1, '2024-04-13 00:05:16'),
(45, 5, 8, 'kkkkkkkkkk', 0, 1, '2024-04-13 00:05:21'),
(46, 8, 5, 'so talk por aq\n', 0, 1, '2024-04-13 00:05:32'),
(47, 8, 5, 'pokas\n', 0, 1, '2024-04-13 00:05:36'),
(48, 5, 8, 'tenta por uma foto', 0, 1, '2024-04-13 00:05:50'),
(49, 8, 5, 'jÃ¡ mete esse sistema dentro da iris azul', 0, 1, '2024-04-13 00:05:52'),
(50, 5, 8, 've se funciona', 0, 1, '2024-04-13 00:05:57'),
(51, 8, 5, 'pera\n', 0, 1, '2024-04-13 00:05:58'),
(52, 8, 5, 'foi ai?', 0, 1, '2024-04-13 00:06:25'),
(53, 5, 8, 'foi kkk', 0, 1, '2024-04-13 00:06:39'),
(54, 8, 2, 'salve\n', 0, 1, '2024-04-13 00:06:43'),
(55, 8, 5, 'q pikaaaa\n', 0, 1, '2024-04-13 00:06:52'),
(56, 8, 5, 'mt foda', 0, 1, '2024-04-13 00:07:01'),
(57, 5, 8, 'ta ficando bom ne kk', 0, 1, '2024-04-13 00:07:18'),
(58, 8, 5, 'Orrasss', 0, 1, '2024-04-13 00:07:25'),
(59, 8, 5, 'segue com esse projeto, vai tocando ele\n', 0, 1, '2024-04-13 00:07:37'),
(60, 8, 5, 'slc\n', 0, 1, '2024-04-13 00:07:41'),
(61, 8, 5, 'bom demais', 0, 1, '2024-04-13 00:07:48'),
(62, 5, 8, 'mn', 0, 1, '2024-04-13 00:08:24'),
(63, 5, 8, 'qr implementar mt mais coisa', 0, 1, '2024-04-13 00:08:32'),
(66, 5, 8, 'oie', 0, 0, '2024-04-14 14:35:29'),
(69, 5, 2, 'oie', 0, 1, '2024-04-14 18:32:49'),
(70, 5, 2, 'oie', 0, 1, '2024-04-14 18:53:56'),
(72, 9, 10, 'oie', 0, 0, '2024-04-21 18:04:13'),
(73, 12, 38, 'Bem-vindo ao nosso site! Estamos felizes em tê-lo conosco.', 0, 0, '2024-04-25 00:12:02'),
(74, 12, 39, 'Bem-vindo ao nosso chat!', 0, 0, '2024-04-25 00:22:13'),
(75, 12, 40, 'Bem-vindo ao nosso chat!', 0, 0, '2024-04-25 00:28:43'),
(76, 12, 42, 'Mensagem de boas-vindas do usuário 12', 0, 0, '2024-04-25 00:51:14'),
(77, 12, 43, 'Mensagem automática do usuário 12', 0, 0, '2024-04-25 01:00:43'),
(78, 12, 44, 'Mensagem automática do usuário 12', 0, 0, '2024-04-25 01:04:04'),
(79, 12, 45, 'Welcome to our chat!', 0, 0, '2024-04-25 01:10:28'),
(80, 12, 1, 'opa', 0, 1, '2024-04-25 01:22:15'),
(81, 12, 46, 'Mensagem automática do usuário 12', 0, 1, '2024-04-25 01:22:28'),
(82, 46, 12, 'opa', 0, 0, '2024-04-25 01:25:16'),
(83, 12, 47, 'Olá, tudo bem? sou o Unibot, o Bot oficial do chat Online, venho te informar que esse e um projeto de faculdade, não somos uma empresa registrada. \r\nprojeto desenvolvido por Victor Hugo Santana (R.A), Wesley Feu (R.A), Silvio(R.A).', 0, 1, '2024-04-25 01:30:57'),
(84, 12, 48, 'Olá, tudo bem? sou o Unibot, o Bot oficial do chat Online, venho te informar que esse e um projeto de faculdade, não somos uma empresa registrada. \r\nprojeto desenvolvido por Victor Hugo Santana (R.A), Wesley Feu (R.A), Silvio(R.A).', 0, 1, '2024-04-25 01:47:02'),
(85, 12, 49, 'Olá, tudo bem? sou o Unibot, o Bot oficial do chat Online, venho te informar que esse e um projeto de faculdade, não somos uma empresa registrada. \r\nprojeto desenvolvido por Victor Hugo Santana (R.A), Wesley Feu (R.A), Silvio(R.A).', 0, 0, '2024-04-25 01:50:48'),
(86, 12, 50, 'Olá, tudo bem? sou o Unibot, o Bot oficial do chat Online, venho te informar que esse e um projeto de faculdade, não somos uma empresa registrada. \r\nprojeto desenvolvido por Victor Hugo Santana (R.A), Wesley Feu (R.A), Silvio(R.A).', 0, 1, '2024-04-25 01:51:56'),
(87, 12, 51, 'Olá, tudo bem? sou o Unibot, o Bot oficial do chat Online, venho te informar que esse e um projeto de faculdade, não somos uma empresa registrada. \r\nprojeto desenvolvido por Victor Hugo Santana (R.A), Wesley Feu (R.A), Silvio(R.A).', 0, 0, '2024-04-25 01:54:26'),
(88, 12, 52, 'Olá, tudo bem? sou o Unibot, o Bot oficial do chat Online, venho te informar que esse e um projeto de faculdade, não somos uma empresa registrada. \r\nprojeto desenvolvido por Victor Hugo Santana (R.A), Wesley Feu (R.A), Silvio(R.A).', 0, 0, '2024-04-26 00:14:38'),
(89, 12, 53, 'Olá, tudo bem? sou o Unibot, o Bot oficial do chat Online, venho te informar que esse e um projeto de faculdade, não somos uma empresa registrada. \r\nprojeto desenvolvido por Victor Hugo Santana (R.A), Wesley Feu (R.A), Silvio(R.A).', 0, 0, '2024-04-26 00:15:01'),
(91, 12, 55, 'Olá, tudo bem? sou o Unibot, o Bot oficial do chat Online, venho te informar que esse e um projeto de faculdade, não somos uma empresa registrada. \r\nprojeto desenvolvido por Victor Hugo Santana (R.A), Wesley Feu (R.A), Silvio(R.A).', 0, 0, '2024-04-26 01:05:04'),
(111, 12, 59, 'Olá, tudo bem? sou o Unibot, o Bot oficial do chat Online, venho te informar que esse e um projeto de faculdade, não somos uma empresa registrada.', 0, 1, '2024-04-26 01:30:59'),
(113, 5, 59, 'opa', 0, 1, '2024-04-26 15:54:15'),
(114, 59, 5, 'fala ai', 0, 1, '2024-04-26 15:54:27');

-- --------------------------------------------------------

--
-- Estrutura para tabela `conversations`
--

CREATE TABLE `conversations` (
  `conversation_id` int(11) NOT NULL,
  `user_1` int(11) NOT NULL,
  `user_2` int(11) NOT NULL,
  `visualizada` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `conversations`
--

INSERT INTO `conversations` (`conversation_id`, `user_1`, `user_2`, `visualizada`) VALUES
(1, 2, 1, 0),
(2, 3, 1, 0),
(3, 5, 2, 0),
(4, 5, 7, 0),
(5, 7, 1, 0),
(6, 8, 5, 0),
(7, 8, 2, 0),
(8, 9, 10, 0),
(9, 12, 1, 0),
(10, 12, 46, 0),
(11, 12, 47, 0),
(12, 12, 48, 0),
(13, 12, 49, 0),
(14, 12, 50, 0),
(15, 12, 51, 0),
(16, 12, 52, 0),
(17, 12, 53, 0),
(19, 12, 55, 0),
(20, 12, 56, 0),
(37, 12, 59, 0),
(39, 5, 59, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `groups`
--

CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `groups`
--

INSERT INTO `groups` (`group_id`, `group_name`, `created_by`, `created_at`) VALUES
(1, 'oi', 5, '2024-04-13 14:39:36'),
(2, 'oi', 5, '2024-04-13 14:42:02'),
(3, 'oi', 5, '2024-04-13 14:46:45'),
(4, 'Nati', 5, '2024-04-13 23:23:09'),
(5, 'oi', 5, '2024-04-13 23:37:09'),
(6, 'eu', 5, '2024-04-13 23:42:19'),
(7, 'oie', 5, '2024-04-14 01:29:08'),
(8, 'Deu certo?', 5, '2024-04-14 03:23:05'),
(9, 'oi', 5, '2024-04-14 03:27:26'),
(10, 'Nati', 5, '2024-04-14 21:24:45'),
(11, 'oie', 2, '2024-04-14 21:27:34'),
(12, 'aba', 2, '2024-04-14 21:28:03'),
(13, 'xablau', 2, '2024-04-14 21:31:38'),
(14, 'Te amo', 2, '2024-04-20 23:40:45'),
(15, 'oie', 9, '2024-04-21 18:04:21');

-- --------------------------------------------------------

--
-- Estrutura para tabela `group_chats`
--

CREATE TABLE `group_chats` (
  `group_chat_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `opened` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `group_chats`
--

INSERT INTO `group_chats` (`group_chat_id`, `group_id`, `from_id`, `message`, `opened`, `created_at`) VALUES
(1, 1, 5, 'ola', 0, '2024-04-13 22:05:24'),
(2, 1, 5, 'ola', 0, '2024-04-13 23:08:59'),
(3, 1, 5, 'ola', 0, '2024-04-13 23:09:00'),
(4, 1, 5, 'ola', 0, '2024-04-13 23:09:02'),
(5, 4, 5, 'ola', 0, '2024-04-13 23:23:17'),
(6, 7, 5, 'ola', 0, '2024-04-14 01:29:15'),
(7, 1, 2, 'opiie', 0, '2024-04-14 03:17:46'),
(8, 1, 2, 'Migo', 0, '2024-04-14 03:20:17'),
(9, 4, 2, 'migo?', 0, '2024-04-14 03:21:03'),
(10, 8, 5, 'opa', 0, '2024-04-14 03:23:09'),
(11, 8, 5, 'mor?', 0, '2024-04-14 03:33:26'),
(12, 8, 2, 'oie bb', 0, '2024-04-14 03:33:46'),
(13, 9, 5, 'Aqui tbm deu?', 0, '2024-04-14 03:34:07'),
(14, 4, 5, 'opa', 0, '2024-04-14 03:41:16'),
(15, 4, 5, 'opa', 0, '2024-04-14 03:42:48'),
(16, 4, 5, 'opa', 0, '2024-04-14 03:42:50'),
(17, 4, 5, 'opa', 0, '2024-04-14 03:45:32'),
(18, 4, 1, 'oie gente', 0, '2024-04-14 19:30:47'),
(19, 4, 5, 'oie', 0, '2024-04-14 21:03:38'),
(20, 4, 5, 'bb', 0, '2024-04-14 21:03:55'),
(21, 13, 1, 'iae', 0, '2024-04-20 23:41:47'),
(22, 4, 2, 'ola', 0, '2024-04-25 02:18:44');

-- --------------------------------------------------------

--
-- Estrutura para tabela `group_members`
--

CREATE TABLE `group_members` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `joined_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `group_members`
--

INSERT INTO `group_members` (`group_id`, `user_id`, `joined_at`) VALUES
(1, 3, '2024-04-14 03:16:54'),
(4, 1, '2024-04-14 20:56:15'),
(4, 2, '2024-04-14 03:47:17'),
(4, 8, '2024-04-14 20:56:45'),
(7, 3, '2024-04-14 02:58:44'),
(7, 7, '2024-04-14 02:58:49'),
(7, 8, '2024-04-14 02:58:51'),
(11, 5, '2024-04-14 21:27:56'),
(15, 9, '2024-04-21 18:04:21'),
(15, 10, '2024-04-21 18:04:27');

-- --------------------------------------------------------

--
-- Estrutura para tabela `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `p_p` varchar(255) DEFAULT 'user-default.png',
  `visto` timestamp NOT NULL DEFAULT current_timestamp(),
  `hobbies` text DEFAULT NULL,
  `profession` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `biography` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`user_id`, `name`, `username`, `password`, `p_p`, `visto`, `hobbies`, `profession`, `age`, `biography`) VALUES
(1, 'dev', 'dev', '$2y$10$gPjrjrzwpUkn9Xwz.3vJ.elMEBrxc89Y1OqhwfMly4pMssfdFbd/u', '662b28522bcb7.png', '2024-04-14 03:01:27', NULL, NULL, NULL, NULL),
(2, 'Nati', 'nati@gmail.com', '$2y$10$fCIMGsB42IRQ/fbyA48WUuqyoiag5kdWX95S.FWfN1HJkRq4DvglO', '662b2860f0baa.png', '2024-04-14 03:01:27', NULL, NULL, NULL, NULL),
(3, 'eu', 'eu', '$2y$10$qVl3yTF3Z8JfvaDoGdiy4.y4dA10weK0qA7UfFadLHGlA4zNNnVgC', 'user-default.png', '2024-04-14 03:01:27', NULL, NULL, NULL, NULL),
(5, 'Victor', 'admin', '$2y$10$ep3rXF6ot1pTNZ4BtQYQeu2OHzRz5ktn1gPN73NMIt4.hxF2ySZ5S', '661abefdee372.jpg', '2024-04-14 03:00:00', NULL, NULL, NULL, NULL),
(7, 'Wendy ', 'Endy', '$2y$10$/PncwChbe23YsvrnZSycl.TwjxiUSos./VZ5v3bFvPuJj1SkbJ9jG', 'user-default.png', '2024-04-14 03:00:00', NULL, NULL, NULL, NULL),
(8, 'MARIA', 'Maria', '$2y$10$HPlvqngBvs36erYOXbxooeVIotGxLMHISAVcNTe/gndC7tBGdXzEa', 'user-default.png', '2024-04-14 03:00:00', NULL, NULL, NULL, NULL),
(9, 'Ariane', 'Ariane', '$2y$10$6ZuJu7kWcf7wQOlRlGfpouWTKBGzRhnr1MmJubduFwp17ShrSzKqC', '66257d072758d.jpg', '2024-04-21 20:53:49', NULL, NULL, NULL, NULL),
(10, 'Paulo', 'Paulo', '$2y$10$G3e1zI0M.FhT2BOz0cmYqe8A90e6TeXad9DiMX7.3p5NSLyrab1bG', '66257cfb278aa.jpg', '2024-04-21 20:54:09', NULL, NULL, NULL, NULL),
(12, 'UniBot✅', 'UniBot✅', '$2y$10$mNVDLYMZzF.YSwP7FiTN4.po..w/opT1OM9dTinq5T2LWrdTnH1wu', '6629df30e8f96.png', '2024-04-24 19:54:05', NULL, NULL, NULL, NULL),
(59, 'victor', 'Victor', '$2y$10$RkvoeTVuOVxRqW/VeANrHOfBZbUMFOPEPzSeAuf1Xr8Atouiik7Ry', '662c8077b2f91.jpg', '2024-04-26 04:30:59', 'Desenvolver', 'Dev', 19, 'dev ne pae');

--
-- Acionadores `users`
--
DELIMITER $$
CREATE TRIGGER `after_user_creation` AFTER INSERT ON `users` FOR EACH ROW BEGIN
  DECLARE v_conversation_exists INT;

  SELECT COUNT(*) INTO v_conversation_exists FROM conversations
  WHERE (user_1 = 12 AND user_2 = NEW.user_id) OR (user_1 = NEW.user_id AND user_2 = 12);

  IF v_conversation_exists = 0 THEN
    INSERT INTO conversations (user_1, user_2, visualizada)
    VALUES (12, NEW.user_id, 0);
  END IF;

  INSERT INTO chats (from_id, to_id, message, is_group_message, opened, created_at)
  VALUES (12, NEW.user_id, 'Olá, tudo bem? sou o Unibot, o Bot oficial do chat Online, venho te informar que esse e um projeto de faculdade, não somos uma empresa registrada.', 0, 0, NOW());
END
$$
DELIMITER ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `arquivos`
--
ALTER TABLE `arquivos`
  ADD PRIMARY KEY (`arquivo_id`),
  ADD KEY `chat_id_idx` (`chat_id`);

--
-- Índices de tabela `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`chat_id`);

--
-- Índices de tabela `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`conversation_id`);

--
-- Índices de tabela `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Índices de tabela `group_chats`
--
ALTER TABLE `group_chats`
  ADD PRIMARY KEY (`group_chat_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `from_id` (`from_id`);

--
-- Índices de tabela `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`group_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices de tabela `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `arquivos`
--
ALTER TABLE `arquivos`
  MODIFY `arquivo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `chats`
--
ALTER TABLE `chats`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT de tabela `conversations`
--
ALTER TABLE `conversations`
  MODIFY `conversation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de tabela `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `group_chats`
--
ALTER TABLE `group_chats`
  MODIFY `group_chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `arquivos`
--
ALTER TABLE `arquivos`
  ADD CONSTRAINT `fk_arquivos_chat_id` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`chat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_arquivos_chat_id_unique` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`chat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_chat_id` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`chat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `groups_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `group_chats`
--
ALTER TABLE `group_chats`
  ADD CONSTRAINT `group_chats_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`),
  ADD CONSTRAINT `group_chats_ibfk_2` FOREIGN KEY (`from_id`) REFERENCES `users` (`user_id`);

--
-- Restrições para tabelas `group_members`
--
ALTER TABLE `group_members`
  ADD CONSTRAINT `group_members_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
