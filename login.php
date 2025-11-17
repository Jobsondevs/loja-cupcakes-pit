<?php
session_start();

if(isset($_SESSION['cliente'])) {
    header("Location: index.php");
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

$erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    
    if (empty($email) || empty($senha)) {
        $erro = "Preencha e-mail e senha!";
    } else {
        // Busca o usuário
        $sql = "SELECT * FROM clientes WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($user = $result->fetch_assoc()) {
            // VERIFICAÇÃO SIMPLES DA SENHA (MD5)
            if (md5($senha) === $user['senha'] || password_verify($senha, $user['senha'])) {
                // Login bem-sucedido
                $_SESSION['cliente'] = [
                    'id' => $user['id'],
                    'nome' => $user['nome'],
                    'email' => $user['email']
                ];
                
                $_SESSION['sucesso'] = "Login realizado com sucesso!";
                header("Location: index.php");
                exit();
            } else {
                $erro = "Senha incorreta!";
            }
        } else {
            $erro = "E-mail não encontrado!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cupcake Delícia</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">🍰 Cupcake Delícia</div>
            <div class="menu">
                <a href="index.php">Home</a>
                <a href="cadastro.php">Cadastrar</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <div style="max-width: 500px; margin: 0 auto;">
            <h2>Login</h2>
            
            <?php if(isset($_SESSION['sucesso'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['sucesso']; ?>
                    <?php unset($_SESSION['sucesso']); ?>
                </div>
            <?php endif; ?>
            
            <?php if($erro): ?>
                <div class="alert alert-error"><?= $erro ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required
                           value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                
                <button type="submit" class="btn">Entrar</button>
            </form>
            
            <p style="text-align: center; margin-top: 1rem;">
                Não tem conta? <a href="cadastro.php">Cadastre-se aqui</a>
            </p>
        </div>
    </main>
</body>
</html>

<?php $conn->close(); ?>