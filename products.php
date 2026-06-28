<?php
require_once 'config/database.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Get category name
$stmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
$stmt->execute([$category_id]);
$category = $stmt->fetch();

if(!$category) {
    header('Location: dashboard.php');
    exit();
}

// Get products
$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ?");
$stmt->execute([$category_id]);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $category['name']; ?> - DailyNeeds</title>
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
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-cart-shopping"></i> Cart
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-arrow-left"></i> Back
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
        <h2 class="mb-4"><?php echo $category['name']; ?></h2>
        <div class="row g-4">
            <?php if(count($products) > 0): ?>
                <?php foreach($products as $product): ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="card product-card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-box fa-3x text-secondary mb-3"></i>
                                <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                <p class="card-text text-muted"><?php echo $product['description']; ?></p>
                                <p class="fw-bold text-success">₹<?php echo number_format($product['price'], 2); ?></p>
                                <a href="add_to_cart.php?product=<?php echo $product['id']; ?>" class="btn btn-success w-100">
                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No products found in this category.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
