<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thank you</title>
    <link rel="stylesheet" href="styles.css">
    <style>.thanks { max-width:560px; margin:80px auto; text-align:center; padding:28px; background:#fff; border-radius:8px; box-shadow:0 8px 26px rgba(0,0,0,0.06); }</style>
</head>
<body>
    <div class="container">
        <div class="thanks">
            <h2>Thanks for your purchase!</h2>
            <p>Your order has been placed. You can view your items in the cart.</p>
            <a href="cart.php" class="btn btn-primary">Go to cart</a>
            <a href="products.php" class="link" style="margin-left:12px">Continue shopping</a>
        </div>
    </div>
</body>
</html>
