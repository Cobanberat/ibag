@extends('layouts.admin')
@section('content')
@vite(['resources/css/fault.css'])

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
            <a href="{{ route('admin.fault') }}" class="text-decoration-none">Arıza Bildirimi</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Yeni Arıza Bildirimi</li>
    </ol>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Yeni Arıza/Bakım Bildirimi</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Başarılı!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Hata!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Hata!</strong> Lütfen aşağıdaki hataları düzeltin:
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form id="faultForm" action="{{ route('admin.fault.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="equipment_stock_id" class="form-label">
                                        <i class="fas fa-tools me-1"></i>Ekipman Seçin <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('equipment_stock_id') is-invalid @enderror" 
                                            id="equipment_stock_id" name="equipment_stock_id" required>
                                        <option value="">Ekipman Seçin</option>
                                        @foreach($equipmentStocks as $stock)
                                            <option value="{{ $stock->id }}" {{ old('equipment_stock_id') == $stock->id ? 'selected' : '' }}>
                                                {{ $stock->equipment->name }} - {{ $stock->code }} ({{ $stock->equipment->category->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('equipment_stock_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">
                                        <i class="fas fa-tag me-1"></i>Bildirim Türü <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="">Tür Seçin</option>
                                        <option value="arıza" {{ old('type') == 'arıza' ? 'selected' : '' }}>Arıza</option>
                                        <option value="bakım" {{ old('type') == 'bakım' ? 'selected' : '' }}>Bakım</option>
                                        <option value="diğer" {{ old('type') == 'diğer' ? 'selected' : '' }}>Diğer</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">
                                        <i class="fas fa-exclamation-circle me-1"></i>Öncelik <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('priority') is-invalid @enderror" 
                                            id="priority" name="priority" required>
                                        <option value="">Öncelik Seçin</option>
                                        <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="yüksek" {{ old('priority') == 'yüksek' ? 'selected' : '' }}>Yüksek</option>
                                        <option value="acil" {{ old('priority') == 'acil' ? 'selected' : '' }}>Acil</option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reported_date" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Bildirim Tarihi <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control @error('reported_date') is-invalid @enderror" 
                                           id="reported_date" name="reported_date" 
                                           value="{{ old('reported_date', now()->format('Y-m-d\TH:i')) }}" required>
                                    @error('reported_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>Açıklama <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Arıza/bakım durumunu detaylı olarak açıklayın..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">
                                <i class="fas fa-camera me-1"></i>Fotoğraf (Opsiyonel)
                            </label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                   id="photo" name="photo" accept="image/*">
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maksimum 2MB, JPG/PNG formatında</div>
                        </div>

                        <div class="form-actions mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Bildirimi Gönder
                            </button>
                            <a href="{{ route('admin.fault') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Geri Dön
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Bilgilendirme</h6>
                </div>
                <div class="card-body">
                    <h6>Bildirim Türleri:</h6>
                    <ul class="list-unstyled">
                        <li><span class="badge bg-danger me-2">Arıza</span> Ekipman çalışmıyor</li>
                        <li><span class="badge bg-warning me-2">Bakım</span> Periyodik bakım gerekli</li>
                        <li><span class="badge bg-secondary me-2">Diğer</span> Diğer durumlar</li>
                    </ul>
                    
                    <h6 class="mt-3">Öncelik Seviyeleri:</h6>
                    <ul class="list-unstyled">
                        <li><span class="badge bg-success me-2">Normal</span> Rutin işlemler</li>
                        <li><span class="badge bg-warning me-2">Yüksek</span> Acil müdahale gerekli</li>
                        <li><span class="badge bg-danger me-2">Acil</span> Kritik durum</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('faultForm');
    
    if(form) {
        form.addEventListener('submit', function(e) {
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Gönderiliyor...';
            submitBtn.disabled = true;
            
            // Let the form submit naturally to the server
        });
    }
});
</script>
@endsection
