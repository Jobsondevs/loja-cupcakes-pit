<?php
session_start();

// Verifica se é administrador
if(!isset($_SESSION['cliente']) || $_SESSION['cliente']['email'] != 'admin@admin.com') {
    header("Location: ../login.php");
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

// Busca estatísticas
$sql_pedidos = "SELECT COUNT(*) as total FROM pedidos";
$result = $conn->query($sql_pedidos);
$total_pedidos = $result->fetch_assoc()['total'];

$sql_clientes = "SELECT COUNT(*) as total FROM clientes WHERE email != 'admin@admin.com'";
$result = $conn->query($sql_clientes);
$total_clientes = $result->fetch_assoc()['total'];

$sql_cupcakes = "SELECT COUNT(*) as total FROM cupcakes WHERE ativo = 1";
$result = $conn->query($sql_cupcakes);
$total_cupcakes = $result->fetch_assoc()['total'];

// Últimos pedidos
$sql_ultimos_pedidos = "SELECT p.*, c.nome as cliente_nome 
                        FROM pedidos p 
                        JOIN clientes c ON p.cliente_id = c.id 
                        ORDER BY p.data_pedido DESC 
                        LIMIT 5";
$ultimos_pedidos = $conn->query($sql_ultimos_pedidos);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Cupcake Delícia</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        
        .card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .card h3 {
            font-size: 2rem;
            color: #ff6b8b;
            margin-bottom: 0.5rem;
        }
        
        .admin-menu {
            display: flex;
            gap: 1rem;
            margin: 2rem 0;
            flex-wrap: wrap;
        }
        
        .admin-menu a {
            background: #343a40;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s;
        }
        
        .admin-menu a:hover {
            background: #495057;
        }
        
        .pedidos-lista {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
        .pedido-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }
        
        .pedido-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">👑 Painel Administrativo</div>
            <div class="menu">
                <a href="../index.php">Ver Loja</a>
                <a href="../logout.php">Sair</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <h1>Dashboard - Cupcake Delícia</h1>
        <p>Bem-vindo ao painel de administração da loja.</p>
        
        <!-- Menu de Navegação -->
        <div class="admin-menu">
            <a href="cupcakes.php">🍰 Gerenciar Cupcakes</a>
            <a href="pedidos.php">📦 Gerenciar Pedidos</a>
            <a href="cupcake-form.php">➕ Adicionar Cupcake</a>
        </div>
        
        <!-- Estatísticas -->
        <div class="dashboard-cards">
            <div class="card">
                <h3><?php echo $total_pedidos; ?></h3>
                <p>Total de Pedidos</p>
            </div>
            <div class="card">
                <h3><?php echo $total_clientes; ?></h3>
                <p>Clientes Cadastrados</p>
            </div>
            <div class="card">
                <h3><?php echo $total_cupcakes; ?></h3>
                <p>Cupcakes Ativos</p>
            </div>
            <div class="card">
                <h3>R$ <?php echo number_format($total_pedidos * 15, 2, ',', '.'); ?></h3>
                <p>Faturamento Estimado</p>
            </div>
        </div>
        
        <!-- Últimos Pedidos -->
        <div class="pedidos-lista">
            <h3>📦 Últimos Pedidos</h3>
            <?php if($ultimos_pedidos->num_rows > 0): ?>
                <?php while($pedido = $ultimos_pedidos->fetch_assoc()): ?>
                <div class="pedido-item">
                    <div>
                        <strong>Pedido #<?php echo $pedido['id']; ?></strong>
                        <p>Cliente: <?php echo $pedido['cliente_nome']; ?></p>
                        <p>Data: <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></p>
                    </div>
                    <div style="text-align: right;">
                        <p><strong>R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?></strong></p>
                        <span class="status status-<?php echo $pedido['status']; ?>">
                            <?php echo ucfirst($pedido['status']); ?>
                        </span>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; padding: 2rem;">Nenhum pedido encontrado.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

<?php $conn->close(); ?>