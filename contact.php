<?php
require_once 'config.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
require_once 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$settings = getSettings($conn);

// Function to send email using PHPMailer
function sendEmail($to, $subject, $body, $replyTo = '', $replyName = '') {
    global $settings;
    
    try {
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
        $mail->setFrom(SMTP_USER, $settings['site_name']);
        $mail->addAddress($to);
        
        if (!empty($replyTo)) {
            $mail->addReplyTo($replyTo, $replyName);
        }
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

// Handle contact form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'contact_form') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $subject = sanitize($_POST['subject']);
    $message = sanitize($_POST['message']);
    
    // Validate inputs
    $errors = [];
    
    if (empty($name)) $errors[] = 'Name is required';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
    if (empty($phone)) $errors[] = 'Phone number is required';
    if (empty($subject)) $errors[] = 'Subject is required';
    if (empty($message)) $errors[] = 'Message is required';
    
    if (empty($errors)) {
        // Prepare email content
        $to = $settings['email'];
        $email_subject = "Contact Form: $subject - $settings[site_name]";
        
        $email_body = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: 'Inter', Arial, sans-serif; line-height: 1.6; color: #2C3E50; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #D4AF37, #FFD700); color: white; padding: 20px; border-radius: 8px 8px 0 0; }
                .content { background: #f8f9fa; padding: 20px; border-radius: 0 0 8px 8px; }
                .info-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                .info-table td { padding: 12px; border-bottom: 1px solid #dee2e6; }
                .info-table td:first-child { font-weight: bold; color: #D4AF37; width: 30%; }
                .message-box { background: white; padding: 15px; border-left: 4px solid #D4AF37; margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; color: #6c757d; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2 style='margin: 0; color: white;'>New Contact Form Submission</h2>
                    <p style='margin: 5px 0 0 0; opacity: 0.9;'>$settings[site_name]</p>
                </div>
                <div class='content'>
                    <table class='info-table'>
                        <tr>
                            <td>Name:</td>
                            <td>$name</td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td>$email</td>
                        </tr>
                        <tr>
                            <td>Phone:</td>
                            <td>$phone</td>
                        </tr>
                        <tr>
                            <td>Subject:</td>
                            <td>$subject</td>
                        </tr>
                    </table>
                    
                    <div style='margin: 20px 0;'>
                        <strong style='color: #D4AF37;'>Message:</strong>
                        <div class='message-box'>" . nl2br(htmlspecialchars($message)) . "</div>
                    </div>
                    
                    <div class='footer'>
                        <p>This message was sent from the contact form on $settings[site_name] website.</p>
                        <p>Received at: " . date('F j, Y, g:i a') . "</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Headers
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . $settings['site_name'] . " <no-reply@" . $_SERVER['HTTP_HOST'] . ">\r\n";
        $headers .= "Reply-To: $name <$email>\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Send email using PHPMailer
        if (sendEmail($to, $email_subject, $email_body, $email, $name)) {
            // Send auto-reply to user
            $user_subject = "Thank you for contacting " . $settings['site_name'];
            $user_message = "
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: 'Inter', Arial, sans-serif; line-height: 1.6; color: #2C3E50; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: linear-gradient(135deg, #D4AF37, #FFD700); color: white; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
                    .content { background: #f8f9fa; padding: 20px; border-radius: 0 0 8px 8px; }
                    .message-box { background: white; padding: 15px; border-left: 4px solid #D4AF37; margin: 20px 0; }
                    .footer { text-align: center; margin-top: 30px; color: #6c757d; font-size: 14px; }
                    .btn { display: inline-block; background: linear-gradient(135deg, #D4AF37, #FFD700); color: white; padding: 12px 30px; text-decoration: none; border-radius: 8px; margin: 10px 0; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2 style='margin: 0; color: white;'>Thank You for Contacting Us</h2>
                    </div>
                    <div class='content'>
                        <p>Dear $name,</p>
                        <p>Thank you for contacting <strong>$settings[site_name]</strong>. We have received your message and our team will review it shortly.</p>
                        
                        <p><strong>Here's a summary of your inquiry:</strong></p>
                        <div class='message-box'>
                            <p><strong>Subject:</strong> $subject</p>
                            <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
                        </div>
                        
                        <p>We aim to respond to all inquiries within <strong>24 hours</strong> during business days.</p>
                        
                        <p>In the meantime, you might want to:</p>
                        <ul>
                            <li>Visit our website for more information: <a href='" . $_SERVER['HTTP_HOST'] . "'>" . $_SERVER['HTTP_HOST'] . "</a></li>
                            <li>Check our FAQ section for quick answers</li>
                            <li>Follow us on social media for updates</li>
                        </ul>
                        
                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='" . $_SERVER['HTTP_HOST'] . "' class='btn'>Visit Our Website</a>
                        </div>
                        
                        <div class='footer'>
                            <p>Best regards,<br>
                            <strong>$settings[site_name] Team</strong></p>
                            <p style='font-size: 12px; color: #6c757d;'>
                                Phone: $settings[primary_phone]<br>
                                Email: $settings[email]
                            </p>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            ";
            
            sendEmail($email, $user_subject, $user_message, $settings['email'], $settings['site_name']);
            
            $success_message = 'Thank you! Your message has been sent successfully. We will contact you soon.';
            
            // Clear form data
            $_POST = [];
        } else {
            $error_message = 'Sorry, there was an error sending your message. Please try again or contact us directly.';
        }
    } else {
        $error_message = 'Please fill in all required fields correctly.';
    }
}

include 'header.php';
?>

<!-- Page Header -->
<section class="hero-section contact-hero">
    <div class="container-custom">
        <div class="hero-content text-center py-5">
            <div class="hero-badge mb-4">
                <span class="badge-gold">Get in Touch</span>
            </div>
            <h1 class="hero-title mb-3">Contact Us</h1>
            <p class="hero-subtitle mb-4">Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
            <div class="hero-buttons">
                <a href="#contact-form" class="btn-royal">
                    <i class="fas fa-envelope me-2"></i> Send Message
                </a>
                <a href="tel:<?php echo htmlspecialchars($settings['primary_phone']); ?>" class="btn-outline-gold">
                    <i class="fas fa-phone me-2"></i> Call Now
                </a>
            </div>
        </div>
    </div>
</section>

<div class="container-custom py-5">
    <!-- Messages -->
    <?php if ($success_message): ?>
        <div class="royal-toast toast-success" id="successToast">
            <i class="fas fa-check-circle toast-icon"></i>
            <div>
                <strong class="text-gold">Success!</strong>
                <p><?php echo htmlspecialchars($success_message); ?></p>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if ($error_message): ?>
        <div class="royal-toast toast-error" id="errorToast">
            <i class="fas fa-exclamation-triangle toast-icon"></i>
            <div>
                <strong class="text-gold">Error!</strong>
                <p><?php echo htmlspecialchars($error_message); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <div class="row g-5">
        <!-- Contact Information -->
        <div class="col-lg-4">
            <div class="card-royal contact-info-card">
                <div class="card-header-royal">
                    <h3 class="card-title-royal">
                        <i class="fas fa-info-circle me-2"></i> Contact Information
                    </h3>
                </div>
                <div class="card-body-royal">
                    <!-- Phone -->
                    <div class="contact-item mb-4">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Phone Numbers</h4>
                            <div class="phone-numbers">
                                <a href="tel:<?php echo htmlspecialchars($settings['primary_phone']); ?>" class="contact-link">
                                    <i class="fas fa-phone-alt me-2 text-gold"></i>
                                    <span><?php echo htmlspecialchars($settings['primary_phone']); ?></span>
                                </a>
                                <?php if ($settings['secondary_phone']): ?>
                                    <a href="tel:<?php echo htmlspecialchars($settings['secondary_phone']); ?>" class="contact-link">
                                        <i class="fas fa-phone me-2 text-gold"></i>
                                        <span><?php echo htmlspecialchars($settings['secondary_phone']); ?></span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="contact-item mb-4">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Email Address</h4>
                            <a href="mailto:<?php echo htmlspecialchars($settings['email']); ?>" class="contact-link">
                                <i class="fas fa-envelope-open-text me-2 text-gold"></i>
                                <span><?php echo htmlspecialchars($settings['email']); ?></span>
                            </a>
                        </div>
                    </div>

                    <!-- WhatsApp -->
                    <?php if ($settings['whatsapp_number']): ?>
                        <div class="contact-item mb-4">
                            <div class="contact-icon">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div class="contact-details">
                                <h4>WhatsApp</h4>
                                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $settings['whatsapp_number']); ?>" 
                                   class="contact-link" target="_blank">
                                    <i class="fab fa-whatsapp me-2 text-gold"></i>
                                    <span><?php echo htmlspecialchars($settings['whatsapp_number']); ?></span>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Address -->
                    <?php if ($settings['address']): ?>
                        <div class="contact-item mb-4">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Our Location</h4>
                                <div class="address-text">
                                    <i class="fas fa-location-dot me-2 text-gold"></i>
                                    <span><?php echo nl2br(htmlspecialchars($settings['address'])); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Business Hours -->
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Business Hours</h4>
                            <div class="hours-list">
                                <div class="hours-item">
                                    <span class="day">Monday - Friday:</span>
                                    <span class="time">9:00 AM - 7:00 PM</span>
                                </div>
                                <div class="hours-item">
                                    <span class="day">Saturday:</span>
                                    <span class="time">10:00 AM - 5:00 PM</span>
                                </div>
                                <div class="hours-item">
                                    <span class="day">Sunday:</span>
                                    <span class="time closed">Closed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="card-royal mt-4">
                <div class="card-header-royal">
                    <h3 class="card-title-royal">
                        <i class="fas fa-share-alt me-2"></i> Follow Us
                    </h3>
                </div>
                <div class="card-body-royal">
                    <div class="social-links-grid">
                        <?php if ($settings['facebook_url'] && $settings['show_facebook']): ?>
                            <a href="<?php echo $settings['facebook_url']; ?>" class="social-link fb" target="_blank">
                                <i class="fab fa-facebook-f"></i>
                                <span>Facebook</span>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($settings['instagram_url'] && $settings['show_instagram']): ?>
                            <a href="<?php echo $settings['instagram_url']; ?>" class="social-link ig" target="_blank">
                                <i class="fab fa-instagram"></i>
                                <span>Instagram</span>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($settings['twitter_url'] && $settings['show_twitter']): ?>
                            <a href="<?php echo $settings['twitter_url']; ?>" class="social-link tw" target="_blank">
                                <i class="fab fa-twitter"></i>
                                <span>Twitter</span>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($settings['linkedin_url'] && $settings['show_linkedin']): ?>
                            <a href="<?php echo $settings['linkedin_url']; ?>" class="social-link li" target="_blank">
                                <i class="fab fa-linkedin-in"></i>
                                <span>LinkedIn</span>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($settings['youtube_url'] && $settings['show_youtube']): ?>
                            <a href="<?php echo $settings['youtube_url']; ?>" class="social-link yt" target="_blank">
                                <i class="fab fa-youtube"></i>
                                <span>YouTube</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-8">
            <div class="card-royal" id="contact-form">
                <div class="card-header-royal">
                    <h3 class="card-title-royal">
                        <i class="fas fa-paper-plane me-2"></i> Send Us a Message
                    </h3>
                </div>
                <div class="card-body-royal">
                    <form method="POST" class="contact-form" id="contactForm">
                        <input type="hidden" name="action" value="contact_form">
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contactName" class="form-label">Full Name *</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-user"></i>
                                        <input type="text" id="contactName" name="name" class="form-control" 
                                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                                               required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contactEmail" class="form-label">Email Address *</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-envelope"></i>
                                        <input type="email" id="contactEmail" name="email" class="form-control" 
                                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                               required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contactPhone" class="form-label">Phone Number *</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-phone"></i>
                                        <input type="tel" id="contactPhone" name="phone" class="form-control" 
                                               value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" 
                                               required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contactSubject" class="form-label">Subject *</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-tag"></i>
                                        <input type="text" id="contactSubject" name="subject" class="form-control" 
                                               value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>" 
                                               required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="contactMessage" class="form-label">Message *</label>
                                    <div class="textarea-with-icon">
                                        <i class="fas fa-comment"></i>
                                        <textarea id="contactMessage" name="message" class="form-control form-textarea" 
                                                  rows="6" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="privacyPolicy" required>
                                    <label for="privacyPolicy" class="form-check-label">
                                        I agree to the <a href="#" class="text-gold">privacy policy</a> and 
                                        allow <?php echo htmlspecialchars($settings['site_name']); ?> to contact me.
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn-royal w-100 py-3">
                                    <i class="fas fa-paper-plane me-2"></i> Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Google Map - FIXED SECTION -->
            <?php if (!empty($settings['google_map_embed'])): ?>
                <div class="card-royal mt-4">
                    <div class="card-header-royal">
                        <h3 class="card-title-royal">
                            <i class="fas fa-map-marked-alt me-2"></i> Our Location
                        </h3>
                    </div>
                    <div class="card-body-royal">
                        <div class="map-container">
                            <!-- Fix 1: Use html_entity_decode to convert HTML entities back -->
                            <?php 
                            // First, decode any HTML entities that might have been stored
                            $map_embed = html_entity_decode($settings['google_map_embed']);
                            
                            // Fix 2: Use strip_tags with allowed tags to ensure only iframe is allowed
                            $map_embed = strip_tags($map_embed, '<iframe><embed><object><script>');
                            
                            // Fix 3: Make sure the iframe has responsive attributes
                            // Add style to make iframe responsive if not already present
                            if (strpos($map_embed, 'style=') === false) {
                                $map_embed = str_replace('<iframe', '<iframe style="width: 100%; height: 400px; border: 0;"', $map_embed);
                            }
                            
                            // Fix 4: Output raw HTML without escaping
                            echo $map_embed;
                            ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* Contact Page Specific Styles */
    .contact-hero {
        background: linear-gradient(rgba(255, 255, 255, 0.95), rgba(165, 162, 162, 0.95)), 
                    url('https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80');
        background-size: cover;
        background-position: center;
        padding: 80px 0 40px;
        margin-top: 0;
    }
    
    .contact-hero .hero-title {
        font-size: 3.5rem;
        color: var(--royal-darker);
    }
    
    /* Contact Information Card */
    .contact-info-card {
        border: 2px solid rgba(212, 175, 55, 0.3);
    }
    
    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid rgba(212, 175, 55, 0.1);
    }
    
    .contact-item:last-child {
        border-bottom: none;
    }
    
    .contact-icon {
        width: 45px;
        height: 45px;
        background: rgba(212, 175, 55, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--royal-gold);
        font-size: 18px;
        flex-shrink: 0;
    }
    
    .contact-details {
        flex: 1;
    }
    
    .contact-details h4 {
        font-size: 1.1rem;
        color: var(--royal-dark);
        margin-bottom: 8px;
        font-weight: 600;
    }
    
    .contact-link {
        display: flex;
        align-items: center;
        color: var(--royal-text);
        text-decoration: none;
        padding: 8px 0;
        transition: var(--transition);
    }
    
    .contact-link:hover {
        color: var(--royal-gold);
        transform: translateX(5px);
    }
    
    .phone-numbers {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    
    .address-text {
        display: flex;
        align-items: flex-start;
        color: var(--royal-text-dark);
        line-height: 1.6;
    }
    
    .hours-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .hours-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .hours-item .day {
        color: var(--royal-text);
        font-weight: 500;
    }
    
    .hours-item .time {
        color: var(--royal-success);
        font-weight: 600;
    }
    
    .hours-item .closed {
        color: var(--royal-danger);
    }
    
    /* Social Links Grid */
    .social-links-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
    }
    
    .social-link {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px 10px;
        background: var(--royal-lighter);
        border: 1px solid rgba(212, 175, 55, 0.2);
        border-radius: var(--border-radius);
        color: var(--royal-text);
        text-decoration: none;
        transition: var(--transition);
    }
    
    .social-link:hover {
        transform: translateY(-5px);
        border-color: var(--royal-gold);
        box-shadow: var(--shadow);
    }
    
    .social-link i {
        font-size: 24px;
        margin-bottom: 10px;
    }
    
    .social-link span {
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .social-link.fb:hover {
        background: #4267B2;
        color: white;
    }
    
    .social-link.ig:hover {
        background: linear-gradient(45deg, #405DE6, #5851DB, #833AB4, #C13584, #E1306C, #FD1D1D);
        color: white;
    }
    
    .social-link.tw:hover {
        background: #1DA1F2;
        color: white;
    }
    
    .social-link.li:hover {
        background: #0077B5;
        color: white;
    }
    
    .social-link.yt:hover {
        background: #FF0000;
        color: white;
    }
    
    /* Contact Form Styles */
    .contact-form .form-group {
        margin-bottom: 1.5rem;
    }
    
    .input-with-icon, .textarea-with-icon {
        position: relative;
    }
    
    .input-with-icon i, .textarea-with-icon i {
        position: absolute;
        left: 20px;
        top: 18px;
        color: var(--royal-gold);
        z-index: 2;
    }
    
    .input-with-icon input, .textarea-with-icon textarea {
        padding-left: 50px !important;
    }
    
    .textarea-with-icon i {
        top: 20px;
    }
    
    .contact-form .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--royal-dark);
        font-size: 0.95rem;
    }
    
    .contact-form .form-control {
        background: var(--royal-lighter);
        border: 2px solid rgba(212, 175, 55, 0.2);
        border-radius: var(--border-radius);
        padding: 15px 20px;
        font-size: 16px;
        color: var(--royal-text);
        transition: var(--transition);
    }
    
    .contact-form .form-control:focus {
        background: white;
        border-color: var(--royal-gold);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }
    
    .contact-form .form-textarea {
        min-height: 150px;
        resize: vertical;
    }
    
    .contact-form .form-check {
        margin: 1.5rem 0;
    }
    
    .contact-form .form-check-input {
        margin-right: 10px;
        cursor: pointer;
    }
    
    .contact-form .form-check-input:checked {
        background-color: var(--royal-gold);
        border-color: var(--royal-gold);
    }
    
    .contact-form .form-check-label {
        color: var(--royal-text);
        cursor: pointer;
    }
    
    /* Map Container - UPDATED for better responsiveness */
    .map-container {
        border-radius: var(--border-radius);
        overflow: hidden;
        border: 2px solid rgba(212, 175, 55, 0.2);
        position: relative;
        padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
        height: 0;
        width: 100%;
    }
    
    .map-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100% !important;
        height: 100% !important;
        border: none !important;
    }
    
    /* Ensure iframe is responsive */
    iframe[src*="google.com/maps"] {
        width: 100% !important;
        height: 400px !important;
    }
    
    /* Royal Toast */
    .royal-toast {
        position: fixed;
        top: 100px;
        right: 30px;
        padding: 20px 25px;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
        display: flex;
        align-items: center;
        gap: 15px;
        z-index: 2000;
        animation: slideInRight 0.5s ease-out;
        max-width: 400px;
        border-left: 4px solid;
        background: white;
        border: 1px solid rgba(212, 175, 55, 0.1);
    }
    
    .toast-success {
        border-left-color: var(--royal-success);
    }
    
    .toast-error {
        border-left-color: var(--royal-danger);
    }
    
    .toast-icon {
        font-size: 24px;
        color: var(--royal-gold);
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .contact-hero .hero-title {
            font-size: 2.8rem;
        }
        
        .social-links-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .map-container {
            padding-bottom: 75%; /* Adjust aspect ratio for mobile */
        }
    }
    
    @media (max-width: 768px) {
        .contact-hero {
            padding: 60px 0 30px;
        }
        
        .contact-hero .hero-title {
            font-size: 2.3rem;
        }
        
        .contact-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .social-links-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .royal-toast {
            left: 20px;
            right: 20px;
            max-width: none;
        }
    }
    
    @media (max-width: 576px) {
        .contact-hero .hero-title {
            font-size: 2rem;
        }
        
        .social-links-grid {
            grid-template-columns: 1fr;
        }
        
        .hours-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
        
        .map-container {
            padding-bottom: 100%; /* Square aspect ratio for mobile */
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide toast messages
        const toasts = document.querySelectorAll('.royal-toast');
        toasts.forEach(toast => {
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 5000);
        });
        
        // Form validation
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                let isValid = true;
                const requiredFields = contactForm.querySelectorAll('[required]');
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.style.borderColor = 'var(--royal-danger)';
                    } else {
                        field.style.borderColor = '';
                    }
                    
                    // Email validation
                    if (field.type === 'email') {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(field.value)) {
                            isValid = false;
                            field.style.borderColor = 'var(--royal-danger)';
                        }
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    showToast('Please fill in all required fields correctly.', 'error');
                }
            });
        }
        
        // Phone number formatting
        const phoneInput = document.getElementById('contactPhone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 10) {
                    value = value.substring(0, 10);
                }
                
                // Format: XXX-XXX-XXXX
                if (value.length > 6) {
                    value = value.substring(0, 3) + '-' + value.substring(3, 6) + '-' + value.substring(6);
                } else if (value.length > 3) {
                    value = value.substring(0, 3) + '-' + value.substring(3);
                }
                
                e.target.value = value;
            });
        }
        
        // Make Google Maps iframe responsive
        const mapIframes = document.querySelectorAll('.map-container iframe');
        mapIframes.forEach(iframe => {
            // Remove fixed width and height attributes
            iframe.removeAttribute('width');
            iframe.removeAttribute('height');
            
            // Add responsive style if not already present
            if (!iframe.getAttribute('style') || iframe.getAttribute('style').indexOf('width') === -1) {
                iframe.style.width = '100%';
                iframe.style.height = '400px';
                iframe.style.border = '0';
            }
        });
        
        // Show toast function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `royal-toast toast-${type}`;
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} toast-icon"></i>
                <div>
                    <strong class="text-gold">${type === 'success' ? 'Success!' : 'Error!'}</strong>
                    <p>${message}</p>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    });
</script>

<?php include 'footer.php'; ?>