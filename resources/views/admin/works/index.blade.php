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
                        <label class="form-label fw-bold">
                            <i class="fas fa-boxes me-1"></i> Götürülecek Ekipmanlar
                        </label>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Bilgi:</strong> Sadece kullanılabilir (aktif) ekipmanlar listelenmektedir. Ekipman seçin, adet girin ve her ekipman için fotoğraf yükleyin.
                        </div>
                        <div id="equipment-list">
                            <div
                                class="row g-3 align-items-end equipment-row mb-3 py-3 px-3 rounded modern-row bg-white shadow-sm position-relative border">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small">Ekipman Seçimi</label>
                                    <select name="equipment_id[]" class="form-select equipment-select modern-input select2"
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

                                <div class="col-12 mt-3 equipment-photos">
                                    <div class="alert alert-warning mb-0">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Fotoğraf:</strong> Ekipman seçildikten sonra fotoğraf yükleme alanı görünecektir.
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

    @vite('resources/js/works.js')
@endsection
