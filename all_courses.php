<?php
require_once 'config.php';
$settings = getSettings($conn);

// Get all courses including inactive for admin view
$all_courses_query = mysqli_query($conn, "SELECT * FROM courses ORDER BY sort_order DESC, name ASC");
$all_courses = [];
while($course = mysqli_fetch_assoc($all_courses_query)) {
    $all_courses[] = $course;
}

// Get all status counts
$status_counts = [
    'active' => mysqli_num_rows(mysqli_query($conn, "SELECT id FROM courses WHERE status = 'active'")),
    'inactive' => mysqli_num_rows(mysqli_query($conn, "SELECT id FROM courses WHERE status = 'inactive'")),
    'coming_soon' => mysqli_num_rows(mysqli_query($conn, "SELECT id FROM courses WHERE status = 'coming_soon'")),
    'featured' => mysqli_num_rows(mysqli_query($conn, "SELECT id FROM courses WHERE is_featured = 1")),
    'popular' => mysqli_num_rows(mysqli_query($conn, "SELECT id FROM courses WHERE is_popular = 1"))
];

include 'header.php';
?>

<div class="container-custom py-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-light p-3 rounded-3 justify-content-center">
                <li class="breadcrumb-item"><a href="index.php" class="text-gold">Home</a></li>
                <li class="breadcrumb-item active text-dark">All Courses</li>
            </ol>
        </nav>
        
        <span class="text-gold fw-bold text-uppercase letter-spacing-2 d-block mb-2">Complete Catalog</span>
        <h1 class="display-3 fw-bold mb-3 text-dark">All Courses</h1>
        <p class="lead text-muted mb-4">Explore our comprehensive range of language and test preparation courses</p>
        
        <!-- Course Count -->
        <div class="row g-3 justify-content-center mb-5">
            <div class="col-auto">
                <div class="bg-light p-3 rounded-3 border border-royal-gold text-center">
                    <div class="h4 fw-bold text-gold mb-0"><?php echo count($all_courses); ?></div>
                    <div class="small text-muted">Total Courses</div>
                </div>
            </div>
            <div class="col-auto">
                <div class="bg-light p-3 rounded-3 border border-royal-gold text-center">
                    <div class="h4 fw-bold text-success mb-0"><?php echo $status_counts['active']; ?></div>
                    <div class="small text-muted">Active</div>
                </div>
            </div>
            <div class="col-auto">
                <div class="bg-light p-3 rounded-3 border border-royal-gold text-center">
                    <div class="h4 fw-bold text-warning mb-0"><?php echo $status_counts['coming_soon']; ?></div>
                    <div class="small text-muted">Coming Soon</div>
                </div>
            </div>
            <div class="col-auto">
                <div class="bg-light p-3 rounded-3 border border-royal-gold text-center">
                    <div class="h4 fw-bold text-gold mb-0"><?php echo $status_counts['featured']; ?></div>
                    <div class="small text-muted">Featured</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Advanced Search and Filter -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <div class="card border-royal-gold shadow-lg bg-white">
                <div class="card-body p-4">
                    <div class="row g-3 align-items-end">
                        <!-- Search Box -->
                        <div class="col-lg-6">
                            <label for="courseSearch" class="form-label fw-bold text-dark mb-2">Search Courses</label>
                            <div class="search-box position-relative">
                                <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-gold"></i>
                                <input type="text" class="form-control form-control-lg ps-5 bg-light border-royal-gold text-dark" 
                                       id="courseSearch" placeholder="Search by course name, description...">
                                <button class="btn btn-outline-gold position-absolute top-50 end-0 translate-middle-y me-3 d-none" 
                                        id="clearSearch">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Status Filter -->
                        <div class="col-lg-3">
                            <label for="statusFilter" class="form-label fw-bold text-dark mb-2">Status</label>
                            <select class="form-select form-select-lg bg-light border-royal-gold text-dark" id="statusFilter">
                                <option value="all">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="coming_soon">Coming Soon</option>
                            </select>
                        </div>
                        
                        <!-- Type Filter -->
                        <div class="col-lg-3">
                            <label for="typeFilter" class="form-label fw-bold text-dark mb-2">Type</label>
                            <select class="form-select form-select-lg bg-light border-royal-gold text-dark" id="typeFilter">
                                <option value="all">All Types</option>
                                <option value="featured">Featured</option>
                                <option value="popular">Popular</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Advanced Filters (Collapsible) -->
                    <div class="mt-4">
                        <button class="btn btn-link text-gold p-0 d-flex align-items-center" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#advancedFilters" 
                                aria-expanded="false" 
                                aria-controls="advancedFilters">
                            <i class="fas fa-sliders-h me-2"></i>
                            <span class="fw-bold">Advanced Filters</span>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </button>
                        
                        <div class="collapse mt-3" id="advancedFilters">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="sortBy" class="form-label fw-bold text-dark mb-2">Sort By</label>
                                    <select class="form-select bg-light border-royal-gold text-dark" id="sortBy">
                                        <option value="name_asc">Name (A-Z)</option>
                                        <option value="name_desc">Name (Z-A)</option>
                                        <option value="newest">Newest First</option>
                                        <option value="oldest">Oldest First</option>
                                        <option value="featured_first">Featured First</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark mb-2 d-block">Quick Actions</label>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-outline-gold flex-grow-1" onclick="applyFilter('active')">
                                            <i class="fas fa-check-circle me-2"></i> Active Only
                                        </button>
                                        <button class="btn btn-outline-gold flex-grow-1" onclick="applyFilter('featured')">
                                            <i class="fas fa-star me-2"></i> Featured
                                        </button>
                                        <button class="btn btn-outline-gold flex-grow-1" onclick="resetAllFilters()">
                                            <i class="fas fa-redo me-2"></i> Reset All
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Active Filters Display -->
                    <div id="activeFilters" class="mt-3 d-none">
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-3">Active filters:</span>
                            <div class="d-flex flex-wrap gap-2" id="filterTags"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Courses Grid -->
    <div class="row g-4" id="coursesContainer">
        <?php foreach($all_courses as $course): 
            $status_color = $course['status'] === 'active' ? 'success' : ($course['status'] === 'coming_soon' ? 'warning' : 'secondary');
            $status_icon = $course['status'] === 'active' ? 'check-circle' : ($course['status'] === 'coming_soon' ? 'clock' : 'times-circle');
        ?>
        <div class="col-lg-4 col-md-6 course-item" 
             data-id="<?php echo $course['id']; ?>"
             data-name="<?php echo strtolower(htmlspecialchars($course['name'])); ?>"
             data-description="<?php echo strtolower(htmlspecialchars($course['short_description'])); ?>"
             data-status="<?php echo $course['status']; ?>"
             data-featured="<?php echo $course['is_featured'] ? 'yes' : 'no'; ?>"
             data-popular="<?php echo $course['is_popular'] ? 'yes' : 'no'; ?>"
             data-created="<?php echo strtotime($course['created_at']); ?>"
             data-order="<?php echo $course['sort_order']; ?>">
            <div class="card course-card-royal border-royal-gold shadow-hover h-100">
                <?php if(!empty($course['cover_image'])): ?>
                <div class="course-image position-relative" style="height: 200px; overflow: hidden;">
                    <img src="<?php echo htmlspecialchars($course['cover_image']); ?>" 
                         alt="<?php echo htmlspecialchars($course['name']); ?>" 
                         class="img-fluid w-100" style="object-fit: cover; height: 100%;">
                    <div class="position-absolute top-0 end-0 m-3">
                        <?php if($course['is_featured']): ?>
                        <span class="badge bg-gold badge-sm">
                            <i class="fas fa-star me-1"></i> Featured
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="position-absolute top-0 start-0 m-3">
                        <span class="badge bg-<?php echo $status_color; ?> badge-sm">
                            <i class="fas fa-<?php echo $status_icon; ?> me-1"></i> 
                            <?php echo ucfirst(str_replace('_', ' ', $course['status'])); ?>
                        </span>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="course-icon-small me-3">
                            <i class="<?php echo htmlspecialchars($course['icon'] ?: 'fas fa-book'); ?>"></i>
                        </div>
                        <div class="course-meta">
                            <?php if($course['is_popular']): ?>
                            <span class="badge bg-danger badge-sm">
                                <i class="fas fa-fire me-1"></i> Popular
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <h4 class="fw-bold mb-3 text-dark"><?php echo htmlspecialchars($course['name']); ?></h4>
                    <p class="text-muted mb-4" style="min-height: 60px;"><?php echo htmlspecialchars($course['short_description'] ?: 'No description available.'); ?></p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <a href="courses.php?slug=<?php echo $course['slug']; ?>" class="btn-royal btn-sm">
                            <i class="fas fa-info-circle me-2"></i> View Details
                        </a>
                        <?php if(!empty($settings['whatsapp_number'])): ?>
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $settings['whatsapp_number']); ?>?text=I'm%20interested%20in%20<?php echo urlencode($course['name']); ?>" 
                           class="btn-whatsapp-royal btn-sm" target="_blank">
                            <i class="fab fa-whatsapp me-1"></i> Enquire
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Course Footer -->
                <div class="card-footer bg-light border-0 pt-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-sort-numeric-down me-1"></i> Order: <?php echo $course['sort_order']; ?>
                        </small>
                        <small class="text-muted">
                            <?php echo date('M d, Y', strtotime($course['created_at'])); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- No Results Message -->
    <div id="noResults" class="text-center py-5 d-none">
        <div class="icon-circle-lg mx-auto mb-4">
            <i class="fas fa-search text-gold"></i>
        </div>
        <h3 class="fw-bold text-dark mb-3">No Courses Found</h3>
        <p class="text-muted mb-4">Try adjusting your search or filter criteria to find what you're looking for.</p>
        <button class="btn-royal" onclick="resetAllFilters()">
            <i class="fas fa-redo me-2"></i> Reset All Filters
        </button>
    </div>
    
    <!-- Results Count -->
    <div id="resultsCount" class="text-center mt-5">
        <p class="text-muted">Showing <span id="visibleCount"><?php echo count($all_courses); ?></span> of <?php echo count($all_courses); ?> courses</p>
    </div>
