<?php
session_start();

// Verifica se o usuário está logado
if(!isset($_SESSION['cliente'])) {
    $_SESSION['erro'] = "Você precisa fazer login para acessar o carrinho!";
    header("Location: login.php");
    exit();
}

// Conexão com o banco
$host = "localhost";
$user = "root"; 
$password = "";
$database = "loja_cupcakes";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Inicializa o carrinho se não existir
if(!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$carrinho = $_SESSION['carrinho'];
$total = 0;

// Busca informações dos cupcakes no carrinho
$carrinho_detalhado = [];
foreach($carrinho as $item) {
    $sql = "SELECT * FROM cupcakes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item['cupcake_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($cupcake = $result->fetch_assoc()) {
        $cupcake['quantidade'] = $item['quantidade'];
        $cupcake['subtotal'] = $cupcake['preco'] * $item['quantidade'];
        $total += $cupcake['subtotal'];
        $carrinho_detalhado[] = $cupcake;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho - Cupcake Delícia</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">🍰 Cupcake Delícia</div>
            <div class="menu">
                <a href="index.php">Home</a>
                <a href="carrinho.php">Carrinho (<?= count($carrinho) ?>)</a>
                <a href="logout.php">Sair</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <h2>🛒 Seu Carrinho</h2>
        
        <?php if(empty($carrinho_detalhado)): ?>
            <div style="text-align: center; padding: 3rem;">
                <h3>Seu carrinho está vazio</h3>
                <p>Adicione alguns cupcakes deliciosos!</p>
                <a href="index.php" class="btn">Continuar Comprando</a>
            </div>
        <?php else: ?>
            <div class="carrinho-itens">
                <?php foreach($carrinho_detalhado as $item): ?>
                <div class="carrinho-item">
                    <div>
                        <h4><?= $item['nome'] ?></h4>
                        <p>Quantidade: <?= $item['quantidade'] ?></p>
                        <p>Preço unitário: R$ <?= number_format($item['preco'], 2, ',', '.') ?></p>
                    </div>
                    <div style="text-align: right;">
                        <p style="font-size: 1.2rem; font-weight: bold; color: #ff6b8b;">
                            R$ <?= number_format($item['subtotal'], 2, ',', '.') ?>
                        </p>
                        <a href="remover_carrinho.php?id=<?= $item['id'] ?>" 
                           class="btn btn-secondary" 
                           style="margin-top: 0.5rem;">
                            Remover
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="carrinho-total">
                <h3>Total: R$ <?= number_format($total, 2, ',', '.') ?></h3>
                <a href="checkout.php" class="btn">Finalizar Compra</a>
                <a href="index.php" class="btn btn-secondary">Continuar Comprando</a>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>

<?php $conn->close(); ?>