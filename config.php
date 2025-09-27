<?php
// config.php - database configuration for local XAMPP
// Update values if your MySQL credentials differ.
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'riga');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// start session if not already started
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

?>
