<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LAB RSI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2d5af1;
            --primary-dark: #1a4ae7;
            --secondary: #ff6b6b;
            --accent: #4ecdc4;
            --dark: #1a237e;
            --light: #f8f9ff;
            --gray: #6c757d;
            --gradient: linear-gradient(135deg, #2d5af1 0%, #1a237e 100%);
            --gradient-accent: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--gradient);
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
            width: 300%;
            height: 300%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="white" opacity="0.03"><polygon points="50,0 100,50 50,100 0,50"/></svg>');
            animation: bgMove 100s linear infinite;
            z-index: 0;
        }

        @keyframes bgMove {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 20s infinite linear;
        }

        .shape:nth-child(1) {
            width: 150px;
            height: 150px;
            top: 10%;
            left: 5%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 100px;
            height: 100px;
            top: 20%;
            right: 10%;
            animation-delay: -5s;
            background: rgba(78, 205, 196, 0.2);
        }

        .shape:nth-child(3) {
            width: 200px;
            height: 200px;
            bottom: 10%;
            left: 10%;
            animation-delay: -10s;
        }

        .shape:nth-child(4) {
            width: 80px;
            height: 80px;
            bottom: 20%;
            right: 5%;
            animation-delay: -15s;
            background: rgba(255, 107, 107, 0.2);
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        .login-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(26, 35, 126, 0.3);
            width: 100%;
            max-width: 1100px;
            min-height: 650px;
            display: flex;
            overflow: hidden;
            position: relative;
            z-index: 1;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .brand-section {
            flex: 1;
            background: var(--gradient);
            padding: 60px 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .brand-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="white" opacity="0.05"><circle cx="50" cy="50" r="40"/></svg>');
        }

        .logo-container {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        .logo-circle {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
            animation: pulse 4s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .logo-circle i {
            font-size: 50px;
            color: white;
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .brand-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 10px;
            letter-spacing: 1px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .brand-subtitle {
            font-size: 20px;
            opacity: 0.9;
            font-weight: 300;
            margin-bottom: 40px;
        }

        .brand-text {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.9;
            max-width: 400px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .features {
            margin-top: 50px;
            position: relative;
            z-index: 1;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s, background 0.3s;
        }

        .feature-item:hover {
            transform: translateX(10px);
            background: rgba(255, 255, 255, 0.2);
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 22px;
        }

        .feature-text h4 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .feature-text p {
            font-size: 13px;
            opacity: 0.8;
        }

        .login-section {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: var(--light);
        }

        .login-header {
            margin-bottom: 40px;
        }

        .login-header h2 {
            font-family: 'Montserrat', sans-serif;
            font-size: 36px;
            color: var(--dark);
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
        }

        .login-header h2::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--accent);
            border-radius: 2px;
        }

        .login-header p {
            color: var(--gray);
            font-size: 16px;
        }

        .login-form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 30px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark);
            font-weight: 500;
            font-size: 15px;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 18px;
            z-index: 2;
        }

        .form-input {
            width: 100%;
            padding: 18px 20px 18px 55px;
            border: 2px solid #e1e8ff;
            border-radius: 12px;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            background: white;
            transition: all 0.3s;
            color: var(--dark);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(45, 90, 241, 0.1);
        }

        .form-input::placeholder {
            color: #a0a7c2;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            cursor: pointer;
            position: relative;
        }

        .checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .checkmark {
            width: 20px;
            height: 20px;
            background: white;
            border: 2px solid #e1e8ff;
            border-radius: 6px;
            margin-right: 10px;
            position: relative;
            transition: all 0.3s;
        }

        .checkbox-container input:checked~.checkmark {
            background: var(--primary);
            border-color: var(--primary);
        }

        .checkmark::after {
            content: '';
            position: absolute;
            display: none;
            left: 6px;
            top: 2px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .checkbox-container input:checked~.checkmark::after {
            display: block;
        }

        .remember-me label {
            font-size: 14px;
            color: var(--gray);
            cursor: pointer;
            user-select: none;
        }

        .forgot-password {
            font-size: 14px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            position: relative;
        }

        .forgot-password:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .login-button {
            width: 100%;
            padding: 18px;
            background: var(--gradient);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(45, 90, 241, 0.3);
            position: relative;
            overflow: hidden;
        }

        .login-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(45, 90, 241, 0.4);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .login-button i {
            margin-right: 10px;
            font-size: 18px;
        }

        .login-button::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .login-button:hover::after {
            left: 100%;
        }

        .login-footer {
            text-align: center;
            margin-top: 40px;
            color: var(--gray);
            font-size: 14px;
        }

        .login-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            margin-left: 5px;
            transition: all 0.3s;
        }

        .login-footer a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .alert {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            display: flex;
            align-items: center;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background: linear-gradient(135deg, #ffeaea 0%, #ffd6d6 100%);
            border: 2px solid #ff6b6b;
            color: #d32f2f;
        }

        .alert-success {
            background: linear-gradient(135deg, #e8f7ee 0%, #d4f1e0 100%);
            border: 2px solid #4ecdc4;
            color: #155724;
        }

        .alert i {
            margin-right: 10px;
            font-size: 18px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
                max-width: 500px;
            }

            .brand-section,
            .login-section {
                padding: 40px 30px;
            }

            .brand-title {
                font-size: 36px;
            }

            .login-header h2 {
                font-size: 30px;
            }
        }

        @media (max-width: 480px) {

            .brand-section,
            .login-section {
                padding: 30px 20px;
            }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
            }

            .forgot-password {
                margin-top: 15px;
            }

            .brand-title {
                font-size: 32px;
            }

            .logo-circle {
                width: 100px;
                height: 100px;
            }

            .logo-circle i {
                font-size: 40px;
            }
        }

        /* Loading effect */
        .loading {
            display: none;
            position: absolute;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <!-- Floating background shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="login-container">
        <!-- Brand/Info Section -->
        <div class="brand-section">
            <div class="logo-container">
                <div class="logo-circle">
                    <i class="fas fa-flask"></i>
                </div>
                <h1 class="brand-title">LAB RSI</h1>
                <p class="brand-subtitle">Research & System Innovation</p>
                <p class="brand-text">
                    Sistem manajemen peminjaman barang laboratorium dengan teknologi terdepan untuk mendukung penelitian
                    dan inovasi.
                </p>
            </div>


        </div>

        <!-- Login Form Section -->
        <div class="login-section">
            <div class="login-header">
                <h2>Masuk ke Sistem</h2>
                <p>Masukkan kredensial Anda untuk mengakses panel administrasi</p>
            </div>

            <!-- Error/Success Messages -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Terjadi kesalahan:</strong>
                        <ul style="margin: 5px 0 0 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="/login" class="login-form" id="loginForm">
                @csrf

                <div class="form-group">
                    <label class="form-label">Email Institusi</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="email" name="email" class="form-input" placeholder="Masukkan Email Anda"
                            value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-input" placeholder="Masukkan password Anda"
                            required id="password">
                        <i class="fas fa-eye toggle-password" style="left: auto; right: 20px; cursor: pointer;"></i>
                    </div>
                </div>





                <button type="submit" class="login-button" id="loginBtn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Masuk ke Sistem</span>
                    <div class="loading" id="loadingSpinner"></div>
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.querySelector('.toggle-password');
            const passwordInput = document.getElementById('password');

            if (togglePassword) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            }

            // Form submission animation
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const btnText = loginBtn.querySelector('span');

            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    // Show loading state
                    loginBtn.disabled = true;
                    btnText.textContent = 'Memproses...';
                    loadingSpinner.style.display = 'block';
                    loginBtn.style.cursor = 'not-allowed';
                });
            }

            // Input focus effects
            const inputs = document.querySelectorAll('.form-input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.parentElement.classList.add('focused');
                });

                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.parentElement.classList.remove('focused');
                    }
                });

                // Check if input has value on load
                if (input.value) {
                    input.parentElement.parentElement.classList.add('focused');
                }
            });

            // Add floating animation to feature items on hover
            const featureItems = document.querySelectorAll('.feature-item');
            featureItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });

                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Add ripple effect to login button
            loginBtn.addEventListener('click', function(e) {
                // Create ripple element
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.cssText = `
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.4);
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    width: ${size}px;
                    height: ${size}px;
                    top: ${y}px;
                    left: ${x}px;
                `;

                this.appendChild(ripple);

                // Remove ripple after animation
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });

            // Add CSS for ripple animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</body>

</html>
