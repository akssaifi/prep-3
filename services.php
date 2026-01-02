<?php
require_once 'config.php';
$settings = getSettings($conn);

include 'header.php';
?>

<div class="container-custom">
    <!-- Hero Section -->
    <section class="py-8">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <span class="badge bg-gold-transparent text-gold fs-6 px-4 py-2 mb-4 rounded-pill">
                    <i class="fas fa-crown me-2"></i> Premium Services
                </span>
                <h1 class="display-3 fw-bold mb-4">Comprehensive Academic & Immigration Support</h1>
                <p class="lead text-muted mb-5">We provide end-to-end guidance for your educational journey, from study preparation to visa processing and beyond.</p>
                
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="#main-services" class="btn-royal">
                        <i class="fas fa-list me-2"></i> Explore Services
                    </a>
                    <a href="contact.php" class="btn btn-outline-gold">
                        <i class="fas fa-calendar-check me-2"></i> Free Consultation
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="main-services" class="py-6">
        <div class="text-center mb-6">
            <h2 class="display-5 fw-bold mb-3">Our Premium Services</h2>
            <p class="text-muted lead">Comprehensive support for every step of your academic journey</p>
        </div>

        <div class="row g-5">
            <!-- Service 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="service-card border-royal-gold rounded-4 p-5 h-100">
                    <div class="service-icon mb-4">
                        <div class="icon-circle-lg bg-gold-transparent">
                            <i class="fas fa-passport text-gold fs-2"></i>
                        </div>
                    </div>
                    <h3 class="h3 fw-bold mb-3">WES/ECA Assistance</h3>
                    <p class="text-muted mb-4">
                        Complete Educational Credential Assessment guidance for Canada immigration.
                        Professional document preparation, submission, and follow-up services.
                    </p>
                    <div class="service-features mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-check-circle text-success fs-6"></i>
                            <span class="text-muted">Document Verification</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-check-circle text-success fs-6"></i>
                            <span class="text-muted">Application Submission</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle text-success fs-6"></i>
                            <span class="text-muted">Follow-up Support</span>
                        </div>
                    </div>
                    <a href="contact.php" class="btn-service w-100">
                        <i class="fas fa-rocket me-2"></i> Get Started
                    </a>
                </div>
            </div>

            <!-- Service 2 -->
            <div class="col-lg-4 col-md-6">
                <div class="service-card border-royal-gold rounded-4 p-5 h-100">
                    <div class="service-icon mb-4">
                        <div class="icon-circle-lg bg-gold-transparent">
                            <i class="fas fa-file-alt text-gold fs-2"></i>
                        </div>
                    </div>
                    <h3 class="h3 fw-bold mb-3">SOP & LOR Writing</h3>
                    <p class="text-muted mb-4">
                        Professional Statement of Purpose and Letter of Recommendation writing.
                        Customized, impactful documents for university and visa applications.
                    </p>
                    <div class="service-features mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-check-circle text-success fs-6"></i>
                            <span class="text-muted">Custom Content Writing</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-check-circle text-success fs-6"></i>
                            <span class="text-muted">Multiple Revisions</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle text-success fs-6"></i>
                            <span class="text-muted">Expert Review</span>
                        </div>
                    </div>
                    <a href="contact.php" class="btn-service w-100">
                        <i class="fas fa-rocket me-2"></i> Get Started
                    </a>
                </div>
            </div>

            <!-- Service 3 -->
            <div class="col-lg-4 col-md-6">
                <div class="service-card border-royal-gold rounded-4 p-5 h-100">
                    <div class="service-icon mb-4">
                        <div class="icon-circle-lg bg-gold-transparent">
                            <i class="fas fa-graduation-cap text-gold fs-2"></i>
                        </div>
                    </div>
                    <h3 class="h3 fw-bold mb-3">Assignment & Dissertation</h3>
                    <p class="text-muted mb-4">
                        Expert academic writing assistance for assignments, thesis, and dissertations.
                        Research, writing, editing, and proofreading services.
                    </p>
                    <div class="service-features mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-check-circle text-success fs-6"></i>
                            <span class="text-muted">Research Assistance</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-check-circle text-success fs-6"></i>
                            <span class="text-muted">Plagiarism-Free Content</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle text-success fs-6"></i>
                            <span class="text-muted">Timely Delivery</span>
                        </div>
                    </div>
                    <a href="contact.php" class="btn-service w-100">
                        <i class="fas fa-rocket me-2"></i> Get Started
                    </a>
                </div>
            </div>

            <!-- Service 4 -->
            <div class="col-lg-4 col-md-6">
                <div class="service-card border-royal-gold rounded-4 p-5 h-100">
                    <div class="service-icon mb-4">
                        <div class="icon-circle-lg bg-gold-transparent">
                            <i class="fas fa-pen-fancy text-gold fs-2"></i>
                        </div>
                    </div>
                    <h3 class="h3 fw-bold mb-3">Content Writing</h3>
                    <p class="text-muted mb-4">
                        High-quality content writing for websites, blogs, articles, and marketing materials.
                        SEO optimized, engaging, and professional content creation.
                    </p>
                    <div class="service-features mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-check-circle text-success fs-6"></i>
                            <span class="text-muted">SEO Optimization</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-check-circle text-success fs-6"></i>
                            <span class="text-muted">Keyword Research</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle text-success fs-6"></i>
                            <span class="text-muted">Content Strategy</span>
                        </div>
                    </div>
                    <a href="contact.php" class="btn-service w-100">
                        <i class="fas fa-rocket me-2"></i> Get Started
                    </a>
                </div>
            </div>

            <!-- Service 5 -->
            <div class="col-lg-4 col-md-6">
                <div class="service-card border-royal-gold rounded-4 p-5 h-100">
                    <div class="service-icon mb-4">
                        <div class="icon-circle-lg bg-gold-transparent">
                            <i class="fas fa-hands-helping text-gold fs-2"></i>
                        </div>
                    </div>
                    <h3 class="h3 fw-bold mb-3">Academic Support</h3>
                    <p class="text-muted mb-4">
                        One-on-one tutoring, homework help, and academic support across various subjects.
                        Personalized learning plans and progress tracking.
                    </p>
                    <div class="service-features mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-check-circle text-success fs-6"></i>
                            <span class="text-muted">Personalized Tutoring</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-check-circle text-success fs-6"></i>
                            <span class="text-muted">Homework Assistance</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle text-success fs-6"></i>
                            <span class="text-muted">Progress Reports</span>
                        </div>
                    </div>
                    <a href="contact.php" class="btn-service w-100">
                        <i class="fas fa-rocket me-2"></i> Get Started
                    </a>
                </div>
            </div>

            <!-- Service 6 -->
            <div class="col-lg-4 col-md-6">
                <div class="service-card border-royal-gold rounded-4 p-5 h-100">
                    <div class="service-icon mb-4">
                        <div class="icon-circle-lg bg-gold-transparent">
                            <i class="fas fa-headset text-gold fs-2"></i>
                        </div>
                    </div>
                    <h3 class="h3 fw-bold mb-3">Career Counseling</h3>
                    <p class="text-muted mb-4">
                        Professional career guidance, resume building, interview preparation, and career path planning.
                        Get expert advice for your professional growth.
                    </p>
                    <div class="service-features mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-check-circle text-success fs-6"></i>
                            <span class="text-muted">Resume Building</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-check-circle text-success fs-6"></i>
                            <span class="text-muted">Interview Preparation</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle text-success fs-6"></i>
                            <span class="text-muted">Career Planning</span>
                        </div>
                    </div>
                    <a href="contact.php" class="btn-service w-100">
                        <i class="fas fa-rocket me-2"></i> Get Started
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Study Abroad Section -->
    <section class="py-8 bg-light-section">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="text-center mb-6">
                    <span class="badge bg-gold-transparent text-gold fs-6 px-4 py-2 mb-4 rounded-pill">
                        <i class="fas fa-globe-americas me-2"></i> Study Abroad
                    </span>
                    <h2 class="display-4 fw-bold mb-4">Complete Study Abroad Guidance</h2>
                    <p class="lead text-muted">Expert guidance for international education opportunities in top destinations</p>
                </div>

                <div class="row g-5">
                    <!-- Canada -->
                    <div class="col-md-4">
                        <div class="country-card bg-white rounded-4 p-5 h-100 text-center">
                            <div class="country-flag mb-4">
                                <div class="flag-circle bg-gold-transparent">
                                    <i class="fas fa-flag-usa text-gold fs-1"></i>
                                </div>
                            </div>
                            <h4 class="h4 fw-bold mb-3">Canada Immigration</h4>
                            <p class="text-muted mb-4">
                                Comprehensive support for study permits, PR applications, work permits, and complete immigration process assistance.
                            </p>
                            <div class="d-grid">
                                <a href="contact.php" class="btn btn-outline-gold">
                                    <i class="fas fa-plane-departure me-2"></i> Explore Canada
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- UK -->
                    <div class="col-md-4">
                        <div class="country-card bg-white rounded-4 p-5 h-100 text-center">
                            <div class="country-flag mb-4">
                                <div class="flag-circle bg-gold-transparent">
                                    <i class="fas fa-flag text-gold fs-1"></i>
                                </div>
                            </div>
                            <h4 class="h4 fw-bold mb-3">UK Study Guidance</h4>
                            <p class="text-muted mb-4">
                                Complete student visa applications, university selection, scholarship guidance, and accommodation support for UK.
                            </p>
                            <div class="d-grid">
                                <a href="contact.php" class="btn btn-outline-gold">
                                    <i class="fas fa-plane-departure me-2"></i> Explore UK
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Australia -->
                    <div class="col-md-4">
                        <div class="country-card bg-white rounded-4 p-5 h-100 text-center">
                            <div class="country-flag mb-4">
                                <div class="flag-circle bg-gold-transparent">
                                    <i class="fas fa-flag-australia text-gold fs-1"></i>
                                </div>
                            </div>
                            <h4 class="h4 fw-bold mb-3">Australia Study</h4>
                            <p class="text-muted mb-4">
                                Australian student visa process, course selection, living arrangements, and post-study work opportunities guidance.
                            </p>
                            <div class="d-grid">
                                <a href="contact.php" class="btn btn-outline-gold">
                                    <i class="fas fa-plane-departure me-2"></i> Explore Australia
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-6">
                    <div class="cta-box bg-white rounded-4 p-5 shadow-lg">
                        <h3 class="h3 fw-bold mb-3">Ready to Start Your Journey?</h3>
                        <p class="text-muted mb-4">Book a free consultation with our experts to discuss your specific needs and requirements.</p>
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <a href="contact.php" class="btn-royal">
                                <i class="fas fa-calendar-check me-2"></i> Book Free Consultation
                            </a>
                            <?php if(!empty($settings['whatsapp_number'])): ?>
                            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $settings['whatsapp_number']); ?>?text=I'm%20interested%20in%20your%20services" 
                               class="btn-whatsapp-royal" target="_blank">
                                <i class="fab fa-whatsapp me-2"></i> WhatsApp Us
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    /* Services Page Styles */
    .py-6 {
        padding-top: 4rem !important;
        padding-bottom: 4rem !important;
    }
    
    .py-8 {
        padding-top: 6rem !important;
        padding-bottom: 6rem !important;
    }

    .bg-gold-transparent {
        background: rgba(212, 175, 55, 0.1) !important;
        border: 1px solid rgba(212, 175, 55, 0.2) !important;
    }

    .border-royal-gold {
        border-color: rgba(212, 175, 55, 0.2) !important;
    }

    .text-gold {
        color: #D4AF37 !important;
    }

    .bg-light-section {
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.03), rgba(212, 175, 55, 0.01));
    }

    .service-card {
        background: white;
        border: 1px solid rgba(212, 175, 55, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .service-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #D4AF37, #FFD700);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(212, 175, 55, 0.15) !important;
        border-color: #D4AF37;
    }

    .service-card:hover::before {
        transform: scaleX(1);
    }

    .country-card {
        border: 1px solid rgba(212, 175, 55, 0.1);
        transition: all 0.3s ease;
    }

    .country-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
        border-color: #D4AF37;
    }

    .icon-circle-lg {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .flag-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        border: 2px solid rgba(212, 175, 55, 0.2);
    }

    .btn-royal {
        background: linear-gradient(135deg, #D4AF37, #FFD700);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
    }

    .btn-royal:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3);
        color: white;
    }

    .btn-outline-gold {
        color: #D4AF37;
        border: 2px solid #D4AF37;
        background: transparent;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-gold:hover {
        background: linear-gradient(135deg, #D4AF37, #FFD700);
        color: white;
        border-color: transparent;
        transform: translateY(-3px);
    }

    .btn-service {
        background: linear-gradient(135deg, #D4AF37, #FFD700);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .btn-service:hover {
        background: linear-gradient(135deg, #B7950B, #D4AF37);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
    }

    .btn-whatsapp-royal {
        background: #25D366;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
    }

    .btn-whatsapp-royal:hover {
        background: #128C7E;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(37, 211, 102, 0.3);
    }

    .cta-box {
        border: 2px solid rgba(212, 175, 55, 0.2);
        background: white;
        max-width: 800px;
        margin: 0 auto;
    }

    .service-features {
        border-top: 1px solid rgba(212, 175, 55, 0.1);
        padding-top: 1rem;
    }

    @media (max-width: 768px) {
        .display-3 {
            font-size: 2.5rem;
        }
        
        .display-4 {
            font-size: 2rem;
        }
        
        .display-5 {
            font-size: 1.8rem;
        }
        
        .py-6 {
            padding-top: 2.5rem !important;
            padding-bottom: 2.5rem !important;
        }
        
        .py-8 {
            padding-top: 3rem !important;
            padding-bottom: 3rem !important;
        }
        
        .service-card,
        .country-card {
            padding: 2rem !important;
        }
    }
</style>

<script>
    // Smooth scroll for anchor links
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                if(this.getAttribute('href') === '#') return;
                
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if(target) {
                    window.scrollTo({
                        top: target.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>

<?php include 'footer.php'; ?>