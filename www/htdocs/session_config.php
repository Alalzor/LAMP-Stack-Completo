<?php
/**
 * Session configuration - Must be included BEFORE session_start()
 */

// Configure session settings for security
ini_set('session.cookie_httponly', 1);   // Prevent XSS attacks
ini_set('session.use_strict_mode', 1);   // Prevent session fixation
ini_set('session.gc_maxlifetime', 3600); // 1 hour session lifetime

// Only set secure cookies if HTTPS is available
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
    ini_set('session.cookie_samesite', 'Strict');
}
?>
