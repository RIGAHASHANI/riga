<?php
// Public homepage for the clothing brand
require_once __DIR__ . '/db.php';

// The `products` table and demo data should be created/seeded in the database separately.
// This file assumes a `products` table already exists and is managed outside of this script.

// Fetch a few featured products using PDO
$products = [];
$pdo = get_pdo();
$stmt = $pdo->query('SELECT id, title, description, price, image FROM products ORDER BY created_at DESC LIMIT 4');
$products = $stmt->fetchAll();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Discover Your Unique Style</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Minimal inline adjustment for hero background height on small screens */
        .hero { min-height: 64vh; }
    </style>
</head>
<body>
    <nav class="nav">
        <div class="nav-inner container">
            <div class="nav-left"></div>
            <div class="nav-center">
                <!-- simple star icon logo -->
                <a class="logo" href="home.php" aria-label="Brand logo">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2.5l2.6 5.26L20.5 9.5l-4 3.9.95 5.54L12 16.9 6.55 18.94 7.5 13.4 3.5 9.5l5.9-.74L12 2.5z" fill="#6B5B95"/></svg>
                </a>
            </div>
            <div class="nav-right">
                <a href="home.php" class="nav-link active">Home</a>
                <a href="products.php" class="nav-link">Products</a>
                <a href="/Riga/cart.php" class="btn" style="margin-left:12px">Go to cart</a>
            </div>
        </div>
    </nav>

    <header class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content container">
            <h1 class="hero-title">Discover Your Unique Style</h1>
            <p class="hero-sub">Elegance and simplicity for the modern woman. Shop our latest collection today.</p>
            <a href="products.php" class="btn btn-primary">Shop New Arrivals</a>
        </div>
    </header>

    <main class="container">
        <section id="products" class="products">
            <h2 class="section-title">Featured</h2>
            <div class="product-grid">
                <?php foreach ($products as $p):
                    $titleLower = strtolower($p['title'] ?? '');
                    $img = $p['image'] ?? '';
                    if (strpos($titleLower, 'silk') !== false || strpos($titleLower, 'blouse') !== false) {
                        $img = 'photo/blouse.png';
                    } elseif (strpos($titleLower, 'tailored') !== false || strpos($titleLower, 'blazer') !== false) {
                        $img = 'photo/tailored.png';
                    }
                    if ($img && !preg_match('#^https?://#i', $img)) {
                        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
                        if ($base === '') {
                            $img = '/' . ltrim($img, '/');
                        } else {
                            if (strpos($img, 'photo/') === 0) {
                                $img = $base . '/' . $img;
                            } elseif (strpos($img, '/') === 0) {
                                $img = $img;
                            } else {
                                $img = $base . '/photo/' . ltrim($img, '/');
                            }
                        }
                    }
                ?>
                    <article class="product">
                        <div class="product-media" style="background-image: url('<?php echo htmlspecialchars($img) ?>')"></div>
                        <h3 class="product-title"><?php echo htmlspecialchars($p['title']) ?></h3>
                        <p class="product-desc"><?php echo htmlspecialchars($p['description']) ?></p>
                        <div class="product-footer">
                            <span class="price">$<?php echo number_format($p['price'], 2) ?></span>
                            <a class="btn btn-sm" href="products.php">View</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <div class="footer-left">
                <h4>Quick Links</h4>
            </div>
            <div class="footer-right">
                <div class="social">
                    <!-- small svg icons -->
                    <a aria-label="Facebook" href="#"><svg width="18" height="18" viewBox="0 0 24 24" fill="#111"><path d="M22 12.07C22 6.48 17.52 2 11.93 2S2 6.48 2 12.07C2 17.09 5.66 21.22 10.39 21.91v-6.91H7.9v-2.93h2.49V9.41c0-2.46 1.46-3.82 3.7-3.82 1.07 0 2.19.19 2.19.19v2.41h-1.23c-1.21 0-1.59.75-1.59 1.52v1.82h2.71l-.43 2.93h-2.28V21.9C18.34 21.22 22 17.09 22 12.07z"/></svg></a>
                    <a aria-label="Instagram" href="#"><svg width="18" height="18" viewBox="0 0 24 24" fill="#111"><path d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm5 6.5A4.5 4.5 0 1016.5 13 4.5 4.5 0 0012 8.5zm0 7.5a3 3 0 113-3 3 3 0 01-3 3zM18.5 6a1 1 0 11-1 1 1 1 0 011-1z"/></svg></a>
                    <a aria-label="LinkedIn" href="#"><svg width="18" height="18" viewBox="0 0 24 24" fill="#111"><path d="M4.98 3.5a2.5 2.5 0 11.02 0zM3 8.98h4v12H3zM9 8.98h3.8v1.64h.05c.53-1 1.83-2.05 3.77-2.05 4.03 0 4.78 2.65 4.78 6.09v6.32h-4v-5.6c0-1.33-.02-3.05-1.86-3.05-1.86 0-2.15 1.45-2.15 2.95v5.7H9z"/></svg></a>
                </div>
            </div>
        </div>
        <div class="container footer-bottom">
        </div>
    </footer>
</body>
</html>
