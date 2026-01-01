<?php
require_once 'config.php';
$settings = getSettings($conn);

// Get some stats for the about page
$total_courses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM courses WHERE status='active'"))['count'];
$total_students = 5000; // You can replace with actual database field if available
$years_experience = date('Y') - 2004; // Automatically calculates years since 2004
$success_rate = 98; // Percentage

include 'header.php';
?>

<!-- Page Header -->
<section class="hero-section about-hero">
    <div class="container-custom">
        <div class="hero-content text-center py-5">
            <div class="hero-badge mb-4">
                <span class="badge-gold">Since 2004</span>
            </div>
            <h1 class="hero-title mb-3">About Daljeet Singh</h1>
            <p class="hero-subtitle mb-4">Two decades of excellence in English education and test preparation</p>
            <div class="hero-buttons">
                <a href="#journey" class="btn-royal">
                    <i class="fas fa-history me-2"></i> Our Journey
                </a>
                <a href="#mission" class="btn-outline-gold">
                    <i class="fas fa-bullseye me-2"></i> Our Mission
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container-custom">
        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-value"><?php echo $years_experience; ?>+</div>
                    <div class="stat-label">Years Experience</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="stat-value"><?php echo $total_students; ?>+</div>
                    <div class="stat-label">Students Trained</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="stat-value"><?php echo $total_courses; ?>+</div>
                    <div class="stat-label">Courses Offered</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-value"><?php echo $success_rate; ?>%</div>
                    <div class="stat-label">Success Rate</div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container-custom py-5">
    <!-- Founder's Story -->
    <section class="section-padding" id="journey">
        <div class="row align-items-center g-5 mb-5">
            <div class="col-lg-6">
                <div class="founder-image-container">
                    <div class="founder-image-placeholder">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="founder-badge">
                        <span class="founder-badge-text">Founder & Lead Instructor</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-header mb-4">
                    <span class="section-subtitle">The Beginning</span>
                    <h2 class="section-title">Meet Daljeet Singh</h2>
                </div>
                <div class="story-timeline">
                    <div class="timeline-item">
                        <div class="timeline-year">2004</div>
                        <div class="timeline-content">
                            <h4>The Journey Begins</h4>
                            <p>Daljeet Singh started his teaching career with a passion for English language and a vision to help students succeed in international exams. What began as small tutoring sessions quickly grew into a reputable coaching center.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2008</div>
                        <div class="timeline-content">
                            <h4>First Major Success</h4>
                            <p>After four years of dedicated teaching, Daljeet achieved his first batch of students with perfect IELTS scores. This milestone established "Prep with Daljeet" as a trusted name in test preparation.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2015</div>
                        <div class="timeline-content">
                            <h4>Expansion & Innovation</h4>
                            <p>Recognizing the changing educational landscape, Daljeet embraced digital learning. He started creating online courses and study materials, making quality education accessible to students across India.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">Present</div>
                        <div class="timeline-content">
                            <h4>Continuing Excellence</h4>
                            <p>With nearly two decades of experience, Daljeet continues to innovate in teaching methodologies while maintaining the personal touch that has made his coaching so successful.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="section-padding" id="mission">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="card-royal h-100">
                    <div class="card-header-royal">
                        <div class="mission-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3 class="card-title-royal">Our Mission</h3>
                    </div>
                    <div class="card-body-royal">
                        <p class="mission-text">
                            To provide exceptional English language education and test preparation that empowers students to achieve their academic and professional goals globally.
                        </p>
                        <ul class="mission-list">
                            <li><i class="fas fa-check-circle text-gold"></i> Deliver personalized, effective teaching</li>
                            <li><i class="fas fa-check-circle text-gold"></i> Stay updated with latest exam patterns</li>
                            <li><i class="fas fa-check-circle text-gold"></i> Make quality education accessible to all</li>
                            <li><i class="fas fa-check-circle text-gold"></i> Foster confidence in English communication</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-royal h-100">
                    <div class="card-header-royal">
                        <div class="mission-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3 class="card-title-royal">Our Vision</h3>
                    </div>
                    <div class="card-body-royal">
                        <p class="mission-text">
                            To be the most trusted and effective English education platform in India, recognized for transforming language learning experiences and producing outstanding results.
                        </p>
                        <ul class="mission-list">
                            <li><i class="fas fa-star text-gold"></i> Create future leaders in global communication</li>
                            <li><i class="fas fa-star text-gold"></i> Set new standards in test preparation</li>
                            <li><i class="fas fa-star text-gold"></i> Expand reach through technology</li>
                            <li><i class="fas fa-star text-gold"></i> Build a community of successful learners</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Teaching Philosophy -->
    <section class="section-padding">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <div class="section-header mb-4">
                    <span class="section-subtitle">Our Approach</span>
                    <h2 class="section-title">Teaching Philosophy</h2>
                    <p class="section-description">The principles that guide our teaching methodology</p>
                </div>
                <div class="philosophy-quote">
                    <div class="quote-icon">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <blockquote class="quote-text">
                        "Teaching isn't just about imparting knowledge; it's about inspiring confidence and unlocking potential. Every student has a unique journey, and my role is to guide them to their destination."
                        <footer class="quote-author">— Daljeet Singh</footer>
                    </blockquote>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="philosophy-card">
                            <div class="philosophy-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <h4>Student-Centered Learning</h4>
                            <p>We adapt our teaching to individual learning styles, ensuring each student gets personalized attention and support.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="philosophy-card">
                            <div class="philosophy-icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <h4>Practical Application</h4>
                            <p>We focus on real-world language use, preparing students for actual communication scenarios they'll encounter.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="philosophy-card">
                            <div class="philosophy-icon">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <h4>Result-Oriented Approach</h4>
                            <p>Every lesson, practice test, and feedback session is designed to maximize improvement and achieve target scores.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="philosophy-card">
                            <div class="philosophy-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h4>Passion-Driven Teaching</h4>
                            <p>Our commitment comes from a genuine passion for teaching and seeing students succeed beyond their expectations.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Stories -->
    <section class="section-padding bg-light">
        <div class="container-custom">
            <div class="section-header text-center mb-5">
                <span class="section-subtitle">Student Achievements</span>
                <h2 class="section-title">Success Stories</h2>
                <p class="section-description">Transforming lives through education</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="success-card">
                        <div class="success-score">
                            <span class="score-badge">9.0</span>
                            <span class="score-text">IELTS Band</span>
                        </div>
                        <div class="success-content">
                            <h4>Rohan Sharma</h4>
                            <p class="success-meta">Engineer, now at University of Toronto</p>
                            <p class="success-quote">"Daljeet Sir's speaking techniques helped me achieve a perfect 9 in Speaking. His mock tests were exactly like the real exam!"</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="success-card">
                        <div class="success-score">
                            <span class="score-badge">118</span>
                            <span class="score-text">TOEFL Score</span>
                        </div>
                        <div class="success-content">
                            <h4>Priya Patel</h4>
                            <p class="success-meta">Medical Student, Harvard University</p>
                            <p class="success-quote">"The writing templates and vocabulary building sessions were game-changers. I improved my score by 25 points in 3 months!"</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="success-card">
                        <div class="success-score">
                            <span class="score-badge">8.5</span>
                            <span class="score-text">IELTS Band</span>
                        </div>
                        <div class="success-content">
                            <h4>Amit Verma</h4>
                            <p class="success-meta">Business Consultant, London</p>
                            <p class="success-quote">"After 3 attempts, I finally achieved my target score. Daljeet Sir's personalized feedback made all the difference."</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="section-padding">
        <div class="container-custom text-center">
            <div class="cta-card">
                <div class="cta-content">
                    <h2 class="cta-title mb-3">Ready to Start Your Journey?</h2>
                    <p class="cta-text mb-4">Join thousands of successful students who have achieved their language goals with Daljeet Singh</p>
                    <div class="cta-buttons">
                        <a href="all_courses.php" class="btn-royal">
                            <i class="fas fa-graduation-cap me-2"></i> Browse Courses
                        </a>
                        <a href="contact.php" class="btn-outline-gold">
                            <i class="fas fa-calendar-check me-2"></i> Free Consultation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    /* About Page Specific Styles */
    .about-hero {
        background: linear-gradient(rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.95)), 
                    url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80');
        background-size: cover;
        background-position: center;
        padding: 80px 0 40px;
        margin-top: 0;
    }
    
    .about-hero .hero-title {
        font-size: 3.5rem;
        color: var(--royal-darker);
    }
    
    /* Stats Section */
    .stats-section {
        background: linear-gradient(135deg, var(--royal-white), var(--royal-lighter));
        padding: 60px 0;
        border-bottom: 1px solid rgba(212, 175, 55, 0.1);
    }
    
    .stat-card {
        text-align: center;
        padding: 30px 20px;
        background: var(--royal-white);
        border-radius: var(--border-radius-lg);
        border: 2px solid rgba(212, 175, 55, 0.2);
        transition: var(--transition);
    }
    
    .stat-card:hover {
        transform: translateY(-10px);
        border-color: var(--royal-gold);
        box-shadow: var(--shadow-lg);
    }
    
    .stat-icon {
        width: 70px;
        height: 70px;
        background: rgba(212, 175, 55, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--royal-gold);
        font-size: 28px;
        margin: 0 auto 20px;
        border: 2px solid rgba(212, 175, 55, 0.3);
    }
    
    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--royal-dark);
        margin-bottom: 5px;
        font-family: 'Cinzel', serif;
    }
    
    .stat-label {
        font-size: 0.9rem;
        color: var(--royal-text-dark);
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }
    
    /* Founder Section */
    .founder-image-container {
        position: relative;
        padding: 20px;
    }
    
    .founder-image-placeholder {
        width: 100%;
        height: 400px;
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(255, 215, 0, 0.1));
        border-radius: var(--border-radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 100px;
        color: var(--royal-gold);
        border: 3px solid var(--royal-gold);
    }
    
    .founder-badge {
        position: absolute;
        bottom: 30px;
        left: -10px;
        background: var(--gold-gradient);
        padding: 15px 25px;
        border-radius: var(--border-radius);
        color: var(--royal-white);
        font-weight: 600;
        box-shadow: var(--shadow);
    }
    
    .founder-badge::after {
        content: '';
        position: absolute;
        right: -10px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        background: var(--royal-gold-dark);
        clip-path: polygon(0 0, 100% 50%, 0 100%);
    }
    
    /* Timeline */
    .story-timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .story-timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: var(--gold-gradient);
        border-radius: 3px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 40px;
    }
    
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    
    .timeline-year {
        position: absolute;
        left: -55px;
        top: 0;
        background: var(--gold-gradient);
        color: var(--royal-white);
        padding: 8px 15px;
        border-radius: var(--border-radius-sm);
        font-weight: 700;
        font-size: 0.9rem;
        min-width: 80px;
        text-align: center;
        box-shadow: var(--shadow-sm);
    }
    
    .timeline-content {
        background: var(--royal-white);
        padding: 25px;
        border-radius: var(--border-radius);
        border: 1px solid rgba(212, 175, 55, 0.2);
        margin-left: 30px;
    }
    
    .timeline-content h4 {
        color: var(--royal-dark);
        margin-bottom: 10px;
        font-size: 1.3rem;
    }
    
    .timeline-content p {
        color: var(--royal-text-dark);
        margin-bottom: 0;
    }
    
    /* Mission & Vision */
    .mission-icon {
        width: 60px;
        height: 60px;
        background: rgba(212, 175, 55, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--royal-gold);
        font-size: 24px;
        margin-bottom: 20px;
    }
    
    .mission-text {
        font-size: 1.1rem;
        color: var(--royal-text);
        margin-bottom: 25px;
        line-height: 1.8;
    }
    
    .mission-list {
        list-style: none;
        padding: 0;
    }
    
    .mission-list li {
        padding: 8px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    /* Philosophy */
    .philosophy-quote {
        background: var(--royal-white);
        padding: 30px;
        border-radius: var(--border-radius);
        border-left: 5px solid var(--royal-gold);
        position: relative;
        margin-top: 30px;
    }
    
    .quote-icon {
        position: absolute;
        top: -20px;
        left: 30px;
        background: var(--royal-white);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--royal-gold);
        font-size: 20px;
        border: 2px solid var(--royal-gold);
    }
    
    .quote-text {
        font-style: italic;
        color: var(--royal-text);
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 15px;
    }
    
    .quote-author {
        color: var(--royal-gold);
        font-weight: 600;
        font-size: 1rem;
    }
    
    .philosophy-card {
        background: var(--royal-white);
        padding: 30px;
        border-radius: var(--border-radius);
        border: 1px solid rgba(212, 175, 55, 0.2);
        height: 100%;
        transition: var(--transition);
    }
    
    .philosophy-card:hover {
        transform: translateY(-5px);
        border-color: var(--royal-gold);
        box-shadow: var(--shadow);
    }
    
    .philosophy-icon {
        width: 50px;
        height: 50px;
        background: rgba(212, 175, 55, 0.1);
        border-radius: var(--border-radius);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--royal-gold);
        font-size: 20px;
        margin-bottom: 20px;
    }
    
    .philosophy-card h4 {
        color: var(--royal-dark);
        margin-bottom: 15px;
        font-size: 1.2rem;
    }
    
    .philosophy-card p {
        color: var(--royal-text-dark);
        margin-bottom: 0;
    }
    
    /* Success Stories */
    .bg-light {
        background: var(--royal-lighter) !important;
    }
    
    .success-card {
        background: var(--royal-white);
        border-radius: var(--border-radius);
        overflow: hidden;
        border: 1px solid rgba(212, 175, 55, 0.2);
        transition: var(--transition);
        height: 100%;
    }
    
    .success-card:hover {
        transform: translateY(-10px);
        border-color: var(--royal-gold);
        box-shadow: var(--shadow-lg);
    }
    
    .success-score {
        background: var(--gold-gradient);
        color: var(--royal-white);
        padding: 20px;
        text-align: center;
    }
    
    .score-badge {
        display: block;
        font-size: 2.5rem;
        font-weight: 700;
        font-family: 'Cinzel', serif;
    }
    
    .score-text {
        font-size: 0.9rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .success-content {
        padding: 25px;
    }
    
    .success-content h4 {
        color: var(--royal-dark);
        margin-bottom: 5px;
    }
    
    .success-meta {
        color: var(--royal-gold);
        font-size: 0.9rem;
        margin-bottom: 15px;
        font-weight: 500;
    }
    
    .success-quote {
        color: var(--royal-text-dark);
        font-style: italic;
        line-height: 1.7;
        margin-bottom: 0;
        border-left: 3px solid rgba(212, 175, 55, 0.3);
        padding-left: 15px;
    }
    
    /* CTA Section */
    .cta-card {
        background: var(--gold-gradient);
        border-radius: var(--border-radius-lg);
        padding: 60px 40px;
        position: relative;
        overflow: hidden;
    }
    
    .cta-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80') center/cover;
        opacity: 0.1;
    }
    
    .cta-content {
        position: relative;
        z-index: 2;
        color: var(--royal-white);
    }
    
    .cta-title {
        font-size: 2.5rem;
        color: var(--royal-white);
    }
    
    .cta-text {
        font-size: 1.1rem;
        opacity: 0.9;
        max-width: 700px;
        margin: 0 auto;
    }
    
    .cta-buttons {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    /* Section Styles */
    .section-padding {
        padding: 80px 0;
    }
    
    .section-header {
        margin-bottom: 50px;
    }
    
    .section-subtitle {
        color: var(--royal-gold);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 1rem;
        display: block;
        font-weight: 600;
    }
    
    .section-title {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: var(--royal-dark);
    }
    
    .section-description {
        color: var(--royal-text-dark);
        font-size: 1.1rem;
        max-width: 700px;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .about-hero .hero-title {
            font-size: 2.8rem;
        }
        
        .stat-value {
            font-size: 2rem;
        }
        
        .section-title {
            font-size: 2.2rem;
        }
        
        .founder-image-placeholder {
            height: 350px;
        }
        
        .cta-title {
            font-size: 2.2rem;
        }
    }
    
    @media (max-width: 768px) {
        .about-hero {
            padding: 60px 0 30px;
        }
        
        .about-hero .hero-title {
            font-size: 2.3rem;
        }
        
        .stat-card {
            padding: 20px 15px;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            font-size: 24px;
        }
        
        .stat-value {
            font-size: 1.8rem;
        }
        
        .founder-image-placeholder {
            height: 300px;
            font-size: 80px;
        }
        
        .story-timeline {
            padding-left: 20px;
        }
        
        .timeline-year {
            position: relative;
            left: 0;
            display: inline-block;
            margin-bottom: 10px;
        }
        
        .timeline-content {
            margin-left: 0;
        }
        
        .cta-card {
            padding: 40px 20px;
        }
        
        .cta-title {
            font-size: 1.8rem;
        }
        
        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .cta-buttons a {
            width: 100%;
            max-width: 300px;
            justify-content: center;
        }
        
        .section-padding {
            padding: 60px 0;
        }
    }
    
    @media (max-width: 576px) {
        .about-hero .hero-title {
            font-size: 2rem;
        }
        
        .founder-image-placeholder {
            height: 250px;
            font-size: 60px;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .cta-title {
            font-size: 1.6rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate counters in stats section when scrolled into view
        const statCards = document.querySelectorAll('.stat-card');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, {
            threshold: 0.5
        });
        
        statCards.forEach(card => {
            observer.observe(card);
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                
                if (href === '#') return;
                
                if (href.startsWith('#')) {
                    e.preventDefault();
                    const targetId = href.substring(1);
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
        
        // Add animation to timeline items
        const timelineItems = document.querySelectorAll('.timeline-item');
        const timelineObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 200);
                }
            });
        }, {
            threshold: 0.3
        });
        
        // Initialize timeline items with hidden state
        timelineItems.forEach(item => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            timelineObserver.observe(item);
        });
        
        // Philosophy cards hover effect enhancement
        const philosophyCards = document.querySelectorAll('.philosophy-card');
        philosophyCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });
</script>

<?php include 'footer.php'; ?>