<?php
session_start();

// Verifica se o usuário está logado e tem itens no carrinho
if(!isset($_SESSION['cliente'])) {
    header("Location: login.php");
    exit();
}

if(!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    $_SESSION['erro'] = "Seu carrinho está vazio!";
    header("Location: index.php");
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

// Busca informações do cliente
$cliente_id = $_SESSION['cliente']['id'];
$sql = "SELECT * FROM clientes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$result = $stmt->get_result();
$cliente = $result->fetch_assoc();

// Calcula total do carrinho
$carrinho = $_SESSION['carrinho'];
$total = 0;
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

// Processa o pedido
$erro = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $endereco_entrega = trim($_POST['endereco_entrega']);
    
    if(empty($endereco_entrega)) {
        $erro = "Por favor, informe o endereço de entrega!";
    } else {
        // Inicia transação
        $conn->begin_transaction();
        
        try {
            // Cria o pedido
            $sql = "INSERT INTO pedidos (cliente_id, valor_total, endereco_entrega) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ids", $cliente_id, $total, $endereco_entrega);
            $stmt->execute();
            $pedido_id = $conn->insert_id;
            
            // Adiciona os itens do pedido
            foreach($carrinho_detalhado as $item) {
                $sql = "INSERT INTO itens_pedido (pedido_id, cupcake_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iiid", $pedido_id, $item['id'], $item['quantidade'], $item['preco']);
                $stmt->execute();
                
                // Atualiza estoque
                $sql = "UPDATE cupcakes SET estoque = estoque - ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $item['quantidade'], $item['id']);
                $stmt->execute();
            }
            
            // Confirma a transação
            $conn->commit();
            
            // Limpa o carrinho
            unset($_SESSION['carrinho']);
            
            // Redireciona para página de sucesso
            $_SESSION['pedido_id'] = $pedido_id;
            header("Location: pedido_sucesso.php");
            exit();
            
        } catch (Exception $e) {
            // Desfaz a transação em caso de erro
            $conn->rollback();
            $erro = "Erro ao processar pedido: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Cupcake Delícia</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">🍰 Cupcake Delícia</div>
            <div class="menu">
                <a href="index.php">Home</a>
                <a href="carrinho.php">Carrinho</a>
                <a href="logout.php">Sair</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <h2>✅ Finalizar Pedido</h2>
        
        <?php if($erro): ?>
            <div class="alert alert-error"><?= $erro ?></div>
        <?php endif; ?>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Resumo do Pedido -->
            <div>
                <h3>Resumo do Pedido</h3>
                <?php foreach($carrinho_detalhado as $item): ?>
                <div class="carrinho-item">
                    <div>
                        <h4><?= $item['nome'] ?></h4>
                        <p>Quantidade: <?= $item['quantidade'] ?></p>
                    </div>
                    <div style="text-align: right;">
                        <p>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <div class="carrinho-total">
                    <h3>Total: R$ <?= number_format($total, 2, ',', '.') ?></h3>
                </div>
            </div>
            
            <!-- Formulário de Entrega -->
            <div>
                <h3>Informações de Entrega</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" value="<?= $cliente['nome'] ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email" id="email" value="<?= $cliente['email'] ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="endereco_entrega">Endereço de Entrega:*</label>
                        <textarea id="endereco_entrega" name="endereco_entrega" rows="4" required
                                  placeholder="Rua, número, bairro, cidade, CEP..."><?= $cliente['endereco'] ?? '' ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefone">Telefone para Contato:</label>
                        <input type="text" id="telefone" value="<?= $cliente['telefone'] ?? '' ?>" 
                               placeholder="(11) 99999-9999">
                    </div>
                    
                    <button type="submit" class="btn">Confirmar Pedido</button>
                    <a href="carrinho.php" class="btn btn-secondary">Voltar ao Carrinho</a>
                </form>
            </div>
        </div>
    </main>
</body>
</html>

<?php $conn->close(); ?>