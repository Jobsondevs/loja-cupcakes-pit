<?php
session_start();

// Conexão com o banco
$host = "localhost";
$user = "root"; 
$password = "";
$database = "loja_cupcakes";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$erro = "";

// Processa o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
    // Validações
    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = "Todos os campos são obrigatórios!";
    } elseif ($senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem!";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres!";
    } else {
        // Verifica se email já existe
        $sql = "SELECT id FROM clientes WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $erro = "Este e-mail já está cadastrado!";
        } else {
            // Cadastra o usuário
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "INSERT INTO clientes (nome, email, senha) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $nome, $email, $senha_hash);
            
            if ($stmt->execute()) {
                $_SESSION['sucesso'] = "Cadastro realizado com sucesso! Faça login.";
                header("Location: login.php");
                exit();
            } else {
                $erro = "Erro ao cadastrar. Tente novamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Cupcake Delícia</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">🍰 Cupcake Delícia</div>
            <div class="menu">
                <a href="index.php">Home</a>
                <a href="login.php">Login</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <div style="max-width: 500px; margin: 0 auto;">
            <h2>Criar Conta</h2>
            
            <?php if($erro): ?>
                <div class="alert alert-error"><?= $erro ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="nome">Nome Completo:</label>
                    <input type="text" id="nome" name="nome" required 
                           value="<?= isset($_POST['nome']) ? $_POST['nome'] : '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required
                           value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha (mínimo 6 caracteres):</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                
                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Senha:</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" required>
                </div>
                
                <button type="submit" class="btn">Cadastrar</button>
            </form>
            
            <p style="text-align: center; margin-top: 1rem;">
                Já tem conta? <a href="login.php">Faça login aqui</a>
            </p>
        </div>
    </main>
</body>
</html>

<?php $conn->close(); ?>