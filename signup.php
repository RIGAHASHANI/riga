<?php
require_once __DIR__ . '/db.php';

$error = '';
if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $pdo = get_pdo();
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email OR name = :name LIMIT 1');
        $stmt->execute([':email' => $email, ':name' => $username]);
        if ($stmt->fetch()) {
            $error = 'Username or email already registered.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            if (create_user($email, $hashed, $username)) {
                header('Location: login.php?registered=1');
                exit;
            }
            $error = 'Registration failed. Please try again.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="container" style="max-width:420px; margin:60px auto;">
        <h1>Create account</h1>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required value="<?php echo htmlspecialchars($_POST['username'] ?? '') ?>">
            <input type="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($_POST['email'] ?? '') ?>">
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <p class="link">Already have an account? <a href="login.php">Log in</a></p>
    </main>
</body>
</html>