@extends('layouts.admin')
@section('content')
@vite('resources/css/stock.css')

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
                    <i class="fas fa-boxes me-1"></i>
                    <span class="d-none d-sm-inline">{{ $pageTitle ?? 'Stok' }}</span>
                    <span class="d-sm-none">Stok</span>
                </li>
    </ol>
</nav>
    </div>
</div>
   <div>
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        Kritik seviyenin altına düşen ürünler var! Lütfen stokları kontrol edin.
    </div>
    <!-- Filtreler -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label fw-bold small mb-1">
                                <i class="fas fa-search me-1 text-primary"></i>Arama
                            </label>
                            <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="Ekipman ara...">
                        </div>
                        <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                            <label class="form-label fw-bold small mb-1">
                                <i class="fas fa-layer-group me-1 text-primary"></i>Kategori
                            </label>
                            <select class="form-select form-select-sm" id="filterCategory">
            <option value="">Tüm Kategoriler</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                            <label class="form-label fw-bold small mb-1">
                                <i class="fas fa-tags me-1 text-primary"></i>Takip Türü
                            </label>
                            <select class="form-select form-select-sm" id="filterTracking">
            <option value="">Tüm Takip Türleri</option>
            <option value="1">Ayrı Takip</option>
            <option value="0">Toplu Takip</option>
        </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                            <button class="btn btn-sm btn-outline-secondary w-100" id="clearFiltersBtn">
                                <i class="fas fa-times me-1"></i>
                                <span class="d-none d-sm-inline">Temizle</span>
                            </button>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                            <button class="btn btn-add-equipment d-flex align-items-center justify-content-center gap-2 w-100" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="fas fa-plus"></i>
                                <span class="d-none d-sm-inline">Yeni Ekipman Ekle</span>
                                <span class="d-sm-none">Ekle</span>
        </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Ürün Ekle Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel"><i class="fas fa-plus me-2"></i>Yeni Ekipman Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addProductForm">
                    <div class="modal-body">
                        <!-- Mod Seçimi -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="quantityOnlyMode" checked>
                                <label class="form-check-label fw-bold" for="quantityOnlyMode">
                                    <i class="fas fa-layer-group me-2"></i>Sadece miktar gir (hızlı ekleme)
                                </label>
                            </div>
                            <small class="text-muted">Aktifse sadece miktar girilir, ekipman özellikleri otomatik oluşturulur</small>
                        </div>

                        <!-- Sadece Miktar Modu -->
                        <div id="quantityOnlySection">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Ekipman Seç</label>
                                        <select class="form-select border-0" name="equipment_id" required style="background:#fff; border-radius:0.5em; border:1.5px solid #e3e6ea;">
                                            <option value="">Ekipman Seçiniz</option>
                                            @foreach($categories as $category)
                                                <optgroup label="{{ $category->name }}">
                                                    @foreach($category->equipments ?? [] as $equipment)
                                                        <option value="{{ $equipment->id }}" data-individual-tracking="{{ $equipment->individual_tracking ? 'true' : 'false' }}">{{ $equipment->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Seçilen ekipmanın mevcut kodları (tekil takip için) -->
                                    <div id="addExistingCodesSelectWrapper" style="display:none;" class="mb-3">
                                        <label class="form-label fw-bold">Mevcut Kod Seç (Ekipman)</label>
                                        <select class="form-select" id="addExistingCodesSelect"></select>
                                        <small class="form-text text-muted">Kod seçenekleri ekipman adı ile birlikte listelenir</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold" id="quantityLabel">Miktar</label>
                                        <input type="number" class="form-control" name="quantity" min="1" value="1" required>
                                        <small class="form-text text-muted" id="quantityHelp">Ayrı takip ekipmanları için miktar otomatik 1 olur</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Manuel Ekipman Modu -->
                        <div id="manualEquipmentSection" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Ekipman Adı</label>
                                        <input type="text" class="form-control" name="name" placeholder="Örn: Jeneratör" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Kategori</label>
                                        <select class="form-select border-0" name="category_id" required style="background:#fff; border-radius:0.5em; border:1.5px solid #e3e6ea;">
                                            <option value="">Kategori Seçiniz</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Marka</label>
                                        <input type="text" class="form-control" name="brand" placeholder="Örn: Honda">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Model</label>
                                        <input type="text" class="form-control" name="model" placeholder="Örn: EU3000i">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold" id="manualQuantityLabel">Miktar</label>
                                        <input type="number" class="form-control" name="manual_quantity" min="1" value="1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Beden/Özellik</label>
                                        <input type="text" class="form-control" name="size" placeholder="Örn: 3KW, XL, 1000W">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Birim Türü</label>
                                        <select class="form-select border-0" name="unit_type" id="modalUnitType" required style="background:#fff; border-radius:0.5em; border:1.5px solid #e3e6ea;">
                                            <option value="adet">Adet</option>
                                            <option value="metre">Metre</option>
                                            <option value="kilogram">Kilogram</option>
                                            <option value="litre">Litre</option>
                                            <option value="paket">Paket</option>
                                            <option value="kutu">Kutu</option>
                                            <option value="çift">Çift</option>
                                            <option value="takım">Takım</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold" id="modalCriticalLevelLabel">Kritik Seviye</label>
                                        <input type="number" class="form-control" name="critical_level" id="modalCriticalLevel" min="0" value="3" step="0.01">
                                        <small class="form-text text-muted" id="modalCriticalLevelHelp">Birim türüne göre kritik seviye</small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Özellik</label>
                                        <textarea class="form-control" name="feature" rows="2" placeholder="Ekipman özellikleri..."></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Açıklama</label>
                                        <textarea class="form-control" name="note" rows="2" placeholder="Ekipman hakkında ek bilgiler..."></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="individual_tracking" id="individualTracking">
                                            <label class="form-check-label fw-bold" for="individualTracking">
                                                <i class="fas fa-barcode me-2"></i>Her ürünü ayrı ayrı takip et
                                            </label>
                                        </div>
                                        <small class="text-muted">
                                            <strong>Aktifse:</strong> Her ürün ayrı kod, ayrı resim, tek adet (Jeneratör, bilgisayar gibi)<br>
                                            <strong>Kapalıysa:</strong> Tek kod, tek resim, miktar bazlı (Kablo, vida gibi)
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resim Seçimi -->
                        <div id="imageSection">
                        <div class="mb-3">
                                <label class="form-label fw-bold">Ekipman Fotoğrafı</label>
                            <input type="file" class="form-control" name="photo" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Toplu Kategori Değiştir Modal -->
    <div class="modal fade" id="bulkCategoryModal" tabindex="-1" aria-labelledby="bulkCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-slide-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkCategoryModalLabel"><i class="fas fa-layer-group me-2"></i>Toplu Kategori Değiştir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bulkCategoryForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Yeni Kategori</label>
                            <select class="form-select border-0" name="newCategory" required style="background:#fff; border-radius:0.5em; border:1.5px solid #e3e6ea;">
                                <option value="">Seçiniz</option>
                                <option>Donanım</option>
                                <option>Ağ</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- KALDIRILDI: Eski stok giriş/çıkış modalı (tek modal mimarisi için) -->
    <!-- Stok Hareketleri Modalı -->
    <div class="modal fade" id="logModal" tabindex="-1" aria-labelledby="logModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logModalLabel"><i class="fas fa-history me-2"></i>Stok Hareketleri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tarih</th>
                                <th>İşlem</th>
                                <th>Miktar</th>
                                <th>Açıklama</th>
                            </tr>
                        </thead>
                        <tbody id="logTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Ürün Fotoğrafı Modalı -->
    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalLabel"><i class="fas fa-image me-2"></i>Ürün Fotoğrafı</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalPhoto" src="" alt="Ürün Fotoğrafı" style="max-width:100%;max-height:350px;border-radius:1em;box-shadow:0 2px 12px #0d6efd22;">
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <!-- Stok Tablosu -->
    <div class="card mt-2 p-2" style="border-radius:1.2rem;box-shadow:0 4px 24px #0d6efd11;">
            <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0" id="stockTable" style="font-size:0.95em;">
                    <thead class="table-light">
                        <tr>
                            <th class="d-none d-md-table-cell"><input type="checkbox" id="selectAll"></th>
                            <th>Ürün</th>
                            <th class="d-none d-sm-table-cell">Kategori</th>
                            <th class="d-none d-md-table-cell">Birim Türü</th>
                            <th>Miktar</th>
                            <th class="d-none d-sm-table-cell">Kritik Seviye</th>
                            <th class="d-none d-md-table-cell">Takip Türü</th>
                            <th class="d-none d-lg-table-cell">Stok Durumu</th>
                            <th class="d-none d-xl-table-cell">Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody id="stockTableBody">
                        @forelse($stocks as $stock)
                            <tr class="{{ $stock->row_class }}" data-id="{{ $stock->id }}">
                                <td class="d-none d-md-table-cell"><input type="checkbox" class="stock-checkbox" value="{{ $stock->id }}"></td>
                                <td>
                                    <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $stock->name ?? '-' }}</span>
                                        <small class="text-muted d-md-none">{{ $stock->category->name ?? '-' }}</small>
                                        <div class="d-flex gap-1 mt-1 d-md-none">
                                            <span class="badge bg-info small">{{ $stock->unit_type_label }}</span>
                                            @if($stock->individual_tracking)
                                                <span class="badge bg-primary small"><i class="fas fa-user"></i> Ayrı</span>
                                            @else
                                                <span class="badge bg-secondary small"><i class="fas fa-layer-group"></i> Toplu</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-sm-table-cell">{{ $stock->category->name ?? '-' }}</td>
                                <td class="d-none d-md-table-cell">
                                    <span class="badge bg-info">{{ $stock->unit_type_label }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">{{ $stock->total_quantity }}</span>
                                        <small class="text-muted d-sm-none">Kritik: {{ $stock->critical_level }}</small>
                                    </div>
                                </td>
                                <td class="d-none d-sm-table-cell">{{ $stock->critical_level }}</td>
                                <td class="d-none d-md-table-cell">
                                    @if($stock->individual_tracking)
                                        <span class="badge bg-primary"><i class="fas fa-user"></i> Ayrı Takip</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="fas fa-layer-group"></i> Toplu Takip</span>
                                    @endif
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar {{ $stock->bar_class }}" style="width: {{ $stock->percentage }}%"></div>
                                    </div>
                                </td>
                                <td class="d-none d-xl-table-cell">
                                    @if($stock->status === 'Arızalı')
                                        <span class="badge bg-danger"><i class="fas fa-exclamation-triangle"></i> Arızalı</span>
                                    @elseif($stock->status === 'Bakımda')
                                        <span class="badge bg-warning"><i class="fas fa-tools"></i> Bakımda</span>
                                    @elseif($stock->stock_status === 'Tükendi')
                                        <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Tükendi</span>
                                    @elseif($stock->stock_status === 'Az')
                                        <span class="badge bg-warning"><i class="fas fa-exclamation-triangle"></i> Az Stok</span>
                                    @endif
                                </td>
                                
                                <td class="category-actions">
                                    <div class="d-flex flex-wrap gap-1">
                                    @if($stock->status === 'Arızalı')
                                            <button class="btn btn-outline-danger btn-sm" style="padding:0.35em 0.7em;border-radius:1.2em;font-size:0.8rem;" onclick="viewFault({{ $stock->id }})" title="Arıza Detayı">
                                            <i class="fas fa-exclamation-triangle"></i>
                                                <span class="d-none d-lg-inline ms-1">Arıza</span>
                                        </button>
                                            <button class="btn btn-outline-success btn-sm" style="padding:0.35em 0.7em;border-radius:1.2em;font-size:0.8rem;" onclick="repairEquipment({{ $stock->id }})" title="Tamir Et">
                                            <i class="fas fa-wrench"></i>
                                                <span class="d-none d-lg-inline ms-1">Tamir</span>
                                        </button>
                                    @elseif($stock->status === 'Bakımda')
                                            <button class="btn btn-outline-warning btn-sm" style="padding:0.35em 0.7em;border-radius:1.2em;font-size:0.8rem;" onclick="viewMaintenance({{ $stock->id }})" title="Bakım Detayı">
                                            <i class="fas fa-tools"></i>
                                                <span class="d-none d-lg-inline ms-1">Bakım</span>
                                        </button>
                                            <button class="btn btn-outline-success btn-sm" style="padding:0.35em 0.7em;border-radius:1.2em;font-size:0.8rem;" onclick="completeMaintenance({{ $stock->id }})" title="Bakımı Tamamla">
                                            <i class="fas fa-check"></i>
                                                <span class="d-none d-lg-inline ms-1">Tamamla</span>
                                        </button>
                                    @else
                                            <button class="btn btn-outline-info btn-sm" style="padding:0.35em 0.7em;border-radius:1.2em;font-size:0.8rem;" onclick="toggleStockDetails({{ $stock->id }})" title="Detayları Göster/Gizle">
                                            <i class="fas fa-eye"></i>
                                                <span class="d-none d-lg-inline ms-1">Detay</span>
                                        </button>
                                            <button class="btn btn-outline-secondary btn-sm" style="padding:0.35em 0.7em;border-radius:1.2em;font-size:0.8rem;" onclick="editStock({{ $stock->id }})" title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                                <span class="d-none d-lg-inline ms-1">Düzenle</span>
                                        </button>
                                            <button class="btn btn-outline-success btn-sm" style="padding:0.35em 0.7em;border-radius:1.2em;font-size:0.8rem;" onclick="stockIn({{ $stock->id }})" title="Stok Girişi">
                                            <i class="fas fa-plus"></i>
                                                <span class="d-none d-lg-inline ms-1">Giriş</span>
                                        </button>
                                            <button class="btn btn-outline-warning btn-sm" style="padding:0.35em 0.7em;border-radius:1.2em;font-size:0.8rem;" onclick="stockOut({{ $stock->id }})" title="Stok Çıkışı">
                                            <i class="fas fa-minus"></i>
                                                <span class="d-none d-lg-inline ms-1">Çıkış</span>
                                        </button>
                                    @endif
                                        <button class="btn btn-outline-danger btn-sm" style="padding:0.35em 0.7em;border-radius:1.2em;font-size:0.8rem;" onclick="deleteStock({{ $stock->id }})" title="Sil">
                                        <i class="fas fa-trash"></i>
                                            <span class="d-none d-lg-inline ms-1">Sil</span>
                                    </button>
                                    </div>
                                </td>
                            </tr>
                            <!-- Detay satırı (gizli) -->
                            <tr class="stock-detail-row" id="detailRow{{ $stock->id }}" style="display: none;">
                                <td colspan="11" class="p-0">
                                    <div class="bg-light p-2 p-md-3">
                                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
                                            <h6 class="mb-0 text-primary">
                                                <i class="fas fa-barcode me-2"></i>
                                                <span class="d-none d-sm-inline">{{ $stock->name }} - Stok Kodları</span>
                                                <span class="d-sm-none">Stok Kodları</span>
                                            </h6>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="toggleStockDetails({{ $stock->id }})">
                                                <i class="fas fa-times me-1"></i>
                                                <span class="d-none d-sm-inline">Kapat</span>
                                            </button>
                                        </div>
                                        <div id="stockCodes{{ $stock->id }}" class="stock-codes-container">
                                            <div class="text-center py-3">
                                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                    <span class="visually-hidden">Yükleniyor...</span>
                                                </div>
                                                <p class="text-muted mt-2 mb-0">Kodlar yükleniyor...</p>
                                            </div>
                                        </div>
                                        <!-- Carousel Controls -->
                                        <div id="carouselControls{{ $stock->id }}" style="display: none;">
                                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mb-2 gap-2">
                                                <button class="btn btn-sm btn-outline-primary carousel-btn w-100 w-sm-auto" onclick="previousPage({{ $stock->id }})" id="prevBtn{{ $stock->id }}" disabled>
                                                    <i class="fas fa-chevron-left me-1"></i>
                                                    <span class="d-none d-sm-inline">Önceki</span>
                                                    <span class="d-sm-none">Önceki</span>
                                                </button>
                                                <span class="text-muted small page-info" id="pageInfo{{ $stock->id }}">1 / 1</span>
                                                <button class="btn btn-sm btn-outline-primary carousel-btn w-100 w-sm-auto" onclick="nextPage({{ $stock->id }})" id="nextBtn{{ $stock->id }}" disabled>
                                                    <span class="d-none d-sm-inline">Sonraki</span>
                                                    <span class="d-sm-none">Sonraki</span>
                                                    <i class="fas fa-chevron-right ms-1"></i>
                                                </button>
                                            </div>
                                            <!-- Dots indicator -->
                                            <div class="text-center" id="dots{{ $stock->id }}">
                                                <!-- Dots will be generated by JavaScript -->
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <i class="fas fa-boxes fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Henüz stok bulunmuyor</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-3 gap-3">
                <button class="btn btn-danger btn-lg px-4" id="deleteSelected">
                    <i class="fas fa-trash-alt me-2"></i> 
                    <span class="d-none d-sm-inline">Seçili Ekipmanları Sil</span>
                    <span class="d-sm-none">Seçili Sil</span>
                </button>
                <div class="d-flex flex-column flex-sm-row align-items-center gap-3 gap-sm-4">
                    <div class="text-muted text-center text-sm-start">
                        <i class="fas fa-info-circle me-1"></i>
                        Toplam {{ $pagination['total'] }} stoktan {{ ($pagination['total'] > 0 ? 1 : 0) }}-{{ min($pagination['per_page'], $pagination['total']) }} arası gösteriliyor
                    </div>
                    <nav aria-label="Sayfalama">
                        <ul class="pagination mb-0" id="pagination">
                            @if($pagination['last_page'] > 1)
                                <!-- Önceki sayfa -->
                                @if($pagination['current_page'] > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="?page={{ $pagination['current_page'] - 1 }}">
                                            <i class="fas fa-chevron-left me-1"></i>
                                            <span class="d-none d-sm-inline">Önceki</span>
                                        </a>
                                    </li>
                                @endif

                                <!-- Sayfa numaraları -->
                                @php
                                    $startPage = max(1, $pagination['current_page'] - 2);
                                    $endPage = min($pagination['last_page'], $pagination['current_page'] + 2);
                                @endphp

                                @for($i = $startPage; $i <= $endPage; $i++)
                                    <li class="page-item {{ $i == $pagination['current_page'] ? 'active' : '' }}">
                                        <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                <!-- Sonraki sayfa -->
                                @if($pagination['current_page'] < $pagination['last_page'])
                                    <li class="page-item">
                                        <a class="page-link" href="?page={{ $pagination['current_page'] + 1 }}">
                                            <span class="d-none d-sm-inline">Sonraki</span>
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </a>
                                    </li>  
                                @endif
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

<!-- Tekil Modal: Stok işlemleri ve düzenleme -->
<div class="modal fade" id="stockOperationModal" tabindex="-1" aria-labelledby="stockOperationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stockOperationModalLabel">Stok İşlemi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="stockId">
                <input type="hidden" id="operationType">
                
                <!-- Stok Giriş/Çıkış Bölümü -->
                <div id="operationSection">
                    <div class="mb-2">
                        <span id="trackingTypeBadge" class="badge bg-secondary" style="display:inline-block;">Takip Türü</span>
                    </div>
                
                <div class="mb-3">
                    <label for="operationTitle" class="form-label fw-bold">İşlem Türü</label>
                    <h6 id="operationTitle" class="text-primary"></h6>
                </div>
                
                <!-- Tekil takip: mevcut kodlardan seçim (varsa) -->
                <div id="existingCodesSelectWrapper" style="display:none;" class="mb-3">
                    <label class="form-label fw-bold" id="existingCodesLabel">Mevcut Kod Seç</label>
                    <select class="form-select" id="existingCodesSelect"></select>
                    <small class="form-text text-muted" id="existingCodesHelp">Mevcut tekil ürünlerden birini seçtiğinizde ilgili kod otomatik doldurulur</small>
                </div>
                
                <!-- Aynı Özellik Seçeneği (Sadece Stok Girişi) -->
                <div id="samePropertiesOption" style="display: none;">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="useSameProperties" checked>
                            <label class="form-check-label fw-bold" for="useSameProperties">
                                <i class="fas fa-copy me-2"></i>Aynı özellikleri kullan
                            </label>
                        </div>
                        <small class="text-muted">Aktifse belirtilen stok kodunun özellikleri kullanılır</small>
                    </div>
                    
                    <!-- Referans Stok Kodu Girişi -->
                    <div id="referenceCodeSection" style="display: none;">
                        <div class="mb-3">
                            <label for="referenceCode" class="form-label fw-bold">Referans Stok Kodu</label>
                            <input type="text" class="form-control" id="referenceCode" placeholder="Özelliklerini kopyalamak istediğiniz stok kodunu girin">
                            <div id="referenceCodeValidationMessage" class="form-text"></div>
                            <small class="text-muted">Bu stok kodunun özellikleri (marka, model, boyut, özellik) kopyalanacak</small>
                        </div>
                    </div>
                </div>
                
                <!-- Manuel Özellik Girişi (Sadece Stok Girişi) -->
                <div id="manualPropertiesSection" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Marka</label>
                                <input type="text" class="form-control" id="operationBrand" placeholder="Örn: Honda">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Model</label>
                                <input type="text" class="form-control" id="operationModel" placeholder="Örn: EU3000i">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Beden/Özellik</label>
                                <input type="text" class="form-control" id="operationSize" placeholder="Örn: 3KW, XL, 1000W">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Özellik</label>
                                <textarea class="form-control" id="operationFeature" rows="2" placeholder="Ekipman özellikleri..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                <div class="mb-3">
                                                            <label for="operationAmount" class="form-label fw-bold" id="operationAmountLabel">Miktar</label>
                                <input type="number" class="form-control" id="operationAmount" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="operationCode" class="form-label fw-bold">Stok Kodu</label>
                            <input type="text" class="form-control" id="operationCode" placeholder="Stok kodunu girin">
                            <div id="codeValidationMessage" class="form-text"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Birim Türü Güncelleme -->
                <div class="mb-3">
                    <label for="operationUnitType" class="form-label fw-bold">Birim Türü Güncelle</label>
                    <select class="form-select" id="operationUnitType">
                        <option value="">Mevcut birim türünü koru</option>
                        <option value="adet">Adet</option>
                        <option value="metre">Metre</option>
                        <option value="kilogram">Kilogram</option>
                        <option value="litre">Litre</option>
                        <option value="paket">Paket</option>
                        <option value="kutu">Kutu</option>
                        <option value="çift">Çift</option>
                        <option value="takım">Takım</option>
                    </select>
                    <small class="form-text text-muted">Sadece değiştirmek istiyorsanız seçin</small>
                </div>
                
                <div class="mb-3">
                    <label for="operationNote" class="form-label fw-bold">Not (Opsiyonel)</label>
                    <textarea class="form-control" id="operationNote" rows="3" placeholder="İşlem hakkında not..."></textarea>
                </div>

                <!-- Resim Seçimi (Sadece Stok Girişi) -->
                <div id="operationImageOptions" style="display: none;">
                    <div id="operationImageSection">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ekipman Fotoğrafı</label>
                            <input type="file" class="form-control" id="operationPhoto" accept="image/*" multiple>
                            <small class="text-muted">Miktar kadar resim seçebilirsiniz (örn: 3 adet için 3 resim)</small>
            </div>
        </div>
    </div>
</div>

                <!-- Düzenleme Bölümü -->
                <div id="editSection" style="display:none;">
                <input type="hidden" id="editStockId">
                <div class="mb-3">
                    <label for="editStockName" class="form-label">Ekipman Adı</label>
                    <input type="text" class="form-control" id="editStockName" required>
                </div>
                <div class="mb-3">
                    <label for="editStockCode" class="form-label">Ekipman Kodu</label>
                    <input type="text" class="form-control" id="editStockCode" required>
                </div>
                    <div class="mb-3">
                        <label for="editStockUnitType" class="form-label">Birim Türü</label>
                        <select class="form-select" id="editStockUnitType" required>
                            <option value="adet">Adet</option>
                            <option value="metre">Metre</option>
                            <option value="kilogram">Kilogram</option>
                            <option value="litre">Litre</option>
                            <option value="paket">Paket</option>
                            <option value="kutu">Kutu</option>
                            <option value="çift">Çift</option>
                            <option value="takım">Takım</option>
                        </select>
                    </div>
                <div class="mb-3">
                    <label for="editStockCriticalLevel" class="form-label">Kritik Seviye</label>
                        <input type="number" class="form-control" id="editStockCriticalLevel" min="1" step="0.01" required>
                        <small class="form-text text-muted" id="editCriticalLevelHelp">Birim türüne göre kritik seviye</small>
                </div>
                <div class="mb-3">
                    <label for="editStockNote" class="form-label">Not (Opsiyonel)</label>
                    <textarea class="form-control" id="editStockNote" rows="3" placeholder="Ekipman hakkında not..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" id="btnSubmitOperation" class="btn btn-primary" onclick="submitStockOperation()">Kaydet</button>
                <button type="button" id="btnSubmitEdit" class="btn btn-primary" onclick="submitEditStock()" style="display:none;">Güncelle</button>
            </div>
        </div>
    </div>
</div>

<!-- KALDIRILDI: Ayrı düzenleme modalı (tek modal mimarisi için) -->

@endsection

<style>
.btn-add-equipment {
    background: linear-gradient(135deg, #3b7ddd 0%, #2f64b1 100%);
    border: none;
    border-radius: 12px;
    padding: 12px 24px;
    color: #ffffff !important;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(59, 125, 221, 0.3);
}

.btn-add-equipment:hover {
    background: linear-gradient(135deg, #2f64b1 0%, #1e4a8c 100%);
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 125, 221, 0.4);
}

.btn-add-equipment:active {
    transform: translateY(0);
}

.btn-add-equipment:focus {
    box-shadow: 0 0 0 0.2rem rgba(59, 125, 221, 0.25);
    outline: none;
}

.btn-add-equipment i {
    margin-right: 8px;
    font-size: 1.1rem;
}

/* Carousel dots styling */
.dot {
    height: 8px;
    width: 8px;
    margin: 0 4px;
    background-color: #bbb;
    border-radius: 50%;
    display: inline-block;
    cursor: pointer;
    transition: all 0.3s ease;
    transform: scale(1);
}

.dot.active {
    background-color: #007bff;
    transform: scale(1.2);
}

.dot:hover {
    background-color: #666;
    transform: scale(1.1);
}

/* Carousel transition effects */
.stock-codes-container {
    position: relative;
    overflow: hidden;
}

.stock-codes-slide {
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateX(0);
    opacity: 1;
}

.stock-codes-slide.slide-out-left {
    transform: translateX(-100%);
    opacity: 0;
}

.stock-codes-slide.slide-out-right {
    transform: translateX(100%);
    opacity: 0;
}

.stock-codes-slide.slide-in-left {
    transform: translateX(-100%);
    opacity: 0;
}

.stock-codes-slide.slide-in-right {
    transform: translateX(100%);
    opacity: 0;
}

/* Button hover effects */
.carousel-btn {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.carousel-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

.carousel-btn:active {
    transform: translateY(0);
}

.carousel-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

/* Page info animation */
.page-info {
    transition: all 0.3s ease;
    font-weight: 500;
}

/* Card hover effects */
.stock-code-card {
    transition: all 0.3s ease;
    transform: translateY(0);
}

.stock-code-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Responsive Stock Codes */
.stock-codes-container {
    min-height: 200px;
}

.stock-code-card .card-body {
    padding: 0.75rem;
}

.stock-code-card .card-title {
    font-size: 0.875rem;
    line-height: 1.2;
}

.stock-code-card .badge {
    font-size: 0.7rem;
    padding: 0.25em 0.5em;
}

.stock-code-card .small {
    font-size: 0.75rem;
    line-height: 1.3;
}

/* Mobile Stock Codes */
@media (max-width: 768px) {
    .stock-codes-container {
        min-height: 150px;
    }
    
    .stock-code-card .card-body {
        padding: 0.5rem;
    }
    
    .stock-code-card .card-title {
        font-size: 0.8rem;
    }
    
    .stock-code-card .badge {
        font-size: 0.65rem;
        padding: 0.2em 0.4em;
    }
    
    .stock-code-card .small {
        font-size: 0.7rem;
    }
    
    .carousel-btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .page-info {
        font-size: 0.75rem;
    }
}

@media (max-width: 576px) {
    .stock-codes-container {
        min-height: 120px;
    }
    
    .stock-code-card .card-body {
        padding: 0.4rem;
    }
    
    .stock-code-card .card-title {
        font-size: 0.75rem;
    }
    
    .stock-code-card .badge {
        font-size: 0.6rem;
        padding: 0.15em 0.3em;
    }
    
    .stock-code-card .small {
        font-size: 0.65rem;
    }
    
    .carousel-btn {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
    }
    
    .page-info {
        font-size: 0.7rem;
    }
    
    .dot {
        height: 6px;
        width: 6px;
        margin: 0 3px;
    }
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

/* İşlemler butonları arası boşluk */
.category-actions .btn {
    margin-bottom: 4px;
    margin-right: 2px;
}

.category-actions .btn:last-child {
    margin-right: 0;
}

/* İşlemler sütunu genişlik ayarı */
.category-actions {
    white-space: nowrap;
    min-width: 150px;
}

/* Responsive Table */
.table-responsive {
    border-radius: 0.5rem;
}

.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.table tbody tr:hover td {
    background-color: transparent;
}

/* Mobile Optimizations */
@media (max-width: 768px) {
    .container-fluid {
        padding: 0 15px;
    }
    
    .btn-add-equipment {
        font-size: 0.875rem;
        padding: 8px 16px;
    }
    
    .btn-add-equipment i {
        margin-right: 4px;
    }
    
    .category-actions {
        min-width: 120px;
    }
    
    .category-actions .btn {
        padding: 0.25em 0.5em;
        font-size: 0.75rem;
        margin-bottom: 2px;
        margin-right: 1px;
    }
    
    .table {
        font-size: 0.8rem;
    }
    
    .pagination {
        font-size: 1rem;
    }
    
    .pagination .page-link {
        padding: 0.5rem 0.75rem;
        font-weight: 500;
    }
    
    .pagination .page-link:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
}

@media (max-width: 576px) {
    .btn-add-equipment {
        font-size: 0.8rem;
        padding: 6px 12px;
    }
    
    .category-actions {
        min-width: 100px;
    }
    
    .category-actions .btn {
        padding: 0.2em 0.4em;
        font-size: 0.7rem;
    }
    
    .table {
        font-size: 0.75rem;
    }
    
    .badge {
        font-size: 0.65rem;
    }
    
    .progress {
        height: 8px;
    }
    
    .pagination {
        font-size: 0.9rem;
    }
    
    .pagination .page-link {
        padding: 0.4rem 0.6rem;
        font-weight: 500;
    }
    
    .pagination .page-link:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }
}

/* Tablet Optimizations */
@media (min-width: 768px) and (max-width: 1024px) {
    .category-actions {
        min-width: 180px;
    }
    
    .category-actions .btn {
        padding: 0.3em 0.6em;
        font-size: 0.75rem;
    }
}
</style>

<script>

// Stok detaylarını göster/gizle (fallback)
function toggleStockDetails(stockId) {
    const detailRow = document.getElementById(`detailRow${stockId}`);
    const codesContainer = document.getElementById(`stockCodes${stockId}`);
    
    if (detailRow.style.display === 'none') {
        // Detay satırını göster
        detailRow.style.display = 'table-row';
        
        // Eğer kodlar daha önce yüklenmemişse, yükle
        if (codesContainer.innerHTML.includes('Yükleniyor')) {
            loadStockCodes(stockId);
        }
    } else {
        // Detay satırını gizle
        detailRow.style.display = 'none';
    }
}

// Stok kodlarını yükle (fallback)
function loadStockCodes(equipmentId) {
    const codesContainer = document.getElementById(`stockCodes${equipmentId}`);
    const carouselControls = document.getElementById(`carouselControls${equipmentId}`);
    
    fetch(`/admin/stock/${equipmentId}/detailed-codes`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.codes && data.codes.length > 0) {
            // Carousel state'i sakla
            window.stockCarouselData = window.stockCarouselData || {};
            window.stockCarouselData[equipmentId] = {
                codes: data.codes,
                currentPage: 0,
                itemsPerPage: 4,
                totalPages: Math.ceil(data.codes.length / 4)
            };
            
            // İlk sayfayı göster
            showStockCodesPage(equipmentId);
            
            // Carousel kontrollerini göster
            carouselControls.style.display = 'block';
        } else {
            codesContainer.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">Bu ekipman için henüz stok kodu bulunmuyor</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error loading stock codes:', error);
        codesContainer.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                <p class="text-danger mb-0">Kodlar yüklenirken hata oluştu</p>
            </div>
        `;
    });
}

// Stok kodlarının belirli bir sayfasını göster
function showStockCodesPage(equipmentId, direction = 'none') {
    const data = window.stockCarouselData[equipmentId];
    if (!data) return;
    
    const codesContainer = document.getElementById(`stockCodes${equipmentId}`);
    const pageInfo = document.getElementById(`pageInfo${equipmentId}`);
    const prevBtn = document.getElementById(`prevBtn${equipmentId}`);
    const nextBtn = document.getElementById(`nextBtn${equipmentId}`);
    const dotsContainer = document.getElementById(`dots${equipmentId}`);
    
    const startIndex = data.currentPage * data.itemsPerPage;
    const endIndex = Math.min(startIndex + data.itemsPerPage, data.codes.length);
    const pageCodes = data.codes.slice(startIndex, endIndex);
    
    // HTML oluştur
    let html = '<div class="row g-2 stock-codes-slide">';
    pageCodes.forEach((code, index) => {
        html += `
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm h-100 stock-code-card" style="animation-delay: ${index * 0.1}s;">
                    <div class="card-body p-2 p-md-3">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start mb-2 gap-1">
                            <h6 class="card-title mb-0 text-primary small">${code.code || 'Kod Yok'}</h6>
                            <span class="badge bg-info small">${code.quantity || 0} adet</span>
                        </div>
                        <div class="small text-muted">
                            <div class="d-flex flex-column flex-sm-row gap-1">
                                <div class="flex-fill">
                            <div><strong>Marka:</strong> ${code.brand || '-'}</div>
                            <div><strong>Model:</strong> ${code.model || '-'}</div>
                                </div>
                                <div class="flex-fill">
                            <div><strong>Beden:</strong> ${code.size || '-'}</div>
                                    <div class="d-none d-sm-block"><strong>Tarih:</strong> ${new Date(code.created_at).toLocaleDateString('tr-TR')}</div>
                        </div>
                            </div>
                            ${code.feature ? `<div class="mt-1"><strong>Özellik:</strong> ${code.feature}</div>` : ''}
                            ${code.note ? `<div class="mt-1"><strong>Not:</strong> ${code.note}</div>` : ''}
                            <div class="mt-1 d-sm-none">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                ${new Date(code.created_at).toLocaleDateString('tr-TR')}
                            </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';
    
    // Animasyon efekti
    if (direction !== 'none') {
        const currentSlide = codesContainer.querySelector('.stock-codes-slide');
        if (currentSlide) {
            // Mevcut slide'ı çıkış animasyonu ile gizle
            currentSlide.classList.add(direction === 'next' ? 'slide-out-left' : 'slide-out-right');
            
            setTimeout(() => {
                codesContainer.innerHTML = html;
                const newSlide = codesContainer.querySelector('.stock-codes-slide');
                if (newSlide) {
                    // Yeni slide'ı giriş animasyonu ile göster
                    newSlide.classList.add(direction === 'next' ? 'slide-in-right' : 'slide-in-left');
                    setTimeout(() => {
                        newSlide.classList.remove('slide-in-left', 'slide-in-right');
                    }, 50);
                }
            }, 250);
        } else {
            codesContainer.innerHTML = html;
        }
    } else {
        codesContainer.innerHTML = html;
    }
    
    // Sayfa bilgisini güncelle (animasyonlu)
    pageInfo.style.opacity = '0';
    setTimeout(() => {
        pageInfo.textContent = `${data.currentPage + 1} / ${data.totalPages}`;
        pageInfo.style.opacity = '1';
    }, 100);
    
    // Butonları güncelle
    prevBtn.disabled = data.currentPage === 0;
    nextBtn.disabled = data.currentPage === data.totalPages - 1;
    
    // Dots oluştur (animasyonlu)
    let dotsHtml = '';
    for (let i = 0; i < data.totalPages; i++) {
        const isActive = i === data.currentPage ? 'active' : '';
        dotsHtml += `<span class="dot ${isActive}" onclick="goToPage(${equipmentId}, ${i})"></span>`;
    }
    dotsContainer.innerHTML = dotsHtml;
}

// Önceki sayfa
function previousPage(equipmentId) {
    const data = window.stockCarouselData[equipmentId];
    if (data && data.currentPage > 0) {
        data.currentPage--;
        showStockCodesPage(equipmentId, 'prev');
    }
}

// Sonraki sayfa
function nextPage(equipmentId) {
    const data = window.stockCarouselData[equipmentId];
    if (data && data.currentPage < data.totalPages - 1) {
        data.currentPage++;
        showStockCodesPage(equipmentId, 'next');
    }
}

// Belirli bir sayfaya git
function goToPage(equipmentId, page) {
    const data = window.stockCarouselData[equipmentId];
    if (data && page >= 0 && page < data.totalPages) {
        const direction = page > data.currentPage ? 'next' : 'prev';
        data.currentPage = page;
        showStockCodesPage(equipmentId, direction);
    }
}

// Arıza detayını görüntüle
function viewFault(stockId) {
    window.location.href = `/admin/fault/status?stock_id=${stockId}`;
}

// Ekipmanı tamir et
function repairEquipment(stockId) {
    if (confirm('Bu ekipmanı tamir edildi olarak işaretlemek istediğinizden emin misiniz?')) {
        // AJAX ile tamir işlemi
        fetch(`/admin/stock/${stockId}/repair`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Hata: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Bir hata oluştu');
        });
    }
}

// Bakım detayını görüntüle
function viewMaintenance(stockId) {
    window.location.href = `/admin/fault/status?stock_id=${stockId}&type=bakım`;
}

// Bakımı tamamla
function completeMaintenance(stockId) {
    if (confirm('Bu ekipmanın bakımını tamamlandı olarak işaretlemek istediğinizden emin misiniz?')) {
        // AJAX ile bakım tamamlama işlemi
        fetch(`/admin/stock/${stockId}/complete-maintenance`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Hata: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Bir hata oluştu');
        });
    }
}
</script>

@vite('resources/js/stock.js')
