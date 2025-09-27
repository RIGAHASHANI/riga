<?php
require_once __DIR__ . '/db.php';

$pdo = get_pdo();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $image = trim($_POST['image'] ?? '');

    // basic validation
    if ($title === '') {
        $message = 'Title is required.';
    } else {
        $priceVal = floatval(str_replace(',', '.', $price));
        $stmt = $pdo->prepare('INSERT INTO products (title, description, price, image) VALUES (:t, :d, :p, :i)');
        $stmt->execute([':t'=>$title, ':d'=>$description, ':p'=>$priceVal, ':i'=>$image]);
        header('Location: products.php?added=1');
        exit;
    }
}

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .form-card { max-width:680px; margin:40px auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.04); }
        label { display:block; margin-top:10px; font-weight:600; }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <h2>Add a new product</h2>
            <?php if ($message): ?><div class="error"><?php echo htmlspecialchars($message) ?></div><?php endif; ?>
            <form method="post">
                <label>Title</label>
                <input type="text" name="title" required>
                <label>Description</label>
                <textarea name="description"></textarea>
                <label>Price</label>
                <input type="text" name="price" placeholder="99.00">
                <label>Image (URL or filename in photo/)</label>
                <input type="text" name="image" placeholder="photo/blouse.png or https://...">
                <div style="margin-top:14px">
                    <button type="submit" class="btn">Add product</button>
                    <a href="products.php" class="link" style="margin-left:12px">Back to products</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
