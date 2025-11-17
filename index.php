<?php
session_start();

// Conexão com o banco de dados
$host = "localhost";
$user = "root"; 
$password = "";
$database = "loja_cupcakes";

$conn = new mysqli($host, $user, $password, $database);

// Verifica se há erro na conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Busca todos os cupcakes ativos
$sql = "SELECT * FROM cupcakes WHERE ativo = 1 AND estoque > 0 ORDER BY nome";
$result = $conn->query($sql);
$cupcakes = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $cupcakes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cupcake Delícia - Loja Online</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">🍰 Cupcake Delícia</div>
            <div class="menu">
                <a href="index.php">Home</a>
                <a href="carrinho.php">Carrinho</a>
                <?php if(isset($_SESSION['cliente'])): ?>
                    <span>Olá, <?= $_SESSION['cliente']['nome'] ?></span>
                    <a href="logout.php">Sair</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="cadastro.php">Cadastrar</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="container">
        <h1>Bem-vindo à Cupcake Delícia! 🧁</h1>
        <p>Encontre os cupcakes mais deliciosos feitos com ingredientes selecionados.</p>
        
        <?php if(isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['sucesso']; ?>
                <?php unset($_SESSION['sucesso']); ?>
            </div>
        <?php endif; ?>

        <div class="vitrine">
            <?php foreach($cupcakes as $cupcake): ?>
            <div class="cupcake-card">
                <img src="imagens/<?= $cupcake['imagem'] ?: 'default.jpg' ?>" 
                     alt="<?= $cupcake['nome'] ?>" 
                     onerror="this.src='imagens/default.jpg'">
                <h3><?= $cupcake['nome'] ?></h3>
                <p><?= $cupcake['descricao'] ?></p>
                <p class="preco">R$ <?= number_format($cupcake['preco'], 2, ',', '.') ?></p>
                
                <?php if(isset($_SESSION['cliente'])): ?>
                    <form action="adicionar_carrinho.php" method="POST">
                        <input type="hidden" name="cupcake_id" value="<?= $cupcake['id'] ?>">
                        <button type="submit" class="btn">Adicionar ao Carrinho</button>
                    </form>
                <?php else: ?>
                    <a href="login.php" class="btn">Fazer Login para Comprar</a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            
            <?php if(empty($cupcakes)): ?>
                <p style="grid-column: 1 / -1; text-align: center; padding: 2rem;">
                    Nenhum cupcake disponível no momento.
                </p>
            <?php endif; ?>
        </div>
    </main>

    <footer style="text-align: center; padding: 2rem; margin-top: 3rem; background: #333; color: white;">
        <p>&copy; 2024 Cupcake Delícia. Todos os direitos reservados.</p>
    </footer>
</body>
</html>

<?php $conn->close(); ?>