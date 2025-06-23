@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
  .table thead th { background: #f7f7fa; font-weight: bold; }
  .badge-overdue { background: #ff4d4f; color: #fff; }
  .badge-upcoming { background: #ffec3d; color: #333; }
  .badge-done { background: #28a745; color: #fff; }
  .table-actions .btn { margin-right: 0.2rem; }
  .row-detail-panel { background: #f8f9fa; border-bottom: 2px solid #e0e7ff; }
  .daterange-box { position: relative; }
  .daterange-box .fa-calendar-alt { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #888; }
  .daterange-input { padding-left: 2.2rem; }
</style>
<!-- Bildirim ve Hatırlatıcılar -->
<div class="alert alert-warning d-flex align-items-center justify-content-between mb-3" role="alert">
  <div class="d-flex align-items-center">
    <i class="fas fa-bell fa-lg me-2"></i>
    <div><b>2 ekipmanda yaklaşan bakım var!</b> <span class="small">Bakım planlaması yapmayı unutmayın.</span></div>
  </div>
  <button class="btn btn-sm btn-outline-warning" id="showUpcomingBtn"><i class="fas fa-list"></i> Ürünleri Gör</button>
</div>
<div class="alert alert-danger d-flex align-items-center justify-content-between mb-3" role="alert">
  <div class="d-flex align-items-center">
    <i class="fas fa-exclamation-triangle fa-lg me-2"></i>
    <div><b>1 ekipmanda geciken işlem var!</b> <span class="small">Acil müdahale gereklidir.</span></div>
  </div>
  <button class="btn btn-sm btn-outline-danger" id="showOverdueBtn"><i class="fas fa-list"></i> Ürünleri Gör</button>
</div>

<!-- Acil Durumlar Tablosu -->
<div class="card shadow-sm mb-4">
  <div class="card-header bg-danger text-white"><i class="fas fa-exclamation-circle me-2"></i> Acil Durumlar</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="acilDurumlarTable">
        <thead>
          <tr>
            <th>Ekipman</th>
            <th>İşlem</th>
            <th>Planlanan Tarih</th>
            <th>Kalan/Gecikme</th>
            <th>Sorumlu</th>
            <th class="text-end">Aksiyon</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><b>Akülü Matkap</b> <span class='badge bg-info'>İnşaat</span></td>
            <td><span class='badge bg-danger'>Arıza</span></td>
            <td>17.06.2025</td>
            <td><span class='badge badge-overdue'>Bugün</span></td>
            <td>teknisyen1</td>
            <td class='text-end table-actions'>
              <button class='btn btn-sm btn-outline-info'><i class='fas fa-eye'></i></button>
              <button class='btn btn-sm btn-outline-secondary'><i class='fas fa-edit'></i></button>
              <button class='btn btn-sm btn-outline-danger'><i class='fas fa-trash'></i></button>
            </td>
          </tr>
          <tr>
            <td><b>Hilti Kırıcı</b> <span class='badge bg-info'>İnşaat</span></td>
            <td><span class='badge bg-info'>Test</span></td>
            <td>18.06.2025</td>
            <td><span class='badge badge-overdue'>1 gün gecikti</span></td>
            <td>teknisyen1</td>
            <td class='text-end table-actions'>
              <button class='btn btn-sm btn-outline-info'><i class='fas fa-eye'></i></button>
              <button class='btn btn-sm btn-outline-secondary'><i class='fas fa-edit'></i></button>
              <button class='btn btn-sm btn-outline-danger'><i class='fas fa-trash'></i></button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Ürünleri Gör Modalı -->
<div class="modal fade" id="upcomingModal" tabindex="-1" aria-labelledby="upcomingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning bg-opacity-25">
        <h5 class="modal-title" id="upcomingModalLabel"><i class="fas fa-bell me-2 text-warning"></i>Yaklaşan Bakım Gereken Ürünler</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Jeneratör 5kVA <span class="badge bg-warning text-dark">28 gün</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Oksijen Konsantratörü <span class="badge bg-warning text-dark">3 gün</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="overdueModal" tabindex="-1" aria-labelledby="overdueModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger bg-opacity-25">
        <h5 class="modal-title" id="overdueModalLabel"><i class="fas fa-exclamation-triangle me-2 text-danger"></i>Geciken İşlem Olan Ürünler</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Akülü Matkap <span class="badge bg-danger">Bugün</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Hilti Kırıcı <span class="badge bg-danger">1 gün gecikti</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- Gelişmiş Filtreler ve Toplu İşlem Barı -->
<div class="d-flex flex-wrap gap-2 align-items-center mb-2">
  <button class="btn btn-success" id="addControlBtn"><i class="fas fa-plus"></i> Yeni Kontrol Ekle</button>
  <button class="btn btn-outline-danger" id="bulkDeleteBtn" disabled><i class="fas fa-trash"></i> Toplu Sil</button>
  <button class="btn btn-outline-secondary" id="bulkReportBtn" disabled><i class="fas fa-file-download"></i> Toplu Rapor İndir</button>
  <input type="text" class="form-control form-control-sm ms-auto" style="max-width:180px;" id="searchInput" placeholder="Ara...">
  <div class="daterange-box">
    <i class="fas fa-calendar-alt"></i>
    <input type="text" class="form-control form-control-sm daterange-input" id="filterDateRange" placeholder="Tarih Aralığı Seçin" style="max-width:180px;">
  </div>
  <select class="form-select form-select-sm" id="filterType" style="max-width:120px;">
    <option value="">Tür (Tümü)</option>
    <option>Bakım</option>
    <option>Test</option>
    <option>Arıza</option>
    <option>Taşınma</option>
    <option>Kullanım</option>
  </select>
  <select class="form-select form-select-sm" id="filterSorumlu" style="max-width:120px;">
    <option value="">Sorumlu (Tümü)</option>
    <option>admin</option>
    <option>teknisyen1</option>
  </select>
</div>

<!-- Kontroller Ne Zaman Yapılmış? Tablosu -->
<div class="card shadow-sm mb-4">
  <div class="card-header bg-dark text-white"><i class="fas fa-history me-2"></i> Kontroller Ne Zaman Yapılmış?</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="kontrolGecmisTable">
        <thead>
          <tr>
            <th><input type="checkbox" id="selectAllRows"></th>
            <th>Ekipman</th>
            <th>Kontrol Türü</th>
            <th>Açıklama</th>
            <th>Sorumlu</th>
            <th>Tarih</th>
            <th>Dosya</th>
            <th>Yorum</th>
            <th class="text-end">Aksiyon</th>
          </tr>
        </thead>
        <tbody id="kontrolGecmisTbody">
          <!-- JS ile doldurulacak -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Satır Detay Paneli (JS ile eklenir) -->
<div id="rowDetailPanelContainer"></div>

<!-- Yeni Kontrol Ekle Modalı -->
<div class="modal fade" id="addControlModal" tabindex="-1" aria-labelledby="addControlModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addControlModalLabel">Yeni Kontrol Ekle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <form id="addControlForm">
          <div class="mb-2">
            <label class="form-label">Ekipman</label>
            <select class="form-select" id="addControlEkipman" required>
              <option>Jeneratör 5kVA</option>
              <option>Oksijen Konsantratörü</option>
              <option>Hilti Kırıcı</option>
              <option>Akülü Matkap</option>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label">Kontrol Türü</label>
            <select class="form-select" id="addControlType" required>
              <option>Bakım</option>
              <option>Test</option>
              <option>Arıza</option>
              <option>Taşınma</option>
              <option>Kullanım</option>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label">Açıklama</label>
            <input type="text" class="form-control" id="addControlDesc" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Sorumlu</label>
            <select class="form-select" id="addControlSorumlu" required>
              <option>admin</option>
              <option>teknisyen1</option>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label">Tarih</label>
            <input type="date" class="form-control" id="addControlDate" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Yorum</label>
            <input type="text" class="form-control" id="addControlYorum">
          </div>
          <button type="submit" class="btn btn-success w-100">Ekle</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Yapılması Gereken Durumlar Tablosu -->
<div class="card shadow-sm mb-4">
  <div class="card-header bg-info text-white"><i class="fas fa-calendar-check me-2"></i> Yapılması Gereken Durumlar</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="yapilacaklarTable">
        <thead>
          <tr>
            <th>Ekipman</th>
            <th>İşlem</th>
            <th>Planlanan Tarih</th>
            <th>Kalan/Gecikme</th>
            <th>Sorumlu</th>
            <th class="text-end">Aksiyon</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><b>Jeneratör 5kVA</b> <span class='badge bg-info'>Elektrik</span></td>
            <td><span class='badge bg-warning text-dark'>Bakım</span></td>
            <td>15.07.2025</td>
            <td><span class='badge badge-upcoming'>28 gün</span></td>
            <td>admin</td>
            <td class='text-end table-actions'>
              <button class='btn btn-sm btn-outline-info'><i class='fas fa-eye'></i></button>
              <button class='btn btn-sm btn-outline-secondary'><i class='fas fa-edit'></i></button>
              <button class='btn btn-sm btn-outline-danger'><i class='fas fa-trash'></i></button>
            </td>
          </tr>
          <tr>
            <td><b>Oksijen Konsantratörü</b> <span class='badge bg-info'>Medikal</span></td>
            <td><span class='badge bg-warning text-dark'>Bakım</span></td>
            <td>20.06.2025</td>
            <td><span class='badge badge-upcoming'>3 gün</span></td>
            <td>admin</td>
            <td class='text-end table-actions'>
              <button class='btn btn-sm btn-outline-info'><i class='fas fa-eye'></i></button>
              <button class='btn btn-sm btn-outline-secondary'><i class='fas fa-edit'></i></button>
              <button class='btn btn-sm btn-outline-danger'><i class='fas fa-trash'></i></button>
            </td>
          </tr>
          <tr>
            <td><b>Hilti Kırıcı</b> <span class='badge bg-info'>İnşaat</span></td>
            <td><span class='badge bg-info'>Test</span></td>
            <td>18.06.2025</td>
            <td><span class='badge badge-upcoming'>1 gün</span></td>
            <td>teknisyen1</td>
            <td class='text-end table-actions'>
              <button class='btn btn-sm btn-outline-info'><i class='fas fa-eye'></i></button>
              <button class='btn btn-sm btn-outline-secondary'><i class='fas fa-edit'></i></button>
              <button class='btn btn-sm btn-outline-danger'><i class='fas fa-trash'></i></button>
            </td>
          </tr>
          <tr>
            <td><b>Akülü Matkap</b> <span class='badge bg-info'>İnşaat</span></td>
            <td><span class='badge bg-danger'>Arıza</span></td>
            <td>17.06.2025</td>
            <td><span class='badge badge-overdue'>Bugün</span></td>
            <td>teknisyen1</td>
            <td class='text-end table-actions'>
              <button class='btn btn-sm btn-outline-info'><i class='fas fa-eye'></i></button>
              <button class='btn btn-sm btn-outline-secondary'><i class='fas fa-edit'></i></button>
              <button class='btn btn-sm btn-outline-danger'><i class='fas fa-trash'></i></button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Satır Detay Paneli (JS ile eklenir) -->
<div id="rowDetailPanel"></div>

<!-- ... diğer modallar ... -->
<script>
// Örnek veri ve toplu seçim, filtreleme, detay paneli, hızlı ekleme için temel JS altyapısı burada olmalı.
// Tarih aralığı picker
flatpickr('#filterDateRange', {mode:'range', dateFormat:'Y-m-d', locale:{rangeSeparator:' - '}});
// Filtreleme fonksiyonunda tarih aralığı kontrolü
function filterByDateRange(dateStr, rangeStr) {
  if(!rangeStr) return true;
  const [start, end] = rangeStr.split(' - ');
  if(!start||!end) return true;
  const d = new Date(dateStr);
  return d >= new Date(start) && d <= new Date(end);
}
// ... diğer filtreleme fonksiyonlarında filterByDateRange kullanılmalı ...
document.getElementById('showUpcomingBtn').onclick = function() {
  new bootstrap.Modal(document.getElementById('upcomingModal')).show();
};
document.getElementById('showOverdueBtn').onclick = function() {
  new bootstrap.Modal(document.getElementById('overdueModal')).show();
};
</script>
@endsection