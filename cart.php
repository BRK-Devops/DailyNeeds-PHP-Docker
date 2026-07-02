<?php
require_once 'config/database.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$stmt = $pdo->prepare("
    SELECT c.id as cart_id, c.quantity, p.id as product_id, p.name, p.price, p.image 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll();

$total = 0;
foreach($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - DailyNeeds</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-crown"></i>DailyNeeds
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-arrow-left"></i> Continue Shopping
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-4" style="font-family:'Playfair Display',serif;color:var(--silver);">
            <i class="fas fa-shopping-cart me-2" style="color:var(--blue);"></i>Your Cart
        </h2>

        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <?php if(count($cart_items) > 0): ?>
            <div class="row">
                <div class="col-md-8">
                    <?php foreach($cart_items as $item): ?>
                        <div class="cart-item">
                            <div class="row align-items-center">
                                <div class="col-md-1">
                                    <i class="fas fa-box" style="font-size:30px;color:var(--blue);"></i>
                                </div>
                                <div class="col-md-4">
                                    <div class="item-name"><?php echo $item['name']; ?></div>
                                    <span style="color:var(--gray);font-size:13px;">₹<?php echo number_format($item['price'], 2); ?> each</span>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge" style="background:var(--blue);color:var(--white);padding:8px 15px;">
                                        Qty: <?php echo $item['quantity']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <span class="item-price">₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                                </div>
                                <div class="col-md-2 text-end">
                                    <a href="remove_from_cart.php?id=<?php echo $item['cart_id']; ?>" 
                                       class="btn" style="color:#FF6B6B;font-size:20px;"
                                       onclick="return confirm('Remove from cart?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="col-md-4">
                    <div class="cart-summary">
                        <h4>Order Summary</h4>
                        <div class="d-flex justify-content-between mb-2" style="color:var(--gray-light);">
                            <span>Items:</span>
                            <span><?php echo count($cart_items); ?></span>
                        </div>
                        <hr style="border-color:rgba(74,144,217,0.2);">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color:var(--gray-light);">Total:</span>
                            <span class="total-amount">₹<?php echo number_format($total, 2); ?></span>
                        </div>
                        <button class="btn-blue w-100 mt-4" disabled>
                            <i class="fas fa-credit-card me-2"></i>Proceed to Checkout
                        </button>
                        <p class="text-center mt-2" style="color:var(--gray);font-size:12px;">Checkout feature coming soon!</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart" style="font-size:80px;color:var(--gray);margin-bottom:20px;"></i>
                <h3 style="color:var(--gray-light);">Your cart is empty</h3>
                <p style="color:var(--gray);">Explore our premium collection now!</p>
                <a href="dashboard.php" class="btn-blue">Browse Products</a>
            </div>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <div class="container">
            <p>© 2026 <span class="silver-text">DailyNeeds</span> — Premium Grocery Store. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
