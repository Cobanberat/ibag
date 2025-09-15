@extends('layouts.admin')


@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a></li>
            <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
            <li class="breadcrumb-item active" aria-current="page">Teslim İşlemleri</li>
        </ol>
    </nav>
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h3 mb-0 text-gradient">
                        <i class="fas fa-boxes me-2"></i>Zimmet Yönetimi
                    </h2>
                    <p class="text-muted mb-0">Aktif ve geçmiş zimmetleri yönetin</p>
                </div>
                <div class="d-flex gap-2">
                    <div class="bg-primary bg-opacity-10 px-3 py-2 rounded-pill">
                        <small class="text-primary fw-bold">
                            <i class="fas fa-clock me-1"></i>Aktif: {{ $assignments->where('status', 0)->count() }}
                        </small>
                    </div>
                    <div class="bg-success bg-opacity-10 px-3 py-2 rounded-pill">
                        <small class="text-success fw-bold">
                            <i class="fas fa-check me-1"></i>Teslim: {{ $assignments->where('status', 1)->count() }}
                        </small>
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
                        <div class="col-md-3">
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
                        <div class="col-md-2">
                            <label class="form-label fw-bold text-dark">
                                <i class="fas fa-filter me-1 text-primary"></i>Durum
                            </label>
                            <select class="form-select" id="statusFilter">
                                <option value="">Tüm Durumlar</option>
                                <option value="0">Aktif</option>
                                <option value="1">Teslim Edildi</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold text-dark">
                                <i class="fas fa-calendar me-1 text-primary"></i>Başlangıç
                            </label>
                            <input type="date" class="form-control" id="dateFrom">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold text-dark">
                                <i class="fas fa-calendar me-1 text-primary"></i>Bitiş
                            </label>
                            <input type="date" class="form-control" id="dateTo">
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="button" class="btn btn-primary px-4" id="filterBtn">
                                <i class="fas fa-filter me-1"></i>Filtrele
                            </button>
                            <button type="button" class="btn btn-outline-secondary px-4" id="clearBtn">
                                <i class="fas fa-times me-1"></i>Temizle
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
                            <button class="nav-link active d-flex align-items-center justify-content-center py-3" id="active-tab" data-bs-toggle="pill" data-bs-target="#active" type="button" role="tab" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                                <i class="fas fa-clock me-2"></i>
                                <span>Aktif Zimmetler</span>
                                <span class="badge bg-white text-primary ms-2" id="activeCount">{{ $assignments->where('status', 0)->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center justify-content-center py-3" id="history-tab" data-bs-toggle="pill" data-bs-target="#history" type="button" role="tab" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none;">
                                <i class="fas fa-history me-2"></i>
                                <span>Geçmiş Zimmetler</span>
                                <span class="badge bg-white text-danger ms-2" id="historyCount">{{ $assignments->where('status', 1)->count() }}</span>
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
                    <div class="col-lg-4 col-md-6 mb-4" data-id="{{ $assignment->id }}" data-note="{{ $assignment->note }}" data-date="{{ $assignment->created_at->format('Y-m-d') }}">
                        <div class="card h-100 border-0 shadow-lg assignment-card" style="border-radius: 20px; overflow: hidden;">
                            <div class="card-header text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.5rem;">
                                <div>
                                    <h6 class="mb-1 fw-bold fs-5">Zimmet #{{ $assignment->id }}</h6>
                                    <small class="opacity-90">{{ $assignment->created_at ? $assignment->created_at->format('d.m.Y H:i') : '-' }}</small>
                                </div>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-light text-primary rounded-pill px-3" onclick="openDetailModal({{ $assignment->id }})" title="Detay Görüntüle" style="border-radius: 20px !important;">
                                        <i class="fas fa-eye me-1"></i>Detay
                                    </button>
                                    <button class="btn btn-sm btn-success rounded-pill px-3" onclick="openReturnModal({{ $assignment->id }})" title="Teslim Et" style="border-radius: 20px !important;">
                                        <i class="fas fa-undo me-1"></i>Teslim
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
                    <div class="col-lg-4 col-md-6 mb-4" data-id="{{ $assignment->id }}" data-note="{{ $assignment->note }}" data-date="{{ $assignment->updated_at->format('Y-m-d') }}">
                        <div class="card h-100 border-0 shadow-lg assignment-card" style="border-radius: 20px; overflow: hidden;">
                            <div class="card-header text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 1.5rem;">
                                <div>
                                    <h6 class="mb-1 fw-bold fs-5">Zimmet #{{ $assignment->id }}</h6>
                                    <small class="opacity-90">{{ $assignment->updated_at ? $assignment->updated_at->format('d.m.Y H:i') : '-' }}</small>
                                </div>
                                <button class="btn btn-sm btn-light text-danger rounded-pill px-3" onclick="openDetailModal({{ $assignment->id }})" title="Detay Görüntüle" style="border-radius: 20px !important;">
                                    <i class="fas fa-eye me-1"></i>Detay
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
            <div class="modal-body" id="returnModalBody">
                <div class="text-center">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Yükleniyor...</span>
                    </div>
                    <p class="mt-2">Form yükleniyor...</p>
                </div>
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
}

.nav-pills .nav-link:hover::before {
    left: 100%;
}

.nav-pills .nav-link.active {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
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

/* Responsive */
@media (max-width: 768px) {
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
</style>

<script>
// Global değişkenler
let assignments = @json($assignments);

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
                            ${item.equipment?.individual_tracking ? 
                                `<span class="badge bg-info">Ayrı Takip</span><br><small class="text-muted">Kod: ${item.code || '-'}</small>` :
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
    
    const modalBody = document.getElementById('returnModalBody');
    if (!modalBody) {
        console.error('Return modal body bulunamadı');
        return;
    }
    
    let html = `
        <form action="/admin/teslim-al/${assignmentId}" method="POST" enctype="multipart/form-data" id="returnForm${assignmentId}">
            @csrf
            @method('PUT')
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Bilgi:</strong> Her ekipman için teslim fotoğrafı yüklemek zorunludur.
            </div>
            <div class="row g-3">
    `;
    
    if (assignment.items && assignment.items.length > 0) {
        assignment.items.forEach(item => {
            html += `
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-cube me-2"></i>
                                ${item.equipment?.name || 'Bilinmiyor'}
                                <span class="badge ${item.equipment?.individual_tracking ? 'bg-info' : 'bg-secondary'} ms-2">
                                    ${item.equipment?.individual_tracking ? 'Ayrı Takip' : 'Toplu Takip'}
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
                                <input type="file" name="return_photos[${item.id}]" class="form-control" accept="image/*" required>
                                <small class="text-muted">Teslim edilen ekipmanın fotoğrafını çekin</small>
                            </div>
                            
                            <div class="alert alert-warning mb-3">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>Aldığınız Miktar:</strong> ${item.quantity || 0} adet
                                </small>
                            </div>
                            
                            ${!item.equipment?.individual_tracking ? `
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
    
    html += `
            </div>
            
            <div class="mb-3 mt-4">
                <label class="form-label fw-bold">
                    <i class="fas fa-exclamation-triangle me-1"></i>Arıza/Hasar Notu:
                </label>
                <textarea name="damage_note" class="form-control" rows="3" placeholder="Ekipmanlarda herhangi bir arıza veya hasar varsa belirtin..."></textarea>
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
    `;
    
    modalBody.innerHTML = html;
    
    const modal = document.getElementById('returnModal');
    if (modal) {
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }
}

// Filtreleme fonksiyonları
function filterAssignments() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    
    const activeCards = document.querySelectorAll('#activeAssignments .col-lg-4');
    const historyCards = document.querySelectorAll('#historyAssignments .col-lg-4');
    
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
    document.querySelectorAll('#activeAssignments .col-lg-4, #historyAssignments .col-lg-4').forEach(card => {
        card.style.display = 'block';
    });
    
    // Sayıları sıfırla
    document.getElementById('activeCount').textContent = {{ $assignments->where('status', 0)->count() }};
    document.getElementById('historyCount').textContent = {{ $assignments->where('status', 1)->count() }};
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Filtre butonları
    document.getElementById('filterBtn').addEventListener('click', filterAssignments);
    document.getElementById('clearBtn').addEventListener('click', clearFilters);
    
    // Enter tuşu ile filtreleme
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            filterAssignments();
        }
    });
    
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