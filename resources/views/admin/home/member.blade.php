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
                                <i class="fas fa-user-circle me-2"></i>
                                Hoş Geldin, {{ auth()->user()->name }}!
                            </h1>
                            <p class="text-muted mb-0">
                                Bugün {{ now()->format('d F Y') }} - {{ now()->format('l') }}
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="me-3">
                                    <div class="fw-bold text-success">{{ $myStats['active_assignments'] }}</div>
                                    <small class="text-muted">Aktif Zimmet</small>
                                      </div>
                                <div class="me-3">
                                    <div class="fw-bold text-primary">{{ $myStats['completed_assignments'] }}</div>
                                    <small class="text-muted">Tamamlanan</small>
                                </div>
                                <div>
                                    <div class="fw-bold text-info">{{ $myStats['this_month_assignments'] }}</div>
                                    <small class="text-muted">Bu Ay</small>
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
        @foreach($quickActions as $action)
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="{{ $action['url'] }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 quick-action-card" style="transition: all 0.3s ease;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="{{ $action['icon'] }} fa-2x text-{{ $action['color'] }}"></i>
                        </div>
                        <h6 class="card-title text-dark mb-2">{{ $action['title'] }}</h6>
                        <p class="card-text text-muted small mb-0">{{ $action['description'] }}</p>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <div class="row">
        <!-- Aktif Zimmetlerim -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-clipboard-list me-2 text-success"></i>
                            Aktif Zimmetlerim
                        </h5>
                        <span class="badge bg-success">{{ $myAssignments->count() }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($myAssignments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ekipman</th>
                                        <th>Kategori</th>
                                        <th>Tarih</th>
                                        <th>Durum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($myAssignments as $assignment)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $assignment['equipment_name'] }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $assignment['category'] }}</span>
                                        </td>
                                        <td>{{ $assignment['assigned_date'] }}</td>
                                        <td>
                                            <span class="badge {{ $assignment['status'] == 'Aktif' ? 'bg-success' : 'bg-secondary' }}">{{ $assignment['status'] }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">Aktif zimmetiniz bulunmuyor</h6>
                            <p class="text-muted small">Yeni ekipman zimmeti almak için "Zimmet Al" butonunu kullanın.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Son Alınan Ekipmanlar -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history me-2 text-info"></i>
                            Son Alınan Ekipmanlar
                        </h5>
                        <span class="badge bg-info">{{ $recentEquipment->count() }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentEquipment->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ekipman</th>
                                        <th>Kategori</th>
                                        <th>Tarih</th>
                                        <th>Durum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentEquipment as $equipment)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $equipment['equipment_name'] }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $equipment['category'] }}</span>
                                        </td>
                                        <td>{{ $equipment['assigned_date'] }}</td>
                                        <td>
                                            <span class="badge {{ $equipment['status_badge'] }}">
                                                {{ ucfirst($equipment['status']) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">Henüz ekipman alım geçmişiniz yok</h6>
                            <p class="text-muted small">İlk ekipmanınızı almak için "Zimmet Al" butonunu kullanın.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- İstatistikler -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>
                        İstatistiklerim
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="p-3">
                                <div class="fw-bold h4 text-primary">{{ $myStats['total_assignments'] }}</div>
                                <small class="text-muted">Toplam Zimmet</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="p-3">
                                <div class="fw-bold h4 text-success">{{ $myStats['active_assignments'] }}</div>
                                <small class="text-muted">Aktif Zimmet</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="p-3">
                                <div class="fw-bold h4 text-info">{{ $myStats['completed_assignments'] }}</div>
                                <small class="text-muted">Tamamlanan</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="p-3">
                                <div class="fw-bold h4 text-warning">{{ $myStats['this_month_assignments'] }}</div>
                                <small class="text-muted">Bu Ay</small>
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
</style>
@endsection
