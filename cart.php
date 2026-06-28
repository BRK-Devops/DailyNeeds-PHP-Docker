<?php
require_once 'config/database.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get cart items
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-shopping-basket me-2"></i>DailyNeeds
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
        <h2 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>Your Cart</h2>
        
        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if(count($cart_items) > 0): ?>
            <div class="row">
                <div class="col-md-8">
                    <?php foreach($cart_items as $item): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <i class="fas fa-box fa-2x text-secondary"></i>
                                    </div>
                                    <div class="col-md-4">
                                        <h5><?php echo $item['name']; ?></h5>
                                        <p class="text-muted">₹<?php echo number_format($item['price'], 2); ?> each</p>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="badge bg-info">Qty: <?php echo $item['quantity']; ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <p class="fw-bold">₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="remove_from_cart.php?id=<?php echo $item['cart_id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Remove from cart?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Items:</span>
                                <span><?php echo count($cart_items); ?></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Total:</span>
                                <span class="text-success">₹<?php echo number_format($total, 2); ?></span>
                            </div>
                            <button class="btn btn-success w-100 mt-3" disabled>
                                <i class="fas fa-credit-card me-2"></i>Proceed to Checkout
                            </button>
                            <p class="text-muted text-center mt-2 small">Checkout feature coming soon!</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-5x text-muted mb-3"></i>
                <h3>Your cart is empty</h3>
                <p class="text-muted">Start shopping now!</p>
                <a href="dashboard.php" class="btn btn-success">
                    <i class="fas fa-arrow-left me-2"></i>Browse Products
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
