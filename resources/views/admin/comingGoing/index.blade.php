@extends('layouts.admin')
@section('content')
@vite(['resources/css/comingGoing.css'])

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
        <li class="breadcrumb-item">
            <a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a>
        </li>
        <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Zimmet Takip' }}</li>
    </ol>
</nav>

<div class="animated-title">
    <i class="fas fa-clipboard-list"></i> Zimmet Alma & Teslim Etme Takip Sistemi
</div>

<!-- Özet Kartları -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $gidenAssignments->count() }}</h4>
                        <small>Aktif Zimmet</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-sign-out-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-gradient-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $gelenAssignments->count() }}</h4>
                        <small>Teslim Edilen</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-sign-in-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-gradient-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $gidenAssignments->where('status', 0)->count() }}</h4>
                        <small>Bekleyen</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-gradient-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $gidenAssignments->where('status', 1)->count() }}</h4>
                        <small>Tamamlanan</small>
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
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <label class="form-label fw-bold">Durum Filtresi</label>
                <select class="form-select" id="statusFilter">
                    <option value="">Tümü</option>
                    <option value="0">Bekleyen</option>
                    <option value="1">Tamamlanan</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Kullanıcı Ara</label>
                <input type="text" class="form-control" id="userFilter" placeholder="Kullanıcı adı...">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Tarih Aralığı</label>
                <select class="form-select" id="dateFilter">
                    <option value="">Tümü</option>
                    <option value="today">Bugün</option>
                    <option value="week">Bu Hafta</option>
                    <option value="month">Bu Ay</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">&nbsp;</label>
                <div>
                    <button class="btn btn-primary" id="applyFilters">
                        <i class="fas fa-filter"></i> Filtrele
                    </button>
                    <button class="btn btn-outline-secondary" id="clearFilters">
                        <i class="fas fa-times"></i> Temizle
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sekmeler -->
<ul class="nav nav-tabs approval-tabs mb-3" id="comingGoingTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="giden-tab" data-bs-toggle="tab" data-bs-target="#gidenTab" type="button" role="tab">
            <i class="fas fa-sign-out-alt me-2"></i>Zimmet Alınanlar ({{ $gidenAssignments->count() }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="gelen-tab" data-bs-toggle="tab" data-bs-target="#gelenTab" type="button" role="tab">
            <i class="fas fa-sign-in-alt me-2"></i>Teslim Edilenler ({{ $gelenAssignments->count() }})
        </button>
    </li>
</ul>

<div class="tab-content" id="comingGoingTabContent">
    <!-- Zimmet Alınanlar Tab -->
    <div class="tab-pane fade show active" id="gidenTab" role="tabpanel">
        <div class="col-md-12 mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">
                    <i class="fas fa-sign-out-alt text-primary me-2"></i>Zimmet Alınan Ekipmanlar
                </h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="exportToExcel('giden')">
                        <i class="fas fa-file-excel me-1"></i>Excel
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="printTable('giden')">
                        <i class="fas fa-print me-1"></i>Yazdır
                    </button>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="gidenTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Kullanıcı</th>
                            <th>Tarih</th>
                            <th>Ekipman Sayısı</th>
                            <th>Durum</th>
                            <th>Not</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gidenAssignments as $assignment)
                        <tr class="assignment-row" data-status="{{ $assignment->status }}" data-user="{{ strtolower($assignment->user->name ?? '') }}" data-date="{{ $assignment->created_at->format('Y-m-d') }}">
                            <td>
                                <span class="badge bg-secondary">#{{ $assignment->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        {{ substr($assignment->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $assignment->user->name ?? 'Bilinmiyor' }}</strong>
                                        <br><small class="text-muted">{{ $assignment->user->email ?? 'Email yok' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $assignment->created_at->format('d.m.Y') }}</strong>
                                    <br><small class="text-muted">{{ $assignment->created_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $assignment->items->count() }} ekipman</span>
                            </td>
                            <td>
                                @if($assignment->status == 0)
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>Bekliyor
                                </span>
                                @else
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Tamamlandı
                                </span>
                                @endif
                            </td>
                            <td>
                                @if($assignment->note)
                                <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $assignment->note }}">
                                    {{ Str::limit($assignment->note, 30) }}
                                </span>
                                            @else
                                <span class="text-muted">Not yok</span>
                                            @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModalGiden{{ $assignment->id }}" title="Detay Görüntüle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($assignment->status == 0)
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#finishModalGiden{{ $assignment->id }}" title="Teslim Et">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                                @endif
                                    <button class="btn btn-outline-secondary btn-sm" onclick="showTimeline({{ $assignment->id }})" title="Zaman Çizelgesi">
                                        <i class="fas fa-history"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Henüz zimmet alınan ekipman bulunmuyor</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Teslim Edilenler Tab -->
    <div class="tab-pane fade" id="gelenTab" role="tabpanel">
        <div class="col-md-12 mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">
                    <i class="fas fa-sign-in-alt text-success me-2"></i>Teslim Edilen Ekipmanlar
                </h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-success btn-sm" onclick="exportToExcel('gelen')">
                        <i class="fas fa-file-excel me-1"></i>Excel
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="printTable('gelen')">
                        <i class="fas fa-print me-1"></i>Yazdır
                    </button>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="gelenTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Kullanıcı</th>
                            <th>Zimmet Tarihi</th>
                            <th>Teslim Tarihi</th>
                            <th>Ekipman Sayısı</th>
                            <th>Durum</th>
                            <th>Arıza Notu</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gelenAssignments as $assignment)
                        <tr class="assignment-row" data-status="{{ $assignment->status }}" data-user="{{ strtolower($assignment->user->name ?? '') }}" data-date="{{ $assignment->created_at->format('Y-m-d') }}">
                            <td>
                                <span class="badge bg-secondary">#{{ $assignment->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        {{ substr($assignment->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $assignment->user->name ?? 'Bilinmiyor' }}</strong>
                                        <br><small class="text-muted">{{ $assignment->user->email ?? 'Email yok' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $assignment->created_at->format('d.m.Y') }}</strong>
                                    <br><small class="text-muted">{{ $assignment->created_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $assignment->updated_at->format('d.m.Y') }}</strong>
                                    <br><small class="text-muted">{{ $assignment->updated_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-success">{{ $assignment->items->count() }} ekipman</span>
                            </td>
                            <td>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Teslim Edildi
                                </span>
                            </td>
                            <td>
                                @if($assignment->damage_note)
                                <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $assignment->damage_note }}">
                                    <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                                    {{ Str::limit($assignment->damage_note, 30) }}
                                </span>
                                @else
                                <span class="text-success">
                                    <i class="fas fa-check text-success me-1"></i>Sağlam
                                </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModalGelen{{ $assignment->id }}" title="Detay Görüntüle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="showTimeline({{ $assignment->id }})" title="Zaman Çizelgesi">
                                        <i class="fas fa-history"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Henüz teslim edilen ekipman bulunmuyor</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Giden Detay Modal -->
