@extends('layouts.admin')
@section('content')
@vite(['resources/css/equipment.css'])
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a></li>
        <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Ekipmanlar' }}</li>
    </ol>
</nav>
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2 class="mb-0 fw-bold"><i class="fas fa-cubes me-2 text-primary"></i>Ekipman Yönetimi</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('stock.create') }}" class="btn btn-success shadow-sm">
                <i class="fas fa-plus"></i> Ekipman Ekle
            </a>
            <button class="btn btn-outline-primary ms-2 shadow-sm" id="exportCsvBtn">
                <i class="fas fa-file-csv"></i> CSV Aktar
            </button>
            <button class="btn btn-outline-danger ms-2 shadow-sm" id="deleteSelectedBtn" disabled>
                <i class="fas fa-trash"></i> Seçiliyi Sil
            </button>
        </div>
    </div>
    
    <!-- Düzenleme Yardım Paneli -->
    <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-info-circle me-2"></i>
            <div>
                <strong>Düzenleme Özelliği:</strong> Tablo hücrelerine çift tıklayarak düzenleyebilirsiniz.
                <div class="mt-1">
                    <small>
                        <strong>Klavye Kısayolları:</strong>
                        <span class="badge bg-primary ms-1">Enter</span> Kaydet,
                        <span class="badge bg-secondary ms-1">Escape</span> İptal,
                        <span class="badge bg-info ms-1">Tab</span> Sonraki Hücre
                    </small>
                </div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div class="row mb-4 g-2">
        <div class="col-md-3">
            <input type="text" class="form-control w-auto" placeholder="Ara..." id="searchInput" style="max-width:200px;">
        </div>
        <div class="col-md-3">
            <select class="form-select w-auto" id="typeFilter" style="max-width:180px;">
                <option value="">Tüm Ürün Cinsleri</option>
                <option>2.5 KW Benzinli Jeneratör</option>
                <option>3.5 KW Benzinli Jeneratör</option>
                <option>4.4 KW Benzinli Jeneratör</option>
                <option>7.5 KW Dizel Jeneratör</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control w-auto" placeholder="Marka..." id="brandFilter" style="max-width:180px;">
        </div>
        <div class="col-md-3">
            <select class="form-select w-auto" id="statusFilter" style="max-width:160px">
                <option value="">Tüm Durumlar</option>
                <option>Sıfır</option>
                <option>Açık</option>
            </select>
        </div>
    </div>
    <div class="card shadow-lg border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="equipmentTable" style="min-height:400px;">
                    <thead class="table-light">
                        <tr>
                            <th>Sıra</th>
                            <th>Ürün Cinsi</th>
                            <th>Marka</th>
                            <th>Model</th>
                            <th>Beden</th>
                            <th>Özellik</th>
                            <th>Adet</th>
                            <th>Durum</th>
                            <th>Tarih</th>
                            <th>Not</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Satırlar JS ile doldurulacak -->
                    </tbody>
                </table>
            </div>
            <nav class="mt-3 sticky-pagination p-2">
                <ul class="pagination justify-content-end mb-0" id="pagination">
                    <!-- Pagination JS ile doldurulacak -->
                </ul>
            </nav>
        </div>
    </div>
    <!-- Detay Modalı -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Ekipman Detayı</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Sıra:</strong> <span id="detailSno">-</span></p>
                    <p><strong>Ürün Cinsi:</strong> <span id="detailType">-</span></p>
                    <p><strong>Marka:</strong> <span id="detailBrand">-</span></p>
                    <p><strong>Model:</strong> <span id="detailModel">-</span></p>
                    <p><strong>Beden:</strong> <span id="detailSize">-</span></p>
                    <p><strong>Özellik:</strong> <span id="detailFeature">-</span></p>
                    <p><strong>Adet:</strong> <span id="detailCount">-</span></p>
                    <p><strong>Durum:</strong> <span id="detailStatus">-</span></p>
                    <p><strong>Tarih:</strong> <span id="detailDate">-</span></p>
                    <p><strong>Not:</strong> <span id="detailNote">-</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
@vite(['resources/js/equipment.js'])
@endsection