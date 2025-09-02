<?php
// Configuración de la base de datos
$host = $_ENV['DB_HOST'] ?? 'db';
$dbname = $_ENV['DB_NAME'] ?? 'projecto';
$username = $_ENV['DB_USER'] ?? 'webapp';
$password = $_ENV['DB_PASSWORD'] ?? 'webpassword';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Función para verificar si el usuario está logueado
function isLoggedIn() {
    session_start();
    return isset($_SESSION['user_id']);
}

// Función para redirigir si no está logueado
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php');
        exit;
    }
}
?>