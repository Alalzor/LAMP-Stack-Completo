<?php
// Optimized login system
require_once 'constants.php';
require_once 'session_config.php';
session_start();

// Validate HTTP method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: POST');
    exit('Method Not Allowed');
}

require_once 'config.php';
require_once 'security.php';

// Rate limiting with optimized logic
if (!isset($_SESSION['login_attempts'], $_SESSION['last_attempt'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt'] = time();
}

// Check rate limiting
if ($_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
    $time_elapsed = time() - $_SESSION['last_attempt'];
    if ($time_elapsed < LOGIN_LOCKOUT_TIME) {
        $minutes_left = ceil((LOGIN_LOCKOUT_TIME - $time_elapsed) / 60);
        header("Location: index.php?error=" . urlencode("Too many failed attempts. Try again in $minutes_left minutes."));
        exit;
    }
    $_SESSION['login_attempts'] = 0; // Reset after cooldown
}

// Optimized security and input validation
$threats = detectThreats($_POST);
if (!empty($threats)) {
    logSecurityEvent('THREAT_DETECTED', implode(', ', $threats));
    $_SESSION['login_attempts']++;
    $_SESSION['last_attempt'] = time();
    header("Location: index.php?error=" . urlencode("Security violation detected."));
    exit;
}

// Validate CSRF token
if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
    $_SESSION['login_attempts']++;
    $_SESSION['last_attempt'] = time();
    header("Location: index.php?error=" . urlencode("Security violation: Invalid request token"));
    exit;
}

// Input validation and sanitization
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$password = trim($_POST['password'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
    $_SESSION['login_attempts']++;
    $_SESSION['last_attempt'] = time();
    $error_msg = !filter_var($email, FILTER_VALIDATE_EMAIL) ? "Invalid email format" : "Password must be at least 6 characters";
    header("Location: index.php?error=" . urlencode($error_msg));
    exit;
}

// Authenticate user
$user = getUserByEmail($email);

if ($user && password_verify($password, $user['password'])) {
    // Authentication successful
    $_SESSION['login_attempts'] = 0;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['full_name'];
    $_SESSION['user_job_title'] = $user['job_title'];
    $_SESSION['user_department'] = $user['department'];
    $_SESSION['user_employee_id'] = $user['employee_id'];
    
    // Update last login time
    updateLastLogin($user['id']);
    
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
