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
    <div class="alert alert-warning d-flex align-items-center d-none" role="alert" id="criticalStockAlert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <span id="criticalStockMessage">Kritik seviyenin altına düşen ürünler var! Lütfen stokları kontrol edin.</span>
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
                            <label class="form-label fw-bold small mb-1">
                                <i class="fas fa-list me-1 text-primary"></i>Sayfa Başına
                            </label>
                            <select class="form-select form-select-sm" id="perPageSelect">
                                <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15 kayıt</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 kayıt</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 kayıt</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 kayıt</option>
                                <option value="999999" {{ request('per_page') == 999999 ? 'selected' : '' }}>Tümünü Listele</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                            <button class="btn btn-sm btn-outline-secondary w-100" id="clearFiltersBtn">
                                <i class="fas fa-times me-1"></i>
                                <span class="d-none d-sm-inline">Temizle</span>
                            </button>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                            <button class="btn btn-sm btn-add-equipment d-flex align-items-center justify-content-center gap-2 w-100" data-bs-toggle="modal" data-bs-target="#addProductModal">
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
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
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
                                        <input type="number" class="form-control" name="quantity" min="1" max="10000" value="1" required>
                                        <small class="form-text text-muted" id="quantityHelp">Ayrı takip ekipmanları için miktar otomatik 1 olur</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Seçilen ekipmanın mevcut kodları -->
                            <div id="quickAddCodeSelect" style="display: none;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Mevcut Kod Seç (Özellikleri Kopyala)</label>
                                            <select class="form-select" id="quickAddExistingCodesSelect">
                                                <option value="">Kod seçiniz...</option>
                                            </select>
                                            <small class="form-text text-muted">Seçilen koddan marka, model, beden ve özellik bilgileri kopyalanacak</small>
                                        </div>
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
                                        <input type="number" class="form-control" name="manual_quantity" min="1" max="10000" value="1" required>
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
                                        <input type="number" class="form-control" name="critical_level" id="modalCriticalLevel" min="0" max="10000" value="3" step="0.01">
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
                <table class="table table-hover table-striped mb-0" id="stockTable">
                    <thead class="table-light">
                        <tr>
                            <th class="d-none d-md-table-cell"><input type="checkbox" id="selectAll"></th>
                            <th>Ürün</th>
                            <th class="d-none d-sm-table-cell">Kategori</th>
                            <th class="d-none d-md-table-cell">Birim Türü</th>
                            <th>Miktar</th>
                            <th class="d-none d-sm-table-cell">Kritik Seviye</th>
                            <th class="d-none d-lg-table-cell">Durum</th>
                            <th class="d-none d-md-table-cell">Takip Türü</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody id="stockTableBody">
                        <!-- Veriler JavaScript'ten yüklenecek -->
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Yükleniyor...</span>
                                </div>
                                <p class="mt-2 text-muted">Stok verileri yükleniyor...</p>
                            </td>
                        </tr>
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
                    <div class="text-muted text-center text-sm-start" id="paginationInfo">
                        <i class="fas fa-info-circle me-1"></i>
                        <span id="paginationText">Veriler yükleniyor...</span>
                    </div>
                    <nav aria-label="Sayfalama">
                        <ul class="pagination mb-0" id="pagination">
                            <!-- Pagination JavaScript'ten yüklenecek -->
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
                                <input type="number" class="form-control" id="operationAmount" min="1" max="10000" required>
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
                        <input type="number" class="form-control" id="editStockCriticalLevel" min="1" max="10000" step="0.01" required>
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

@vite('resources/js/stock.js')