<?php
/**
 * Security monitoring and logging functions
 */

/**
 * Log security events
 * @param string $event_type
 * @param string $details
 * @param string $ip_address
 */
function logSecurityEvent($event_type, $details, $ip_address = null) {
    if ($ip_address === null) {
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    
    $log_entry = "[{$timestamp}] [{$event_type}] IP: {$ip_address} | Details: {$details} | User-Agent: {$user_agent}" . PHP_EOL;
    
    // Log to file (in production, consider using a proper logging system)
    error_log($log_entry, 3, '/var/log/apache2/security.log');
}

/**
 * Detect potential security threats
 * @param array $request_data
 * @return array
 */
function detectThreats($request_data) {
    $threats = [];
    
    // Check for SQL injection patterns
    $sql_patterns = [
        '/union\s+select/i',
        '/drop\s+table/i',
        '/insert\s+into/i',
        '/delete\s+from/i',
        '/update\s+.*set/i',
        '/script.*src/i',
        '/<script/i',
        '/javascript:/i'
    ];
    
    foreach ($request_data as $key => $value) {
        if (is_string($value)) {
            foreach ($sql_patterns as $pattern) {
                if (preg_match($pattern, $value)) {
                    $threats[] = "Potential {$key} injection detected: " . substr($value, 0, 100);
                    logSecurityEvent('THREAT_DETECTED', "Injection attempt in {$key}");
                }
            }
        }
    }
    
    return $threats;
}

/**
 * Check for suspicious IP behavior
 * @param string $ip_address
 * @return bool
 */
function isIPSuspicious($ip_address) {
    // In a real application, you might check against:
    // - Known blacklists
    // - Rate limiting by IP
    // - Geographic restrictions
    // - Tor exit nodes
    
    // For now, just check basic patterns
    $suspicious_patterns = [
        '/^10\./',      // Private networks trying external access
        '/^172\.16/',   // Private networks
        '/^192\.168/',  // Private networks (if accessed externally)
    ];
    
    foreach ($suspicious_patterns as $pattern) {
        if (preg_match($pattern, $ip_address)) {
            return true;
        }
    }
    
    return false;
}

/**
 * Validate file upload for security
 * @param array $file
 * @return array
 */
function validateFileUpload($file) {
    $errors = [];
    
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return ['No file uploaded'];
    }
    
    // Check file size (2MB max)
    if ($file['size'] > 2097152) {
        $errors[] = 'File too large (max 2MB)';
    }
    
    // Check file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $detected_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($detected_type, $allowed_types)) {
        $errors[] = 'File type not allowed';
        logSecurityEvent('ILLEGAL_UPLOAD', "Attempt to upload {$detected_type}");
    }
    
    // Check for embedded scripts in images
    if (strpos($detected_type, 'image/') === 0) {
        $content = file_get_contents($file['tmp_name']);
        if (preg_match('/<script|javascript:|php|<?php/i', $content)) {
            $errors[] = 'Malicious content detected in image';
            logSecurityEvent('MALICIOUS_UPLOAD', 'Script content in image file');
        }
    }
    
    return $errors;
}
?>
