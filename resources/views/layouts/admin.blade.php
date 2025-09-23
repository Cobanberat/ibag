<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords"
        content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="{{ asset('images/ibag-logo.svg') }}" type="image/svg+xml" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/dt-2.0.7/datatables.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">

    <link rel="canonical" href="https://demo-basic.adminkit.io/" />
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome (ikonlar için) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/dt-2.0.7/datatables.min.css"/>
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- QR Code Generator -->
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

<!-- QR Code Scanner (jsQR) -->
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

<!-- JS (Blade dosyasının altına ekle) -->


   


    <!-- Bootstrap 5 CSS -->
    <!-- Bootstrap JS (bundle, Popper ile birlikte) -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script> --}}

    <title>IBAG</title>
    @vite(['resources/css/admin.css'])
    @vite(['resources/js/admin.js'])


    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
<div class="wrapper">

    <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">
            <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/ibag-logo.svg') }}" alt="İBAG Logo" style="width: 40px; height: 40px; margin-right: 10px;">
                <span class="align-middle">İBAG Panel</span>
            </a>

            <ul class="sidebar-nav">
                <li class="sidebar-header">Genel</li>

                <!-- Ana Sayfa - Tüm roller -->
                <li class="sidebar-item{{ request()->routeIs('admin.dashboard') ? ' active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.dashboard') }}">
                        <i class="align-middle" data-feather="home"></i>
                        <span class="align-middle">Ana Sayfa</span>
                    </a>
                </li>

                <!-- Zimmet İşlemleri - Tüm roller -->
                <li class="sidebar-item{{ request()->routeIs('admin.zimmetAl') ? ' active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.zimmetAl') }}">
                        <i class="align-middle" data-feather="plus-circle"></i>
                        <span class="align-middle">Zimmet Al</span>
                    </a>
                </li>
                <li class="sidebar-item{{ request()->routeIs('admin.teslimEt') ? ' active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.teslimEt') }}">
                        <i class="align-middle" data-feather="corner-up-right"></i>
                        <span class="align-middle">Teslim Et</span>
                    </a>
                </li>

             
                <!-- Ekipman Yönetimi - Admin ve Ekip Yetkilisi -->
                @if(auth()->user()->canManageEquipment())
                <li class="sidebar-header">Ekipman Yönetimi</li>

                <li class="sidebar-item{{ request()->routeIs('admin.stock') ? ' active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.stock') }}">
                        <i class="align-middle" data-feather="package"></i>
                        <span class="align-middle">Stok</span>
                    </a>
                </li>
                <li class="sidebar-item{{ request()->routeIs('admin.equipments') ? ' active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.equipments') }}">
                        <i class="align-middle" data-feather="tool"></i>
                        <span class="align-middle">Ekipmanlar</span>
                    </a>
                </li>

                <li class="sidebar-item{{ request()->routeIs('admin.kategori') ? ' active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.kategori') }}">
                        <i class="align-middle" data-feather="layers"></i>
                        <span class="align-middle">Kategoriler</span>
                    </a>
                </li>
                @endif

                <!-- Arıza Yönetimi - Admin ve Ekip Yetkilisi -->
                @if(auth()->user()->canManageFaults())
                <li class="sidebar-header">Arıza Yönetimi</li>

                  <!-- Arıza Bildirimi - Tüm roller -->
                  <li class="sidebar-item{{ request()->routeIs('admin.fault.create') ? ' active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.fault.create') }}">
                        <i class="align-middle" data-feather="alert-circle"></i>
                        <span class="align-middle">Arıza Bildir</span>
                    </a>
                </li>


                <li class="sidebar-item{{ request()->routeIs('admin.fault.status') ? ' active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.fault.status') }}">
                        <i class="align-middle" data-feather="check-square"></i>
                        <span class="align-middle">Arıza Durumu</span>
                    </a>
                </li>
                @endif

                <!-- İşlem Takibi - Admin ve Ekip Yetkilisi -->
                @if(auth()->user()->isAdmin() || auth()->user()->isTeamLeader())
                <li class="sidebar-header">İşlem Takibi</li>

                <li class="sidebar-item{{ request()->routeIs('admin.gidenGelen') ? ' active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.gidenGelen') }}">
                        <i class="align-middle" data-feather="repeat"></i>
                        <span class="align-middle">Giden / Gelen İşlemler</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->canManageEquipment())
                <li class="sidebar-item{{ request()->routeIs('admin.equipmentStatus') ? ' active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.equipmentStatus') }}">
                        <i class="align-middle" data-feather="check-circle"></i>
                        <span class="align-middle">Ekipman Durumu</span>
                    </a>
                </li>
                @endif

                <!-- Raporlama - Sadece Admin -->
                @if(auth()->user()->isAdmin())
                <li class="sidebar-header">Raporlama</li>

                <li class="sidebar-item{{ request()->routeIs('admin.reporting') ? ' active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.reporting') }}">
                        <i class="align-middle" data-feather="bar-chart-2"></i>
                        <span class="align-middle">Raporlama</span>
                    </a>
                </li>
                @endif

                <!-- Kullanıcı Yönetimi - Sadece Admin -->
                @if(auth()->user()->canManageUsers())
                <li class="sidebar-header">Kullanıcı Yönetimi</li>

                <li class="sidebar-item{{ request()->routeIs('admin.users') ? ' active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.users') }}">
                        <i class="align-middle" data-feather="user"></i>
                        <span class="align-middle">Kullanıcılar</span>
                    </a>
                </li>
                @endif

                <!-- Profil - Tüm roller -->
                <li class="sidebar-header">Hesap</li>

                <li class="sidebar-item{{ request()->routeIs('admin.profile') ? ' active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.profile') }}">
                        <img src="{{ asset('images/ibag-logo.svg') }}" alt="İBAG Logo" style="width: 20px; height: 20px; margin-right: 8px;">
                        <i class="align-middle" data-feather="user-circle"></i>
                        <span class="align-middle">Profilim</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="main">
        <nav class="navbar navbar-expand navbar-light navbar-bg">
            <a class="sidebar-toggle js-sidebar-toggle">
                <i class="hamburger align-self-center"></i>
            </a>

            <div class="navbar-collapse collapse">
                <ul class="navbar-nav navbar-align d-flex align-items-center">
                   
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown" style="gap: 8px;">
                            <div class="position-relative">
                                <span
                                    class="avatar bg-gradient-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width:36px;height:36px;font-weight:700;font-size:1.2rem;background: linear-gradient(135deg, #3b7ddd 0%, #2f64b1 100%);">
                                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                </span>
                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle" style="width: 12px; height: 12px;">
                                    <span class="visually-hidden">Çevrimiçi</span>
                                </span>
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="text-dark fw-semibold">{{ auth()->user()->name ?? 'Kullanıcı' }}</span>
                                <small class="text-muted">{{ auth()->user()->role_label ?? 'Rol' }}</small>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="min-width:280px; border-radius: 12px; margin-top: 8px;">
                            <!-- Profil Header -->
                            <li class="px-4 py-3 border-bottom" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 12px 12px 0 0;">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-3">
                                        <span
                                            class="avatar bg-gradient-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                            style="width:50px;height:50px;font-weight:700;font-size:1.4rem;background: linear-gradient(135deg, #3b7ddd 0%, #2f64b1 100%);">
                                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                        </span>
                                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle" style="width: 14px; height: 14px;">
                                            <span class="visually-hidden">Çevrimiçi</span>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-dark mb-1">{{ auth()->user()->name ?? 'Kullanıcı' }}</div>
                                        <div class="text-muted small mb-1">{{ auth()->user()->email ?? 'email@example.com' }}</div>
                                        <span class="badge bg-primary text-white" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">
                                            {{ auth()->user()->role_label ?? 'Rol' }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                            
                            <!-- Menü Öğeleri -->
                            <li class="px-2 py-1">
                                <a class="dropdown-item d-flex align-items-center gap-3 py-2 px-3 rounded" href="{{ route('admin.profile') }}" style="transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(59, 125, 221, 0.1); border-radius: 8px;">
                                        <i class="fas fa-user-circle text-primary" style="font-size: 1.1rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">Profilim</div>
                                        <small class="text-muted">Hesap ayarları ve bilgiler</small>
                                    </div>
                                </a>
                            </li>
                            
                            @if(auth()->user()->isAdmin())
                            <!-- Admin için menüler -->
                            <li class="px-2 py-1">
                                <a class="dropdown-item d-flex align-items-center gap-3 py-2 px-3 rounded" href="{{ route('admin.dashboard') }}" style="transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(40, 167, 69, 0.1); border-radius: 8px;">
                                        <i class="fas fa-tachometer-alt text-success" style="font-size: 1.1rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">Ana Sayfa</div>
                                        <small class="text-muted">Dashboard ve genel bakış</small>
                                    </div>
                                </a>
                            </li>
                            
                            <li class="px-2 py-1">
                                <a class="dropdown-item d-flex align-items-center gap-3 py-2 px-3 rounded" href="{{ route('admin.stock') }}" style="transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(255, 87, 34, 0.1); border-radius: 8px;">
                                        <i class="fas fa-boxes text-warning" style="font-size: 1.1rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">Stok Durumu</div>
                                        <small class="text-muted">Ekipman stok takibi</small>
                                    </div>
                                </a>
                            </li>
                            
                            <li class="px-2 py-1">
                                <a class="dropdown-item d-flex align-items-center gap-3 py-2 px-3 rounded" href="{{ route('admin.users') }}" style="transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(108, 117, 125, 0.1); border-radius: 8px;">
                                        <i class="fas fa-users text-secondary" style="font-size: 1.1rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">Kullanıcılar</div>
                                        <small class="text-muted">Kullanıcı yönetimi</small>
                                    </div>
                                </a>
                            </li>
                            
                            @elseif(auth()->user()->isTeamLeader())
                            <!-- Ekip Yetkilisi için menüler -->
                            <li class="px-2 py-1">
                                <a class="dropdown-item d-flex align-items-center gap-3 py-2 px-3 rounded" href="{{ route('admin.dashboard') }}" style="transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(40, 167, 69, 0.1); border-radius: 8px;">
                                        <i class="fas fa-tachometer-alt text-success" style="font-size: 1.1rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">Ana Sayfa</div>
                                        <small class="text-muted">Ekip dashboard'u</small>
                                    </div>
                                </a>
                            </li>
                            
                            <li class="px-2 py-1">
                                <a class="dropdown-item d-flex align-items-center gap-3 py-2 px-3 rounded" href="{{ route('admin.stock') }}" style="transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(255, 87, 34, 0.1); border-radius: 8px;">
                                        <i class="fas fa-boxes text-warning" style="font-size: 1.1rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">Stok Durumu</div>
                                        <small class="text-muted">Ekipman stok takibi</small>
                                    </div>
                                </a>
                            </li>
                            
                            <li class="px-2 py-1">
                                <a class="dropdown-item d-flex align-items-center gap-3 py-2 px-3 rounded" href="{{ route('admin.gidenGelen') }}" style="transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(13, 110, 253, 0.1); border-radius: 8px;">
                                        <i class="fas fa-exchange-alt text-primary" style="font-size: 1.1rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">Giden/Gelen</div>
                                        <small class="text-muted">İşlem takibi</small>
                                    </div>
                                </a>
                            </li>
                            
                            @else
                            <!-- Üye için menüler -->
                            <li class="px-2 py-1">
                                <a class="dropdown-item d-flex align-items-center gap-3 py-2 px-3 rounded" href="{{ route('admin.dashboard') }}" style="transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(40, 167, 69, 0.1); border-radius: 8px;">
                                        <i class="fas fa-tachometer-alt text-success" style="font-size: 1.1rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">Ana Sayfa</div>
                                        <small class="text-muted">Kişisel dashboard</small>
                                    </div>
                                </a>
                            </li>
                            
                            <li class="px-2 py-1">
                                <a class="dropdown-item d-flex align-items-center gap-3 py-2 px-3 rounded" href="{{ route('admin.zimmetAl') }}" style="transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(40, 167, 69, 0.1); border-radius: 8px;">
                                        <i class="fas fa-plus-circle text-success" style="font-size: 1.1rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">Zimmet Al</div>
                                        <small class="text-muted">Yeni ekipman al</small>
                                    </div>
                                </a>
                            </li>
                            
                            <li class="px-2 py-1">
                                <a class="dropdown-item d-flex align-items-center gap-3 py-2 px-3 rounded" href="{{ route('admin.teslimEt') }}" style="transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(255, 193, 7, 0.1); border-radius: 8px;">
                                        <i class="fas fa-hand-holding text-warning" style="font-size: 1.1rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">Teslim Et</div>
                                        <small class="text-muted">Ekipmanı teslim et</small>
                                    </div>
                                </a>
                            </li>
                            
                            <li class="px-2 py-1">
                                <a class="dropdown-item d-flex align-items-center gap-3 py-2 px-3 rounded" href="{{ route('admin.fault.create') }}" style="transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(220, 53, 69, 0.1); border-radius: 8px;">
                                        <i class="fas fa-exclamation-triangle text-danger" style="font-size: 1.1rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">Arıza Bildir</div>
                                        <small class="text-muted">Arıza kaydı oluştur</small>
                                    </div>
                                </a>
                            </li>
                            @endif
                            
                            <li><hr class="dropdown-divider my-2"></li>
                            
                            <li class="px-2 py-1">
                                <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-3 py-2 px-3 rounded text-danger w-100 border-0 bg-transparent" style="transition: all 0.2s ease;">
                                        <div class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(220, 53, 69, 0.1); border-radius: 8px;">
                                            <i class="fas fa-sign-out-alt" style="font-size: 1.1rem;"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">Çıkış Yap</div>
                                            <small class="text-muted">Hesabınızdan güvenle çıkın</small>
                                        </div>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="content">
            @yield('content')
        </main>

        <!-- Toast Container -->
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
            @if (session('success'))
                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header bg-success text-white">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong class="me-auto">Başarılı!</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            
            @if (session('error'))
                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header bg-danger text-white">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong class="me-auto">Hata!</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        {{ session('error') }}
                    </div>
                </div>
            @endif
            
            @if (session('warning'))
                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header bg-warning text-dark">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong class="me-auto">Uyarı!</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        {{ session('warning') }}
                    </div>
                </div> @endif
        </div>

        <footer class="footer">
    <div class="container-fluid">
        <div class="row text-muted">
            <div class="col-12 text-center">
                <p class="mb-0">
                    &copy; {{ date('Y') }} <strong>İBag</strong> | Tüm hakları saklıdır.
                </p>
            </div>
        </div>
    </div>
    </footer>
    </div>
    </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var canvasLine = document.getElementById("chartjs-dashboard-line");
            if (canvasLine) {
                var ctx = canvasLine.getContext("2d");
                var gradient = ctx.createLinearGradient(0, 0, 0, 225);
                gradient.addColorStop(0, "rgba(215, 227, 244, 1)");
                gradient.addColorStop(1, "rgba(215, 227, 244, 0)");
                new Chart(canvasLine, {
                    type: "line",
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct",
                            "Nov",
                            "Dec"
                        ],
                        datasets: [{
                            label: "Sales ($)",
                            fill: true,
                            backgroundColor: gradient,
                            borderColor: window.theme.primary,
                            data: [
                                2115,
                                1562,
                                1584,
                                1892,
                                1587,
                                1923,
                                2566,
                                2448,
                                2805,
                                3438,
                                2917,
                                3327
                            ]
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        tooltips: {
                            intersect: false
                        },
                        hover: {
                            intersect: true
                        },
                        plugins: {
                            filler: {
                                propagate: false
                            }
                        },
                        scales: {
                            xAxes: [{
                                reverse: true,
                                gridLines: {
                                    color: "rgba(0,0,0,0.0)"
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    stepSize: 1000
                                },
                                display: true,
                                borderDash: [3, 3],
                                gridLines: {
                                    color: "rgba(0,0,0,0.0)"
                                }
                            }]
                        }
                    }
                });
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var canvasPie = document.getElementById("chartjs-dashboard-pie");
            if (canvasPie) {
                new Chart(canvasPie, {
                    type: "pie",
                    data: {
                        labels: ["Chrome", "Firefox", "IE"],
                        datasets: [{
                            data: [4306, 3801, 1689],
                            backgroundColor: [
                                window.theme.primary,
                                window.theme.warning,
                                window.theme.danger
                            ],
                            borderWidth: 5
                        }]
                    },
                    options: {
                        responsive: !window.MSInputMethodContext,
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        cutoutPercentage: 75
                    }
                });
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var canvasBar = document.getElementById("chartjs-dashboard-bar");
            if (canvasBar) {
                new Chart(canvasBar, {
                    type: "bar",
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct",
                            "Nov",
                            "Dec"
                        ],
                        datasets: [{
                            label: "This year",
                            backgroundColor: window.theme.primary,
                            borderColor: window.theme.primary,
                            hoverBackgroundColor: window.theme.primary,
                            hoverBorderColor: window.theme.primary,
                            data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79],
                            barPercentage: .75,
                            categoryPercentage: .5
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        scales: {
                            yAxes: [{
                                gridLines: {
                                    display: false
                                },
                                stacked: false,
                                ticks: {
                                    stepSize: 20
                                }
                            }],
                            xAxes: [{
                                stacked: false,
                                gridLines: {
                                    color: "transparent"
                                }
                            }]
                        }
                    }
                });
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var worldMap = document.getElementById("world_map");
            if (worldMap && typeof jsVectorMap !== 'undefined') {
                var markers = [{
                        coords: [31.230391, 121.473701],
                        name: "Shanghai"
                    },
                    {
                        coords: [28.704060, 77.102493],
                        name: "Delhi"
                    },
                    {
                        coords: [6.524379, 3.379206],
                        name: "Lagos"
                    },
                    {
                        coords: [35.689487, 139.691711],
                        name: "Tokyo"
                    },
                    {
                        coords: [23.129110, 113.264381],
                        name: "Guangzhou"
                    },
                    {
                        coords: [40.7127837, -74.0059413],
                        name: "New York"
                    },
                    {
                        coords: [34.052235, -118.243683],
                        name: "Los Angeles"
                    },
                    {
                        coords: [41.878113, -87.629799],
                        name: "Chicago"
                    },
                    {
                        coords: [51.507351, -0.127758],
                        name: "London"
                    },
                    {
                        coords: [40.416775, -3.703790],
                        name: "Madrid "
                    }
                ];
                var map = new jsVectorMap({
                    map: "world",
                    selector: "#world_map",
                    zoomButtons: true,
                    markers: markers,
                    markerStyle: {
                        initial: {
                            r: 9,
                            strokeWidth: 7,
                            stokeOpacity: .4,
                            fill: window.theme.primary
                        },
                        hover: {
                            fill: window.theme.primary,
                            stroke: window.theme.primary
                        }
                    },
                    zoomOnScroll: false
                });
                window.addEventListener("resize", () => {
                    map.updateSize();
                });
            }
        });
    </script>

    @stack('scripts')
    </body>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/v/bs5/dt-2.0.7/datatables.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- xlsx (Excel için) -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



</html>
<style>
    /* From Uiverse.io by Type-Delta */
    /* a clone from joshwcomeau.com
 * but this version runs on pure CSS
 */

    .themeToggle {
        color: #bbb;
        width: 3em;
    }

    .st-sunMoonThemeToggleBtn {
        position: relative;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .st-sunMoonThemeToggleBtn .themeToggleInput {
        opacity: 0;
        width: 100%;
        aspect-ratio: 1;
    }

    .st-sunMoonThemeToggleBtn svg {
        position: absolute;
        left: 0;
        width: 70%;
        height: 70%;
        transition: transform 0.4s ease;
        transform: rotate(40deg);
    }

    .st-sunMoonThemeToggleBtn svg .sunMoon {
        transform-origin: center center;
        transition: inherit;
        transform: scale(1);
    }

    .st-sunMoonThemeToggleBtn svg .sunRay {
        transform-origin: center center;
        transform: scale(0);
    }

    .st-sunMoonThemeToggleBtn svg mask>circle {
        transition: transform 0.64s cubic-bezier(0.41, 0.64, 0.32, 1.575);
        transform: translate(0px, 0px);
    }

    .st-sunMoonThemeToggleBtn svg .sunRay2 {
        animation-delay: 0.05s !important;
    }

    .st-sunMoonThemeToggleBtn svg .sunRay3 {
        animation-delay: 0.1s !important;
    }

    .st-sunMoonThemeToggleBtn svg .sunRay4 {
        animation-delay: 0.17s !important;
    }

    .st-sunMoonThemeToggleBtn svg .sunRay5 {
        animation-delay: 0.25s !important;
    }

    .st-sunMoonThemeToggleBtn svg .sunRay5 {
        animation-delay: 0.29s !important;
    }

    .st-sunMoonThemeToggleBtn .themeToggleInput:checked+svg {
        transform: rotate(90deg);
    }

    .st-sunMoonThemeToggleBtn .themeToggleInput:checked+svg mask>circle {
        transform: translate(16px, -3px);
    }

    .st-sunMoonThemeToggleBtn .themeToggleInput:checked+svg .sunMoon {
        transform: scale(0.55);
    }

    .st-sunMoonThemeToggleBtn .themeToggleInput:checked+svg .sunRay {
        animation: showRay1832 0.4s ease 0s 1 forwards;
    }

    @keyframes showRay1832 {
        0% {
            transform: scale(0);
        }

        100% {
            transform: scale(1);
        }
    }
</style>

@push('scripts')
    <script>
        if (window.feather) {
            feather.replace();
        }

        // Toast otomatik kaybolma
        document.addEventListener('DOMContentLoaded', function() {
            var toasts = document.querySelectorAll('.toast');
            toasts.forEach(function(toast) {
                setTimeout(function() {
                    var bsToast = new bootstrap.Toast(toast);
                    bsToast.hide();
                }, 5000); // 5 saniye sonra kaybolur
            });
        });
    </script>
    
    <!-- Arıza Yönetimi JavaScript -->
    @if(request()->routeIs('admin.fault.*'))
        @vite('resources/js/fault-management.js')
    @endif
    
    <!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
