<?php
require_once 'session_config.php';
session_start();

// Log the logout event if security logging is available
if (isset($_SESSION['user_email']) && function_exists('logSecurityEvent')) {
    require_once 'security.php';
    logSecurityEvent('LOGOUT', "User logged out: " . $_SESSION['user_email']);
}

// Clear all session variables
$_SESSION = array();

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: index.php');
exit;
?>
