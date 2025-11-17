# 🧁 Loja de Cupcakes - Projeto PIT

Sistema completo de e-commerce para loja de cupcakes desenvolvido em PHP.

## 🚀 Funcionalidades

### Para Clientes
- Cadastro e login de usuários
- Navegação na vitrine de cupcakes
- Adicionar produtos ao carrinho
- Finalização de pedidos
- Acompanhamento de pedidos

### Para Administradores
- Painel administrativo
- Gerenciamento de cupcakes (CRUD)
- Controle de pedidos
- Atualização de status
- Dashboard com estatísticas

## 🛠️ Tecnologias

- PHP 7.4+
- MySQL 5.7+
- HTML5 & CSS3
- XAMPP (ambiente de desenvolvimento)

## 📦 Instalação

1. **Instale o XAMPP**
   - Baixe em: https://www.apachefriends.org/

2. **Configure o projeto**
   - Coloque a pasta `cupcakes` em `C:\xampp\htdocs\`

3. **Configure o banco de dados**
   - Acesse: `http://localhost/phpmyadmin`
   - Execute o script `sql/schema.sql`

4. **Acesse o sistema**
   - **Loja:** `http://localhost/cupcakes/`
   - **Admin:** `http://localhost/cupcakes/admin/index.php`
     - Email: `admin@admin.com`
     - Senha: `123456`

## 📁 Estrutura do Projeto
cupcakes/
├── index.php # Página inicial
├── cadastro.php # Cadastro de clientes
├── login.php # Login de clientes
├── carrinho.php # Carrinho de compras
├── checkout.php # Finalização de pedido
├── admin/ # Painel administrativo
├── css/ # Estilos
├── imagens/ # Imagens dos produtos
└── sql/ # Scripts do banco

## 👤 Credenciais de Teste

### Administrador
- **Email:** admin@admin.com
- **Senha:** 123456

### Cliente Comum
- Faça cadastro pela página de cadastro

