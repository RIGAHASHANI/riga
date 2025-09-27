<?php
require_once __DIR__ . '/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = find_user_by_email($email);
    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            background: #f4f6f8;
            font-family: Arial, sans-serif;
        }
        .login-container {
            width: 350px;
            margin: 80px auto;
            padding: 30px 25px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px 10px;
            margin: 8px 0 18px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .login-container button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .login-container button:hover {
            background: #0056b3;
        }
        .error {
            color: #d8000c;
            background: #ffd2d2;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            text-align: center;
        }
        .register-link {
            text-align: center;
            margin-top: 18px;
        }
        .register-link form button {
            background: none;
            border: none;
            color: #000;
            font-weight: bold;
            cursor: pointer;
            padding: 0;
            font-size: 15px;
        }
        .register-link form button strong {
            font-weight: bold;
        }
        .register-link form button:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="register-link">
            <form action="signup.php" method="get">
                <button type="submit">
                    Don't have an account? <strong>Create one here</strong>
                </button>
            </form>
        </div>
    </div>
</body>
</html>