@foreach($gidenAssignments as $assignment)
<div class="modal fade" id="detailModalGiden{{ $assignment->id }}" tabindex="-1" aria-labelledby="detailModalGidenLabel{{ $assignment->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
                                <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="detailModalGidenLabel{{ $assignment->id }}">
                    <i class="fas fa-clipboard-list me-2"></i>Zimmet Detayı #{{ $assignment->id }}
                </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                    </div>
                                    <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-user me-2"></i>Kullanıcı Bilgileri
                        </h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p><strong>Ad Soyad:</strong> {{ $assignment->user->name ?? 'Bilinmiyor' }}</p>
                                <p><strong>Email:</strong> {{ $assignment->user->email ?? 'Email yok' }}</p>
                                <p><strong>Zimmet Tarihi:</strong> {{ $assignment->created_at->format('d.m.Y H:i') }}</p>
                                        <p><strong>Durum:</strong>
                                            @if($assignment->status == 0)
                                    <span class="badge bg-warning">Bekliyor</span>
                                            @else
                                            <span class="badge bg-success">Tamamlandı</span>
                                            @endif
                                        </p>
                                @if($assignment->note)
                                <p><strong>Not:</strong> {{ $assignment->note }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-success mb-3">
                            <i class="fas fa-boxes me-2"></i>Ekipman Özeti
                        </h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p><strong>Toplam Ekipman:</strong> {{ $assignment->items->count() }} adet</p>
                                <p><strong>Tek Takip:</strong> {{ $assignment->items->where('equipment.individual_tracking', true)->count() }} adet</p>
                                <p><strong>Çoklu Takip:</strong> {{ $assignment->items->where('equipment.individual_tracking', false)->count() }} adet</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <h6 class="fw-bold text-info mb-3">
                    <i class="fas fa-list me-2"></i>Ekipman Detayları
                </h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Ekipman</th>
                                <th>Kategori</th>
                                <th>Takip Türü</th>
                                <th>Miktar</th>
                                <th>Fotoğraf</th>
                                <th>Stok Durumu</th>
                            </tr>
                        </thead>
                        <tbody>
                                            @foreach($assignment->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->equipment->name ?? 'Bilinmeyen Ekipman' }}</strong>
                                    @if($item->equipment->code)
                                    <br><small class="text-muted">Kod: {{ $item->equipment->code }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $item->equipment->category->name ?? 'Kategori yok' }}</span>
                                </td>
                                <td>
                                    @if($item->equipment->individual_tracking)
                                    <span class="badge bg-info">
                                        <i class="fas fa-barcode me-1"></i>Tek Takip
                                    </span>
                                    @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-layer-group me-1"></i>Çoklu Takip
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $item->quantity ?? 1 }} adet</span>
                                </td>
                                <td>
                                                @if($item->photo_path)
                                    <button class="btn btn-sm btn-outline-primary" onclick="showImageModal('{{ asset('storage/' . $item->photo_path) }}', '{{ $item->equipment->name ?? 'Ekipman' }} - Zimmet Fotoğrafı')">
                                        <i class="fas fa-image me-1"></i>Görüntüle
                                    </button>
                                    @else
                                    <span class="text-muted">Fotoğraf yok</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->equipment->individual_tracking)
                                    <span class="badge bg-warning">Kullanımda</span>
                                    @else
                                    <span class="badge bg-success">Stoktan Düşüldü</span>
                                                @endif
                                </td>
                            </tr>
                                            @endforeach
                        </tbody>
                    </table>
                </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                @if($assignment->status == 0)
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#finishModalGiden{{ $assignment->id }}" data-bs-dismiss="modal">
                    <i class="fas fa-check-circle me-2"></i>Teslim Et
                </button>
                @endif
                                    </div>
                                </div>
                            </div>
                        </div>
