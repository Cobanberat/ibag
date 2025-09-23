@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@vite(['resources/css/reporting.css'])

<style>
.page-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e7f1ff 100%);
    border-radius: 1rem;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 16px rgba(13,110,253,0.08);
    border: 1px solid rgba(13,110,253,0.1);
}

.page-header h2 {
    font-size: 2.2rem;
    font-weight: 700;
    color: #0d6efd;
    text-shadow: 0 1px 2px rgba(13,110,253,0.1);
}

.page-header p {
    font-size: 1.1rem;
    color: #6c757d;
    margin-bottom: 0;
}

@media (max-width: 768px) {
    .page-header {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .page-header h2 {
        font-size: 1.8rem;
    }
    
    .page-header .d-flex {
        flex-direction: column;
        text-align: center;
    }
    
    .page-header img {
        margin-right: 0 !important;
        margin-bottom: 1rem;
    }
}

/* Tablo stilleri */
.modern-table {
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.table-container {
    height: 400px;
    overflow: hidden;
    border-radius: 0.5rem;
    position: relative;
}

.table-container table {
    margin-bottom: 0;
}

.table-container thead {
    position: sticky;
    top: 0;
    z-index: 10;
}

.table-container thead th {
    background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%) !important;
    color: white;
    border: none;
    font-weight: 600;
    padding: 1rem 0.75rem;
}

.table-container tbody {
    display: block;
    height: 320px;
    overflow-y: auto;
}

.table-container thead,
.table-container tbody tr {
    display: table;
    width: 100%;
    table-layout: fixed;
}

.table-container tbody::-webkit-scrollbar {
    width: 8px;
}

.table-container tbody::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.table-container tbody::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.table-container tbody::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Modern table thead styles moved to .table-container thead th above */

.modern-table tbody tr {
    transition: all 0.3s ease;
}

.modern-table tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.1);
    transform: translateY(-1px);
}

.modern-card {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.modern-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

.chart-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 1rem;
    border: 1px solid rgba(0,0,0,0.05);
}

.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 0.875rem;
    font-weight: 600;
}

/* KPI kartları */
.kpi-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 1rem;
    transition: all 0.3s ease;
    color: #333;
}

.kpi-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.kpi-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.kpi-value {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: #333;
}

.kpi-label {
    font-size: 0.875rem;
    color: #6c757d;
}

/* Filtre barı */
.filter-bar {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
}

