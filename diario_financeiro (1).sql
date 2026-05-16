-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 12/05/2026 às 16:30
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
-- Banco de dados: `diario_financeiro`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `gastos_extras`
--

CREATE TABLE `gastos_extras` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data_gasto` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `gastos_extras`
--

INSERT INTO `gastos_extras` (`id`, `id_usuario`, `descricao`, `valor`, `data_gasto`) VALUES
(82, 30, 'remedio', 500.00, '2026-04-19'),
(83, 30, 'comidinhas', 200.00, '2026-04-19'),
(85, 31, 'carro', 600.00, '2026-05-12');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `renda` decimal(10,2) NOT NULL,
  `moradia` decimal(10,2) DEFAULT 0.00,
  `alimentacao` decimal(10,2) DEFAULT 0.00,
  `transporte` decimal(10,2) DEFAULT 0.00,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `lazer` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `renda`, `moradia`, `alimentacao`, `transporte`, `data_cadastro`, `lazer`) VALUES
(30, 'Maria Silva', 4000.00, 800.00, 2000.00, 250.00, '2026-04-18 23:51:04', 0.00),
(31, 'lucas silva ', 2000.00, 300.00, 200.00, 200.00, '2026-05-12 14:27:05', 200.00);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `gastos_extras`
--
ALTER TABLE `gastos_extras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario_gasto` (`id_usuario`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `gastos_extras`
--
ALTER TABLE `gastos_extras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `gastos_extras`
--
ALTER TABLE `gastos_extras`
  ADD CONSTRAINT `fk_usuario_gasto` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
