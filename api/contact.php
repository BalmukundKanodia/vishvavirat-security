<?php
/**
 * VISHVAVIRAT SECURITY - Contact Form Handler
 *
 * Secure form submission handler with:
 * - Input validation and sanitization
 * - CSRF protection
 * - XSS prevention
 * - SQL injection protection (via prepared statements)
 * - Rate limiting
 * - Email notification
 */

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Content-Type: application/json');

// Start session for CSRF and rate limiting
session_start();

define('SECURE_ACCESS', true);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/security.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Check if request is AJAX
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

try {
    // ============================================
    // RATE LIMITING
    // ============================================
    if (!Security::checkRateLimit()) {
        http_response_code(429);
        echo json_encode([
            'success' => false,
            'message' => 'Too many requests. Please try again later.'
        ]);
        exit;
    }

    // ============================================
    // CSRF VALIDATION
    // ============================================
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!Security::validateCSRFToken($csrfToken)) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid security token. Please refresh the page and try again.'
        ]);
        exit;
    }

    // ============================================
    // INPUT VALIDATION & SANITIZATION
    // ============================================

    // Required fields
    $name = Security::sanitizeInput($_POST['name'] ?? '');
    $phone = Security::sanitizePhone($_POST['phone'] ?? '');
    $email = Security::sanitizeEmail($_POST['email'] ?? '');
    $service = Security::sanitizeInput($_POST['service'] ?? 'General Inquiry');

    // Optional fields
    $organization = Security::sanitizeInput($_POST['organization'] ?? '');
    $location = Security::sanitizeInput($_POST['location'] ?? '');
    $duration = Security::sanitizeInput($_POST['duration'] ?? '');
    $message = Security::sanitizeInput($_POST['message'] ?? '');
    $sourcePage = Security::sanitizeInput($_POST['source_page'] ?? '');
    $timestamp = $_POST['timestamp'] ?? date('Y-m-d H:i:s');

    // Validate required fields
    $errors = [];

    if (empty($name) || strlen($name) < 2) {
        $errors[] = 'Please provide a valid name';
    }

    if (!Security::validatePhone($phone)) {
        $errors[] = 'Please provide a valid 10-digit phone number';
    }

    if (!Security::validateEmail($email)) {
        $errors[] = 'Please provide a valid email address';
    }

    if (!in_array($service, AVAILABLE_SERVICES)) {
        $errors[] = 'Invalid service selected';
    }

    // Return validation errors
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => implode('. ', $errors)
        ]);
        exit;
    }

    // ============================================
    // DATABASE STORAGE
    // ============================================

    $db = getDB();

    $insertQuery = "
        INSERT INTO contact_submissions (
            name,
            phone,
            email,
            service,
            organization,
            location,
            duration,
            message,
            source_page,
            ip_address,
            user_agent,
            created_at
        ) VALUES (
            :name,
            :phone,
            :email,
            :service,
            :organization,
            :location,
            :duration,
            :message,
            :source_page,
            :ip_address,
            :user_agent,
            NOW()
        )
    ";

    $params = [
        ':name' => $name,
        ':phone' => $phone,
        ':email' => $email,
        ':service' => $service,
        ':organization' => $organization,
        ':location' => $location,
        ':duration' => $duration,
        ':message' => $message,
        ':source_page' => $sourcePage,
        ':ip_address' => Security::getClientIP(),
        ':user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255)
    ];

    $submissionId = $db->insert($insertQuery, $params);

    // ============================================
    // EMAIL NOTIFICATION
    // ============================================

    $emailSent = sendAdminNotification([
        'id' => $submissionId,
        'name' => $name,
        'phone' => $phone,
        'email' => $email,
        'service' => $service,
        'organization' => $organization,
        'location' => $location,
        'duration' => $duration,
        'message' => $message,
        'source_page' => $sourcePage
    ]);

    // ============================================
    // SEND CONFIRMATION EMAIL TO USER
    // ============================================

    sendUserConfirmation([
        'name' => $name,
        'email' => $email,
        'service' => $service
    ]);

    // ============================================
    // SUCCESS RESPONSE
    // ============================================

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for contacting us! We have received your inquiry and will get back to you within 24 hours.',
        'submission_id' => $submissionId
    ]);

} catch (Exception $e) {
    // Log error
    error_log('Form submission error: ' . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while processing your request. Please try again or contact us directly.'
    ]);
}

// ============================================
// EMAIL FUNCTIONS
// ============================================

/**
 * Send email notification to admin
 */
