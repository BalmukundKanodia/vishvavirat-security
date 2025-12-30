<?php
/**
 * VISHVAVIRAT SECURITY - Security Helper Class
 *
 * Provides security functions for:
 * - Input sanitization
 * - CSRF protection
 * - XSS prevention
 * - Rate limiting
 * - Validation
 */

class Security {

    /**
     * Sanitize general input
     *
     * @param string $input
     * @return string
     */
    public static function sanitizeInput($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        $input = strip_tags($input);

        // Limit length
        $input = substr($input, 0, 1000);

        return $input;
    }

    /**
     * Sanitize email
     *
     * @param string $email
     * @return string
     */
    public static function sanitizeEmail($email) {
        $email = trim($email);
        $email = strtolower($email);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        return $email;
    }

    /**
     * Sanitize phone number
     *
     * @param string $phone
     * @return string
     */
    public static function sanitizePhone($phone) {
        // Remove all non-numeric characters
        $phone = preg_replace('/\D/', '', $phone);
        // Limit to 10 digits
        $phone = substr($phone, 0, 10);
        return $phone;
    }

    /**
     * Validate email
     *
     * @param string $email
     * @return bool
     */
    public static function validateEmail($email) {
        if (empty($email)) {
            return false;
        }

        // Basic email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Check for common disposable email domains (optional)
        $disposableDomains = ['tempmail.com', 'throwaway.email', '10minutemail.com'];
        $domain = substr(strrchr($email, "@"), 1);

        if (in_array($domain, $disposableDomains)) {
            return false;
        }

        return true;
    }

    /**
     * Validate phone number (Indian format)
     *
     * @param string $phone
     * @return bool
     */
    public static function validatePhone($phone) {
        // Clean phone number
        $phone = preg_replace('/\D/', '', $phone);

        // Check if it's 10 digits
        if (strlen($phone) !== 10) {
            return false;
        }

        // Indian mobile numbers start with 6, 7, 8, or 9
        if (!preg_match('/^[6-9]\d{9}$/', $phone)) {
            return false;
        }

        return true;
    }

    /**
     * Generate CSRF token
     *
     * @return string
     */
    public static function generateCSRFToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        $_SESSION['csrf_token_time'] = time();

        return $token;
    }

    /**
     * Validate CSRF token
     *
     * @param string $token
     * @return bool
     */
    public static function validateCSRFToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if token exists in session
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }

        // Check if token matches
        if (!hash_equals($_SESSION['csrf_token'], $token)) {
            return false;
        }

        // Check if token is expired
        if (isset($_SESSION['csrf_token_time'])) {
            $tokenAge = time() - $_SESSION['csrf_token_time'];
            if ($tokenAge > CSRF_TOKEN_EXPIRY) {
                return false;
            }
        }

        return true;
    }

    /**
     * Rate limiting check
     *
     * @return bool True if allowed, false if rate limit exceeded
     */
    public static function checkRateLimit() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $now = time();
        $clientIp = self::getClientIP();

        // Initialize submission tracking
        if (!isset($_SESSION['submissions'])) {
            $_SESSION['submissions'] = [];
        }

        // Clean old submissions outside the time window
        $_SESSION['submissions'] = array_filter(
            $_SESSION['submissions'],
            function($timestamp) use ($now) {
                return ($now - $timestamp) < RATE_LIMIT_WINDOW;
            }
        );

        // Check if limit exceeded
        if (count($_SESSION['submissions']) >= MAX_FORM_SUBMISSIONS) {
            return false;
        }

        // Add current submission
        $_SESSION['submissions'][] = $now;

        return true;
    }

    /**
     * Get client IP address
     *
     * @return string
     */
    public static function getClientIP() {
        $ipKeys = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR'
        ];

        foreach ($ipKeys as $key) {
            if (isset($_SERVER[$key])) {
                $ip = $_SERVER[$key];

                // Handle multiple IPs (take the first one)
                if (strpos($ip, ',') !== false) {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]);
                }

                // Validate IP
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return '0.0.0.0';
    }

    /**
     * Prevent SQL injection by escaping strings
     * Note: Use prepared statements instead when possible
     *
     * @param string $string
     * @return string
     */
    public static function escapeSQLString($string) {
        return addslashes($string);
    }

    /**
     * Generate secure random token
     *
     * @param int $length
     * @return string
     */
    public static function generateSecureToken($length = 32) {
        return bin2hex(random_bytes($length));
    }

    /**
     * Hash password securely
     *
     * @param string $password
     * @return string
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_ARGON2ID);
    }

    /**
     * Verify password
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Check if request is from bot
     *
     * @return bool
     */
    public static function isBotRequest() {
        if (!isset($_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }

        $botPatterns = [
            'bot', 'crawl', 'spider', 'slurp', 'archive',
            'scrape', 'curl', 'wget', 'python-requests'
        ];

        $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);

        foreach ($botPatterns as $pattern) {
            if (strpos($userAgent, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Prevent XSS in output
     *
     * @param string $string
     * @return string
     */
    public static function escapeHTML($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Clean filename for upload
     *
     * @param string $filename
     * @return string
     */
    public static function cleanFilename($filename) {
        // Remove any path information
        $filename = basename($filename);

        // Remove any non-alphanumeric characters except dots, dashes, and underscores
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);

        // Limit length
        $filename = substr($filename, 0, 255);

        return $filename;
    }

    /**
     * Log security event
     *
     * @param string $event
     * @param array $data
     */
    public static function logSecurityEvent($event, $data = []) {
        $logFile = __DIR__ . '/security.log';

        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'event' => $event,
            'ip' => self::getClientIP(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            'data' => $data
        ];

        $logLine = json_encode($logEntry) . "\n";

        error_log($logLine, 3, $logFile);
    }

    /**
     * Check honeypot field (anti-spam)
     *
     * @param string $fieldValue
     * @return bool True if legitimate, false if spam
     */
    public static function checkHoneypot($fieldValue) {
        // Honeypot should be empty for legitimate users
        return empty($fieldValue);
    }

    /**
     * Validate referer
     *
     * @param array $allowedDomains
     * @return bool
     */
    public static function validateReferer($allowedDomains = []) {
        if (empty($_SERVER['HTTP_REFERER'])) {
            return false;
        }

        $referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);

        if (empty($allowedDomains)) {
            $allowedDomains = ALLOWED_ORIGINS;
        }

        foreach ($allowedDomains as $domain) {
            $domain = parse_url($domain, PHP_URL_HOST);
            if ($referer === $domain) {
                return true;
            }
        }

        return false;
    }
}
