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
    <div class="filter-bar mb-2">
        <select class="form-select form-select-sm border-0" id="filterCategory" style="width: 150px; background:#fff; border-radius:0.5em; border:1.5px solid #e3e6ea;">
            <option value="">Kategori Seç</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <select class="form-select form-select-sm border-0" id="filterStatus" style="width: 150px; background:#fff; border-radius:0.5em; border:1.5px solid #e3e6ea;">
            <option value="">Durum Seç</option>
            <option value="sufficient">Yeterli</option>
            <option value="low">Az Stok</option>
            <option value="empty">Tükendi</option>
        </select>
        <input type="text" class="form-control form-control-sm" id="filterSearch" style="width: 200px;" placeholder="Ürün ara...">
        <button class="btn btn-sm btn-outline-secondary" id="filterBtn"><i class="fas fa-filter"></i> Filtrele</button>
        <button class="btn btn-add-product d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="fas fa-plus"></i> Yeni Ekipman
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
                                                        <option value="{{ $equipment->id }}">{{ $equipment->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                            </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Miktar</label>
                                        <input type="number" class="form-control" name="quantity" min="1" value="1" required>
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
                                        <input type="text" class="form-control" name="name" placeholder="Örn: Jeneratör">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Kategori</label>
                                        <select class="form-select border-0" name="category_id" style="background:#fff; border-radius:0.5em; border:1.5px solid #e3e6ea;">
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
                                        <label class="form-label fw-bold">Miktar</label>
                                        <input type="number" class="form-control" name="manual_quantity" min="1" value="1">
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
                                        <label class="form-label fw-bold">Kritik Seviye</label>
                                        <input type="number" class="form-control" name="critical_level" min="1" value="3">
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
                                            <strong>Aktifse:</strong> Her ürün ayrı kod, ayrı resim (Jeneratör, bilgisayar gibi)<br>
                                            <strong>Kapalıysa:</strong> Tek kod, tek resim, miktar bazlı (Kablo, vida gibi)
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ortak Alanlar -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Konum</label>
                                    <input type="text" class="form-control" name="location" placeholder="Örn: Depo A, Raf 1">
                                </div>
                            </div>
                            <div class="col-md-6">
                        <div class="mb-3">
                                    <label class="form-label fw-bold">Durum</label>
                                    <select class="form-select border-0" name="status" style="background:#fff; border-radius:0.5em; border:1.5px solid #e3e6ea;">
                                        <option value="aktif">Aktif</option>
                                        <option value="pasif">Pasif</option>
                                        <option value="bakımda">Bakımda</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Resim Seçenekleri -->
                        <div class="mb-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="useSingleImage" checked>
                                <label class="form-check-label fw-bold" for="useSingleImage">
                                    <i class="fas fa-image me-2"></i>Tek resim kullan
                                </label>
                            </div>
                            <small class="text-muted">Aktifse tüm ekipmanlar için aynı resim kullanılır</small>
                        </div>
                        
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
    <!-- Stok Girişi/Çıkışı Modalı -->
    <div class="modal fade" id="stockInOutModal" tabindex="-1" aria-labelledby="stockInOutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockInOutModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="stockInOutForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="productId">
                        <input type="hidden" name="type">
                        <div class="mb-3">
                            <label class="form-label">Miktar</label>
                            <input type="number" class="form-control" name="amount" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" name="desc" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ürün Fotoğrafı</label>
                            <input type="file" class="form-control" name="photo" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                            <th>Miktar</th>
                            <th>Kritik Seviye</th>
                            <th>Stok Durumu</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody id="stockTableBody">
                        @forelse($stocks as $stock)
                            @php
                                $totalQuantity = $stock->total_quantity ?? 0;
                                $criticalLevel = $stock->critical_level ?? 3;
                                $isLowStock = $totalQuantity <= $criticalLevel && $totalQuantity > 0;
                                $isEmpty = $totalQuantity == 0;
                                $isSufficient = $totalQuantity > $criticalLevel;
                                
                                $rowClass = $isEmpty ? 'table-danger' : ($isLowStock ? 'table-warning' : 'table-success');
                                $percentage = $totalQuantity > 0 ? min(100, ($totalQuantity / max(1, $criticalLevel)) * 100) : 0;
                                $barClass = $isEmpty ? 'bg-danger' : ($isLowStock ? 'bg-warning' : 'bg-success');
                            @endphp
                            <tr class="{{ $rowClass }}" data-id="{{ $stock->id }}">
                                <td><input type="checkbox" class="stock-checkbox" value="{{ $stock->id }}"></td>
                                <td>
                                    <span class="fw-bold">{{ $stock->name ?? '-' }}</span>
                                    <br><small class="text-muted">{{ $stock->code ?? '-' }}</small>
                                </td>
                                <td>{{ $stock->category->name ?? '-' }}</td>
                                <td>{{ $totalQuantity }}</td>
                                <td>{{ $criticalLevel }}</td>
                                <td>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar {{ $barClass }}" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </td>
                                <td>
                                    @if($isEmpty)
                                        <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Tükendi</span>
                                    @elseif($isLowStock)
                                        <span class="badge bg-warning"><i class="fas fa-exclamation-triangle"></i> Az Stok</span>
                                    @else
                                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Yeterli</span>
                                    @endif
                                </td>
                                <td class="category-actions">
                                    <button class="btn btn-outline-secondary btn-sm" style="padding:0.45em 1em;border-radius:1.2em;" onclick="editStock({{ $stock->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-success btn-sm" style="padding:0.45em 1em;border-radius:1.2em;" onclick="stockIn({{ $stock->id }})">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm" style="padding:0.45em 1em;border-radius:1.2em;" onclick="stockOut({{ $stock->id }})">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" style="padding:0.45em 1em;border-radius:1.2em;" onclick="deleteStock({{ $stock->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
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
                            <!-- Sayfalama JavaScript ile yönetilecek -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

<!-- Stok İşlemi Modal -->
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
                
                <div class="mb-3">
                    <label for="operationTitle" class="form-label fw-bold">İşlem Türü</label>
                    <h6 id="operationTitle" class="text-primary"></h6>
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
                            <label for="operationAmount" class="form-label fw-bold">Miktar</label>
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
                
                <div class="mb-3">
                    <label for="operationNote" class="form-label fw-bold">Not (Opsiyonel)</label>
                    <textarea class="form-control" id="operationNote" rows="3" placeholder="İşlem hakkında not..."></textarea>
                </div>

                <!-- Resim Seçenekleri (Sadece Stok Girişi) -->
                <div id="operationImageOptions" style="display: none;">
                    <div class="mb-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="operationUseSingleImage" checked>
                            <label class="form-check-label fw-bold" for="operationUseSingleImage">
                                <i class="fas fa-image me-2"></i>Tek resim kullan
                            </label>
                        </div>
                        <small class="text-muted">Aktifse tüm ekipmanlar için aynı resim kullanılır</small>
                    </div>
                    
                    <div id="operationImageSection">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ekipman Fotoğrafı</label>
                            <input type="file" class="form-control" id="operationPhoto" accept="image/*" multiple>
                            <small class="text-muted">Miktar kadar resim seçebilirsiniz (örn: 3 adet için 3 resim)</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-primary" onclick="submitStockOperation()">Kaydet</button>
            </div>
        </div>
    </div>
</div>

<!-- Stok Düzenleme Modal -->
<div class="modal fade" id="editStockModal" tabindex="-1" aria-labelledby="editStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStockModalLabel">Ekipman Düzenle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                    <label for="editStockCriticalLevel" class="form-label">Kritik Seviye</label>
                    <input type="number" class="form-control" id="editStockCriticalLevel" min="1" required>
                </div>
                
                <div class="mb-3">
                    <label for="editStockNote" class="form-label">Not (Opsiyonel)</label>
                    <textarea class="form-control" id="editStockNote" rows="3" placeholder="Ekipman hakkında not..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-primary" onclick="submitEditStock()">Güncelle</button>
            </div>
        </div>
    </div>
</div>

@endsection

@vite('resources/js/stock.js')
