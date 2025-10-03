@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <!-- Breadcrumb -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                            <i class="fas fa-home me-1"></i>Ana Sayfa
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-tachometer-alt me-1"></i>Yönetim Paneli
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Hoş Geldin Mesajı -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="h3 mb-2 text-primary">
                                <i class="fas fa-crown me-2"></i>
                                Hoş Geldin, {{ auth()->user()->name }}!
                            </h1>
                            <p class="text-muted mb-0">
                                Admin Dashboard - {{ now()->format('d F Y') }} - {{ now()->format('l') }}
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="me-3">
                                    <div class="fw-bold text-success">{{ $stats['total_equipment'] }}</div>
                                    <small class="text-muted">Toplam Ekipman</small>
                                </div>
                                <div class="me-3">
                                    <div class="fw-bold text-primary">{{ $stats['active_users'] }}</div>
                                    <small class="text-muted">Aktif Kullanıcı</small>
                                </div>
                                <div>
                                    <div class="fw-bold text-warning">{{ $stats['critical_stocks'] }}</div>
                                    <small class="text-muted">Kritik Stok</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@vite('resources/css/home.css')

    <!-- Hızlı İşlemler -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-white border-0 pb-1 text-center">
                    <span class="fw-bold fs-5"><i class="fa fa-bolt text-warning me-2"></i>Hızlı İşlemler</span>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                            <a href="{{route('stock.create')}}" class="quick-action-btn w-100" data-bs-toggle="tooltip" title="Yeni ekipman ekle">
                                <div class="quick-action-icon bg-gradient-primary"><i class="fa fa-plus-circle"></i></div>
                                <div class="quick-action-label">Ekipman Ekle</div>
                            </a>
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                            <a href="/admin/stock" class="quick-action-btn w-100" data-bs-toggle="tooltip" title="Stokları kontrol et">
                                <div class="quick-action-icon bg-gradient-warning"><i class="fa fa-boxes-stacked"></i></div>
                                <div class="quick-action-label">Stok Kontrol</div>
                            </a>
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                            <a href="/admin/gidenGelen" class="quick-action-btn w-100" data-bs-toggle="tooltip" title="Zimmet işlemleri">
                                <div class="quick-action-icon bg-gradient-success"><i class="fa fa-hand-holding"></i></div>
                                <div class="quick-action-label">Zimmet Yönetimi</div>
                            </a>
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                            <a href="/admin/ekipmanlar" class="quick-action-btn w-100" data-bs-toggle="tooltip" title="Ekipman listesi">
                                <div class="quick-action-icon bg-gradient-info"><i class="fa fa-list"></i></div>
                                <div class="quick-action-label">Ekipman Listesi</div>
                            </a>
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                            <a href="{{route('admin.fault.create')}}" class="quick-action-btn w-100" data-bs-toggle="tooltip" title="Arıza bildirimi yap">
                                <div class="quick-action-icon bg-gradient-danger"><i class="fa fa-bug"></i></div>
                                <div class="quick-action-label">Arıza Bildir</div>
                            </a>
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                            <a href="{{route('admin.users')}}" class="quick-action-btn w-100" data-bs-toggle="tooltip" title="Kullanıcıları yönet">
                                <div class="quick-action-icon bg-gradient-secondary"><i class="fa fa-users-cog"></i></div>
                                <div class="quick-action-label">Kullanıcı Yönetimi</div>
                            </a>
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                            <a href="{{route('admin.users.create')}}" class="quick-action-btn w-100" data-bs-toggle="tooltip" title="Yeni kullanıcı ekle">
                                <div class="quick-action-icon bg-gradient-dark position-relative">
                                    <i class="fa fa-user-plus"></i>
                                    <img src="{{ asset('images/ibag-logo.svg') }}" alt="İBAG Logo" class="position-absolute" style="width: 40px; height: 40px; top: 5px; right: 5px; opacity: 1;">
                                </div>
                                <div class="quick-action-label">Kullanıcı Ekle</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
    <!-- Gradientli başlık ve hoşgeldin -->
   
    <!-- KPI Kartları -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow h-100 kpi-card text-white position-relative" style="background:linear-gradient(135deg,#43e97b 0%,#6366f1 100%);">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="display-6 mb-2"><i class="fa fa-box"></i></div>
                    <div class="h1 mb-0 counter text-white" data-count="{{ $stats['total_equipment'] }}">0</div>
                    <div class="small">Toplam Ekipman</div>
                </div>
                <span class="position-absolute top-0 end-0 m-2 badge bg-light text-primary">+{{ $stats['today_equipment'] }} bugün</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow h-100 kpi-card text-white position-relative" style="background:linear-gradient(135deg,#6366f1 0%,#43e97b 100%);">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="display-6 mb-2"><i class="fa fa-users"></i></div>
                    <div class="h1 mb-0 counter text-white" data-count="{{ $stats['active_users'] }}">0</div>
                    <div class="small">Aktif Kullanıcı</div>
                </div>
                <span class="position-absolute top-0 end-0 m-2 badge bg-light text-primary">+{{ $stats['today_users'] }} yeni</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow h-100 kpi-card text-white position-relative" style="background:linear-gradient(135deg,#fbbf24 0%,#f43f5e 100%);">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="display-6 mb-2"><i class="fa fa-exclamation-triangle"></i></div>
                    <div class="h1 mb-0 counter text-white" data-count="{{ $stats['pending_faults'] }}">0</div>
                    <div class="small">Bekleyen Arıza</div>
                </div>
                <span class="position-absolute top-0 end-0 m-2 badge bg-light text-danger">{{ $stats['critical_faults'] }} kritik</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow h-100 kpi-card text-white position-relative" style="background:linear-gradient(135deg,#6366f1 0%,#fbbf24 100%);">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="display-6 mb-2"><i class="fa fa-boxes-stacked"></i></div>
                    <div class="h1 mb-0 counter text-white" data-count="{{ $stats['critical_stocks'] }}">0</div>
                    <div class="small">Kritik Stok</div>
                </div>
                <span class="position-absolute top-0 end-0 m-2 badge bg-light text-danger">Dikkat!</span>
            </div>
        </div>
    </div>
    <!-- Motivasyon ve Görev Alanı -->
    <div class="row g-3 mb-4">
        <!-- Motivasyon kartı tamamen kaldırıldı, sadece Hızlı Görev Ekle kaldı -->
      
    </div>
    <!-- Kritik Stok ve Son İşlemler -->
    <div class="row g-3 mb-4">
        <div class="col-12 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-exclamation-triangle text-warning me-2"></i>
                        Kritik Stok Seviyesindeki Ekipmanlar
                    </h5>
                    <a href="{{ route('admin.stock') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-eye me-1"></i>Tümünü Gör
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($criticalStocks->count() > 0)
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-hover mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Ekipman Adı</th>
                                        <th>Durum</th>
                                        <th>Mevcut Stok</th>
                                        <th>Kritik Seviye</th>
                                        <th>Son Güncelleme</th>
                                        <th>Kategori</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($criticalStocks as $stock)
                                        <tr style="cursor: pointer;" onclick="window.location.href='{{ route('admin.stock') }}'">
                                            <td>
                                                <div class="fw-semibold">{{ $stock['equipment_name'] }}</div>
                                            </td>
                                            <td>
                                                @if($stock['critical_level'] == 1)
                                                    <span class="badge bg-danger">Kritik</span>
                                                @elseif($stock['critical_level'] == 2)
                                                    <span class="badge bg-warning">Dikkat</span>
                                                @else
                                                    <span class="badge bg-info">Uyarı</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold text-danger">{{ $stock['quantity'] }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-primary">{{ $stock['critical_threshold'] }}</span>
                                            </td>
                                            <td>{{ $stock['last_used'] }}</td>
                                            <td>{{ $stock['category'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fa fa-check-circle text-success fa-3x mb-3"></i>
                            <h6 class="text-muted">Kritik stok seviyesinde ekipman bulunmuyor</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-history me-2"></i>
                        Son 5 İşlem
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($recentActivities->count() > 0)
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-hover mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tarih</th>
                                        <th>İşlem</th>
                                        <th>Kullanıcı</th>
                                        <th>Detay</th>
                                        <th>Açıklama</th>
                                        <th>Tip</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentActivities as $activity)
                                        <tr>
                                            <td>{{ $activity['id'] }}</td>
                                            <td>{{ $activity['date']->format('d.m.Y H:i') }}</td>
                                            <td>
                                                <span class="badge {{ $activity['badge_class'] }}">{{ $activity['action'] }}</span>
                                            </td>
                                            <td>{{ $activity['user'] }}</td>
                                            <td>{{ $activity['detail'] }}</td>
                                            <td>{{ $activity['description'] }}</td>
                                            <td>{{ $activity['type'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fa fa-info-circle text-muted fa-3x mb-3"></i>
                            <h6 class="text-muted">Henüz işlem kaydı bulunmuyor</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div id="snackbar" class="position-fixed bottom-0 end-0 m-4 bg-dark text-white px-4 py-2 rounded shadow" style="display:none;z-index:9999;">Mesaj</div>
</div>
@vite('resources/js/home.js')


@endsection

<style>
/* Responsive Hızlı İşlem Kartları */
.quick-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    padding: 1rem;
    border-radius: 12px;
    background: white;
    border: 1px solid #e9ecef;
    height: 100%;
    min-height: 120px;
}

.quick-action-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    color: inherit;
    text-decoration: none;
    border-color: #dee2e6;
}

.quick-action-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
    font-size: 1.5rem;
    color: white;
}

.quick-action-label {
    font-weight: 600;
    font-size: 0.875rem;
    text-align: center;
    line-height: 1.2;
}

/* Responsive Tablolar */
.table-responsive {
    border-radius: 0.375rem;
    border: 1px solid #dee2e6;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
    white-space: nowrap;
    background-color: #f8f9fa;
    position: sticky;
    top: 0;
    z-index: 10;
}

.table td {
    vertical-align: middle;
    font-size: 0.875rem;
    border-bottom: 1px solid #dee2e6;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.table tbody tr:hover td {
    background-color: transparent;
}

/* Mobile optimizasyonları */
@media (max-width: 576px) {
    .quick-action-btn {
        min-height: 100px;
        padding: 0.75rem;
    }
    
    .quick-action-icon {
        width: 40px;
        height: 40px;
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }
    
    .quick-action-label {
        font-size: 0.75rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}

/* Breadcrumb responsive */
.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #6c757d;
}

/* Hoşgeldin kartı responsive */
@media (max-width: 768px) {
    .h3 {
        font-size: 1.25rem;
    }
    
    .text-end {
        text-align: left !important;
        margin-top: 1rem;
    }
}
</style>

