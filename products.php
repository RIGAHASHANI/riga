<?php
require_once __DIR__ . '/db.php';

$pdo = get_pdo();
// Try to fetch products from DB; if none exist, fall back to example data
$products = [];
try {
    $stmt = $pdo->query('SELECT id, title, description, price, image FROM products ORDER BY created_at DESC');
    $products = $stmt->fetchAll();
} catch (Exception $e) {
    $products = [];
}

if (empty($products)) {
    $products = [
        ['title' => 'Elegant Linen Maxi Dress','price'=>129.00,'image'=>'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?auto=format&fit=crop&w=1200&q=80'],
    ['title' => 'Delicate Silk Pleated Blouse','price'=>89.50,'image'=>'photo/blouse.png'],
        ['title' => 'Flowing A-Line Midi Skirt','price'=>75.00,'image'=>'https://images.unsplash.com/photo-1503342452485-86d61254f4b2?auto=format&fit=crop&w=1200&q=80'],
    ['title' => 'Tailored High-Waisted Trousers','price'=>99.99,'image'=>'photo/tailored.png'],
    ['title' => 'Classic Lightweight Trench Coat','price'=>180.00,'image'=>'photo/tailored.png'],
        ['title' => 'Soft Knit Cashmere Sweater','price'=>150.00,'image'=>'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?auto=format&fit=crop&w=1200&q=80'],
    ['title' => 'Ribbed Scoop-Neck Top','price'=>45.00,'image'=>'photo/tailored.png'],
        ['title' => 'Elegant Leather Ankle Boots','price'=>210.00,'image'=>'https://images.unsplash.com/photo-1519741496631-9b1bf8f9b3d6?auto=format&fit=crop&w=1200&q=80'],
    ];
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Our Latest Collection</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Page-specific styles */
        .nav-inner { display:flex; align-items:center; justify-content:space-between; }
        .nav-left { display:flex; align-items:center; gap:12px; }
        .nav-center { display:flex; gap:18px; align-items:center; justify-content:center; flex:1; }
        .nav-right { margin-left:auto; }
        .checkout { border:1px solid #e6e6f2; padding:8px 12px; border-radius:999px; background:transparent; color:#333; }
        .products-hero { padding:48px 0 18px; text-align:center; }
        .section-title { font-family: 'Playfair Display', Georgia, serif; font-size:32px; margin-bottom:20px; }
        .product-grid { display:grid; grid-template-columns: repeat(4, 1fr); gap:20px; }
        @media (max-width: 1000px) { .product-grid { grid-template-columns: repeat(3,1fr); } }
        @media (max-width: 720px) { .product-grid { grid-template-columns: repeat(2,1fr); } .container { padding:16px } }
        @media (max-width: 420px) { .product-grid { grid-template-columns: 1fr; } }
        .card-product { background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 6px 18px rgba(0,0,0,0.04); padding:12px; display:flex; flex-direction:column; }
        .card-media { width:100%; height:200px; background-size:cover; background-position:center; border-radius:8px; }
        .card-title { font-weight:600; margin:12px 0 6px; }
        .card-price { color:#6f42c1; font-weight:600; margin-bottom:10px; }
        .card-actions { margin-top:auto; display:flex; justify-content:space-between; align-items:center; }
        .add-btn { background:#C7B5F5; color:#fff; padding:10px 14px; border-radius:999px; border:none; cursor:pointer; }
    </style>
</head>
<body>
    <nav class="nav" style="background:#fff; border-bottom:1px solid #f0ecec;">
        <div class="container nav-inner">
            <div class="nav-left">
                <a href="home.php" class="logo" aria-label="logo">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2.5l2.6 5.26L20.5 9.5l-4 3.9.95 5.54L12 16.9 6.55 18.94 7.5 13.4 3.5 9.5l5.9-.74L12 2.5z" fill="#6B5B95"/></svg>
                </a>
            </div>
            <div class="nav-center">
                <a href="home.php" class="nav-link">Home</a>
                <a href="products.php" class="nav-link active">Products</a>
            </div>
            <div class="nav-right">
            </div>
        </div>
    </nav>

    <header class="products-hero">
        <div class="container">
            <h1 class="section-title">Our Latest Collection</h1>
            <div style="text-align:right; margin-bottom:12px">
                <a href="add_product.php" class="btn">Add Item</a>
                <a href="/Riga/cart.php" class="btn" style="margin-left:8px">Go to cart</a>
            </div>
            <p style="color:#666; max-width:720px; margin:0 auto;">Minimal, elegant pieces with timeless silhouettes. Browse our curated selection below.</p>
            
            <!-- Server-side for-loop demo: renders up to 3 products using a PHP for loop -->
            <?php if (!empty($products)): ?>
                <h3 style="margin-top:20px; font-size:18px;">For-loop demo (server-side)</h3>
                <div class="product-grid" style="margin-bottom:18px;">
                    <?php
                        $limit = min(3, count($products));
                        for ($i = 0; $i < $limit; $i++):
                            $p = $products[$i];
                            $titleLowerDemo = strtolower($p['title'] ?? '');
                            $imgDemo = $p['image'] ?? '';
                            if (strpos($titleLowerDemo, 'silk') !== false || strpos($titleLowerDemo, 'blouse') !== false) {
                                $imgDemo = 'photo/blouse.png';
                            } elseif (strpos($titleLowerDemo, 'tailored') !== false || strpos($titleLowerDemo, 'blazer') !== false) {
                                $imgDemo = 'photo/tailored.png';
                            }
                            if ($imgDemo && !preg_match('#^https?://#i', $imgDemo)) {
                                $baseDemo = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
                                if ($baseDemo === '') {
                                    $imgDemo = '/' . ltrim($imgDemo, '/');
                                } else {
                                    if (strpos($imgDemo, 'photo/') === 0) {
                                        $imgDemo = $baseDemo . '/' . $imgDemo;
                                    } elseif (strpos($imgDemo, '/') === 0) {
                                        $imgDemo = $imgDemo;
                                    } else {
                                        $imgDemo = $baseDemo . '/photo/' . ltrim($imgDemo, '/');
                                    }
                                }
                            }
                    ?>
                        <div class="card-product">
                            <div class="card-media" style="background-image: url('<?php echo htmlspecialchars($imgDemo) ?>')"></div>
                            <div class="card-body">
                                <div class="card-title"><?php echo htmlspecialchars($p['title']) ?></div>
                                <div class="card-price">$<?php echo number_format($p['price'],2) ?></div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <main class="container" style="padding-bottom:48px">
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
                <div class="card-product">
                    <div class="card-media" style="background-image: url('<?php echo htmlspecialchars($img) ?>')"></div>
                    <div class="card-body">
                        <div class="card-title"><?php echo htmlspecialchars($p['title']) ?></div>
                        <div class="card-price">$<?php echo number_format($p['price'],2) ?></div>
                        <div class="card-actions">
                            <?php
                                $buyUrl = 'checkout.php?title=' . urlencode($p['title']) . '&price=' . urlencode($p['price']) . '&image=' . urlencode($img);
                            ?>
                            <a class="add-btn" href="<?php echo $buyUrl ?>">Buy</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <div class="footer-left">
                <h4>Quick Links</h4>
            </div>
            <div class="footer-right">
                <div class="social">
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
