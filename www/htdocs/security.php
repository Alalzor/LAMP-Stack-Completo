<?php
/**
 * Security functions for CSRF protection and threat detection
 */

/**
 * Generate or retrieve existing CSRF token
 * @return string
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token']) || empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token
 * @param string $token
 * @return bool
 */
function validateCSRFToken($token) {
    // Check if token exists in session
    if (!isset($_SESSION['csrf_token']) || empty($_SESSION['csrf_token'])) {
        logSecurityEvent('CSRF_ERROR', 'No CSRF token in session');
        return false;
    }
    
    // Check if token matches (timing-safe comparison)
    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        logSecurityEvent('CSRF_VIOLATION', 'Invalid CSRF token provided');
        return false;
    }
    
    // Check token age (max 1 hour)
    if (isset($_SESSION['csrf_token_time'])) {
        $token_age = time() - $_SESSION['csrf_token_time'];
        if ($token_age > 3600) { // 1 hour
            logSecurityEvent('CSRF_EXPIRED', 'CSRF token expired');
            unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
            return false;
        }
    }
    
    return true;
}

/**
 * Regenerate CSRF token (call after successful login)
 */
function regenerateCSRFToken() {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['csrf_token_time'] = time();
}

/**
 * Log security events
 * @param string $event_type
 * @param string $details
 */
function logSecurityEvent($event_type, $details) {
    $timestamp = date('Y-m-d H:i:s');
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    
    $log_entry = "[{$timestamp}] [{$event_type}] IP: {$ip_address} | Details: {$details} | User-Agent: {$user_agent}" . PHP_EOL;
    
    // Log to security file
    error_log($log_entry, 3, '/var/log/apache2/security.log');
}

/**
 * Detect potential security threats in form data
 * @param array $request_data
 * @return array
 */
function detectThreats($request_data) {
    $threats = [];
    
    // Common injection patterns
    $patterns = [
        'sql' => ['/union\s+select/i', '/drop\s+table/i', '/insert\s+into/i', '/delete\s+from/i'],
        'xss' => ['/script.*src/i', '/<script/i', '/javascript:/i', '/on\w+\s*=/i'],
        'path' => ['/\.\.\//i', '/etc\/passwd/i', '/proc\/self/i']
    ];
    
    foreach ($request_data as $key => $value) {
        if (is_string($value)) {
            foreach ($patterns as $type => $pattern_list) {
                foreach ($pattern_list as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $threats[] = "Potential {$type} injection in {$key}: " . substr($value, 0, 50) . '...';
                        logSecurityEvent('THREAT_DETECTED', "Injection attempt in {$key}");
                        break 2; // Exit both loops for this value
                    }
                }
            }
        }
    }
    
    return $threats;
}

/**
 * Basic IP monitoring (simplified version)
 * @param string $ip_address
 * @return bool
 */
function isIPSuspicious($ip_address) {
    // For development purposes, just log the access
    // In production, implement proper IP filtering
    if ($ip_address !== '127.0.0.1' && !preg_match('/^172\.20\./', $ip_address)) {
        logSecurityEvent('EXTERNAL_ACCESS', "Access from external IP: {$ip_address}");
    }
    
    return false; // Don't block any IPs for now
}
?>
