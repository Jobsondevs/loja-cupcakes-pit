<?php
session_start();

if(!isset($_SESSION['cliente']) || $_SESSION['cliente']['email'] != 'admin@admin.com') {
    header("Location: ../login.php");
    exit();
}

$host = "localhost";
$user = "root"; 
$password = "";
$database = "loja_cupcakes";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Atualiza status do pedido
if(isset($_POST['atualizar_status'])) {
    $pedido_id = intval($_POST['pedido_id']);
    $novo_status = $_POST['status'];
    
    $sql = "UPDATE pedidos SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $novo_status, $pedido_id);
    $stmt->execute();
    
    $_SESSION['sucesso'] = "Status do pedido atualizado!";
    header("Location: pedidos.php");
    exit();
}

// Busca todos os pedidos
$sql = "SELECT p.*, c.nome as cliente_nome, c.email as cliente_email 
        FROM pedidos p 
        JOIN clientes c ON p.cliente_id = c.id 
        ORDER BY p.data_pedido DESC";
$result = $conn->query($sql);
$pedidos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Pedidos - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">📦 Gerenciar Pedidos</div>
            <div class="menu">
                <a href="index.php">Dashboard</a>
                <a href="cupcakes.php">Cupcakes</a>
                <a href="../index.php">Ver Loja</a>
                <a href="../logout.php">Sair</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <h1>Gerenciar Pedidos</h1>
        
        <?php if(isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['sucesso']; ?>
                <?php unset($_SESSION['sucesso']); ?>
            </div>
        <?php endif; ?>
        
        <div style="background: white; padding: 1.5rem; border-radius: 10px; margin-top: 2rem;">
            <?php if(empty($pedidos)): ?>
                <div style="text-align: center; padding: 3rem;">
                    <p>Nenhum pedido encontrado.</p>
                </div>
            <?php else: ?>
                <?php foreach($pedidos as $pedido): ?>
                <div style="border: 1px solid #ddd; border-radius: 8px; padding: 1.5rem; margin-bottom: 1rem; background: #f8f9fa;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #ddd;">
                        <div>
                            <h3>Pedido #<?php echo $pedido['id']; ?></h3>
                            <p><strong>Cliente:</strong> <?php echo $pedido['cliente_nome']; ?> (<?php echo $pedido['cliente_email']; ?>)</p>
                            <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></p>
                            <p><strong>Endereço:</strong> <?php echo $pedido['endereco_entrega']; ?></p>
                        </div>
                        <div style="text-align: right;">
                            <p style="font-size: 1.5rem; font-weight: bold; color: #ff6b8b;">
                                R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?>
                            </p>
                            <span class="status status-<?php echo $pedido['status']; ?>">
                                <?php echo ucfirst($pedido['status']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Formulário de atualização de status -->
                    <form method="POST" style="display: flex; gap: 1rem; align-items: center; margin-top: 1rem;">
                        <input type="hidden" name="pedido_id" value="<?php echo $pedido['id']; ?>">
                        <strong>Atualizar Status:</strong>
                        <select name="status" required>
                            <option value="pendente" <?php echo $pedido['status'] == 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                            <option value="confirmado" <?php echo $pedido['status'] == 'confirmado' ? 'selected' : ''; ?>>Confirmado</option>
                            <option value="enviado" <?php echo $pedido['status'] == 'enviado' ? 'selected' : ''; ?>>Enviado</option>
                            <option value="entregue" <?php echo $pedido['status'] == 'entregue' ? 'selected' : ''; ?>>Entregue</option>
                        </select>
                        <button type="submit" name="atualizar_status" class="btn">Atualizar</button>
                    </form>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

<?php $conn->close(); ?>