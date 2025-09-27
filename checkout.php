<?php
// checkout.php - Minimal checkout page for a single product
if (session_status() === PHP_SESSION_NONE) session_start();

$title = $_REQUEST['title'] ?? 'Classic Crew Neck T-Shirt';
$price = $_REQUEST['price'] ?? '49.99';
$image = $_REQUEST['image'] ?? 'https://images.unsplash.com/photo-1519741496631-9b1bf8f9b3d6?auto=format&fit=crop&w=1200&q=80';

// If image passed is relative, convert to absolute path under this script path so browsers can fetch it reliably
if ($image && !preg_match('#^https?://#i', $image)) {
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    if ($base === '') {
        $image = '/' . ltrim($image, '/');
    } else {
        if (strpos($image, 'photo/') === 0) {
            $image = $base . '/' . $image;
        } elseif (strpos($image, '/') === 0) {
            $image = $image;
        } else {
            $image = $base . '/photo/' . ltrim($image, '/');
        }
    }
}

// Handle Buy action: store item in session cart then redirect to thank_you
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'buy') {
    $item = [
        'title' => trim($_POST['title'] ?? $title),
        'price' => floatval($_POST['price'] ?? $price),
        'image' => trim($_POST['image'] ?? $image),
    ];
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][] = $item;
    header('Location: thank_you.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Checkout</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body { background:#fff; }
        .checkout-main { max-width:720px; margin:80px auto; text-align:center; }
        .brand { font-family: 'Playfair Display', Georgia, serif; font-style:italic; color:#6f42c1; font-size:22px; }
        .product-card { background:#fff; padding:28px; border-radius:12px; box-shadow:0 8px 26px rgba(0,0,0,0.06); }
        .product-image { width:100%; max-width:420px; height:420px; background-size:cover; background-position:center; margin:0 auto 18px; border-radius:8px; }
        .product-title { font-family: 'Playfair Display', Georgia, serif; font-size:22px; margin-bottom:8px; }
        .product-price { color:#6f42c1; font-size:28px; font-weight:700; margin-bottom:16px; }
        .buy-btn { background:#C7B5F5; color:#fff; padding:14px 20px; border-radius:999px; border:none; font-size:16px; cursor:pointer; }
    </style>
</head>
<body>
    <nav style="background:#fff; padding:18px 0; border-bottom:1px solid #f0ecec;">
        <div class="container" style="display:flex; align-items:center; justify-content:flex-start;">
            <div class="brand">EleganceBoutique</div>
        </div>
    </nav>

    <main class="checkout-main">
        <div class="product-card">
            <div class="product-image" style="background-image:url('<?php echo htmlspecialchars($image) ?>')"></div>
            <div class="product-title"><?php echo htmlspecialchars($title) ?></div>
            <div class="product-price">$<?php echo number_format((float)$price,2) ?></div>
            <form method="post" style="margin:0">
                <input type="hidden" name="action" value="buy">
                <input type="hidden" name="title" value="<?php echo htmlspecialchars($title) ?>">
                <input type="hidden" name="price" value="<?php echo htmlspecialchars($price) ?>">
                <input type="hidden" name="image" value="<?php echo htmlspecialchars($image) ?>">
                <button type="submit" class="buy-btn">Buy Now</button>
            </form>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <div class="footer-left">
                <h4>Quick Links</h4>
            </div>
            <div class="footer-right">
                <div class="social">
                    <a aria-label="Facebook" href="#">FB</a>
                    <a aria-label="Instagram" href="#">IG</a>
                    <a aria-label="LinkedIn" href="#">IN</a>
                </div>
            </div>
        </div>
        <div class="container footer-bottom">
        </div>
    </footer>
</body>
</html>
