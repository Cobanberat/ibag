@extends('layouts.admin')
@section('content')
@vite('resources/css/works.css')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a></li>
        <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Ekipmanlar' }}</li>
    </ol>
</nav>
<div class="container mt-4">
    <div class="card shadow-lg border-0 mb-4 modern-card">
        <div class="card-header text-white d-flex align-items-center modern-gradient rounded-top">
            <i class="fas fa-plus-circle fa-lg me-2"></i>
            <h4 class="mb-0" style="color: #fff;">Yeni İş Ekle</h4>
        </div>
        <div class="card-body p-4 bg-light rounded-bottom">
            <form autocomplete="off">
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="fas fa-map-marker-alt me-1"></i> İl</label>
                        <input type="text" class="form-control modern-input" placeholder="Konya" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="fas fa-map-pin me-1"></i> İlçe</label>
                        <input type="text" class="form-control modern-input" placeholder="Selçuklu" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="fas fa-location-arrow me-1"></i> Mahalle</label>
                        <input type="text" class="form-control modern-input" placeholder="Bosna Hersek" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="fas fa-road me-1"></i> Açık Adres</label>
                        <input type="text" class="form-control modern-input" placeholder="Örn: Hoca Ahmet Yesevi Cad. No:12 Daire:5" required>
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label class="form-label fw-bold"><i class="fas fa-users me-1"></i> Ekip Yetkilisi</label>
                        <div class="row g-2 align-items-end mb-2 py-3 px-2 rounded modern-row bg-white shadow-sm position-relative justify-content-center">
                            <div class="col-md-12 d-flex align-items-center justify-content-center">
                                <span class="badge bg-primary me-2"><i class="fas fa-user"></i></span>
                                <select class="form-select modern-input" name="person-select" required>
                                    <option value="">Çalışan Seç</option>
                                    <option value="Berat Çoban">Berat Çoban</option>
                                    <option value="Ayşe Yılmaz">Ayşe Yılmaz</option>
                                    <option value="Mehmet Demir">Mehmet Demir</option>
                                    <option value="Zeynep Kaya">Zeynep Kaya</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold"><i class="fas fa-boxes me-1"></i> Götürülecek Ekipmanlar</label>
                    <div class="small text-muted mb-2">Ekipman seçin, adet girin. Her ekipman için adet kadar fotoğraf yükleyin.</div>
                    <div id="equipment-list">
                        <div class="row g-2 align-items-end equipment-row mb-3 py-3 px-2 rounded modern-row bg-white shadow-sm position-relative">
                            <div class="col-md-4 d-flex align-items-center">
                                <span class="badge bg-secondary me-2"><i class="fas fa-cube"></i></span>
                                <select class="form-select equipment-select modern-input" required>
                                    <option value="">Ekipman Seç</option>
                                    <option>UPS 3kVA</option>
                                    <option>Kask</option>
                                    <option>Jeneratör</option>
                                    <option>Kırıcı</option>
                                    <option>Akü</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-center">
                                <span class="badge bg-success me-2"><i class="fas fa-hashtag"></i></span>
                                <input type="number" class="form-control equipment-qty modern-input" min="1" max="50" value="1" placeholder="Örn: 2" required>
                            </div>
                           
                            <div class="col-md-2 text-end">
                                <button type="button" class="btn btn-outline-danger remove-equipment w-100" title="Ekipmanı kaldır"><i class="fas fa-trash"></i></button>
                            </div>
                            <div class="col-12 mt-3 equipment-photos" style="display:none;"></div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-equipment"><i class="fas fa-plus"></i> Ekipman Ekle</button>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold"><i class="fas fa-sticky-note me-1"></i> Notlar</label>
                    <textarea class="form-control modern-input" rows="2" placeholder="Ek bilgi (opsiyonel)"></textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-gradient btn-lg px-4"><i class="fas fa-save me-1"></i> Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>
@vite('resources/js/works.js')

@endsection