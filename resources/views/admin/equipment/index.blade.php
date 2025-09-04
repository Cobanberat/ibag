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
            <button class="btn btn-outline-success ms-2 shadow-sm" id="importExcelBtn">
                <i class="fas fa-file-excel"></i> Excel İçe Aktar
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
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Ara..." id="searchInput">
        </div>
        <div class="col-md-3">
            <select class="form-select" id="categoryFilter">
                <option value="">Tüm Kategoriler</option>
                @foreach($categories ?? [] as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" placeholder="Ekipman Kodu" id="codeFilter">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-outline-secondary" id="clearFilters">
                <i class="fas fa-times"></i> Temizle
            </button>
        </div>
    </div>
    <div class="card shadow-lg border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="equipmentTable">
                    <thead class="table-light">
                        <tr>
                            <th>Sıra</th>
                            <th>Kod</th>
                            <th>Resim</th>
                            <th>QR Kod</th>
                            <th>Ürün Cinsi</th>
                            <th>Marka</th>
                            <th>Model</th>
                            <th>Beden</th>
                            <th>Özellik</th>
                            <th>Birim Türü</th>
                            <th>Adet/Takip</th>
                            <th>Durum</th>
                            <th>Tarih</th>
                            <th>Not</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($equipmentStocks as $index => $stock)
                            <tr data-id="{{ $stock->id }}" data-category="{{ $stock->equipment->category->id ?? '' }}">
                                <td>{{ ($equipmentStocks->currentPage() - 1) * $equipmentStocks->perPage() + $index + 1 }}</td>
                                <td class="editable-cell" data-field="code" data-id="{{ $stock->id }}">{{ $stock->code ?? '-' }}</td>
                                <td>
                                    @if($stock->equipment_image_url)
                                        <img src="{{ $stock->equipment_image_url }}" alt="Ekipman Resmi" class="img-fluid rounded" style="max-width: 50px; max-height: 50px; object-fit: cover; cursor: pointer;" onclick="showImageModal('{{ $stock->equipment_image_url }}', '{{ $stock->equipment->name ?? 'Ekipman' }}')">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        // QR kod yoksa otomatik oluştur
                                        $qrCode = $stock->qr_code;
                                        if (!$qrCode || strlen($qrCode) < 100) {
                                            $qrCode = $stock->generateQrCode();
                                        }
                                    @endphp
                                    
                                    @if($qrCode)
                                        <div class="d-flex flex-column align-items-center">
                                            <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Kod" class="img-fluid" style="max-width: 50px; max-height: 50px; cursor: pointer;" onclick="showQrModal('{{ $qrCode }}', '{{ $stock->code ?? 'QR Kod' }}')">
                                            <small class="text-muted mt-1">
                                                <a href="{{ route('admin.equipment.qr-download', $stock->id) }}" class="text-decoration-none" title="QR Kodu İndir">
                                                    <i class="fas fa-download"></i> İndir
                                                </a>
                                            </small>
                                        </div>
                                    @else
                                        <span class="text-muted">QR oluşturulamadı</span>
                                    @endif
                                </td>
                                <td class="editable-cell" data-field="equipment_name" data-id="{{ $stock->id }}">{{ $stock->equipment->name ?? '-' }}</td>
                                <td class="editable-cell" data-field="brand" data-id="{{ $stock->id }}">{{ $stock->brand ?? '-' }}</td>
                                <td class="editable-cell" data-field="model" data-id="{{ $stock->id }}">{{ $stock->model ?? '-' }}</td>
                                <td class="editable-cell" data-field="size" data-id="{{ $stock->id }}">{{ $stock->size ?? '-' }}</td>
                                <td class="editable-cell" data-field="feature" data-id="{{ $stock->id }}">{{ $stock->feature ?? '-' }}</td>
                                <td>
                                    @if($stock->equipment && $stock->equipment->unit_type)
                                        @php
                                            $unitTypes = [
                                                'adet' => 'Adet',
                                                'metre' => 'Metre',
                                                'kilogram' => 'Kilogram',
                                                'litre' => 'Litre', 
                                                'paket' => 'Paket',
                                                'kutu' => 'Kutu',
                                                'çift' => 'Çift',
                                                'takım' => 'Takım'
                                            ];
                                        @endphp
                                        <span class="badge bg-info">{{ $unitTypes[$stock->equipment->unit_type] ?? 'Adet' }}</span>
                                    @else
                                        <span class="badge bg-secondary">Adet</span>
                                    @endif
                                </td>
                                <td class="editable-cell" data-field="quantity" data-id="{{ $stock->id }}">
                                    @if($stock->equipment && $stock->equipment->individual_tracking)
                                        <span class="badge bg-info">Ayrı Takip</span>
                                        <br><small class="text-muted">Kod: {{ $stock->code ?? '-' }}</small>
                                    @else
                                        {{ $stock->quantity ?? 0 }}
                                    @endif
                                </td>
                                <td>{{ $stock->status ?? '-' }}</td>
                                <td>{{ $stock->created_at ? $stock->created_at->format('d.m.Y') : '-' }}</td>
                                <td class="editable-cell" data-field="note" data-id="{{ $stock->id }}">{{ $stock->note ?? '-' }}</td>
                                <td>
                                    <div class="d-flex gap-2">
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
                                <td colspan="15" class="text-center py-4">
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
                    <div class="mb-0">
                        @if($equipmentStocks->hasPages())
                            {{ $equipmentStocks->onEachSide(1)->links('pagination::bootstrap-5') }}
                        @endif
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Resim Modalı -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Ekipman Resmi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Ekipman Resmi" class="img-fluid rounded" style="max-width: 100%; max-height: 500px; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>
    <!-- QR Kod Modalı -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModalLabel">QR Kod</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalQrCode" src="" alt="QR Kod" class="img-fluid" style="max-width: 300px; max-height: 300px;">
                    <div class="mt-3">
                        <button type="button" class="btn btn-primary" onclick="downloadQrCode()">
                            <i class="fas fa-download"></i> QR Kodu İndir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Excel Import Modalı -->
    <div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importExcelModalLabel">
                        <i class="fas fa-file-excel text-success me-2"></i>Excel ile Ekipman İçe Aktar
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Önemli:</strong> Excel dosyası belirli formatta olmalıdır. 
                        <a href="#" id="downloadTemplateBtn" class="text-decoration-none">
                            <i class="fas fa-download me-1"></i>Şablon dosyasını indirin
                        </a>
                    </div>
                    
                    <form id="importExcelForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="excelFile" class="form-label">Excel Dosyası Seçin</label>
                            <input type="file" class="form-control" id="excelFile" name="excel_file" accept=".xlsx,.xls" required>
                            <div class="form-text">Sadece .xlsx ve .xls dosyaları kabul edilir.</div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="skipDuplicates" name="skip_duplicates" checked>
                                <label class="form-check-label" for="skipDuplicates">
                                    Mükerrer kayıtları atla (aynı kod)
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="createCategories" name="create_categories" checked>
                                <label class="form-check-label" for="createCategories">
                                    Eksik kategorileri otomatik oluştur
                                </label>
                            </div>
                        </div>
                    </form>
                    
                    <div id="importProgress" class="d-none">
                        <div class="progress mb-3">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                        </div>
                        <div class="text-center">
                            <span id="importStatus">İşlem başlatılıyor...</span>
                        </div>
                    </div>
                    
                    <div id="importPreview" class="d-none">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-eye me-2"></i>Önizleme - Eklenecek Ekipmanlar</h6>
                            <div id="previewContent"></div>
                        </div>
                    </div>
                    
                    <div id="importResults" class="d-none">
                        <div class="alert alert-success">
                            <h6><i class="fas fa-check-circle me-2"></i>İçe Aktarım Tamamlandı</h6>
                            <div id="importSummary"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-success" id="startImportBtn">
                        <i class="fas fa-upload me-2"></i>İçe Aktar
                    </button>
                </div>
            </div>
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
                    <p><strong>QR Kod:</strong> <span id="detailQrCode">-</span></p>
                    <p><strong>Resim:</strong></p>
                    <div class="text-center mb-3">
                        <img id="detailImage" src="" alt="Ekipman Resmi" class="img-fluid rounded" style="max-width: 300px; max-height: 200px; object-fit: contain;">
                    </div>
                    <p><strong>Ürün Cinsi:</strong> <span id="detailType">-</span></p>
                    <p><strong>Marka:</strong> <span id="detailBrand">-</span></p>
                    <p><strong>Model:</strong> <span id="detailModel">-</span></p>
                    <p><strong>Beden:</strong> <span id="detailSize">-</span></p>
                    <p><strong>Özellik:</strong> <span id="detailFeature">-</span></p>
                    <p><strong>Birim Türü:</strong> <span id="detailUnitType">-</span></p>
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