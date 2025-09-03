<?php
// Database configuration with improved error handling
$host = $_ENV['DB_HOST'] ?? 'db';
$dbname = $_ENV['DB_NAME'] ?? 'empresa';
$username = $_ENV['DB_USER'] ?? 'webapp';
$password = $_ENV['DB_PASSWORD'] ?? 'WebAppSecure123';

$pdo = null;
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_FOUND_ROWS => true,
        PDO::ATTR_PERSISTENT => false // Disable persistent connections for better resource management
    ]);
} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    $pdo = null;
}

// Core utility functions
function isLoggedIn() {
    return (session_status() === PHP_SESSION_ACTIVE || session_start()) && isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php');
        exit;
    }
}

function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Optimized database functions
function getUserByEmail($email) {
    global $pdo;
    if (!$pdo) return null;
    
    try {
        $stmt = $pdo->prepare("SELECT id, email, password, first_name, last_name, full_name, job_title, department, employee_id, status FROM users WHERE email = ? AND status = 'active' LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching user: " . $e->getMessage());
        return null;
    }
}

function getUserById($id) {
    global $pdo;
    if (!$pdo) return null;
    
    try {
        $stmt = $pdo->prepare("SELECT id, email, first_name, last_name, full_name, job_title, department, employee_id, status FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching user by ID: " . $e->getMessage());
        return null;
    }
}

function updateLastLogin($userId) {
    global $pdo;
    if (!$pdo) return false;
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        return $stmt->execute([$userId]);
    } catch (PDOException $e) {
        error_log("Error updating last login: " . $e->getMessage());
        return false;
    }
}

// Optimized dashboard statistics with single queries
function getDashboardStats() {
    global $pdo;
    if (!$pdo) return null;
    
    try {
        $stats = [];
        
        // Single optimized query for user statistics
        $stmt = $pdo->query("SELECT 
            COUNT(*) as total_users,
            SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_users,
            SUM(CASE WHEN hire_date >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND status = 'active' THEN 1 ELSE 0 END) as new_hires
            FROM users");
        $stats['users'] = $stmt->fetch();
        
        // Optimized department query
        $stmt = $pdo->query("SELECT 
            d.name,
            COUNT(u.id) as employee_count,
            d.budget
            FROM departments d 
            LEFT JOIN users u ON u.department = d.name AND u.status = 'active'
            GROUP BY d.id, d.name, d.budget
            ORDER BY employee_count DESC
            LIMIT 10");
        $stats['departments'] = $stmt->fetchAll();
        
        // Single query for project statistics
        $stmt = $pdo->query("SELECT 
            COUNT(*) as total_projects,
            SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_projects,
            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_projects,
            ROUND(AVG(progress_percentage), 1) as avg_progress,
            SUM(budget) as total_budget,
            SUM(spent_budget) as total_spent
            FROM projects");
        $stats['projects'] = $stmt->fetch();
        
        return $stats;
    } catch (PDOException $e) {
        error_log("Error fetching dashboard stats: " . $e->getMessage());
        return null;
    }
}

// Optimized recent activities function
function getRecentActivities($limit = 10) {
    global $pdo;
    if (!$pdo) return [];
    
    try {
        $stmt = $pdo->prepare("SELECT 
            full_name,
            job_title,
            last_login,
            status,
            'User Login' as activity_type
            FROM users 
            WHERE last_login IS NOT NULL AND status = 'active'
            ORDER BY last_login DESC 
            LIMIT ?");
        $stmt->execute([(int)$limit]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching recent activities: " . $e->getMessage());
        return [];
    }
}
?>