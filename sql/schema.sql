CREATE DATABASE IF NOT EXISTS loja_cupcakes;
USE loja_cupcakes;

-- Tabela de clientes
CREATE TABLE clientes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    endereco TEXT,
    telefone VARCHAR(20),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de cupcakes
CREATE TABLE cupcakes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    estoque INT DEFAULT 0,
    imagem VARCHAR(255),
    ativo BOOLEAN DEFAULT true,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de pedidos
CREATE TABLE pedidos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT,
    status VARCHAR(50) DEFAULT 'pendente',
    valor_total DECIMAL(10,2),
    endereco_entrega TEXT,
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

-- Tabela de itens do pedido
CREATE TABLE itens_pedido (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pedido_id INT,
    cupcake_id INT,
    quantidade INT,
    preco_unitario DECIMAL(10,2),
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (cupcake_id) REFERENCES cupcakes(id)
);

-- Inserir cupcakes de exemplo
INSERT INTO cupcakes (nome, descricao, preco, estoque, imagem) VALUES
('Cupcake Chocolate', 'Delicioso cupcake de chocolate belga com cobertura cremosa', 12.90, 50, 'chocolate.jpg'),
('Cupcake Morango', 'Cupcake com recheio de morango fresco e frosting especial', 14.90, 30, 'morango.jpg'),
('Cupcake Baunilha', 'Clássico cupcake de baunilha com frosting de baunilha', 11.90, 40, 'baunilha.jpg'),
('Cupcake Red Velvet', 'Cupcake aveludado vermelho com cream cheese frosting', 16.90, 25, 'redvelvet.jpg'),
('Cupcake Limão', 'Cupcake refrescante de limão com raspas cítricas', 13.90, 35, 'limao.jpg');

-- Criar usuário administrador (email: admin@admin.com, senha: 123456)
INSERT INTO clientes (nome, email, senha) VALUES 
('Administrador', 'admin@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');