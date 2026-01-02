</main>

<!-- Ultimate Premium Footer -->
<footer class="bg-royal-dark text-white position-relative overflow-hidden">
    <!-- Background Elements -->
    <div class="footer-bg-elements">
        <div class="element element-1"></div>
        <div class="element element-2"></div>
        <div class="element element-3"></div>
        <div class="element element-4"></div>
    </div>
    
    <div class="container-custom position-relative z-1">
        <!-- Main Footer Content -->
        <div class="row g-5 py-6">
            <!-- About Column -->
            <div class="col-lg-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="logo-premium me-3">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-white"><?php echo htmlspecialchars($settings['site_name']); ?></h4>
                        <small class="text-white">Excellence in Education & Immigration</small>
                    </div>
                </div>
                
                <p class="text-white mb-4">
                    We provide world-class coaching for English proficiency tests, comprehensive language training, 
                    and expert guidance for study abroad programs and immigration processes.
                </p>
                
                <!-- Newsletter -->
                <?php if($settings['enable_newsletter']): ?>
                <div class="newsletter-form mb-4">
                    <h6 class="fw-bold mb-3 text-white">Subscribe to Newsletter</h6>
                    <form class="d-flex gap-2" id="newsletterForm">
                        <input type="email" class="form-control form-control-sm bg-royal-light border-royal-gray text-white" 
                               placeholder="Your email" required id="newsletterEmail">
                        <button type="submit" class="btn-royal btn-sm">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
                <?php endif; ?>
                
                <!-- Social Media -->
                <div class="social-links-premium">
                    <h6 class="fw-bold mb-3 text-white">Follow Us</h6>
                    <div class="d-flex gap-2">
                        <?php if($settings['facebook_url'] && $settings['show_facebook']): ?>
                        <a href="<?php echo $settings['facebook_url']; ?>" class="social-link-premium" target="_blank" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if($settings['instagram_url'] && $settings['show_instagram'] ): ?>
                        <a href="<?php echo $settings['instagram_url']; ?>" class="social-link-premium" target="_blank" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if($settings['twitter_url'] && $settings['show_twitter']): ?>
                        <a href="<?php echo $settings['twitter_url']; ?>" class="social-link-premium" target="_blank" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if($settings['linkedin_url'] && $settings['show_linkedin']): ?>
                        <a href="<?php echo $settings['linkedin_url']; ?>" class="social-link-premium" target="_blank" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if($settings['youtube_url'] && $settings['show_youtube'] ): ?>
                        <a href="<?php echo $settings['youtube_url']; ?>" class="social-link-premium" target="_blank" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold mb-4 text-uppercase text-white">Quick Links</h5>
                <ul class="list-unstyled footer-links">
                    <li class="mb-3">
                        <a href="index.php" class="text-white text-decoration-none d-flex align-items-center gap-3 hover-lift">
                            <i class="fas fa-chevron-right fa-xs text-white"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="all_courses.php" class="text-white text-decoration-none d-flex align-items-center gap-3 hover-lift">
                            <i class="fas fa-chevron-right fa-xs text-white"></i>
                            <span>Courses</span>
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="services.php" class="text-white text-decoration-none d-flex align-items-center gap-3 hover-lift">
                            <i class="fas fa-chevron-right fa-xs text-white"></i>
                            <span>Services</span>
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="resources.php" class="text-white text-decoration-none d-flex align-items-center gap-3 hover-lift">
                            <i class="fas fa-chevron-right fa-xs text-white"></i>
                            <span>Resources</span>
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="about.php" class="text-white text-decoration-none d-flex align-items-center gap-3 hover-lift">
                            <i class="fas fa-chevron-right fa-xs text-white"></i>
                            <span>About Us</span>
                        </a>
                    </li>
                    <li>
                        <a href="contact.php" class="text-white text-decoration-none d-flex align-items-center gap-3 hover-lift">
                            <i class="fas fa-chevron-right fa-xs text-white"></i>
                            <span>Contact</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Popular Courses -->
            <div class="col-lg-3 col-md-6">
                <h5 class="fw-bold mb-4 text-uppercase text-white">Popular Courses</h5>
                <ul class="list-unstyled footer-links">
                    <?php
                    $popular_courses = mysqli_query($conn, "SELECT name, slug FROM courses WHERE is_popular = 1 AND status = 'active' ORDER BY sort_order DESC LIMIT 5");
                    while($course = mysqli_fetch_assoc($popular_courses)):
                    ?>
                    <li class="mb-3">
                        <a href="courses.php?slug=<?php echo $course['slug']; ?>" class="text-white text-decoration-none d-flex align-items-center gap-3 hover-lift">
                            <i class="fas fa-book text-white fa-xs"></i>
                            <span><?php echo htmlspecialchars($course['name']); ?></span>
                        </a>
                    </li>
                    <?php endwhile; ?>
                    
                    <li>
                        <a href="all_courses.php" class="text-white text-decoration-none d-flex align-items-center gap-3 hover-lift fw-bold">
                            <i class="fas fa-plus"></i>
                            <span>View All Courses</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6">
                <h5 class="fw-bold mb-4 text-uppercase text-white">Contact Info</h5>
                <ul class="list-unstyled contact-info">
                    <li class="mb-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <a href="tel:<?php echo htmlspecialchars($settings['primary_phone']); ?>" class="text-white text-decoration-none d-block mb-1 hover-lift">
                                    <?php echo htmlspecialchars($settings['primary_phone']); ?>
                                </a>
                                <?php if($settings['secondary_phone']): ?>
                                <a href="tel:<?php echo htmlspecialchars($settings['secondary_phone']); ?>" class="text-white text-decoration-none d-block hover-lift">
                                    <?php echo htmlspecialchars($settings['secondary_phone']); ?>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </li>
                    
                    <li class="mb-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                 <a href="mailto:<?php echo htmlspecialchars($settings['email']); ?>" class="text-white text-decoration-none hover-lift">
                                    <i class="fas fa-envelope me-2"></i> <?php echo htmlspecialchars($settings['email']); ?>
                                </a>
                            </div>
                        </div>
                    </li>
                    
                    <?php if($settings['whatsapp_number'] && $settings['enable_whatsapp_chat']): ?>
                    <li class="mb-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="contact-icon">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div>
                                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $settings['whatsapp_number']); ?>" 
                                   class="text-white text-decoration-none hover-lift" target="_blank">
                                    <?php echo htmlspecialchars($settings['whatsapp_number']); ?>
                                </a>
                            </div>
                        </div>
                    </li>
                    <?php endif; ?>
                    
                    <?php if($settings['address']): ?>
                    <li>
                        <div class="d-flex align-items-start gap-3">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <span class="text-white"><?php echo htmlspecialchars($settings['address']); ?></span>
                            </div>
                        </div>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="border-top border-royal-gray pt-5">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-royal-text-dark">
                        &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($settings['site_name']); ?>. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex justify-content-md-end gap-4">
                        
                        <a href="privacy.php" class="text-royal-text-dark text-decoration-none hover-lift">
                            Privacy Policy
                        </a>
                        <a href="terms.php" class="text-royal-text-dark text-decoration-none hover-lift">
                            Terms of Service
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Contact Form Processor -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Newsletter form submission
        const newsletterForm = document.getElementById('newsletterForm');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const email = document.getElementById('newsletterEmail').value;
                
                if(!email || !email.includes('@')) {
                    showAlert('Please enter a valid email address.', 'error');
                    return;
                }
                
                // Submit via AJAX
                const formData = new FormData();
                formData.append('email', email);
                formData.append('action', 'newsletter_subscribe');
                
                fetch('process_contact.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('Thank you for subscribing to our newsletter!', 'success');
                        newsletterForm.reset();
                    } else {
                        showAlert(data.message || 'Subscription failed. Please try again.', 'error');
                    }
                })
                .catch(error => {
                    showAlert('Network error. Please try again.', 'error');
                });
            });
        }
        
        // Demo form submission (from index.php)
        const demoForm = document.getElementById('premiumDemoForm');
        if (demoForm && !demoForm.hasAttribute('data-processed')) {
            demoForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                formData.append('action', 'demo_request');
                
                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
                submitBtn.disabled = true;
                
                // Submit via AJAX
                fetch('process_contact.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('Demo request submitted successfully! We will contact you shortly.', 'success');
                        this.reset();
                    } else {
                        showAlert(data.message || 'Submission failed. Please try again.', 'error');
                    }
                    
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                })
                .catch(error => {
                    showAlert('Network error. Please try again.', 'error');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
            demoForm.setAttribute('data-processed', 'true');
        }
        
        // Alert function
        function showAlert(message, type) {
            // Remove existing alerts
            document.querySelectorAll('.alert-royal').forEach(alert => alert.remove());
            
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert-royal alert-${type}`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
                <span>${message}</span>
                <button class="alert-close">&times;</button>
            `;
            
            document.body.appendChild(alertDiv);
            
            // Remove alert after 5 seconds
            setTimeout(() => {
                alertDiv.classList.add('fade-out');
                setTimeout(() => alertDiv.remove(), 300);
            }, 5000);
            
            // Close button
            alertDiv.querySelector('.alert-close').addEventListener('click', () => {
                alertDiv.classList.add('fade-out');
                setTimeout(() => alertDiv.remove(), 300);
            });
        }
    });
    
    // Footer animations
    document.querySelectorAll('.hover-lift').forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.opacity = '1';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.opacity = '0.9';
        });
    });
    
    // Social link animations
    document.querySelectorAll('.social-link-premium').forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) rotate(5deg)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) rotate(0)';
        });
    });
    
    // Scroll to top button
    const scrollTopBtn = document.createElement('button');
    scrollTopBtn.innerHTML = '<i class="fas fa-chevron-up"></i>';
    scrollTopBtn.className = 'btn-royal scroll-top-btn';
    scrollTopBtn.style.cssText = 'position:fixed;bottom:100px;right:30px;z-index:999;width:50px;height:50px;border-radius:50%;display:none;align-items:center;justify-content:center;';
    
    document.body.appendChild(scrollTopBtn);
    
    scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    
    window.addEventListener('scroll', () => {
        if(window.scrollY > 500) {
            scrollTopBtn.style.display = 'flex';
        } else {
            scrollTopBtn.style.display = 'none';
        }
    });
