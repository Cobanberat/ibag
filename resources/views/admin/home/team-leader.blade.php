@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <!-- Hoş Geldin Mesajı -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="h3 mb-2 text-primary">
                                <i class="fas fa-users-cog me-2"></i>
                                Hoş Geldin, {{ auth()->user()->name }}!
                            </h1>
                            <p class="text-muted mb-0">
                                Ekip Yetkilisi Dashboard - {{ now()->format('d F Y') }}
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="me-3">
                                    <div class="fw-bold text-success">{{ $stats['team_members'] }}</div>
                                    <small class="text-muted">Ekip Üyesi</small>
                                </div>
                                <div class="me-3">
                                    <div class="fw-bold text-warning">{{ $stats['critical_stocks'] }}</div>
                                    <small class="text-muted">Kritik Stok</small>
                                </div>
                                <div>
                                    <div class="fw-bold text-info">{{ $stats['pending_assignments'] }}</div>
                                    <small class="text-muted">Bekleyen</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hızlı İşlemler -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3">
                <i class="fas fa-bolt me-2 text-warning"></i>
                Hızlı İşlemler
            </h5>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 quick-action-card" style="transition: all 0.3s ease;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-tachometer-alt fa-2x text-success"></i>
                        </div>
                        <h6 class="card-title text-dark mb-2">Ana Sayfa</h6>
                        <p class="card-text text-muted small mb-0">Ekip dashboard'u</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="{{ route('admin.stock') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 quick-action-card" style="transition: all 0.3s ease;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-boxes fa-2x text-warning"></i>
                        </div>
                        <h6 class="card-title text-dark mb-2">Stok Durumu</h6>
                        <p class="card-text text-muted small mb-0">Ekipman stok takibi</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="{{ route('admin.gidenGelen') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 quick-action-card" style="transition: all 0.3s ease;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-exchange-alt fa-2x text-primary"></i>
                        </div>
                        <h6 class="card-title text-dark mb-2">Giden/Gelen</h6>
                        <p class="card-text text-muted small mb-0">İşlem takibi</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="{{ route('admin.fault.create') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 quick-action-card" style="transition: all 0.3s ease;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                        </div>
                        <h6 class="card-title text-dark mb-2">Arıza Bildir</h6>
                        <p class="card-text text-muted small mb-0">Arıza kaydı oluştur</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- KPI Kartları -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-tools fa-2x text-primary"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-primary mb-1">{{ $stats['total_equipment'] }}</h3>
                    <p class="text-muted mb-0">Toplam Ekipman</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-users fa-2x text-success"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success mb-1">{{ $stats['team_members'] }}</h3>
                    <p class="text-muted mb-0">Ekip Üyesi</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-warning mb-1">{{ $stats['critical_stocks'] }}</h3>
                    <p class="text-muted mb-0">Kritik Stok</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-clock fa-2x text-info"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-info mb-1">{{ $stats['pending_assignments'] }}</h3>
                    <p class="text-muted mb-0">Bekleyen İşlem</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Kritik Stok Seviyesindeki Ekipmanlar -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                            Kritik Stok Seviyesi
                        </h5>
                        <a href="{{ route('admin.stock') }}" class="btn btn-sm btn-outline-primary">Tümünü Gör</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($criticalStocks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ekipman Adı</th>
                                        <th>Durum</th>
                                        <th>Mevcut Stok</th>
                                        <th>Kritik Seviye</th>
                                        <th>Kategori</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($criticalStocks->take(5) as $stock)
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
                                        <td>
                                            <span class="fw-bold text-primary">{{ $stock['critical_threshold'] }}</span>
                                        </td>
                                        <td>{{ $stock['category'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h6 class="text-muted">Kritik stok seviyesinde ekipman yok</h6>
                            <p class="text-muted small">Tüm ekipmanlar yeterli stok seviyesinde.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Son İşlemler -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history me-2 text-info"></i>
                            Son 5 İşlem
                        </h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentActivities->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentActivities as $activity)
                            <div class="list-group-item border-0 py-3">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        <span class="badge {{ $activity['badge_class'] }} rounded-pill">
                                            {{ $activity['type'] }}
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $activity['action'] }}</h6>
                                        <p class="mb-1 text-muted">{{ $activity['detail'] }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>{{ $activity['user'] }}
                                            <i class="fas fa-clock ms-2 me-1"></i>{{ $activity['date']->format('d-m-Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">Henüz işlem geçmişi yok</h6>
                            <p class="text-muted small">Ekip işlemleri burada görünecek.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Ekip İstatistikleri -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>
                        Ekip İstatistikleri
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="p-3">
                                <div class="fw-bold h4 text-primary">{{ $stats['total_equipment'] }}</div>
                                <small class="text-muted">Toplam Ekipman</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="p-3">
                                <div class="fw-bold h4 text-success">{{ $stats['team_members'] }}</div>
                                <small class="text-muted">Ekip Üyesi</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="p-3">
                                <div class="fw-bold h4 text-warning">{{ $stats['critical_stocks'] }}</div>
                                <small class="text-muted">Kritik Stok</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="p-3">
                                <div class="fw-bold h4 text-info">{{ $stats['pending_assignments'] }}</div>
                                <small class="text-muted">Bekleyen İşlem</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.quick-action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.card {
    border-radius: 12px;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.list-group-item {
    border-left: none;
    border-right: none;
}

.list-group-item:first-child {
    border-top: none;
}

.list-group-item:last-child {
    border-bottom: none;
}
</style>
@endsection
