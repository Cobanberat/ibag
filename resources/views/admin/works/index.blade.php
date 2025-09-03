@extends('layouts.admin')
@section('content')
@vite('resources/css/works.css')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a>
            </li>
            <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
            <li class="breadcrumb-item active" aria-current="page">Zimmet Alma</li>
        </ol>
    </nav>

    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2 text-primary"></i>Yeni Zimmet Al</h2>
                <p class="text-muted mt-2">İhtiyacınız olan ekipmanları zimmet olarak alın</p>
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
                            <div
                                class="row g-3 align-items-end equipment-row mb-3 py-3 px-3 rounded modern-row bg-white shadow-sm position-relative border">
                                <div class="col-md-4">
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
                                    {{-- <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        QR kod ile hızlı seçim yapabilirsiniz
                                    </small> --}}
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold small">Miktar</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white">
                                            <i class="fas fa-hashtag"></i>
                                        </span>
                                        <input type="number" name="quantity[]" class="form-control equipment-qty modern-input"
                                            min="1" value="1" placeholder="Örn: 2" required>
                                    </div>
                                </div>

                                <div class="col-md-2">
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

                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.teslimEt') }}" class="btn btn-secondary btn-lg px-4">
                    <i class="fas fa-arrow-left me-1"></i> Geri Dön
                </a>
                        <button type="submit" class="btn btn-gradient btn-lg px-4">
                            <i class="fas fa-save me-1"></i> Zimmet Al
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
    }
    
    .equipment-row .form-select,
    .equipment-row .form-control {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
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
</style>
@endsection
