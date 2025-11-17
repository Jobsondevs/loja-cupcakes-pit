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

// Busca todos os cupcakes
$sql = "SELECT * FROM cupcakes ORDER BY id DESC";
$result = $conn->query($sql);
$cupcakes = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Cupcakes - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .table {
            width: 100%;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .table-header {
            display: grid;
            grid-template-columns: 50px 100px 1fr 100px 100px 100px 120px;
            background: #343a40;
            color: white;
            padding: 1rem;
            font-weight: bold;
        }
        
        .table-row {
            display: grid;
            grid-template-columns: 50px 100px 1fr 100px 100px 100px 120px;
            padding: 1rem;
            border-bottom: 1px solid #eee;
            align-items: center;
        }
        
        .table-row:hover {
            background: #f8f9fa;
        }
        
        .table-row:last-child {
            border-bottom: none;
        }
        
        .cupcake-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .btn-small {
            padding: 0.3rem 0.7rem;
            font-size: 0.8rem;
            margin: 0.2rem;
        }
        
        .status-badge {
            padding: 0.3rem 0.7rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .status-ativo { background: #d4edda; color: #155724; }
        .status-inativo { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">🍰 Gerenciar Cupcakes</div>
            <div class="menu">
                <a href="index.php">Dashboard</a>
                <a href="cupcake-form.php">Adicionar Cupcake</a>
                <a href="../index.php">Ver Loja</a>
                <a href="../logout.php">Sair</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1>Gerenciar Cupcakes</h1>
            <a href="cupcake-form.php" class="btn">➕ Adicionar Novo Cupcake</a>
        </div>

        <div class="table">
            <div class="table-header">
                <div>ID</div>
                <div>Imagem</div>
                <div>Nome & Descrição</div>
                <div>Preço</div>
                <div>Estoque</div>
                <div>Status</div>
                <div>Ações</div>
            </div>
            
            <?php foreach($cupcakes as $cupcake): ?>
            <div class="table-row">
                <div>#<?= $cupcake['id'] ?></div>
                <div>
                    <img src="../imagens/<?= $cupcake['imagem'] ?: 'default.jpg' ?>" 
                         alt="<?= $cupcake['nome'] ?>" 
                         class="cupcake-img"
                         onerror="this.src='../imagens/default.jpg'">
                </div>
                <div>
                    <strong><?= $cupcake['nome'] ?></strong>
                    <p style="font-size: 0.9rem; color: #666; margin-top: 0.5rem;">
                        <?= substr($cupcake['descricao'], 0, 100) ?>...
                    </p>
                </div>
                <div>R$ <?= number_format($cupcake['preco'], 2, ',', '.') ?></div>
                <div><?= $cupcake['estoque'] ?></div>
                <div>
                    <span class="status-badge status-<?= $cupcake['ativo'] ? 'ativo' : 'inativo' ?>">
                        <?= $cupcake['ativo'] ? 'Ativo' : 'Inativo' ?>
                    </span>
                </div>
                <div>
                    <a href="cupcake-form.php?id=<?= $cupcake['id'] ?>" 
                       class="btn btn-small">✏️ Editar</a>
                    <a href="excluir_cupcake.php?id=<?= $cupcake['id'] ?>" 
                       class="btn btn-small btn-secondary"
                       onclick="return confirm('Tem certeza que deseja excluir este cupcake?')">
                       🗑️ Excluir
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if(empty($cupcakes)): ?>
            <div style="text-align: center; padding: 3rem;">
                <p>Nenhum cupcake cadastrado.</p>
                <a href="cupcake-form.php" class="btn">Adicionar Primeiro Cupcake</a>
            </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

<?php $conn->close(); ?>