<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="İBAG Ekipman Yönetim Sistemi">
    <meta name="author" content="İBAG">
    <meta name="keywords" content="ibag, ekipman, yönetim, sistem">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="{{ asset('images/ibag-logo.svg') }}" type="image/svg+xml" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <title>İBAG - Giriş</title>

    <style>
        :root {
            --bs-primary: #3b7ddd;
            --bs-secondary: #6c757d;
            --bs-success: #1cbb8c;
            --bs-info: #17a2b8;
            --bs-warning: #fcb92c;
            --bs-danger: #dc3545;
            --bs-light: #f5f7fb;
            --bs-dark: #212529;
            --bs-body-font-family: "Inter", "Helvetica Neue", Arial, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Noto Sans", sans-serif;
        }

        body {
            font-family: var(--bs-body-font-family);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, var(--bs-primary) 0%, #2f64b1 100%);
            color: white;
            text-align: center;
            padding: 2rem 1.5rem;
            position: relative;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .login-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
        }

        .security-badge {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 0.5rem 1rem;
            margin-top: 1rem;
            font-size: 0.8rem;
            position: relative;
            z-index: 1;
        }

        .security-badge i {
            margin-right: 0.5rem;
        }

        .login-body {
            padding: 2rem 1.5rem;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-floating .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem 0.75rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-floating .form-control:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(59, 125, 221, 0.25);
            background: white;
        }

        .form-floating label {
            color: #6c757d;
            font-weight: 500;
        }

        .form-floating .form-control:focus + label,
        .form-floating .form-control:not(:placeholder-shown) + label {
            color: var(--bs-primary);
        }

        .form-label {
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .input-group .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.875rem 0.75rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .input-group .form-control:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(59, 125, 221, 0.25);
            background: white;
        }

        .input-group .btn {
            border: 2px solid #e9ecef;
            border-left: none;
            border-radius: 0 12px 12px 0;
            background: #f8f9fa;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .input-group .form-control:focus + .btn {
            border-color: var(--bs-primary);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--bs-primary) 0%, #2f64b1 100%);
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 125, 221, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            padding: 1rem 1.5rem 1.5rem;
            border-top: 1px solid #e9ecef;
            background: #f8f9fa;
        }

        .login-footer a {
            color: var(--bs-primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .login-footer a:hover {
            color: #2f64b1;
        }

        .error-message {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.2);
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 1rem;
            color: #dc3545;
            font-size: 0.9rem;
        }

        .input-group-text {
            background: transparent;
            border: 2px solid #e9ecef;
            border-right: none;
            color: #6c757d;
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group .form-control:focus + .input-group-text {
            border-color: var(--bs-primary);
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            position: relative;
            z-index: 1;
        }

        .brand-logo i {
            font-size: 1.5rem;
            color: white;
        }

        @media (max-width: 576px) {
            .login-container {
                max-width: 100%;
            }
            
            .login-card {
                border-radius: 15px;
            }
            
            .login-header {
                padding: 1.5rem 1rem;
            }
            
            .login-body {
                padding: 1.5rem 1rem;
            }
        }

        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="brand-logo">
                    <img src="{{ asset('images/ibag-logo.svg') }}" alt="İBAG Logo" style="width: 50px; height: 50px;">
                </div>
                <h1>İBAG</h1>
                <p>Ekipman Yönetim Sistemi</p>
                <div class="security-badge">
                    <i class="fas fa-shield-alt"></i>
                    Güvenli Giriş
                </div>
            </div>

            <div class="login-body">
                @if ($errors->any())
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        @if ($errors->has('email'))
                            E-posta adresi geçersiz.
                        @elseif ($errors->has('password'))
                            Şifre hatalı.
                        @else
                            Giriş bilgilerinizi kontrol ediniz.
                        @endif
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('status') }}
                    </div>
                @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" placeholder="E-posta Adresi" required autocomplete="email" autofocus>
                            <button class="btn btn-outline-secondary" type="button" id="clearEmail">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" placeholder="Şifre" required autocomplete="current-password">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Beni Hatırla
                        </label>
                    </div>

                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>Giriş Yap
                                </button>
                    </form>
                </div>

            <div class="login-footer">
                <p class="mb-2 text-muted">Sisteme erişim için yöneticinizle iletişime geçin</p>
                <small class="text-muted">© {{ date('Y') }} İBAG. Tüm hakları saklıdır.</small>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // E-posta temizle
        document.getElementById('clearEmail').addEventListener('click', function() {
            document.getElementById('email').value = '';
            document.getElementById('email').focus();
        });

        // Şifre göster/gizle
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });

        // Form validasyonu
        document.querySelector('form').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Lütfen tüm alanları doldurun.');
                return false;
            }
            
            // Loading state
            const submitBtn = document.querySelector('.btn-login');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Giriş yapılıyor...';
            submitBtn.disabled = true;
        });

        // Auto focus on email field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });
    </script>
</body>
</html> 