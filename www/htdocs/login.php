<?php
// Configure and start session
require_once 'session_config.php';
session_start();

// Validate HTTP method
$allowed_methods = ['POST'];
if (!in_array($_SERVER['REQUEST_METHOD'], $allowed_methods)) {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: POST');
    exit('Method Not Allowed');
}

require_once 'config.php';
require_once 'security.php';

// Initialize rate limiting
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt'] = time();
}

// Check if user is rate limited (5 attempts in 15 minutes)
if ($_SESSION['login_attempts'] >= 5) {
    $time_elapsed = time() - $_SESSION['last_attempt'];
    if ($time_elapsed < 900) { // 15 minutes
        $minutes_left = ceil((900 - $time_elapsed) / 60);
        header("Location: index.php?error=" . urlencode("Too many failed attempts. Try again in $minutes_left minutes."));
        exit;
    } else {
        // Reset attempts after cooldown period
        $_SESSION['login_attempts'] = 0;
    }
}

// Security checks
$threats = detectThreats($_POST);
if (!empty($threats)) {
    logSecurityEvent('THREAT_DETECTED', implode(', ', $threats));
    header("Location: index.php?error=" . urlencode("Security violation detected."));
    exit;
}

// Log suspicious IP activity
$client_ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if (isIPSuspicious($client_ip)) {
    logSecurityEvent('SUSPICIOUS_IP', "Access from suspicious IP: {$client_ip}");
}

// Validate CSRF token
$csrf_token = $_POST['csrf_token'] ?? '';
if (!validateCSRFToken($csrf_token)) {
    $_SESSION['login_attempts']++;
    $_SESSION['last_attempt'] = time();
    header("Location: index.php?error=" . urlencode("Security violation: Invalid request token"));
    exit;
}

// Sanitize and validate input
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$password = trim($_POST['password'] ?? '');

// Input validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['login_attempts']++;
    $_SESSION['last_attempt'] = time();
    header("Location: index.php?error=" . urlencode("Invalid email format"));
    exit;
}

if (strlen($password) < 6) {
    $_SESSION['login_attempts']++;
    $_SESSION['last_attempt'] = time();
    header("Location: index.php?error=" . urlencode("Password must be at least 6 characters"));
    exit;
}

// Authenticate user
$valid_users = [
    'admin@projectdelta.local' => 'admin123',
    'user@projectdelta.local' => 'admin123'
];

if (isset($valid_users[$email]) && $valid_users[$email] === $password) {
    // Successful login
    $_SESSION['login_attempts'] = 0;
    $_SESSION['user_id'] = 1;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_name'] = ($email === 'admin@projectdelta.local') ? 'Administrator' : 'User';
    
    // Regenerate CSRF token for security
    regenerateCSRFToken();
    
    // Log successful login
    logSecurityEvent('LOGIN_SUCCESS', "User logged in: {$email}");
    
    header("Location: dashboard.php");
    exit;
} else {
    // Failed login
    $_SESSION['login_attempts']++;
    $_SESSION['last_attempt'] = time();
    
    logSecurityEvent('LOGIN_FAILED', "Failed login attempt for: {$email}");
    
    header("Location: index.php?error=" . urlencode("Incorrect email or password"));
    exit;
}
?>
