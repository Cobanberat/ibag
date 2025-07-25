@extends('layouts.admin')
@section('content')

@vite('resources/css/requests.css')

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a></li>
        <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
        <li class="breadcrumb-item active" aria-current="page">Talepler</li>
    </ol>
</nav>

<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-clipboard-list me-2"></i> Talepler</span>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRequestModal">
            <i class="fas fa-plus"></i> Yeni Talep
        </button>
    </div>
    <div class="card-body">
        <!-- Filtreleme Barı -->
        <form class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" class="form-control" id="requestSearch" placeholder="Ekipman, açıklama...">
            </div>
            <div class="col-md-3">
                <select class="form-select" id="requestTypeFilter">
                    <option value="">Talep Tipi (Tümü)</option>
                    <option>Bakım</option>
                    <option>Arıza</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="requestStatusFilter">
                    <option value="">Durum (Tümü)</option>
                    <option>Bekliyor</option>
                    <option>İşlemde</option>
                    <option>Tamamlandı</option>
                    <option>Reddedildi</option>
                </select>
            </div>
            <div class="col-md-3 text-end">
                <button type="button" class="btn btn-outline-secondary" id="filterBtn"><i class="fas fa-filter"></i> Filtrele</button>
            </div>
        </form>
        <!-- Talepler Tablosu -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ekipman</th>
                        <th>Talep Tipi</th>
                        <th>Açıklama</th>
                        <th>Durum</th>
                        <th>Tarih</th>
                        <th class="text-end">Aksiyon</th>
                    </tr>
                </thead>
                <tbody id="requestsTableBody">
                    <!-- Örnek satır -->
                    <tr>
                        <td>1</td>
                        <td>Jeneratör 5kVA</td>
                        <td><span class="badge bg-warning text-dark">Bakım</span></td>
                        <td>Periyodik bakım zamanı geldi.</td>
                        <td><span class="badge bg-info text-dark">Bekliyor</span></td>
                        <td>2024-06-20</td>
                        <td class="text-end">
                            <button class="btn btn-outline-info btn-sm request-detail-btn"><i class="fas fa-eye"></i> Detay</button>
                        </td>
                    </tr>
                    <!-- ... -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Talep Detay Modalı -->
<div class="modal fade" id="requestDetailModal" tabindex="-1" aria-labelledby="requestDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestDetailModalLabel">Talep Detayı</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body" id="requestDetailBody">
                <!-- JS ile doldurulacak -->
            </div>
        </div>
    </div>
</div>
    
<!-- Yeni Talep Ekle Modalı -->
<div class="modal fade" id="addRequestModal" tabindex="-1" aria-labelledby="addRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRequestModalLabel">Yeni Talep Oluştur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Ekipman</label>
                        <select class="form-select" id="requestEquipSelect" required>
                            <option value="">Ekipman Seçiniz</option>
                            <option value="SNO1">JEN-001 - 2.5 KW Benzinli Jeneratör</option>
                            <option value="SNO2">JEN-002 - 3.5 KW Benzinli Jeneratör</option>
                            <option value="SNO3">JEN-003 - 4.4 KW Benzinli Jeneratör</option>
                            <option value="SNO4">JEN-004 - 7.5 KW Dizel Jeneratör</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Talep Tipi</label>
                        <select class="form-select" required>
                            <option value="">Seçiniz</option>
                            <option>Bakım</option>
                            <option>Arıza</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Açıklama</label>
                        <textarea class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gönder</button>
                </form>
            </div>
        </div>
    </div>
</div>

@vite('resources/js/requests.js')
@endsection