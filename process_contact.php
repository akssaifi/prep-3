<?php
require_once 'config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON header
header('Content-Type: application/json');

// Get POST data
$action = isset($_POST['action']) ? sanitize($_POST['action']) : '';

// Response array
$response = ['success' => false, 'message' => ''];

switch ($action) {
    case 'newsletter_subscribe':
        $email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Invalid email address';
            echo json_encode($response);
            exit;
        }
        
        // Save to database (you should create a newsletter_subscriptions table)
        // For now, just send email
        $subject = "New Newsletter Subscription";
        $body = "New newsletter subscription from: $email\n\n";
        $body .= "Time: " . date('Y-m-d H:i:s') . "\n";
        $body .= "IP: " . $_SERVER['REMOTE_ADDR'];
        
        // Send email using PHPMailer
        if (sendEmail('akssaifi95@gmail.com', $subject, $body)) {
            $response['success'] = true;
            $response['message'] = 'Subscription successful!';
        } else {
            $response['message'] = 'Failed to process subscription';
        }
        break;
        
    case 'demo_request':
        $name = isset($_POST['name']) ? sanitize($_POST['name']) : '';
        $email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? sanitize($_POST['phone']) : '';
        $course_id = isset($_POST['course']) ? intval($_POST['course']) : 0;
        $message = isset($_POST['message']) ? sanitize($_POST['message']) : '';
        
        // Validation
        if (empty($name) || empty($email) || empty($phone)) {
            $response['message'] = 'Please fill in all required fields';
            echo json_encode($response);
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Invalid email address';
            echo json_encode($response);
            exit;
        }
        
        // Get course name if selected
        $course_name = 'Not specified';
        if ($course_id > 0) {
            $course_result = mysqli_query($conn, "SELECT name FROM courses WHERE id = $course_id");
            if ($course_row = mysqli_fetch_assoc($course_result)) {
                $course_name = $course_row['name'];
            }
        }
        
        // Prepare email content
        $subject = "New Demo Class Request - Prep with Daljeet";
        $body = "<h2>New Demo Class Request</h2>";
        $body .= "<p><strong>Name:</strong> $name</p>";
        $body .= "<p><strong>Email:</strong> $email</p>";
        $body .= "<p><strong>Phone:</strong> $phone</p>";
        $body .= "<p><strong>Interested Course:</strong> $course_name</p>";
        $body .= "<p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>";
        $body .= "<hr>";
        $body .= "<p>Time: " . date('Y-m-d H:i:s') . "</p>";
        $body .= "<p>IP: " . $_SERVER['REMOTE_ADDR'] . "</p>";
        
        // Send email to admin
        if (sendEmail('akssaifi95@gmail.com', $subject, $body, $email, $name)) {
            // Also send confirmation to user
            $user_subject = "Demo Class Request Confirmation";
            $user_body = "<h2>Thank you for your interest in Prep with Daljeet!</h2>";
            $user_body .= "<p>Dear $name,</p>";
            $user_body .= "<p>We have received your request for a demo class for <strong>$course_name</strong>.</p>";
            $user_body .= "<p>Our team will contact you within 24 hours to schedule your demo session.</p>";
            $user_body .= "<hr>";
            $user_body .= "<p>If you have any questions, please contact us at:</p>";
            $user_body .= "<p>Phone: " . $settings['primary_phone'] . "</p>";
            $user_body .= "<p>Email: akssaifi95@gmail.com</p>";
            
            sendEmail($email, $user_subject, $user_body, 'akssaifi95@gmail.com', 'Prep with Daljeet');
            
            $response['success'] = true;
            $response['message'] = 'Demo request submitted successfully!';
        } else {
            $response['message'] = 'Failed to submit request. Please try again.';
        }
        break;
        
    default:
        $response['message'] = 'Invalid action';
}

echo json_encode($response);
exit;

// Email sending function using PHPMailer
function sendEmail($to, $subject, $body, $from_email = null, $from_name = null) {
    // In a production environment, you would use PHPMailer
    // For now, use PHP's mail function with proper headers
    
    $from_email = $from_email ?: 'noreply@prepwithdaljeet.com';
    $from_name = $from_name ?: 'Prep with Daljeet';
    
    $headers = "From: $from_name <$from_email>\r\n";
    $headers .= "Reply-To: $from_email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    return mail($to, $subject, $body, $headers);
}
?>