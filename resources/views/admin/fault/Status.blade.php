@extends('layouts.admin')
@section('content')

    <!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center">
                    <img src="{{ asset('images/ibag-logo.svg') }}" alt="İBAG Logo" style="width: 24px; height: 24px; margin-right: 8px;">
                    <i class="fa fa-home me-1"></i> Ana Sayfa
                </a>
            </li>
        <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
        <li class="breadcrumb-item active" aria-current="page">Arıza Durumu</li>
        </ol>
    </nav>

    <style>
        .approval-tabs .nav-link {
            font-weight: 600;
            font-size: 1.08rem;
        }

        .approval-badge {
            font-size: 0.95rem;
            border-radius: 1em;
            padding: 0.3em 0.9em;
            font-weight: 600;
        }

        .approval-badge.acil {
            background: #ff4d4f;
            color: #fff;
        }

        .approval-badge.normal {
            background: #ffec3d;
            color: #333;
        }

        .approval-badge.tamam {
            background: #28a745;
            color: #fff;
        }

        .approval-badge.red {
            background: #b993d6;
            color: #fff;
        }

        .approval-table th {
            background: #f7f7fa;
            font-weight: bold;
        }

        .approval-actions .btn {
            margin-right: 0.2rem;
        }

        .approval-row.selected {
            background: #e0e7ff !important;
        }

        .status-badge {
            font-size: 0.85rem;
            padding: 0.4em 0.8em;
            border-radius: 0.5em;
        }

        .priority-yüksek { background: #dc3545; color: white; }
        .priority-normal { background: #28a745; color: white; }
        .priority-acil { background: #dc3545; color: white; }
        
        /* Fallback for different priority values */
        .priority-high { background: #dc3545; color: white; }
        .priority-medium { background: #ffc107; color: black; }
        .priority-low { background: #28a745; color: white; }

        .status-pending { background: #6c757d; color: white; }
        .status-beklemede { background: #6c757d; color: white; }
        .status-in-progress { background: #17a2b8; color: white; }
        .status-işlemde { background: #17a2b8; color: white; }
        .status-resolved { background: #28a745; color: white; }
        .status-çözüldü { background: #28a745; color: white; }
        .status-cancelled { background: #dc3545; color: white; }
        .status-iptal { background: #dc3545; color: white; }
    </style>
    

    <!-- İstatistik Kartları -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['bekleyen'] ?? 0 }}</h4>
                            <small>Bekleyen İşlemler</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['bakim'] ?? 0 }}</h4>
                            <small>Bakım Gerekenler</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-tools fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['arizali'] ?? 0 }}</h4>
                            <small>Arızalı Olanlar</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['cozulen'] ?? 0 }}</h4>
                            <small>Çözülen İşlemler</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtreler -->
    <div class="card mb-3 shadow-sm border-0">
        <div class="card-body py-3">
            <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                    <label class="form-label mb-1">Kategori</label>
                    <select class="form-select" id="filterCategory">
                                <option value="">Tüm Kategoriler</option>
                                @foreach($categories ?? [] as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                    <label class="form-label mb-1">Öncelik</label>
                    <select class="form-select" id="filterPriority">
                        <option value="">Tümü</option>
                        <option value="acil">Acil</option>
                        <option value="yüksek">Yüksek</option>
                        <option value="normal">Normal</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                    <label class="form-label mb-1">Durum</label>
                    <select class="form-select" id="filterStatus">
                        <option value="">Tümü</option>
                                <option value="beklemede">Beklemede</option>
                                <option value="işlemde">İşlemde</option>
                                <option value="giderildi">Çözüldü</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                    <label class="form-label mb-1">Arama</label>
                    <input type="text" class="form-control" id="filterSearch" placeholder="Ekipman veya açıklama...">
                </div>
                        <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-secondary w-100" id="clearFilters" title="Filtreleri Temizle">
                        <i class="fas fa-times me-1"></i>Temizle
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sekmeler -->
    <ul class="nav nav-tabs approval-tabs mb-3" id="approvalTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="gereken-tab" data-bs-toggle="tab" data-bs-target="#gereken" type="button" role="tab">
                <i class="fas fa-exclamation-circle me-2"></i>Gereken İşlemler
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="gecmis-tab" data-bs-toggle="tab" data-bs-target="#gecmis" type="button" role="tab">
                <i class="fas fa-history me-2"></i>Geçmiş İşlemler
            </button>
        </li>
    </ul>

    <div class="tab-content" id="approvalTabContent">
        <!-- Gereken İşlemler Sekmesi -->
        <div class="tab-pane fade show active" id="gereken" role="tabpanel">
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-exclamation-triangle me-2"></i>Gereken İşlemler</span>
                    <span class="badge bg-dark">{{ count($faults ?? []) }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 approval-table" id="pendingTable">
                            <thead>
                                <tr>
                                    <th>Ekipman</th>
                                    <th>Kategori</th>
                                    <th>Bildirim Tarihi</th>
                                    <th>Öncelik</th>
                                    <th>Açıklama</th>
                                    <th>Bildiren</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody id="pendingTableBody">
                                @forelse($faults ?? [] as $fault)
                                    <tr data-fault-id="{{ $fault->id }}" data-equipment-stock-id="{{ $fault->equipment_stock_id }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($fault->equipmentStock && $fault->equipmentStock->photo_path)
                                                    <img src="{{ asset('storage/' . $fault->equipmentStock->photo_path) }}" 
                                                         alt="{{ $fault->equipmentStock->equipment->name ?? 'Ekipman' }}" 
                                                         class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-image text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $fault->equipmentStock->equipment->name ?? 'Bilinmeyen' }}</strong>
                                                    <br><small class="text-muted">{{ $fault->equipmentStock->code ?? 'Kod yok' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $fault->equipmentStock->equipment->category->name ?? 'Kategori yok' }}</td>
                                        <td>{{ $fault->reported_date ? \Carbon\Carbon::parse($fault->reported_date)->format('d.m.Y H:i') : 'Tarih yok' }}</td>
                                        <td>
                                            <span class="badge priority-{{ strtolower($fault->priority) }}">
                                                {{ $fault->priority }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $fault->description }}">
                                                {{ Str::limit($fault->description, 50) }}
                                            </div>
                                        </td>
                                        <td>{{ $fault->reporter->name ?? 'Bilinmeyen' }}</td>
                                        <td>
                                            <span class="badge status-{{ strtolower(str_replace(' ', '-', $fault->status)) }}">
                                                {{ $fault->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-info btn-sm" onclick="showFaultDetail({{ $fault->id }})" title="Detay">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                    <button class="btn btn-success btn-sm" onclick="showResolveModal({{ $fault->id }})" title="Çöz">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-info-circle text-muted me-2"></i>
                                            Bekleyen işlem bulunamadı
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- Geçmiş İşlemler Sekmesi -->
        <div class="tab-pane fade" id="gecmis" role="tabpanel">
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-history me-2"></i>Geçmiş İşlemler</span>
                    <span class="badge bg-dark">{{ count($resolvedFaults ?? []) }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 approval-table">
                            <thead>
                                <tr>
                                    <th>Ekipman</th>
                                    <th>İşlem Tipi</th>
                                    <th>Bildirim Tarihi</th>
                                    <th>Çözüm Tarihi</th>
                                    <th>Çözen</th>
                                    <th>Sonuç</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($resolvedFaults ?? [] as $fault)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($fault->equipmentStock && $fault->equipmentStock->photo_path)
                                                    <img src="{{ asset('storage/' . $fault->equipmentStock->photo_path) }}" 
                                                         alt="{{ $fault->equipmentStock->equipment->name ?? 'Ekipman' }}" 
                                                         class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-image text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $fault->equipmentStock->equipment->name ?? 'Bilinmeyen' }}</strong>
                                                    <br><small class="text-muted">{{ $fault->equipmentStock->code ?? 'Kod yok' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $fault->type }}</span>
                                        </td>
                                        <td>{{ $fault->reported_date ? \Carbon\Carbon::parse($fault->reported_date)->format('d.m.Y') : 'Tarih yok' }}</td>
                                        <td>{{ $fault->resolved_date ? \Carbon\Carbon::parse($fault->resolved_date)->format('d.m.Y') : 'Tarih yok' }}</td>
                                        <td>{{ $fault->resolver->name ?? 'Bilinmeyen' }}</td>
                                        <td>
                                            <span class="badge bg-success">Çözüldü</span>
                                        </td>
                                        <td>
                                                <button class="btn btn-outline-info btn-sm" onclick="showResolvedFaultDetail({{ $fault->id }})" title="Detay">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-info-circle text-muted me-2"></i>
                                            Geçmiş işlem bulunamadı
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Modaller -->
@include('admin.fault.partials.fault-detail-modal')
@include('admin.fault.partials.resolve-fault-modal')
    @include('admin.fault.partials.update-status-modal')
@include('admin.fault.partials.maintenance-complete-modal')
@include('admin.fault.partials.fault-fixed-modal')
@include('admin.fault.partials.resolved-fault-detail-modal')

    <script>
        // Mesaj gösterme fonksiyonları
        function showSuccessMessage(message) {
            // Toast bildirimi göster
            const toastContainer = getOrCreateToastContainer();
            const toastId = 'toast-' + Date.now();
            
            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-check-circle me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            
            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
            
            // Toast kapandıktan sonra DOM'dan kaldır
            toastElement.addEventListener('hidden.bs.toast', function() {
                toastElement.remove();
            });
        }

        function showErrorMessage(message) {
            // Toast bildirimi göster
            const toastContainer = getOrCreateToastContainer();
            const toastId = 'toast-' + Date.now();
            
            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            
            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
            
            // Toast kapandıktan sonra DOM'dan kaldır
            toastElement.addEventListener('hidden.bs.toast', function() {
                toastElement.remove();
            });
        }

        function getOrCreateToastContainer() {
            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                container.className = 'toast-container position-fixed top-0 end-0 p-3';
                container.style.zIndex = '9999';
                document.body.appendChild(container);
            }
            return container;
        }

        // Modal fonksiyonları
        function showMaintenanceDetail(id) {
            // Bakım detay modalı
                    $('#maintenanceDetailModal').modal('show');
            // Burada AJAX ile veri çekilebilir
            console.log('Bakım detay modalı açıldı - ID: ' + id);
    }

        function showFaultDetail(id) {
            // AJAX ile arıza detaylarını getir
            const url = `/admin/fault/${id}`;
            console.log('Fetching fault detail from:', url);
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Modal içeriğini doldur
                        populateFaultDetailModal(data.fault);
                        $('#faultDetailModal').modal('show');
                    } else {
                        console.error('Arıza detayları alınamadı:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Hata oluştu:', error);
                });
        }

    function populateFaultDetailModal(fault) {
        // Modal başlığını güncelle
        document.getElementById('faultDetailModalLabel').textContent = `Arıza Detayı - ${fault.equipment_name}`;
        
        // Modal içeriğini doldur
        const modalBody = document.querySelector('#faultDetailModal .modal-body');
        if (modalBody) {
            modalBody.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-cube me-2"></i>Ekipman Bilgileri</h6>
                        <p><strong>Adı:</strong> ${fault.equipment_name}</p>
                        <p><strong>Kodu:</strong> ${fault.equipment_code}</p>
                        <p><strong>Kategori:</strong> ${fault.category_name}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-info-circle me-2"></i>Arıza Bilgileri</h6>
                        <p><strong>Tip:</strong> <span class="badge bg-danger">${fault.type}</span></p>
                        <p><strong>Öncelik:</strong> <span class="badge priority-${fault.priority.toLowerCase()}">${fault.priority}</span></p>
                        <p><strong>Durum:</strong> <span class="badge status-${fault.status.toLowerCase().replace(' ', '-')}">${fault.status}</span></p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6><i class="fas fa-file-text me-2"></i>Açıklama</h6>
                        <p class="border p-3 rounded">${fault.description}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6><i class="fas fa-user me-2"></i>Bildiren</h6>
                        <p>${fault.reporter_name}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-calendar me-2"></i>Bildirim Tarihi</h6>
                        <p>${fault.reported_date}</p>
                    </div>
                </div>
                ${fault.photo_path ? `
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6><i class="fas fa-image me-2"></i>Fotoğraf</h6>
                            <img src="/storage/${fault.photo_path}" alt="Arıza Fotoğrafı" class="img-fluid rounded border" style="max-height: 300px;">
                        </div>
                    </div>
                ` : ''}
            `;
        }
    }

        function showResolveModal(id) {
            console.log('Opening resolve modal for fault ID:', id);
            
            // Hidden field'ları doldur
            const row = document.querySelector(`tr[data-fault-id="${id}"]`);
            if (row) {
                const equipmentStockId = row.getAttribute('data-equipment-stock-id');
                console.log('Equipment Stock ID:', equipmentStockId);
                
                // Hidden field'ları doldur
                const faultIdField = document.getElementById('resolveFaultId');
                const equipmentStockIdField = document.getElementById('resolveEquipmentStockId');
                
                if (faultIdField) faultIdField.value = id;
                if (equipmentStockIdField) equipmentStockIdField.value = equipmentStockId;
                
                console.log('Fault ID field value:', faultIdField ? faultIdField.value : 'Field not found');
                console.log('Equipment Stock ID field value:', equipmentStockIdField ? equipmentStockIdField.value : 'Field not found');
            } else {
                console.error('Row not found for fault ID:', id);
            }
            
            $('#resolveFaultModal').modal('show');
        }

        function showResolvedFaultDetail(id) {
            // AJAX ile çözülen arıza detaylarını getir
            const url = `/admin/fault/resolved/${id}`;
            console.log('Fetching resolved fault detail from:', url);
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Modal içeriğini doldur
                        populateResolvedFaultDetailModal(data.fault);
                        $('#resolvedFaultDetailModal').modal('show');
                    } else {
                        console.error('Çözülen arıza detayları alınamadı:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Hata oluştu:', error);
                });
        }

    function populateResolvedFaultDetailModal(fault) {
        // Modal başlığını güncelle
        document.getElementById('resolvedFaultDetailModalLabel').textContent = `Çözülen Arıza Detayı - ${fault.equipment_name}`;
        
        // Modal içeriğini doldur
        const modalBody = document.querySelector('#resolvedFaultDetailModal .modal-body');
        if (modalBody) {
            modalBody.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-cube me-2"></i>Ekipman Bilgileri</h6>
                        <p><strong>Adı:</strong> ${fault.equipment_name}</p>
                        <p><strong>Kodu:</strong> ${fault.equipment_code}</p>
                        <p><strong>Kategori:</strong> ${fault.category_name}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-check-circle me-2"></i>Çözüm Bilgileri</h6>
                        <p><strong>Çözüm Tarihi:</strong> ${fault.resolved_date}</p>
                        <p><strong>Çözen:</strong> ${fault.resolver_name}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6><i class="fas fa-file-text me-2"></i>Çözüm Açıklaması</h6>
                        <p class="border p-3 rounded">${fault.resolution_note}</p>
                    </div>
                </div>
                ${fault.resolved_photo_path ? `
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6><i class="fas fa-image me-2"></i>Çözüm Fotoğrafı</h6>
                            <img src="/storage/${fault.resolved_photo_path}" alt="Çözüm Fotoğrafı" class="img-fluid rounded border" style="max-height: 300px;">
                        </div>
                    </div>
                ` : ''}
            `;
        }
    }

    function showMaintenanceCompleteModal(id) {
            // Bakım tamamla modalı
            $('#maintenanceCompleteModal').modal('show');
            // Burada AJAX ile veri çekilebilir
            console.log('Bakım tamamla modalı açıldı - ID: ' + id);
        }

        function showFaultFixedModal(id) {
            // Arıza giderildi modalı
            $('#faultFixedModal').modal('show');
            // Burada AJAX ile veri çekilebilir
            console.log('Arıza giderildi modalı açıldı - ID: ' + id);
        }

        // Filtreleme fonksiyonları
        function applyFilters() {
            const category = document.getElementById('filterCategory').value;
            const priority = document.getElementById('filterPriority').value;
            const status = document.getElementById('filterStatus').value;
            const search = document.getElementById('filterSearch').value.toLowerCase();

            // Aktif sekmedeki tabloyu filtrele
            const activeTab = document.querySelector('.nav-link.active');
            if (activeTab) {
                const targetId = activeTab.getAttribute('data-bs-target');
                if (targetId === '#gereken') {
                    filterTable('pendingTableBody', category, priority, status, search);
                } else if (targetId === '#gecmis') {
                    filterHistoryTable(category, priority, status, search);
                }
            }
        }

        function filterTable(tableBodyId, category, priority, status, search) {
            const tbody = document.getElementById(tableBodyId);
            if (!tbody) return;

            const rows = tbody.querySelectorAll('tr');
            rows.forEach(row => {
                let show = true;

                // Kategori filtresi
                if (category && row.querySelector('td:nth-child(2)')?.textContent.trim() !== category) {
                    show = false;
                }

                // Öncelik filtresi
                if (priority) {
                    const priorityText = row.querySelector('td:nth-child(4)')?.textContent.trim().toLowerCase();
                    const priorityMap = {
                        'acil': 'acil',
                        'yüksek': 'yüksek', 
                        'normal': 'normal'
                    };
                    if (priorityText !== priorityMap[priority]) {
                        show = false;
                    }
                }

                // Durum filtresi
                if (status) {
                    const statusText = row.querySelector('td:nth-child(7)')?.textContent.trim().toLowerCase();
                    const statusMap = {
                        'beklemede': 'beklemede',
                        'işlemde': 'işlemde',
                        'giderildi': 'çözüldü'
                    };
                    if (statusText !== statusMap[status]) {
                        show = false;
                    }
                }

                // Arama filtresi
                if (search) {
                    const equipmentName = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
                    const description = row.querySelector('td:nth-child(5)')?.textContent.toLowerCase() || '';
                    if (!equipmentName.includes(search) && !description.includes(search)) {
                        show = false;
                    }
                }

                row.style.display = show ? '' : 'none';
            });
        }

        function filterHistoryTable(category, priority, status, search) {
            // Geçmiş işlemler tablosunu bul
            const historyTable = document.querySelector('#gecmis .table tbody');
            if (!historyTable) return;

            const rows = historyTable.querySelectorAll('tr');
            rows.forEach(row => {
                let show = true;

                // Kategori filtresi - ekipman adında kategori arama
                if (category) {
                    const equipmentName = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
                    if (!equipmentName.includes(category.toLowerCase())) {
                        show = false;
                    }
                }

                // Öncelik filtresi - geçmiş tabloda öncelik bilgisi yok, bu yüzden atla
                // Priority filter is skipped for history table as it doesn't have priority column

                // Durum filtresi (6. sütun - Sonuç)
                if (status) {
                    const statusText = row.querySelector('td:nth-child(6)')?.textContent.trim().toLowerCase();
                    const statusMap = {
                        'beklemede': 'beklemede',
                        'işlemde': 'işlemde',
                        'giderildi': 'çözüldü'
                    };
                    if (statusText !== statusMap[status]) {
                        show = false;
                    }
                }

                // Arama filtresi - ekipman adı ve işlem tipinde arama
                if (search) {
                    const equipmentName = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
                    const processType = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
                    const reporter = row.querySelector('td:nth-child(5)')?.textContent.toLowerCase() || '';
                    
                    if (!equipmentName.includes(search) && 
                        !processType.includes(search) && 
                        !reporter.includes(search)) {
                        show = false;
                    }
                }

                row.style.display = show ? '' : 'none';
            });
        }

        // Temizle butonu fonksiyonu
        function clearFilters() {
            document.getElementById('filterCategory').value = '';
            document.getElementById('filterPriority').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterSearch').value = '';
            applyFilters();
        }

        // Event listeners
        document.getElementById('filterCategory').addEventListener('change', applyFilters);
        document.getElementById('filterPriority').addEventListener('change', applyFilters);
        document.getElementById('filterStatus').addEventListener('change', applyFilters);
        document.getElementById('filterSearch').addEventListener('input', applyFilters);
        document.getElementById('clearFilters').addEventListener('click', clearFilters);

        // Tab switching event listeners
        document.querySelectorAll('.nav-link[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function() {
                // Tab değiştiğinde filtreleri uygula
                applyFilters();
            });
        });

    // Form submit handlers
        function handleResolveFault(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            
            console.log('Form action:', form.action);
            console.log('Form data:', Object.fromEntries(formData));
            console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            // Hidden field'ları kontrol et
            const faultId = document.getElementById('resolveFaultId').value;
            const equipmentStockId = document.getElementById('resolveEquipmentStockId').value;
            console.log('Hidden fields - Fault ID:', faultId, 'Equipment Stock ID:', equipmentStockId);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Başarılı mesajı göster
                    showSuccessMessage(data.message);
                    $('#resolveFaultModal').modal('hide');
                    // Sayfayı yenile
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showErrorMessage(data.message || 'Bir hata oluştu');
                }
            })
            .catch(error => {
                console.error('Hata oluştu:', error);
                showErrorMessage('Bağlantı hatası: ' + error.message);
            });
        }

        function handleMaintenanceComplete(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            
            console.log('Form action:', form.action);
            console.log('Form data:', Object.fromEntries(formData));
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    showSuccessMessage(data.message);
                    $('#maintenanceCompleteModal').modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showErrorMessage(data.message || 'Bir hata oluştu');
                }
            })
            .catch(error => {
                console.error('Hata oluştu:', error);
                showErrorMessage('Bağlantı hatası: ' + error.message);
            });
        }

        function handleFaultFixed(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            
            console.log('Form action:', form.action);
            console.log('Form data:', Object.fromEntries(formData));
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    showSuccessMessage(data.message);
                    $('#faultFixedModal').modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showErrorMessage(data.message || 'Bir hata oluştu');
                }
            })
            .catch(error => {
                console.error('Hata oluştu:', error);
                showErrorMessage('Bağlantı hatası: ' + error.message);
            });
        }

        // Sayfa yüklendiğinde filtreleri uygula
        document.addEventListener('DOMContentLoaded', function() {
            applyFilters();
        
        // Form submit event'lerini dinle
        const resolveForm = document.getElementById('resolveFaultForm');
        if (resolveForm) {
            resolveForm.addEventListener('submit', handleResolveFault);
        }
        
        const maintenanceForm = document.getElementById('maintenanceCompleteForm');
        if (maintenanceForm) {
            maintenanceForm.addEventListener('submit', handleMaintenanceComplete);
        }
        
        const faultFixedForm = document.getElementById('faultFixedForm');
        if (faultFixedForm) {
            faultFixedForm.addEventListener('submit', handleFaultFixed);
        }
    });
</script>

    <!-- Arıza Yönetimi JavaScript -->
@endsection
