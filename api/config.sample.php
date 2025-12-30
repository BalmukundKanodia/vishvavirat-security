<?php
/**
 * Configuration File Template
 *
 * IMPORTANT:
 * 1. Copy this file to config.php (or rename on Hostinger)
 * 2. Update all placeholder values with your actual credentials
 * 3. Do NOT commit config.php to version control (protected by .gitignore)
 */

// ============================================
// DATABASE CONFIGURATION
// ============================================
// Get these from Hostinger cPanel > MySQL Databases

define('DB_HOST', 'localhost');                        // Usually 'localhost' for Hostinger
define('DB_NAME', 'your_database_name');               // e.g., u876271999_vv_leads
define('DB_USER', 'your_database_user');               // e.g., u876271999_vv_admin
define('DB_PASS', 'your_database_password');           // Your MySQL password

// Variables for backward compatibility (required by contact.php)
$db_host = DB_HOST;
$db_name = DB_NAME;
$db_user = DB_USER;
$db_pass = DB_PASS;


// ============================================
// EMAIL CONFIGURATION
// ============================================

define('ADMIN_EMAIL', 'viratagencies770@gmail.com');   // Email to receive lead notifications
define('FROM_NAME', 'VISHVAVIRAT SECURITY');           // Site name for emails
define('FROM_EMAIL', 'noreply@viratsecurity.com');     // From email address

// Variables for backward compatibility (required by contact.php)
$notification_email = ADMIN_EMAIL;
$site_name = FROM_NAME;


// ============================================
// ADMIN PANEL CREDENTIALS
// ============================================
// IMPORTANT: Change these to secure values!

define('ADMIN_USERNAME', 'admin');                      // Change this to your desired username
define('ADMIN_PASSWORD', 'change_this_password_now');   // Change this to a STRONG password


// ============================================
// SITE SETTINGS
// ============================================

define('SITE_URL', 'https://www.viratsecurity.com');   // Your website URL
define('TIMEZONE', 'Asia/Kolkata');                     // Your timezone


// ============================================
// SECURITY SETTINGS
// ============================================

define('ENABLE_CSRF', true);                            // Enable CSRF protection
define('ENABLE_RATE_LIMIT', true);                      // Enable rate limiting
define('MAX_REQUESTS_PER_HOUR', 10);                    // Max form submissions per hour per IP


// ============================================
// ERROR LOGGING
// ============================================

define('LOG_ERRORS', true);                             // Enable error logging
define('LOG_PATH', __DIR__ . '/../logs/');              // Path to log files


// ============================================
// INITIALIZATION
// ============================================

// Set timezone
date_default_timezone_set(TIMEZONE);

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);  // Don't show errors to users
ini_set('log_errors', 1);      // Log errors to file

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);
