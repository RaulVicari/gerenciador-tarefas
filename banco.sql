CREATE DATABASE gerenciador_tarefas;

USE gerenciador_tarefas;

CREATE TABLE tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT,
    status ENUM('Pendente', 'Concluída') DEFAULT 'Pendente',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
