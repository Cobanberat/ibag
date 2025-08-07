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
    
    <!-- Individual Tracking Bilgi Paneli -->
    <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>
                <strong>Takip Sistemi:</strong>
                <div class="mt-1">
                    <small>
                        <span class="badge bg-info me-1">Ayrı Takip</span> Her ekipman tek adet, ayrı kod (Jeneratör, bilgisayar gibi)<br>
                        <span class="badge bg-secondary me-1">Toplu Takip</span> Miktar bazlı, tek kod (Kablo, vida gibi)
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
        <div class="col-md-3">
            <select class="form-select w-auto" id="trackingFilter" style="max-width:180px">
                <option value="">Tüm Takip Türleri</option>
                <option value="1">Ayrı Takip</option>
                <option value="0">Toplu Takip</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="button" class="btn btn-outline-secondary" id="clearFilters" style="max-width:180px;">
                <i class="fas fa-times"></i> Filtreleri Temizle
            </button>
        </div>
    </div>
    <div class="card shadow-lg border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="equipmentTable" style="min-height:400px;">
                    <thead class="table-light">
                        <tr>
                            <th>Sıra</th>
                            <th>Kod</th>
                            <th>Ürün Cinsi</th>
                            <th>Marka</th>
                            <th>Model</th>
                            <th>Beden</th>
                            <th>Özellik</th>
                            <th>Adet/Takip</th>
                            <th>Durum</th>
                            <th>Lokasyon</th>
                            <th>Tarih</th>
                            <th>Not</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($equipmentStocks as $index => $stock)
                            <tr data-id="{{ $stock->id }}">
                                <td>{{ ($equipmentStocks->currentPage() - 1) * $equipmentStocks->perPage() + $index + 1 }}</td>
                                <td class="editable-cell" data-field="code" data-id="{{ $stock->id }}">{{ $stock->code ?? '-' }}</td>
                                <td class="editable-cell" data-field="equipment_name" data-id="{{ $stock->id }}">{{ $stock->equipment->name ?? '-' }}</td>
                                <td class="editable-cell" data-field="brand" data-id="{{ $stock->id }}">{{ $stock->brand ?? '-' }}</td>
                                <td class="editable-cell" data-field="model" data-id="{{ $stock->id }}">{{ $stock->model ?? '-' }}</td>
                                <td class="editable-cell" data-field="size" data-id="{{ $stock->id }}">{{ $stock->size ?? '-' }}</td>
                                <td class="editable-cell" data-field="feature" data-id="{{ $stock->id }}">{{ $stock->feature ?? '-' }}</td>
                                <td class="editable-cell" data-field="quantity" data-id="{{ $stock->id }}">
                                    @if($stock->equipment && $stock->equipment->individual_tracking)
                                        <span class="badge bg-info">Ayrı Takip</span>
                                        <br><small class="text-muted">Kod: {{ $stock->code ?? '-' }}</small>
                                    @else
                                        {{ $stock->quantity ?? 0 }}
                                    @endif
                                </td>
                                <td class="editable-cell" data-field="status" data-id="{{ $stock->id }}">{{ $stock->status ?? '-' }}</td>
                                <td class="editable-cell" data-field="location" data-id="{{ $stock->id }}">{{ $stock->location ?? '-' }}</td>
                                <td>{{ $stock->created_at ? $stock->created_at->format('d.m.Y') : '-' }}</td>
                                <td class="editable-cell" data-field="note" data-id="{{ $stock->id }}">{{ $stock->note ?? '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="showDetail({{ $stock->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteEquipment({{ $stock->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Henüz ekipman bulunmuyor</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <nav class="mt-3 sticky-pagination p-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Toplam {{ $equipmentStocks->total() }} kayıttan {{ $equipmentStocks->firstItem() ?? 0 }}-{{ $equipmentStocks->lastItem() ?? 0 }} arası gösteriliyor
                    </div>
                    <ul class="pagination justify-content-end mb-0" id="pagination">
                        @if($equipmentStocks->hasPages())
                            {{ $equipmentStocks->links() }}
                        @endif
                    </ul>
                </div>
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
                    <p><strong>Kod:</strong> <span id="detailCode">-</span></p>
                    <p><strong>Ürün Cinsi:</strong> <span id="detailType">-</span></p>
                    <p><strong>Marka:</strong> <span id="detailBrand">-</span></p>
                    <p><strong>Model:</strong> <span id="detailModel">-</span></p>
                    <p><strong>Beden:</strong> <span id="detailSize">-</span></p>
                    <p><strong>Özellik:</strong> <span id="detailFeature">-</span></p>
                    <p><strong>Adet/Takip:</strong> <span id="detailCount">-</span></p>
                    <p><strong>Takip Türü:</strong> <span id="detailTrackingType">-</span></p>
                    <p><strong>Durum:</strong> <span id="detailStatus">-</span></p>
                    <p><strong>Lokasyon:</strong> <span id="detailLocation">-</span></p>
                    <p><strong>Tarih:</strong> <span id="detailDate">-</span></p>
                    <p><strong>Not:</strong> <span id="detailNote">-</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
@vite(['resources/js/equipment.js'])
@endsection