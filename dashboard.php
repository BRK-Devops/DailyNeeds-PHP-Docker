<?php
require_once 'config/database.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM cart WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cart_count = $stmt->fetch()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DailyNeeds</title>
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
                        <a class="nav-link position-relative" href="cart.php">
                            <i class="fas fa-shopping-cart"></i> Cart
                            <?php if($cart_count > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background:var(--blue);color:var(--white);">
                                    <?php echo $cart_count; ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?>
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

    <div class="container mt-5">
        <div class="hero" style="padding:40px 0;border-radius:15px;margin-bottom:40px;">
            <h1 style="font-size:36px;">Welcome back, <?php echo $_SESSION['username']; ?>! 👋</h1>
            <p>Explore our curated collection of fresh groceries</p>
        </div>

        <h2 class="text-center mb-4" style="font-family:'Playfair Display',serif;color:var(--silver);">
            <i class="fas fa-clock me-2" style="color:var(--blue);"></i> Browse Categories
        </h2>
        <div class="row g-4">
            <?php
            $categories = $pdo->query("SELECT * FROM categories");
            while($category = $categories->fetch()):
            ?>
            <div class="col-md-3 col-sm-6">
                <a href="products.php?category=<?php echo $category['id']; ?>" class="category-card">
                    <i class="fas <?php echo $category['icon']; ?>"></i>
                    <div class="blue-line"></div>
                    <h5><?php echo $category['name']; ?></h5>
                    <p>View Collection <i class="fas fa-arrow-right ms-1"></i></p>
                </a>
            </div>
            <?php endwhile; ?>
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
