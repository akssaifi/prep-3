<?php
require_once 'config.php';
$settings = getSettings($conn);

// Get all published blogs
$blogs_sql = "SELECT * FROM blogs WHERE status = 'published' ORDER BY created_at DESC";
$blogs_result = mysqli_query($conn, $blogs_sql);
$blogs = [];
if ($blogs_result) {
    while ($row = mysqli_fetch_assoc($blogs_result)) {
        $blogs[] = $row;
    }
}

include 'header.php';
?>

<div class="container-custom py-5">
    <div class="text-center mb-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-light p-3 rounded-3 justify-content-center">
                <li class="breadcrumb-item"><a href="index.php" class="text-gold">Home</a></li>
                <li class="breadcrumb-item active text-dark">Blog</li>
            </ol>
        </nav>
        
        <span class="text-gold fw-bold text-uppercase letter-spacing-2 d-block mb-2">Latest Updates</span>
        <h1 class="display-3 fw-bold mb-3 text-dark">Our Blog</h1>
        <p class="lead text-muted mb-4">Stay updated with our latest articles and insights</p>
    </div>

    <div class="row">
        <div class="col-12">
            <?php if (empty($blogs)): ?>
                <div class="text-center py-5">
                    <div class="icon-circle-lg mx-auto mb-4">
                        <i class="fas fa-blog text-gold"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-3">No Blog Posts Available</h3>
                    <p class="text-muted">Check back later for new blog posts.</p>
                </div>
            <?php else: ?>
                <?php foreach ($blogs as $blog): ?>
                    <div class="blog-card bg-white rounded-3 border border-royal-gold mb-4 p-4">
                        <div class="row g-4">
                            <?php if (!empty($blog['image'])): ?>
                                <div class="col-lg-4 col-md-5">
                                    <img src="<?php echo htmlspecialchars($blog['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($blog['title']); ?>" 
                                         class="img-fluid rounded" style="width: 100%; height: 250px; object-fit: cover;">
                                </div>
                                <div class="col-lg-8 col-md-7">
                            <?php else: ?>
                                <div class="col-12">
                            <?php endif; ?>
                                <div class="blog-content">
                                    <small class="text-muted mb-2 d-block">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?php echo date('F j, Y', strtotime($blog['created_at'])); ?>
                                    </small>
                                    <h3 class="fw-bold text-dark mb-3"><?php echo htmlspecialchars($blog['title']); ?></h3>
                                    <div class="blog-excerpt" id="blog-excerpt-<?php echo $blog['id']; ?>">
                                        <?php 
                                        $content = strip_tags($blog['content']);
                                        if (strlen($content) > 300) {
                                            echo '<div class="blog-preview" id="blog-preview-' . $blog['id'] . '">' . nl2br(htmlspecialchars(substr($content, 0, 300))) . '...</div>';
                                            echo '<div class="blog-full-content d-none" id="blog-full-' . $blog['id'] . '">' . nl2br(htmlspecialchars($content)) . '</div>';
                                            ?>
                                            <button class="btn btn-link text-gold p-0 ms-1 read-more-btn" 
                                                    data-blog-id="<?php echo $blog['id']; ?>"
                                                    data-content="<?php echo htmlspecialchars($blog['content']); ?>"
                                                    data-title="<?php echo htmlspecialchars($blog['title']); ?>">
                                                Read More
                                            </button>
                                            <button class="btn btn-link text-gold p-0 ms-1 read-less-btn d-none" 
                                                    data-blog-id="<?php echo $blog['id']; ?>">
                                                Read Less
                                            </button>
                                            <?php
                                        } else {
                                            echo nl2br(htmlspecialchars($content));
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>



<style>
    .blog-card {
        transition: var(--transition);
    }
    
    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg) !important;
        border-color: var(--royal-gold);
    }
    
    .read-more-btn {
        text-decoration: underline;
        border: none;
        background: none;
        font-weight: 600;
        cursor: pointer;
    }
    
    .read-more-btn:hover {
        color: var(--royal-gold) !important;
    }
    
    .blog-excerpt {
        line-height: 1.8;
        overflow-wrap: break-word;
    }
    
    .letter-spacing-2 {
        letter-spacing: 2px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Read more button functionality
        const readMoreButtons = document.querySelectorAll('.read-more-btn');
        const readLessButtons = document.querySelectorAll('.read-less-btn');
        
        readMoreButtons.forEach(button => {
            button.addEventListener('click', function() {
                const blogId = this.getAttribute('data-blog-id');
                
                // Show full content and hide preview
                document.getElementById('blog-preview-' + blogId).classList.add('d-none');
                document.getElementById('blog-full-' + blogId).classList.remove('d-none');
                
                // Show read less button and hide read more button
                this.classList.add('d-none');
                document.querySelector('.read-less-btn[data-blog-id="' + blogId + '"]').classList.remove('d-none');
            });
        });
        
        readLessButtons.forEach(button => {
            button.addEventListener('click', function() {
                const blogId = this.getAttribute('data-blog-id');
                
                // Show preview and hide full content
                document.getElementById('blog-preview-' + blogId).classList.remove('d-none');
                document.getElementById('blog-full-' + blogId).classList.add('d-none');
                
                // Show read more button and hide read less button
                this.classList.add('d-none');
                document.querySelector('.read-more-btn[data-blog-id="' + blogId + '"]').classList.remove('d-none');
            });
        });
    });
</script>

<?php include 'footer.php'; ?>
