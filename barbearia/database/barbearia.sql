DROP DATABASE IF EXISTS barbearia;
CREATE DATABASE barbearia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE barbearia;

CREATE TABLE usuarios (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  senha_hash VARCHAR(255) NOT NULL,
  perfil ENUM('ADMINISTRADOR','RECEPCIONISTA') NOT NULL DEFAULT 'RECEPCIONISTA',
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  ultimo_acesso_em DATETIME NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE barbeiros (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  telefone VARCHAR(20) NOT NULL,
  email VARCHAR(150) NULL UNIQUE,
  especialidade VARCHAR(150) NULL,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE servicos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  preco DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  duracao INT UNSIGNED NOT NULL DEFAULT 30,
  status TINYINT(1) NOT NULL DEFAULT 1,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE agendamentos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cliente_nome VARCHAR(120) NOT NULL,
  cliente_telefone VARCHAR(20) NOT NULL,
  barbeiro_id BIGINT UNSIGNED NOT NULL,
  servico_id BIGINT UNSIGNED NOT NULL,
  data_hora DATETIME NOT NULL,
  observacoes VARCHAR(255) NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_agendamento_barbeiro FOREIGN KEY (barbeiro_id) REFERENCES barbeiros(id) ON DELETE CASCADE,
  CONSTRAINT fk_agendamento_servico FOREIGN KEY (servico_id) REFERENCES servicos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Usuário inicial: admin@barbearia.com | senha: password
INSERT INTO usuarios (nome, email, senha_hash, perfil) VALUES
('Administrador', 'admin@barbearia.com', '$2y$10$0iJXZ2XYRXcQv200gOn2u.nP4V51CVIlZSC.rayGp3bFlx1.rUToG', 'ADMINISTRADOR');

INSERT INTO barbeiros (nome, telefone, email, especialidade, ativo) VALUES
('Carlos Silva', '(42) 98888-1111', 'carlos@barbearia.com', 'Degradê e Barba', 1),
('Lucas Santos', '(42) 97777-2222', 'lucas@barbearia.com', 'Corte Clássico', 1);

INSERT INTO servicos (nome, preco, duracao, status) VALUES
('Corte Tradicional', 40.00, 30, 1),
('Barba Completa', 30.00, 25, 1),
('Combo Cabelo + Barba', 65.00, 50, 1);

INSERT INTO agendamentos (cliente_nome, cliente_telefone, barbeiro_id, servico_id, data_hora, observacoes) VALUES
('Marcos Pereira', '(42) 99123-4567', 1, 1, CONCAT(CURDATE(), ' 14:00:00'), 'Cliente pontual'),
('Gabriel Souza', '(42) 99234-5678', 2, 3, CONCAT(CURDATE(), ' 15:30:00'), 'Primeira vez');
