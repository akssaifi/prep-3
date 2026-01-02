<?php
// Premium Database Configuration - Black & Gold Theme
session_start();

// Environment-based configuration
define('ENVIRONMENT', 'development');
define('SITE_URL', 'http://localhost/prepwithdaljeet');
define('ADMIN_PATH', '/admin');

if (ENVIRONMENT === 'production') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'prepwithdaljeet');
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    define('DB_HOST', 'localhost');
    define('DB_USER', 'u211483608_prep');
    define('DB_PASS', '+2l:KAhN');
    define('DB_NAME', 'u211483608_prep');
}

// Create connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    if (ENVIRONMENT === 'development') {
        die("Database connection failed: " . mysqli_connect_error());
    } else {
        error_log("Database connection failed: " . mysqli_connect_error());
        die("System maintenance in progress. Please try again later.");
    }
}

mysqli_set_charset($conn, 'utf8mb4');

// Enhanced table structure with new features
$tables_sql = [
    // Premium Settings Table - Black & Gold Theme
    "CREATE TABLE IF NOT EXISTS settings (
        id INT PRIMARY KEY AUTO_INCREMENT,
        site_name VARCHAR(100) NOT NULL DEFAULT 'Prep with Daljeet',
        tagline VARCHAR(200),
        logo_url VARCHAR(255),
        favicon_url VARCHAR(255),
        primary_color VARCHAR(7) DEFAULT '#D4AF37',
        secondary_color VARCHAR(7) DEFAULT '#000000',
        accent_color VARCHAR(7) DEFAULT '#FFD700',
        facebook_url VARCHAR(255),
        instagram_url VARCHAR(255),
        twitter_url VARCHAR(255),
        linkedin_url VARCHAR(255),
        youtube_url VARCHAR(255),
        show_facebook BOOLEAN DEFAULT 1,
        show_instagram BOOLEAN DEFAULT 1,
        show_twitter BOOLEAN DEFAULT 1,
        show_linkedin BOOLEAN DEFAULT 1,
        show_youtube BOOLEAN DEFAULT 1,
        primary_phone VARCHAR(20) NOT NULL,
        secondary_phone VARCHAR(20),
        whatsapp_number VARCHAR(20),
        email VARCHAR(100) NOT NULL,
        address TEXT,
        google_map_embed TEXT,
        enable_whatsapp_chat BOOLEAN DEFAULT 1,
        enable_newsletter BOOLEAN DEFAULT 1,
        admin_theme ENUM('dark-gold', 'royal-purple', 'deep-blue', 'emerald-green') DEFAULT 'dark-gold',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // Courses Table
    "CREATE TABLE IF NOT EXISTS courses (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        slug VARCHAR(120) UNIQUE NOT NULL,
        short_description VARCHAR(200),
        full_description LONGTEXT,
        icon VARCHAR(50) DEFAULT 'fas fa-book',
        cover_image VARCHAR(255),
        sort_order INT DEFAULT 0,
        is_featured BOOLEAN DEFAULT 0,
        is_popular BOOLEAN DEFAULT 0,
        status ENUM('active', 'inactive', 'coming_soon') DEFAULT 'active',
        meta_title VARCHAR(150),
        meta_description VARCHAR(300),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_status_order (status, sort_order),
        INDEX idx_featured (is_featured),
        INDEX idx_popular (is_popular)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // Subjects Table (Belongs to Courses)
    "CREATE TABLE IF NOT EXISTS subjects (
        id INT PRIMARY KEY AUTO_INCREMENT,
        course_id INT NOT NULL,
        subject_name VARCHAR(100) NOT NULL,
        slug VARCHAR(120) UNIQUE NOT NULL,
        duration VARCHAR(50),
        duration_type ENUM('hours', 'days', 'weeks', 'months') DEFAULT 'weeks',
        price DECIMAL(12,2) NOT NULL DEFAULT 0.00,
        discount_price DECIMAL(12,2),
        currency VARCHAR(3) DEFAULT 'INR',
        description TEXT,
        highlights TEXT,
        features JSON,
        what_you_learn TEXT,
        prerequisites TEXT,
        is_bestseller BOOLEAN DEFAULT 0,
        is_recommended BOOLEAN DEFAULT 0,
        display_order INT DEFAULT 0,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE ON UPDATE CASCADE,
        INDEX idx_course_status (course_id, status),
        INDEX idx_bestseller (is_bestseller),
        INDEX idx_recommended (is_recommended)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // Books/Study Material Table (Can be independent or linked to subjects)
    "CREATE TABLE IF NOT EXISTS books (
        id INT PRIMARY KEY AUTO_INCREMENT,
        subject_id INT NULL,
        title VARCHAR(200) NOT NULL,
        slug VARCHAR(150) UNIQUE NOT NULL,
        author VARCHAR(100),
        publisher VARCHAR(100),
        edition VARCHAR(50),
        isbn VARCHAR(50),
        description TEXT,
        cover_image VARCHAR(255),
        file_url VARCHAR(255),
        file_size VARCHAR(20),
        file_format VARCHAR(10),
        pages INT,
        language VARCHAR(50) DEFAULT 'English',
        price DECIMAL(10,2) DEFAULT 0.00,
        is_free BOOLEAN DEFAULT 0,
        is_independent BOOLEAN DEFAULT 0,
        download_count INT DEFAULT 0,
        view_count INT DEFAULT 0,
        is_featured BOOLEAN DEFAULT 0,
        is_recommended BOOLEAN DEFAULT 0,
        status ENUM('published', 'draft', 'archived') DEFAULT 'draft',
        published_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE SET NULL ON UPDATE CASCADE,
        INDEX idx_subject_status (subject_id, status),
        INDEX idx_independent (is_independent),
        INDEX idx_featured (is_featured),
        INDEX idx_recommended (is_recommended),
        FULLTEXT idx_search (title, author, description)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // Speaking Examples Table
    "CREATE TABLE IF NOT EXISTS speaking_examples (
        id INT PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(200) NOT NULL,
        description TEXT,
        audio_file VARCHAR(255),
        file_size VARCHAR(20),
        duration VARCHAR(20),
        transcript TEXT,
        level ENUM('beginner', 'intermediate', 'advanced') DEFAULT 'intermediate',
        tags VARCHAR(255),
        download_count INT DEFAULT 0,
        view_count INT DEFAULT 0,
        is_featured BOOLEAN DEFAULT 0,
        status ENUM('published', 'draft') DEFAULT 'draft',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_level_status (level, status),
        FULLTEXT idx_search (title, description, transcript)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // Video Resources Table
    "CREATE TABLE IF NOT EXISTS video_resources (
        id INT PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(200) NOT NULL,
        description TEXT,
        video_url VARCHAR(500),
        thumbnail_url VARCHAR(255),
        platform ENUM('youtube', 'vimeo', 'custom') DEFAULT 'youtube',
        duration VARCHAR(20),
        category VARCHAR(100),
        tags VARCHAR(255),
        view_count INT DEFAULT 0,
        is_featured BOOLEAN DEFAULT 0,
        status ENUM('published', 'draft') DEFAULT 'draft',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_category_status (category, status),
        FULLTEXT idx_search (title, description)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // Blogs Table
    "CREATE TABLE IF NOT EXISTS blogs (
        id INT PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(200) NOT NULL,
        slug VARCHAR(225) UNIQUE NOT NULL,
        image VARCHAR(255),
        content LONGTEXT,
        excerpt TEXT,
        meta_title VARCHAR(150),
        meta_description VARCHAR(300),
        author VARCHAR(100) DEFAULT 'Admin',
        status ENUM('published', 'draft') DEFAULT 'draft',
        view_count INT DEFAULT 0,
        is_featured BOOLEAN DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_status (status),
        INDEX idx_featured (is_featured),
        INDEX idx_created_at (created_at),
        FULLTEXT idx_search (title, content, excerpt)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"

];

