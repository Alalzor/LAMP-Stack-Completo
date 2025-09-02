<?php
// Database configuration
$host = $_ENV['DB_HOST'] ?? 'db';
$dbname = $_ENV['DB_NAME'] ?? 'projecto';
$username = $_ENV['DB_USER'] ?? 'webapp';
$password = $_ENV['DB_PASSWORD'] ?? 'WebAppSecure123'; // Use correct password as fallback

$pdo = null; // Initialize as null
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_FOUND_ROWS => true
    ]);
} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    // Don't die - just log the error and continue without database
    $pdo = null;
}

// Function to check if user is logged in
function isLoggedIn() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['user_id']);
}

// Function to redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php');
        exit;
    }
}

// Function to escape HTML output
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>