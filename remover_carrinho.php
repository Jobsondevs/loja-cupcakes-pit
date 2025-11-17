<?php
session_start();

if(isset($_GET['id'])) {
    $cupcake_id = intval($_GET['id']);
    
    if(isset($_SESSION['carrinho'])) {
        // Remove o item do carrinho
        foreach($_SESSION['carrinho'] as $key => $item) {
            if($item['cupcake_id'] == $cupcake_id) {
                unset($_SESSION['carrinho'][$key]);
                $_SESSION['sucesso'] = "Item removido do carrinho!";
                break;
            }
        }
        
        // Reindexa o array
        $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
    }
}

// Volta para o carrinho
header("Location: carrinho.php");
exit();
?>