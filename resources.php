<?php
require_once 'config.php';
$settings = getSettings($conn);
$free_materials = getFreeStudyMaterials($conn, 12);
$speaking_examples = getSpeakingExamples($conn, 12);
$video_resources = getVideoResources($conn, 12);

include 'header.php';
?>

<div class="container-custom py-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-light p-3 rounded-3 justify-content-center">
                <li class="breadcrumb-item"><a href="index.php" class="text-gold">Home</a></li>
                <li class="breadcrumb-item active text-dark">Resources</li>
            </ol>
        </nav>
        
        <span class="text-gold fw-bold text-uppercase letter-spacing-2 d-block mb-2">Learning Materials</span>
        <h1 class="display-3 fw-bold mb-3 text-dark">Free Resources</h1>
        <p class="lead text-muted mb-4">Access our premium collection of study materials, speaking examples, and video resources</p>
    </div>
    
    <!-- Resource Stats -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="resource-stat-card bg-white p-4 rounded-3 text-center border border-royal-gold shadow-sm">
                <div class="resource-stat-icon mb-3 mx-auto">
                    <i class="fas fa-download text-gold"></i>
                </div>
                <div class="h2 fw-bold text-gold mb-2"><?php echo count($free_materials); ?></div>
                <div class="text-dark">Free Study Materials</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="resource-stat-card bg-white p-4 rounded-3 text-center border border-royal-gold shadow-sm">
                <div class="resource-stat-icon mb-3 mx-auto">
                    <i class="fas fa-microphone text-gold"></i>
                </div>
                <div class="h2 fw-bold text-gold mb-2"><?php echo count($speaking_examples); ?></div>
                <div class="text-dark">Speaking Examples</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="resource-stat-card bg-white p-4 rounded-3 text-center border border-royal-gold shadow-sm">
                <div class="resource-stat-icon mb-3 mx-auto">
                    <i class="fas fa-play-circle text-gold"></i>
                </div>
                <div class="h2 fw-bold text-gold mb-2"><?php echo count($video_resources); ?></div>
                <div class="text-dark">Video Resources</div>
            </div>
        </div>
    </div>
    
    <!-- Free Study Materials Section -->
    <section id="free-material" class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-2"><i class="fas fa-download me-3 text-gold"></i>Free Study Materials</h2>
                <p class="text-muted">Download PDFs, guides, and study materials for various tests</p>
            </div>
            <div class="filter-buttons">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-gold material-filter active" data-filter="all">All Materials</button>
                    <button type="button" class="btn btn-outline-gold material-filter" data-filter="pdf">PDF Files</button>
                    <button type="button" class="btn btn-outline-gold material-filter" data-filter="doc">DOC Files</button>
                    <button type="button" class="btn btn-outline-gold material-filter" data-filter="txt">Text Files</button>
                </div>
            </div>
        </div>
        
        <div class="row g-4 materials-container">
            <?php foreach($free_materials as $material): 
                // Safely get file extension
                $file_extension = '';
                $file_format = strtolower($material['file_format'] ?? '');
                if (!empty($material['file_url'])) {
                    $file_parts = explode('.', $material['file_url']);
                    $file_extension = end($file_parts);
                    $file_extension = strtolower($file_extension);
                }
                
                // Determine icon based on file format
                $file_icon = 'fa-file';
                if (strpos($file_format, 'pdf') !== false || $file_extension === 'pdf') {
                    $file_icon = 'fa-file-pdf';
                } elseif (strpos($file_format, 'doc') !== false || in_array($file_extension, ['doc', 'docx'])) {
                    $file_icon = 'fa-file-word';
                } elseif (strpos($file_format, 'txt') !== false || $file_extension === 'txt') {
                    $file_icon = 'fa-file-alt';
                }
            ?>
            <div class="col-lg-4 col-md-6 material-item" 
                 data-format="<?php echo $file_extension; ?>"
                 data-type="<?php echo $file_format; ?>">
                <div class="material-card bg-white p-4 rounded-3 h-100 border border-royal-gold">
                    <?php if(!empty($material['cover_image'])): ?>
                    <div class="cover-image mb-3">
                        <img src="<?php echo htmlspecialchars($material['cover_image']); ?>" 
                             alt="<?php echo htmlspecialchars($material['title']); ?>" 
                             class="img-fluid rounded" style="width: 100%; height: 200px; object-fit: cover;">
                    </div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="material-icon me-3">
                            <i class="fas <?php echo $file_icon; ?> text-gold"></i>
                        </div>
                        <div class="download-count">
                            <small class="text-muted">
                                <i class="fas fa-download me-1"></i> <?php echo $material['download_count'] ?? 0; ?>
                            </small>
                        </div>
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-3"><?php echo htmlspecialchars($material['title']); ?></h5>
                    
                    <?php if(!empty($material['author'])): ?>
                    <p class="text-muted small mb-3">
                        <i class="fas fa-user me-2"></i><?php echo htmlspecialchars($material['author']); ?>
                    </p>
                    <?php endif; ?>
                    
                    <?php if(!empty($material['description'])): ?>
                    <p class="text-muted mb-4"><?php echo htmlspecialchars(substr($material['description'], 0, 100)) . '...'; ?></p>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <a href="download.php?type=material&id=<?php echo $material['id']; ?>" 
                           class="btn-royal btn-sm" onclick="incrementDownload(<?php echo $material['id']; ?>, 'material')">
                            <i class="fas fa-download me-2"></i> Download
                        </a>
                        <span class="badge bg-light text-dark border border-royal-gold">
                            <?php echo !empty($material['file_format']) ? strtoupper($material['file_format']) : strtoupper($file_extension); ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if(empty($free_materials)): ?>
        <div class="text-center py-5">
            <div class="icon-circle-lg mx-auto mb-4">
                <i class="fas fa-book text-gold"></i>
            </div>
            <h3 class="fw-bold text-dark mb-3">No Study Materials Available</h3>
            <p class="text-muted">Check back later for free study materials.</p>
        </div>
        <?php endif; ?>
    </section>
    
    <!-- Speaking Examples Section -->
    <section id="speaking-examples" class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-2"><i class="fas fa-microphone me-3 text-gold"></i>Speaking Examples</h2>
                <p class="text-muted">Listen to high-scoring speaking samples with transcripts</p>
            </div>
            <div class="filter-buttons">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-gold level-filter active" data-level="all">All Levels</button>
                    <button type="button" class="btn btn-outline-gold level-filter" data-level="beginner">Beginner</button>
                    <button type="button" class="btn btn-outline-gold level-filter" data-level="intermediate">Intermediate</button>
                    <button type="button" class="btn btn-outline-gold level-filter" data-level="advanced">Advanced</button>
                </div>
            </div>
        </div>
        
        <div class="row g-4 examples-container">
            <?php foreach($speaking_examples as $example): 
                // Check if transcript exists
                $has_transcript = !empty($example['transcript']);
            ?>
            <div class="col-lg-4 col-md-6 example-item" data-level="<?php echo $example['level']; ?>">
                <div class="example-card bg-white p-4 rounded-3 h-100 border border-royal-gold">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="example-icon me-3">
                            <i class="fas fa-headphones text-gold"></i>
                        </div>
                        <div class="level-badge">
                            <span class="badge level-badge-<?php echo $example['level']; ?>">
                                <?php echo ucfirst($example['level']); ?>
                            </span>
                            <?php if($has_transcript): ?>
                            <span class="badge bg-success ms-1" style="background: rgba(16, 185, 129, 0.1); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.2);">
                                <i class="fas fa-file-alt me-1"></i> Transcript
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-3"><?php echo htmlspecialchars($example['title']); ?></h5>
                    
                    <?php if(!empty($example['description'])): ?>
                    <p class="text-muted mb-3"><?php echo htmlspecialchars(substr($example['description'], 0, 100)) . '...'; ?></p>
                    <?php endif; ?>
                    
                    <?php if(!empty($example['duration'])): ?>
                    <p class="text-muted small mb-4">
                        <i class="fas fa-clock me-2"></i>Duration: <?php echo $example['duration']; ?>
                    </p>
                    <?php endif; ?>
                    
                    <div class="d-flex gap-2">
                        <button class="btn-royal btn-sm flex-grow-1" onclick="playAudio('<?php echo htmlspecialchars($example['audio_file']); ?>', <?php echo $example['id']; ?>, '<?php echo htmlspecialchars(addslashes($example['title'])); ?>', '<?php echo htmlspecialchars(addslashes($example['description'])); ?>', '<?php echo $example['level']; ?>', <?php echo $has_transcript ? "'" . htmlspecialchars(addslashes($example['transcript'])) . "'" : 'null'; ?>)">
                            <i class="fas fa-play me-2"></i> Play Audio
                        </button>
                        <a href="download.php?type=example&id=<?php echo $example['id']; ?>" 
                           class="btn btn-outline-gold btn-sm" onclick="incrementDownload(<?php echo $example['id']; ?>, 'example')">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if(empty($speaking_examples)): ?>
        <div class="text-center py-5">
            <div class="icon-circle-lg mx-auto mb-4">
                <i class="fas fa-microphone text-gold"></i>
            </div>
            <h3 class="fw-bold text-dark mb-3">No Speaking Examples Available</h3>
            <p class="text-muted">Check back later for speaking examples.</p>
        </div>
        <?php endif; ?>
    </section>
    
    <!-- Video Resources Section -->
    <section id="video-resources">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-2"><i class="fas fa-play-circle me-3 text-gold"></i>Video Resources</h2>
                <p class="text-muted">Watch tutorials, tips, and strategy videos</p>
            </div>
            <div class="filter-buttons">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-gold category-filter active" data-category="all">All Categories</button>
                    <?php
                    $categories = [];
                    foreach($video_resources as $video) {
                        if(!empty($video['category'])) {
                            $categories[] = $video['category'];
                        }
                    }
                    $categories = array_unique($categories);
                    foreach($categories as $category):
                    ?>
                    <button type="button" class="btn btn-outline-gold category-filter" data-category="<?php echo htmlspecialchars($category); ?>">
                        <?php echo htmlspecialchars($category); ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="row g-4 videos-container">
            <?php foreach($video_resources as $video): ?>
            <div class="col-lg-4 col-md-6 video-item" data-category="<?php echo htmlspecialchars($video['category'] ?? ''); ?>">
                <div class="video-card bg-white rounded-3 overflow-hidden border border-royal-gold">
                    <!-- Video Thumbnail -->
                    <div class="video-thumbnail position-relative" style="height: 200px; overflow: hidden; cursor: pointer;" 
                         onclick="playVideo('<?php echo htmlspecialchars($video['video_url']); ?>', '<?php echo $video['platform']; ?>', '<?php echo htmlspecialchars(addslashes($video['title'])); ?>', '<?php echo htmlspecialchars(addslashes($video['description'])); ?>', <?php echo $video['id']; ?>)">
                        <?php if(!empty($video['thumbnail_url'])): ?>
                        <img src="<?php echo htmlspecialchars($video['thumbnail_url']); ?>" alt="<?php echo htmlspecialchars($video['title']); ?>" 
                             class="img-fluid w-100 h-100" style="object-fit: cover;">
                        <?php else: ?>
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                            <i class="fas fa-play-circle text-gold" style="font-size: 48px;"></i>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Play Button Overlay -->
                        <div class="play-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                            <div class="play-button">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        
                        <!-- Video Duration -->
                        <?php if(!empty($video['duration'])): ?>
                        <div class="duration-badge position-absolute bottom-0 end-0 m-3">
                            <span class="badge bg-dark text-white"><?php echo $video['duration']; ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Video Info -->
                    <div class="p-4">
                        <h5 class="fw-bold text-dark mb-3"><?php echo htmlspecialchars($video['title']); ?></h5>
                        
                        <?php if(!empty($video['description'])): ?>
                        <p class="text-muted mb-3 small"><?php echo htmlspecialchars(substr($video['description'], 0, 80)) . '...'; ?></p>
                        <?php endif; ?>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <?php if(!empty($video['category'])): ?>
                            <span class="badge bg-light text-dark border border-royal-gold"><?php echo htmlspecialchars($video['category']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if(empty($video_resources)): ?>
        <div class="text-center py-5">
            <div class="icon-circle-lg mx-auto mb-4">
                <i class="fas fa-video text-gold"></i>
            </div>
            <h3 class="fw-bold text-dark mb-3">No Video Resources Available</h3>
            <p class="text-muted">Check back later for video resources.</p>
        </div>
        <?php endif; ?>
    </section>
</div>

<!-- Audio Player Modal -->
<div class="modal fade" id="audioPlayerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-white border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title text-dark">
                    <i class="fas fa-headphones text-gold me-2"></i>
                    <span id="audioTitle">Audio Player</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="audio-player">
                    <div class="audio-info mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <span id="audioLevelBadge" class="badge me-2"></span>
                            <small id="audioComplexity" class="text-muted"></small>
                        </div>
                        <p id="audioDescription" class="text-muted mb-0"></p>
                    </div>
                    
                    <div class="audio-controls mb-4">
                        <audio id="audioElement" controls class="w-100">
                            Your browser does not support the audio element.
                        </audio>
                        <div class="audio-time mt-2 d-flex justify-content-between">
                            <small class="text-muted" id="currentTime">0:00</small>
                            <small class="text-muted" id="duration">0:00</small>
                        </div>
                    </div>
                    
                    <!-- Transcript Section -->
                    <div class="transcript-section p-3 bg-light rounded border">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-gold mb-0">
                                <i class="fas fa-file-alt me-2"></i>Transcript
                            </h6>
                            <button id="copyTranscriptBtn" class="btn btn-outline-gold btn-sm d-none">
                                <i class="fas fa-copy me-1"></i> Copy Transcript
                            </button>
                        </div>
                        
                        <div id="transcriptContainer">
                            <div id="transcriptPlaceholder" class="text-center py-4">
                                <i class="fas fa-file-alt text-gold mb-3" style="font-size: 32px;"></i>
                                <p class="text-muted mb-0">No transcript available for this audio.</p>
                            </div>
                            <div id="transcriptContent" class="d-none">
                                <div id="transcriptText" class="transcript-text small mb-3"></div>
                                <div class="transcript-meta">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Transcript provided by instructor
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Video Player Modal -->
<div class="modal fade" id="videoPlayerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-white border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title text-dark">
                    <i class="fas fa-video text-gold me-2"></i>
                    <span id="videoTitle">Video Player</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="video-player">
                    <div id="videoContainer" class="ratio ratio-16x9 mb-4">
                        <!-- Video will be loaded here -->
                    </div>
                    <div class="video-info">
                        <div class="d-flex align-items-center mb-2">
                            <span id="videoCategoryBadge" class="badge me-2"></span>
                            <small id="videoDuration" class="text-muted"></small>
                        </div>
                        <p id="videoDescription" class="text-muted mb-0"></p>
                    </div>
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
        --royal-gold: #2D5A87;
        --royal-gold-light: #1A365D;
        --royal-gold-dark: #0F204E;
        --gold-gradient: linear-gradient(135deg, var(--royal-gold), var(--royal-gold-light));
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
    
    .border-royal-gold {
        border-color: var(--royal-gold) !important;
    }
    
    .text-gold {
        color: var(--royal-gold) !important;
    }
    
    .text-blue {
        color: var(--royal-gold) !important;
    }
    
    .text-dark {
        color: var(--royal-text) !important;
    }
    
    .text-muted {
        color: var(--royal-text-dark) !important;
    }
    
    .btn-outline-gold {
        color: var(--royal-gold);
        border-color: var(--royal-gold);
        background: transparent;
        transition: var(--transition);
    }
    
    .btn-outline-gold:hover,
    .btn-outline-gold.active {
        background-color: var(--royal-gold);
        color: white;
        border-color: var(--royal-gold);
        transform: translateY(-2px);
    }
    
    .btn-outline-gold.active {
        background: var(--gold-gradient);
        border-color: var(--royal-gold);
        color: white;
    }
    
    .btn-royal {
        background: var(--gold-gradient);
        color: white;
        border: none;
        padding: 8px 16px;
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
        padding: 6px 12px;
        font-size: 14px;
    }
    
    /* Resources Page Styles */
    .letter-spacing-2 {
        letter-spacing: 2px;
    }
    
    .resource-stat-card {
        transition: var(--transition);
    }
    
    .resource-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg) !important;
        border-color: var(--royal-gold);
    }
    
    .resource-stat-icon {
        width: 70px;
        height: 70px;
        background: rgba(45, 90, 135, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }
    
    .material-card,
    .example-card,
    .video-card {
        transition: var(--transition);
    }
    
    .material-card:hover,
    .example-card:hover,
    .video-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg) !important;
        border-color: var(--royal-gold);
    }
    
    .material-icon,
    .example-icon {
        width: 50px;
        height: 50px;
        background: rgba(45, 90, 135, 0.1);
        border-radius: var(--border-radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    
    .play-overlay {
        background: rgba(0, 0, 0, 0.5);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .video-thumbnail:hover .play-overlay {
        opacity: 1;
    }
    
    .play-button {
        width: 60px;
        height: 60px;
        background: var(--royal-gold);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        transition: transform 0.3s ease;
    }
    
    .video-thumbnail:hover .play-button {
        transform: scale(1.1);
    }
    
    .duration-badge .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .icon-circle-lg {
        width: 80px;
        height: 80px;
        background: rgba(45, 90, 135, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
    }
    
    /* Level badges */
    .level-badge-beginner {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }
    
    .level-badge-intermediate {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }
    
    .level-badge-advanced {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }
    
    /* Transcript styling */
    .transcript-text {
        background: rgba(45, 90, 135, 0.05);
        border-radius: var(--border-radius-sm);
        padding: 1rem;
        max-height: 300px;
        overflow-y: auto;
        line-height: 1.6;
        font-size: 0.9rem;
        white-space: pre-wrap;
        word-wrap: break-word;
    }
    
    .transcript-text::-webkit-scrollbar {
        width: 6px;
    }
    
    .transcript-text::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .transcript-text::-webkit-scrollbar-thumb {
        background: var(--royal-gold);
        border-radius: 3px;
    }
    
    /* Filter buttons group */
    .filter-buttons .btn-group {
        flex-wrap: wrap;
        gap: 5px;
    }
    
    .filter-buttons .btn {
        border-radius: var(--border-radius-sm) !important;
        padding: 6px 12px;
        font-size: 14px;
    }
    
    /* Toast notifications */
    .toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: white;
        border: 1px solid var(--royal-gold);
        border-radius: var(--border-radius-sm);
        padding: 1rem 1.5rem;
        box-shadow: var(--shadow);
        z-index: 9999;
        animation: slideIn 0.3s ease-out;
    }
    
    .toast-success {
        border-left: 4px solid #10B981;
    }
    
    .toast-error {
        border-left: 4px solid #EF4444;
    }
    
    .toast-warning {
        border-left: 4px solid #F59E0B;
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @media (max-width: 768px) {
        .display-3 {
            font-size: 2.5rem;
        }
        
        .resource-stat-icon {
            width: 60px;
            height: 60px;
            font-size: 24px;
        }
        
        .material-card,
        .example-card,
        .video-card {
            margin-bottom: 1.5rem;
        }
        
        .filter-buttons .btn-group {
            width: 100%;
            justify-content: center;
        }
        
        .d-flex.justify-content-between.align-items-center.mb-4 {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .filter-buttons {
            margin-top: 1rem;
            width: 100%;
        }
    }
    
    @media (max-width: 576px) {
        .btn-group {
            flex-direction: column;
            width: 100%;
        }
        
        .btn-group .btn {
            width: 100%;
            margin: 2px 0;
        }
    }
</style>

<script>
// Global variables
let currentAudioUrl = '';
let currentAudioId = '';
let currentAudioLevel = '';
let audioTranscript = '';

document.addEventListener('DOMContentLoaded', function() {
    // Material filter functionality
    const materialFilters = document.querySelectorAll('.material-filter');
    const materialItems = document.querySelectorAll('.material-item');
    
    materialFilters.forEach(btn => {
        btn.addEventListener('click', function() {
            const filterValue = this.dataset.filter;
            
            // Update active state
            materialFilters.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            materialItems.forEach(item => {
                const format = item.dataset.format;
                const type = item.dataset.type;
                
                let shouldShow = true;
                
                if (filterValue === 'pdf' && !(format === 'pdf' || type.includes('pdf'))) {
                    shouldShow = false;
                }
                if (filterValue === 'doc' && !(format === 'doc' || format === 'docx' || type.includes('doc'))) {
                    shouldShow = false;
                }
                if (filterValue === 'txt' && !(format === 'txt' || type.includes('txt'))) {
                    shouldShow = false;
                }
                
                item.style.display = shouldShow ? 'block' : 'none';
            });
        });
    });
    
    // Speaking examples level filter
    const levelFilters = document.querySelectorAll('.level-filter');
    const exampleItems = document.querySelectorAll('.example-item');
    
    levelFilters.forEach(btn => {
        btn.addEventListener('click', function() {
            const levelValue = this.dataset.level;
            
            // Update active state
            levelFilters.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            exampleItems.forEach(item => {
                if (levelValue === 'all' || item.dataset.level === levelValue) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    
    // Video category filter
    const categoryFilters = document.querySelectorAll('.category-filter');
    const videoItems = document.querySelectorAll('.video-item');
    
    categoryFilters.forEach(btn => {
        btn.addEventListener('click', function() {
            const categoryValue = this.dataset.category;
            
            // Update active state
            categoryFilters.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            videoItems.forEach(item => {
                if (categoryValue === 'all' || item.dataset.category === categoryValue) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    
    // Initialize audio time display
    initAudioTimeDisplay();
    
    // Setup transcript copy button
    setupTranscriptButtons();
});

// Initialize audio time display
function initAudioTimeDisplay() {
    const audioElement = document.getElementById('audioElement');
    const currentTimeEl = document.getElementById('currentTime');
    const durationEl = document.getElementById('duration');
    
    if (audioElement) {
        audioElement.addEventListener('loadedmetadata', function() {
            durationEl.textContent = formatTime(audioElement.duration);
        });
        
        audioElement.addEventListener('timeupdate', function() {
            currentTimeEl.textContent = formatTime(audioElement.currentTime);
        });
    }
}

// Format time in MM:SS
function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
}

// Setup transcript button listeners
function setupTranscriptButtons() {
    const copyBtn = document.getElementById('copyTranscriptBtn');
    
    if (copyBtn) {
        copyBtn.addEventListener('click', function() {
            copyTranscriptToClipboard();
        });
    }
}

// Audio player function
function playAudio(audioFile, audioId, title, description, level, transcript) {
    const audioModal = new bootstrap.Modal(document.getElementById('audioPlayerModal'));
    const audioElement = document.getElementById('audioElement');
    const audioTitle = document.getElementById('audioTitle');
    const audioDescription = document.getElementById('audioDescription');
    const audioLevelBadge = document.getElementById('audioLevelBadge');
    const audioComplexity = document.getElementById('audioComplexity');
    
    // Store current audio info
    currentAudioUrl = audioFile;
    currentAudioId = audioId;
    currentAudioLevel = level;
    audioTranscript = transcript || '';
    
    // Set audio source and info
    audioElement.src = audioFile;
    audioTitle.textContent = title;
    audioDescription.textContent = description;
    
    // Set level badge
    audioLevelBadge.textContent = level.charAt(0).toUpperCase() + level.slice(1);
    audioLevelBadge.className = `badge level-badge-${level}`;
    
    // Set complexity text
    const complexityText = {
        beginner: "Basic vocabulary, simple sentences",
        intermediate: "Varied vocabulary, complex structures",
        advanced: "Sophisticated vocabulary, native-like fluency"
    };
    audioComplexity.textContent = complexityText[level] || "Language sample";
    
    // Display transcript if available
    displayTranscript(transcript);
    
    // Show modal
    audioModal.show();
    
    // Play audio automatically
    setTimeout(() => {
        audioElement.play().catch(e => console.log("Autoplay prevented:", e));
    }, 500);
    
    // Increment view count
    incrementViewCount('example');
}

// Display transcript from database
function displayTranscript(transcript) {
    const transcriptPlaceholder = document.getElementById('transcriptPlaceholder');
    const transcriptContent = document.getElementById('transcriptContent');
    const transcriptText = document.getElementById('transcriptText');
    const copyBtn = document.getElementById('copyTranscriptBtn');
    
    if (transcript && transcript.trim() !== '') {
        // Show transcript
        transcriptPlaceholder.classList.add('d-none');
        transcriptContent.classList.remove('d-none');
        transcriptText.textContent = transcript;
        copyBtn.classList.remove('d-none');
    } else {
        // Hide transcript
        transcriptPlaceholder.classList.remove('d-none');
        transcriptContent.classList.add('d-none');
        copyBtn.classList.add('d-none');
    }
}

// Copy transcript to clipboard
function copyTranscriptToClipboard() {
    if (!audioTranscript || audioTranscript.trim() === '') {
        showToast('No transcript to copy', 'error');
        return;
    }
    
    navigator.clipboard.writeText(audioTranscript).then(() => {
        showToast('Transcript copied to clipboard!', 'success');
    }).catch(err => {
        console.error('Failed to copy:', err);
        showToast('Failed to copy transcript', 'error');
    });
}

// Show toast notification
function showToast(message, type = 'info') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.custom-toast');
    existingToasts.forEach(toast => toast.remove());
    
    // Create toast
    const toast = document.createElement('div');
    toast.className = `custom-toast toast-${type}`;
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2 text-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Style toast
    Object.assign(toast.style, {
        position: 'fixed',
        bottom: '20px',
        right: '20px',
        background: 'white',
        border: '1px solid var(--royal-gold)',
        borderRadius: 'var(--border-radius-sm)',
        padding: '12px 20px',
        boxShadow: 'var(--shadow)',
        zIndex: '9999',
        animation: 'slideIn 0.3s ease-out'
    });
    
    // Add to page
    document.body.appendChild(toast);
    
    // Remove after 3 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.style.animation = 'slideOut 0.3s ease-in';
            setTimeout(() => toast.remove(), 300);
        }
    }, 3000);
}

// Add slideOut animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Video player function
function playVideo(videoUrl, platform, title, description, videoId) {
    const videoModal = new bootstrap.Modal(document.getElementById('videoPlayerModal'));
    const videoContainer = document.getElementById('videoContainer');
    const videoTitle = document.getElementById('videoTitle');
    const videoDescription = document.getElementById('videoDescription');
    const videoCategoryBadge = document.getElementById('videoCategoryBadge');
    const videoDuration = document.getElementById('videoDuration');
    
    // Clear previous content
    videoContainer.innerHTML = '';
    
    // Create video embed based on platform
    let videoEmbed;
    if (platform === 'youtube') {
        const ytId = extractYouTubeId(videoUrl);
        videoEmbed = `
            <iframe src="https://www.youtube.com/embed/${ytId}?autoplay=1" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen></iframe>
        `;
    } else if (platform === 'vimeo') {
        const vimeoId = extractVimeoId(videoUrl);
        videoEmbed = `
            <iframe src="https://player.vimeo.com/video/${vimeoId}?autoplay=1" 
                    frameborder="0" 
                    allow="autoplay; fullscreen; picture-in-picture" 
                    allowfullscreen></iframe>
        `;
    } else {
        // Determine the video type based on file extension for custom videos
        let videoType = 'video/mp4'; // default
        if (videoUrl.toLowerCase().includes('.webm')) {
            videoType = 'video/webm';
        } else if (videoUrl.toLowerCase().includes('.ogg') || videoUrl.toLowerCase().includes('.ogv')) {
            videoType = 'video/ogg';
        } else if (videoUrl.toLowerCase().includes('.mov')) {
            videoType = 'video/quicktime';
        } else if (videoUrl.toLowerCase().includes('.avi')) {
            videoType = 'video/x-msvideo';
        } else if (videoUrl.toLowerCase().includes('.wmv')) {
            videoType = 'video/x-ms-wmv';
        } else if (videoUrl.toLowerCase().includes('.flv')) {
            videoType = 'video/x-flv';
        } else if (videoUrl.toLowerCase().includes('.m4v')) {
            videoType = 'video/mp4';
        } else if (videoUrl.toLowerCase().includes('.3gp')) {
            videoType = 'video/3gpp';
        }
        
        videoEmbed = `
            <video controls autoplay class="w-100" preload="metadata">
                <source src="${videoUrl}" type="${videoType}">
                Your browser does not support the video tag.
            </video>
        `;
    }
    
    videoContainer.innerHTML = videoEmbed;
    
    // Set video info
    videoTitle.textContent = title;
    videoDescription.textContent = description;
    
    // Extract category from description or use default
    const category = extractCategoryFromDescription(description) || 'Education';
    videoCategoryBadge.textContent = category;
    videoCategoryBadge.className = 'badge bg-light text-dark border border-royal-gold';
    videoDuration.textContent = 'Click to play';
    
    // Show modal
    videoModal.show();
    
    // Increment view count
    incrementViewCount('video');
}

// Helper functions for video ID extraction
function extractYouTubeId(url) {
    const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
    const match = url.match(regExp);
    return (match && match[7].length === 11) ? match[7] : 'dQw4w9WgXcQ';
}

function extractVimeoId(url) {
    const regExp = /^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/;
    const match = url.match(regExp);
    return match ? match[5] : '148751763';
}

function extractCategoryFromDescription(description) {
    const categories = ['IELTS', 'TOEFL', 'Grammar', 'Vocabulary', 'Speaking', 'Writing', 'Listening', 'Reading'];
    for (const category of categories) {
        if (description.toLowerCase().includes(category.toLowerCase())) {
            return category;
        }
    }
    return null;
}

// Simulated function to increment download/view counts
function incrementDownload(id, type) {
    console.log(`Incrementing download count for ${type} ID: ${id}`);
    // In production: fetch(`increment_download.php?type=${type}&id=${id}`, { method: 'POST' });
}

function incrementViewCount(type) {
    console.log(`Incrementing view count for ${type}`);
    // In production: fetch(`increment_view.php?type=${type}`, { method: 'POST' });
}
</script>

<?php include 'footer.php'; ?>