<?php
require_once 'config.php';
$settings = getSettings($conn);
$current_page = basename($_SERVER['PHP_SELF']);
$is_home = ($current_page == 'index.php' || $current_page == '');

// Get active courses for navigation
$courses_nav = getAllActiveCourses($conn);
$featured_courses_nav = getFeaturedCourses($conn, 6);

// Get logo and favicon URLs from settings
$logo_url = !empty($settings['logo_url']) ? htmlspecialchars($settings['logo_url']) : '';
$favicon_url = !empty($settings['favicon_url']) ? htmlspecialchars($settings['favicon_url']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($settings['site_name']); ?> | Excellence in Education & Immigration</title>
    
    <!-- Favicon -->
    <?php if($favicon_url): ?>
    <link rel="icon" type="image/x-icon" href="<?php echo $favicon_url; ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $favicon_url; ?>">
    <?php else: ?>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <?php endif; ?>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-17549979663"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-17549979663');
</script>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Cinzel:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Compiled CSS -->
    <style>
        :root {
            /* Light & Blue Color Palette */
            --royal-light: #F0F4F8;
            --royal-lighter: #E6EEF4;
            --royal-light-accent: #D8E4ED;
            --royal-white: #F8FAFC;
            --royal-gold: #1A365D;
            --royal-gold-light: #2D5A87;
            --royal-gold-dark: #0F204E;
            --royal-accent: #2D5A87;
            --royal-dark: #0F204E;
            --royal-darker: #0F204E;
            --royal-gray: #6C757D;
            --royal-gray-light: #8D99A7;
            --royal-text: #0F204E;
            --royal-text-light: #1F3A6D;
            --royal-text-dark: #4A5568;
            --royal-success: #28A745;
            --royal-warning: #FFC107;
            --royal-danger: #DC3545;
            --royal-info: #17A2B8;
            --royal-primary: #2D5A87;
            --royal-border: #B0C4DE;
            
            /* Layout Variables */
            --border-radius: 12px;
            --border-radius-sm: 8px;
            --border-radius-lg: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.12);
            --glow: 0 0 30px rgba(26, 54, 93, 0.15);
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
        .close-modal {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: transparent;
    border: none;
    color: var(--royal-gold);
    font-size: 1.5rem;
    cursor: pointer;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: var(--transition);
    z-index: 10;
}

.close-modal:hover {
    background: rgba(26, 54, 93, 0.1);
    transform: rotate(90deg);
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
        
        .logo-royal img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 5px;
        }
        
        .logo-royal.fallback {
            background: var(--gold-gradient);
        }
        
        .logo-royal.fallback i {
            font-size: 1.5rem;
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
        /* Courses Dropdown with Scroll */
.courses-dropdown {
    max-height: 70vh !important;
    overflow-y: auto !important;
    min-width: 350px !important;
}

/* Custom scrollbar for dropdowns */
.dropdown-menu-royal::-webkit-scrollbar {
    width: 6px;
}

.dropdown-menu-royal::-webkit-scrollbar-track {
    background: rgba(212, 175, 55, 0.1);
    border-radius: 3px;
}

.dropdown-menu-royal::-webkit-scrollbar-thumb {
    background: var(--royal-gold);
    border-radius: 3px;
}

.dropdown-menu-royal::-webkit-scrollbar-thumb:hover {
    background: var(--royal-gold-dark);
}

/* Mobile dropdown scrollable */
.mobile-dropdown-content {
    max-height: 60vh !important;
    overflow-y: auto !important;
    padding-right: 10px;
}

.mobile-dropdown-content::-webkit-scrollbar {
    width: 4px;
}

.mobile-dropdown-content::-webkit-scrollbar-track {
    background: rgba(212, 175, 55, 0.1);
    border-radius: 2px;
}

.mobile-dropdown-content::-webkit-scrollbar-thumb {
    background: var(--royal-gold);
    border-radius: 2px;
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
        
        /* App Store Badges */
        .app-badges {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        
        .app-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: var(--border-radius-sm);
            color: var(--royal-gold);
            font-weight: 500;
            font-size: 0.85rem;
            transition: var(--transition);
        }
        
        .app-badge:hover {
            background: rgba(212, 175, 55, 0.2);
            transform: translateY(-2px);
        }
        
        .app-badge i {
            font-size: 1.2rem;
        }
        
        /* App Download Modal */
        .app-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }
        
        .app-modal.active {
            display: flex;
        }
        
        .app-modal-content {
            background: var(--royal-white);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--royal-gold);
        }
        
        .app-modal-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .app-modal-button {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--royal-lighter);
            border: 1px solid var(--royal-border);
            border-radius: var(--border-radius);
            text-align: left;
            transition: var(--transition);
        }
        
        .app-modal-button:hover {
            background: rgba(212, 175, 55, 0.1);
            border-color: var(--royal-gold);
            transform: translateY(-2px);
        }
        
        .app-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--border-radius-sm);
            background: var(--gold-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--royal-white);
            font-size: 1.5rem;
        }
        
        .app-info {
            flex: 1;
        }
        
        .app-name {
            font-weight: 600;
            color: var(--royal-dark);
            margin-bottom: 0.25rem;
        }
        
        .app-desc {
            font-size: 0.85rem;
            color: var(--royal-text-dark);
        }
        
        /* Rest of your existing CSS remains the same */
        /* Only showing new additions above, keep all your existing CSS below */
        
        /* ... [Rest of your existing CSS] ... */

        /* Mobile menu adjustments for light theme */
        .mobile-nav .nav-link-royal:hover {
            background: rgba(212, 175, 55, 0.08);
            color: var(--royal-gold);
        }
    </style>
    <!-- Custom Compiled CSS -->
     <style>
        :root {
            /* Light & Blue Color Palette */
            --royal-light: #F0F4F8;
            --royal-lighter: #E6EEF4;
            --royal-light-accent: #D8E4ED;
            --royal-white: #F8FAFC;
            --royal-gold: #1A365D;
            --royal-gold-light: #2D5A87;
            --royal-gold-dark: #0F204E;
            --royal-accent: #2D5A87;
            --royal-dark: #0F204E;
            --royal-darker: #0F204E;
            --royal-gray: #6C757D;
            --royal-gray-light: #8D99A7;
            --royal-text: #0F204E;
            --royal-text-light: #1F3A6D;
            --royal-text-dark: #4A5568;
            --royal-success: #28A745;
            --royal-warning: #FFC107;
            --royal-danger: #DC3545;
            --royal-info: #17A2B8;
            --royal-primary: #2D5A87;
            --royal-border: #B0C4DE;
            
            /* Layout Variables */
            --border-radius: 12px;
            --border-radius-sm: 8px;
            --border-radius-lg: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.12);
            --glow: 0 0 30px rgba(26, 54, 93, 0.15);
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
        
        /* App Float */
        .app-float {
            position: fixed;
            bottom: 90px; /* Positioned above WhatsApp icon */
            left: 20px; /* Positioned on the left side (opposite to WhatsApp) */
            width: 60px;
            height: 60px;
            background: var(--gold-gradient);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px; /* Reduced font size to fit both icons */
            box-shadow: var(--shadow-xl);
            z-index: 998;
            transition: var(--transition);
            animation: float 3s ease-in-out infinite, pulse 2s infinite;
            cursor: pointer;
            flex-direction: column; /* Stack icons vertically */
            gap: 2px; /* Small gap between icons */
        }
        
        .app-float i {
            margin: 0;
            line-height: 1;
        }

        .app-float:hover {
            transform: scale(1.15);
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.5);
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.4);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(212, 175, 55, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(212, 175, 55, 0);
            }
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
            
            .app-float {
                bottom: calc(1.5rem + 65px); /* Positioned above WhatsApp icon */
                left: 1.5rem;
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
            
            .app-float {
                bottom: calc(1rem + 60px); /* Positioned above WhatsApp icon */
                left: 1rem;
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
    <?php if($settings['enable_whatsapp_chat'] && $settings['whatsapp_number']): ?>
    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $settings['whatsapp_number']); ?>?text=Hi%20I'm%20interested%20in%20your%20courses" 
       class="whatsapp-float-royal" target="_blank" title="Chat on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    <?php endif; ?>

   <!-- App Download Modal -->
<div class="app-modal" id="appModal">
    <div class="app-modal-content">
        <button class="close-modal" onclick="closeAppModal()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: var(--royal-gold); font-size: 1.5rem; cursor: pointer; z-index: 10;">
            <i class="fas fa-times"></i>
        </button>
        <h4 style="color: var(--royal-gold); margin-bottom: 1rem;">Download Our Mobile App</h4>
        <p style="color: var(--royal-text-dark); margin-bottom: 1.5rem;">Choose your platform to download our app for a better learning experience.</p>
        <div class="app-modal-buttons">
            <?php if(!empty($settings['android_app_link'])): ?>
            <a href="<?php echo htmlspecialchars($settings['android_app_link']); ?>" class="app-modal-button" target="_blank">
                <div class="app-icon">
                    <i class="fab fa-android"></i>
                </div>
                <div class="app-info">
                    <div class="app-name">Android App</div>
                    <div class="app-desc">Download from Google Play</div>
                </div>
                <i class="fas fa-external-link-alt"></i>
            </a>
            <?php endif; ?>
            
            <?php if(!empty($settings['ios_app_link'])): ?>
            <div class="app-modal-button" onclick="window.open('<?php echo htmlspecialchars($settings['ios_app_link']); ?>', '_blank')">
                <div class="app-icon">
                    <i class="fab fa-apple"></i>
                </div>
                <div class="app-info">
                    <div class="app-name">iOS App</div>
                    <div class="app-desc">Download from App Store</div>
                    <?php if(!empty($settings['ios_org_pass'])): ?>
                    <div class="org-code-section" style="margin-top: 0.75rem; padding: 0.5rem; background: rgba(212, 175, 55, 0.15); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: var(--border-radius-sm); display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-key" style="color: var(--royal-gold); font-size: 1rem;"></i>
                        <div style="font-weight: 600; color: var(--royal-gold-dark); font-size: 0.9rem;">
                            Organization Code: <strong><?php echo htmlspecialchars($settings['ios_org_pass']); ?></strong>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <i class="fas fa-external-link-alt"></i>
            </div>
            <?php endif; ?>
        </div>
        <button class="btn btn-outline-gold" style="margin-top: 1.5rem; width: 100%;" onclick="closeAppModal()">
            Close
        </button>
    </div>
</div>

    <!-- Premium Navigation -->
    <nav class="navbar-royal">
        <div class="navbar-container">
            <a class="navbar-brand-royal" href="index.php">
                <div class="logo-royal <?php echo empty($logo_url) ? 'fallback' : ''; ?>">
                    <?php if(!empty($logo_url)): ?>
                        <img src="<?php echo $logo_url; ?>" alt="<?php echo htmlspecialchars($settings['site_name']); ?> Logo">
                    <?php else: ?>
                        <i class="fas fa-crown"></i>
                    <?php endif; ?>
                </div>
                <div class="brand-text">
                    <div class="brand-title"><?php echo htmlspecialchars($settings['site_name']); ?></div>
                    
                </div>
            </a>
            
            <!-- Desktop Navigation -->
            <div class="nav-main">
                <a class="nav-link-royal <?php echo $is_home ? 'active' : ''; ?>" href="index.php">
                    <i class="fas fa-home me-1"></i> Home
                </a>
                
                <!-- Courses Dropdown -->
                <div class="dropdown-royal">
                    <a class="nav-link-royal <?php echo $current_page == 'courses.php' || $current_page == 'all_courses.php' ? 'active' : ''; ?>" 
                       href="#">
                        <i class="fas fa-graduation-cap me-1"></i> Courses
                        <i class="fas fa-chevron-down ms-1" style="font-size: 0.8rem;"></i>
                    </a>
<div class="dropdown-menu-royal courses-dropdown">
    <h6 class="dropdown-header-royal">Courses</h6>
    <a class="dropdown-item-royal fw-bold text-gold" href="all_courses.php">
        <i class="fas fa-eye"></i>
        <div>
            <div>View All Courses</div>
            <div class="text-gold-light small mt-1">Browse complete course catalog</div>
        </div>
    </a>
    
    <div class="dropdown-divider-royal"></div>
    <h6 class="dropdown-header-royal">Featured Courses</h6>
    <?php foreach($featured_courses_nav as $course): ?>
    <a class="dropdown-item-royal" href="courses.php?slug=<?php echo $course['slug']; ?>">
        <i class="<?php echo htmlspecialchars($course['icon']); ?>"></i>
        <div>
            <div><?php echo htmlspecialchars($course['name']); ?></div>
            <div class="text-royal-text-dark small mt-1"><?php echo htmlspecialchars($course['short_description']); ?></div>
        </div>
    </a>
    <?php endforeach; ?>
</div>
                </div>
                
                <a class="nav-link-royal <?php echo $current_page == 'services.php' ? 'active' : ''; ?>" href="services.php">
                    <i class="fas fa-cogs me-1"></i> Services
                </a>
                
                <!-- Resources Dropdown (Now includes App Download) -->
                <div class="dropdown-royal">
                    <a class="nav-link-royal <?php echo $current_page == 'resources.php' ? 'active' : ''; ?>" href="#">
                        <i class="fas fa-book-open me-1"></i> Resources
                        <i class="fas fa-chevron-down ms-1" style="font-size: 0.8rem;"></i>
                    </a>
                    <div class="dropdown-menu-royal">
                        <a class="dropdown-item-royal" href="resources.php#free-material">
                            <i class="fas fa-download"></i> Free Study Material
                        </a>
                        <a class="dropdown-item-royal" href="resources.php#speaking-examples">
                            <i class="fas fa-microphone"></i> Speaking Examples
                        </a>
                        <a class="dropdown-item-royal" href="resources.php#video-resources">
                            <i class="fas fa-play-circle"></i> Video Resources
                        </a>
                        
                        <!-- App Download Link -->
                        <?php if(!empty($settings['android_app_link']) || !empty($settings['ios_app_link'])): ?>
                        <div class="dropdown-divider-royal"></div>
                        <a class="dropdown-item-royal" href="javascript:void(0);" onclick="openAppModal()">
                            <i class="fas fa-mobile-alt"></i> Download Mobile App
                        </a>
                        <?php endif; ?>
                        
                        <?php if($settings['youtube_url'] && $settings['show_youtube']): ?>
                        <a class="dropdown-item-royal" href="<?php echo $settings['youtube_url']; ?>" target="_blank">
                            <i class="fab fa-youtube"></i> YouTube Channel
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <a class="nav-link-royal <?php echo $current_page == 'about.php' ? 'active' : ''; ?>" href="about.php">
                    <i class="fas fa-info-circle me-1"></i> About
                </a>
                
                <a class="nav-link-royal <?php echo $current_page == 'contact.php' ? 'active' : ''; ?>" href="contact.php">
                    <i class="fas fa-envelope me-1"></i> Contact
                </a>
                
                <!-- Blog Link -->
                <a class="nav-link-royal <?php echo $current_page == 'blog.php' ? 'active' : ''; ?>" href="blog.php">
                    <i class="fas fa-blog me-1"></i> Blog
                </a>
            </div>
            
            <div class="nav-actions">
                
                <a href="tel:<?php echo htmlspecialchars($settings['primary_phone']); ?>" class="btn-outline-gold">
                    <i class="fas fa-phone me-1"></i> Call Now
                </a>
                
                <!-- App Download Button -->
                
            </div>
            
            <!-- Mobile Menu Toggle -->
            <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle mobile menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu">
            <div class="mobile-nav">
                <a class="nav-link-royal <?php echo $is_home ? 'active' : ''; ?>" href="index.php" onclick="closeMobileMenu()">
                    <i class="fas fa-home me-2"></i> Home
                </a>
                
                <div class="dropdown-royal">
                    <a class="nav-link-royal mobile-dropdown-toggle <?php echo $current_page == 'courses.php' || $current_page == 'all_courses.php' ? 'active' : ''; ?>" 
                       href="javascript:void(0);" onclick="toggleMobileDropdown('courses')">
                        <span>
                            <i class="fas fa-graduation-cap me-2"></i> Courses
                        </span>
                        <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
                    </a>
                    <div class="mobile-dropdown-content" id="mobileCoursesDropdown">
    <h6 class="dropdown-header-royal mb-2">Courses</h6>
    <a class="dropdown-item-royal fw-bold text-gold" href="all_courses.php" onclick="closeMobileMenu()">
        <i class="fas fa-eye"></i>
        <div>
            <div>View All Courses</div>
            <div class="text-gold-light small mt-1">Browse complete course catalog</div>
        </div>
    </a>
    
    <h6 class="dropdown-header-royal mb-2" style="margin-top: 1rem;">Featured Courses</h6>
    <?php foreach($featured_courses_nav as $course): ?>
    <a class="dropdown-item-royal" href="courses.php?slug=<?php echo $course['slug']; ?>" onclick="closeMobileMenu()">
        <i class="<?php echo htmlspecialchars($course['icon']); ?>"></i>
        <div>
            <div><?php echo htmlspecialchars($course['name']); ?></div>
            <div class="text-royal-text-dark small mt-1"><?php echo htmlspecialchars($course['short_description']); ?></div>
        </div>
    </a>
    <?php endforeach; ?>
</div>
                </div>
                
                <a class="nav-link-royal <?php echo $current_page == 'services.php' ? 'active' : ''; ?>" href="services.php" onclick="closeMobileMenu()">
                    <i class="fas fa-cogs me-2"></i> Services
                </a>
                
                <div class="dropdown-royal">
                    <a class="nav-link-royal mobile-dropdown-toggle <?php echo $current_page == 'resources.php' ? 'active' : ''; ?>" 
                       href="javascript:void(0);" onclick="toggleMobileDropdown('resources')">
                        <span>
                            <i class="fas fa-book-open me-2"></i> Resources
                        </span>
                        <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
                    </a>
                    <div class="mobile-dropdown-content" id="mobileResourcesDropdown">
                        <a class="dropdown-item-royal" href="resources.php#free-material" onclick="closeMobileMenu()">
                            <i class="fas fa-download"></i> Free Study Material
                        </a>
                        <a class="dropdown-item-royal" href="resources.php#speaking-examples" onclick="closeMobileMenu()">
                            <i class="fas fa-microphone"></i> Speaking Examples
                        </a>
                        <a class="dropdown-item-royal" href="resources.php#video-resources" onclick="closeMobileMenu()">
                            <i class="fas fa-play-circle"></i> Video Resources
                        </a>
                        
                        <!-- App Download Link in Mobile -->
                        <?php if(!empty($settings['android_app_link']) || !empty($settings['ios_app_link'])): ?>
                        <a class="dropdown-item-royal" href="javascript:void(0);" onclick="openAppModal(); closeMobileMenu();">
                            <i class="fas fa-mobile-alt"></i> Download Mobile App
                        </a>
                        <?php endif; ?>
                        
                        <?php if($settings['youtube_url'] && $settings['show_youtube']): ?>
                        <a class="dropdown-item-royal" href="<?php echo $settings['youtube_url']; ?>" target="_blank" onclick="closeMobileMenu()">
                            <i class="fab fa-youtube"></i> YouTube Channel
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <a class="nav-link-royal <?php echo $current_page == 'about.php' ? 'active' : ''; ?>" href="about.php" onclick="closeMobileMenu()">
                    <i class="fas fa-info-circle me-2"></i> About
                </a>
                
                <a class="nav-link-royal <?php echo $current_page == 'contact.php' ? 'active' : ''; ?>" href="contact.php" onclick="closeMobileMenu()">
                    <i class="fas fa-envelope me-2"></i> Contact
                </a>
                
                <!-- Blog Link in Mobile Menu -->
                <a class="nav-link-royal <?php echo $current_page == 'blog.php' ? 'active' : ''; ?>" href="blog.php" onclick="closeMobileMenu();">
                    <i class="fas fa-blog me-2"></i> Blog
                </a>
            </div>
            
            <div class="mobile-actions">
               
                <a href="tel:<?php echo htmlspecialchars($settings['primary_phone']); ?>" class="btn-outline-gold" onclick="closeMobileMenu()">
                    <i class="fas fa-phone me-2"></i> Call Now
                </a>
                
                <!-- App Download Button in Mobile -->
                <?php if(!empty($settings['android_app_link']) || !empty($settings['ios_app_link'])): ?>
                <button class="btn-outline-gold" onclick="openAppModal(); closeMobileMenu();" style="display: flex; align-items: center; gap: 0.5rem; justify-content: center;">
                    <i class="fas fa-mobile-alt"></i>
                    <span>Download App</span>
                </button>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- App Floating Icon -->
    <?php if(!empty($settings['android_app_link']) || !empty($settings['ios_app_link'])): ?>
    <div class="app-float" onclick="openAppModal()" title="Download our app">
        <i class="fab fa-apple"></i>
        <i class="fab fa-google-play"></i>
    </div>
    <?php endif; ?>

    <!-- WhatsApp Floating Icon -->
    <?php if(!empty($settings['whatsapp_number'])): ?>
    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $settings['whatsapp_number']); ?>" 
       class="whatsapp-float" target="_blank" title="Chat with us on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    <?php endif; ?>

    <main>
    
    <script>
    // Mobile menu functionality
    document.addEventListener('DOMContentLoaded', function() {
        const mobileToggle = document.getElementById('mobileToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuLinks = document.querySelectorAll('.mobile-menu a[href]');
        
        // Toggle mobile menu
        mobileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleMobileMenu();
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileMenu.contains(e.target) && !mobileToggle.contains(e.target)) {
                closeMobileMenu();
            }
        });
        
        // Close mobile menu when clicking escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMobileMenu();
            }
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
                        // Close mobile menu if open
                        closeMobileMenu();
                        
                        // Smooth scroll
                        window.scrollTo({
                            top: targetElement.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
        
        // Initialize app modal event listeners
        setupAppModal();
    });
    
    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileToggle = document.getElementById('mobileToggle');
        
        mobileMenu.classList.toggle('active');
        mobileToggle.innerHTML = mobileMenu.classList.contains('active') ? 
            '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
        
        // Prevent body scroll when menu is open
        document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
    }
    
    function closeMobileMenu() {
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileToggle = document.getElementById('mobileToggle');
        
        mobileMenu.classList.remove('active');
        mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
        document.body.style.overflow = '';
        
        // Close all dropdowns
        closeAllMobileDropdowns();
    }
    
    function toggleMobileDropdown(type) {
        const dropdown = document.getElementById(`mobile${type.charAt(0).toUpperCase() + type.slice(1)}Dropdown`);
        const toggle = document.querySelector(`[onclick="toggleMobileDropdown('${type}')"]`);
        
        // Close all other dropdowns
        closeAllMobileDropdowns(type);
        
        // Toggle current dropdown
        dropdown.classList.toggle('active');
        toggle.classList.toggle('active');
    }
    
    function closeAllMobileDropdowns(except = null) {
        const dropdowns = document.querySelectorAll('.mobile-dropdown-content');
        const toggles = document.querySelectorAll('.mobile-dropdown-toggle');
        
        dropdowns.forEach(dropdown => {
            dropdown.classList.remove('active');
        });
        
        toggles.forEach(toggle => {
            toggle.classList.remove('active');
        });
    }
    
    // App Modal Functions
    function openAppModal() {
        const modal = document.getElementById('appModal');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    
    function closeAppModal() {
        const modal = document.getElementById('appModal');
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
    
    function setupAppModal() {
        const modal = document.getElementById('appModal');
        if (!modal) return;
        
        // Add click handler to app-float icon
        const appFloat = document.querySelector('.app-float');
        if (appFloat) {
            appFloat.addEventListener('click', function(e) {
                e.stopPropagation();
                openAppModal();
            });
        }
        
        // Add click handlers to other app download triggers
        document.querySelectorAll('[onclick*="openAppModal"]').forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                openAppModal();
            });
        });
        
        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAppModal();
            }
        });
        
        // Close modal with escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAppModal();
            }
        });
        
        // Close modal with close button (if you add one)
        const closeButtons = document.querySelectorAll('[onclick*="closeAppModal"]');
        closeButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                closeAppModal();
            });
        });
    }
    
    // Show app modal on first visit using local storage
    document.addEventListener('DOMContentLoaded', function() {
        // Check if user has seen the app modal before
        const hasSeenAppModal = localStorage.getItem('hasSeenAppModal');
        
        if (!hasSeenAppModal && (<?php echo !empty($settings['android_app_link']) || !empty($settings['ios_app_link']) ? 'true' : 'false'; ?>)) {
            setTimeout(() => {
                openAppModal();
                localStorage.setItem('hasSeenAppModal', 'true');
            }, 3000); // Show after 3 seconds
        }
    });
</script>
    </main>
</body>
</html>
