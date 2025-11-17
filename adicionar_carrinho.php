<?php
session_start();

// Verifica se o usuário está logado
if(!isset($_SESSION['cliente'])) {
    $_SESSION['erro'] = "Você precisa fazer login para adicionar itens ao carrinho!";
    header("Location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cupcake_id'])) {
    $cupcake_id = intval($_POST['cupcake_id']);
    
    // Inicializa o carrinho se não existir
    if(!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }
    
    // Verifica se o cupcake já está no carrinho
    $encontrado = false;
    foreach($_SESSION['carrinho'] as &$item) {
        if($item['cupcake_id'] == $cupcake_id) {
            $item['quantidade'] += 1;
            $encontrado = true;
            break;
        }
    }
    
    // Se não encontrou, adiciona novo item
    if(!$encontrado) {
        $_SESSION['carrinho'][] = [
            'cupcake_id' => $cupcake_id,
            'quantidade' => 1
        ];
    }
    
    $_SESSION['sucesso'] = "Cupcake adicionado ao carrinho!";
}

// Volta para a página anterior
header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit();
?>