-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15-Maio-2022 às 03:55
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Banco de dados: `podologiadb2`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamentos`
--

CREATE TABLE `agendamentos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `collaborator_id` int(11) NOT NULL,
  `agendador_id` int(11) NOT NULL,
  `data_agendamento` int(11) NOT NULL,
  `data_agendada` date NOT NULL,
  `hora_agendada` time NOT NULL,
  `observacao` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `atendimentos_realizado`
--

CREATE TABLE `atendimentos_realizado` (
  `id` int(11) NOT NULL,
  `agendamento_id` int(11) NOT NULL,
  `data_confirmacao` int(11) NOT NULL DEFAULT 0,
  `observacao` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `atendimento_image`
--

CREATE TABLE `atendimento_image` (
  `id` int(11) NOT NULL,
  `ATENDIMENTO_ID` int(11) NOT NULL,
  `IMG` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `caixa`
--

CREATE TABLE `caixa` (
  `id` int(11) NOT NULL,
  `horario_abertura` time NOT NULL,
  `valor_abertura` float(10,2) NOT NULL DEFAULT 0.00,
  `colaborador_id_abertura` int(11) NOT NULL,
  `data_abertura` date NOT NULL,
  `horario_fechamento` time DEFAULT NULL,
  `valor_fechamento` float(10,2) DEFAULT 0.00,
  `colaborador_id_fechamento` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `data_fechamento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `contas`
--

CREATE TABLE `contas` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(15) NOT NULL,
  `telefone_02` varchar(15) NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL DEFAULT '',
  `criacao` date NOT NULL,
  `ip` varchar(100) NOT NULL DEFAULT '0',
  `nv_acesso` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO `contas` VALUES (1,'podologia','2a5b86d598aeefd3dca18cc7904c5c4487140022','11994489463','0','podologia@gmail.com', '2000-01-01','0',3,1)
-- --------------------------------------------------------

--
-- Estrutura da tabela `gabaritos`
--

CREATE TABLE `gabaritos` (
  `id` int(11) NOT NULL,
  `pergunta_id` int(11) NOT NULL,
  `resposta_id` int(11) NOT NULL,
  `data_criacao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pagamentos`
--

CREATE TABLE `pagamentos` (
  `id` int(11) NOT NULL,
  `agendamento_id` int(11) NOT NULL,
  `valor_total` float(10,2) NOT NULL DEFAULT 0.00,
  `tipo_pagamento` int(11) NOT NULL DEFAULT 1,
  `data_pagamento` date NOT NULL,
  `hora_pagamento` time NOT NULL,
  `caixa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `patologias`
--

CREATE TABLE `patologias` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL DEFAULT '',
  `type` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `perguntas`
--

CREATE TABLE `perguntas` (
  `id` int(11) NOT NULL,
  `questionario_id` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL DEFAULT '',
  `data_criacao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `questionarios`
--

CREATE TABLE `questionarios` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `data_criacao` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `respostas`
--

CREATE TABLE `respostas` (
  `id` int(11) NOT NULL,
  `pergunta_id` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL DEFAULT '',
  `data_criacao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `servicos`
--

CREATE TABLE `servicos` (
  `id` int(11) NOT NULL,
  `tempo` time NOT NULL DEFAULT '00:00:00',
  `valor` float(10,2) NOT NULL DEFAULT 0.00,
  `descricao` varchar(255) NOT NULL DEFAULT '',
  `prioridade` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `servicos_agendado`
--

CREATE TABLE `servicos_agendado` (
  `id` int(11) NOT NULL,
  `agendamento_id` int(11) NOT NULL,
  `servico_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipos_pagamento`
--

CREATE TABLE `tipos_pagamento` (
  `id` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `conta_id` int(11) NOT NULL,
  `primeiro_nome` varchar(50) NOT NULL,
  `ultimo_nome` varchar(100) NOT NULL,
  `data_nascimento` date NOT NULL,
  `cpf` varchar(255) NOT NULL,
  `rg` varchar(255) NOT NULL,
  `genero` varchar(1) NOT NULL DEFAULT 'M',
  `rua` varchar(100) NOT NULL DEFAULT '',
  `bairro` varchar(100) NOT NULL DEFAULT '',
  `numero` smallint(6) NOT NULL DEFAULT 0,
  `complemento` varchar(50) DEFAULT '',
  `cep` varchar(15) NOT NULL DEFAULT '',
  `cidade` varchar(100) NOT NULL DEFAULT '',
  `estado` varchar(5) NOT NULL DEFAULT 'SP',
  `nacionalidade` varchar(100) NOT NULL DEFAULT 'BRASILEIRO',
  `profissao` varchar(100) NOT NULL DEFAULT '',
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
INSERT INTO `usuarios` VALUES (1,1,'ADMINISTRADOR','SISTEMA','1999-03-31','00000000000','000000000','M','','',0,'','','','SP','BRASILEIRO','',NULL)

--
-- Estrutura da tabela `usuarios_gabarito`
--

CREATE TABLE `usuarios_gabarito` (
  `id` int(11) NOT NULL,
  `pergunta_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `resposta_id` int(11) NOT NULL,
  `observacao` varchar(255) NOT NULL DEFAULT '',
  `data_criacao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios_patologias`
--

CREATE TABLE `usuarios_patologias` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `PE` varchar(2) NOT NULL,
  `PAT_N` int(11) NOT NULL,
  `PAT_ID` int(11) NOT NULL,
  `PAT_POS` smallint(6) NOT NULL,
  `PAT_TYPE` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios_tratamentos`
--

CREATE TABLE `usuarios_tratamentos` (
  `id` int(11) NOT NULL,
  `usuario_patologia_id` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agendador_id` (`agendador_id`),
  ADD KEY `collaborator_id` (`collaborator_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `atendimentos_realizado`
--
ALTER TABLE `atendimentos_realizado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agendamento_id` (`agendamento_id`);

--
-- Índices para tabela `atendimento_image`
--
ALTER TABLE `atendimento_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ATENDIMENTO_ID` (`ATENDIMENTO_ID`);

--
-- Índices para tabela `caixa`
--
ALTER TABLE `caixa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `colaborador_id_abertura` (`colaborador_id_abertura`),
  ADD KEY `colaborador_id_fechamento` (`colaborador_id_fechamento`);

--
-- Índices para tabela `contas`
--
ALTER TABLE `contas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Índices para tabela `gabaritos`
--
ALTER TABLE `gabaritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pergunta_id` (`pergunta_id`),
  ADD KEY `resposta_id` (`resposta_id`);

--
-- Índices para tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agendamento_id` (`agendamento_id`),
  ADD KEY `tipo_pagamento` (`tipo_pagamento`),
  ADD KEY `caixa_id` (`caixa_id`);

--
-- Índices para tabela `patologias`
--
ALTER TABLE `patologias`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `perguntas`
--
ALTER TABLE `perguntas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questionario_id` (`questionario_id`);

--
-- Índices para tabela `questionarios`
--
ALTER TABLE `questionarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `respostas`
--
ALTER TABLE `respostas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pergunta_id` (`pergunta_id`);

--
-- Índices para tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `servicos_agendado`
--
ALTER TABLE `servicos_agendado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agendamento_id` (`agendamento_id`),
  ADD KEY `servico_id` (`servico_id`);

--
-- Índices para tabela `tipos_pagamento`
--
ALTER TABLE `tipos_pagamento`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`,`rg`),
  ADD KEY `conta_id` (`conta_id`,`cpf`,`rg`);

--
-- Índices para tabela `usuarios_gabarito`
--
ALTER TABLE `usuarios_gabarito`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pergunta_id` (`pergunta_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `resposta_id` (`resposta_id`);

--
-- Índices para tabela `usuarios_patologias`
--
ALTER TABLE `usuarios_patologias`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `usuarios_tratamentos`
--
ALTER TABLE `usuarios_tratamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_patologia_id` (`usuario_patologia_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimentos_realizado`
--
ALTER TABLE `atendimentos_realizado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimento_image`
--
ALTER TABLE `atendimento_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `caixa`
--
ALTER TABLE `caixa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas`
--
ALTER TABLE `contas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `gabaritos`
--
ALTER TABLE `gabaritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `patologias`
--
ALTER TABLE `patologias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `perguntas`
--
ALTER TABLE `perguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `questionarios`
--
ALTER TABLE `questionarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `respostas`
--
ALTER TABLE `respostas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `servicos_agendado`
--
ALTER TABLE `servicos_agendado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipos_pagamento`
--
ALTER TABLE `tipos_pagamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios_gabarito`
--
ALTER TABLE `usuarios_gabarito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios_patologias`
--
ALTER TABLE `usuarios_patologias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios_tratamentos`
--
ALTER TABLE `usuarios_tratamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD CONSTRAINT `agendamentos_ibfk_1` FOREIGN KEY (`agendador_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `agendamentos_ibfk_2` FOREIGN KEY (`collaborator_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `agendamentos_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `atendimentos_realizado`
--
ALTER TABLE `atendimentos_realizado`
  ADD CONSTRAINT `atendimentos_realizado_ibfk_1` FOREIGN KEY (`agendamento_id`) REFERENCES `agendamentos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `atendimento_image`
--
ALTER TABLE `atendimento_image`
  ADD CONSTRAINT `atendimento_image_ibfk_1` FOREIGN KEY (`ATENDIMENTO_ID`) REFERENCES `atendimentos_realizado` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `caixa`
--
ALTER TABLE `caixa`
  ADD CONSTRAINT `caixa_ibfk_1` FOREIGN KEY (`colaborador_id_abertura`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `caixa_ibfk_2` FOREIGN KEY (`colaborador_id_fechamento`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `gabaritos`
--
ALTER TABLE `gabaritos`
  ADD CONSTRAINT `gabaritos_ibfk_1` FOREIGN KEY (`pergunta_id`) REFERENCES `perguntas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gabaritos_ibfk_2` FOREIGN KEY (`resposta_id`) REFERENCES `respostas` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD CONSTRAINT `pagamentos_ibfk_1` FOREIGN KEY (`agendamento_id`) REFERENCES `agendamentos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pagamentos_ibfk_2` FOREIGN KEY (`tipo_pagamento`) REFERENCES `tipos_pagamento` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pagamentos_ibfk_3` FOREIGN KEY (`caixa_id`) REFERENCES `caixa` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `perguntas`
--
ALTER TABLE `perguntas`
  ADD CONSTRAINT `perguntas_ibfk_1` FOREIGN KEY (`questionario_id`) REFERENCES `questionarios` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `respostas`
--
ALTER TABLE `respostas`
  ADD CONSTRAINT `respostas_ibfk_1` FOREIGN KEY (`pergunta_id`) REFERENCES `perguntas` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `servicos_agendado`
--
ALTER TABLE `servicos_agendado`
  ADD CONSTRAINT `servicos_agendado_ibfk_1` FOREIGN KEY (`agendamento_id`) REFERENCES `agendamentos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `servicos_agendado_ibfk_2` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`conta_id`) REFERENCES `contas` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `usuarios_gabarito`
--
ALTER TABLE `usuarios_gabarito`
  ADD CONSTRAINT `usuarios_gabarito_ibfk_1` FOREIGN KEY (`pergunta_id`) REFERENCES `perguntas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuarios_gabarito_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuarios_gabarito_ibfk_3` FOREIGN KEY (`resposta_id`) REFERENCES `respostas` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `usuarios_tratamentos`
--
ALTER TABLE `usuarios_tratamentos`
  ADD CONSTRAINT `usuarios_tratamentos_ibfk_1` FOREIGN KEY (`usuario_patologia_id`) REFERENCES `usuarios_patologias` (`id`) ON DELETE CASCADE;
COMMIT;
