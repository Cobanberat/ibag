@extends('layouts.admin')


@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center">
                            <img src="{{ asset('images/ibag-logo.svg') }}" alt="İBAG Logo" style="width: 20px; height: 20px; margin-right: 6px;">
                            <i class="fa fa-home me-1"></i> 
                            <span class="d-none d-sm-inline">Ana Sayfa</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="/admin/" class="text-decoration-none d-none d-md-inline">Yönetim</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-undo me-1"></i>
                        <span class="d-none d-sm-inline">Teslim İşlemleri</span>
                        <span class="d-sm-none">Teslim</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="h3 mb-2 text-primary">
                                <i class="fas fa-boxes me-2"></i>
                                Zimmet Yönetimi
                            </h1>
                            <p class="text-muted mb-0">Aktif ve geçmiş zimmetleri yönetin</p>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end">
                                <div class="bg-primary bg-opacity-10 px-3 py-2 rounded-pill text-center">
                                    <small class="text-primary fw-bold">
                                        <i class="fas fa-clock me-1"></i>
                                        <span class="d-none d-sm-inline">Aktif: </span>{{ $assignments->where('status', 0)->count() }}
                                    </small>
                                </div>
                                <div class="bg-success bg-opacity-10 px-3 py-2 rounded-pill text-center">
                                    <small class="text-success fw-bold">
                                        <i class="fas fa-check me-1"></i>
                                        <span class="d-none d-sm-inline">Teslim: </span>{{ $assignments->where('status', 1)->count() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtreler -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-filter text-primary me-2"></i>
                        <h6 class="mb-0 fw-bold text-dark">Filtreler</h6>
                    </div>
                    <form id="filterForm" class="row g-3">
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="fas fa-search me-1 text-primary"></i>Arama
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="searchInput" placeholder="Zimmet ID, not veya ekipman ara...">
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <label class="form-label fw-bold text-dark">
                                <i class="fas fa-filter me-1 text-primary"></i>Durum
                            </label>
                            <select class="form-select" id="statusFilter">
                                <option value="">Tüm Durumlar</option>
                                <option value="0">Aktif</option>
                                <option value="1">Teslim Edildi</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <label class="form-label fw-bold text-dark">
                                <i class="fas fa-calendar me-1 text-primary"></i>Başlangıç
                            </label>
                            <input type="date" class="form-control" id="dateFrom">
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <label class="form-label fw-bold text-dark">
                                <i class="fas fa-calendar me-1 text-primary"></i>Bitiş
                            </label>
                            <input type="date" class="form-control" id="dateTo">
                        </div>
                        <div class="col-6 col-md-3 col-lg-3 d-flex flex-column flex-sm-row align-items-end gap-2">
                            <button type="button" class="btn btn-primary px-3 px-sm-4 w-100 w-sm-auto" id="filterBtn">
                                <i class="fas fa-filter me-1"></i>
                                <span class="d-none d-sm-inline">Filtrele</span>
                                <span class="d-sm-none">Filtre</span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary px-3 px-sm-4 w-100 w-sm-auto" id="clearBtn">
                                <i class="fas fa-times me-1"></i>
                                <span class="d-none d-sm-inline">Temizle</span>
                                <span class="d-sm-none">Temizle</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <ul class="nav nav-pills nav-fill" id="assignmentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active d-flex align-items-center justify-content-center py-2 py-sm-3 position-relative" id="active-tab" data-bs-toggle="pill" data-bs-target="#active" type="button" role="tab" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; z-index: 2;">
                                <i class="fas fa-clock me-1 me-sm-2"></i>
                                <span class="d-none d-sm-inline">Aktif Zimmetler</span>
                                <span class="d-sm-none">Aktif</span>
                                <span class="badge bg-white text-primary ms-1 ms-sm-2" id="activeCount">{{ $assignments->where('status', 0)->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center justify-content-center py-2 py-sm-3 position-relative" id="history-tab" data-bs-toggle="pill" data-bs-target="#history" type="button" role="tab" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none; z-index: 1;">
                                <i class="fas fa-history me-1 me-sm-2"></i>
                                <span class="d-none d-sm-inline">Geçmiş Zimmetler</span>
                                <span class="d-sm-none">Geçmiş</span>
                                <span class="badge bg-white text-danger ms-1 ms-sm-2" id="historyCount">{{ $assignments->where('status', 1)->count() }}</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="assignmentTabContent">
        <!-- Aktif Zimmetler -->
        <div class="tab-pane fade show active" id="active" role="tabpanel">
            <div class="row" id="activeAssignments">
                @forelse($assignments->where('status', 0) as $assignment)
                    <div class="col-12 col-sm-6 col-lg-4 mb-4" data-id="{{ $assignment->id }}" data-note="{{ $assignment->note }}" data-date="{{ $assignment->created_at->format('Y-m-d') }}">
                        <div class="card h-100 border-0 shadow-lg assignment-card" style="border-radius: 20px; overflow: hidden;">
                            <div class="card-header text-white d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1rem 1.5rem;">
                                <div class="mb-2 mb-sm-0">
                                    <h6 class="mb-1 fw-bold fs-5">Zimmet #{{ $assignment->id }}</h6>
                                    <small class="opacity-90">{{ $assignment->created_at ? $assignment->created_at->format('d.m.Y H:i') : '-' }}</small>
                                </div>
                                <div class="btn-group d-flex w-100 w-sm-auto" role="group">
                                    <button class="btn btn-sm btn-light text-primary rounded-pill px-2 px-sm-3 flex-fill" onclick="openDetailModal({{ $assignment->id }})" title="Detay Görüntüle" style="border-radius: 20px !important;">
                                        <i class="fas fa-eye me-1"></i>
                                        <span class="d-none d-sm-inline">Detay</span>
                                    </button>
                                    <button class="btn btn-sm btn-success rounded-pill px-2 px-sm-3 flex-fill" onclick="openReturnModal({{ $assignment->id }})" title="Teslim Et" style="border-radius: 20px !important;">
                                        <i class="fas fa-undo me-1"></i>
                                        <span class="d-none d-sm-inline">Teslim</span>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column" style="padding: 1.5rem; background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
                                <div class="mb-3">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                            <i class="fas fa-sticky-note text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 text-dark fw-bold">Not</h6>
                                            <p class="mb-0 text-muted small">
                                                {{ $assignment->note ? Str::limit($assignment->note, 100) : 'Not bulunmuyor' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info bg-opacity-10 p-2 rounded-circle me-2">
                                                <i class="fas fa-cubes text-info"></i>
                                            </div>
                                            <span class="text-dark fw-bold">Ekipman Sayısı</span>
                                        </div>
                                        <span class="badge bg-info fs-6 px-3 py-2">{{ $assignment->items->count() }}</span>
                                    </div>
                                </div>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-warning px-3 py-2 rounded-pill">
                                            <i class="fas fa-clock me-1"></i>Aktif
                                        </span>
                                        <small class="text-muted fw-bold">
                                            {{ $assignment->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aktif zimmet bulunamadı</h5>
                            <p class="text-muted">Henüz aktif zimmet bulunmuyor.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Geçmiş Zimmetler -->
        <div class="tab-pane fade" id="history" role="tabpanel">
            <div class="row" id="historyAssignments">
                @forelse($assignments->where('status', 1) as $assignment)
                    <div class="col-12 col-sm-6 col-lg-4 mb-4" data-id="{{ $assignment->id }}" data-note="{{ $assignment->note }}" data-date="{{ $assignment->updated_at->format('Y-m-d') }}">
                        <div class="card h-100 border-0 shadow-lg assignment-card" style="border-radius: 20px; overflow: hidden;">
                            <div class="card-header text-white d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 1rem 1.5rem;">
                                <div class="mb-2 mb-sm-0">
                                    <h6 class="mb-1 fw-bold fs-5">Zimmet #{{ $assignment->id }}</h6>
                                    <small class="opacity-90">{{ $assignment->updated_at ? $assignment->updated_at->format('d.m.Y H:i') : '-' }}</small>
                                </div>
                                <button class="btn btn-sm btn-light text-danger rounded-pill px-2 px-sm-3 w-100 w-sm-auto" onclick="openDetailModal({{ $assignment->id }})" title="Detay Görüntüle" style="border-radius: 20px !important;">
                                    <i class="fas fa-eye me-1"></i>
                                    <span class="d-none d-sm-inline">Detay</span>
                                </button>
                            </div>
                            <div class="card-body d-flex flex-column" style="padding: 1.5rem; background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
                                <div class="mb-3">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                            <i class="fas fa-sticky-note text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 text-dark fw-bold">Not</h6>
                                            <p class="mb-0 text-muted small">
                                                {{ $assignment->note ? Str::limit($assignment->note, 100) : 'Not bulunmuyor' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info bg-opacity-10 p-2 rounded-circle me-2">
                                                <i class="fas fa-cubes text-info"></i>
                                            </div>
                                            <span class="text-dark fw-bold">Ekipman Sayısı</span>
                                        </div>
                                        <span class="badge bg-info fs-6 px-3 py-2">{{ $assignment->items->count() }}</span>
                                    </div>
                                </div>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-success px-3 py-2 rounded-pill">
                                            <i class="fas fa-check me-1"></i>Teslim Edildi
                                        </span>
                                        <small class="text-muted fw-bold">
                                            {{ $assignment->updated_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Geçmiş zimmet bulunamadı</h5>
                            <p class="text-muted">Henüz teslim edilmiş zimmet bulunmuyor.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Detay Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="detailModalLabel">
                    <i class="fas fa-eye me-2"></i>Zimmet Detayları
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Yükleniyor...</span>
                    </div>
                    <p class="mt-2">Detaylar yükleniyor...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Teslim Modal -->
<div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success text-white">
                <h5 class="modal-title" id="returnModalLabel">
                    <i class="fas fa-undo me-2"></i>Zimmet Teslim Et
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="returnForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Bilgi:</strong> Teslim fotoğrafı yüklemek opsiyoneldir. Kullanılan miktarı belirtmeyi unutmayın.
                    </div>
                    
                    <!-- Bu kısım JavaScript ile doldurulacak -->
                    <div id="equipmentItems">
                        <!-- Equipment items buraya eklenecek -->
                    </div>
                    
                    <div class="mb-3">
                        <label for="damageNote" class="form-label fw-bold">
                            <i class="fas fa-sticky-note me-1"></i>Arıza/Hasar Notu (Opsiyonel)
                        </label>
                        <textarea class="form-control" id="damageNote" name="damage_note" rows="3" placeholder="Arıza veya hasar durumu hakkında notlar..."></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>İptal
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-1"></i>Teslim Et
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Global Styles */
.text-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Card Styles */
.assignment-card {
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    border-radius: 20px;
    overflow: hidden;
    position: relative;
}

.assignment-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1;
}

.assignment-card:hover::before {
    opacity: 1;
}

.assignment-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
}

.assignment-card .card-header {
    position: relative;
    z-index: 2;
}

.assignment-card .card-body {
    position: relative;
    z-index: 2;
}

/* Tab Styles */
.nav-pills .nav-link {
    transition: all 0.3s ease;
    border-radius: 0;
    position: relative;
    overflow: hidden;
    border: 2px solid transparent;
}

.nav-pills .nav-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
    z-index: 1;
}

.nav-pills .nav-link:hover::before {
    left: 100%;
}

.nav-pills .nav-link.active {
    transform: scale(1.02);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    border-color: rgba(255,255,255,0.3);
    z-index: 3;
}

.nav-pills .nav-link:not(.active) {
    opacity: 0.8;
    transform: scale(0.98);
}

.nav-pills .nav-link:not(.active):hover {
    opacity: 1;
    transform: scale(1);
}

/* Tab Content Transition */
.tab-content {
    position: relative;
}

.tab-pane {
    transition: all 0.3s ease;
}

.tab-pane.fade:not(.show) {
    opacity: 0;
    transform: translateX(20px);
}

.tab-pane.fade.show {
    opacity: 1;
    transform: translateX(0);
}

/* Button Styles */
.btn {
    transition: all 0.3s ease;
    font-weight: 600;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.btn-group .btn {
    margin: 0 2px;
}

/* Badge Styles */
.badge {
    font-weight: 600;
    letter-spacing: 0.5px;
}

/* Input Group Styles */
.input-group-text {
    border-right: none;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Modal Styles */
.modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}

.modal-header {
    border-radius: 20px 20px 0 0;
    border-bottom: none;
}

/* Modal Close Button */
.modal-header .btn-close {
    opacity: 1;
    transition: all 0.3s ease;
    background: none;
    border: none;
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-header .btn-close:hover {
    opacity: 0.8;
    transform: scale(1.1);
}

.modal-header .btn-close::before {
    content: '×';
    font-size: 18px;
    font-weight: bold;
    color: white;
    line-height: 1;
}

/* Fotoğraf paneli animasyonu */
[id^="photos-"] {
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    overflow: hidden;
    border-radius: 15px;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border: 2px solid #e9ecef;
    display: none;
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
}

[id^="photos-"].show {
    display: block !important;
    opacity: 1 !important;
    transform: translateY(0) scale(1) !important;
}

.btn-toggle-photos {
    transition: all 0.3s ease;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.btn-toggle-photos:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

/* Empty State */
.text-center.py-5 {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 20px;
    margin: 2rem 0;
}

/* Responsive Breadcrumb */
.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #6c757d;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding: 0 15px;
    }
    
    .assignment-card:hover {
        transform: translateY(-4px) scale(1.01);
    }
    
    .nav-pills .nav-link {
        font-size: 0.9rem;
        padding: 0.75rem;
    }
    
    .btn-group .btn {
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
    }
    
    .card-body {
        padding: 1rem !important;
    }
    
    .card-header {
        padding: 0.75rem 1rem !important;
    }
    
    .h3 {
        font-size: 1.25rem;
    }
    
    .text-end {
        text-align: left !important;
        margin-top: 1rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .btn {
        font-size: 0.875rem;
    }
    
    .form-label {
        font-size: 0.875rem;
    }
    
    .input-group-text {
        font-size: 0.875rem;
    }
}

@media (max-width: 576px) {
    .assignment-card {
        margin-bottom: 1rem;
    }
    
    .assignment-card .card-header {
        padding: 0.75rem !important;
    }
    
    .assignment-card .card-body {
        padding: 0.75rem !important;
    }
    
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        margin: 0.25rem 0;
        width: 100%;
    }
    
    .nav-pills .nav-link {
        font-size: 0.8rem;
        padding: 0.5rem;
    }
    
    .badge {
        font-size: 0.7rem;
    }
    
    .fs-5 {
        font-size: 1rem !important;
    }
}

/* Tablet Optimizations */
@media (min-width: 768px) and (max-width: 1024px) {
    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }
    
    .col-lg-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }
}

/* Animation Keyframes */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.assignment-card {
    animation: fadeInUp 0.6s ease-out;
}

/* Loading Animation */
.spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}

/* Form Validation Styles */
.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #dc3545;
}

.form-control.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.form-select.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}
</style>

<script>
// Global değişkenler
let assignments = @json($assignments);

// Toast bildirim fonksiyonu
function showToast(message, type = 'info') {
    // Toast container oluştur (eğer yoksa)
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    // Toast ID oluştur
    const toastId = 'toast-' + Date.now();
    
    // Toast type'a göre class'ları belirle
    let bgClass, iconClass;
    switch(type) {
        case 'success':
            bgClass = 'bg-success';
            iconClass = 'fas fa-check-circle';
            break;
        case 'error':
            bgClass = 'bg-danger';
            iconClass = 'fas fa-exclamation-circle';
            break;
        case 'warning':
            bgClass = 'bg-warning';
            iconClass = 'fas fa-exclamation-triangle';
            break;
        default:
            bgClass = 'bg-info';
            iconClass = 'fas fa-info-circle';
    }

    // Toast HTML oluştur
    const toastHTML = `
        <div id="${toastId}" class="toast ${bgClass} text-white" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header ${bgClass} text-white border-0">
                <i class="${iconClass} me-2"></i>
                <strong class="me-auto">${type === 'success' ? 'Başarılı' : type === 'error' ? 'Hata' : type === 'warning' ? 'Uyarı' : 'Bilgi'}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;

    // Toast'u container'a ekle
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);

    // Toast'u göster
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: type === 'success' ? 3000 : 5000
    });
    
    toast.show();

    // Toast kapandıktan sonra DOM'dan kaldır
    toastElement.addEventListener('hidden.bs.toast', function() {
        toastElement.remove();
    });
}

// Modal açma fonksiyonları
function openDetailModal(assignmentId) {
    console.log('Detay modal açılıyor:', assignmentId);
    
    const assignment = assignments.find(a => a.id == assignmentId);
    if (!assignment) {
        console.error('Assignment bulunamadı:', assignmentId);
        alert('Zimmet bulunamadı!');
        return;
    }
    
    const modalBody = document.getElementById('detailModalBody');
    if (!modalBody) {
        console.error('Modal body bulunamadı');
        return;
    }
    
    const isHistory = assignment.status == 1;
    
    let html = `
        <div class="row mb-3">
            <div class="col-md-6">
                <p><strong>Oluşturulma Tarihi:</strong> ${assignment.created_at ? new Date(assignment.created_at).toLocaleString('tr-TR') : '-'}</p>
                ${isHistory ? `<p><strong>Teslim Tarihi:</strong> ${assignment.updated_at ? new Date(assignment.updated_at).toLocaleString('tr-TR') : '-'}</p>` : ''}
                <p><strong>Not:</strong> ${assignment.note || 'Not bulunmuyor'}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Toplam Ekipman:</strong> ${assignment.items ? assignment.items.length : 0}</p>
                <p><strong>Durum:</strong> <span class="badge ${isHistory ? 'bg-success' : 'bg-warning'}">${isHistory ? 'Teslim Edildi' : 'Aktif'}</span></p>
                ${assignment.damage_note ? `<p><strong>Arıza Notu:</strong> ${assignment.damage_note}</p>` : ''}
            </div>
        </div>
        <hr>
        <h6 class="mb-3"><i class="fas fa-cubes me-2"></i>Ekipman Listesi</h6>
        <div class="row g-3">
    `;
    
    if (assignment.items && assignment.items.length > 0) {
        assignment.items.forEach(item => {
            html += `
                <div class="col-md-4 text-center">
                    <div class="border rounded p-2 h-100 d-flex flex-column justify-content-between">
                        <div class="small mb-2">
                            <strong class="d-block">${item.equipment?.name || 'Bilinmiyor'}</strong>
                            ${item.equipment?.individual_tracking == 1 || item.equipment?.individual_tracking === true || item.equipment?.individual_tracking === '1' ? 
                                `<span class="badge bg-info">Ayrı Takip</span><br><small class="text-muted">Kod: ${item.equipment_stock?.code || item.code || '-'}</small>` :
                                `<span class="badge bg-secondary">Toplu Takip</span><br><small class="text-muted">Miktar: ${item.quantity || 0}</small>`
                            }
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-outline-primary btn-sm btn-toggle-photos" 
                                data-target="#photos-${assignmentId}-${item.id}">
                                <i class="fas fa-images me-1"></i>Fotoğrafları Gör
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div id="photos-${assignmentId}-${item.id}" class="p-2 border rounded">
                        <div class="row g-3">
                            <div class="col-md-6 text-center">
                                <h6 class="fw-bold mb-2"><i class="fas fa-download me-1"></i>Alınırken</h6>
                                ${item.photo_path ? 
                                    `<img src="/storage/${item.photo_path}" alt="Alınırken Fotoğraf" class="img-fluid rounded border" style="max-height: 300px; object-fit: contain;">` :
                                    `<div class="alert alert-warning small mb-0">Alınırken fotoğraf bulunmuyor.</div>`
                                }
                            </div>
                            <div class="col-md-6 text-center">
                                <h6 class="fw-bold mb-2"><i class="fas fa-upload me-1"></i>Teslim Ederken</h6>
                                ${item.return_photo_path ? 
                                    `<img src="/storage/${item.return_photo_path}" alt="Teslim Fotoğrafı" class="img-fluid rounded border" style="max-height: 300px; object-fit: contain;">` :
                                    `<div class="alert alert-warning small mb-0">Teslim fotoğrafı bulunmuyor.</div>`
                                }
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
    } else {
        html += '<div class="col-12"><p class="text-muted">Ekipman bulunamadı.</p></div>';
    }
    
    html += '</div>';
    modalBody.innerHTML = html;
    
    const modal = document.getElementById('detailModal');
    if (modal) {
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }
}

function openReturnModal(assignmentId) {
    console.log('Teslim modal açılıyor:', assignmentId);
    
    const assignment = assignments.find(a => a.id == assignmentId);
    if (!assignment) {
        console.error('Assignment bulunamadı:', assignmentId);
        alert('Zimmet bulunamadı!');
        return;
    }
    
    const equipmentItems = document.getElementById('equipmentItems');
    if (!equipmentItems) {
        console.error('Equipment items container bulunamadı');
        return;
    }
    
    // Form action'ını ayarla
    const form = document.getElementById('returnForm');
    if (form) {
        form.action = `/admin/teslim-et/${assignmentId}`;
    }
    
    let html = '<div class="row g-3">';
    
    if (assignment.items && assignment.items.length > 0) {
        assignment.items.forEach(item => {
            html += `
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-cube me-2"></i>
                                ${item.equipment?.name || 'Bilinmiyor'}
                                <span class="badge ${item.equipment?.individual_tracking == 1 || item.equipment?.individual_tracking === true || item.equipment?.individual_tracking === '1' ? 'bg-info' : 'bg-secondary'} ms-2">
                                    ${item.equipment?.individual_tracking == 1 || item.equipment?.individual_tracking === true || item.equipment?.individual_tracking === '1' ? 'Ayrı Takip' : 'Toplu Takip'}
                                </span>
                            </h6>
                        </div>
                        <div class="card-body">
                            ${item.photo_path ? `
                                <div class="text-center mb-3">
                                    <img src="/storage/${item.photo_path}" alt="Orijinal Ekipman" class="img-fluid rounded border" style="max-height: 100px;">
                                    <small class="d-block text-muted mt-1">Orijinal Ekipman</small>
                                </div>
                            ` : ''}
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-camera me-1"></i>Teslim Fotoğrafı:
                                </label>
                                <input type="file" name="return_photos[${item.id}]" class="form-control" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Desteklenen formatlar: JPG, PNG, GIF, WebP | Maksimum boyut: 5MB
                                </small>
                            </div>
                            
                            <div class="alert alert-warning mb-3">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>Aldığınız Miktar:</strong> ${item.quantity || 0} adet
                                </small>
                            </div>
                            
                            ${!(item.equipment?.individual_tracking == 1 || item.equipment?.individual_tracking === true || item.equipment?.individual_tracking === '1') ? `
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-exclamation-triangle me-1 text-warning"></i>Kullanılan/Kayıp Miktar:
                                    </label>
                                    <input type="number" name="used_qty[${item.id}]" class="form-control" min="0" max="${item.quantity}" value="0" required>
                                    <small class="text-muted">
                                        <strong>0:</strong> Hiç kullanılmadı (tamamı geri dönüyor)<br>
                                        <strong>${item.quantity}:</strong> Tamamı kullanıldı/kayboldu<br>
                                        <strong>Geri dönen miktar:</strong> ${item.quantity} - kullanılan miktar
                                    </small>
                                </div>
                            ` : `
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-exclamation-triangle me-1 text-warning"></i>Ekipman Durumu:
                                    </label>
                                    <select name="used_qty[${item.id}]" class="form-select" required>
                                        <option value="0">Sağlam - Geri Dönüyor</option>
                                        <option value="1">Hasarlı/Kayıp - Geri Dönmüyor</option>
                                    </select>
                                    <small class="text-muted">Ekipmanın mevcut durumunu seçin</small>
                                </div>
                            `}
                        </div>
                    </div>
                </div>
            `;
        });
    } else {
        html += '<div class="col-12"><p class="text-muted">Ekipman bulunamadı.</p></div>';
    }
    
    html += '</div>';
    
    equipmentItems.innerHTML = html;
    
    const modal = document.getElementById('returnModal');
    if (modal) {
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
        
        // Form submit event listener ekle
        const form = document.getElementById('returnForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Form validasyonu
                let isValid = true;
                let errorMessage = '';
                
                // Kullanılan miktar kontrolü
                const usedQtyInputs = form.querySelectorAll('input[name^="used_qty"]');
                usedQtyInputs.forEach(input => {
                    const value = parseInt(input.value);
                    const max = parseInt(input.getAttribute('max'));
                    if (isNaN(value) || value < 0 || value > max) {
                        isValid = false;
                        errorMessage = 'Lütfen geçerli miktar girin!';
                        input.classList.add('is-invalid');
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });
                
                // Fotoğraf formatı kontrolü
                const photoInputs = form.querySelectorAll('input[type="file"]');
                photoInputs.forEach(input => {
                    if (input.files.length > 0) {
                        const file = input.files[0];
                        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                        const maxSize = 5 * 1024 * 1024; // 5MB
                        
                        if (!allowedTypes.includes(file.type)) {
                            isValid = false;
                            errorMessage = `Desteklenmeyen dosya formatı: ${file.name}`;
                            input.classList.add('is-invalid');
                        } else if (file.size > maxSize) {
                            isValid = false;
                            errorMessage = `Dosya boyutu çok büyük: ${file.name}`;
                            input.classList.add('is-invalid');
                        } else {
                            input.classList.remove('is-invalid');
                        }
                    }
                });
                
                if (!isValid) {
                    showToast(errorMessage, 'error');
                    return;
                }
                
                // FormData'yı form'dan direkt oluştur
                const formData = new FormData(this);
                
                // CSRF token ve method ekle
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                formData.append('_method', 'PUT');
                
                // Debug için form data'yı kontrol et
                console.log('Form Data:');
                for (let [key, value] of formData.entries()) {
                    console.log(key, value);
                }
                
                fetch(this.action, {
                    method: 'POST', // Laravel'de PUT request için POST kullanıyoruz
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Teslim işlemi başarılı!', 'success');
                        // Modal'ı kapat ve sayfayı yenile
                        const modal = bootstrap.Modal.getInstance(document.getElementById('returnModal'));
                        modal.hide();
                        setTimeout(() => {
                        window.location.href = window.location.pathname + '?success=1';
                        }, 1000);
                    } else {
                        showToast(data.message || 'Bilinmeyen hata oluştu', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Bir hata oluştu: ' + error.message, 'error');
                });
            });
        }
    }
}

// Filtreleme fonksiyonları
function filterAssignments() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    
    const activeCards = document.querySelectorAll('#activeAssignments .col-12');
    const historyCards = document.querySelectorAll('#historyAssignments .col-12');
    
    let activeCount = 0;
    let historyCount = 0;
    
    // Aktif zimmetleri filtrele
    activeCards.forEach(card => {
        const id = card.getAttribute('data-id');
        const note = card.getAttribute('data-note').toLowerCase();
        const date = card.getAttribute('data-date');
        
        let show = true;
        
        if (searchTerm && !id.includes(searchTerm) && !note.includes(searchTerm)) {
            show = false;
        }
        
        if (statusFilter && statusFilter !== '0') {
            show = false;
        }
        
        if (dateFrom && date < dateFrom) {
            show = false;
        }
        
        if (dateTo && date > dateTo) {
            show = false;
        }
        
        if (show) {
            card.style.display = 'block';
            activeCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Geçmiş zimmetleri filtrele
    historyCards.forEach(card => {
        const id = card.getAttribute('data-id');
        const note = card.getAttribute('data-note').toLowerCase();
        const date = card.getAttribute('data-date');
        
        let show = true;
        
        if (searchTerm && !id.includes(searchTerm) && !note.includes(searchTerm)) {
            show = false;
        }
        
        if (statusFilter && statusFilter !== '1') {
            show = false;
        }
        
        if (dateFrom && date < dateFrom) {
            show = false;
        }
        
        if (dateTo && date > dateTo) {
            show = false;
        }
        
        if (show) {
            card.style.display = 'block';
            historyCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Sayıları güncelle
    document.getElementById('activeCount').textContent = activeCount;
    document.getElementById('historyCount').textContent = historyCount;
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('dateFrom').value = '';
    document.getElementById('dateTo').value = '';
    
    // Tüm kartları göster
    document.querySelectorAll('#activeAssignments .col-12, #historyAssignments .col-12').forEach(card => {
        card.style.display = 'block';
    });
    
    // Sayıları sıfırla
    document.getElementById('activeCount').textContent = {{ $assignments->where('status', 0)->count() }};
    document.getElementById('historyCount').textContent = {{ $assignments->where('status', 1)->count() }};
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // URL parametresi kontrol et
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === '1') {
        showToast('Teslim işlemi başarılı!', 'success');
        // URL'den parametreyi temizle
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    
    // Filtre butonları
    document.getElementById('filterBtn').addEventListener('click', filterAssignments);
    document.getElementById('clearBtn').addEventListener('click', clearFilters);
    
    // Enter tuşu ile filtreleme
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            filterAssignments();
        }
    });
    
    // Tab geçişleri için event listeners
    const activeTab = document.getElementById('active-tab');
    const historyTab = document.getElementById('history-tab');
    
    if (activeTab) {
        activeTab.addEventListener('click', function() {
            // Aktif tab'a geçiş
            this.classList.add('active');
            this.style.zIndex = '3';
            historyTab.classList.remove('active');
            historyTab.style.zIndex = '1';
            
            // İçerik geçişi
            const activeContent = document.getElementById('active');
            const historyContent = document.getElementById('history');
            
            if (activeContent && historyContent) {
                activeContent.classList.add('show', 'active');
                historyContent.classList.remove('show', 'active');
            }
        });
    }
    
    if (historyTab) {
        historyTab.addEventListener('click', function() {
            // Geçmiş tab'a geçiş
            this.classList.add('active');
            this.style.zIndex = '3';
            activeTab.classList.remove('active');
            activeTab.style.zIndex = '1';
            
            // İçerik geçişi
            const activeContent = document.getElementById('active');
            const historyContent = document.getElementById('history');
            
            if (activeContent && historyContent) {
                historyContent.classList.add('show', 'active');
                activeContent.classList.remove('show', 'active');
            }
        });
    }
    
    // Fotoğraf butonları için event delegation
    document.addEventListener('click', function(e) {
        const button = e.target.closest('.btn-toggle-photos');
        if (!button) return;
        
        e.preventDefault();
        e.stopPropagation();
        
        const targetId = button.getAttribute('data-target');
        if (!targetId) return;
        
        const panel = document.querySelector(targetId);
        if (!panel) return;
        
        const isVisible = panel.classList.contains('show');
        
        if (isVisible) {
            panel.classList.remove('show');
            button.innerHTML = '<i class="fas fa-images me-1"></i>Fotoğrafları Gör';
            button.classList.remove('btn-outline-danger');
            button.classList.add('btn-outline-primary');
        } else {
            panel.classList.add('show');
            button.innerHTML = '<i class="fas fa-eye-slash me-1"></i>Fotoğrafları Gizle';
            button.classList.remove('btn-outline-primary');
            button.classList.add('btn-outline-danger');
        }
    });
});
</script>
@endsection