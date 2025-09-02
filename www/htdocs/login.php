<?php
// Validar método HTTP permitido
$allowed_methods = ['GET', 'POST', 'HEAD'];
if (!in_array($_SERVER['REQUEST_METHOD'], $allowed_methods)) {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: ' . implode(', ', $allowed_methods));
    exit('Method Not Allowed');
}

session_start();
require_once 'config.php';
require_once 'security.php';

// Check for threats in request data
$threats = detectThreats($_POST);
if (!empty($threats)) {
    logSecurityEvent('THREAT_DETECTED', implode(', ', $threats));
    header("Location: index.php?error=" . urlencode("Security violation detected."));
    exit;
}

// Check for suspicious IP
$client_ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if (isIPSuspicious($client_ip)) {
    logSecurityEvent('SUSPICIOUS_IP', "Access from suspicious IP: {$client_ip}");
}

// Basic rate limiting
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt'] = time();
}

// Check if blocked (maximum 5 attempts in 15 minutes)
if ($_SESSION['login_attempts'] >= 5) {
    $time_elapsed = time() - $_SESSION['last_attempt'];
    if ($time_elapsed < 900) { // 15 minutes
        $minutes_left = ceil((900 - $time_elapsed) / 60);
        header("Location: index.php?error=" . urlencode("Too many failed attempts. Try again in $minutes_left minutes."));
        exit;
    } else {
        // Reset attempts after 15 minutes
        $_SESSION['login_attempts'] = 0;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar credenciales directamente
    
    // Sanitize and validate input
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password'] ?? '');
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt'] = time();
        header("Location: index.php?error=" . urlencode("Invalid email format"));
        exit;
    }
    
    // Validate password length
    if (strlen($password) < 6) {
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt'] = time();
        header("Location: index.php?error=" . urlencode("Password must be at least 6 characters"));
        exit;
    }
    
    if (!empty($email) && !empty($password)) {
        // Usuarios de prueba hardcodeados (simple)
        $valid_users = [
            'admin@projectdelta.local' => 'admin123',
            'user@projectdelta.local' => 'admin123'
        ];
        
        if (isset($valid_users[$email]) && $valid_users[$email] === $password) {
            // Login exitoso
            $_SESSION['login_attempts'] = 0;
            $_SESSION['user_id'] = 1;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = 'Admin User';
            
            // Redirigir al dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            // Credenciales incorrectas
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt'] = time();
            header("Location: index.php?error=" . urlencode("Incorrect email or password"));
            exit;
        }
    } else {
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt'] = time();
        header("Location: index.php?error=" . urlencode("Please fill in all fields"));
        exit;
    }
}
?>
