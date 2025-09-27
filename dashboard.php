<?php
// Simple dashboard for managing products (CRUD) using PDO
require_once __DIR__ . '/db.php';
require_login();

$pdo = get_pdo();

// Handle create/update/delete via POST
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $title = trim($_POST['title'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $image = trim($_POST['image'] ?? '');
        if ($title !== '') {
            $stmt = $pdo->prepare('INSERT INTO products (title, description, price, image) VALUES (:t, :d, :p, :i)');
            $stmt->execute([':t'=>$title, ':d'=>$desc, ':p'=>$price, ':i'=>$image]);
            $message = 'Product created.';
        }
    } elseif ($action === 'update') {
        $id = intval($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $image = trim($_POST['image'] ?? '');
        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE products SET title=:t, description=:d, price=:p, image=:i WHERE id=:id');
            $stmt->execute([':t'=>$title, ':d'=>$desc, ':p'=>$price, ':i'=>$image, ':id'=>$id]);
            $message = 'Product updated.';
        }
    } elseif ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare('DELETE FROM products WHERE id=:id');
            $stmt->execute([':id'=>$id]);
            $message = 'Product deleted.';
        }
    }
}

// Fetch products for display
$products = $pdo->query('SELECT * FROM products ORDER BY created_at DESC')->fetchAll();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard - Products</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .dashboard { max-width: 1000px; margin: 36px auto; }
        .grid { display: grid; grid-template-columns: 1fr 360px; gap: 18px; }
        .card { background:#fff; padding:16px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.04); }
        .product-row { display:flex; gap:12px; align-items:center; }
        .product-row img { width:64px; height:64px; object-fit:cover; border-radius:6px; }
        .small { font-size:13px; color:#666; }
    </style>
</head>
<body>
    <div class="container dashboard">
        <h1>Products Dashboard</h1>
        <?php if ($message): ?><div class="success"><?php echo htmlspecialchars($message) ?></div><?php endif; ?>
        <div class="grid">
            <div>
                <div class="card">
                    <h3>All products</h3>
                    <?php if (empty($products)): ?>
                        <p class="small">No products yet.</p>
                    <?php else: ?>
                        <?php foreach ($products as $p):
                            $img = $p['image'] ?? '';
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
                            <div class="product-row">
                                <img src="<?php echo htmlspecialchars($img) ?>" alt="">
                                <div style="flex:1">
                                    <strong><?php echo htmlspecialchars($p['title']) ?></strong>
                                    <div class="small">$<?php echo number_format($p['price'],2) ?></div>
                                </div>
                                <form method="post" style="margin:0 6px">
                                    <input type="hidden" name="id" value="<?php echo $p['id'] ?>">
                                    <button name="action" value="delete" class="btn">Delete</button>
                                </form>
                                <form method="get" action="#edit" style="margin:0 6px">
                                    <input type="hidden" name="edit" value="<?php echo $p['id'] ?>">
                                    <button class="btn secondary">Edit</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <div class="card">
                    <h3 id="edit">Create / Edit</h3>
                    <?php
                    $edit = null;
                    if (!empty($_GET['edit'])) {
                        $id = intval($_GET['edit']);
                        $edit = $pdo->prepare('SELECT * FROM products WHERE id=:id');
                        $edit->execute([':id'=>$id]);
                        $edit = $edit->fetch();
                    }
                    ?>
                    <form method="post">
                        <input type="hidden" name="id" value="<?php echo $edit['id'] ?? '' ?>">
                        <label>Title</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($edit['title'] ?? '') ?>" required>
                        <label>Description</label>
                        <textarea name="description"><?php echo htmlspecialchars($edit['description'] ?? '') ?></textarea>
                        <label>Price</label>
                        <input type="text" name="price" value="<?php echo htmlspecialchars($edit['price'] ?? '') ?>">
                        <label>Image URL</label>
                        <input type="text" name="image" value="<?php echo htmlspecialchars($edit['image'] ?? '') ?>">
                        <div style="margin-top:12px">
                            <button type="submit" name="action" value="create" class="btn">Create</button>
                            <button type="submit" name="action" value="update" class="btn secondary">Update</button>
                        </div>
                    </form>
                </div>

                <div class="card" style="margin-top:12px">
                    <h4>Live preview</h4>
                    <div id="slider" style="height:220px; display:flex; gap:8px; align-items:center; overflow:hidden">
                        <?php foreach ($products as $p):
                            $img2 = $p['image'] ?? '';
                            if ($img2 && !preg_match('#^https?://#i', $img2)) {
                                $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
                                if ($base === '') {
                                    $img2 = '/' . ltrim($img2, '/');
                                } else {
                                    if (strpos($img2, 'photo/') === 0) {
                                        $img2 = $base . '/' . $img2;
                                    } elseif (strpos($img2, '/') === 0) {
                                        $img2 = $img2;
                                    } else {
                                        $img2 = $base . '/photo/' . ltrim($img2, '/');
                                    }
                                }
                            }
                        ?>
                            <div class="card" style="min-width:180px; padding:8px">
                                <img src="<?php echo htmlspecialchars($img2) ?>" style="width:100%; height:110px; object-fit:cover; border-radius:6px;">
                                <div class="small"><?php echo htmlspecialchars($p['title']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Simple slider and event examples
    (function(){
        const slider = document.getElementById('slider');
        let offset = 0;
        // rotate items every 3s
        setInterval(() => {
            offset = (offset + 1) % Math.max(1, slider.children.length);
            slider.style.transform = `translateX(${ -offset * 188 }px)`;
        }, 3000);

        // example event: click to log product title
        Array.from(slider.querySelectorAll('.card')).forEach((el) => {
            el.addEventListener('click', () => console.log('Clicked', el.querySelector('.small').textContent));
        });
    })();
    </script>
</body>
</html>
