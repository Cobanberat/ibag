@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    @include('admin.partials.breadcrumb', ['pageTitle' => 'Yönetim Paneli'])
    <!-- Üst Bar: Kullanıcı Kartı, Yardım, Duyuru, Hava Durumu, Bildirimler -->
@vite('resources/css/home.css')

    <div class="row g-3 mb-4">
        <!-- Hızlı İşlemler (Sol) -->
        <div class="col-md-12">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-header bg-white border-0 pb-1 text-center">
                    <span class="fw-bold fs-5"><i class="fa fa-bolt text-warning me-2"></i>Hızlı İşlemler</span>
                </div>
                <div class="card-body d-flex flex-wrap justify-content-center gap-3 p-3">
                    <a href="{{route('stock.create')}}" class="quick-action-btn" data-bs-toggle="tooltip" title="Yeni ekipman ekle">
                        <div class="quick-action-icon bg-gradient-primary"><i class="fa fa-plus-circle"></i></div>
                        <div class="quick-action-label">Ekipman Ekle</div>
                    </a>
                    <a href="/admin/stock" class="quick-action-btn" data-bs-toggle="tooltip" title="Stokları kontrol et">
                        <div class="quick-action-icon bg-gradient-warning"><i class="fa fa-boxes-stacked"></i></div>
                        <div class="quick-action-label">Stok Kontrol</div>
                    </a>
                    <a href="/admin/gidenGelen" class="quick-action-btn" data-bs-toggle="tooltip" title="Zimmet işlemleri">
                        <div class="quick-action-icon bg-gradient-success"><i class="fa fa-hand-holding"></i></div>
                        <div class="quick-action-label">Zimmet Yönetimi</div>
                    </a>
                    <a href="/admin/ekipmanlar" class="quick-action-btn" data-bs-toggle="tooltip" title="Ekipman listesi">
                        <div class="quick-action-icon bg-gradient-info"><i class="fa fa-list"></i></div>
                        <div class="quick-action-label">Ekipman Listesi</div>
                    </a>
                    <a href="{{route('admin.fault')}}" class="quick-action-btn" data-bs-toggle="tooltip" title="Arıza bildirimi yap">
                        <div class="quick-action-icon bg-gradient-danger"><i class="fa fa-bug"></i></div>
                        <div class="quick-action-label">Arıza Bildir</div>
                    </a>
                    <a href="{{route('admin.users')}}" class="quick-action-btn" data-bs-toggle="tooltip" title="Kullanıcıları yönet">
                        <div class="quick-action-icon bg-gradient-secondary"><i class="fa fa-users-cog"></i></div>
                        <div class="quick-action-label">Kullanıcı Yönetimi</div>
                    </a>
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
                        <table class="table table-hover mb-0 table-lg">
                            <thead>
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
                                        <td>{{ $stock['equipment_name'] }}</td>
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
                                        <td>{{ $stock['critical_threshold'] }}</td>
                                        <td>{{ $stock['last_used'] }}</td>
                                        <td>{{ $stock['category'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                        <table class="table table-hover mb-0 table-lg">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tarih</th>
                                    <th>İşlem</th>
                                    <th>Kullanıcı</th>
                                    <th>Detay</th>
                                    <th>Açıklama</th>
                                    <th>İşlem Tipi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentActivities as $activity)
                                    <tr>
                                        <td>{{ $activity['id'] }}</td>
                                        <td>{{ $activity['date']->format('d.m.Y H:i') }}</td>
                                        <td><span class="badge {{ $activity['badge_class'] }}">{{ $activity['action'] }}</span></td>
                                        <td>{{ $activity['user'] }}</td>
                                        <td>{{ $activity['detail'] }}</td>
                                        <td>{{ $activity['description'] }}</td>
                                        <td>{{ $activity['type'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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