@endforeach

<!-- Giden Finish Modal -->
@foreach($gidenAssignments as $assignment)
<div class="modal fade" id="finishModalGiden{{ $assignment->id }}" tabindex="-1" aria-labelledby="finishModalGidenLabel{{ $assignment->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
            <div class="modal-header bg-gradient-success text-white">
                <h5 class="modal-title" id="finishModalGidenLabel{{ $assignment->id }}">
                    <i class="fas fa-check-circle me-2"></i>Ekipman Teslim İşlemi
                </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                    </div>
                                    <form action="{{ route('assignments.finish', $assignment->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Bilgi:</strong> Bu işlem zimmeti tamamlayacak ve ekipmanları teslim edilenler listesine ekleyecektir.
                    </div>
                    
                    <h6 class="fw-bold mb-3">Teslim Edilecek Ekipmanlar:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Ekipman</th>
                                    <th>Miktar</th>
                                    <th>Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                                @foreach($assignment->items as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->equipment->name ?? 'Bilinmeyen Ekipman' }}</strong>
                                        @if($item->equipment->code)
                                        <br><small class="text-muted">Kod: {{ $item->equipment->code }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $item->quantity ?? 1 }} adet</span>
                                    </td>
                                    <td>
                                        @if($item->equipment->individual_tracking)
                                        <span class="badge bg-warning">Kullanımda</span>
                                        @else
                                        <span class="badge bg-success">Stoktan Düşüldü</span>
                                                    @endif
                                    </td>
                                </tr>
                                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                                            <div class="mt-3">
                        <label for="noteGiden{{ $assignment->id }}" class="form-label fw-bold">Teslim Notu (Opsiyonel):</label>
                        <textarea name="note" class="form-control" id="noteGiden{{ $assignment->id }}" rows="3" placeholder="Teslim hakkında not ekleyin..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle me-2"></i>Onayla ve Teslim Et
                    </button>
                                        </div>
                                    </form>
                                </div>
    </div>
