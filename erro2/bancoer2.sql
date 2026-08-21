CREATE DATABASE IF NOT EXISTS crud_aula
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE crud_aula;

CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    descricao TEXT
);