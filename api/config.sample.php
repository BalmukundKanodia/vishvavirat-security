<?php
/**
 * Configuration File
 * 
 * IMPORTANT: Rename this file to config.php and update with your actual credentials
 * Do NOT commit config.php to version control
 */

// Database Configuration (from Hostinger cPanel > MySQL Databases)
$db_host = 'localhost';              // Usually 'localhost' for Hostinger
$db_user = 'your_database_user';     // Your MySQL username
$db_pass = 'your_database_password'; // Your MySQL password
$db_name = 'your_database_name';     // Your database name

// Email Configuration
$notification_email = 'viratagencies770@gmail.com'; // Email where you want to receive lead notifications
$site_name = 'VISHVAVIRAT SECURITY';               // Your site name

// Security Settings
define('ADMIN_USERNAME', 'admin');                  // Admin panel username
define('ADMIN_PASSWORD', 'change_this_password');   // Admin panel password (CHANGE THIS!)

// Site Settings
define('SITE_URL', 'https://yourdomain.com');      // Your website URL
define('TIMEZONE', 'Asia/Kolkata');                // Your timezone

// Set timezone
date_default_timezone_set(TIMEZONE);
