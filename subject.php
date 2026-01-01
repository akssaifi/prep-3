<?php
// Include config first
require_once 'config.php';

// Get subject slug from URL
$subject_slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';

// Check if subject slug is provided
if(empty($subject_slug)) {
    echo '<div class="container-custom py-5"><div class="alert alert-danger">Subject slug is required.</div></div>';
    return;
}

// Get subject details
$subject_query = mysqli_query($conn, "SELECT s.*, c.name as course_name, c.slug as course_slug FROM subjects s LEFT JOIN courses c ON s.course_id = c.id WHERE s.slug = '" . sanitize($subject_slug) . "'");
$subject = mysqli_fetch_assoc($subject_query);

if(!$subject) {
    echo '<div class="container-custom py-5"><div class="alert alert-danger">Subject not found.</div></div>';
    return;
}

// Get books for this subject
$books_query = mysqli_query($conn, "SELECT * FROM books WHERE subject_id = " . $subject['id'] . " AND status = 'published' ORDER BY is_featured DESC, title ASC");
$books = [];
while($book = mysqli_fetch_assoc($books_query)) {
    $books[] = $book;
}

// Get settings for contact info
$settings = getSettings($conn);

// Decode features JSON
$features = [];
if(!empty($subject['features'])) {
    $features = json_decode($subject['features'], true);
    if($features === null) {
        $features = [];
    }
}
?>

