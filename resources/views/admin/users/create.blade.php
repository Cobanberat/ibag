@extends('layouts.admin')
@section('content')
@vite(['resources/css/users.css'])

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center">
                <img src="{{ asset('images/ibag-logo.svg') }}" alt="İBAG Logo" style="width: 24px; height: 24px; margin-right: 8px;">
                <i class="fa fa-home me-1"></i> Ana Sayfa
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.users') }}" class="text-decoration-none">Kullanıcılar</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Yeni Kullanıcı</li>
    </ol>
</nav>

<div class="container-fluid">
    <!-- Sayfa Başlığı -->
    <div class="page-header mb-4 text-center">
        <div class="d-flex align-items-center justify-content-center mb-3">
            <img src="{{ asset('images/ibag-logo.svg') }}" alt="İBAG Logo" style="width: 48px; height: 48px; margin-right: 15px;">
            <h2 class="mb-0 text-primary">
                <i class="fas fa-user-plus me-2"></i>Yeni Kullanıcı Ekle
            </h2>
        </div>
        <p class="text-muted">Sisteme yeni kullanıcı ekleyin ve rol atayın</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>Kullanıcı Bilgileri
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Hata!</strong> Lütfen aşağıdaki hataları düzeltin:
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form id="createUserForm" action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-1"></i>Ad Soyad <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="Kullanıcının adı ve soyadı" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>E-posta <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="ornek@email.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">
                                    <i class="fas fa-at me-1"></i>Kullanıcı Adı
                                </label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" value="{{ old('username') }}" 
                                       placeholder="kullanici_adi">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Boş bırakılırsa e-posta adresi kullanılır</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">
                                    <i class="fas fa-user-tag me-1"></i>Rol <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="">Rol Seçin</option>
                                    @foreach($roles as $key => $label)
                                        <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-1"></i>Şifre <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" placeholder="En az 8 karakter" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock me-1"></i>Şifre Tekrar <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="Şifreyi tekrar girin" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">
                                    <i class="fas fa-toggle-on me-1"></i>Durum
                                </label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="avatar_color" class="form-label">
                                    <i class="fas fa-palette me-1"></i>Avatar Rengi
                                </label>
                                <input type="color" class="form-control form-control-color @error('avatar_color') is-invalid @enderror" 
                                       id="avatar_color" name="avatar_color" value="{{ old('avatar_color', '#0d6efd') }}">
                                @error('avatar_color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-actions mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-gradient-primary">
                                <i class="fas fa-save me-1"></i> Kullanıcı Oluştur
                            </button>
                            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Geri Dön
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e7f1ff 100%);
    border-radius: 1rem;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 16px rgba(13,110,253,0.08);
    border: 1px solid rgba(13,110,253,0.1);
}

.page-header h2 {
    font-size: 2.2rem;
    font-weight: 700;
    color: #0d6efd;
    text-shadow: 0 1px 2px rgba(13,110,253,0.1);
}

.page-header p {
    font-size: 1.1rem;
    color: #6c757d;
    margin-bottom: 0;
}

.bg-gradient-primary {
    background: linear-gradient(90deg, #0d6efd 60%, #0dcaf0 100%) !important;
}

.btn-gradient-primary {
    background: linear-gradient(90deg, #0d6efd 60%, #0dcaf0 100%);
    color: #fff;
    border: none;
    font-weight: 600;
    border-radius: 0.5rem;
    box-shadow: 0 1px 4px rgba(13,110,253,0.22);
    transition: all 0.2s;
}

.btn-gradient-primary:hover {
    background: linear-gradient(90deg, #0dcaf0 0%, #0d6efd 100%);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(13,110,253,0.3);
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.25);
}

.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.25);
}

@media (max-width: 768px) {
    .page-header {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .page-header h2 {
        font-size: 1.8rem;
    }
    
    .page-header .d-flex {
        flex-direction: column;
        text-align: center;
    }
    
    .page-header img {
        margin-right: 0 !important;
        margin-bottom: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Şifre göster/gizle
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    // Şifre tekrar göster/gizle
    const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    togglePasswordConfirmation.addEventListener('click', function() {
        const type = passwordConfirmation.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmation.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    // Form validasyonu
    const form = document.getElementById('createUserForm');
    form.addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        
        if (password !== passwordConfirmation) {
            e.preventDefault();
            alert('Şifreler eşleşmiyor!');
            return false;
        }
        
        if (password.length < 8) {
            e.preventDefault();
            alert('Şifre en az 8 karakter olmalıdır!');
            return false;
        }
    });

    // Kullanıcı adı otomatik doldurma
    const emailInput = document.getElementById('email');
    const usernameInput = document.getElementById('username');
    
    emailInput.addEventListener('blur', function() {
        if (!usernameInput.value && this.value) {
            const username = this.value.split('@')[0];
            usernameInput.value = username;
        }
    });
});
</script>
@endsection
