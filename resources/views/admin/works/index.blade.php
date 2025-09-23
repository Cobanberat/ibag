@extends('layouts.admin')
@section('content')
@vite('resources/css/works.css')
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
                        <i class="fas fa-plus-circle me-1"></i>
                        <span class="d-none d-sm-inline">Zimmet Alma</span>
                        <span class="d-sm-none">Zimmet</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container-fluid p-0">
        <!-- Başlık ve Açıklama -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h1 class="h3 mb-2 text-primary">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    Yeni Zimmet Al
                                </h1>
                                <p class="text-muted mb-0">İhtiyacınız olan ekipmanları zimmet olarak alın</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('admin.teslimEt') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> 
                                    <span class="d-none d-sm-inline">Geri Dön</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-lg border-0 mb-4 modern-card">
            <div class="card-header text-white d-flex align-items-center modern-gradient rounded-top">
                <i class="fas fa-boxes fa-lg me-2"></i>
                <h4 class="mb-0" style="color: #fff;">Zimmet Formu</h4>
            </div>
            <div class="card-body p-4 bg-light rounded-bottom">
                <form action="{{ route('admin.zimmetAl.store') }}" method="POST" enctype="multipart/form-data"
                    autocomplete="off" id="assignmentForm">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold mb-3">
                            <i class="fas fa-boxes me-1"></i> Götürülecek Ekipmanlar
                        </label>
                        {{-- <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Bilgi:</strong> Sadece kullanılabilir (aktif) ekipmanlar listelenmektedir. Ekipman seçin, adet girin ve her ekipman için fotoğraf yükleyin.
                        </div> --}}
                        <div id="equipment-list" class="mt-3">
                            <div class="equipment-row mb-3 py-3 px-3 rounded modern-row bg-white shadow-sm position-relative border">
                                <div class="row g-3 align-items-end">
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <label class="form-label fw-bold small">Ekipman Seçimi</label>
                                        <div class="d-flex gap-2">
                                            <select name="equipment_id[]" class="form-select equipment-select modern-input select2 flex-grow-1"
                                                required>
                                                <option value="">Ekipman Seç</option>
                                                @foreach ($equipmentStocks as $stock)
                                                    @if ($stock->equipment)
                                                        <option value="{{ $stock->equipment->id }}"
                                                            data-individual="{{ $stock->equipment->individual_tracking }}"
                                                            data-stock="{{ $stock->quantity }}"
                                                            data-code="{{ $stock->code ?? '' }}">
                                                            {{ $stock->equipment->name }} 
                                                            @if($stock->code)
                                                                ({{ $stock->code }})
                                                            @endif
                                                            - {{ $stock->status }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-outline-primary qr-scan-btn" title="QR Kod Tara">
                                                <i class="fas fa-qrcode"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-6 col-md-3 col-lg-2">
                                        <label class="form-label fw-bold small">Miktar</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-success text-white">
                                                <i class="fas fa-hashtag"></i>
                                            </span>
                                            <input type="number" name="quantity[]" class="form-control equipment-qty modern-input"
                                                min="1" value="1" placeholder="Örn: 2" required>
                                        </div>
                                    </div>

                                    <div class="col-6 col-md-3 col-lg-2">
                                        <label class="form-label fw-bold small">İşlem</label>
                                        <button type="button" class="btn btn-outline-danger remove-equipment w-100"
                                            title="Ekipmanı kaldır">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <div class="col-12 mt-3 equipment-photos" style="display: none;">
                                        <!-- Fotoğraf yükleme alanı buraya dinamik olarak eklenecek -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-equipment">
                            <i class="fas fa-plus"></i> Ekipman Ekle
                        </button>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-sticky-note me-1"></i> Notlar
                        </label>
                        <textarea class="form-control modern-input" name="note" rows="3" 
                            placeholder="Zimmet alma amacı, kullanım yeri veya ek bilgiler..."></textarea>
                    </div>

                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                        <button type="submit" class="btn btn-gradient btn-lg px-4 order-2 order-sm-1">
                            <i class="fas fa-save me-1"></i> 
                            <span class="d-none d-sm-inline">Zimmet Al</span>
                            <span class="d-sm-none">Kaydet</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- QR Kod Tarama Modal -->
    <div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrScannerModalLabel">
                        <i class="fas fa-qrcode me-2"></i>QR Kod Tarama
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="qr-reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>

    <!-- Fotoğraf Yükleme Template'leri -->
    <template id="photo-template-individual">
        <div class="alert alert-info mb-0">
            <i class="fas fa-info-circle me-2"></i>
            <strong class="equipment-name">Ekipman</strong> için fotoğraf yükleyin
        </div>
        <div class="mt-2">
            <label class="form-label fw-bold">
                <i class="fas fa-camera me-1"></i>Fotoğraf:
            </label>
            <input type="file" name="equipment_photo[]" class="form-control" accept="image/*" required>
            <small class="text-muted">Ekipmanın mevcut durumunu gösteren fotoğraf çekin</small>
        </div>
    </template>

    <template id="photo-template-bulk">
        <div class="alert alert-info mb-0">
            <i class="fas fa-info-circle me-2"></i>
            <strong class="equipment-name">Ekipman</strong> için fotoğraf yükleyin
        </div>
        <div class="mt-2">
            <label class="form-label fw-bold">
                <i class="fas fa-camera me-1"></i>Fotoğraf:
            </label>
            <input type="file" name="equipment_photo[]" class="form-control" accept="image/*" required>
            <small class="text-muted">Ekipmanın mevcut durumunu gösteren fotoğraf çekin</small>
        </div>
    </template>

    <template id="photo-template-warning">
        <div class="alert alert-warning mb-0">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Fotoğraf:</strong> Ekipman seçildikten sonra fotoğraf yükleme alanı görünecektir.
        </div>
    </template>

@vite('resources/js/works.js')

<style>
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
    
    /* Equipment Row Responsive */
    .equipment-row {
        margin-bottom: 1rem !important;
        border: 1px solid #e9ecef;
        border-radius: 0.5rem;
        background: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .equipment-row .form-label {
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #495057;
        font-size: 0.875rem;
    }
    
    .equipment-row .form-select,
    .equipment-row .form-control {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        font-size: 0.875rem;
    }
    
    .equipment-row .form-select:focus,
    .equipment-row .form-control:focus {
        border-color: #4facfe;
        box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
    }
    
    .qr-scan-btn {
        min-width: 45px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .equipment-photos {
        border-top: 1px solid #e9ecef;
        padding-top: 1rem;
        margin-top: 1rem;
    }
    
    #equipment-list {
        min-height: 100px;
    }
    
    .modern-card {
        border-radius: 1rem;
        overflow: hidden;
    }
    
    .modern-gradient {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .btn-gradient {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        border: none;
        color: white;
        font-weight: 600;
    }
    
    .btn-gradient:hover {
        background: linear-gradient(135deg, #3d8bfe 0%, #00d4fe 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);
    }
    
    /* Mobile Optimizations */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 0 15px;
        }
        
        .equipment-row {
            padding: 1rem !important;
        }
        
        .equipment-row .row {
            margin: 0;
        }
        
        .equipment-row .col-12,
        .equipment-row .col-6 {
            padding: 0.25rem;
        }
        
        .form-label {
            font-size: 0.8rem;
        }
        
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }
        
        .card-body {
            padding: 1.5rem !important;
        }
        
        .h3 {
            font-size: 1.25rem;
        }
        
        .text-end {
            text-align: left !important;
            margin-top: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .equipment-row {
            padding: 0.75rem !important;
        }
        
        .d-flex.gap-2 {
            flex-direction: column;
            gap: 0.5rem !important;
        }
        
        .qr-scan-btn {
            width: 100%;
            height: 40px;
        }
        
        .btn {
            font-size: 0.875rem;
        }
        
        .input-group-text {
            font-size: 0.875rem;
        }
    }
    
    /* Tablet Optimizations */
    @media (min-width: 768px) and (max-width: 1024px) {
        .equipment-row .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
        
        .equipment-row .col-md-3 {
            flex: 0 0 25%;
            max-width: 25%;
        }
    }
</style>
@endsection