</div>
@endforeach

<!-- Gelen Detay Modal -->
@foreach($gelenAssignments as $assignment)
<div class="modal fade" id="detailModalGelen{{ $assignment->id }}" tabindex="-1" aria-labelledby="detailModalGelenLabel{{ $assignment->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success text-white">
                <h5 class="modal-title" id="detailModalGelenLabel{{ $assignment->id }}">
                    <i class="fas fa-check-circle me-2"></i>Teslim Detayı #{{ $assignment->id }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-success mb-3">
                            <i class="fas fa-user me-2"></i>Kullanıcı Bilgileri
                        </h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p><strong>Ad Soyad:</strong> {{ $assignment->user->name ?? 'Bilinmiyor' }}</p>
                                <p><strong>Email:</strong> {{ $assignment->user->email ?? 'Email yok' }}</p>
                                <p><strong>Zimmet Tarihi:</strong> {{ $assignment->created_at->format('d.m.Y H:i') }}</p>
                                <p><strong>Teslim Tarihi:</strong> {{ $assignment->updated_at->format('d.m.Y H:i') }}</p>
                                <p><strong>Durum:</strong>
                                    <span class="badge bg-success">Teslim Edildi</span>
                                </p>
                                @if($assignment->note)
                                <p><strong>Not:</strong> {{ $assignment->note }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-info mb-3">
                            <i class="fas fa-boxes me-2"></i>Ekipman Özeti
                        </h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p><strong>Toplam Ekipman:</strong> {{ $assignment->items->count() }} adet</p>
                                <p><strong>Tek Takip:</strong> {{ $assignment->items->where('equipment.individual_tracking', true)->count() }} adet</p>
                                <p><strong>Çoklu Takip:</strong> {{ $assignment->items->where('equipment.individual_tracking', false)->count() }} adet</p>
                                @if($assignment->damage_note)
                                <p><strong>Arıza Durumu:</strong> 
                                    <span class="badge bg-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Arızalı
                                    </span>
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <h6 class="fw-bold text-info mb-3">
                    <i class="fas fa-list me-2"></i>Ekipman Detayları
                </h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Ekipman</th>
                                <th>Kategori</th>
                                <th>Takip Türü</th>
                                <th>Miktar</th>
                                <th>Zimmet Fotoğrafı</th>
                                <th>Teslim Fotoğrafı</th>
                                <th>Stok Durumu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignment->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->equipment->name ?? 'Bilinmeyen Ekipman' }}</strong>
                                    @if($item->equipment->code)
                                    <br><small class="text-muted">Kod: {{ $item->equipment->code }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $item->equipment->category->name ?? 'Kategori yok' }}</span>
                                </td>
                                <td>
                                    @if($item->equipment->individual_tracking)
                                    <span class="badge bg-info">
                                        <i class="fas fa-barcode me-1"></i>Tek Takip
                                    </span>
                                    @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-layer-group me-1"></i>Çoklu Takip
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $item->quantity ?? 1 }} adet</span>
                                </td>
                                <td>
                                    @if($item->photo_path)
                                    <button class="btn btn-sm btn-outline-primary" onclick="showImageModal('{{ asset('storage/' . $item->photo_path) }}', '{{ $item->equipment->name ?? 'Ekipman' }} - Zimmet Fotoğrafı')">
                                        <i class="fas fa-image me-1"></i>Görüntüle
                                    </button>
                                    @else
                                    <span class="text-muted">Fotoğraf yok</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->return_photo_path)
                                    <button class="btn btn-sm btn-outline-success" onclick="showImageModal('{{ asset('storage/' . $item->return_photo_path) }}', '{{ $item->equipment->name ?? 'Ekipman' }} - Teslim Fotoğrafı')">
                                        <i class="fas fa-image me-1"></i>Görüntüle
                                    </button>
                                    @else
                                    <span class="text-muted">Fotoğraf yok</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->equipment->individual_tracking)
                                    <span class="badge bg-success">Aktif</span>
                                    @else
                                    <span class="badge bg-success">Stok Güncellendi</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                
                @if($assignment->damage_note)
                <hr class="my-4">
                <h6 class="fw-bold text-warning mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>Arıza/Hasar Notu
                </h6>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Dikkat:</strong> {{ $assignment->damage_note }}
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Resim Görüntüleme Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="imageModalLabel">
                    <i class="fas fa-image me-2"></i>Fotoğraf Görüntüle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Ekipman Fotoğrafı" class="img-fluid rounded shadow" style="max-width: 100%; max-height: 500px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                <a id="downloadImage" href="" download class="btn btn-primary">
                    <i class="fas fa-download me-2"></i>İndir
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Zaman Çizelgesi Modal -->
<div class="modal fade" id="timelineModal" tabindex="-1" aria-labelledby="timelineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info text-white">
                <h5 class="modal-title" id="timelineModalLabel">
                    <i class="fas fa-history me-2"></i>Zaman Çizelgesi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <div id="timelineContent">
                    <!-- Timeline content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Filtreleme işlemleri
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const userFilter = document.getElementById('userFilter');
    const dateFilter = document.getElementById('dateFilter');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const clearFiltersBtn = document.getElementById('clearFilters');
    
    function applyFilters() {
        const status = statusFilter.value;
        const user = userFilter.value.toLowerCase();
        const date = dateFilter.value;
        
        const rows = document.querySelectorAll('.assignment-row');
        
        rows.forEach(row => {
            let show = true;
            
            // Status filter
            if (status && row.dataset.status !== status) {
                show = false;
            }
            
            // User filter
            if (user && !row.dataset.user.includes(user)) {
                show = false;
            }
            
            // Date filter
            if (date) {
                const rowDate = row.dataset.date;
                const today = new Date().toISOString().split('T')[0];
                
                switch(date) {
                    case 'today':
                        if (rowDate !== today) show = false;
                        break;
                    case 'week':
                        const weekAgo = new Date();
                        weekAgo.setDate(weekAgo.getDate() - 7);
                        const weekAgoStr = weekAgo.toISOString().split('T')[0];
                        if (rowDate < weekAgoStr) show = false;
                        break;
                    case 'month':
                        const monthAgo = new Date();
                        monthAgo.setMonth(monthAgo.getMonth() - 1);
                        const monthAgoStr = monthAgo.toISOString().split('T')[0];
                        if (rowDate < monthAgoStr) show = false;
                        break;
                }
            }
            
            row.style.display = show ? '' : 'none';
        });
    }
    
    applyFiltersBtn.addEventListener('click', applyFilters);
    clearFiltersBtn.addEventListener('click', function() {
        statusFilter.value = '';
        userFilter.value = '';
        dateFilter.value = '';
        document.querySelectorAll('.assignment-row').forEach(row => {
            row.style.display = '';
        });
    });
    
    // Auto-filter on change
    statusFilter.addEventListener('change', applyFilters);
    userFilter.addEventListener('input', applyFilters);
    dateFilter.addEventListener('change', applyFilters);
});

