<?php
session_start();

if(!isset($_SESSION['cliente']) || $_SESSION['cliente']['email'] != 'admin@admin.com') {
    header("Location: ../login.php");
    exit();
}

if(isset($_GET['id'])) {
    $host = "localhost";
    $user = "root"; 
    $password = "";
    $database = "loja_cupcakes";

    $conn = new mysqli($host, $user, $password, $database);

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    $id = intval($_GET['id']);
    
    // Marca como inativo em vez de excluir
    $sql = "UPDATE cupcakes SET ativo = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $_SESSION['sucesso'] = "Cupcake excluído com sucesso!";
    $conn->close();
}

header("Location: cupcakes.php");
exit();
?>