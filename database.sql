-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 15/05/2022 às 06:08
-- Versão do servidor: 8.0.29-0ubuntu0.20.04.3
-- Versão do PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `podologiaDB2`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamentos`
--

CREATE TABLE `agendamentos` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `collaborator_id` int NOT NULL,
  `agendador_id` int NOT NULL,
  `data_agendamento` int NOT NULL,
  `data_agendada` date NOT NULL,
  `hora_agendada` time NOT NULL,
  `observacao` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos_realizado`
--

CREATE TABLE `atendimentos_realizado` (
  `id` int NOT NULL,
  `agendamento_id` int NOT NULL,
  `data_confirmacao` int NOT NULL DEFAULT '0',
  `observacao` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimento_image`
--

CREATE TABLE `atendimento_image` (
  `id` int NOT NULL,
  `ATENDIMENTO_ID` int NOT NULL,
  `IMG` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `caixa`
--

CREATE TABLE `caixa` (
  `id` int NOT NULL,
  `horario_abertura` time NOT NULL,
  `valor_abertura` float(10,2) NOT NULL DEFAULT '0.00',
  `colaborador_id_abertura` int NOT NULL,
  `data_abertura` date NOT NULL,
  `horario_fechamento` time DEFAULT NULL,
  `valor_fechamento` float(10,2) DEFAULT '0.00',
  `colaborador_id_fechamento` int DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `data_fechamento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas`
--

CREATE TABLE `contas` (
  `id` int NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(15) NOT NULL,
  `telefone_02` varchar(15) NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL DEFAULT '',
  `criacao` date NOT NULL,
  `ip` varchar(100) NOT NULL DEFAULT '0',
  `nv_acesso` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `contas`
--

INSERT INTO `contas` (`id`, `usuario`, `senha`, `telefone`, `telefone_02`, `email`, `criacao`, `ip`, `nv_acesso`, `status`) VALUES
(1, 'podologia', '2a5b86d598aeefd3dca18cc7904c5c4487140022', '11994489463', '0', 'podologia@gmail.com', '2000-01-01', '0', 3, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `gabaritos`
--

CREATE TABLE `gabaritos` (
  `id` int NOT NULL,
  `pergunta_id` int NOT NULL,
  `resposta_id` int NOT NULL,
  `data_criacao` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagamentos`
--

CREATE TABLE `pagamentos` (
  `id` int NOT NULL,
  `agendamento_id` int NOT NULL,
  `valor_total` float(10,2) NOT NULL DEFAULT '0.00',
  `tipo_pagamento` int NOT NULL DEFAULT '1',
  `data_pagamento` date NOT NULL,
  `hora_pagamento` time NOT NULL,
  `caixa_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `patologias`
--

CREATE TABLE `patologias` (
  `id` int NOT NULL,
  `nome` varchar(100) NOT NULL DEFAULT '',
  `type` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `patologias`
--

INSERT INTO `patologias` (`id`, `nome`, `type`) VALUES
(1, 'ressecamento', 1),
(2, 'HIPERDROSE', 1),
(3, 'DESIDROSE', 1),
(4, 'HIPERQUERATOSE', 1),
(5, 'BRONIDROSE', 1),
(6, 'MICOSE PLANTAR', 1),
(7, 'MICOSE INTERDIGITAL', 1),
(8, 'MICOSE UNGEAL', 1),
(9, 'PSORIASE', 1),
(10, 'ONICOCRIPTOSE', 1),
(11, 'ONICOFOSE', 1),
(12, 'EXOSTOSE', 1),
(13, 'GRANULOMA', 1),
(14, 'OUTRAS DERMATOLÓGICAS', 1),
(15, 'TELHA ', 2),
(16, 'FUNIL ', 2),
(17, 'GANCHO ', 2),
(18, 'CARACOL ', 2),
(19, 'DISTRÓFICA ', 2),
(20, 'TORQUES ', 2),
(21, 'OUTRAS UNIGUEAIS', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `perguntas`
--

CREATE TABLE `perguntas` (
  `id` int NOT NULL,
  `questionario_id` int NOT NULL,
  `descricao` varchar(255) NOT NULL DEFAULT '',
  `data_criacao` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `perguntas`
--

INSERT INTO `perguntas` (`id`, `questionario_id`, `descricao`, `data_criacao`) VALUES
(1, 1, 'Tipo de calçado mais utilizado?', 0),
(2, 1, 'Tipo de meia mais utilizado?', 0),
(3, 1, 'cirurgia nos membros inferiores?', 0),
(4, 1, 'Prática esportes?', 0),
(5, 1, 'está tomando algum medicamento?', 0),
(6, 1, 'gestante?', 0),
(7, 1, 'possui alguma alergia?', 0),
(8, 1, 'sensibilidade a dor?', 0),
(9, 1, 'tem hipo/hipertensão arterial?', 0),
(10, 1, 'diabetes?', 0),
(11, 1, 'hanseniase?', 0),
(12, 1, 'cardiopatia?', 0),
(13, 1, 'algum tipo de câncer?', 0),
(14, 1, 'portador de marcapasso/pinos?', 0),
(15, 1, 'distúrbio circulatório?', 0),
(16, 1, 'hepatite?', 0),
(17, 1, 'tem hipo/hipertensão arterial?', 0),
(18, 1, 'diabete?', 0),
(19, 1, 'hanseniase?', 0),
(20, 1, 'cardiopatia?', 0),
(21, 1, 'algum tipo de câncer?', 0),
(22, 1, 'portador de marcapasso/pinos?', 0),
(23, 1, 'distúrbio circulatório?', 0),
(24, 1, 'hepatite?', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `questionarios`
--

CREATE TABLE `questionarios` (
  `id` int NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `data_criacao` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `questionarios`
--

INSERT INTO `questionarios` (`id`, `titulo`, `data_criacao`) VALUES
(1, 'FICHA ANAMNESE', '2000-01-01');

-- --------------------------------------------------------

--
-- Estrutura para tabela `respostas`
--

CREATE TABLE `respostas` (
  `id` int NOT NULL,
  `pergunta_id` int NOT NULL,
  `descricao` varchar(255) NOT NULL DEFAULT '',
  `data_criacao` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `respostas`
--

INSERT INTO `respostas` (`id`, `pergunta_id`, `descricao`, `data_criacao`) VALUES
(1, 1, 'Aberto', 0),
(2, 1, 'Fechado', 0),
(3, 2, 'social', 0),
(4, 2, 'esportiva', 0),
(5, 3, 'Sim', 0),
(6, 3, 'Não', 0),
(7, 4, 'Sim', 0),
(8, 4, 'Não', 0),
(9, 5, 'Sim', 0),
(10, 5, 'Não', 0),
(11, 6, 'Sim', 0),
(12, 6, 'Não', 0),
(13, 7, 'Sim', 0),
(14, 7, 'Não', 0),
(15, 8, 'Sim', 0),
(16, 8, 'Não', 0),
(17, 9, 'Sim', 0),
(18, 9, 'Não', 0),
(19, 10, 'Sim', 0),
(20, 10, 'Não', 0),
(21, 11, 'Sim', 0),
(22, 11, 'Não', 0),
(23, 12, 'Sim', 0),
(24, 12, 'Não', 0),
(25, 13, 'Sim', 0),
(26, 13, 'Não', 0),
(27, 14, 'Sim', 0),
(28, 14, 'Não', 0),
(29, 15, 'Sim', 0),
(30, 15, 'Não', 0),
(31, 16, 'Sim', 0),
(32, 16, 'Não', 0),
(34, 17, 'Sim', 0),
(35, 17, 'Não', 0),
(36, 18, 'Sim', 0),
(37, 18, 'Não', 0),
(38, 19, 'Sim', 0),
(39, 19, 'Não', 0),
(40, 20, 'Sim', 0),
(41, 20, 'Não', 0),
(42, 21, 'Sim', 0),
(43, 21, 'Não', 0),
(44, 22, 'Sim', 0),
(45, 22, 'Não', 0),
(46, 23, 'Sim', 0),
(47, 23, 'Não', 0),
(48, 24, 'Sim', 0),
(49, 24, 'Não', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicos`
--

CREATE TABLE `servicos` (
  `id` int NOT NULL,
  `tempo` time NOT NULL DEFAULT '00:00:00',
  `valor` float(10,2) NOT NULL DEFAULT '0.00',
  `descricao` varchar(255) NOT NULL DEFAULT '',
  `prioridade` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicos_agendado`
--

CREATE TABLE `servicos_agendado` (
  `id` int NOT NULL,
  `agendamento_id` int NOT NULL,
  `servico_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_pagamento`
--

CREATE TABLE `tipos_pagamento` (
  `id` int NOT NULL,
  `descricao` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `tipos_pagamento`
--

INSERT INTO `tipos_pagamento` (`id`, `descricao`) VALUES
(1, 'DINHEIRO'),
(2, 'DÉBITO'),
(3, 'CRÉDITO'),
(4, 'PIX');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `conta_id` int NOT NULL,
  `primeiro_nome` varchar(50) NOT NULL,
  `ultimo_nome` varchar(100) NOT NULL,
  `data_nascimento` date NOT NULL,
  `cpf` varchar(255) NOT NULL,
  `rg` varchar(255) NOT NULL,
  `genero` varchar(1) NOT NULL DEFAULT 'M',
  `rua` varchar(100) NOT NULL DEFAULT '',
  `bairro` varchar(100) NOT NULL DEFAULT '',
  `numero` smallint NOT NULL DEFAULT '0',
  `complemento` varchar(50) DEFAULT '',
  `cep` varchar(15) NOT NULL DEFAULT '',
  `cidade` varchar(100) NOT NULL DEFAULT '',
  `estado` varchar(5) NOT NULL DEFAULT 'SP',
  `nacionalidade` varchar(100) NOT NULL DEFAULT 'BRASILEIRO',
  `profissao` varchar(100) NOT NULL DEFAULT '',
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `conta_id`, `primeiro_nome`, `ultimo_nome`, `data_nascimento`, `cpf`, `rg`, `genero`, `rua`, `bairro`, `numero`, `complemento`, `cep`, `cidade`, `estado`, `nacionalidade`, `profissao`, `foto`) VALUES
(1, 1, 'ADMINISTRADOR', 'SISTEMA', '1999-03-31', '00000000000', '000000000', 'M', '', '', 0, '', '', '', 'SP', 'BRASILEIRO', '', 'profile.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_gabarito`
--

CREATE TABLE `usuarios_gabarito` (
  `id` int NOT NULL,
  `pergunta_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `resposta_id` int NOT NULL,
  `observacao` varchar(255) NOT NULL DEFAULT '',
  `data_criacao` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_patologias`
--

CREATE TABLE `usuarios_patologias` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `PE` varchar(2) NOT NULL,
  `PAT_N` int NOT NULL,
  `PAT_ID` int NOT NULL,
  `PAT_POS` smallint NOT NULL,
  `PAT_TYPE` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_tratamentos`
--

CREATE TABLE `usuarios_tratamentos` (
  `id` int NOT NULL,
  `usuario_patologia_id` int NOT NULL,
  `descricao` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agendador_id` (`agendador_id`),
  ADD KEY `collaborator_id` (`collaborator_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `atendimentos_realizado`
--
ALTER TABLE `atendimentos_realizado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agendamento_id` (`agendamento_id`);

--
-- Índices de tabela `atendimento_image`
--
ALTER TABLE `atendimento_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ATENDIMENTO_ID` (`ATENDIMENTO_ID`);

--
-- Índices de tabela `caixa`
--
ALTER TABLE `caixa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `colaborador_id_abertura` (`colaborador_id_abertura`),
  ADD KEY `colaborador_id_fechamento` (`colaborador_id_fechamento`);

--
-- Índices de tabela `contas`
--
ALTER TABLE `contas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Índices de tabela `gabaritos`
--
ALTER TABLE `gabaritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pergunta_id` (`pergunta_id`),
  ADD KEY `resposta_id` (`resposta_id`);

--
-- Índices de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agendamento_id` (`agendamento_id`),
  ADD KEY `tipo_pagamento` (`tipo_pagamento`),
  ADD KEY `caixa_id` (`caixa_id`);

--
-- Índices de tabela `patologias`
--
ALTER TABLE `patologias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `perguntas`
--
ALTER TABLE `perguntas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questionario_id` (`questionario_id`);

--
-- Índices de tabela `questionarios`
--
ALTER TABLE `questionarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `respostas`
--
ALTER TABLE `respostas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pergunta_id` (`pergunta_id`);

--
-- Índices de tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `servicos_agendado`
--
ALTER TABLE `servicos_agendado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agendamento_id` (`agendamento_id`),
  ADD KEY `servico_id` (`servico_id`);

--
-- Índices de tabela `tipos_pagamento`
--
ALTER TABLE `tipos_pagamento`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`,`rg`),
  ADD KEY `conta_id` (`conta_id`,`cpf`,`rg`);

--
-- Índices de tabela `usuarios_gabarito`
--
ALTER TABLE `usuarios_gabarito`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pergunta_id` (`pergunta_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `resposta_id` (`resposta_id`);

--
-- Índices de tabela `usuarios_patologias`
--
ALTER TABLE `usuarios_patologias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios_tratamentos`
--
ALTER TABLE `usuarios_tratamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_patologia_id` (`usuario_patologia_id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimentos_realizado`
--
ALTER TABLE `atendimentos_realizado`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimento_image`
--
ALTER TABLE `atendimento_image`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `caixa`
--
ALTER TABLE `caixa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas`
--
ALTER TABLE `contas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `gabaritos`
--
ALTER TABLE `gabaritos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `patologias`
--
ALTER TABLE `patologias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `perguntas`
--
ALTER TABLE `perguntas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `questionarios`
--
ALTER TABLE `questionarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `respostas`
--
ALTER TABLE `respostas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `servicos_agendado`
--
ALTER TABLE `servicos_agendado`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipos_pagamento`
--
ALTER TABLE `tipos_pagamento`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios_gabarito`
--
ALTER TABLE `usuarios_gabarito`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios_patologias`
--
ALTER TABLE `usuarios_patologias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios_tratamentos`
--
ALTER TABLE `usuarios_tratamentos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
