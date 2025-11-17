<?php
session_start();

if(!isset($_SESSION['pedido_id'])) {
    header("Location: index.php");
    exit();
}

$pedido_id = $_SESSION['pedido_id'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado - Cupcake Delícia</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">🍰 Cupcake Delícia</div>
            <div class="menu">
                <a href="index.php">Home</a>
                <a href="logout.php">Sair</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <div style="text-align: center; padding: 3rem;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🎉</div>
            <h1>Pedido Confirmado!</h1>
            <p style="font-size: 1.2rem; margin: 1rem 0;">
                Obrigado pela sua compra! Seu pedido <strong>#<?= $pedido_id ?></strong> foi recebido com sucesso.
            </p>
            <p style="margin-bottom: 2rem;">
                Você receberá um e-mail com os detalhes do pedido e atualizações de entrega.
            </p>
            
            <div style="background: #f8f9fa; padding: 2rem; border-radius: 10px; max-width: 400px; margin: 0 auto;">
                <h3>📦 Status do Pedido</h3>
                <p><strong>Número do Pedido:</strong> #<?= $pedido_id ?></p>
                <p><strong>Status:</strong> Em preparação</p>
                <p><strong>Previsão de Entrega:</strong> 2-3 dias úteis</p>
            </div>
            
            <div style="margin-top: 2rem;">
                <a href="index.php" class="btn">Continuar Comprando</a>
            </div>
        </div>
    </main>
    
    <script>
        // Limpa o ID do pedido da sessão após mostrar
        setTimeout(() => {
            <?php unset($_SESSION['pedido_id']); ?>
        }, 5000);
    </script>
</body>
</html>