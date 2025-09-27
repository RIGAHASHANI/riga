<?php
// db.php - PDO helper and simple user helpers
require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) session_start();

function get_pdo() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
        $opts = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $opts);
        } catch (PDOException $e) {
            // In development, show error. In production, handle gracefully.
            die('Database connection failed: ' . $e->getMessage());
        }
    }
    return $pdo;
}

function find_user_by_email($email) {
    $pdo = get_pdo();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    return $stmt->fetch();
}

function create_user($email, $passwordHash, $name = null) {
    $pdo = get_pdo();
    $stmt = $pdo->prepare('INSERT INTO users (email, password, name) VALUES (:email, :password, :name)');
    return $stmt->execute([':email' => $email, ':password' => $passwordHash, ':name' => $name]);
}

function require_login() {
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

?>