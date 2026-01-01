<?php

require_once 'config.php';

if (isLoggedIn()) {
    header('Location: admin.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? strtolower(trim($_POST['username'])) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if ($username === 'admin' && $password === '123admin') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['login_time'] = time();
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
        
        session_regenerate_id(true);
        
        if (ENVIRONMENT === 'production') {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', 1);
            ini_set('session.cookie_samesite', 'Strict');
        }
        
        header('Location: admin.php');
        exit();
    } else {
        $error = 'Invalid credentials';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Prep with Daljeet</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Premium SASS Compiled CSS */
        :root {
            --royal-purple: #8B5CF6;
            --royal-purple-dark: #7C3AED;
            --royal-purple-light: #A78BFA;
            --royal-gold: #F59E0B;
            --royal-gold-dark: #D97706;
            --royal-gold-light: #FBBF24;
            --royal-blue: #1E40AF;
            --royal-blue-dark: #1E3A8A;
            --royal-blue-light: #3B82F6;
            --royal-emerald: #10B981;
            --royal-emerald-dark: #059669;
            --royal-emerald-light: #34D399;
            --royal-gray-50: #F9FAFB;
            --royal-gray-100: #F3F4F6;
            --royal-gray-800: #1F2937;
            --royal-gray-900: #111827;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, 
                var(--royal-purple) 0%, 
                var(--royal-blue) 50%, 
                var(--royal-emerald) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="white" opacity="0.03"/></svg>');
            background-size: cover;
            pointer-events: none;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 50px 40px;
            width: 100%;
            max-width: 480px;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transform-style: preserve-3d;
            perspective: 1000px;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, 
                var(--royal-purple), 
                var(--royal-gold), 
                var(--royal-emerald));
            border-radius: 26px;
            z-index: -1;
            opacity: 0.3;
            filter: blur(10px);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }
        
        .login-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--royal-purple), var(--royal-gold));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);
            border: 3px solid white;
        }
        
        .login-logo i {
            font-size: 32px;
            color: white;
        }
        
        .login-header h1 {
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(135deg, var(--royal-purple), var(--royal-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        
        .login-header p {
            color: var(--royal-gray-800);
            font-size: 15px;
            opacity: 0.8;
        }
        
        .form-group {
            margin-bottom: 24px;
            position: relative;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--royal-gray-800);
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-control {
            width: 100%;
            padding: 16px 20px;
            background: white;
            border: 2px solid var(--royal-gray-100);
            border-radius: 12px;
            font-size: 16px;
            color: var(--royal-gray-900);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--royal-purple);
            box-shadow: 
                0 0 0 4px rgba(139, 92, 246, 0.1),
                0 4px 12px rgba(0, 0, 0, 0.08);
            transform: translateY(-1px);
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--royal-gray-800);
            cursor: pointer;
            padding: 8px;
            transition: color 0.3s;
            opacity: 0.6;
        }
        
        .password-toggle:hover {
            color: var(--royal-purple);
            opacity: 1;
        }
        
        .btn-login {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, var(--royal-purple), var(--royal-blue));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 20px rgba(139, 92, 246, 0.3);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(139, 92, 246, 0.4);
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert-error {
            background: linear-gradient(135deg, #FEE2E2, #FECACA);
            border: 1px solid #FCA5A5;
            color: #991B1B;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .alert-error i {
            font-size: 20px;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--royal-gray-100);
            color: var(--royal-gray-800);
            font-size: 13px;
            opacity: 0.7;
        }
        
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 0;
        }
        
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 20s infinite linear;
        }
        
        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            background: rgba(139, 92, 246, 0.15);
        }
        
        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            bottom: 15%;
            right: 10%;
            background: rgba(245, 158, 11, 0.15);
        }
        
        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            top: 50%;
            left: 85%;
            background: rgba(30, 64, 175, 0.15);
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            33% {
                transform: translateY(-20px) rotate(120deg);
            }
            66% {
                transform: translateY(20px) rotate(240deg);
            }
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 40px 24px;
                margin: 10px;
            }
            
            .login-header h1 {
                font-size: 28px;
            }
        }
        
        /* Premium input focus effect */
        .form-control:focus {
            background: linear-gradient(white, white) padding-box,
                        linear-gradient(135deg, var(--royal-purple), var(--royal-gold)) border-box;
            border: 2px solid transparent;
        }
    </style>
</head>
<body>
    <!-- Floating Shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <!-- Login Container -->
    <div class="login-container">
        <div class="login-header">
            <div class="login-logo">
                <i class="fas fa-crown"></i>
            </div>
            <h1>Prep with Daljeet</h1>
            <p>Premium Admin Dashboard</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" id="loginForm">
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" 
                       name="username" 
                       class="form-control" 
                       required 
                       placeholder="Enter admin username"
                       autocomplete="username"
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="password-wrapper">
                    <input type="password" 
                           name="password" 
                           id="password" 
                           class="form-control" 
                           required 
                           placeholder="Enter your password"
                           autocomplete="current-password">
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-lock-open"></i> Sign In to Dashboard
            </button>
        </form>
        
        <div class="login-footer">
            <p>Restricted Access • Authorized Personnel Only • Version 2.0</p>
        </div>
    </div>

    <script>
        // Password toggle functionality
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = togglePassword.querySelector('i');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        });
        
        // Form validation
        const form = document.getElementById('loginForm');
        const inputs = form.querySelectorAll('input[required]');
        
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('error');
                    
                    // Add shake animation
                    input.style.animation = 'none';
                    setTimeout(() => {
                        input.style.animation = 'shake 0.5s';
                    }, 10);
                } else {
                    input.classList.remove('error');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                // Add error style to form
                form.classList.add('form-error');
                setTimeout(() => {
                    form.classList.remove('form-error');
                }, 1000);
            }
        });
        
        // Add shake animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                20%, 40%, 60%, 80% { transform: translateX(5px); }
            }
            
            .error {
                border-color: #EF4444 !important;
                background: linear-gradient(white, white) padding-box,
                            linear-gradient(135deg, #EF4444, #DC2626) border-box !important;
            }
            
            .form-error {
                animation: shake 0.5s;
            }
        `;
        document.head.appendChild(style);
        
        // Add autofocus to username
        document.querySelector('input[name="username"]').focus();
        
        // Add floating effect to login container
        const loginContainer = document.querySelector('.login-container');
        loginContainer.addEventListener('mousemove', (e) => {
            const xAxis = (window.innerWidth / 2 - e.pageX) / 25;
            const yAxis = (window.innerHeight / 2 - e.pageY) / 25;
            loginContainer.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
        });
        
        loginContainer.addEventListener('mouseleave', () => {
            loginContainer.style.transform = 'rotateY(0deg) rotateX(0deg)';
            loginContainer.style.transition = 'transform 0.5s ease';
        });
        
        loginContainer.addEventListener('mouseenter', () => {
            loginContainer.style.transition = 'none';
        });
    </script>
</body>
</html>