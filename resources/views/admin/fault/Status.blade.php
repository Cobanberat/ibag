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
    
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a></li>
            <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
            <li class="breadcrumb-item active" aria-current="page">Arıza Yönetimi</li>
        </ol>
    </nav>

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
                <div class="col-md-3">
                    <label class="form-label mb-1">Öncelik</label>
                    <select class="form-select" id="filterPriority">
                        <option value="">Tümü</option>
                        <option value="Yüksek">Yüksek</option>
                        <option value="Orta">Orta</option>
                        <option value="Düşük">Düşük</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">Durum</label>
                    <select class="form-select" id="filterStatus">
                        <option value="">Tümü</option>
                        <option value="Beklemede">Beklemede</option>
                        <option value="İşlemde">İşlemde</option>
                        <option value="Çözüldü">Çözüldü</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">Arama</label>
                    <input type="text" class="form-control" id="filterSearch" placeholder="Ekipman veya açıklama...">
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
            <button class="nav-link" id="bakim-tab" data-bs-toggle="tab" data-bs-target="#bakim" type="button" role="tab">
                <i class="fas fa-tools me-2"></i>Bakım Gerekenler
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="arizali-tab" data-bs-toggle="tab" data-bs-target="#arizali" type="button" role="tab">
                <i class="fas fa-times-circle me-2"></i>Arızalı Olanlar
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
                                    <tr data-fault-id="{{ $fault->id }}">
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

        <!-- Bakım Gerekenler Sekmesi -->
        <div class="tab-pane fade" id="bakim" role="tabpanel">
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-tools me-2"></i>Bakım Gereken Ekipmanlar</span>
                    <span class="badge bg-dark">{{ count($maintenanceItems ?? []) }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 approval-table">
                            <thead>
                                <tr>
                                    <th>Ekipman</th>
                                    <th>Kategori</th>
                                    <th>Son Bakım Tarihi</th>
                                    <th>Bakım Periyodu</th>
                                    <th>Kalan Gün</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($maintenanceItems ?? [] as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @php
                                                    // Bakım verisi için farklı yapı kontrolü
                                                    $imageUrl = null;
                                                    $equipmentName = 'Bilinmeyen';
                                                    $equipmentCode = 'Kod yok';
                                                    
                                                    // Eğer equipmentStock ilişkisi varsa
                                                    if(isset($item->equipmentStock)) {
                                                        $imageUrl = $item->equipmentStock->equipment_image_url ?? 
                                                                   ($item->equipmentStock->photo_path ?? null);
                                                        $equipmentName = $item->equipmentStock->equipment->name ?? 'Bilinmeyen';
                                                        $equipmentCode = $item->equipmentStock->code ?? 'Kod yok';
                                                    }
                                                    // Eğer doğrudan equipment ilişkisi varsa
                                                    elseif(isset($item->equipment)) {
                                                        $imageUrl = $item->equipment->equipment_image_url ?? 
                                                                   ($item->equipment->photo_path ?? null);
                                                        $equipmentName = $item->equipment->name ?? 'Bilinmeyen';
                                                        $equipmentCode = $item->code ?? 'Kod yok';
                                                    }
                                                    // Eğer doğrudan veri varsa
                                                    else {
                                                        $imageUrl = $item->equipment_image_url ?? null;
                                                        $equipmentName = $item->name ?? 'Bilinmeyen';
                                                        $equipmentCode = $item->code ?? 'Kod yok';
                                                    }
                                                @endphp
                                                @if($imageUrl)
                                                    <img src="{{ $imageUrl }}" 
                                                         alt="{{ $equipmentName }}" 
                                                         class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-warning rounded me-2 d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-tools text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $equipmentName }}</strong>
                                                    <br><small class="text-muted">{{ $equipmentCode }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if(isset($item->equipmentStock))
                                                {{ $item->equipmentStock->equipment->category->name ?? 'Kategori yok' }}
                                            @elseif(isset($item->equipment))
                                                {{ $item->equipment->category->name ?? 'Kategori yok' }}
                                            @else
                                                {{ $item->category_name ?? 'Kategori yok' }}
                                            @endif
                                        </td>
                                        <td>{{ $item->last_maintenance_date ? \Carbon\Carbon::parse($item->last_maintenance_date)->format('d.m.Y') : 'Bilinmiyor' }}</td>
                                        <td>{{ $item->maintenance_period ?? 'Belirtilmemiş' }} gün</td>
                                        <td>
                                            @php
                                                $lastMaintenance = $item->last_maintenance_date ? \Carbon\Carbon::parse($item->last_maintenance_date) : null;
                                                $period = $item->maintenance_period ?? 0;
                                                $nextMaintenance = $lastMaintenance ? $lastMaintenance->addDays($period) : null;
                                                $remainingDays = $nextMaintenance ? $nextMaintenance->diffInDays(now(), false) : null;
                                            @endphp
                                            @if($remainingDays !== null)
                                                @if($remainingDays < 0)
                                                    <span class="badge bg-danger">Gecikmiş ({{ abs($remainingDays) }} gün)</span>
                                                @elseif($remainingDays <= 7)
                                                    <span class="badge bg-warning">Yaklaşıyor ({{ $remainingDays }} gün)</span>
                                                @else
                                                    <span class="badge bg-success">{{ $remainingDays }} gün</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Hesaplanamadı</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark">Bakım Gerekiyor</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-info btn-sm" onclick="showMaintenanceDetail({{ $item->id }})" title="Detay">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-success btn-sm" onclick="showMaintenanceCompleteModal({{ $item->id }})" title="Bakım Tamamlandı">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-tools text-muted me-2"></i>
                                            Bakım gereken ekipman bulunamadı
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Arızalı Olanlar Sekmesi -->
        <div class="tab-pane fade" id="arizali" role="tabpanel">
            <div class="card mb-4">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-exclamation-triangle me-2"></i>Arızalı Ekipmanlar</span>
                    <span class="badge bg-dark">{{ count($faultyItems ?? []) }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 approval-table">
                            <thead>
                                <tr>
                                    <th>Ekipman</th>
                                    <th>Kategori</th>
                                    <th>Arıza Tarihi</th>
                                    <th>Arıza Tipi</th>
                                    <th>Öncelik</th>
                                    <th>Açıklama</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($faultyItems ?? [] as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @php
                                                    // Arıza verisi için farklı yapı kontrolü
                                                    $imageUrl = null;
                                                    $equipmentName = 'Bilinmeyen';
                                                    $equipmentCode = 'Kod yok';
                                                    
                                                    // Eğer equipmentStock ilişkisi varsa
                                                    if(isset($item->equipmentStock)) {
                                                        $imageUrl = $item->equipmentStock->equipment_image_url ?? 
                                                                   ($item->equipmentStock->photo_path ?? null);
                                                        $equipmentName = $item->equipmentStock->equipment->name ?? 'Bilinmeyen';
                                                        $equipmentCode = $item->equipmentStock->code ?? 'Kod yok';
                                                    }
                                                    // Eğer doğrudan equipment ilişkisi varsa
                                                    elseif(isset($item->equipment)) {
                                                        $imageUrl = $item->equipment->equipment_image_url ?? 
                                                                   ($item->equipment->photo_path ?? null);
                                                        $equipmentName = $item->equipment->name ?? 'Bilinmeyen';
                                                        $equipmentCode = $item->code ?? 'Kod yok';
                                                    }
                                                    // Eğer doğrudan veri varsa
                                                    else {
                                                        $imageUrl = $item->equipment_image_url ?? null;
                                                        $equipmentName = $item->name ?? 'Bilinmeyen';
                                                        $equipmentCode = $item->code ?? 'Kod yok';
                                                    }
                                                @endphp
                                                @if($imageUrl)
                                                    <img src="{{ $imageUrl }}" 
                                                         alt="{{ $equipmentName }}" 
                                                         class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-danger rounded me-2 d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-exclamation-triangle text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $equipmentName }}</strong>
                                                    <br><small class="text-muted">{{ $equipmentCode }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if(isset($item->equipmentStock))
                                                {{ $item->equipmentStock->equipment->category->name ?? 'Kategori yok' }}
                                            @elseif(isset($item->equipment))
                                                {{ $item->equipment->category->name ?? 'Kategori yok' }}
                                            @else
                                                {{ $item->category_name ?? 'Kategori yok' }}
                                            @endif
                                        </td>
                                        <td>{{ $item->created_at ? $item->created_at->format('d.m.Y H:i') : 'Bilinmiyor' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $item->type ?? 'Belirtilmemiş' }}</span>
                                        </td>
                                        <td>
                                            @if($item->priority)
                                                <span class="badge priority-{{ strtolower($item->priority) }}">
                                                    {{ $item->priority }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Belirtilmemiş</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $item->description ?? '' }}">
                                                {{ Str::limit($item->description ?? 'Açıklama yok', 50) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">Arızalı</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-info btn-sm" onclick="showFaultDetail({{ $item->id }})" title="Detay">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-success btn-sm" onclick="showFaultFixedModal({{ $item->id }})" title="Arıza Giderildi">
                                                    <i class="fas fa-wrench"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-exclamation-triangle text-muted me-2"></i>
                                            Arızalı ekipman bulunamadı
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
        // Modal fonksiyonları
        function showMaintenanceDetail(id) {
            // Bakım detay modalı
            $('#maintenanceDetailModal').modal('show');
            // Burada AJAX ile veri çekilebilir
            console.log('Bakım detay modalı açıldı - ID: ' + id);
        }

        function showFaultDetail(id) {
            // Arıza detay modalı
            $('#faultDetailModal').modal('show');
            // Burada AJAX ile veri çekilebilir
            console.log('Arıza detay modalı açıldı - ID: ' + id);
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

            // Tüm tablolarda filtreleme yap
            filterTable('pendingTableBody', category, priority, status, search);
            filterTable('bakimTableBody', category, priority, status, search);
            filterTable('arizaliTableBody', category, priority, status, search);
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
                if (priority && row.querySelector('td:nth-child(4)')?.textContent.trim() !== priority) {
                    show = false;
                }

                // Durum filtresi
                if (status && row.querySelector('td:nth-child(7)')?.textContent.trim() !== status) {
                    show = false;
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

        // Event listeners
        document.getElementById('filterCategory').addEventListener('change', applyFilters);
        document.getElementById('filterPriority').addEventListener('change', applyFilters);
        document.getElementById('filterStatus').addEventListener('change', applyFilters);
        document.getElementById('filterSearch').addEventListener('input', applyFilters);

        // Sayfa yüklendiğinde filtreleri uygula
        document.addEventListener('DOMContentLoaded', function() {
            applyFilters();
        });
    </script>
    
    <!-- Arıza Yönetimi JavaScript -->
    @vite('resources/js/fault-management.js')
@endsection