.type-filter-group {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.type-filter-btn {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 0.5rem 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.type-filter-btn:hover {
    border-color: #0d6efd;
    color: #0d6efd;
}

.type-filter-btn.selected {
    background: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

/* Snackbar */
#snackbar {
    background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
    color: white;
    border-radius: 0.5rem;
    padding: 1rem 1.5rem;
    box-shadow: 0 4px 16px rgba(13, 110, 253, 0.3);
}

/* Pagination */
.pagination-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 0.5rem;
    gap: 1rem;
}

.pagination-info {
    font-size: 0.9rem;
    color: #6c757d;
    text-align: center;
}

.pagination-controls {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    flex-wrap: wrap;
    justify-content: center;
}

.pagination-btn {
    padding: 0.5rem 1rem;
    border: 1px solid #dee2e6;
    background: white;
    border-radius: 0.25rem;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.pagination-btn:hover:not(:disabled) {
    background: #e9ecef;
    border-color: #0d6efd;
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-btn.active {
    background: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

@media (min-width: 768px) {
    .pagination-container {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
    
    .pagination-info {
        text-align: left;
    }
    
    .pagination-controls {
        justify-content: flex-end;
    }
}

/* Mobil için ek responsive düzenlemeler */
@media (max-width: 576px) {
    .page-header {
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .page-header h2 {
        font-size: 1.5rem;
    }
    
    .page-header .d-flex {
        flex-direction: column;
        text-align: center;
    }
    
    .page-header img {
        margin-right: 0 !important;
        margin-bottom: 0.5rem;
    }
    
    .kpi-card {
        padding: 0.75rem !important;
    }
    
    .kpi-value {
        font-size: 1.5rem !important;
    }
    
    .kpi-label {
        font-size: 0.7rem !important;
    }
    
    .filter-bar {
        padding: 0.75rem;
    }
    
    .type-filter-btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .chart-card {
        padding: 1rem !important;
    }
    
    .chart-card h6 {
        font-size: 0.9rem;
    }
}
</style>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center">
                <img src="{{ asset('images/ibag-logo.svg') }}" alt="İBAG Logo" class="d-none d-sm-inline" style="width: 24px; height: 24px; margin-right: 8px;">
                <i class="fa fa-home me-1"></i> 
                <span class="d-none d-sm-inline">Ana Sayfa</span>
                <span class="d-sm-none">Ana</span>
            </a>
        </li>
        <li class="breadcrumb-item d-none d-md-inline">
            <a href="/admin/" class="text-decoration-none">Yönetim</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <span class="d-none d-sm-inline">Raporlama & Analiz</span>
            <span class="d-sm-none">Raporlama</span>
        </li>
    </ol>
</nav>

<div class="container-fluid">
  <!-- Sayfa Başlığı -->
  <div class="page-header mb-4 text-center">
    <div class="d-flex align-items-center justify-content-center mb-3">
      <img src="{{ asset('images/ibag-logo.svg') }}" alt="İBAG Logo" style="width: 48px; height: 48px; margin-right: 15px;">
      <h2 class="mb-0 text-primary">
        <i class="fas fa-chart-bar me-2"></i>Raporlama & Analiz Paneli
      </h2>
    </div>
    <p class="text-muted">Sistem verilerini analiz edin ve raporlar oluşturun</p>
  </div>

  <!-- Bildirim -->
  <div class="d-flex justify-content-end align-items-center mb-3">
    <div id="snackbar" style="display:none;position:fixed;top:1.5em;right:2em;z-index:9999;" class="alert alert-info shadow">Yeni veri geldi!</div>
  </div>
  <!-- Otomatik Rapor Planlama ve Paylaşım -->
  <!-- Favori Raporlarım -->
 
  <!-- KPI Kartları (animasyonlu uyarı, mini trend) -->
  <div class="row g-2 g-md-3 mb-4">
    <div class="col-6 col-md-2">
      <div class="card kpi-card text-center p-2 p-md-3 position-relative">
        <div class="kpi-icon" style="color: #0d6efd; font-size: 1.5rem;"><i class="fas fa-boxes"></i></div>
        <div class="kpi-value" id="kpiTotal" style="font-size: 1.8rem;">{{ $stats['total_equipment'] ?? 0 }}</div>
        <div class="kpi-label" style="font-size: 0.75rem;">Toplam Ekipman</div>
        <canvas id="miniTrend1" height="18" style="width:100%;max-width:80px;" class="d-none d-lg-block"></canvas>
      </div>
    </div>
    <div class="col-6 col-md-2">
      <div class="card kpi-card text-center p-2 p-md-3 position-relative">
        <div class="kpi-icon" style="color: #28a745; font-size: 1.5rem;"><i class="fas fa-check-circle"></i></div>
        <div class="kpi-value" id="kpiActive" style="font-size: 1.8rem;">{{ $stats['active_equipment'] ?? 0 }}</div>
        <div class="kpi-label" style="font-size: 0.75rem;">Aktif</div>
        <canvas id="miniTrend2" height="18" style="width:100%;max-width:80px;" class="d-none d-lg-block"></canvas>
      </div>
    </div>
    <div class="col-6 col-md-2">
      <div class="card kpi-card text-center p-2 p-md-3 position-relative {{ ($stats['faulty_equipment'] ?? 0) > 0 ? 'animate__animated animate__flash animate__infinite' : '' }}" id="kpiFaultCard">
        <div class="kpi-icon" style="color: #dc3545; font-size: 1.5rem;"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="kpi-value" id="kpiFault" style="font-size: 1.8rem;">{{ $stats['faulty_equipment'] ?? 0 }}</div>
        <div class="kpi-label" style="font-size: 0.75rem;">Arızalı</div>
        @if(($stats['faulty_equipment'] ?? 0) > 0)
        <span class="position-absolute top-0 end-0 badge bg-danger" style="margin:5px; font-size: 0.6rem;">Kritik!</span>
        @endif
        <canvas id="miniTrend3" height="18" style="width:100%;max-width:80px;" class="d-none d-lg-block"></canvas>
      </div>
    </div>
    <div class="col-6 col-md-2">
      <div class="card kpi-card text-center p-2 p-md-3">
        <div class="kpi-icon" style="color: #ffc107; font-size: 1.5rem;"><i class="fas fa-tools"></i></div>
        <div class="kpi-value" id="kpiMaintenance" style="font-size: 1.8rem;">{{ $stats['maintenance_required'] ?? 0 }}</div>
        <div class="kpi-label" style="font-size: 0.75rem;">Bakımda</div>
      </div>
    </div>
    <div class="col-6 col-md-2">
      <div class="card kpi-card text-center p-2 p-md-3">
        <div class="kpi-icon" style="color: #17a2b8; font-size: 1.5rem;"><i class="fas fa-users"></i></div>
        <div class="kpi-value" id="kpiUsers" style="font-size: 1.8rem;">{{ $stats['total_users'] ?? 0 }}</div>
        <div class="kpi-label" style="font-size: 0.75rem;">Toplam Kullanıcı</div>
      </div>
    </div>
    <div class="col-6 col-md-2">
      <div class="card kpi-card text-center p-2 p-md-3">
        <div class="kpi-icon" style="color: #28a745; font-size: 1.5rem;"><i class="fas fa-check-double"></i></div>
        <div class="kpi-value" id="kpiResolved" style="font-size: 1.8rem;">{{ $stats['resolved_faults'] ?? 0 }}</div>
        <div class="kpi-label" style="font-size: 0.75rem;">Çözülen Arızalar</div>
      </div>
    </div>
  </div>
  <!-- Modern Filtreleme Barı (KPI kartlarının altında) -->
  <div class="filter-bar mt-2 mb-2">
    <div class="row g-2">
      <div class="col-12 col-md-3">
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
          <input type="text" class="form-control" id="filterDate" placeholder="Tarih Aralığı">
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="type-filter-group" id="typeFilterGroup">
          <button class="type-filter-btn" data-type="Bakım" title="Bakım taleplerini göster">
            <span class="type-ico"><i class="fas fa-tools"></i></span>
            <span class="d-none d-sm-inline">Bakım</span>
          </button>
          <button class="type-filter-btn" data-type="Arıza" title="Arıza bildirimlerini göster">
            <span class="type-ico"><i class="fas fa-bolt"></i></span>
            <span class="d-none d-sm-inline">Arıza</span>
          </button>
          <button class="type-filter-btn" data-type="Transfer" title="Transfer taleplerini göster">
            <span class="type-ico"><i class="fas fa-exchange-alt"></i></span>
            <span class="d-none d-sm-inline">Transfer</span>
          </button>
        </div>
      </div>
      <div class="col-12 col-md-3">
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
          <input type="text" class="form-control" id="searchInput" placeholder="Tabloda Ara...">
        </div>
      </div>
      <div class="col-12 col-md-2">
        <div class="d-flex flex-wrap gap-1">
          <button class="btn btn-outline-primary btn-sm" onclick="printTable()" title="Yazdır">
            <i class="fas fa-print"></i>
          </button>
          <button class="btn btn-outline-secondary btn-sm" onclick="copyTable()" title="Kopyala">
            <i class="fas fa-copy"></i>
          </button>
          <button class="btn btn-outline-success btn-sm" onclick="downloadCSV()" title="CSV İndir">
            <i class="fas fa-file-csv"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="mb-3" id="activeFilters"></div>
  <!-- Ekipman Durumu Tablosu -->
  <div class="card modern-card mb-4">
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
      <span><i class="fas fa-boxes me-2"></i>Ekipman Durumu</span>
      <div class="mt-2 mt-md-0">
        <button class="btn btn-outline-primary btn-sm me-1" onclick="printTable()">
          <i class="fas fa-print"></i>
          <span class="d-none d-sm-inline">Yazdır</span>
        </button>
        <button class="btn btn-outline-success btn-sm me-1" onclick="downloadCSV()">
          <i class="fas fa-file-csv"></i>
          <span class="d-none d-sm-inline">CSV</span>
        </button>
        <button class="btn btn-outline-info btn-sm" onclick="copyTable()">
          <i class="fas fa-copy"></i>
          <span class="d-none d-sm-inline">Kopyala</span>
        </button>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table modern-table table-striped table-hover mb-0" id="equipmentTable">
          <thead class="table-light sticky-top">
            <tr>
              <th class="d-none d-sm-table-cell">#</th>
              <th>Ekipman Adı</th>
              <th class="d-none d-md-table-cell">Kategori</th>
              <th class="d-none d-lg-table-cell">Kod</th>
              <th>Durum</th>
              <th class="d-none d-sm-table-cell">Son Güncelleme</th>
              <th>İşlemler</th>
            </tr>
          </thead>
          <tbody>
            @forelse($equipmentByCategory as $category)
              @foreach($category['equipment'] as $index => $equipment)
                <tr>
                  <td class="d-none d-sm-table-cell">{{ $loop->iteration }}</td>
                  <td>
                    <div class="d-flex flex-column">
                      <strong>{{ $equipment['name'] }}</strong>
                      <small class="text-muted d-sm-none">{{ $category['name'] }} • {{ $equipment['code'] ?? 'N/A' }}</small>
                    </div>
                  </td>
                  <td class="d-none d-md-table-cell"><span class="badge bg-info">{{ $category['name'] }}</span></td>
                  <td class="d-none d-lg-table-cell"><code>{{ $equipment['code'] ?? 'N/A' }}</code></td>
                  <td>
                    @if($equipment['status'] == 'Aktif')
                      <span class="badge bg-success">Aktif</span>
                    @elseif($equipment['status'] == 'Arızalı')
                      <span class="badge bg-danger">Arızalı</span>
                    @elseif($equipment['status'] == 'Bakım Gerekiyor')
                      <span class="badge bg-warning">Bakım Gerekiyor</span>
                    @else
                      <span class="badge bg-secondary">{{ $equipment['status'] }}</span>
                    @endif
                  </td>
                  <td class="d-none d-sm-table-cell">{{ $equipment['updated_at'] ? \Carbon\Carbon::parse($equipment['updated_at'])->format('d.m.Y H:i') : 'Bilinmiyor' }}</td>
                  <td>
                    <button class="btn btn-sm btn-outline-info" onclick="showEquipmentDetail({{ $equipment['id'] }})">
                      <i class="fas fa-eye"></i>
                    </button>
                  </td>
                </tr>
              @endforeach
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">
                  <i class="fas fa-box-open fa-2x mb-2"></i><br>
                  Henüz ekipman bulunmuyor
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <!-- Pagination -->
      <div class="pagination-container" id="equipmentPagination">
        <div class="pagination-info" id="equipmentPaginationInfo">
          Sayfa 1 / 1 (Toplam 0 kayıt)
        </div>
        <div class="pagination-controls">
          <button class="pagination-btn" id="equipmentPrevBtn" onclick="changeEquipmentPage(-1)" disabled>
            <i class="fas fa-chevron-left"></i> Önceki
          </button>
          <span class="pagination-btn active" id="equipmentCurrentPage">1</span>
          <button class="pagination-btn" id="equipmentNextBtn" onclick="changeEquipmentPage(1)" disabled>
            Sonraki <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Arıza Bildirimleri Tablosu -->
  <div class="card modern-card mb-4">
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
      <span><i class="fas fa-exclamation-triangle me-2"></i>Arıza Bildirimleri</span>
      <div class="mt-2 mt-md-0">
        <button class="btn btn-outline-primary btn-sm me-1" onclick="printFaultTable()">
          <i class="fas fa-print"></i>
          <span class="d-none d-sm-inline">Yazdır</span>
        </button>
        <button class="btn btn-outline-success btn-sm" onclick="downloadFaultCSV()">
          <i class="fas fa-file-csv"></i>
          <span class="d-none d-sm-inline">CSV</span>
        </button>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table modern-table table-striped table-hover mb-0" id="faultTable">
          <thead class="table-light sticky-top">
            <tr>
              <th class="d-none d-sm-table-cell">#</th>
              <th>Ekipman</th>
              <th class="d-none d-md-table-cell">Arıza Tipi</th>
              <th class="d-none d-lg-table-cell">Öncelik</th>
              <th class="d-none d-sm-table-cell">Bildiren</th>
              <th>Durum</th>
              <th class="d-none d-md-table-cell">Tarih</th>
              <th>İşlemler</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentActivities as $activity)
              @if($activity['type'] == 'fault')
                <tr>
                  <td class="d-none d-sm-table-cell">{{ $loop->iteration }}</td>
                  <td>
                    <div class="d-flex flex-column">
                      <strong>{{ $activity['description'] }}</strong>
                      <small class="text-muted d-sm-none">{{ $activity['user'] }} • {{ $activity['date'] }}</small>
                    </div>
                  </td>
                  <td class="d-none d-md-table-cell"><span class="badge bg-warning">Arıza</span></td>
                  <td class="d-none d-lg-table-cell"><span class="badge bg-danger">Yüksek</span></td>
                  <td class="d-none d-sm-table-cell">{{ $activity['user'] }}</td>
                  <td>
                    @if($activity['status'] == 'Çözüldü')
                      <span class="badge bg-success">Çözüldü</span>
                    @elseif($activity['status'] == 'Beklemede')
                      <span class="badge bg-warning">Beklemede</span>
                    @else
                      <span class="badge bg-info">{{ $activity['status'] }}</span>
                    @endif
                  </td>
                  <td class="d-none d-md-table-cell">{{ $activity['date'] }}</td>
                  <td>
                    <button class="btn btn-sm btn-outline-info" onclick="showFaultDetail({{ $loop->iteration }})">
                      <i class="fas fa-eye"></i>
                    </button>
                  </td>
                </tr>
              @endif
            @empty
              <tr>
                <td colspan="8" class="text-center text-muted py-4">
                  <i class="fas fa-exclamation-triangle fa-2x mb-2"></i><br>
                  Henüz arıza bildirimi bulunmuyor
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <!-- Pagination -->
      <div class="pagination-container" id="faultPagination">
        <div class="pagination-info" id="faultPaginationInfo">
          Sayfa 1 / 1 (Toplam 0 kayıt)
        </div>
        <div class="pagination-controls">
          <button class="pagination-btn" id="faultPrevBtn" onclick="changeFaultPage(-1)" disabled>
            <i class="fas fa-chevron-left"></i> Önceki
          </button>
          <span class="pagination-btn active" id="faultCurrentPage">1</span>
          <button class="pagination-btn" id="faultNextBtn" onclick="changeFaultPage(1)" disabled>
            Sonraki <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Kullanıcı Aktiviteleri Tablosu -->
  <div class="card modern-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span><i class="fas fa-users me-2"></i>Kullanıcı Aktiviteleri</span>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table modern-table table-striped table-hover mb-0" id="userActivityTable">
          <thead class="table-light sticky-top">
            <tr>
              <th class="d-none d-sm-table-cell">#</th>
              <th>Kullanıcı</th>
              <th class="d-none d-md-table-cell">Rol</th>
              <th class="d-none d-lg-table-cell">Zimmet Sayısı</th>
              <th class="d-none d-sm-table-cell">Son Giriş</th>
              <th>Durum</th>
            </tr>
          </thead>
          <tbody>
            @forelse($userActivity as $user)
              <tr>
                <td class="d-none d-sm-table-cell">{{ $loop->iteration }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="avatar-sm bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center">
                      {{ substr($user['name'], 0, 1) }}
                    </div>
                    <div>
                      <div class="fw-bold">{{ $user['name'] }}</div>
                      <small class="text-muted d-sm-none">{{ $user['email'] }}</small>
                      <small class="text-muted d-none d-sm-inline">{{ $user['email'] }}</small>
                    </div>
                  </div>
                </td>
                <td class="d-none d-md-table-cell">
                  @if($user['role'] == 'Admin')
                    <span class="badge bg-danger">{{ $user['role'] }}</span>
                  @elseif($user['role'] == 'Ekip Yetkilisi')
                    <span class="badge bg-warning">{{ $user['role'] }}</span>
                  @else
                    <span class="badge bg-info">{{ $user['role'] }}</span>
                  @endif
                </td>
                <td class="d-none d-lg-table-cell"><span class="badge bg-primary">{{ $user['assignments'] }}</span></td>
                <td class="d-none d-sm-table-cell">{{ $user['last_login'] }}</td>
                <td>
                  <span class="badge bg-success">Aktif</span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">
                  <i class="fas fa-users fa-2x mb-2"></i><br>
                  Henüz kullanıcı aktivitesi bulunmuyor
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <!-- Pagination -->
      <div class="pagination-container" id="userPagination">
        <div class="pagination-info" id="userPaginationInfo">
          Sayfa 1 / 1 (Toplam 0 kayıt)
        </div>
        <div class="pagination-controls">
          <button class="pagination-btn" id="userPrevBtn" onclick="changeUserPage(-1)" disabled>
            <i class="fas fa-chevron-left"></i> Önceki
          </button>
          <span class="pagination-btn active" id="userCurrentPage">1</span>
          <button class="pagination-btn" id="userNextBtn" onclick="changeUserPage(1)" disabled>
            Sonraki <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- Talep Detay Modalı (işlem geçmişi sekmeli) -->
  <div class="modal fade" id="talepDetailModal" tabindex="-1" aria-labelledby="talepDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="talepDetailModalLabel">Talep Detayı</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
        </div>
        <div class="modal-body">
          <ul class="nav nav-tabs mb-3" id="talepDetailTabs">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#talepInfo">Bilgi</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#talepLog">İşlem Geçmişi</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade show active" id="talepInfo">
              <div id="talepDetailBody"></div>
            </div>
            <div class="tab-pane fade" id="talepLog">
              <ul class="list-group" id="talepLogList"></ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Grafikler -->
  <div class="row mb-4">
    <!-- Üst satır: Aylık Arıza Trendi ve Ekipman Durumu Dağılımı -->
    <div class="col-12 col-lg-8 mb-4">
      <div class="card chart-card p-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-2">
          <h6 class="mb-2 mb-md-0"><i class="fas fa-chart-line"></i> Aylık Arıza Trendi</h6>
          <select class="form-select form-select-sm w-auto" id="chartTypeSelect">
            <option value="line">Çizgi</option>
            <option value="bar">Bar</option>
            <option value="doughnut">Donut</option>
          </select>
        </div>
        <div style="position: relative; height: 300px;">
          <canvas id="lineChart"></canvas>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-4 mb-4">
      <div class="card chart-card p-3">
        <h6><i class="fas fa-chart-pie"></i> Ekipman Durumu Dağılımı</h6>
        <div style="position: relative; height: 300px;">
          <canvas id="pieChart"></canvas>
        </div>
      </div>
    </div>
    
    <!-- Alt satır: Kategori Bazında Ekipman (tam genişlik) -->
    <div class="col-12 mb-4">
      <div class="card chart-card p-3">
        <h6><i class="fas fa-chart-bar"></i> Kategori Bazında Ekipman</h6>
        <div style="position: relative; height: 400px;">
          <canvas id="barChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  <!-- Tedarik Edilmesi Gereken Ürünler Modal -->
  <div class="modal fade" id="supplyModal" tabindex="-1" aria-labelledby="supplyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="supplyModalLabel">Tedarik Edilmesi Gereken Ürünler</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
        </div>
        <div class="modal-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Ürün Adı</th>
                <th>Adet</th>
                <th>Açıklama</th>
              </tr>
            </thead>
            <tbody id="supplyTableBody">
              <!-- JS ile doldurulacak -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
flatpickr('#filterDate', {mode:'range', dateFormat:'Y-m-d'});
// Chart.js gerçek veriler
const lineChart = new Chart(document.getElementById('lineChart'), {
  type: 'line',
  data: {
    labels: {!! json_encode($faultTrendsData['labels']) !!},
    datasets: [{
      label: 'Arızalar',
      data: {!! json_encode($faultTrendsData['faults']) !!},
      borderColor: '#dc3545',
      backgroundColor: 'rgba(220,53,69,0.1)',
      tension: 0.4,
      fill: true
    }, {
      label: 'Çözülen Arızalar',
      data: {!! json_encode($faultTrendsData['resolved']) !!},
      borderColor: '#28a745',
      backgroundColor: 'rgba(40,167,69,0.1)',
      tension: 0.4,
      fill: true
    }]
  },
  options: {responsive:true, plugins:{legend:{display:true}}}
});

const pieChart = new Chart(document.getElementById('pieChart'), {
  type: 'pie',
  data: {
    labels: ['Aktif', 'Arızalı', 'Bakım Gerekiyor', 'Diğer'],
    datasets: [{
      data: [
        {{ $stats['active_equipment'] ?? 0 }},
        {{ $stats['faulty_equipment'] ?? 0 }},
        {{ $stats['maintenance_required'] ?? 0 }},
        {{ ($stats['total_equipment'] ?? 0) - ($stats['active_equipment'] ?? 0) - ($stats['faulty_equipment'] ?? 0) - ($stats['maintenance_required'] ?? 0) }}
      ],
      backgroundColor: ['#28a745','#dc3545','#ffc107','#6c757d']
    }]
  },
  options: {responsive:true}
});

const barChart = new Chart(document.getElementById('barChart'), {
  type: 'bar',
  data: {
    labels: {!! json_encode(array_column($equipmentByCategory, 'name')) !!},
    datasets: [{
      label: 'Toplam Ekipman',
      data: {!! json_encode(array_column($equipmentByCategory, 'total')) !!},
      backgroundColor: '#0d6efd'
    }, {
      label: 'Aktif',
      data: {!! json_encode(array_column($equipmentByCategory, 'active')) !!},
      backgroundColor: '#28a745'
    }, {
      label: 'Arızalı',
      data: {!! json_encode(array_column($equipmentByCategory, 'faulty')) !!},
      backgroundColor: '#dc3545'
    }]
  },
  options: {responsive:true, plugins:{legend:{display:true}}}
});
// Modal fonksiyonları
function showEquipmentDetail(equipmentId) {
  // Ekipman detay modalı aç
  $('#talepDetailModal').modal('show');
  $('#talepDetailModalLabel').text('Ekipman Detayı');
  
  // AJAX ile gerçek veri çek
  fetch(`/admin/ekipmanlar/${equipmentId}`)
    .then(response => response.json())
    .then(result => {
      const data = result.data || result;
      const equipment = data.equipment || data;
      $('#talepDetailBody').html(`
        <div class="row">
          <div class="col-md-6">
            <h6>Ekipman Bilgileri</h6>
            <p><strong>ID:</strong> ${data.id || equipmentId}</p>
            <p><strong>Ad:</strong> ${equipment.name || 'Bilinmiyor'}</p>
            <p><strong>Kod:</strong> ${equipment.code || 'N/A'}</p>
            <p><strong>Durum:</strong> <span class="badge bg-${equipment.status === 'Aktif' ? 'success' : equipment.status === 'Arızalı' ? 'danger' : 'warning'}">${equipment.status || 'Bilinmiyor'}</span></p>
            <p><strong>Son Güncelleme:</strong> ${data.updated_at ? new Date(data.updated_at).toLocaleDateString('tr-TR') : 'Bilinmiyor'}</p>
          </div>
          <div class="col-md-6">
            <h6>İstatistikler</h6>
            <p><strong>Kategori:</strong> ${equipment.category ? equipment.category.name : 'Belirtilmemiş'}</p>
            <p><strong>Kritik Seviye:</strong> ${equipment.critical_level || 'Belirtilmemiş'}</p>
            <p><strong>Stok Miktarı:</strong> ${data.quantity || '0'}</p>
            <p><strong>Oluşturulma:</strong> ${data.created_at ? new Date(data.created_at).toLocaleDateString('tr-TR') : 'Bilinmiyor'}</p>
          </div>
        </div>
      `);
    })
    .catch(error => {
      console.error('Veri çekme hatası:', error);
      $('#talepDetailBody').html(`
        <div class="alert alert-warning">
          <i class="fas fa-exclamation-triangle"></i> Ekipman detayları yüklenirken hata oluştu.
        </div>
      `);
    });
  
  // İşlem geçmişini temizle
  $('#talepLogList').html('<li class="list-group-item text-muted">İşlem geçmişi yükleniyor...</li>');
}

function showFaultDetail(faultId) {
  // Arıza detay modalı aç
  $('#talepDetailModal').modal('show');
  $('#talepDetailModalLabel').text('Arıza Detayı');
  
  // AJAX ile gerçek veri çek
  fetch(`/admin/fault/${faultId}`)
    .then(response => response.json())
    .then(result => {
      const data = result.fault || result;
      $('#talepDetailBody').html(`
        <div class="row">
          <div class="col-md-6">
            <h6>Arıza Bilgileri</h6>
            <p><strong>ID:</strong> ${faultId}</p>
            <p><strong>Durum:</strong> <span class="badge bg-${data.status === 'Çözüldü' ? 'success' : data.status === 'Beklemede' ? 'warning' : 'info'}">${data.status || 'Bilinmiyor'}</span></p>
            <p><strong>Öncelik:</strong> <span class="badge bg-${data.priority === 'Yüksek' ? 'danger' : data.priority === 'Orta' ? 'warning' : 'info'}">${data.priority || 'Belirtilmemiş'}</span></p>
            <p><strong>Bildiren:</strong> ${data.reporter_name || 'Bilinmiyor'}</p>
          </div>
          <div class="col-md-6">
            <h6>Teknik Detaylar</h6>
            <p><strong>Arıza Tipi:</strong> ${data.type || 'Belirtilmemiş'}</p>
            <p><strong>Bildirim Tarihi:</strong> ${data.reported_date || 'Bilinmiyor'}</p>
            <p><strong>Ekipman:</strong> ${data.equipment_name || 'Belirtilmemiş'}</p>
            <p><strong>Kategori:</strong> ${data.category_name || 'Belirtilmemiş'}</p>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-12">
            <h6>Açıklama</h6>
            <p class="text-muted">${data.description || 'Açıklama bulunmuyor'}</p>
          </div>
        </div>
      `);
    })
    .catch(error => {
      console.error('Veri çekme hatası:', error);
      $('#talepDetailBody').html(`
        <div class="alert alert-warning">
          <i class="fas fa-exclamation-triangle"></i> Arıza detayları yüklenirken hata oluştu.
        </div>
      `);
    });
  
  // İşlem geçmişini temizle
  $('#talepLogList').html('<li class="list-group-item text-muted">İşlem geçmişi yükleniyor...</li>');
}

function printFaultTable() {
  window.print();
}

function downloadFaultCSV() {
    const table = document.getElementById('faultTable');
    const rows = table.querySelectorAll('tbody tr:not([style*="display: none"])');
    
    let csv = 'Ekipman,Arıza Tipi,Öncelik,Bildiren,Durum,Tarih\n';
    
    rows.forEach(row => {
        if (row.style.display !== 'none') {
            const cells = row.querySelectorAll('td');
            const equipment = cells[1].textContent.trim();
            const type = cells[2].textContent.trim();
            const priority = cells[3].textContent.trim();
            const reporter = cells[4].textContent.trim();
            const status = cells[5].textContent.trim();
            const date = cells[6].textContent.trim();
            
            csv += `"${equipment}","${type}","${priority}","${reporter}","${status}","${date}"\n`;
        }
    });
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'ariza_raporu.csv';
    a.click();
    window.URL.revokeObjectURL(url);
    
    // Snackbar kaldırıldı - gereksiz bildirim
}

// Genel fonksiyonlar
window.onload = function() {
  // Pagination'ı başlat
  initPagination();
};
// Tablo araçları
function printTable() { 
  window.print(); 
}

function copyTable() { 
  showSnackbar('Tablo kopyalandı!'); 
}

function downloadCSV() { 
  showSnackbar('CSV dosyası indiriliyor...'); 
}

// Grafik türü seçme
let chartType = 'line';
document.getElementById('chartTypeSelect').onchange = function() {
  chartType = this.value;
  showSnackbar('Grafik türü değiştirildi: ' + chartType);
};

// Canlı bildirim/snackbar
function showSnackbar(msg) {
  const sb = document.getElementById('snackbar');
  sb.innerText = msg;
  sb.style.display = 'block';
  setTimeout(()=>sb.style.display='none', 3000);
}

// Mini trend grafikler - gerçek verilerle doldurulacak
// Bu grafikler şu anda gizli (d-none d-lg-block) ve gerçek verilerle doldurulabilir


// Canlı veri simülasyonu - kaldırıldı

// Filtreleme fonksiyonları
let currentFilters = {
    dateRange: null,
    typeFilters: [],
    searchTerm: ''
};

// Pagination ayarları
const itemsPerPage = 10;
let pagination = {
    equipment: { currentPage: 1, totalPages: 1, totalItems: 0 },
    fault: { currentPage: 1, totalPages: 1, totalItems: 0 },
    user: { currentPage: 1, totalPages: 1, totalItems: 0 }
};

// Tarih filtresi
document.getElementById('filterDate').addEventListener('change', function() {
    const dateRange = this.value;
    currentFilters.dateRange = dateRange;
    applyFilters();
    // Snackbar kaldırıldı - gereksiz bildirim
});

// Tip filtresi butonları
document.querySelectorAll('.type-filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const type = this.dataset.type;
        this.classList.toggle('selected');
        
        if (this.classList.contains('selected')) {
            if (!currentFilters.typeFilters.includes(type)) {
                currentFilters.typeFilters.push(type);
            }
        } else {
            currentFilters.typeFilters = currentFilters.typeFilters.filter(t => t !== type);
        }
        
        applyFilters();
        // Snackbar kaldırıldı - gereksiz bildirim
    });
});

// Arama filtresi
document.getElementById('searchInput').addEventListener('input', function() {
    currentFilters.searchTerm = this.value.toLowerCase();
    applyFilters();
});

// Filtreleri uygula
function applyFilters() {
    const table = document.getElementById('equipmentTable');
    const rows = table.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        let showRow = true;
        
        // Arama filtresi
        if (currentFilters.searchTerm) {
            const text = row.textContent.toLowerCase();
            if (!text.includes(currentFilters.searchTerm)) {
                showRow = false;
            }
        }
        
        // Tip filtresi (durum bazında)
        if (currentFilters.typeFilters.length > 0) {
            const statusCell = row.cells[4]; // Durum sütunu
            const statusText = statusCell.textContent.trim();
            let matchesType = false;
            
            currentFilters.typeFilters.forEach(type => {
                if (type === 'Arıza' && statusText.includes('Arızalı')) {
                    matchesType = true;
                } else if (type === 'Bakım' && statusText.includes('Bakım')) {
                    matchesType = true;
                } else if (type === 'Transfer' && statusText.includes('Transfer')) {
                    matchesType = true;
                }
            });
            
            if (!matchesType) {
                showRow = false;
            }
        }
        
        // Satırı göster/gizle
        if (showRow) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    // Aktif filtreleri göster
    updateActiveFilters();
    
    // Pagination'ı güncelle (sayfa 1'e dön)
    pagination.equipment.currentPage = 1;
    updatePagination('equipment');
}

// Aktif filtreleri güncelle
function updateActiveFilters() {
    const activeFiltersDiv = document.getElementById('activeFilters');
    let filtersHtml = '';
    
    if (currentFilters.dateRange) {
        filtersHtml += `<span class="badge bg-primary me-2">Tarih: ${currentFilters.dateRange}</span>`;
    }
    
    currentFilters.typeFilters.forEach(type => {
        filtersHtml += `<span class="badge bg-info me-2">${type}</span>`;
    });
    
    if (currentFilters.searchTerm) {
        filtersHtml += `<span class="badge bg-success me-2">Arama: "${currentFilters.searchTerm}"</span>`;
    }
    
    if (filtersHtml) {
        activeFiltersDiv.innerHTML = `
            <div class="d-flex align-items-center">
                <strong class="me-2">Aktif Filtreler:</strong>
                ${filtersHtml}
                <button class="btn btn-sm btn-outline-secondary ms-2" onclick="clearAllFilters()">
                    <i class="fas fa-times"></i> Temizle
                </button>
            </div>
        `;
    } else {
        activeFiltersDiv.innerHTML = '';
    }
}

// Tüm filtreleri temizle
function clearAllFilters() {
    currentFilters = {
        dateRange: null,
        typeFilters: [],
        searchTerm: ''
    };
    
    // Form elemanlarını sıfırla
    document.getElementById('filterDate').value = '';
    document.getElementById('searchInput').value = '';
    document.querySelectorAll('.type-filter-btn').forEach(btn => {
        btn.classList.remove('selected');
    });
    
    // Tabloyu sıfırla
    const table = document.getElementById('equipmentTable');
    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
        row.style.display = '';
    });
    
    // Pagination'ı sıfırla
    pagination.equipment.currentPage = 1;
    updateActiveFilters();
    updatePagination('equipment');
    // Snackbar kaldırıldı - gereksiz bildirim
}

// Tablo araçları - geliştirilmiş
function downloadCSV() {
    const table = document.getElementById('equipmentTable');
    const rows = table.querySelectorAll('tbody tr:not([style*="display: none"])');
    
    let csv = 'Ekipman Adı,Kategori,Kod,Durum,Son Güncelleme\n';
    
    rows.forEach(row => {
        if (row.style.display !== 'none') {
            const cells = row.querySelectorAll('td');
            const name = cells[1].textContent.trim();
            const category = cells[2].textContent.trim();
            const code = cells[3].textContent.trim();
            const status = cells[4].textContent.trim();
            const date = cells[5].textContent.trim();
            
            csv += `"${name}","${category}","${code}","${status}","${date}"\n`;
        }
    });
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'ekipman_raporu.csv';
    a.click();
    window.URL.revokeObjectURL(url);
    
    // Snackbar kaldırıldı - gereksiz bildirim
}

function copyTable() {
    const table = document.getElementById('equipmentTable');
    const rows = table.querySelectorAll('tbody tr:not([style*="display: none"])');
    
    let text = 'Ekipman Raporu\n\n';
    text += 'Sıra\tEkipman Adı\tKategori\tKod\tDurum\tSon Güncelleme\n';
    
    rows.forEach(row => {
        if (row.style.display !== 'none') {
            const cells = row.querySelectorAll('td');
            const rowText = Array.from(cells).slice(0, -1).map(cell => cell.textContent.trim()).join('\t');
            text += rowText + '\n';
        }
    });
    
    navigator.clipboard.writeText(text).then(() => {
        // Snackbar kaldırıldı - gereksiz bildirim
    }).catch(() => {
        // Snackbar kaldırıldı - gereksiz bildirim
    });
}

// Pagination fonksiyonları
function initPagination() {
    updatePagination('equipment');
    updatePagination('fault');
    updatePagination('user');
}

function updatePagination(tableType) {
    const table = document.getElementById(tableType + 'Table');
    if (!table) {
        console.warn(`Table ${tableType}Table not found`);
        return;
    }
    const rows = table.querySelectorAll('tbody tr');
    
    // Eğer filtreleme aktifse, tüm satırları say (pagination yok)
    if (currentFilters.searchTerm || currentFilters.typeFilters.length > 0) {
        const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
        pagination[tableType].totalItems = visibleRows.length;
        pagination[tableType].totalPages = 1;
        pagination[tableType].currentPage = 1;
        updatePaginationUI(tableType);
        return; // Filtreleme aktifse pagination uygulama
    }
    
    // Normal durum: tüm satırları say (filtreleme yok)
    const totalItems = rows.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;
    
    pagination[tableType].totalItems = totalItems;
    pagination[tableType].totalPages = totalPages;
    
    // Eğer mevcut sayfa toplam sayfa sayısından büyükse, ilk sayfaya git
    if (pagination[tableType].currentPage > totalPages) {
        pagination[tableType].currentPage = 1;
    }
    
    updatePaginationUI(tableType);
    showTablePage(tableType);
}

function updatePaginationUI(tableType) {
    const paginationContainer = document.getElementById(tableType + 'Pagination');
    const info = document.getElementById(tableType + 'PaginationInfo');
    const prevBtn = document.getElementById(tableType + 'PrevBtn');
    const nextBtn = document.getElementById(tableType + 'NextBtn');
    const currentPageSpan = document.getElementById(tableType + 'CurrentPage');
    
    // Eğer toplam kayıt sayısı 10'dan azsa pagination'ı gizle
    if (pagination[tableType].totalItems <= itemsPerPage) {
        paginationContainer.style.display = 'none';
        return;
    } else {
        paginationContainer.style.display = 'flex';
    }
    
    // Filtreleme aktifse farklı mesaj göster
    if (currentFilters.searchTerm || currentFilters.typeFilters.length > 0) {
        info.textContent = `Filtrelenmiş: ${pagination[tableType].totalItems} kayıt gösteriliyor`;
        prevBtn.disabled = true;
        nextBtn.disabled = true;
    } else {
        info.textContent = `Sayfa ${pagination[tableType].currentPage} / ${pagination[tableType].totalPages} (Toplam ${pagination[tableType].totalItems} kayıt)`;
        prevBtn.disabled = pagination[tableType].currentPage === 1;
        nextBtn.disabled = pagination[tableType].currentPage === pagination[tableType].totalPages;
    }
    
    currentPageSpan.textContent = pagination[tableType].currentPage;
}

function showTablePage(tableType) {
    // Eğer filtreleme varsa, pagination uygulama
    if (currentFilters.searchTerm || currentFilters.typeFilters.length > 0) {
        return; // Filtreleme aktifse, applyFilters zaten satırları gösteriyor
    }
    
    const table = document.getElementById(tableType + 'Table');
    const rows = table.querySelectorAll('tbody tr');
    
    // Eğer toplam kayıt sayısı 10'dan azsa, tüm satırları göster
    if (pagination[tableType].totalItems <= itemsPerPage) {
        rows.forEach(row => {
            row.style.display = '';
        });
        return;
    }
    
    // Normal pagination için - tüm satırları gizle
    rows.forEach(row => {
        row.style.display = 'none';
    });
    
    // Sadece mevcut sayfa için gerekli satırları göster
    const startIndex = (pagination[tableType].currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    
    rows.forEach((row, index) => {
        if (index >= startIndex && index < endIndex) {
            row.style.display = '';
        }
    });
}

function changeEquipmentPage(direction) {
    pagination.equipment.currentPage += direction;
    updatePagination('equipment');
}

function changeFaultPage(direction) {
    pagination.fault.currentPage += direction;
    updatePagination('fault');
}

function changeUserPage(direction) {
    pagination.user.currentPage += direction;
    updatePagination('user');
}
</script>
@endsection
