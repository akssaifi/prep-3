<?php
require_once 'config.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
require_once 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get settings
    $settings = getSettings($conn);
    
    // Sanitize inputs
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $course_id = isset($_POST['course']) ? intval($_POST['course']) : 0;
    $message = isset($_POST['message']) ? sanitize($_POST['message']) : '';
    
    // Get course name if selected
    $course_name = 'Not specified';
    if ($course_id > 0) {
        $course_query = mysqli_query($conn, "SELECT name FROM courses WHERE id = $course_id");
        if ($course = mysqli_fetch_assoc($course_query)) {
            $course_name = $course['name'];
        }
    }
    
    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'Name is required';
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required';
    }
    
    if (empty($phone)) {
        $errors[] = 'Phone number is required';
    }
    
    if (empty($errors)) {
        try {
            // Create PHPMailer instance
            $mail = new PHPMailer(true);
            
            // Server settings
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USER;
            $mail->Password   = SMTP_PASS;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port       = SMTP_PORT;
            
            // Recipients
            $mail->setFrom($email, $name);
            $mail->addAddress('director@prepwithdaljeet.com', 'Director');
            $mail->addReplyTo($email, $name);
            
            // Optional CC/BCC
            if (!empty($settings['secondary_email'])) {
                $mail->addCC($settings['secondary_email']);
            }
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = 'New Demo Request - ' . $settings['site_name'];
            
            $email_body = "
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: #D4AF37; color: #000; padding: 20px; text-align: center; }
                    .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
                    .detail { margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
                    .label { font-weight: bold; color: #D4AF37; }
                    .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>New Demo Class Request</h2>
                        <p>" . htmlspecialchars($settings['site_name']) . "</p>
                    </div>
                    
                    <div class='content'>
                        <div class='detail'>
                            <span class='label'>Name:</span> " . htmlspecialchars($name) . "
                        </div>
                        
                        <div class='detail'>
                            <span class='label'>Email:</span> " . htmlspecialchars($email) . "
                        </div>
                        
                        <div class='detail'>
                            <span class='label'>Phone:</span> " . htmlspecialchars($phone) . "
                        </div>
                        
                        <div class='detail'>
                            <span class='label'>Interested Course:</span> " . htmlspecialchars($course_name) . "
                        </div>
                        
                        <div class='detail'>
                            <span class='label'>Message:</span><br>
                            " . nl2br(htmlspecialchars($message)) . "
                        </div>
                        
                        <div class='detail'>
                            <span class='label'>Submitted:</span> " . date('F j, Y, g:i a') . "
                        </div>
                    </div>
                    
                    <div class='footer'>
                        <p>This email was sent from your website contact form.</p>
                    </div>
                </div>
            </body>
            </html>
            ";
            
            $mail->Body = $email_body;
            $mail->AltBody = "New Demo Request\n\nName: $name\nEmail: $email\nPhone: $phone\nCourse: $course_name\nMessage: $message\n\nSubmitted: " . date('F j, Y, g:i a');
            
            // Send email
            if ($mail->send()) {
                // Also save to database for record keeping
                $sql = "INSERT INTO demo_requests (name, email, phone, course_id, message, status) 
                        VALUES ('$name', '$email', '$phone', " . ($course_id > 0 ? $course_id : 'NULL') . ", '$message', 'pending')";
                mysqli_query($conn, $sql);
                
                // Send confirmation to user
                $user_mail = new PHPMailer(true);
                $user_mail->isSMTP();
                $user_mail->Host       = SMTP_HOST;
                $user_mail->SMTPAuth   = true;
                $user_mail->Username   = SMTP_USER;
                $user_mail->Password   = SMTP_PASS;
                $user_mail->SMTPSecure = SMTP_SECURE;
                $user_mail->Port       = SMTP_PORT;
                
                $user_mail->setFrom($settings['email'], $settings['site_name']);
                $user_mail->addAddress($email, $name);
                
                $user_mail->isHTML(true);
                $user_mail->Subject = 'Demo Request Confirmation - ' . $settings['site_name'];
                
                $confirmation_body = "
                <!DOCTYPE html>
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background: #D4AF37; color: #000; padding: 20px; text-align: center; }
                        .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
                        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                        .contact-info { margin-top: 20px; padding: 15px; background: #fff; border: 1px solid #D4AF37; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h2>Thank You for Your Interest!</h2>
                            <p>" . htmlspecialchars($settings['site_name']) . "</p>
                        </div>
                        
                        <div class='content'>
                            <p>Dear " . htmlspecialchars($name) . ",</p>
                            
                            <p>Thank you for requesting a demo class for <strong>" . htmlspecialchars($course_name) . "</strong>.</p>
                            
                            <p>Our team will contact you within 24 hours to schedule your free demo session and answer any questions you may have.</p>
                            
                            <div class='contact-info'>
                                <p><strong>Need immediate assistance?</strong></p>
                                <p>Phone: " . htmlspecialchars($settings['primary_phone']) . "</p>
                                <p>Email: " . htmlspecialchars($settings['email']) . "</p>
                            </div>
                            
                            <p>Best regards,<br>
                            The " . htmlspecialchars($settings['site_name']) . " Team</p>
                        </div>
                        
                        <div class='footer'>
                            <p>" . htmlspecialchars($settings['site_name']) . " | " . htmlspecialchars($settings['tagline']) . "</p>
                            <p>" . htmlspecialchars($settings['address']) . "</p>
                        </div>
                    </div>
                </body>
                </html>
                ";
                
                $user_mail->Body = $confirmation_body;
                $user_mail->AltBody = "Thank you for your demo request. We will contact you within 24 hours.\n\nContact: " . $settings['primary_phone'];
                
                $user_mail->send();
                
                // Return success response
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Thank you! Your demo request has been submitted. We will contact you shortly.'
                ]);
                exit;
            }
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Message could not be sent. Error: ' . $mail->ErrorInfo
            ]);
            exit;
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => implode('<br>', $errors)
        ]);
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>