<?php
/**
 * VISHVAVIRAT SECURITY & FACILITY INDIA PVT LTD
 * Contact Form Handler
 *
 * This script handles form submissions, stores data in database, and sends email notifications
 */

// Enable error reporting for development (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php-errors.log');

// Set response headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Load configuration
require_once __DIR__ . '/config.php';

// Response array
$response = ['success' => false, 'message' => ''];

try {
    // Validate and sanitize input
    $name = sanitizeInput($_POST['name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $location = sanitizeInput($_POST['location'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');
    $service = sanitizeInput($_POST['service'] ?? 'General Inquiry');
    $source_page = sanitizeInput($_POST['source_page'] ?? '');
    $csrf_token = sanitizeInput($_POST['csrf_token'] ?? '');

    // Validation
    $errors = [];

    if (empty($name) || strlen($name) < 2) {
        $errors[] = 'Please provide a valid name';
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please provide a valid email address';
    }

    if (empty($phone) || !preg_match('/^[6-9]\d{9}$/', preg_replace('/\s+/', '', $phone))) {
        $errors[] = 'Please provide a valid 10-digit phone number';
    }

    if (empty($message) || strlen($message) < 10) {
        $errors[] = 'Please provide more details about your requirements';
    }

    // If validation fails
    if (!empty($errors)) {
        $response['message'] = implode('. ', $errors);
        echo json_encode($response);
        exit;
    }

    // Check for spam/duplicate submissions
    if (isSpamSubmission($email, $phone)) {
        $response['message'] = 'Duplicate submission detected. Please wait before submitting again.';
        echo json_encode($response);
        exit;
    }

    // Connect to database
    $db = connectDatabase();

    // Insert into database
    $stmt = $db->prepare("
        INSERT INTO contact_submissions
        (name, email, phone, location, message, service_type, source_page, ip_address, user_agent, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");

    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

    $stmt->bind_param(
        "sssssssss",
        $name,
        $email,
        $phone,
        $location,
        $message,
        $service,
        $source_page,
        $ip_address,
        $user_agent
    );

    if (!$stmt->execute()) {
        throw new Exception('Failed to save submission to database');
    }

    $submission_id = $db->insert_id;
    $stmt->close();

    // Send email notification
    $emailSent = sendEmailNotification([
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'location' => $location,
        'message' => $message,
        'service' => $service,
        'source_page' => $source_page,
        'submission_id' => $submission_id
    ]);

    // Close database connection
    $db->close();

    // Success response
    $response['success'] = true;
    $response['message'] = 'Thank you for your inquiry! We will contact you within 24 hours.';

    if (!$emailSent) {
        $response['message'] .= ' (Note: Email notification could not be sent, but your inquiry was saved.)';
    }

} catch (Exception $e) {
    error_log('Contact form error: ' . $e->getMessage());
    $response['message'] = 'An error occurred while processing your request. Please try again or contact us directly at viratagencies770@gmail.com';
}

echo json_encode($response);
exit;

// ============================================
// HELPER FUNCTIONS
// ============================================

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function connectDatabase() {
    global $db_host, $db_user, $db_pass, $db_name;

    $db = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($db->connect_error) {
        throw new Exception('Database connection failed');
    }

    // Set MySQL timezone to match PHP timezone (Asia/Kolkata = UTC+5:30)
    $db->query("SET time_zone = '+05:30'");

    $db->set_charset('utf8mb4');
    return $db;
}

function isSpamSubmission($email, $phone) {
    try {
        $db = connectDatabase();

        // Check for submissions in last 5 minutes
        $stmt = $db->prepare("
            SELECT COUNT(*) as count
            FROM contact_submissions
            WHERE (email = ? OR phone = ?)
            AND created_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
        ");

        $stmt->bind_param("ss", $email, $phone);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        $db->close();

        return $row['count'] > 0;
    } catch (Exception $e) {
        return false; // Don't block if check fails
    }
}

function sendEmailNotification($data) {
    global $notification_email, $site_name;

    $to = $notification_email;
    $subject = "New {$data['service']} Inquiry - {$site_name}";

    // HTML email template
    $html_message = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #1a1a1a, #2a2a2a); color: #c9a961; padding: 20px; text-align: center; }
            .content { background: #f9f9f9; padding: 20px; margin-top: 20px; }
            .field { margin-bottom: 15px; }
            .label { font-weight: bold; color: #1a1a1a; }
            .value { color: #444; margin-top: 5px; }
            .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
            .highlight { background: #fff; padding: 15px; border-left: 4px solid #c9a961; margin: 10px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>New Lead Submission</h2>
                <p>VISHVAVIRAT SECURITY & FACILITY INDIA PVT LTD</p>
            </div>

            <div class='content'>
                <div class='highlight'>
                    <div class='label'>Service Type:</div>
                    <div class='value' style='font-size: 18px; color: #c9a961;'>{$data['service']}</div>
                </div>

                <div class='field'>
                    <div class='label'>Name:</div>
                    <div class='value'>{$data['name']}</div>
                </div>

                <div class='field'>
                    <div class='label'>Email:</div>
                    <div class='value'><a href='mailto:{$data['email']}'>{$data['email']}</a></div>
                </div>

                <div class='field'>
                    <div class='label'>Phone:</div>
                    <div class='value'><a href='tel:{$data['phone']}'>{$data['phone']}</a></div>
                </div>

                <div class='field'>
                    <div class='label'>Location:</div>
                    <div class='value'>{$data['location']}</div>
                </div>

                <div class='field'>
                    <div class='label'>Requirements:</div>
                    <div class='value'>" . nl2br($data['message']) . "</div>
                </div>

                <hr style='margin: 20px 0; border: none; border-top: 1px solid #ddd;'>

                <div class='field'>
                    <div class='label'>Source Page:</div>
                    <div class='value'>{$data['source_page']}</div>
                </div>

                <div class='field'>
                    <div class='label'>Submission ID:</div>
                    <div class='value'>#{$data['submission_id']}</div>
                </div>

                <div class='field'>
                    <div class='label'>Submitted:</div>
                    <div class='value'>" . date('d M Y, h:i A') . "</div>
                </div>
            </div>

            <div class='footer'>
                <p>This is an automated notification from your website contact form.</p>
                <p>Please respond to the customer within 24 hours for best conversion.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    // Plain text version
    $text_message = "
New Lead Submission - {$data['service']}

Name: {$data['name']}
Email: {$data['email']}
Phone: {$data['phone']}
Location: {$data['location']}

Requirements:
{$data['message']}

Source Page: {$data['source_page']}
Submission ID: #{$data['submission_id']}
Submitted: " . date('d M Y, h:i A') . "
    ";

    // Email headers
    $from_email = defined('FROM_EMAIL') ? FROM_EMAIL : 'noreply@viratsecurity.com';
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: {$site_name} <{$from_email}>\r\n";
    $headers .= "Reply-To: {$data['email']}\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Send email
    return mail($to, $subject, $html_message, $headers);
}
