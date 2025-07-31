<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="İBAG Ekipman Yönetim Sistemi - Profesyonel ekipman takibi ve yönetimi">
    <meta name="author" content="İBAG">
    <meta name="keywords" content="ibag, ekipman, yönetim, sistem, jeneratör, takip">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/favicon.ico" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <title>İBAG - Ekipman Yönetim Sistemi</title>

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
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--bs-primary) !important;
            font-size: 1.5rem;
        }

        .nav-link {
            color: var(--bs-dark) !important;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--bs-primary) !important;
        }

        .hero-section {
            padding: 6rem 0 4rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            font-weight: 400;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-hero {
            padding: 0.875rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary-hero {
            background: linear-gradient(135deg, var(--bs-primary) 0%, #2f64b1 100%);
            color: white;
        }

        .btn-primary-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 125, 221, 0.3);
            color: white;
        }

        .btn-outline-hero {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .btn-outline-hero:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-2px);
        }

        .features-section {
            padding: 4rem 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--bs-dark);
            margin-bottom: 3rem;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--bs-primary) 0%, #2f64b1 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--bs-dark);
            margin-bottom: 1rem;
        }

        .feature-description {
            color: #6c757d;
            line-height: 1.6;
        }

        .roles-section {
            padding: 4rem 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .role-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .role-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--bs-primary), var(--bs-success));
        }

        .role-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .role-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--bs-success) 0%, #15a06b 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: white;
            font-size: 1.5rem;
        }

        .role-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--bs-dark);
            margin-bottom: 1rem;
        }

        .role-description {
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .role-tasks {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .role-tasks li {
            padding: 0.5rem 0;
            color: #6c757d;
            position: relative;
            padding-left: 1.5rem;
        }

        .role-tasks li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--bs-success);
            font-weight: bold;
        }

        .cta-section {
            padding: 4rem 0;
            background: linear-gradient(135deg, var(--bs-primary) 0%, #2f64b1 100%);
            color: white;
            text-align: center;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta-description {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .footer {
            background: var(--bs-dark);
            color: white;
            padding: 2rem 0;
            text-align: center;
        }

        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
            pointer-events: none;
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

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-hero {
                width: 100%;
                max-width: 300px;
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

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-tools me-2"></i>İBAG
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Giriş Yap</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Kayıt Ol</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Çıkış Yap
                            </a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">İBAG Ekipman Yönetim Sistemi</h1>
                <p class="hero-subtitle">Profesyonel ekipman takibi, arıza yönetimi ve stok kontrolü için geliştirilmiş modern çözüm</p>
                <div class="hero-buttons">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-hero btn-primary-hero">
                            <i class="fas fa-sign-in-alt me-2"></i>Giriş Yap
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-hero btn-outline-hero">
                            <i class="fas fa-user-plus me-2"></i>Kayıt Ol
                        </a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-hero btn-primary-hero">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard'a Git
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <section class="features-section">
        <div class="container">
            <h2 class="section-title">Sistem Özellikleri</h2>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h3 class="feature-title">Ekipman Takibi</h3>
                        <p class="feature-description">Tüm ekipmanlarınızın detaylı takibini yapın, durumlarını izleyin ve geçmiş kayıtlarına erişin.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h3 class="feature-title">Arıza Yönetimi</h3>
                        <p class="feature-description">Arıza bildirimlerini hızlıca oluşturun, takip edin ve bakım süreçlerini düzenli yönetin.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-warehouse"></i>
                        </div>
                        <h3 class="feature-title">Stok Kontrolü</h3>
                        <p class="feature-description">Stok seviyelerini takip edin, yeni ekipmanları sisteme ekleyin ve envanter yönetimi yapın.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="feature-title">Raporlama</h3>
                        <p class="feature-description">Detaylı raporlar oluşturun, analizler yapın ve veri odaklı kararlar alın.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="feature-title">Mobil Uyumlu</h3>
                        <p class="feature-description">Tüm cihazlardan erişim sağlayın, sahada çalışırken bile sistemi kullanın.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="feature-title">Güvenli Erişim</h3>
                        <p class="feature-description">Rol tabanlı yetkilendirme sistemi ile güvenli ve kontrollü erişim sağlayın.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="roles-section">
        <div class="container">
            <h2 class="section-title">Kullanıcı Rolleri</h2>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="role-card">
                        <div class="role-icon">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <h3 class="role-title">Ekip Yetkilisi</h3>
                        <p class="role-description">Sahada çalışan ekip yetkilileri için özel arayüz ve işlevler.</p>
                        <ul class="role-tasks">
                            <li>İş ekleme ve yönetimi</li>
                            <li>Ekipman seçimi ve fotoğraf çekimi</li>
                            <li>Giden-gelen işlem takibi</li>
                            <li>Arıza bildirimi</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="role-card">
                        <div class="role-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h3 class="role-title">Yönetici</h3>
                        <p class="role-description">Sistem yönetimi ve genel kontrol için kapsamlı yetkiler.</p>
                        <ul class="role-tasks">
                            <li>Arıza taleplerini yönetme</li>
                            <li>Ekipman geçmişi kontrolü</li>
                            <li>Raporlama ve analiz</li>
                            <li>Sistem ayarları</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="role-card">
                        <div class="role-icon">
                            <i class="fas fa-user-hard-hat"></i>
                        </div>
                        <h3 class="role-title">Depo Yetkilisi</h3>
                        <p class="role-description">Stok yönetimi ve ekipman kayıt işlemleri için özel yetkiler.</p>
                        <ul class="role-tasks">
                            <li>Yeni ekipman ekleme</li>
                            <li>Stok kontrolü</li>
                            <li>Ekipman fotoğrafları</li>
                            <li>Envanter yönetimi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">Hemen Başlayın</h2>
            <p class="cta-description">İBAG Ekipman Yönetim Sistemi ile ekipmanlarınızı profesyonelce yönetin</p>
            <div class="hero-buttons">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-hero btn-outline-hero">
                        <i class="fas fa-rocket me-2"></i>Ücretsiz Kayıt Ol
                    </a>
                @else
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-hero btn-outline-hero">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard'a Git
                    </a>
                @endguest
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p class="mb-0">© 2024 İBAG Ekipman Yönetim Sistemi. Tüm hakları saklıdır.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 