<div class="container-custom py-5">
 <?php include 'header.php';?>
    <div class="row mb-5">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h1 class="display-4 fw-bold mb-2"><?php echo htmlspecialchars($subject['subject_name']); ?></h1>
                    <p class="text-muted mb-3">Part of: <a href="course.php?slug=<?php echo $subject['course_slug']; ?>" class="text-primary"><?php echo htmlspecialchars($subject['course_name']); ?></a></p>
                </div>
                <div class="d-flex gap-2">
                    <?php if($subject['is_bestseller']): ?>
                    <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-25 px-3 py-2">
                        <i class="fas fa-crown me-1"></i> Bestseller
                    </span>
                    <?php endif; ?>
                    <?php if($subject['is_recommended']): ?>
                    <span class="badge  bg-opacity-20 text-success border border-success border-opacity-25 px-3 py-2">
                        <i class="fas fa-thumbs-up me-1"></i> Recommended
                    </span>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Price Section -->
            <div class="price-card p-4 rounded-3 mb-5">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="d-flex align-items-center gap-4">
                            <?php if($subject['discount_price'] && $subject['discount_price'] > 0): ?>
                            <div>
                                <del class="text-muted h5">₹<?php echo number_format($subject['price'], 2); ?></del>
                                <div class="display-5 fw-bold text-primary">₹<?php echo number_format($subject['discount_price'], 2); ?></div>
                            </div>
                            <?php 
                                $save_percent = round((($subject['price'] - $subject['discount_price']) / $subject['price']) * 100);
                                if($save_percent > 0):
                            ?>
                            <span class="badge bg-danger px-3 py-2">Save <?php echo $save_percent; ?>%</span>
                            <?php endif; ?>
                            <?php else: ?>
                            <div class="display-5 fw-bold text-primary">₹<?php echo number_format($subject['price'], 2); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-wrap gap-3">
                            <!-- Subject Buy Button -->
                            <a href="checkout.php?type=subject&id=<?php echo $subject['id']; ?>&price=<?php echo $subject['discount_price'] ?: $subject['price']; ?>&name=<?php echo urlencode($subject['subject_name']); ?>" 
                               class="btn-premium flex-grow-1">
                                <i class="fas fa-shopping-cart me-2"></i> Enroll Now
                            </a>
                            <?php if(!empty($settings['whatsapp_number'])): ?>
                            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $settings['whatsapp_number']); ?>?text=I'm%20interested%20in%20<?php echo urlencode($subject['subject_name']); ?>" 
                               class="btn-whatsapp-premium flex-grow-1" target="_blank">
                                <i class="fab fa-whatsapp me-2"></i> Enquire
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Subject Details -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Highlights -->
            <?php if(!empty($subject['highlights'])): ?>
            <div class="card border-0 shadow-lg mb-5">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4">Course Highlights</h3>
                    <div class="row g-4">
                        <?php 
                        $highlights = array_filter(array_map('trim', explode("\n", $subject['highlights'])));
                        foreach($highlights as $highlight):
                        ?>
                        <div class="col-md-6">
                            <div class="d-flex gap-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                        <i class="fas fa-check text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0"><?php echo htmlspecialchars($highlight); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Features -->
            <?php if(!empty($features)): ?>
            <div class="card border-0 shadow-lg mb-5">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4">Key Features</h3>
                    <div class="row g-4">
                        <?php foreach($features as $feature): ?>
                        <div class="col-md-6">
                            <div class="d-flex gap-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                        <i class="fas fa-star text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0"><?php echo htmlspecialchars($feature); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- What You'll Learn -->
            <?php if(!empty($subject['what_you_learn'])): ?>
            <div class="card border-0 shadow-lg mb-5">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4">What You'll Learn</h3>
                    <div class="learning-content">
                        <?php echo nl2br(htmlspecialchars($subject['what_you_learn'])); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Books & Study Material -->
            <div class="card border-0 shadow-lg mb-5">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4">Books & Study Material</h3>
                    
                    <?php if(!empty($books)): ?>
                    <div class="row g-4">
                        <?php foreach($books as $book): ?>
                        <div class="col-md-6">
                            <div class="book-card p-4 rounded-3 h-100">
                                <div class="d-flex gap-4 mb-3">
                                    <div class="book-cover">
                                        <?php if(!empty($book['cover_image'])): ?>
                                        <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="rounded" style="width: 60px; height: 80px; object-fit: cover;">
                                        <?php else: ?>
                                        <div class="book-icon">
                                            <i class="fas fa-book"></i>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2"><?php echo htmlspecialchars($book['title']); ?></h5>
                                        <?php if(!empty($book['author'])): ?>
                                        <p class="text-muted small mb-2">by <?php echo htmlspecialchars($book['author']); ?></p>
                                        <?php endif; ?>
                                        <?php if(!empty($book['edition'])): ?>
                                        <span class="badge bg-light text-dark"><?php echo htmlspecialchars($book['edition']); ?> Edition</span>
                                        <?php endif; ?>
                                        <?php if($book['is_featured']): ?>
                                        <span class="badge bg-warning text-dark ms-2">Featured</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="book-meta mb-4">
                                    <?php if($book['pages']): ?>
                                    <span class="text-muted me-3"><i class="fas fa-file-alt me-1"></i> <?php echo $book['pages']; ?> pages</span>
                                    <?php endif; ?>
                                    <?php if($book['language']): ?>
                                    <span class="text-muted"><i class="fas fa-language me-1"></i> <?php echo htmlspecialchars($book['language']); ?></span>
                                    <?php endif; ?>
                                    <?php if($book['file_format']): ?>
                                    <span class="text-muted ms-3"><i class="fas fa-file me-1"></i> <?php echo htmlspecialchars($book['file_format']); ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <?php if($book['is_free']): ?>
                                        <span class="badge bg-success">Free</span>
                                        <?php else: ?>
                                        <span class="fw-bold text-primary">₹<?php echo number_format($book['price'], 2); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <?php if($book['is_free'] && !empty($book['file_url'])): ?>
                                        <a href="download.php?type=book&id=<?php echo $book['id']; ?>" 
                                           class="btn btn-success btn-sm" onclick="trackDownload(<?php echo $book['id']; ?>, 'book')">
                                            <i class="fas fa-download me-1"></i> Download
                                        </a>
                                        <?php elseif(!$book['is_free']): ?>
                                        <a href="checkout.php?type=book&id=<?php echo $book['id']; ?>&price=<?php echo $book['price']; ?>&name=<?php echo urlencode($book['title']); ?>" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-shopping-cart me-1"></i> Buy
                                        </a>
                                        <?php else: ?>
                                        <button class="btn btn-outline-secondary btn-sm" disabled>
                                            <i class="fas fa-info-circle me-1"></i> Coming Soon
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Study materials will be added soon. Please check back later.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Subject Info -->
            <div class="card border-0 shadow-lg sticky-top mb-4" style="top: 100px;">
                <div class="card-body p-5">
                    <h4 class="fw-bold mb-4">Module Information</h4>
                    
                    <ul class="list-unstyled mb-4">
                        <?php if(!empty($subject['duration'])): ?>
                        <li class="mb-3 d-flex align-items-center">
                            <div class="icon-circle bg-primary bg-opacity-10 me-3">
                                <i class="fas fa-clock text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Duration</small>
                                <strong><?php echo htmlspecialchars($subject['duration']); ?> <?php echo htmlspecialchars($subject['duration_type']); ?></strong>
                            </div>
                        </li>
                        <?php endif; ?>
                        
                        <li class="mb-3 d-flex align-items-center">
                            <div class="icon-circle bg-primary bg-opacity-10 me-3">
                                <i class="fas fa-book text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Study Materials</small>
                                <strong><?php echo count($books); ?> Books Available</strong>
                            </div>
                        </li>
                        
                        <li class="mb-3 d-flex align-items-center">
                            <div class="icon-circle bg-primary bg-opacity-10 me-3">
                                <i class="fas fa-certificate text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Certificate</small>
                                <strong>Module Completion</strong>
                            </div>
                        </li>
                        
                        <li class="mb-4 d-flex align-items-center">
                            <div class="icon-circle bg-primary bg-opacity-10 me-3">
                                <i class="fas fa-calendar-alt text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Updated</small>
                                <strong><?php echo date('M d, Y', strtotime($subject['updated_at'])); ?></strong>
                            </div>
                        </li>
                    </ul>
                    
                    <!-- Prerequisites -->
                    <?php if(!empty($subject['prerequisites'])): ?>
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Prerequisites</h6>
                        <p class="text-muted"><?php echo htmlspecialchars($subject['prerequisites']); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <hr class="my-4">
                    
                    <!-- Description -->
                    <?php if(!empty($subject['description'])): ?>
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Description</h6>
                        <p class="text-muted"><?php echo htmlspecialchars($subject['description']); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Actions -->
                    <a href="checkout.php?type=subject&id=<?php echo $subject['id']; ?>&price=<?php echo $subject['discount_price'] ?: $subject['price']; ?>&name=<?php echo urlencode($subject['subject_name']); ?>" 
                       class="btn-premium w-100 mb-3">
                        <i class="fas fa-shopping-cart me-2"></i> Enroll Now
                    </a>
                    <a href="courses.php?slug=<?php echo $subject['course_slug']; ?>" class="btn btn-outline-primary w-100">
                        <i class="fas fa-arrow-left me-2"></i> Back to Course
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Subject Page Styles */
    .price-card {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.05), rgba(37, 99, 235, 0.1));
        border: 1px solid rgba(37, 99, 235, 0.2);
    }
    
    .book-card {
        background: white;
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg) !important;
        border-color: var(--primary-light);
    }
    
    .book-cover {
        flex-shrink: 0;
    }
    
    .book-icon {
        width: 60px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary));
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        box-shadow: 0 5px 15px rgba(37, 99, 235, 0.2);
    }
    
    .learning-content {
        line-height: 1.8;
    }
    
    .learning-content p {
        margin-bottom: 1rem;
    }
    
    @media (max-width: 768px) {
        .display-4 {
            font-size: 2.5rem;
        }
        
        .display-5 {
            font-size: 2.5rem;
        }
    }
</style>

<script>
    // Book card hover effect
    document.querySelectorAll('.book-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });

    // Function to track downloads (AJAX call)
    function trackDownload(id, type) {
        // Send AJAX request to track download
        fetch('track_download.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: id,
                type: type
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Download tracked:', data);
        })
        .catch(error => {
            console.error('Error tracking download:', error);
        });
    }
</script>