// Resim modalını göster
function showImageModal(imageSrc, title) {
    console.log('showImageModal çağrıldı:', imageSrc, title);
    
    try {
        // Modal elementlerini bul
        const modalImage = document.getElementById('modalImage');
        const imageModalLabel = document.getElementById('imageModalLabel');
        const downloadImage = document.getElementById('downloadImage');
        const imageModalElement = document.getElementById('imageModal');
        
        if (!modalImage || !imageModalLabel || !downloadImage || !imageModalElement) {
            console.error('Modal elementleri bulunamadı');
            alert('Modal yüklenirken hata oluştu');
            return;
        }
        
        // Resim yüklenme hatası kontrolü
        modalImage.onerror = function() {
            console.error('Resim yüklenemedi:', imageSrc);
            alert('Resim yüklenirken hata oluştu: ' + imageSrc);
        };
        
        // Resim yüklendiğinde
        modalImage.onload = function() {
            console.log('Resim başarıyla yüklendi');
        };
        
        // Modal içeriğini güncelle
        modalImage.src = imageSrc;
        imageModalLabel.innerHTML = `<i class="fas fa-image me-2"></i>${title}`;
        downloadImage.href = imageSrc;
        
        // Bootstrap modal'ı aç
        const imageModal = new bootstrap.Modal(imageModalElement);
        imageModal.show();
        
        console.log('Modal açıldı');
        
    } catch (error) {
        console.error('showImageModal hatası:', error);
        alert('Modal açılırken hata oluştu: ' + error.message);
    }
}

