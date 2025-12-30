<?php
/**
 * VISHVAVIRAT SECURITY - Configuration File
 *
 * IMPORTANT SECURITY INSTRUCTIONS:
 * 1. Keep this file outside the public web directory if possible
 * 2. Set proper file permissions: chmod 600 config.php
 * 3. Never commit this file to version control
 * 4. Use environment variables on production server
 */

// Prevent direct access
if (!defined('SECURE_ACCESS')) {
    http_response_code(403);
    die('Direct access forbidden');
}

// ============================================
// DATABASE CONFIGURATION
// ============================================

define('DB_HOST', 'localhost');           // Usually 'localhost' on Hostinger
define('DB_NAME', 'your_database_name');  // Replace with your database name
define('DB_USER', 'your_db_username');    // Replace with your database username
define('DB_PASS', 'your_db_password');    // Replace with your database password
define('DB_CHARSET', 'utf8mb4');

// ============================================
// EMAIL CONFIGURATION
// ============================================

define('ADMIN_EMAIL', 'viratagencies770@gmail.com');
define('FROM_EMAIL', 'noreply@viratsecurity.com');  // Must be from your domain on Hostinger
define('FROM_NAME', 'VISHVAVIRAT SECURITY');
define('REPLY_TO_EMAIL', 'viratagencies770@gmail.com');

// ============================================
// SECURITY SETTINGS
// ============================================

define('CSRF_TOKEN_EXPIRY', 3600);        // 1 hour in seconds
define('MAX_FORM_SUBMISSIONS', 3);        // Max submissions per time window
define('RATE_LIMIT_WINDOW', 3600);        // 1 hour in seconds
define('SESSION_LIFETIME', 7200);         // 2 hours

// ============================================
// SITE CONFIGURATION
// ============================================

define('SITE_URL', 'https://www.viratsecurity.com');
define('SITE_NAME', 'VISHVAVIRAT SECURITY & FACILITY INDIA PVT. LTD.');

// ============================================
// ERROR REPORTING (Change to false in production)
// ============================================

define('DEBUG_MODE', false);  // Set to false on production

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/error.log');
}

// ============================================
// TIMEZONE
// ============================================

date_default_timezone_set('Asia/Kolkata');

// ============================================
// ALLOWED ORIGINS (for CORS if needed)
// ============================================

define('ALLOWED_ORIGINS', [
    'https://www.viratsecurity.com',
    'https://viratsecurity.com'
]);

// ============================================
// SERVICES LIST
// ============================================

define('AVAILABLE_SERVICES', [
    'Personal Bouncer',
    'Government Department Security Guard',
    'Driver',
    'Housekeeping Staff',
    'Gardener',
    'Maid / Domestic Help',
    'General Inquiry'
]);
