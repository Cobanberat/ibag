@extends('layouts.admin')
@section('content')
@vite('resources/css/stock.css')

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a></li>
        <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Stok' }}</li>
    </ol>
</nav>
   <div>
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        Kritik seviyenin altına düşen ürünler var! Lütfen stokları kontrol edin.
    </div>
    <div class="filter-bar mb-2 d-flex gap-3 align-items-center">
        <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="Ekipman ara..." style="width: 250px;">
        <select class="form-select form-select-sm" id="filterCategory" style="width: 200px;">
            <option value="">Tüm Kategoriler</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <button class="btn btn-sm btn-outline-secondary" id="clearFiltersBtn"><i class="fas fa-times"></i> Temizle</button>
        <button class="btn btn-add-equipment d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="fas fa-plus"></i>
            <span>Yeni Ekipman Ekle</span>
        </button>
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
    <div class="card mt-2 p-2" style="border-radius:1.2rem;box-shadow:0 4px 24px #0d6efd11;">
            <div class="card-body p-0">
                <table class="table table-hover table-striped mb-0" id="stockTable" style="font-size:0.95em;">
                    <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Ürün</th>
                            <th>Kategori</th>
                            <th>Birim Türü</th>
                            <th>Miktar</th>
                            <th>Kritik Seviye</th>
                            <th>Takip Türü</th>
                            <th>Stok Durumu</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody id="stockTableBody">
                        @forelse($stocks as $stock)
                            <tr class="{{ $stock->row_class }}" data-id="{{ $stock->id }}">
                                <td><input type="checkbox" class="stock-checkbox" value="{{ $stock->id }}"></td>
                                <td>
                                    <span class="fw-bold">{{ $stock->name ?? '-' }}</span>
                                </td>
                                <td>{{ $stock->category->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $stock->unit_type_label }}</span>
                                </td>
                                <td>{{ $stock->total_quantity }}</td>
                                <td>{{ $stock->critical_level }}</td>
                                <td>
                                    @if($stock->individual_tracking)
                                        <span class="badge bg-primary"><i class="fas fa-user"></i> Ayrı Takip</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="fas fa-layer-group"></i> Toplu Takip</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar {{ $stock->bar_class }}" style="width: {{ $stock->percentage }}%"></div>
                                    </div>
                                </td>
                                <td>
                                    @if($stock->status === 'Arızalı')
                                        <span class="badge bg-danger"><i class="fas fa-exclamation-triangle"></i> Arızalı</span>
                                    @elseif($stock->status === 'Bakımda')
                                        <span class="badge bg-warning"><i class="fas fa-tools"></i> Bakımda</span>
                                    @elseif($stock->stock_status === 'Tükendi')
                                        <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Tükendi</span>
                                    @elseif($stock->stock_status === 'Az')
                                        <span class="badge bg-warning"><i class="fas fa-exclamation-triangle"></i> Az Stok</span>
                                    @else
                                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Yeterli</span>
                                    @endif
                                </td>
                                
                                <td class="category-actions">
                                    @if($stock->status === 'Arızalı')
                                        <button class="btn btn-outline-danger btn-sm" style="padding:0.45em 1em;border-radius:1.2em;" onclick="viewFault({{ $stock->id }})" title="Arıza Detayı">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-sm" style="padding:0.45em 1em;border-radius:1.2em;" onclick="repairEquipment({{ $stock->id }})" title="Tamir Et">
                                            <i class="fas fa-wrench"></i>
                                        </button>
                                    @elseif($stock->status === 'Bakımda')
                                        <button class="btn btn-outline-warning btn-sm" style="padding:0.45em 1em;border-radius:1.2em;" onclick="viewMaintenance({{ $stock->id }})" title="Bakım Detayı">
                                            <i class="fas fa-tools"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-sm" style="padding:0.45em 1em;border-radius:1.2em;" onclick="completeMaintenance({{ $stock->id }})" title="Bakımı Tamamla">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm" style="padding:0.45em 1em;border-radius:1.2em;" onclick="editStock({{ $stock->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-sm" style="padding:0.45em 1em;border-radius:1.2em;" onclick="stockIn({{ $stock->id }})">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button class="btn btn-outline-warning btn-sm" style="padding:0.45em 1em;border-radius:1.2em;" onclick="stockOut({{ $stock->id }})">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-outline-danger btn-sm" style="padding:0.45em 1em;border-radius:1.2em;" onclick="deleteStock({{ $stock->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-boxes fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Henüz stok bulunmuyor</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                <button class="btn btn-danger btn-sm" id="deleteSelected">
                    <i class="fas fa-trash-alt me-1"></i> Seçili Ekipmanları Sil
                </button>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-muted small">
                        Toplam {{ $pagination['total'] }} stoktan {{ ($pagination['total'] > 0 ? 1 : 0) }}-{{ min($pagination['per_page'], $pagination['total']) }} arası gösteriliyor
                    </div>
                    <nav aria-label="Sayfalama">
                        <ul class="pagination mb-0" id="pagination">
                            @if($pagination['last_page'] > 1)
                                <!-- Önceki sayfa -->
                                @if($pagination['current_page'] > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="?page={{ $pagination['current_page'] - 1 }}">
                                            <i class="fas fa-chevron-left"></i>
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
                                            <i class="fas fa-chevron-right"></i>
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
</style>

<script>

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