// Zaman çizelgesi göster
function showTimeline(assignmentId) {
    // Bu fonksiyon AJAX ile timeline verilerini yükleyebilir
    document.getElementById('timelineContent').innerHTML = `
        <div class="text-center py-4">
            <i class="fas fa-clock fa-3x text-muted mb-3"></i>
            <p class="text-muted">Zaman çizelgesi özelliği yakında eklenecek</p>
        </div>
    `;
    
    const timelineModal = new bootstrap.Modal(document.getElementById('timelineModal'));
    timelineModal.show();
}

// Excel export
function exportToExcel(type) {
    alert(`${type === 'giden' ? 'Zimmet alınanlar' : 'Teslim edilenler'} Excel dosyası indirilecek`);
    // Excel export logic will be implemented here
}

// Print table
function printTable(type) {
    const table = document.getElementById(`${type}Table`);
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>${type === 'giden' ? 'Zimmet Alınanlar' : 'Teslim Edilenler'}</title>
                <style>
                    table { border-collapse: collapse; width: 100%; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                </style>
            </head>
            <body>
                <h2>${type === 'giden' ? 'Zimmet Alınan Ekipmanlar' : 'Teslim Edilen Ekipmanlar'}</h2>
                ${table.outerHTML}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}
</script>
@endpush

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 16px;
    font-weight: bold;
}

.approval-tabs .nav-link {
    border: none;
    border-radius: 0.5rem 0.5rem 0 0;
    margin-right: 0.25rem;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    color: #6c757d;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.approval-tabs .nav-link:hover {
    background: #e9ecef;
    color: #495057;
}

.approval-tabs .nav-link.active {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    border: none;
}

.animated-title {
    font-size: 1.8rem;
    font-weight: bold;
    color: #2c3e50;
    text-align: center;
    margin-bottom: 2rem;
    padding: 1rem;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-radius: 1rem;
    border: 1px solid rgba(79, 172, 254, 0.2);
}

.animated-title i {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-right: 0.5rem;
}

.card {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.table th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    font-weight: 600;
    color: #495057;
}

.btn-group .btn {
    border-radius: 0.5rem;
    margin: 0 0.1rem;
}

.modal-header {
    border-radius: 1rem 1rem 0 0;
}

.modal-content {
    border-radius: 1rem;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}
</style>