</script>

<!-- PHPMailer Contact Processor -->
<?php
// Create process_contact.php file separately
?>

<style>
    footer{
            background: var(--royal-darker);
    }
    /* Footer Styles */
    .footer-bg-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 0;
    }
    
    .element {
        position: absolute;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(212, 175, 55, 0.05));
        filter: blur(40px);
    }
    
    .element-1 {
        width: 300px;
        height: 300px;
        top: -100px;
        left: -100px;
    }
    
    .element-2 {
        width: 200px;
        height: 200px;
        bottom: -50px;
        right: -50px;
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05));
    }
    
    .element-3 {
        width: 150px;
        height: 150px;
        top: 50%;
        left: 30%;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
    }
    
    .element-4 {
        width: 100px;
        height: 100px;
        bottom: 30%;
        right: 30%;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));
    }
    
    .social-link-premium {
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        transition: var(--transition);
        text-decoration: none;
    }
    
    .social-link-premium:hover {
        background: var(--royal-gold);
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3);
        color: var(--royal-dark);
    }
    
    .contact-icon {
        width: 40px;
        height: 40px;
        background: rgba(212, 175, 55, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .footer-links a,
    .contact-info a {
        opacity: 0.9;
        transition: all 0.3s ease;
    }
    
    .footer-links a:hover,
    .contact-info a:hover {
        opacity: 1;
        color: var(--royal-gold-light);
    }
    
    .hover-lift {
        transition: transform 0.3s ease, opacity 0.3s ease;
    }
    
    .py-6 {
        padding-top: 6rem !important;
        padding-bottom: 6rem !important;
    }
    
    @media (max-width: 768px) {
        .py-6 {
            padding-top: 4rem !important;
            padding-bottom: 4rem !important;
        }
        
        .social-link-premium {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        
        .scroll-top-btn {
            bottom: 80px;
            right: 20px;
            width: 45px;
            height: 45px;
        }
        
        .footer-links,
        .contact-info {
            margin-bottom: 2rem;
        }
    }
</style>
</body>
</html>