</div>

<style>
    /* Light Theme Variables */
    :root {
        --royal-bg: #f8f9fa;
        --royal-light: #ffffff;
        --royal-card: #ffffff;
        --royal-border: rgba(212, 175, 55, 0.2);
        --royal-text: #212529;
        --royal-text-dark: #6c757d;
        --royal-gold: #D4AF37;
        --royal-gold-light: #FFD700;
        --royal-gold-dark: #B7950B;
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
    
    .bg-gold {
        background: var(--gold-gradient) !important;
        color: white !important;
    }
    
    .bg-gold-transparent {
        background: rgba(212, 175, 55, 0.1) !important;
        border: 1px solid rgba(212, 175, 55, 0.2) !important;
    }
    
    /* All Courses Page Styles */
    .letter-spacing-2 {
        letter-spacing: 2px;
    }
    
    .course-icon-small {
        width: 50px;
        height: 50px;
        background: var(--gold-gradient);
        border-radius: var(--border-radius);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        flex-shrink: 0;
    }
    
    .badge-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .course-image {
        border-top-left-radius: var(--border-radius);
        border-top-right-radius: var(--border-radius);
    }
    
    .search-box input:focus {
        border-color: var(--royal-gold);
        box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.25);
    }
    
    .icon-circle-lg {
        width: 80px;
        height: 80px;
        background: rgba(212, 175, 55, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
    }
    
    .course-card-royal {
        transition: var(--transition);
        background: var(--royal-card);
        border: 1px solid var(--royal-border);
    }
    
    .course-card-royal:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg) !important;
        border-color: var(--royal-gold);
    }
    
    .shadow-hover {
        transition: var(--transition);
    }
    
    .shadow-hover:hover {
        box-shadow: var(--shadow) !important;
    }
    
    .btn-royal {
        background: var(--gold-gradient);
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
        box-shadow: 0 0 20px rgba(212, 175, 55, 0.3);
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
    
    .btn-whatsapp-royal.btn-sm {
        padding: 8px 16px;
        font-size: 14px;
    }
    
    .filter-tag {
        background: rgba(212, 175, 55, 0.1);
        border: 1px solid rgba(212, 175, 55, 0.3);
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .filter-tag .btn-close {
        font-size: 0.7rem;
        padding: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .display-3 {
            font-size: 2.5rem;
        }
        
        .course-card-royal {
            margin-bottom: 1.5rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const searchInput = document.getElementById('courseSearch');
    const clearSearch = document.getElementById('clearSearch');
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const sortBy = document.getElementById('sortBy');
    const courseItems = document.querySelectorAll('.course-item');
    const noResults = document.getElementById('noResults');
    const coursesContainer = document.getElementById('coursesContainer');
    const visibleCount = document.getElementById('visibleCount');
    const activeFilters = document.getElementById('activeFilters');
    const filterTags = document.getElementById('filterTags');
    
    // Store all courses for sorting
    let visibleCourses = Array.from(courseItems);
    
    // Filter tags state
    let currentFilters = {
        search: '',
        status: 'all',
        type: 'all',
        sort: 'name_asc'
    };
    
    // Initialize
    updateResultsCount();
    
    // Event Listeners
    searchInput.addEventListener('input', function() {
        currentFilters.search = this.value.toLowerCase();
        if (this.value) {
            clearSearch.classList.remove('d-none');
        } else {
            clearSearch.classList.add('d-none');
        }
        filterCourses();
    });
    
    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        currentFilters.search = '';
        clearSearch.classList.add('d-none');
        filterCourses();
    });
    
    statusFilter.addEventListener('change', function() {
        currentFilters.status = this.value;
        filterCourses();
    });
    
    typeFilter.addEventListener('change', function() {
        currentFilters.type = this.value;
        filterCourses();
    });
    
    sortBy.addEventListener('change', function() {
        currentFilters.sort = this.value;
        filterCourses();
    });
    
    // Function to apply quick filter
    window.applyFilter = function(filterType) {
        switch(filterType) {
            case 'active':
                statusFilter.value = 'active';
                typeFilter.value = 'all';
                break;
            case 'featured':
                statusFilter.value = 'all';
                typeFilter.value = 'featured';
                break;
            case 'popular':
                statusFilter.value = 'all';
                typeFilter.value = 'popular';
                break;
        }
        currentFilters.status = statusFilter.value;
        currentFilters.type = typeFilter.value;
        filterCourses();
    };
    
    // Function to reset all filters
    window.resetAllFilters = function() {
        searchInput.value = '';
        statusFilter.value = 'all';
        typeFilter.value = 'all';
        sortBy.value = 'name_asc';
        clearSearch.classList.add('d-none');
        
        currentFilters = {
            search: '',
            status: 'all',
            type: 'all',
            sort: 'name_asc'
        };
        
        filterCourses();
    };
    
    // Main filter function
    function filterCourses() {
        let filteredCount = 0;
        
        // Filter courses
        visibleCourses = Array.from(courseItems).filter(item => {
            const courseName = item.dataset.name;
            const courseDesc = item.dataset.description || '';
            const isFeatured = item.dataset.featured === 'yes';
            const isPopular = item.dataset.popular === 'yes';
            const status = item.dataset.status;
            
            let shouldShow = true;
            
            // Search filter (search in name and description)
            if (currentFilters.search) {
                const searchTerm = currentFilters.search;
                if (!courseName.includes(searchTerm) && !courseDesc.includes(searchTerm)) {
                    shouldShow = false;
                }
            }
            
            // Status filter
            if (currentFilters.status !== 'all' && status !== currentFilters.status) {
                shouldShow = false;
            }
            
            // Type filter
            if (currentFilters.type === 'featured' && !isFeatured) {
                shouldShow = false;
            }
            if (currentFilters.type === 'popular' && !isPopular) {
                shouldShow = false;
            }
            
            if (shouldShow) {
                filteredCount++;
                return true;
            }
            return false;
        });
        
        // Sort courses
        sortCourses(visibleCourses, currentFilters.sort);
        
        // Display courses
        courseItems.forEach(item => {
            item.style.display = 'none';
        });
        
        visibleCourses.forEach(item => {
            item.style.display = 'block';
        });
        
        // Update UI
        updateResultsCount(filteredCount);
        updateActiveFilters();
        
        // Show/hide no results message
        if (filteredCount === 0) {
            noResults.classList.remove('d-none');
            coursesContainer.classList.add('d-none');
        } else {
            noResults.classList.add('d-none');
            coursesContainer.classList.remove('d-none');
        }
    }
    
    // Sort function
    function sortCourses(courses, sortType) {
        switch(sortType) {
            case 'name_asc':
                courses.sort((a, b) => a.dataset.name.localeCompare(b.dataset.name));
                break;
            case 'name_desc':
                courses.sort((a, b) => b.dataset.name.localeCompare(a.dataset.name));
                break;
            case 'newest':
                courses.sort((a, b) => parseInt(b.dataset.created) - parseInt(a.dataset.created));
                break;
            case 'oldest':
                courses.sort((a, b) => parseInt(a.dataset.created) - parseInt(b.dataset.created));
                break;
            case 'featured_first':
                courses.sort((a, b) => {
                    if (a.dataset.featured === 'yes' && b.dataset.featured !== 'yes') return -1;
                    if (a.dataset.featured !== 'yes' && b.dataset.featured === 'yes') return 1;
                    return a.dataset.name.localeCompare(b.dataset.name);
                });
                break;
        }
        
        // Reorder DOM elements
        const container = document.getElementById('coursesContainer');
        courses.forEach(course => {
            container.appendChild(course);
        });
    }
    
    // Update results count
    function updateResultsCount(count = null) {
        if (count !== null) {
            visibleCount.textContent = count;
        } else {
            visibleCount.textContent = courseItems.length;
        }
    }
    
    // Update active filters display
    function updateActiveFilters() {
        filterTags.innerHTML = '';
        const filters = [];
        
        if (currentFilters.search) {
            filters.push({
                type: 'search',
                label: `Search: "${currentFilters.search}"`,
                remove: function() {
                    searchInput.value = '';
                    currentFilters.search = '';
                    clearSearch.classList.add('d-none');
                    filterCourses();
                }
            });
        }
        
        if (currentFilters.status !== 'all') {
            filters.push({
                type: 'status',
                label: `Status: ${currentFilters.status}`,
                remove: function() {
                    statusFilter.value = 'all';
                    currentFilters.status = 'all';
                    filterCourses();
                }
            });
        }
        
        if (currentFilters.type !== 'all') {
            filters.push({
                type: 'type',
                label: `Type: ${currentFilters.type}`,
                remove: function() {
                    typeFilter.value = 'all';
                    currentFilters.type = 'all';
                    filterCourses();
                }
            });
        }
        
        if (currentFilters.sort !== 'name_asc') {
            const sortLabels = {
                'name_asc': 'Name A-Z',
                'name_desc': 'Name Z-A',
                'newest': 'Newest',
                'oldest': 'Oldest',
                'featured_first': 'Featured First'
            };
            filters.push({
                type: 'sort',
                label: `Sorted: ${sortLabels[currentFilters.sort]}`,
                remove: function() {
                    sortBy.value = 'name_asc';
                    currentFilters.sort = 'name_asc';
                    filterCourses();
                }
            });
        }
        
        // Create filter tags
        filters.forEach(filter => {
            const tag = document.createElement('div');
            tag.className = 'filter-tag';
            tag.innerHTML = `
                ${filter.label}
                <button type="button" class="btn-close" aria-label="Remove"></button>
            `;
            
            tag.querySelector('.btn-close').addEventListener('click', filter.remove);
            filterTags.appendChild(tag);
        });
        
        // Show/hide active filters container
        if (filters.length > 0) {
            activeFilters.classList.remove('d-none');
        } else {
            activeFilters.classList.add('d-none');
        }
    }
    
    // Debounce search input for better performance
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentFilters.search = this.value.toLowerCase();
            filterCourses();
        }, 300);
    });
});
</script>

<?php include 'footer.php'; ?>