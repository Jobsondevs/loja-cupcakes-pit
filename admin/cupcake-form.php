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

$cupcake = null;
$editar = false;

// Se veio ID, está editando
if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM cupcakes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cupcake = $result->fetch_assoc();
    $editar = true;
}

$sucesso = "";
$erro = "";

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = floatval($_POST['preco']);
    $estoque = intval($_POST['estoque']);
    $imagem = $_POST['imagem'] ?: 'default.jpg';
    $ativo = isset($_POST['ativo']) ? 1 : 0;
    
    // Validações
    if(empty($nome) || empty($descricao) || $preco <= 0) {
        $erro = "Preencha todos os campos obrigatórios corretamente!";
    } else {
        if($editar) {
            // Atualiza cupcake existente
            $sql = "UPDATE cupcakes SET nome = ?, descricao = ?, preco = ?, estoque = ?, imagem = ?, ativo = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdisii", $nome, $descricao, $preco, $estoque, $imagem, $ativo, $id);
        } else {
            // Insere novo cupcake
            $sql = "INSERT INTO cupcakes (nome, descricao, preco, estoque, imagem, ativo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdisi", $nome, $descricao, $preco, $estoque, $imagem, $ativo);
        }
        
        if($stmt->execute()) {
            $sucesso = $editar ? "Cupcake atualizado com sucesso!" : "Cupcake cadastrado com sucesso!";
            if(!$editar) {
                // Limpa o formulário após cadastro
                $_POST = [];
            }
        } else {
            $erro = "Erro ao salvar cupcake: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $editar ? 'Editar' : 'Adicionar' ?> Cupcake - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">🍰 <?= $editar ? 'Editar' : 'Adicionar' ?> Cupcake</div>
            <div class="menu">
                <a href="cupcakes.php">Voltar</a>
                <a href="index.php">Dashboard</a>
                <a href="../logout.php">Sair</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <div style="max-width: 600px; margin: 0 auto;">
            <h1><?= $editar ? 'Editar Cupcake' : 'Adicionar Novo Cupcake' ?></h1>
            
            <?php if($sucesso): ?>
                <div class="alert alert-success"><?= $sucesso ?></div>
            <?php endif; ?>
            
            <?php if($erro): ?>
                <div class="alert alert-error"><?= $erro ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="nome">Nome do Cupcake:*</label>
                    <input type="text" id="nome" name="nome" required
                           value="<?= $editar ? $cupcake['nome'] : ($_POST['nome'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="descricao">Descrição:*</label>
                    <textarea id="descricao" name="descricao" rows="4" required><?= $editar ? $cupcake['descricao'] : ($_POST['descricao'] ?? '') ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="preco">Preço (R$):*</label>
                    <input type="number" id="preco" name="preco" step="0.01" min="0.01" required
                           value="<?= $editar ? $cupcake['preco'] : ($_POST['preco'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="estoque">Estoque:*</label>
                    <input type="number" id="estoque" name="estoque" min="0" required
                           value="<?= $editar ? $cupcake['estoque'] : ($_POST['estoque'] ?? 0) ?>">
                </div>
                
                <div class="form-group">
                    <label for="imagem">Nome da Imagem:</label>
                    <input type="text" id="imagem" name="imagem" 
                           placeholder="chocolate.jpg, morango.jpg, etc."
                           value="<?= $editar ? $cupcake['imagem'] : ($_POST['imagem'] ?? '') ?>">
                    <small>Coloque a imagem na pasta "imagens/"</small>
                </div>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="ativo" value="1" 
                            <?= ($editar && $cupcake['ativo']) || !$editar ? 'checked' : '' ?>>
                        Cupcake ativo na loja
                    </label>
                </div>
                
                <button type="submit" class="btn">
                    <?= $editar ? 'Atualizar Cupcake' : 'Cadastrar Cupcake' ?>
                </button>
                <a href="cupcakes.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </main>
</body>
</html>

<?php $conn->close(); ?>