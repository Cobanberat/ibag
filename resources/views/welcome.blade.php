<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="İBAG (İyiliğe Çağrı Arama Kurtarma) - Konya'da arama kurtarma ve afet yardım hizmetleri">
    <meta name="author" content="İBAG">
    <meta name="keywords" content="ibag, arama kurtarma, afet yardım, konya, gönüllülük">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/favicon.ico" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <title>İBAG - İyiliğe Çağrı Arama Kurtarma</title>

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

        .content-section {
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

        .info-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            height: 100%;
            margin-bottom: 2rem;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .info-icon {
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

        .info-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--bs-dark);
            margin-bottom: 1rem;
            text-align: center;
        }

        .info-description {
            color: #6c757d;
            line-height: 1.6;
            text-align: center;
        }

        .mission-section {
            padding: 4rem 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .mission-card {
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

        .mission-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--bs-success), var(--bs-primary));
        }

        .mission-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .mission-icon {
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

        .mission-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--bs-dark);
            margin-bottom: 1rem;
        }

        .mission-description {
            color: #6c757d;
            line-height: 1.6;
        }

        .equipment-section {
            padding: 4rem 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
        }

        .equipment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .equipment-item {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .equipment-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .equipment-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--bs-warning) 0%, #e0a800 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: white;
            font-size: 1.25rem;
        }

        .equipment-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--bs-dark);
            margin-bottom: 0.5rem;
        }

        .equipment-description {
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .cta-section {
            padding: 4rem 0;
            background: linear-gradient(135deg, var(--bs-success) 0%, #15a06b 100%);
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

        .organization-info {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 2rem;
        }

        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-list li {
            padding: 0.75rem 0;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
        }

        .info-list li:last-child {
            border-bottom: none;
        }

        .info-list i {
            width: 30px;
            color: var(--bs-primary);
            margin-right: 1rem;
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
                <i class="fas fa-heart me-2"></i>İBAG
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Ana Sayfa</a>
                    </li>
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
                <h1 class="hero-title">İBAG</h1>
                <p class="hero-subtitle">İyiliğe Çağrı Arama Kurtarma</p>
                <div class="hero-buttons">
                    <a href="{{ route('home') }}" class="btn btn-hero btn-primary-hero">
                        <i class="fas fa-home me-2"></i>Ana Sayfa
                    </a>
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-hero btn-outline-hero">
                            <i class="fas fa-sign-in-alt me-2"></i>Giriş Yap
                        </a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-hero btn-outline-hero">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="organization-info">
                <h2 class="section-title">Organizasyon Bilgileri</h2>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="info-list">
                            <li>
                                <i class="fas fa-building"></i>
                                <strong>Organizasyon Adı:</strong> İBAG (İyiliğe Çağrı Arama Kurtarma)
                            </li>
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <strong>Lokasyon:</strong> Konya, Türkiye
                            </li>
                            <li>
                                <i class="fas fa-bullseye"></i>
                                <strong>Alan:</strong> Arama Kurtarma, Afet Yardım, Gönüllülük Hizmetleri
                            </li>
                            <li>
                                <i class="fas fa-globe"></i>
                                <strong>Web Sitesi:</strong> 
                                <a href="http://www.iyiligeçagrı.org.tr" target="_blank" class="text-decoration-none">www.iyiligeçagrı.org.tr</a>
                            </li>
                    </ul>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center">
                            <div class="info-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h3 class="info-title">Gönüllülük Ruhu</h3>
                            <p class="info-description">Toplum yararına çalışan, eğitimli gönüllülerden oluşan profesyonel bir arama kurtarma organizasyonu.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mission-section">
        <div class="container">
            <h2 class="section-title">🎯 Misyon ve Amaç</h2>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="mission-card">
                        <div class="mission-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="mission-title">Arama Kurtarma</h3>
                        <p class="mission-description">Deprem, sel, yangın gibi doğal afetlerde profesyonel arama-kurtarma çalışmaları yürütmek.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="mission-card">
                        <div class="mission-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h3 class="mission-title">Yardım Ulaştırma</h3>
                        <p class="mission-description">Gönüllü ekiplerle ihtiyaç sahiplerine yardım ulaştırmak ve destek sağlamak.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="mission-card">
                        <div class="mission-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h3 class="mission-title">Farkındalık</h3>
                        <p class="mission-description">Afet bilinci oluşturmak ve toplumda farkındalık yaratmak için eğitimler düzenlemek.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <h2 class="section-title">🧑‍🚒 Ekip Yapısı</h2>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="info-title">Gönüllüler</h3>
                        <p class="info-description">Eğitilmiş arama kurtarma gönüllüleri, afet durumlarında aktif olarak çalışır.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3 class="info-title">Eğitmenler</h3>
                        <p class="info-description">Sertifikalı ilk yardım ve kurtarma eğitmenleri, ekip eğitimlerini yönetir.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h3 class="info-title">Destek Ekibi</h3>
                        <p class="info-description">Lojistik, sağlık, iletişim gibi destek birimleri operasyonları destekler.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="equipment-section">
        <div class="container">
            <h2 class="section-title">🧰 Ekipmanlar</h2>
            <div class="equipment-grid">
                <div class="equipment-item">
                    <div class="equipment-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4 class="equipment-title">Arama Cihazları</h4>
                    <p class="equipment-description">Isı ve hareket sensörleri ile gelişmiş arama teknolojileri.</p>
                </div>
                <div class="equipment-item">
                    <div class="equipment-icon">
                        <i class="fas fa-first-aid"></i>
                    </div>
                    <h4 class="equipment-title">İlk Yardım Kitleri</h4>
                    <p class="equipment-description">Travma çantaları, oksijen tüpleri ve acil müdahale ekipmanları.</p>
                </div>
                <div class="equipment-item">
                    <div class="equipment-icon">
                        <i class="fas fa-walkie-talkie"></i>
                    </div>
                    <h4 class="equipment-title">İletişim Cihazları</h4>
                    <p class="equipment-description">Telsiz, uydu telefonları ve koordinasyon ekipmanları.</p>
                </div>
                <div class="equipment-item">
                    <div class="equipment-icon">
                        <i class="fas fa-hard-hat"></i>
                    </div>
                    <h4 class="equipment-title">Koruyucu Kıyafetler</h4>
                    <p class="equipment-description">Kask, eldiven, reflektörlü yelek ve güvenlik ekipmanları.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <h2 class="section-title">📦 Stok Takibi</h2>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-database"></i>
                        </div>
                        <h3 class="info-title">Dijital Kayıt</h3>
                        <p class="info-description">Tüm ekipmanlar dijital ortamda detaylı şekilde kaydedilir ve takip edilir.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <h3 class="info-title">Kullanım Kaydı</h3>
                        <p class="info-description">Hangi olayda, ne zaman, hangi ekipman kullanıldı/geldi/gitti bilgisi tutulur.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h3 class="info-title">Stok Uyarıları</h3>
                        <p class="info-description">Stoğu tükenen malzemeler sistemde otomatik olarak bildirilir.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">Birlikte Daha Güçlüyüz</h2>
            <p class="cta-description">İBAG olarak toplum yararına çalışmaya devam ediyoruz. Siz de bize katılabilirsiniz!</p>
            <div class="hero-buttons">
                <a href="{{ route('home') }}" class="btn btn-hero btn-outline-hero">
                    <i class="fas fa-home me-2"></i>Ana Sayfa
                </a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-hero btn-outline-hero">
                        <i class="fas fa-user-plus me-2"></i>Gönüllü Ol
                    </a>
                @else
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-hero btn-outline-hero">
                        <i class="fas fa-tachometer-alt me-2"></i>Sisteme Git
                    </a>
                @endguest
    </div>
</div>
    </section>

    <footer class="footer">
        <div class="container">
            <p class="mb-0">© 2024 İBAG (İyiliğe Çağrı Arama Kurtarma). Tüm hakları saklıdır.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 