// Execute table creation
foreach ($tables_sql as $sql) {
    if (!mysqli_query($conn, $sql)) {
        error_log("Table creation failed: " . mysqli_error($conn));
    }
}

// Insert premium default settings with Black & Gold theme
$check_settings = "SELECT COUNT(*) as count FROM settings";
$result = mysqli_query($conn, $check_settings);
$row = mysqli_fetch_assoc($result);

if ($row['count'] == 0) {
    $default_settings = "INSERT INTO settings 
        (site_name, tagline, primary_color, secondary_color, accent_color, 
         facebook_url, instagram_url, twitter_url, linkedin_url, youtube_url,
         show_facebook, show_instagram, show_twitter, show_linkedin, show_youtube,
         primary_phone, email, enable_whatsapp_chat, whatsapp_number, address, admin_theme) 
        VALUES 
        ('Prep with Daljeet', 'Excellence in Education & Immigration', 
         '#D4AF37', '#000000', '#FFD700',
         'https://facebook.com/prepwithdaljeet',
         'https://instagram.com/prepwithdaljeet',
         'https://twitter.com/prepwithdaljeet',
         'https://linkedin.com/company/prepwithdaljeet',
         'https://youtube.com/c/prepwithdaljeet',
         1, 1, 1, 1, 1,
         '+91 98765 43210', 'info@prepwithdaljeet.com', 1, '919876543210', '123 Test Prep Road, City, India', 'dark-gold')";
    mysqli_query($conn, $default_settings);
}

