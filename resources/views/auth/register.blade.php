<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="İBAG Ekipman Yönetim Sistemi - Kayıt">
    <meta name="author" content="İBAG">
    <meta name="keywords" content="ibag, ekipman, yönetim, sistem, kayıt">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/favicon.ico" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <title>İBAG - Kayıt Ol</title>

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

        .register-container {
            width: 100%;
            max-width: 450px;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, var(--bs-success) 0%, #15a06b 100%);
            color: white;
            text-align: center;
            padding: 2rem 1.5rem;
            position: relative;
        }

        .register-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .register-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .register-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
        }

        .register-body {
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
            border-color: var(--bs-success);
            box-shadow: 0 0 0 0.2rem rgba(28, 187, 140, 0.25);
            background: white;
        }

        .form-floating label {
            color: #6c757d;
            font-weight: 500;
        }

        .form-floating .form-control:focus + label,
        .form-floating .form-control:not(:placeholder-shown) + label {
            color: var(--bs-success);
        }

        .btn-register {
            background: linear-gradient(135deg, var(--bs-success) 0%, #15a06b 100%);
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

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(28, 187, 140, 0.3);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .register-footer {
            text-align: center;
            padding: 1rem 1.5rem 1.5rem;
            border-top: 1px solid #e9ecef;
            background: #f8f9fa;
        }

        .register-footer a {
            color: var(--bs-success);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .register-footer a:hover {
            color: #15a06b;
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

        .brand-logo {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
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

        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.85rem;
        }

        .strength-bar {
            height: 4px;
            border-radius: 2px;
            background: #e9ecef;
            margin-top: 0.25rem;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { background: #dc3545; width: 25%; }
        .strength-fair { background: #fd7e14; width: 50%; }
        .strength-good { background: #ffc107; width: 75%; }
        .strength-strong { background: #28a745; width: 100%; }

        @media (max-width: 576px) {
            .register-container {
                max-width: 100%;
            }
            
            .register-card {
                border-radius: 15px;
            }
            
            .register-header {
                padding: 1.5rem 1rem;
            }
            
            .register-body {
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

    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="brand-logo">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1>İBAG</h1>
                <p>Hesap Oluştur</p>
            </div>

            <div class="register-body">
                @if ($errors->any())
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Lütfen form bilgilerinizi kontrol ediniz.
                    </div>
                @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                    <div class="form-floating">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" placeholder="Ad Soyad" required autocomplete="name" autofocus>
                        <label for="name">
                            <i class="fas fa-user me-2"></i>Ad Soyad
                        </label>
                                @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        </div>

                    <div class="form-floating">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" placeholder="E-posta" required autocomplete="email">
                        <label for="email">
                            <i class="fas fa-envelope me-2"></i>E-posta Adresi
                        </label>
                                @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        </div>  

                    <div class="form-floating">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" placeholder="Şifre" required autocomplete="new-password">
                        <label for="password">
                            <i class="fas fa-lock me-2"></i>Şifre
                        </label>
                                @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="password-strength">
                            <small class="text-muted">Şifre gücü: <span id="strength-text">Zayıf</span></small>
                            <div class="strength-bar">
                                <div class="strength-fill" id="strength-fill"></div>
                            </div>
                        </div>
                            </div>

                    <div class="form-floating">
                        <input id="password-confirm" type="password" class="form-control" 
                               name="password_confirmation" placeholder="Şifre Tekrar" required autocomplete="new-password">
                        <label for="password-confirm">
                            <i class="fas fa-lock me-2"></i>Şifre Tekrar
                        </label>
                        </div>

                    <button type="submit" class="btn btn-register">
                        <i class="fas fa-user-plus me-2"></i>Hesap Oluştur
                    </button>
                    </form>
                </div>

            <div class="register-footer">
                <p class="mb-2">Zaten hesabınız var mı? <a href="{{ route('login') }}">Giriş Yap</a></p>
                <small class="text-muted">© 2024 İBAG. Tüm hakları saklıdır.</small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Şifre gücü kontrolü
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthText = document.getElementById('strength-text');
            const strengthFill = document.getElementById('strength-fill');
            
            let strength = 0;
            let text = 'Zayıf';
            let className = 'strength-weak';
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            switch(strength) {
                case 0:
                case 1:
                    text = 'Zayıf';
                    className = 'strength-weak';
                    break;
                case 2:
                    text = 'Orta';
                    className = 'strength-fair';
                    break;
                case 3:
                    text = 'İyi';
                    className = 'strength-good';
                    break;
                case 4:
                case 5:
                    text = 'Güçlü';
                    className = 'strength-strong';
                    break;
            }
            
            strengthText.textContent = text;
            strengthFill.className = 'strength-fill ' + className;
        });
    </script>
</body>
</html> 