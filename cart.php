<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Handle clear action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'clear') {
    $_SESSION['cart'] = [];
    header('Location: cart.php');
    exit;
}

$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $c) { $total += floatval($c['price'] ?? 0); }
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .cart { max-width:900px; margin:48px auto; }
        .cart-item { display:flex; gap:12px; align-items:center; background:#fff; padding:12px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.04); margin-bottom:12px }
        .cart-item img { width:110px; height:110px; object-fit:cover; border-radius:6px }
    </style>
</head>
<body>
    <div class="container cart">
        <h1>Your Cart</h1>
        <?php if (empty($cart)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <?php foreach ($cart as $item): ?>
                <div class="cart-item">
                    <img src="<?php echo htmlspecialchars($item['image']) ?>" alt="">
                    <div style="flex:1">
                        <strong><?php echo htmlspecialchars($item['title']) ?></strong>
                        <div>$<?php echo number_format($item['price'],2) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
            <div style="text-align:right; font-weight:700; margin-top:8px">Total: $<?php echo number_format($total,2) ?></div>
            <form method="post" style="margin-top:12px">
                <button name="action" value="clear" class="btn secondary">Clear cart</button>
            </form>
        <?php endif; ?>
        <div style="margin-top:14px">
            <a href="products.php" class="link">Continue shopping</a>
        </div>
    </div>
</body>
</html>
