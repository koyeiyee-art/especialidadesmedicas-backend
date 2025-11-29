<?php
session_start();

// Parse Railway MySQL URL
$mysql_url = getenv('MYSQL_PUBLIC_URL') ?: 'mysql://root:IvXqoRMzqDxfgYmnKmXsQauIgURfoGwH@interchange.proxy.rlwy.net:33933/railway';
$url_parts = parse_url($mysql_url);

define('DB_HOST', $url_parts['host'] . ':' . $url_parts['port']);
define('DB_USER', $url_parts['user']);
define('DB_PASS', $url_parts['pass']);
define('DB_NAME', ltrim($url_parts['path'], '/'));

// Admin credentials - Change these!
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123'); // Use password_hash() for production!

// Create database connection
function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php');
        exit();
    }
}
?>
