<?php
session_start();

// Database configuration using Railway environment variables
$host = getenv('MYSQLHOST') ?: 'interchange.proxy.rlwy.net';
$port = getenv('MYSQLPORT') ?: '33933';
$database = getenv('MYSQLDATABASE') ?: 'especialidades';
$username = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: 'IvXqoRMzqDxfgYmnKmXsQauIgURfoGwH';

// Create connection
$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// Admin credentials (you can change these)
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123'); // CHANGE THIS TO A SECURE PASSWORD

// Function to check if user is logged in
function requireLogin() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: index.php');
        exit;
    }
}

// Function to get database connection
function getDB() {
    global $conn;
    return $conn;
}
?>
