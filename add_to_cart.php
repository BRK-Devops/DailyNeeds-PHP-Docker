<?php
require_once 'config/database.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$product_id = isset($_GET['product']) ? (int)$_GET['product'] : 0;

if($product_id > 0) {
    // Check if product exists
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    
    if($stmt->rowCount() > 0) {
        // Check if already in cart
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$_SESSION['user_id'], $product_id]);
        
        if($stmt->rowCount() > 0) {
            // Update quantity
            $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$_SESSION['user_id'], $product_id]);
        } else {
            // Add to cart
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id) VALUES (?, ?)");
            $stmt->execute([$_SESSION['user_id'], $product_id]);
        }
        
        $_SESSION['message'] = 'Product added to cart successfully!';
    } else {
        $_SESSION['error'] = 'Product not found!';
    }
}

header('Location: ' . $_SERVER['HTTP_REFERER'] ?? 'dashboard.php');
exit();