// Helper Functions
function sanitize($input)
{
    global $conn;
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    // Check if input is not null before trimming
    if ($input === null) {
        return '';
    }
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8'));
}

function generateSlug($string)
{
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
    $slug = strtolower($slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

function isLoggedIn()
{
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function redirectToLogin()
{
    header('Location: login.php');
    exit();
}

function getSettings($conn)
{
    $sql = "SELECT * FROM settings LIMIT 1";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

function formatCurrency($amount, $currency = 'INR')
{
    $symbols = [
        'INR' => '₹',
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£'
    ];
    $symbol = $symbols[$currency] ?? $currency;
    return $symbol . number_format($amount, 2);
}

function getDashboardStats($conn)
{
    $stats = [];

    $queries = [
        'total_courses' => "SELECT COUNT(*) as count FROM courses WHERE status='active'",
        'total_subjects' => "SELECT COUNT(*) as count FROM subjects WHERE status='active'",
        'total_books' => "SELECT COUNT(*) as count FROM books WHERE status='published'",
        'total_blogs' => "SELECT COUNT(*) as count FROM blogs WHERE status='published'",
        'featured_courses' => "SELECT COUNT(*) as count FROM courses WHERE is_featured=1 AND status='active'",
        'bestsellers' => "SELECT COUNT(*) as count FROM subjects WHERE is_bestseller=1 AND status='active'",
        'free_materials' => "SELECT COUNT(*) as count FROM books WHERE is_free=1 AND is_independent=1 AND status='published'"
    ];

    foreach ($queries as $key => $query) {
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $stats[$key] = $row['count'];
    }

    return $stats;
}

// Frontend Functions
function getAllActiveCourses($conn)
{
    $sql = "SELECT id, name, slug, short_description, icon, cover_image FROM courses WHERE status = 'active' ORDER BY sort_order ASC, name ASC";
    $result = mysqli_query($conn, $sql);
    $courses = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $courses[] = $row;
        }
    }
    return $courses;
}

function getSubjectsForCourse($conn, $course_slug)
{
    $safe_slug = sanitize($course_slug);
    $sql = "SELECT s.id, s.subject_name, s.slug, s.price, s.discount_price, s.currency, s.duration, 
                   s.duration_type, s.is_bestseller, s.description, c.name as course_name 
            FROM subjects s 
            INNER JOIN courses c ON s.course_id = c.id 
            WHERE c.slug = '$safe_slug' AND s.status = 'active' 
            ORDER BY s.display_order ASC";

    $result = mysqli_query($conn, $sql);
    $subjects = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $subjects[] = $row;
        }
    }
    return $subjects;
}

function getBooksForSubject($conn, $subject_slug)
{
    $safe_slug = sanitize($subject_slug);
    $sql = "SELECT b.id, b.title, b.author, b.price, b.is_free, b.cover_image, b.file_url, 
                   b.file_format, b.download_count, s.subject_name 
            FROM books b 
            INNER JOIN subjects s ON b.subject_id = s.id 
            WHERE s.slug = '$safe_slug' AND b.status = 'published' 
            ORDER BY b.is_featured DESC, b.title ASC";

    $result = mysqli_query($conn, $sql);
    $books = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }
    }
    return $books;
}

