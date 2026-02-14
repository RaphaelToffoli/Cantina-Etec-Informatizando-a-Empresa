-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 14-Nov-2025 às 13:41
-- Versão do servidor: 10.4.21-MariaDB
-- versão do PHP: 7.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `cantina`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `entrada`
--

CREATE TABLE `entrada` (
  `id.entrada` int(11) NOT NULL,
  `id.produto` int(11) NOT NULL,
  `data_entrada` date NOT NULL,
  `quantidade` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `entrada`
--

INSERT INTO `entrada` (`id.entrada`, `id.produto`, `data_entrada`, `quantidade`) VALUES
(1, 11, '2025-10-22', 1),
(2, 12, '2025-10-22', 1),
(3, 4, '2025-10-22', 1),
(4, 4, '2025-10-22', -2),
(5, 8, '2025-10-22', -2),
(6, 4, '2025-10-23', 2),
(7, 8, '2025-10-23', 4),
(8, 11, '2025-10-23', 4),
(9, 10, '2025-10-23', 1),
(10, 20, '2025-11-13', 2),
(11, 13, '2025-11-13', 7),
(12, 14, '2025-11-13', 4),
(13, 6, '2025-11-13', 6),
(14, 20, '2025-11-13', -1),
(15, 20, '2025-11-14', 4),
(16, 13, '2025-11-14', 1),
(17, 19, '2025-11-14', 5),
(18, 18, '2025-11-14', 6),
(19, 14, '2025-11-14', 10),
(20, 27, '2025-11-14', 5),
(21, 28, '2025-11-14', 5),
(22, 21, '2025-11-14', 5),
(23, 15, '2025-11-14', 5),
(24, 25, '2025-11-14', 5),
(25, 24, '2025-11-14', 5),
(26, 23, '2025-11-14', 5),
(27, 22, '2025-11-14', 5),
(28, 4, '2025-11-14', 6),
(29, 12, '2025-11-14', 5),
(30, 16, '2025-11-14', 5),
(31, 8, '2025-11-14', 6),
(32, 10, '2025-11-14', 5),
(33, 11, '2025-11-14', 5),
(34, 17, '2025-11-14', 5),
(35, 6, '2025-11-14', 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto`
--

CREATE TABLE `produto` (
  `id.produto` int(11) NOT NULL,
  `nome` varchar(43) NOT NULL,
  `quantidade_estoque` int(50) NOT NULL,
  `data_criacao` date NOT NULL,
  `status_produto` tinyint(1) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `descricao` text NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `imagem` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `produto`
--

INSERT INTO `produto` (`id.produto`, `nome`, `quantidade_estoque`, `data_criacao`, `status_produto`, `preco`, `descricao`, `categoria`, `imagem`) VALUES
(4, 'BAURU', 10, '0000-00-00', 1, '5.00', 'Presunto, queijo, tomate e catupiry', 'salgados', 'uploads/BAURU.png'),
(6, 'RISOLE', 10, '2025-10-06', 1, '9.00', 'Presunto e queijo', 'salgados', 'uploads/Risole.png'),
(8, 'ESFIHA', 10, '2025-10-06', 1, '9.00', 'Carne moída', 'salgados', 'uploads/Esfiha.png'),
(10, 'ESPETINHO', 10, '2025-10-06', 1, '9.00', 'Frango empanado', 'salgados', 'uploads/Espetinho.png'),
(11, 'Esfirra de Frango', 10, '2025-10-22', 1, '9.00', 'Esfirra de Frango', 'salgados', 'uploads/EsfihaFrango.png'),
(12, 'Coxinha de carne', 10, '2025-10-22', 1, '12.00', 'Carne moida', 'salgados', 'uploads/coxinha.png'),
(13, 'Coca Cola', 10, '2025-10-22', 1, '6.00', 'Coco Cola 500ml', 'bebidas', 'uploads/CocaCola500ml.png'),
(14, 'Brigadeiro', 15, '2025-10-22', 1, '6.00', 'Chocolate', 'doces', 'uploads/brigadeiro.png'),
(15, 'Cheetos Requeijão', 9, '2025-10-22', 1, '6.00', 'Salgadinho Cheetos sabor requeijão 40g', 'salgadinhos', 'uploads/CheetosRequeijão.png'),
(16, 'Coxinha de Frango', 10, '2025-10-23', 1, '9.00', 'Coxinha de Frango ', 'salgados', 'uploads/coxinha.png'),
(17, 'Kibe', 10, '2025-10-23', 1, '9.00', 'Kibe ', 'salgados', 'uploads/Kibe.png'),
(18, 'Coca Cola 350ml', 10, '2025-10-23', 1, '6.00', 'Coca Cola Lata 350ml', 'bebidas', 'uploads/ColaCola350ml.png'),
(19, 'Coca Cola 2lt', 10, '2025-10-23', 1, '11.00', 'Coca Cola 2lt', 'bebidas', 'uploads/CocaCola2lt.png'),
(20, 'Café', 10, '2025-10-23', 1, '2.50', 'Café 50ml', 'bebidas', 'uploads/Cafe50ml.png'),
(21, 'Cheetos Parmesão ', 10, '2025-10-23', 1, '4.40', 'Cheetos sabor parmesão 40g', 'salgadinhos', 'uploads/CheetosParmesão.png'),
(22, 'Torcida Churrasco', 10, '2025-10-23', 1, '4.50', 'Salgadinho Torcida sabor churrasco 100g', 'salgadinhos', 'uploads/TorcidaChurrasco.png'),
(23, 'Torcida Cebola', 10, '2025-10-23', 1, '4.50', 'Salgadinho Torcida sabor cebola 100g', 'salgadinhos', 'uploads/TorcidaCebola.png'),
(24, 'Fandangos Queijo', 10, '2025-10-23', 1, '4.50', 'Salgadinho Fandangos sabor queijo 37g', 'salgadinhos', 'uploads/Fandangos Queijo.png'),
(25, 'Fandangos Presuntos', 10, '2025-10-23', 1, '4.50', 'Salgadinho Fandangos sabor Presunto 230g', 'salgadinhos', 'uploads/FandangosPresunto.png'),
(26, 'Trufas', 10, '2025-10-23', 1, '2.00', 'Trufas de diversos sabores(Escolher o sabor na cantina)', 'doces', 'uploads/Trufa(Diversos Sabores).png'),
(27, 'Mousse de Chocolate', 10, '2025-10-23', 1, '2.00', 'Sobremesa leve com base de chocolate em barra meio amargo e creme de leite. Aerada, com leve toque de chocolate branco para dar crocância', 'doces', 'uploads/MousseChocolate.png'),
(28, 'X-salada', 10, '2025-10-23', 1, '9.00', 'Pão Frances, Alface, Tomate, hamburguer e condimentos', 'especiais', 'uploads/X-Salada.png');

-- --------------------------------------------------------

--
-- Estrutura da tabela `saida`
--

CREATE TABLE `saida` (
  `id.saida` int(11) NOT NULL,
  `id.usuario` int(11) NOT NULL,
  `data_saida` date NOT NULL,
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `metodo_pagamento` varchar(50) NOT NULL DEFAULT 'Desconhecido'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `saida`
--

INSERT INTO `saida` (`id.saida`, `id.usuario`, `data_saida`, `valor_total`, `metodo_pagamento`) VALUES
(1, 6, '2025-10-21', '18.00', 'pix'),
(2, 9, '2025-11-14', '14.00', 'credito'),
(3, 9, '2025-11-14', '15.00', 'debito'),
(4, 9, '2025-11-14', '6.00', 'pix'),
(5, 9, '2025-11-14', '6.00', 'pix');

-- --------------------------------------------------------

--
-- Estrutura da tabela `saida.detalhes`
--

CREATE TABLE `saida.detalhes` (
  `id.saida` int(11) NOT NULL,
  `id.produto` int(11) NOT NULL,
  `quantidade` int(50) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `saida.detalhes`
--

INSERT INTO `saida.detalhes` (`id.saida`, `id.produto`, `quantidade`, `preco_unitario`) VALUES
(1, 8, 1, '9.00'),
(1, 6, 1, '9.00'),
(2, 4, 1, '5.00'),
(2, 6, 1, '9.00'),
(3, 8, 1, '9.00'),
(3, 18, 1, '6.00'),
(4, 15, 1, '6.00'),
(5, 14, 1, '6.00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id.usuario` int(11) NOT NULL,
  `nome` varchar(44) NOT NULL,
  `email` varchar(40) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel_acesso` varchar(20) NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id.usuario`, `nome`, `email`, `senha`, `nivel_acesso`) VALUES
(2, 'Usuário', 'tcc.cantina@gmail.com', '$2y$10$DVAnl6FCVwuKnx1AvwlKxeppg/KOjLti7qF9/dJzIiPF0cnAkBv8q', 'usuario'),
(6, 'usuario', 'tcc.cantinaklr@gmail.com', '$2y$10$J0Tr.CtQtwUEEza96UQDC.kvbLJK6yXC5ZfMQIGZeTQSEwbUQ6Gsu', 'usuario'),
(9, 'UsuárioAdministrador', 'tcc.cantinaK@gmail.com', '$2y$10$DnU9tqhkarSKWxZgHZJGJ.csTMbvsSUZFqtvOBfckQi3rHoHJpBSK', 'administrador');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `entrada`
--
ALTER TABLE `entrada`
  ADD PRIMARY KEY (`id.entrada`),
  ADD KEY `produto` (`id.produto`);

--
-- Índices para tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id.produto`);

--
-- Índices para tabela `saida`
--
ALTER TABLE `saida`
  ADD PRIMARY KEY (`id.saida`),
  ADD KEY `usuario` (`id.usuario`);

--
-- Índices para tabela `saida.detalhes`
--
ALTER TABLE `saida.detalhes`
  ADD KEY `saida` (`id.saida`),
  ADD KEY `produtos` (`id.produto`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id.usuario`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `entrada`
--
ALTER TABLE `entrada`
  MODIFY `id.entrada` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id.produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de tabela `saida`
--
ALTER TABLE `saida`
  MODIFY `id.saida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id.usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `entrada`
--
ALTER TABLE `entrada`
  ADD CONSTRAINT `produto` FOREIGN KEY (`id.produto`) REFERENCES `produto` (`id.produto`);

--
-- Limitadores para a tabela `saida`
--
ALTER TABLE `saida`
  ADD CONSTRAINT `usuario` FOREIGN KEY (`id.usuario`) REFERENCES `usuario` (`id.usuario`);

--
-- Limitadores para a tabela `saida.detalhes`
--
ALTER TABLE `saida.detalhes`
  ADD CONSTRAINT `produtos` FOREIGN KEY (`id.produto`) REFERENCES `produto` (`id.produto`),
  ADD CONSTRAINT `saida` FOREIGN KEY (`id.saida`) REFERENCES `saida` (`id.saida`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