function sendAdminNotification($data) {
    $to = ADMIN_EMAIL;
    $subject = '[New Inquiry] ' . $data['service'] . ' - ' . $data['name'];

    $body = "
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1a365d; color: white; padding: 20px; text-align: center; }
        .content { background: #f8f9fa; padding: 20px; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #1a365d; }
        .value { margin-top: 5px; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>New Service Inquiry - VISHVAVIRAT SECURITY</h2>
        </div>
        <div class='content'>
            <div class='field'>
                <div class='label'>Submission ID:</div>
                <div class='value'>#" . $data['id'] . "</div>
            </div>
            <div class='field'>
                <div class='label'>Service Requested:</div>
                <div class='value'>" . htmlspecialchars($data['service']) . "</div>
            </div>
            <div class='field'>
                <div class='label'>Name:</div>
                <div class='value'>" . htmlspecialchars($data['name']) . "</div>
            </div>
            <div class='field'>
                <div class='label'>Phone:</div>
                <div class='value'>" . htmlspecialchars($data['phone']) . "</div>
            </div>
            <div class='field'>
                <div class='label'>Email:</div>
                <div class='value'>" . htmlspecialchars($data['email']) . "</div>
            </div>";

    if (!empty($data['organization'])) {
        $body .= "
            <div class='field'>
                <div class='label'>Organization:</div>
                <div class='value'>" . htmlspecialchars($data['organization']) . "</div>
            </div>";
    }

    if (!empty($data['location'])) {
        $body .= "
            <div class='field'>
                <div class='label'>Location:</div>
                <div class='value'>" . htmlspecialchars($data['location']) . "</div>
            </div>";
    }

    if (!empty($data['duration'])) {
        $body .= "
            <div class='field'>
                <div class='label'>Duration:</div>
                <div class='value'>" . htmlspecialchars($data['duration']) . "</div>
            </div>";
    }

    if (!empty($data['message'])) {
        $body .= "
            <div class='field'>
                <div class='label'>Message:</div>
                <div class='value'>" . nl2br(htmlspecialchars($data['message'])) . "</div>
            </div>";
    }

    $body .= "
            <div class='field'>
                <div class='label'>Source Page:</div>
                <div class='value'>" . htmlspecialchars($data['source_page']) . "</div>
            </div>
            <div class='field'>
                <div class='label'>Submitted On:</div>
                <div class='value'>" . date('d M Y, h:i A') . "</div>
            </div>
        </div>
        <div class='footer'>
            <p>This is an automated message from VISHVAVIRAT SECURITY website contact form.</p>
            <p>Please respond to this inquiry within 24 hours.</p>
        </div>
    </div>
</body>
</html>
    ";

    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: ' . FROM_NAME . ' <' . FROM_EMAIL . '>',
        'Reply-To: ' . $data['email'],
        'X-Mailer: PHP/' . phpversion()
    ];

    return mail($to, $subject, $body, implode("\r\n", $headers));
}

/**
 * Send confirmation email to user
 */
function sendUserConfirmation($data) {
    $to = $data['email'];
    $subject = 'Thank You for Contacting VISHVAVIRAT SECURITY';

    $body = "
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1a365d; color: white; padding: 20px; text-align: center; }
        .content { background: #f8f9fa; padding: 20px; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>VISHVAVIRAT SECURITY & FACILITY INDIA PVT. LTD.</h2>
        </div>
        <div class='content'>
            <p>Dear " . htmlspecialchars($data['name']) . ",</p>

            <p>Thank you for your interest in our <strong>" . htmlspecialchars($data['service']) . "</strong> service.</p>

            <p>We have received your inquiry and our team will review your requirements. One of our representatives will contact you within 24 hours to discuss your needs and provide a customized solution.</p>

            <p><strong>What happens next:</strong></p>
            <ul>
                <li>Our team will review your requirements</li>
                <li>We will contact you within 24 hours</li>
                <li>We'll discuss your specific needs</li>
                <li>Provide a customized quote and timeline</li>
            </ul>

            <p><strong>Need immediate assistance?</strong><br>
            Email: viratagencies770@gmail.com<br>
            Phone: +91-XXXXXXXXXX</p>

            <p>Thank you for choosing VISHVAVIRAT SECURITY.</p>
        </div>
        <div class='footer'>
            <p><strong>VISHVAVIRAT SECURITY & FACILITY INDIA PVT. LTD.</strong><br>
            6, 1st Floor, Annapura Main Road, Opp. Dreamz Lodge, Sudhamanagar<br>
            Bangalore – 560 027<br>
            www.viratsecurity.com</p>
        </div>
    </div>
</body>
</html>
    ";

    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: ' . FROM_NAME . ' <' . FROM_EMAIL . '>',
        'Reply-To: ' . REPLY_TO_EMAIL,
        'X-Mailer: PHP/' . phpversion()
    ];

    return mail($to, $subject, $body, implode("\r\n", $headers));
}