function getFeaturedCourses($conn, $limit = 4)
{
    $limit = (int) $limit;
    $sql = "SELECT id, name, slug, short_description, icon, cover_image FROM courses WHERE status = 'active' AND is_featured = 1 ORDER BY sort_order ASC, name ASC LIMIT $limit";
    $result = mysqli_query($conn, $sql);
    $courses = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $courses[] = $row;
        }
    }
    return $courses;
}

function getFreeStudyMaterials($conn, $limit = 6)
{
    $limit = (int) $limit;
    $sql = "SELECT id, title, slug, author, description, cover_image, file_url, file_format, download_count 
            FROM books 
            WHERE is_free = 1 AND status = 'published' 
            ORDER BY is_featured DESC, created_at DESC 
            LIMIT $limit";
    $result = mysqli_query($conn, $sql);
    $materials = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $materials[] = $row;
        }
    }
    return $materials;
}

function getSpeakingExamples($conn, $limit = 6)
{
    $limit = (int) $limit;
    $sql = "SELECT id, title, description, audio_file, duration, level, download_count 
            FROM speaking_examples 
            WHERE status = 'published' 
            ORDER BY is_featured DESC, created_at DESC 
            LIMIT $limit";
    $result = mysqli_query($conn, $sql);
    $examples = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $examples[] = $row;
        }
    }
    return $examples;
}

function getVideoResources($conn, $limit = 6)
{
    $limit = (int) $limit;
    $sql = "SELECT id, title, description, video_url, thumbnail_url, platform, duration, category 
            FROM video_resources 
            WHERE status = 'published' 
            ORDER BY is_featured DESC, created_at DESC 
            LIMIT $limit";
    $result = mysqli_query($conn, $sql);
    $videos = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $videos[] = $row;
        }
    }
    return $videos;
}

// Email Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');

// File Upload Function
function uploadFile($file, $upload_dir = 'uploads/')
{
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $filename = time() . '_' . basename($file['name']);
    $target_path = $upload_dir . $filename;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        return $target_path;
    }
    return false;
}

// Security - Hide admin.php from public
if (strpos($_SERVER['REQUEST_URI'], 'admin.php') !== false && !isLoggedIn()) {
    redirectToLogin();
}
function getAllActiveServices($conn, $category = null)
{
    $sql = "SELECT * FROM services WHERE is_active = 1";
    if ($category) {
        $sql .= " AND category = '$category'";
    }
    $sql .= " ORDER BY display_order ASC, service_name ASC";
    $result = mysqli_query($conn, $sql);
    $services = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $services[] = $row;
        }
    }
    return $services;
}

function getFeaturedServices($conn, $limit = 3)
{
    $limit = (int) $limit;
    $sql = "SELECT * FROM services WHERE is_active = 1 AND is_featured = 1 ORDER BY display_order ASC, service_name ASC LIMIT $limit";
    $result = mysqli_query($conn, $sql);
    $services = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $services[] = $row;
        }
    }
    return $services;
}

function getServiceCategories($conn)
{
    $sql = "SELECT DISTINCT category FROM services WHERE is_active = 1 ORDER BY category";
    $result = mysqli_query($conn, $sql);
    $categories = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row['category'];
        }
    }
    return $categories;
}

// Blog Functions
function getAllBlogs($conn, $limit = null)
{
    $sql = "SELECT * FROM blogs WHERE status = 'published' ORDER BY created_at DESC";
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    $result = mysqli_query($conn, $sql);
    $blogs = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $blogs[] = $row;
        }
    }
    return $blogs;
}

function getBlogById($conn, $id)
{
    $id = (int)$id;
    $sql = "SELECT * FROM blogs WHERE id = $id AND status = 'published'";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

function getAllBlogsAdmin($conn)
{
    $sql = "SELECT * FROM blogs ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
    $blogs = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $blogs[] = $row;
        }
    }
    return $blogs;
}
?>