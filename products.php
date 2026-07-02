<?php
require_once 'config/database.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;

$stmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
$stmt->execute([$category_id]);
$category = $stmt->fetch();

if(!$category) {
    header('Location: dashboard.php');
    exit();
}

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
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart"></i> Cart
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
        <div class="text-center mb-4">
            <h2 style="font-family:'Playfair Display',serif;color:var(--silver);">
                <i class="fas fa-clock me-2" style="color:var(--blue);"></i><?php echo $category['name']; ?>
            </h2>
            <p style="color:var(--gray-light);">Discover our premium collection of fresh products</p>
        </div>

        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <div class="row g-4">
            <?php if(count($products) > 0): ?>
                <?php foreach($products as $product): ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="product-card">
                            <div class="product-image-placeholder">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="card-body">
                                <h5 class="product-name"><?php echo $product['name']; ?></h5>
                                <p class="product-desc"><?php echo $product['description']; ?></p>
                                <p class="product-price">₹<?php echo number_format($product['price'], 2); ?></p>
                                <a href="add_to_cart.php?product=<?php echo $product['id']; ?>" class="btn-blue w-100 text-center d-block">
                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p style="color:var(--gray);">No products found in this collection.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p>© 2026 <span class="silver-text">DailyNeeds</span> — Premium Grocery Store. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
