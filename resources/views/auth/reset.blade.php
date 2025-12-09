<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - LAB RSI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* RESET STYLES */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #2d5af1 0%, #1a237e 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* MAIN CONTAINER */
        .reset-container {
            width: 100%;
            max-width: 1000px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
            display: flex;
            min-height: 600px;
        }

        /* LEFT PANEL - BRANDING */
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #2d5af1 0%, #1a237e 100%);
            padding: 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 36px;
        }

        .logo h1 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .logo p {
            font-size: 16px;
            opacity: 0.9;
        }

        .feature-list {
            position: relative;
            z-index: 1;
        }

        .feature {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 18px;
        }

        .feature-text h4 {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .feature-text p {
            font-size: 12px;
            opacity: 0.8;
        }

        /* RIGHT PANEL - FORM */
        .right-panel {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #f8f9ff;
        }

        .form-header {
            margin-bottom: 30px;
        }

        .form-header h2 {
            font-size: 32px;
            color: #1a237e;
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
        }

        .form-header h2::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 50px;
            height: 4px;
            background: #4ecdc4;
            border-radius: 2px;
        }

        .form-header p {
            color: #666;
            font-size: 16px;
            line-height: 1.5;
        }

        /* FORM STYLES */
        .reset-form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #2d5af1;
        }

        .form-input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #2d5af1;
            box-shadow: 0 0 0 3px rgba(45, 90, 241, 0.1);
        }

        .toggle-password {
            position: absolute !important;
            left: auto !important;
            right: 15px !important;
            cursor: pointer;
        }

        /* PASSWORD STRENGTH */
        .password-strength {
            margin-top: 10px;
        }

        .strength-bar {
            height: 6px;
            background: #eee;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 5px;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 3px;
            transition: width 0.3s;
        }

        .strength-text {
            font-size: 12px;
            color: #666;
        }

        /* PASSWORD MATCH */
        .password-match {
            font-size: 12px;
            margin-top: 5px;
            display: flex;
            align-items: center;
        }

        .password-match i {
            margin-right: 5px;
        }

        .match-yes {
            color: #4ecdc4;
        }

        .match-no {
            color: #ff6b6b;
        }

        /* SUBMIT BUTTON */
        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(to right, #2d5af1, #1a237e);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(45, 90, 241, 0.3);
        }

        .submit-btn i {
            margin-right: 8px;
        }

        /* FOOTER */
        .form-footer {
            text-align: center;
            margin-top: 25px;
            color: #666;
            font-size: 14px;
        }

        .form-footer a {
            color: #2d5af1;
            text-decoration: none;
            font-weight: 500;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        /* ALERTS */
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .alert-danger {
            background: #ffeaea;
            border: 1px solid #ff6b6b;
            color: #d32f2f;
        }

        .alert-success {
            background: #e8f7ee;
            border: 1px solid #4ecdc4;
            color: #155724;
        }

        .alert i {
            margin-right: 10px;
            font-size: 16px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .reset-container {
                flex-direction: column;
                max-width: 500px;
            }

            .left-panel,
            .right-panel {
                padding: 30px;
            }

            .logo h1 {
                font-size: 28px;
            }

            .form-header h2 {
                font-size: 26px;
            }
        }

        @media (max-width: 480px) {

            .left-panel,
            .right-panel {
                padding: 20px;
            }

            .logo-icon {
                width: 70px;
                height: 70px;
                font-size: 30px;
            }

            .form-header h2 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="reset-container">
        <!-- Left Panel - Branding -->
        <div class="left-panel">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-key"></i>
                </div>
                <h1>LAB RSI</h1>
                <p>Research & System Innovation</p>
                <p style="margin-top: 10px; font-size: 14px;">Reset Password</p>
            </div>

            <div class="feature-list">
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Keamanan Tinggi</h4>
                        <p>Password dienkripsi dengan teknologi terbaru</p>
                    </div>
                </div>

                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Password Kuat</h4>
                        <p>Pastikan password Anda sulit ditebak</p>
                    </div>
                </div>

                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Update Instan</h4>
                        <p>Password diperbarui secara langsung</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Form -->
        <div class="right-panel">
            <div class="form-header">
                <h2>Reset Password</h2>
                <p>Buat password baru untuk akun Anda. Pastikan password kuat dan mudah diingat.</p>
            </div>

            <!-- Simulasi Error/Success Messages -->
            <!-- Hapus comment ini jika ingin menampilkan error dari Laravel -->
            <!--
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
            -->

            <form method="POST" action="{{ route('password.update') }}" class="reset-form" id="resetForm">
                <!-- Hapus comment ini untuk token Laravel -->
                <!-- @csrf -->
                <!-- <input type="hidden" name="token" value="{{ $token }}"> -->

                <!-- Untuk testing, kita buat form sederhana -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" class="form-input"
                            placeholder="nama@institusi.ac.id"
                            value="{{ isset($email) ? $email : (isset($email) ? $email : '') }}" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" class="form-input"
                            placeholder="Masukkan password baru" required>
                        <i class="fas fa-eye toggle-password" style="left: auto; right: 15px; cursor: pointer;"></i>
                    </div>

                    <div class="password-strength" id="passwordStrength">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <div class="strength-text">
                            Kekuatan password: <span id="strengthText">-</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password-confirm">Konfirmasi Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password-confirm" name="password_confirmation" class="form-input"
                            placeholder="Konfirmasi password baru" required>
                        <i class="fas fa-eye toggle-password" style="left: auto; right: 15px; cursor: pointer;"></i>
                    </div>

                    <div class="password-match" id="passwordMatch">
                        <i class="fas fa-times"></i>
                        <span>Password tidak cocok</span>
                    </div>
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">
                    <i class="fas fa-redo"></i>
                    Reset Password
                </button>
            </form>

            <div class="form-footer">
                <p>Ingat password Anda? <a href="{{ route('login') }}">Masuk ke akun</a></p>
                <p style="margin-top: 10px; font-size: 12px; color: #888;">
                    &copy; 2024 LAB RSI - Research & System Innovation
                </p>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });

        // Password strength checker
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password-confirm');
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');
        const passwordMatch = document.getElementById('passwordMatch');

        function checkPasswordStrength(password) {
            let score = 0;

            // Length
            if (password.length >= 8) score += 25;
            if (password.length >= 12) score += 15;

            // Complexity
            if (/[A-Z]/.test(password)) score += 20;
            if (/[a-z]/.test(password)) score += 20;
            if (/[0-9]/.test(password)) score += 20;
            if (/[^A-Za-z0-9]/.test(password)) score += 20;

            // Clamp score
            score = Math.min(score, 100);

            return score;
        }

        function updateStrengthDisplay(score) {
            strengthFill.style.width = score + '%';

            if (score < 40) {
                strengthFill.style.backgroundColor = '#ff6b6b';
                strengthText.textContent = 'Lemah';
                strengthText.style.color = '#ff6b6b';
            } else if (score < 70) {
                strengthFill.style.backgroundColor = '#ffa726';
                strengthText.textContent = 'Cukup';
                strengthText.style.color = '#ffa726';
            } else {
                strengthFill.style.backgroundColor = '#4ecdc4';
                strengthText.textContent = 'Kuat';
                strengthText.style.color = '#4ecdc4';
            }
        }

        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirm = confirmInput.value;

            if (confirm.length === 0) {
                passwordMatch.style.display = 'none';
                return;
            }

            passwordMatch.style.display = 'flex';

            if (password === confirm) {
                passwordMatch.innerHTML = '<i class="fas fa-check"></i> <span>Password cocok</span>';
                passwordMatch.className = 'password-match match-yes';
                return true;
            } else {
                passwordMatch.innerHTML = '<i class="fas fa-times"></i> <span>Password tidak cocok</span>';
                passwordMatch.className = 'password-match match-no';
                return false;
            }
        }

        // Event listeners
        passwordInput.addEventListener('input', function() {
            const score = checkPasswordStrength(this.value);
            updateStrengthDisplay(score);
            checkPasswordMatch();
        });

        confirmInput.addEventListener('input', checkPasswordMatch);

        // Form submission
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const confirm = confirmInput.value;

            // Check if passwords match
            if (password !== confirm) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
                return;
            }

            // Check password strength
            const score = checkPasswordStrength(password);
            if (score < 40) {
                if (!confirm('Password Anda cukup lemah. Apakah Anda yakin ingin menggunakan password ini?')) {
                    e.preventDefault();
                    return;
                }
            }

            // Show loading state
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            btn.disabled = true;

            // Simulate server delay (remove this in production)
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-check"></i> Password Berhasil Direset!';
                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-redo"></i> Reset Password';
                    btn.disabled = false;
                    alert('Password berhasil direset! (Ini hanya demo)');
                }, 1500);
            }, 2000);

            e.preventDefault(); // Remove this in production
        });

        // Initialize
        updateStrengthDisplay(0);
        passwordMatch.style.display = 'none';
    </script>
</body>

</html>
