<?php
require_once 'config.php';
$settings = getSettings($conn);
$featured_courses = getFeaturedCourses($conn, 6);
$free_materials = getFreeStudyMaterials($conn, 3);
$speaking_examples = getSpeakingExamples($conn, 3);
$video_resources = getVideoResources($conn, 3);
$all_courses = getAllActiveCourses($conn);

// Get additional stats
$stats = getDashboardStats($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($settings['site_name']); ?> | Excellence in Education & Immigration</title>
    
    <!-- Meta Tags for SEO -->
    <meta name="description" content="Prep with Daljeet offers premium IELTS, PTE, CELPIP coaching and language training courses. Expert faculty, proven methodology, and excellent success rates.">
    <meta name="keywords" content="IELTS coaching, PTE training, CELPIP preparation, English language courses, immigration consultation">
    <meta name="author" content="Prep with Daljeet">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($settings['site_name']); ?>">
    <meta property="og:description" content="Premium education and immigration services with proven track record">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo SITE_URL; ?>">
    <?php require 'header.php'; ?>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Cinzel:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Compiled Custom CSS - Light & Gold Theme -->
    <style>
        :root {
            /* Light & Gold Color Palette */
            --royal-light: #FFFFFF;
            --royal-lighter: #F8F9FA;
            --royal-light-accent: #F0F2F5;
            --royal-white: #FFFFFF;
            --royal-gold: #D4AF37;
            --royal-gold-light: #FFD700;
            --royal-gold-dark: #B7950B;
            --royal-accent: #C9A227;
            --royal-dark: #2C3E50;
            --royal-darker: #1A252F;
            --royal-gray: #6C757D;
            --royal-gray-light: #8D99A7;
            --royal-text: #2C3E50;
            --royal-text-light: #495057;
            --royal-text-dark: #6C757D;
            --royal-success: #28A745;
            --royal-warning: #FFC107;
            --royal-danger: #DC3545;
            --royal-info: #17A2B8;
            --royal-primary: #007BFF;
            --royal-border: #E9ECEF;
            
            /* Layout Variables */
            --border-radius: 12px;
            --border-radius-sm: 8px;
            --border-radius-lg: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.12);
            --glow: 0 0 30px rgba(212, 175, 55, 0.15);
            --gold-gradient: linear-gradient(135deg, var(--royal-gold), var(--royal-gold-light));
            --light-gradient: linear-gradient(135deg, var(--royal-white), var(--royal-lighter));
            --navbar-height: 80px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--royal-white);
            color: var(--royal-text);
            min-height: 100vh;
            overflow-x: hidden;
            line-height: 1.6;
            padding-top: var(--navbar-height);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Cinzel', serif;
            font-weight: 700;
            color: var(--royal-dark);
        }
        
        a {
            text-decoration: none;
            color: inherit;
            transition: var(--transition);
        }
        
        .container-custom {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        @media (max-width: 768px) {
            .container-custom {
                padding: 0 1rem;
            }
        }
        
        /* Premium Navigation */
        .navbar-royal {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
            box-shadow: var(--shadow-sm);
            height: var(--navbar-height);
            display: flex;
            align-items: center;
        }
        
        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .navbar-brand-royal {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-family: 'Cinzel', serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--royal-gold);
            position: relative;
            padding: 0;
            z-index: 1001;
        }
        
        .logo-royal {
            width: 45px;
            height: 45px;
            background: var(--gold-gradient);
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--royal-white);
            font-size: 1.5rem;
            border: 2px solid var(--royal-gold);
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .brand-text {
            display: flex;
            flex-direction: column;
        }
        
        .brand-title {
            font-weight: 700;
            line-height: 1.2;
            color: var(--royal-dark);
        }
        
        .brand-subtitle {
            font-size: 0.75rem;
            color: var(--royal-gray);
            font-weight: 400;
            letter-spacing: 0.5px;
        }
        
        .nav-main {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0 auto;
        }
        
        @media (max-width: 1200px) {
            .nav-main {
                display: none;
            }
        }
        
        .nav-link-royal {
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--royal-text-light);
            padding: 0.75rem 1.25rem !important;
            border-radius: var(--border-radius-sm);
            position: relative;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }
        
        .nav-link-royal:hover,
        .nav-link-royal.active {
            color: var(--royal-gold);
            background: rgba(212, 175, 55, 0.08);
        }
        
        .nav-link-royal::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--gold-gradient);
            border-radius: 2px;
            transition: var(--transition);
        }
        
        .nav-link-royal:hover::after {
            width: 60%;
        }
        
        .dropdown-royal {
            position: relative;
        }
        
        .dropdown-menu-royal {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-xl);
            padding: 0.75rem;
            min-width: 300px;
            margin-top: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            position: absolute;
            z-index: 1000;
        }
        
        .dropdown-royal:hover .dropdown-menu-royal {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-item-royal {
            padding: 0.85rem 1rem;
            border-radius: var(--border-radius-sm);
            font-weight: 500;
            color: var(--royal-text);
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.25rem;
            transition: var(--transition);
        }
        
        .dropdown-item-royal:hover {
            background: rgba(212, 175, 55, 0.1);
            color: var(--royal-gold);
            transform: translateX(5px);
        }
        
        .dropdown-header-royal {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--royal-gold);
            font-weight: 600;
            padding: 0.5rem 1rem;
            margin-top: 0.5rem;
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        }
        
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-shrink: 0;
        }
        
        @media (max-width: 1200px) {
            .nav-actions {
                display: none;
            }
        }
        
        .btn-royal {
            background: var(--gold-gradient);
            color: var(--royal-white);
            border: none;
            padding: 0.75rem 1.75rem;
            border-radius: var(--border-radius);
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: var(--shadow);
        }
        
        .btn-royal:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: var(--royal-white);
        }
        
        .btn-outline-gold {
            color: var(--royal-gold);
            border: 2px solid var(--royal-gold);
            background: transparent;
            padding: 0.75rem 1.75rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-outline-gold:hover {
            background: var(--royal-gold);
            color: var(--royal-white);
            transform: translateY(-2px);
        }
        
        .mobile-toggle {
            display: none;
            background: transparent;
            border: none;
            color: var(--royal-gold);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            z-index: 1001;
        }
        
        @media (max-width: 1200px) {
            .mobile-toggle {
                display: block;
            }
        }
        
        /* Enhanced Carousel */
        .hero-section {
            margin-top: calc(-1 * var(--navbar-height));
            position: relative;
        }
        
        .royal-carousel {
            position: relative;
            overflow: hidden;
            width: 100%;
            height: 100vh;
            min-height: 700px;
        }
        
        @media (max-width: 992px) {
            .royal-carousel {
                height: 80vh;
                min-height: 600px;
            }
        }
        
        @media (max-width: 768px) {
            .royal-carousel {
                height: 70vh;
                min-height: 500px;
            }
        }
        
        @media (max-width: 576px) {
            .royal-carousel {
                height: 60vh;
                min-height: 450px;
            }
        }
        
        .carousel-track {
            position: relative;
            width: 100%;
            height: 100%;
        }
        
        .carousel-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.8s ease, transform 1s ease;
            pointer-events: none;
        }
        
        .carousel-slide.active {
            opacity: 1;
            pointer-events: all;
            z-index: 1;
        }
        
        .slide-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 1;
            transform: scale(1.1);
            transition: transform 10s ease;
        }
        
        .carousel-slide.active .slide-image {
            transform: scale(1);
        }
        
        .slide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                rgba(255, 255, 255, 0.9) 0%,
                rgba(255, 255, 255, 0.6) 50%,
                rgba(255, 255, 255, 0.3) 100%);
            z-index: 2;
        }
        
        .slide-content {
            position: relative;
            z-index: 3;
            height: 100%;
            display: flex;
            align-items: center;
        }
        
        .hero-badge {
            animation: fadeInUp 0.8s ease 0.3s both;
        }
        
        .badge-gold {
            display: inline-flex;
            align-items: center;
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid rgba(212, 175, 55, 0.3);
            color: var(--royal-gold);
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .hero-title {
            font-size: 4rem;
            font-weight: 700;
            color: var(--royal-darker);
            line-height: 1.1;
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.8s ease 0.5s both;
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--royal-text-light);
            opacity: 0.9;
            margin-bottom: 2rem;
            animation: fadeInUp 0.8s ease 0.7s both;
        }
        
        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease 0.9s both;
        }
        
        /* Stats Section */
        .stats-section {
            background: var(--royal-lighter);
            padding: 5rem 0;
            position: relative;
            overflow: hidden;
        }
        
        .stats-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gold-gradient);
        }
        
        .stat-item {
            text-align: center;
            padding: 2rem;
            position: relative;
        }
        
        .stat-number {
            font-size: 3.5rem;
            font-weight: 700;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            font-family: 'Cinzel', serif;
        }
        
        .stat-label {
            font-size: 1rem;
            color: var(--royal-gray);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        
        /* Featured Courses */
        .courses-section {
            padding: 6rem 0;
            background: var(--royal-white);
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .section-subtitle {
            color: var(--royal-gold);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 1rem;
            display: block;
        }
        
        .section-title {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--royal-dark);
        }
        
        .section-description {
            color: var(--royal-text-dark);
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .course-card {
            background: var(--royal-white);
            border-radius: var(--border-radius);
            overflow: hidden;
            border: 1px solid var(--royal-border);
            transition: var(--transition);
            height: 100%;
            position: relative;
            box-shadow: var(--shadow-sm);
        }
        
        .course-card:hover {
            transform: translateY(-10px);
            border-color: var(--royal-gold);
            box-shadow: var(--shadow-xl);
        }
        
        .course-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gold-gradient);
            z-index: 2;
        }
        
        .course-image {
            height: 200px;
            width: 100%;
            background: var(--royal-light-accent);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--royal-gold);
            font-size: 4rem;
            position: relative;
            overflow: hidden;
        }
        
        .course-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .course-body {
            padding: 2rem;
        }
        
        .course-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--royal-dark);
        }
        
        .course-description {
            color: var(--royal-text-dark);
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        
        .course-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(212, 175, 55, 0.1);
        }
        
        /* Resources Section */
        .resources-section {
            padding: 6rem 0;
            background: var(--royal-lighter);
        }
        
        .resource-card {
            background: var(--royal-white);
            border-radius: var(--border-radius);
            padding: 3rem 2rem;
            text-align: center;
            border: 1px solid var(--royal-border);
            transition: var(--transition);
            height: 100%;
            box-shadow: var(--shadow-sm);
        }
        
        .resource-card:hover {
            transform: translateY(-5px);
            border-color: var(--royal-gold);
            box-shadow: var(--shadow-lg);
        }
        
        .resource-icon {
            width: 80px;
            height: 80px;
            background: var(--gold-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--royal-white);
            font-size: 2rem;
            margin: 0 auto 2rem;
        }
        
        .resource-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--royal-dark);
        }
        
        .resource-description {
            color: var(--royal-text-dark);
            margin-bottom: 1.5rem;
        }
        
        /* Testimonials */
        .testimonials-section {
            padding: 6rem 0;
            background: var(--royal-white);
        }
        
        .testimonial-card {
            background: var(--royal-white);
            border-radius: var(--border-radius);
            padding: 2.5rem;
            border: 1px solid var(--royal-border);
            position: relative;
            margin: 1rem;
            box-shadow: var(--shadow);
        }
        
        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: -20px;
            left: 30px;
            font-size: 5rem;
            color: var(--royal-gold);
            font-family: serif;
            opacity: 0.2;
        }
        
        .testimonial-text {
            font-style: italic;
            margin-bottom: 2rem;
            color: var(--royal-text);
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--gold-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--royal-white);
            font-weight: bold;
        }
        
        .author-info h4 {
            margin: 0;
            font-size: 1.1rem;
            color: var(--royal-dark);
        }
        
        .author-info p {
            color: var(--royal-text-dark);
            margin: 0;
            font-size: 0.9rem;
        }
        
        /* Demo Form */
        .demo-section {
            padding: 6rem 0;
            background: var(--royal-lighter);
        }
        
        .form-card {
            background: var(--royal-white);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--royal-border);
        }
        
        .form-image {
            height: 100%;
            min-height: 400px;
            background: var(--gold-gradient);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: var(--royal-white);
        }
        
        .form-content {
            padding: 3rem;
        }
        
        .form-title {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: var(--royal-dark);
        }
        
        .form-subtitle {
            color: var(--royal-text-dark);
            margin-bottom: 2rem;
        }
        
        .form-control-custom {
            background: var(--royal-lighter);
            border: 1px solid var(--royal-border);
            border-radius: var(--border-radius-sm);
            color: var(--royal-text);
            padding: 0.875rem 1rem;
            transition: var(--transition);
        }
        
        .form-control-custom:focus {
            background: var(--royal-white);
            border-color: var(--royal-gold);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
            color: var(--royal-text);
        }
        
        /* Footer */
        .footer {
            background: var(--royal-darker);
            padding: 5rem 0 2rem;
            border-top: 1px solid rgba(212, 175, 55, 0.1);
        }
        
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .footer-logo-icon {
            width: 50px;
            height: 50px;
            background: var(--gold-gradient);
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--royal-white);
            font-size: 1.5rem;
        }
        
        .footer-title {
            font-size: 1.5rem;
            color: var(--royal-white);
            margin: 0;
        }
        
        .footer-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }
        
        .footer-links h5 {
            color: var(--royal-gold);
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }
        
        .footer-links ul {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 0.75rem;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.9);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .footer-links a:hover {
            color: var(--royal-gold);
            transform: translateX(5px);
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .social-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(212, 175, 55, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--royal-gold);
            transition: var(--transition);
        }
        
        .social-link:hover {
            background: var(--royal-gold);
            color: var(--royal-darker);
            transform: translateY(-3px);
        }
        
        .copyright {
            text-align: center;
            padding-top: 3rem;
            margin-top: 3rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }
        
        /* Carousel Controls */
        .carousel-control {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(212, 175, 55, 0.3);
            color: var(--royal-gold);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            cursor: pointer;
            z-index: 10;
            transition: var(--transition);
            backdrop-filter: blur(10px);
            opacity: 0.9;
            box-shadow: var(--shadow);
        }
        
        .carousel-control:hover {
            background: var(--royal-gold);
            color: var(--royal-white);
            transform: translateY(-50%) scale(1.1);
            opacity: 1;
        }
        
        .carousel-control.prev {
            left: 2rem;
        }
        
        .carousel-control.next {
            right: 2rem;
        }
        
        /* Carousel Indicators */
        .carousel-indicators {
            position: absolute;
            bottom: 3rem;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 1rem;
            z-index: 10;
        }
        
        .indicator {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: rgba(212, 175, 55, 0.3);
            border: 2px solid transparent;
            cursor: pointer;
            transition: var(--transition);
            padding: 0;
        }
        
        .indicator.active {
            background: var(--royal-gold);
            transform: scale(1.2);
        }
        
        .indicator:hover {
            background: var(--royal-gold);
            transform: scale(1.2);
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        /* Mobile Menu */
        .mobile-menu {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            z-index: 999;
            padding: 5rem 2rem 2rem;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
        }
        
        .mobile-menu.active {
            height: 500px;
            transform: translateX(0);
        }
        
        .mobile-nav {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .mobile-nav .nav-link-royal {
            padding: 1rem 1.5rem !important;
            font-size: 1rem;
            border-left: 3px solid transparent;
            color: var(--royal-text);
        }
        
        .mobile-nav .nav-link-royal.active {
            border-left-color: var(--royal-gold);
            background: rgba(212, 175, 55, 0.1);
            color: var(--royal-gold);
        }
        
        .mobile-actions {
            margin-top: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .mobile-actions .btn-royal,
        .mobile-actions .btn-outline-gold {
            width: 100%;
            justify-content: center;
            padding: 1rem;
        }
        
        /* WhatsApp Float */
        .whatsapp-float {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #25D366, #128C7E);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            box-shadow: var(--shadow-xl);
            z-index: 998;
            transition: var(--transition);
            animation: float 3s ease-in-out infinite;
        }
        
        .whatsapp-float:hover {
            transform: scale(1.1);
            box-shadow: 0 15px 35px rgba(37, 211, 102, 0.4);
        }
        
        /* Responsive Typography */
        @media (max-width: 1200px) {
            .hero-title {
                font-size: 3.5rem;
            }
            
            .section-title {
                font-size: 2.5rem;
            }
        }
        
        @media (max-width: 992px) {
            .hero-title {
                font-size: 3rem;
            }
            
            .section-title {
                font-size: 2.25rem;
            }
            
            .carousel-control {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }
            
            .carousel-control.prev {
                left: 1rem;
            }
            
            .carousel-control.next {
                right: 1rem;
            }
        }
        
        @media (max-width: 768px) {
            :root {
                --navbar-height: 70px;
            }
            
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
            
            .form-image {
                min-height: 300px;
            }
            
            .whatsapp-float {
                bottom: 1.5rem;
                right: 1.5rem;
                width: 55px;
                height: 55px;
                font-size: 24px;
            }
        }
        
        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .hero-buttons {
                flex-direction: column;
            }
            
            .hero-buttons a {
                width: 100%;
                justify-content: center;
            }
            
            .carousel-control {
                display: none;
            }
            
            .carousel-indicators {
                bottom: 2rem;
            }
            
            .section-title {
                font-size: 1.75rem;
            }
            
            .whatsapp-float {
                bottom: 1rem;
                right: 1rem;
                width: 50px;
                height: 50px;
                font-size: 22px;
            }
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--royal-lighter);
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--royal-gold);
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--royal-gold-dark);
        }
        
        /* Alert Styles */
        .alert-royal {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            gap: 1rem;
            z-index: 10000;
            animation: slideInRight 0.3s ease;
            max-width: 400px;
            border-left: 4px solid;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid var(--royal-border);
            box-shadow: var(--shadow-lg);
        }
        
        .alert-success {
            border-left-color: var(--royal-success);
            color: var(--royal-success);
        }
        
        .alert-error {
            border-left-color: var(--royal-danger);
            color: var(--royal-danger);
        }
        
        .alert-close {
            background: none;
            border: none;
            color: inherit;
            font-size: 1.5rem;
            cursor: pointer;
            margin-left: auto;
            padding: 0 0.5rem;
        }
        
        .fade-out {
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Text color utilities */
        .text-gold {
            color: var(--royal-gold) !important;
        }
        
        .text-gold-light {
            color: var(--royal-gold-light) !important;
        }
        
        .text-royal-text-dark {
            color: var(--royal-text-dark) !important;
        }

        /* Mobile menu adjustments for light theme */
        .mobile-nav .nav-link-royal:hover {
            background: rgba(212, 175, 55, 0.08);
            color: var(--royal-gold);
        }
    </style>
</head>
<body>
    <!-- WhatsApp Float Button -->
    <?php if($settings['enable_whatsapp_chat'] && $settings['whatsapp_number']): ?>
    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $settings['whatsapp_number']); ?>?text=Hi%20I'm%20interested%20in%20your%20courses" 
       class="whatsapp-float" target="_blank" title="Chat on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    <?php endif; ?>

