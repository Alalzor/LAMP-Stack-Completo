<?php
/**
 * System Constants
 * Centralized configuration values for better maintainability
 */

// Security Constants
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes in seconds
define('SESSION_LIFETIME', 3600);   // 1 hour in seconds
define('CSRF_TOKEN_LIFETIME', 1800); // 30 minutes in seconds

// Application Constants
define('APP_NAME', 'Project Delta');
define('APP_VERSION', '1.2.0');
define('DEFAULT_TIMEZONE', 'Europe/Madrid');

// Database Constants
define('DB_CHARSET', 'utf8mb4');
define('USER_STATUS_ACTIVE', 'active');
define('USER_STATUS_INACTIVE', 'inactive');

// UI Constants
define('DEFAULT_ITEMS_PER_PAGE', 10);
define('MAX_ITEMS_PER_PAGE', 100);

// File Upload Constants
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Error Messages
define('ERROR_INVALID_CREDENTIALS', 'Incorrect email or password');
define('ERROR_ACCOUNT_LOCKED', 'Account temporarily locked due to multiple failed attempts');
define('ERROR_INVALID_TOKEN', 'Security violation: Invalid request token');
define('ERROR_UNAUTHORIZED', 'Unauthorized access');

// Success Messages
define('SUCCESS_LOGIN', 'Login successful');
define('SUCCESS_LOGOUT', 'Logged out successfully');
define('SUCCESS_UPDATE', 'Information updated successfully');

// Set default timezone
date_default_timezone_set(DEFAULT_TIMEZONE);
?>
