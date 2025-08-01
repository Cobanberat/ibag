@extends('layouts.admin')
@section('content')
@vite(['resources/css/profile.css'])
<div class="profile-bg-effect"></div>
<div class="container-fluid profile-container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="profile-card shadow-lg">
                <div class="row">
                    <!-- Sol taraf - Profil resmi ve bilgiler -->
                    <div class="col-md-4 text-center">
                        <div class="profile-photo-wrap position-relative mx-auto mb-4">
                            <img src="https://ui-avatars.com/api/?name=Admin+User&background=0d6efd&color=fff&size=160" class="profile-photo" alt="Profil Fotoğrafı">
                            <button class="btn btn-photo-edit" id="changePhotoBtn" title="Fotoğrafı Değiştir"><i class="fas fa-camera"></i></button>
                        </div>
                        <div class="profile-info">
                            <h3 class="profile-name mb-2">Admin User</h3>
                            <div class="profile-role mb-3"><span class="badge bg-gradient-primary">Yönetici</span></div>
                            <div class="profile-meta mb-3">
                                <div><i class="fas fa-calendar-alt me-2"></i> Kayıt: 2024-01-01</div>
                                <div><i class="fas fa-circle text-success me-2"></i> Durum: Aktif</div>
                            </div>
                        </div>
                        <!-- İstatistikler -->
                        <div class="profile-stats mt-4">
                            <div class="stat-box mb-3">
                                <div class="stat-icon bg-gradient-blue"><i class="fas fa-sign-in-alt"></i></div>
                                <div class="stat-label">Son Giriş</div>
                                <div class="stat-value">2024-06-01</div>
                            </div>
                            <div class="stat-box mb-3">
                                <div class="stat-icon bg-gradient-green"><i class="fas fa-tasks"></i></div>
                                <div class="stat-label">Toplam İşlem</div>
                                <div class="stat-value">42</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-icon bg-gradient-orange"><i class="fas fa-clock"></i></div>
                                <div class="stat-label">Aktiflik</div>
                                <div class="stat-value">%95</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sağ taraf - Düzenlenebilir form -->
                    <div class="col-md-8">
                        <div class="profile-form-section">
                            <h4 class="section-title mb-4"><i class="fas fa-user-edit me-2"></i>Profil Bilgileri</h4>
                            
                            <form id="profileForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="profileName" class="form-label">Ad Soyad</label>
                                        <input type="text" class="form-control profile-input" id="profileName" value="Admin User">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="profileEmail" class="form-label">E-posta</label>
                                        <input type="email" class="form-control profile-input" id="profileEmail" value="admin@example.com">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="profilePhone" class="form-label">Telefon</label>
                                        <input type="tel" class="form-control profile-input" id="profilePhone" value="+90 555 123 4567">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="profileDepartment" class="form-label">Departman</label>
                                        <input type="text" class="form-control profile-input" id="profileDepartment" value="Yönetim">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="profileAddress" class="form-label">Adres</label>
                                    <textarea class="form-control profile-input" id="profileAddress" rows="3">İstanbul, Türkiye</textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="profileCity" class="form-label">Şehir</label>
                                        <input type="text" class="form-control profile-input" id="profileCity" value="İstanbul">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="profileCountry" class="form-label">Ülke</label>
                                        <input type="text" class="form-control profile-input" id="profileCountry" value="Türkiye">
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
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="currentPassword" class="form-label">Mevcut Şifre</label>
                                            <input type="password" class="form-control profile-input" id="currentPassword">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="newPassword" class="form-label">Yeni Şifre</label>
                                            <input type="password" class="form-control profile-input" id="newPassword">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="confirmPassword" class="form-label">Yeni Şifre (Tekrar)</label>
                                            <input type="password" class="form-control profile-input" id="confirmPassword">
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
