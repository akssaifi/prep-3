<?php
require_once 'config.php';

// Get course slug from URL
$course_slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';

// Get course details
$course_query = mysqli_query($conn, "SELECT * FROM courses WHERE slug = '$course_slug'");
$course = mysqli_fetch_assoc($course_query);

if(!$course) {
    echo '<div class="container-custom py-5"><div class="alert alert-danger">Course not found.</div></div>';
    return;
}

// Get subjects for this course with safe array access
$subjects_query = mysqli_query($conn, "SELECT s.*, c.name as course_name, c.slug as course_slug 
                                      FROM subjects s 
                                      INNER JOIN courses c ON s.course_id = c.id 
                                      WHERE c.slug = '$course_slug' AND s.status = 'active' 
                                      ORDER BY s.display_order ASC");
$subjects = [];
while($row = mysqli_fetch_assoc($subjects_query)) {
    $subjects[] = $row;
}

// Get settings for contact info
$settings = getSettings($conn);
?>

<div class="container-custom py-5">
    <!-- Course Header -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-light p-3 rounded-3">
            <li class="breadcrumb-item"><a href="index.php" class="text-blue">Home</a></li>
            <li class="breadcrumb-item"><a href="courses.php" class="text-blue">Courses</a></li>
            <li class="breadcrumb-item active text-dark"><?php echo htmlspecialchars($course['name']); ?></li>
        </ol>
    </nav>
    
    <div class="row mb-5">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <?php if(!empty($course['cover_image'])): ?>
                <div class="course-image-large me-4">
                    <img src="<?php echo htmlspecialchars($course['cover_image']); ?>" 
                         alt="<?php echo htmlspecialchars($course['name']); ?>" 
                         class="rounded-3 shadow-lg" style="width: 120px; height: 120px; object-fit: cover;">
                </div>
                <?php else: ?>
                <div class="course-icon-large me-4">
                    <i class="<?php echo htmlspecialchars($course['icon'] ?: 'fas fa-book'); ?>"></i>
                </div>
                <?php endif; ?>
                <div>
                    <h1 class="display-4 fw-bold mb-2 text-dark"><?php echo htmlspecialchars($course['name']); ?></h1>
                    <div class="d-flex gap-3 mb-3">
                        <?php if(isset($course['is_featured']) && $course['is_featured']): ?>
                        <span class="badge bg-blue px-3 py-2">
                            <i class="fas fa-star me-1"></i> Featured
                        </span>
                        <?php endif; ?>
                        <?php if(isset($course['is_popular']) && $course['is_popular']): ?>
                        <span class="badge bg-blue px-3 py-2">
                            <i class="fas fa-fire me-1"></i> Popular
                        </span>
                        <?php endif; ?>
                        <span class="badge bg-blue-transparent px-3 py-2 text-dark border border-royal-blue">
                            <?php echo ucfirst(isset($course['status']) ? $course['status'] : 'active'); ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <p class="lead text-muted mb-5"><?php echo htmlspecialchars($course['short_description'] ?: 'No description available.'); ?></p>
            
            <div class="d-flex flex-wrap gap-3 mb-5">
                <a href="#course-modules" class="btn-royal">
                    <i class="fas fa-book-open me-2"></i> View Modules
                </a>
                <a href="index.php#demo-form" class="btn btn-outline-blue">
                    <i class="fas fa-calendar-check me-2"></i> Book Demo
                </a>
                <?php if(!empty($settings['whatsapp_number'])): ?>
                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $settings['whatsapp_number']); ?>?text=I'm%20interested%20in%20<?php echo urlencode($course['name']); ?>" 
                   class="btn-whatsapp-royal" target="_blank">
                    <i class="fab fa-whatsapp me-2"></i> WhatsApp Enquiry
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Course Content -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Course Description -->
            <div class="card border-royal-blue shadow-lg mb-5 bg-white">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4 text-dark">Course Overview</h3>
                    <div class="course-description-content text-muted">
                        <?php echo nl2br(htmlspecialchars($course['full_description'] ?: 'Detailed description will be added soon.')); ?>
                    </div>
                </div>
            </div>
            
            <!-- Course Modules -->
            <div id="course-modules" class="card border-royal-blue shadow-lg mb-5 bg-white">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4 text-dark">Course Modules</h3>
                    
                    <?php if(!empty($subjects)): ?>
                    <div class="row g-4">
                        <?php foreach($subjects as $subject): 
                            // Get books for this subject
                            $books_query = mysqli_query($conn, "SELECT COUNT(*) as book_count FROM books WHERE subject_id = " . $subject['id'] . " AND status = 'published'");
                            $books_count = mysqli_fetch_assoc($books_query)['book_count'];
                            
                            // Safe array access
                            $is_bestseller = isset($subject['is_bestseller']) ? $subject['is_bestseller'] : 0;
                            $duration_type = isset($subject['duration_type']) ? $subject['duration_type'] : 'weeks';
                            $duration = isset($subject['duration']) ? $subject['duration'] : '';
                            $price = isset($subject['price']) ? floatval($subject['price']) : 0;
                            $discount_price = isset($subject['discount_price']) && $subject['discount_price'] > 0 ? floatval($subject['discount_price']) : null;
                            $currency = isset($subject['currency']) ? $subject['currency'] : 'INR';
                        ?>
                        <div class="col-md-6">
                            <div class="module-card p-4 rounded-3 h-100 bg-light border border-royal-blue">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="fw-bold mb-0 text-dark"><?php echo htmlspecialchars($subject['subject_name']); ?></h5>
                                    <?php if($is_bestseller): ?>
                                    <span class="badge bg-blue">Bestseller</span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if($duration): ?>
                                <div class="d-flex align-items-center gap-2 text-muted mb-3">
                                    <i class="fas fa-clock text-blue"></i>
                                    <span><?php echo $duration; ?> <?php echo $duration_type; ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <div class="price-section mb-4">
                                    <?php if($discount_price): ?>
                                    <div class="d-flex align-items-center gap-3">
                                        <del class="text-muted">₹<?php echo number_format($price, 2); ?></del>
                                        <span class="h4 fw-bold text-blue">₹<?php echo number_format($discount_price, 2); ?></span>
                                        <?php if($price > 0): ?>
                                        <?php $save_percent = round((($price - $discount_price) / $price) * 100); ?>
                                        <span class="badge bg-danger">Save <?php echo $save_percent; ?>%</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php else: ?>
                                    <span class="h4 fw-bold text-blue">₹<?php echo number_format($price, 2); ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if($books_count > 0): ?>
                                <div class="books-preview mb-4">
                                    <small class="text-muted d-block mb-2">Available Study Material:</small>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-dark border border-royal-blue">
                                            <i class="fas fa-book me-1"></i> <?php echo $books_count; ?> books
                                        </span>
                                        <span class="text-muted small">included</span>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <div class="d-flex gap-2">
                                    <a href="subject.php?slug=<?php echo $subject['slug']; ?>" class="btn btn-outline-blue btn-sm flex-grow-1">
                                        <i class="fas fa-eye me-1"></i> View Details
                                    </a>
                                    <?php if($price > 0): ?>
                                    <a href="checkout.php?type=subject&id=<?php echo $subject['id']; ?>&price=<?php echo $discount_price ?: $price; ?>&name=<?php echo urlencode($subject['subject_name']); ?>" 
                                       class="btn-royal btn-sm">
                                        <i class="fas fa-shopping-cart me-1"></i> Enroll Now
                                    </a>
                                    <?php else: ?>
                                    <a href="subject.php?slug=<?php echo $subject['slug']; ?>" class="btn-royal btn-sm">
                                        <i class="fas fa-eye me-1"></i> View Module
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-info bg-light border-royal-blue">
                        <i class="fas fa-info-circle me-2 text-blue"></i> Modules will be added soon. Please check back later.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Course Info Card -->
            <div class="card border-royal-blue shadow-lg mb-4 bg-white sticky-sidebar">
                <div class="card-body p-5">
                    <h4 class="fw-bold mb-4 text-dark">Course Information</h4>
                    
                    <ul class="list-unstyled mb-4">
                        <?php if(!empty($course['duration'])): ?>
                        <li class="mb-3 d-flex align-items-center">
                            <div class="icon-circle bg-blue-transparent me-3">
                                <i class="fas fa-clock text-blue"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Duration</small>
                                <strong class="text-dark"><?php echo htmlspecialchars($course['duration']); ?></strong>
                            </div>
                        </li>
                        <?php endif; ?>
                        
                        <li class="mb-3 d-flex align-items-center">
                            <div class="icon-circle bg-blue-transparent me-3">
                                <i class="fas fa-users text-blue"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Batch Size</small>
                                <strong class="text-dark">Small Groups (5-10 students)</strong>
                            </div>
                        </li>
                        
                        <li class="mb-3 d-flex align-items-center">
                            <div class="icon-circle bg-blue-transparent me-3">
                                <i class="fas fa-chart-line text-blue"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Total Modules</small>
                                <strong class="text-dark"><?php echo count($subjects); ?> Modules</strong>
                            </div>
                        </li>
                        
                        <li class="mb-4 d-flex align-items-center">
                            <div class="icon-circle bg-blue-transparent me-3">
                                <i class="fas fa-certificate text-blue"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Certificate</small>
                                <strong class="text-dark">Industry Recognized</strong>
                            </div>
                        </li>
                    </ul>
                    
                    <hr class="my-4 border-royal-blue">
                    
                    <!-- Quick Contact -->
                    <h6 class="fw-bold mb-3 text-dark">Need Help?</h6>
                    <div class="mb-4">
                        <?php if(!empty($settings['primary_phone'])): ?>
                        <a href="tel:<?php echo htmlspecialchars($settings['primary_phone']); ?>" 
                           class="d-flex align-items-center text-dark mb-3 hover-lift">
                            <div class="icon-circle bg-blue-transparent me-3">
                                <i class="fas fa-phone text-blue"></i>
                            </div>
                            <div>
                                <strong><?php echo htmlspecialchars($settings['primary_phone']); ?></strong>
                                <div class="text-muted small">Primary Contact</div>
                            </div>
                        </a>
                        <?php endif; ?>
                        
                        <?php if(!empty($settings['email'])): ?>
                        <a href="mailto:<?php echo htmlspecialchars($settings['email']); ?>" 
                           class="d-flex align-items-center text-dark mb-3 hover-lift">
                            <div class="icon-circle bg-blue-transparent me-3">
                                <i class="fas fa-envelope text-blue"></i>
                            </div>
                            <div>
                                <strong><?php echo htmlspecialchars($settings['email']); ?></strong>
                                <div class="text-muted small">Email Support</div>
                            </div>
                        </a>
                        <?php endif; ?>
                        
                        <?php if(!empty($settings['whatsapp_number'])): ?>
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $settings['whatsapp_number']); ?>?text=I'm%20interested%20in%20<?php echo urlencode($course['name']); ?>" 
                           class="d-flex align-items-center text-dark hover-lift" target="_blank">
                            <div class="icon-circle bg-blue-transparent me-3">
                                <i class="fab fa-whatsapp text-blue"></i>
                            </div>
                            <div>
                                <strong><?php echo htmlspecialchars($settings['whatsapp_number']); ?></strong>
                                <div class="text-muted small">WhatsApp Chat</div>
                            </div>
                        </a>
                        <?php endif; ?>
                    </div>
                    
                    <a href="index.php#demo-form" class="btn-royal w-100 mb-3">
                        <i class="fas fa-calendar-check me-2"></i> Book Free Demo
                    </a>
                    <a href="courses.php" class="btn btn-outline-blue w-100">
                        <i class="fas fa-arrow-left me-2"></i> Back to Courses
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Light Theme Variables */
    :root {
        --royal-bg: #f8f9fa;
        --royal-light: #ffffff;
        --royal-card: #ffffff;
        --royal-border: rgba(45, 90, 135, 0.2);
        --royal-text: #212529;
        --royal-text-dark: #6c757d;
        --royal-blue: #2D5A87;
        --royal-blue-light: #5B8DB8;
        --royal-blue-dark: #1A365D;
        --blue-gradient: linear-gradient(135deg, var(--royal-blue), var(--royal-blue-light));
        --border-radius: 12px;
        --border-radius-sm: 8px;
        --border-radius-lg: 16px;
        --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        --shadow-sm: 0 2px 12px rgba(0, 0, 0, 0.1);
        --shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 16px 48px rgba(0, 0, 0, 0.1);
    }
    
    body {
        background: var(--royal-bg);
        color: var(--royal-text);
    }
    
    .bg-light {
        background-color: var(--royal-light) !important;
    }
    
    .border-royal-blue {
        border-color: var(--royal-blue) !important;
    }
    
    .text-blue {
        color: var(--royal-blue) !important;
    }
    
    .bg-blue {
        background: var(--blue-gradient) !important;
        color: white !important;
    }
    
    .bg-blue-transparent {
        background: rgba(45, 90, 135, 0.1) !important;
        border: 1px solid rgba(45, 90, 135, 0.2) !important;
    }
    
    .btn-outline-blue {
        color: var(--royal-blue);
        border-color: var(--royal-blue);
        background: transparent;
    }
    
    .btn-outline-blue:hover {
        background: var(--blue-gradient);
        color: white;
        border-color: var(--royal-blue);
    }
    
    /* Course Page Styles */
    .course-icon-large {
        width: 100px;
        height: 100px;
        background: var(--blue-gradient);
        border-radius: var(--border-radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 42px;
        box-shadow: 0 15px 35px rgba(45, 90, 135, 0.3);
    }
    
    .course-image-large {
        width: 120px;
        height: 120px;
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }
    
    .module-card {
        background: var(--royal-light);
        border: 1px solid rgba(45, 90, 135, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .module-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg) !important;
        border-color: var(--royal-blue);
    }
    
    .icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .hover-lift {
        transition: transform 0.3s ease;
        padding: 0.5rem;
        border-radius: var(--border-radius-sm);
    }
    
    .hover-lift:hover {
        transform: translateX(5px);
        background: rgba(45, 90, 135, 0.05);
        text-decoration: none;
    }
    
    .course-description-content {
        line-height: 1.8;
        font-size: 1.1rem;
    }
    
    .course-description-content p {
        margin-bottom: 1.5rem;
    }
    
    .price-section del {
        font-size: 1.1rem;
    }
    
    .btn-royal {
        background: var(--blue-gradient);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: var(--border-radius-sm);
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: var(--transition);
    }
    
    .btn-royal:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 20px rgba(45, 90, 135, 0.3);
        color: white;
    }
    
    .btn-royal.btn-sm {
        padding: 8px 16px;
        font-size: 14px;
    }
    
    .btn-whatsapp-royal {
        background: #25D366;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: var(--border-radius-sm);
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: var(--transition);
    }
    
    .btn-whatsapp-royal:hover {
        background: #128C7E;
        color: white;
        transform: translateY(-2px);
    }
    
    .sticky-sidebar {
        position: sticky;
        top: 100px;
    }
    
    @media (max-width: 768px) {
        .course-icon-large {
            width: 80px;
            height: 80px;
            font-size: 32px;
        }
        
        .course-image-large {
            width: 80px;
            height: 80px;
        }
        
        .display-4 {
            font-size: 2.5rem;
        }
        
        .card-body {
            padding: 2rem !important;
        }
        
        .sticky-sidebar {
            position: static;
        }
    }
</style>

<script>
    // Module card hover effect
    document.addEventListener('DOMContentLoaded', function() {
        const moduleCards = document.querySelectorAll('.module-card');
        moduleCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });
        
        // Smooth scroll to modules
        document.querySelectorAll('a[href="#course-modules"]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.getElementById('course-modules');
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