<?php include 'header.php';?>

    <!-- Hero Carousel -->
    <section class="hero-section">
        <div class="royal-carousel" id="royalCarousel">
            <div class="carousel-track">
                <!-- Slide 1 -->
                <div class="carousel-slide active" data-slide="0">
                    <div class="slide-image" style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80');"></div>
                    <div class="slide-overlay"></div>
                    <div class="slide-content">
                        <div class="container-custom">
                            <div class="row align-items-center">
                                <div class="col-lg-8 col-md-10">
                                    <div class="hero-badge mb-4">
                                        <span class="badge-gold">
                                            <i class="fas fa-star me-2"></i> Trusted by 2,500+ Students
                                        </span>
                                    </div>
                                    <h1 class="hero-title mb-4">Achieve Your Dream Score with Expert Coaching</h1>
                                    <p class="hero-subtitle mb-5">Join thousands of successful students who achieved their desired IELTS, PTE, and CELPIP scores with our proven methods and personalized approach.</p>
                                    <div class="hero-buttons">
                                        <a href="#demo-form" class="btn-royal btn-lg">
                                            <i class="fas fa-calendar-check me-2"></i> Book Free Demo
                                        </a>
                                        <a href="#courses" class="btn-outline-gold btn-lg">
                                            <i class="fas fa-graduation-cap me-2"></i> Explore Courses
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Slide 2 -->
                <div class="carousel-slide" data-slide="1">
                    <div class="slide-image" style="background-image: url('https://images.unsplash.com/photo-1523580494863-6f3031224c94?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80');"></div>
                    <div class="slide-overlay"></div>
                    <div class="slide-content">
                        <div class="container-custom">
                            <div class="row align-items-center">
                                <div class="col-lg-8 col-md-10">
                                    <div class="hero-badge mb-4">
                                        <span class="badge-gold">
                                            <i class="fas fa-award me-2"></i> Expert Guidance
                                        </span>
                                    </div>
                                    <h1 class="hero-title mb-4">Premium Language Training Programs</h1>
                                    <p class="hero-subtitle mb-5">Master English, French, and other languages with our certified instructors and comprehensive curriculum.</p>
                                    <div class="hero-buttons">
                                        <a href="#courses" class="btn-royal btn-lg">
                                            <i class="fas fa-globe-americas me-2"></i> View Language Courses
                                        </a>
                                        <a href="#demo-form" class="btn-outline-gold btn-lg">
                                            <i class="fas fa-headset me-2"></i> Free Consultation
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Slide 3 -->
                <div class="carousel-slide" data-slide="2">
                    <div class="slide-image" style="background-image: url('https://images.unsplash.com/photo-1501504905252-473c47e087f8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80');"></div>
                    <div class="slide-overlay"></div>
                    <div class="slide-content">
                        <div class="container-custom">
                            <div class="row align-items-center">
                                <div class="col-lg-8 col-md-10">
                                    <div class="hero-badge mb-4">
                                        <span class="badge-gold">
                                            <i class="fas fa-check-circle me-2"></i> Premium Support
                                        </span>
                                    </div>
                                    <h1 class="hero-title mb-4">Study Materials & Resources</h1>
                                    <p class="hero-subtitle mb-5">Access our extensive library of free study materials, speaking examples, and video resources.</p>
                                    <div class="hero-buttons">
                                        <a href="#resources" class="btn-royal btn-lg">
                                            <i class="fas fa-download me-2"></i> Free Resources
                                        </a>
                                        <a href="#demo-form" class="btn-outline-gold btn-lg">
                                            <i class="fas fa-file-alt me-2"></i> Get Materials
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <button class="carousel-control prev" aria-label="Previous slide">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="carousel-control next" aria-label="Next slide">
                <i class="fas fa-chevron-right"></i>
            </button>
            
            <div class="carousel-indicators">
                <button class="indicator active" data-slide="0" aria-label="Go to slide 1"></button>
                <button class="indicator" data-slide="1" aria-label="Go to slide 2"></button>
                <button class="indicator" data-slide="2" aria-label="Go to slide 3"></button>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section" id="stats">
        <div class="container-custom">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $stats['total_courses'] + 5; ?>+</div>
                        <div class="stat-label">Courses</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number">2,500+</div>
                        <div class="stat-label">Successful Students</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number">98%</div>
                        <div class="stat-label">Success Rate</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Expert Trainers</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Courses -->
    <section class="courses-section" id="courses">
        <div class="container-custom">
            <div class="section-header">
                <span class="section-subtitle">Our Programs</span>
                <h2 class="section-title">Featured Courses</h2>
                <p class="section-description">Choose from our comprehensive range of English proficiency and language training programs</p>
            </div>
            
            <div class="row g-4">
                <?php foreach($featured_courses as $course): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="course-card">
                        <div class="course-image">
                            <?php if($course['cover_image']): ?>
                                <img src="<?php echo htmlspecialchars($course['cover_image']); ?>" alt="<?php echo htmlspecialchars($course['name']); ?>">
                            <?php else: ?>
                                <i class="<?php echo htmlspecialchars($course['icon']); ?>"></i>
                            <?php endif; ?>
                        </div>
                        <div class="course-body">
                            <h3 class="course-title"><?php echo htmlspecialchars($course['name']); ?></h3>
                            <p class="course-description"><?php echo htmlspecialchars($course['short_description']); ?></p>
                            <div class="course-meta">
                                <a href="courses.php?slug=<?php echo $course['slug']; ?>" class="btn-royal btn-sm">
                                    <i class="fas fa-info-circle me-2"></i> View Details
                                </a>
                                <?php if($settings['whatsapp_number']): ?>
                                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $settings['whatsapp_number']); ?>?text=I'm%20interested%20in%20<?php echo urlencode($course['name']); ?>" 
                                   class="btn-outline-gold btn-sm" target="_blank">
                                    <i class="fab fa-whatsapp me-1"></i> Enquire
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if(count($all_courses) > 6): ?>
            <div class="text-center mt-5">
                <a href="all_courses.php" class="btn-royal btn-lg">
                    <i class="fas fa-eye me-2"></i> View All Courses
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Resources Section -->
    <section class="resources-section" id="resources">
        <div class="container-custom">
            <div class="section-header">
                <span class="section-subtitle">Free Resources</span>
                <h2 class="section-title">Study Materials & Examples</h2>
                <p class="section-description">Access our premium collection of free resources to enhance your learning</p>
            </div>
            
            <div class="row g-4">
                <?php if(!empty($free_materials)): ?>
                <div class="col-md-4">
                    <div class="resource-card">
                        <div class="resource-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3 class="resource-title">Free Study Materials</h3>
                        <p class="resource-description">Download PDFs, guides, and study materials for various tests</p>
                        <a href="resources.php#free-material" class="btn-royal">
                            <i class="fas fa-download me-2"></i> View Materials
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if(!empty($speaking_examples)): ?>
                <div class="col-md-4">
                    <div class="resource-card">
                        <div class="resource-icon">
                            <i class="fas fa-microphone"></i>
                        </div>
                        <h3 class="resource-title">Speaking Examples</h3>
                        <p class="resource-description">Listen to high-scoring speaking samples with transcripts</p>
                        <a href="resources.php#speaking-examples" class="btn-royal">
                            <i class="fas fa-headphones me-2"></i> Listen Now
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if(!empty($video_resources)): ?>
                <div class="col-md-4">
                    <div class="resource-card">
                        <div class="resource-icon">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <h3 class="resource-title">Video Resources</h3>
                        <p class="resource-description">Watch tutorials, tips, and strategy videos</p>
                        <a href="resources.php#video-resources" class="btn-royal">
                            <i class="fas fa-play me-2"></i> Watch Videos
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="text-center mt-5">
                <a href="resources.php" class="btn-outline-gold btn-lg">
                    <i class="fas fa-external-link-alt me-2"></i> Explore All Resources
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials-section" id="testimonials">
        <div class="container-custom">
            <div class="section-header">
                <span class="section-subtitle">Success Stories</span>
                <h2 class="section-title">What Our Students Say</h2>
                <p class="section-description">Hear from our successful students who achieved their dreams with our guidance</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"The IELTS coaching was exceptional! I scored 8.0 in my first attempt. The faculty's personalized attention made all the difference."</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">RK</div>
                            <div class="author-info">
                                <h4>Rahul Kumar</h4>
                                <p>IELTS Student | Band 8.0</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"PTE coaching helped me achieve 90/90! The mock tests and speaking practice sessions were incredibly helpful."</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">PS</div>
                            <div class="author-info">
                                <h4>Priya Sharma</h4>
                                <p>PTE Student | 90/90 Score</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"The study materials and speaking examples are gold! They helped me improve my pronunciation and fluency significantly."</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">AS</div>
                            <div class="author-info">
                                <h4>Ankit Singh</h4>
                                <p>CELPIP Student | CLB 9</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Demo Form -->
    <section class="demo-section" id="demo-form">
        <div class="container-custom">
            <div class="section-header">
                <span class="section-subtitle">Get Started</span>
                <h2 class="section-title">Book a Free Demo Class</h2>
                <p class="section-description">Experience our teaching methodology firsthand with a free demo session</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="form-card">
                        <div class="row g-0">
                            <div class="col-lg-6">
                                <div class="form-image">
                                    <h3 class="text-white fw-bold mb-4">Experience Excellence</h3>
                                    <p class="text-white mb-4">Book a free demo class and experience our teaching methodology firsthand.</p>
                                    <ul class="list-unstyled text-white">
                                        <li class="mb-3"><i class="fas fa-check-circle me-2"></i> Personalized Attention</li>
                                        <li class="mb-3"><i class="fas fa-check-circle me-2"></i> Expert Faculty</li>
                                        <li class="mb-3"><i class="fas fa-check-circle me-2"></i> Proven Methodology</li>
                                        <li class="mb-3"><i class="fas fa-check-circle me-2"></i> Flexible Schedule</li>
                                        <li><i class="fas fa-check-circle me-2"></i> Free Study Materials</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-content">
                                    <h3 class="form-title">Request Free Demo</h3>
                                    <p class="form-subtitle">Fill in your details and we'll contact you to schedule your demo</p>
                                    
                                    <form id="demoForm" method="POST">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name" class="form-label text-dark">Full Name *</label>
                                                    <input type="text" class="form-control form-control-custom" id="name" name="name" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email" class="form-label text-dark">Email Address *</label>
                                                    <input type="email" class="form-control form-control-custom" id="email" name="email" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phone" class="form-label text-dark">Phone Number *</label>
                                                    <input type="tel" class="form-control form-control-custom" id="phone" name="phone" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="course" class="form-label text-dark">Interested Course</label>
                                                    <select class="form-select form-control-custom" id="course" name="course">
                                                        <option value="">Select a course</option>
                                                        <?php foreach($featured_courses as $course): ?>
                                                        <option value="<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['name']); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="message" class="form-label text-dark">Message (Optional)</label>
                                                    <textarea class="form-control form-control-custom" id="message" name="message" rows="3" placeholder="Any specific requirements or questions..."></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn-royal w-100 py-3">
                                                    <i class="fas fa-paper-plane me-2"></i> Submit Request
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'footer.php';?>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Main JavaScript -->
    <script>
        // Enhanced Carousel Class
        class PremiumCarousel {
            constructor(containerId, options = {}) {
                this.container = document.getElementById(containerId);
                this.slides = this.container.querySelectorAll('.carousel-slide');
                this.indicators = this.container.querySelectorAll('.indicator');
                this.prevBtn = this.container.querySelector('.carousel-control.prev');
                this.nextBtn = this.container.querySelector('.carousel-control.next');
                
                this.currentIndex = 0;
                this.totalSlides = this.slides.length;
                this.isAnimating = false;
                this.autoPlay = true;
                this.interval = null;
                this.autoPlayDelay = options.autoPlayDelay || 6000;
                this.pauseOnHover = options.pauseOnHover !== false;
                
                this.init();
            }
            
            init() {
                // Initialize first slide
                this.slides[0].classList.add('active');
                this.indicators[0].classList.add('active');
                
                // Event listeners
                this.prevBtn.addEventListener('click', () => this.prevSlide());
                this.nextBtn.addEventListener('click', () => this.nextSlide());
                
                // Indicator click events
                this.indicators.forEach((indicator, index) => {
                    indicator.addEventListener('click', () => this.goToSlide(index));
                });
                
                // Keyboard navigation
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowLeft') this.prevSlide();
                    if (e.key === 'ArrowRight') this.nextSlide();
                });
                
                // Touch/swipe support
                this.setupTouchEvents();
                
                // Auto-play
                this.startAutoPlay();
                
                // Pause on hover
                if (this.pauseOnHover) {
                    this.container.addEventListener('mouseenter', () => this.stopAutoPlay());
                    this.container.addEventListener('mouseleave', () => {
                        if (this.autoPlay) this.startAutoPlay();
                    });
                }
            }
            
            setupTouchEvents() {
                let startX = 0;
                let endX = 0;
                
                this.container.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].clientX;
                    this.stopAutoPlay();
                });
                
                this.container.addEventListener('touchmove', (e) => {
                    endX = e.touches[0].clientX;
                });
                
                this.container.addEventListener('touchend', () => {
                    const diff = startX - endX;
                    const threshold = 50;
                    
                    if (Math.abs(diff) > threshold) {
                        if (diff > 0) {
                            this.nextSlide();
                        } else {
                            this.prevSlide();
                        }
                    }
                    
                    if (this.autoPlay) {
                        setTimeout(() => this.startAutoPlay(), 1000);
                    }
                });
            }
            
            updateSlides() {
                if (this.isAnimating) return;
                this.isAnimating = true;
                
                // Remove active classes
                this.slides.forEach(slide => {
                    slide.classList.remove('active');
                });
                
                this.indicators.forEach(indicator => {
                    indicator.classList.remove('active');
                });
                
                // Add active class to current slide
                this.slides[this.currentIndex].classList.add('active');
                this.indicators[this.currentIndex].classList.add('active');
                
                // Reset animation flag
                setTimeout(() => {
                    this.isAnimating = false;
                }, 800);
            }
            
            nextSlide() {
                if (this.isAnimating) return;
                
                this.currentIndex = (this.currentIndex + 1) % this.totalSlides;
                this.updateSlides();
            }
            
            prevSlide() {
                if (this.isAnimating) return;
                
                this.currentIndex = (this.currentIndex - 1 + this.totalSlides) % this.totalSlides;
                this.updateSlides();
            }
            
            goToSlide(index) {
                if (this.isAnimating || index === this.currentIndex) return;
                
                this.currentIndex = index;
                this.updateSlides();
            }
            
            startAutoPlay() {
                if (this.interval) return;
                
                this.interval = setInterval(() => {
                    this.nextSlide();
                }, this.autoPlayDelay);
            }
            
            stopAutoPlay() {
                if (this.interval) {
                    clearInterval(this.interval);
                    this.interval = null;
                }
            }
            
            toggleAutoPlay() {
                if (this.autoPlay) {
                    this.stopAutoPlay();
                } else {
                    this.startAutoPlay();
                }
                this.autoPlay = !this.autoPlay;
            }
        }
        
        // Mobile Menu Toggle
        const mobileToggle = document.getElementById('mobileToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        
        if (mobileToggle && mobileMenu) {
            mobileToggle.addEventListener('click', () => {
                mobileMenu.classList.toggle('active');
                mobileToggle.innerHTML = mobileMenu.classList.contains('active') 
                    ? '<i class="fas fa-times"></i>' 
                    : '<i class="fas fa-bars"></i>';
            });
            
            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!mobileToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
                    mobileMenu.classList.remove('active');
                    mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
                }
            });
            
            // Close menu when clicking links
            mobileMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.remove('active');
                    mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
                });
            });
        }
        
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
                        // Close mobile menu if open
                        if (mobileMenu && mobileMenu.classList.contains('active')) {
                            mobileMenu.classList.remove('active');
                            mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
                        }
                        
                        // Smooth scroll
                        window.scrollTo({
                            top: targetElement.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
        
        // Form Validation and Submission
        const demoForm = document.getElementById('demoForm');
        
        if (demoForm) {
            demoForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(this);
                const formValues = Object.fromEntries(formData);
                
                // Basic validation
                let isValid = true;
                const requiredFields = ['name', 'email', 'phone'];
                
                requiredFields.forEach(field => {
                    const input = this.querySelector(`[name="${field}"]`);
                    if (!input.value.trim()) {
                        isValid = false;
                        input.classList.add('is-invalid');
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });
                
                // Email validation
                const emailInput = this.querySelector('[name="email"]');
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailInput.value && !emailRegex.test(emailInput.value)) {
                    isValid = false;
                    emailInput.classList.add('is-invalid');
                }
                
                if (!isValid) {
                    showAlert('Please fill in all required fields correctly.', 'error');
                    return;
                }
                
                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
                submitBtn.disabled = true;
                
                // Simulate API call (replace with actual fetch to process_demo.php)
                setTimeout(() => {
                    // Success response
                    showAlert('Thank you! Your demo request has been submitted. We will contact you shortly.', 'success');
                    this.reset();
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    
                    // Scroll to top to see success message
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }, 2000);
            });
            
            // Remove validation classes on input
            demoForm.querySelectorAll('input, textarea, select').forEach(input => {
                input.addEventListener('input', () => {
                    input.classList.remove('is-invalid');
                });
            });
        }
        
        // Alert Function
        function showAlert(message, type) {
            // Remove existing alerts
            const existingAlerts = document.querySelectorAll('.alert-royal');
            existingAlerts.forEach(alert => alert.remove());
            
            // Create alert
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
        
        // Animate elements on scroll
        function animateOnScroll() {
            const elements = document.querySelectorAll('.course-card, .resource-card, .testimonial-card, .stat-item');
            
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (elementTop < windowHeight - 100) {
                    element.classList.add('animate__animated', 'animate__fadeInUp');
                }
            });
        }
        
        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize carousel
            const carousel = new PremiumCarousel('royalCarousel', {
                autoPlayDelay: 3000,
                pauseOnHover: true
            });
            
            // Initialize animations
            animateOnScroll();
            window.addEventListener('scroll', animateOnScroll);
            
            // Add CSS for invalid form inputs
            const style = document.createElement('style');
            style.textContent = `
                .is-invalid {
                    border-color: var(--royal-danger) !important;
                    background: rgba(220, 53, 69, 0.05) !important;
                }
                
                .is-invalid:focus {
                    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1) !important;
                }
                
                .form-control-custom {
                    transition: var(--transition);
                }
                
                .animate__animated {
                    animation-duration: 1s;
                    animation-fill-mode: both;
                }
            `;
            document.head.appendChild(style);
            
            // Parallax effect for hero image
            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                const heroImage = document.querySelector('.slide-image');
                if (heroImage) {
                    heroImage.style.transform = `scale(${1 + scrolled * 0.0005})`;
                }
            });
            
            // Intersection Observer for lazy loading
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);
            
            // Observe all cards
            document.querySelectorAll('.course-card, .resource-card, .testimonial-card').forEach(card => {
                observer.observe(card);
            });
        });
        
        // Sticky navbar on scroll
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar-royal');
            if (window.scrollY > 100) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                navbar.style.backdropFilter = 'blur(10px)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
            }
        });
    </script>
</body>
</html>