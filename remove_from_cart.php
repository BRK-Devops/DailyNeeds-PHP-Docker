<?php
require_once 'config/database.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$cart_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($cart_id > 0) {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$cart_id, $_SESSION['user_id']]);
    $_SESSION['message'] = 'Item removed from cart!';
}

header('Location: cart.php');
exit();
