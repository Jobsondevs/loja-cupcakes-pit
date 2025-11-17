 Loja de Cupcakes - Projeto PIT

Sistema de e-commerce para loja de cupcakes desenvolvido em PHP como projeto acadêmico para o Projeto Integrador Transdisciplinar.

 Funcionalidades

 Para Clientes
- ✅ Cadastro e login de usuários
- ✅ Navegação na vitrine de cupcakes
- ✅ Adicionar produtos ao carrinho
- ✅ Finalização de pedidos
- ✅ Acompanhamento de pedidos

 Para Administradores
- ✅ Painel administrativo completo
- ✅ Gerenciamento de cupcakes (CRUD)
- ✅ Controle de pedidos
- ✅ Atualização de status
- ✅ Dashboard com estatísticas

Tecnologias Utilizadas

- **Back-end:** PHP 7.4+
- **Banco de Dados:** MySQL 5.7+
- **Front-end:** HTML5, CSS3, JavaScript
- **Servidor:** Apache (XAMPP)
- **Controle de Versão:** Git e GitHub

Instalação e Configuração

 Pré-requisitos
- XAMPP ou servidor Apache com PHP
- MySQL
- Navegador web moderno

Passo a Passo

1. **Instale o XAMPP**
   - Download em: [https://www.apachefriends.org/](https://www.apachefriends.org/)

2. **Configure o projeto**
   - Extraia a pasta `cupcakes` em `C:\xampp\htdocs\`

3. **Configure o banco de dados**
   - Acesse: `http://localhost/phpmyadmin`
   - Crie um banco chamado: `loja_cupcakes`
   - Execute o script: `sql/schema.sql`

4. **Acesse o sistema**
   - **Loja:** `http://localhost/cupcakes/`
   - **Admin:** `http://localhost/cupcakes/admin/index.php`

 Credenciais de Teste

### Administrador
- **Email:** admin@admin.com
- **Senha:** 123456

Cliente Comum
- Faça cadastro pela página de cadastro

## 📁 Estrutura do Projeto
cupcakes/
├── 📄 index.php # Página inicial
├── 📄 cadastro.php # Cadastro de clientes
├── 📄 login.php # Sistema de login
├── 📄 carrinho.php # Carrinho de compras
├── 📄 checkout.php # Finalização de pedido
├── 📄 pedido_sucesso.php # Confirmação de pedido
├── 📄 adicionar_carrinho.php # Adicionar itens ao carrinho
├── 📄 remover_carrinho.php # Remover itens do carrinho
├── 📄 logout.php # Logout do sistema
├── 📁 css/ # Estilos
│ └── 📄 style.css # Folha de estilos principal
├── 📁 imagens/ # Imagens dos produtos
├── 📁 admin/ # Painel administrativo
│ ├── 📄 index.php # Dashboard
│ ├── 📄 cupcakes.php # Gerenciar cupcakes
│ ├── 📄 cupcake-form.php # Formulário de cupcakes
│ ├── 📄 pedidos.php # Gerenciar pedidos
│ └── 📄 excluir_cupcake.php # Excluir cupcakes
├── 📁 sql/ # Scripts do banco
│ └── 📄 schema.sql # Estrutura do banco
├── 📄 README.md # Este arquivo
├── 📄 TESTES.md # Relatório de testes
└── 📄 LAUDO.md # Laudo de qualidade
- Faça cadastro pela página de cadastro

Testes Realizados

O sistema passou por testes rigorosos com 5 usuários diferentes, demonstrando alta usabilidade e estabilidade. Para detalhes completos, consulte:
- [Relatório de Testes](TESTES.md)
- [Laudo de Qualidade](LAUDO.md)

Suporte

Em caso de dúvidas sobre a instalação ou funcionamento do sistema, consulte a documentação ou entre em contato com o desenvolvedor.
