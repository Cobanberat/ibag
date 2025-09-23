@extends('layouts.admin')
@section('content')
@vite(['resources/css/profile.css'])

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center">
                <img src="{{ asset('images/ibag-logo.svg') }}" alt="İBAG Logo" style="width: 24px; height: 24px; margin-right: 8px;">
                <i class="fa fa-home me-1"></i> Ana Sayfa
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-user-circle me-1"></i> Profilim
        </li>
    </ol>
</nav>

<div class="profile-bg-effect"></div>
<div class="container-fluid profile-container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Sayfa Başlığı -->
            <div class="page-header mb-4 text-center">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <img src="{{ asset('images/ibag-logo.svg') }}" alt="İBAG Logo" style="width: 48px; height: 48px; margin-right: 15px;">
                    <h2 class="mb-0 text-primary">
                        <i class="fas fa-user-circle me-2"></i>Profil Yönetimi
                    </h2>
                </div>
                <p class="text-muted">Hesap bilgilerinizi görüntüleyin ve düzenleyin</p>
            </div>
            
            <div class="profile-card shadow-lg">
                <div class="row">
                    <!-- Sol taraf - Profil resmi ve bilgiler -->
                    <div class="col-md-4 text-center">
                        <div class="profile-photo-wrap position-relative mx-auto mb-4">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=0d6efd&color=fff&size=160" class="profile-photo" alt="Profil Fotoğrafı">
                            <button class="btn btn-photo-edit" id="changePhotoBtn" title="Fotoğrafı Değiştir"><i class="fas fa-camera"></i></button>
                        </div>
                        <div class="profile-info">
                            <h3 class="profile-name mb-2">{{ auth()->user()->name ?? 'Kullanıcı' }}</h3>
                            <div class="profile-role mb-3">
                                <span class="badge bg-gradient-primary">{{ auth()->user()->role_label ?? 'Rol' }}</span>
                            </div>
                            <div class="profile-meta mb-3">
                                <div><i class="fas fa-calendar-alt me-2"></i> Kayıt: {{ auth()->user()->created_at ? auth()->user()->created_at->format('d.m.Y') : 'Bilinmiyor' }}</div>
                                <div><i class="fas fa-circle text-success me-2"></i> Durum: Aktif</div>
                            </div>
                        </div>
                        <!-- İstatistikler -->
                        <div class="profile-stats mt-4">
                            <div class="stat-box mb-3">
                                <div class="stat-icon bg-gradient-blue"><i class="fas fa-sign-in-alt"></i></div>
                                <div class="stat-label">Son Giriş</div>
                                <div class="stat-value">{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('d.m.Y H:i') : 'Bilinmiyor' }}</div>
                            </div>
                            <div class="stat-box mb-3">
                                <div class="stat-icon bg-gradient-green"><i class="fas fa-tasks"></i></div>
                                <div class="stat-label">Toplam İşlem</div>
                                <div class="stat-value">{{ \App\Models\Assignment::where('user_id', auth()->id())->count() }}</div>
                            </div>
                        </div>
                    </div>
                    <!-- Sağ taraf - Düzenlenebilir form -->
                    <div class="col-md-8">
                        <div class="profile-form-section">
                            <h4 class="section-title mb-4"><i class="fas fa-user-edit me-2"></i>Profil Bilgileri</h4>
                            <form id="profileForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="profileName" class="form-label">Ad Soyad <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control profile-input" id="profileName" name="name" value="{{ auth()->user()->name ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="profileEmail" class="form-label">E-posta <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control profile-input" id="profileEmail" name="email" value="{{ auth()->user()->email ?? '' }}" required>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="profileUsername" class="form-label">Kullanıcı Adı</label>
                                        <input type="text" class="form-control profile-input" id="profileUsername" name="username" value="{{ auth()->user()->username ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="profileRole" class="form-label">Rol</label>
                                        <input type="text" class="form-control" id="profileRole" value="{{ auth()->user()->role_label ?? 'Bilinmiyor' }}" readonly>
                                    </div>
                                </div>
                                
                                <div class="form-actions mt-4">
                                    <button type="submit" class="btn btn-gradient-primary me-2">
                                        <i class="fas fa-save me-1"></i> Bilgileri Kaydet
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="resetForm">
                                        <i class="fas fa-undo me-1"></i> Sıfırla
                                    </button>
                                </div>
                            </form>
                            
                            <hr class="my-4">
                            
                            <!-- Şifre değiştirme bölümü -->
                            <div class="password-section">
                                <h5 class="section-title mb-3"><i class="fas fa-key me-2"></i>Şifre Değiştir</h5>
                                <form id="passwordForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="currentPassword" class="form-label">Mevcut Şifre <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control profile-input" id="currentPassword" name="current_password" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="newPassword" class="form-label">Yeni Şifre <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control profile-input" id="newPassword" name="password" required minlength="8">
                                            <small class="text-muted">En az 8 karakter olmalıdır</small>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="confirmPassword" class="form-label">Yeni Şifre (Tekrar) <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control profile-input" id="confirmPassword" name="password_confirmation" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-gradient-warning">
                                        <i class="fas fa-key me-1"></i> Şifreyi Değiştir
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@vite(['resources/js/profile.js'])
@endsection
