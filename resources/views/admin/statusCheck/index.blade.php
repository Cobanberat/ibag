@extends('layouts.admin')
@section('content')

@vite('resources/css/statusCheck.css')
<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
      <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a></li>
      <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Durum Kontrolü' }}</li>
  </ol>
</nav>
<!-- Bildirim ve Hatırlatıcılar -->

<div class="alert alert-warning d-flex align-items-center justify-content-between mb-3 clickable-alert" role="alert" id="upcomingAlert">
  <div class="d-flex align-items-center">
    <i class="fas fa-bell fa-lg me-2"></i>
    <div><b>2 ekipmanda yaklaşan bakım var!</b> <span class="small">Bakım planlaması yapmayı unutmayın.</span></div>
  </div>
  <button class="btn btn-sm btn-outline-warning" id="showUpcomingBtn"><i class="fas fa-list"></i> Ürünleri Gör</button>
</div>

<!-- Filtreleme Barı -->
<div class="mb-3">
  <input type="text" class="form-control" id="tabloAramaInput" placeholder="Ekipman, işlem, sorumlu...">
</div>

<!-- Sekmeler -->
<ul class="nav nav-tabs mb-3" id="statusTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="acil-tab" data-bs-toggle="tab" data-bs-target="#acil" type="button" role="tab">Acil Durumlar</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="yapilacaklar-tab" data-bs-toggle="tab" data-bs-target="#yapilacaklar" type="button" role="tab">Yapılması Gerekenler</button>
  </li>
</ul>
<div class="tab-content" id="statusTabContent">
  <div class="tab-pane fade show active" id="acil" role="tabpanel">
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
                <th>Sorumlu</th>
                <th class="text-end">Aksiyon</th>
              </tr>
            </thead>
            <tbody id="acilDurumlarTableBody"></tbody>
          </table>
          <nav><ul class="pagination justify-content-end m-3" id="acilDurumlarPagination"></ul></nav>
        </div>
      </div>
    </div>
  </div>
  <div class="tab-pane fade" id="yapilacaklar" role="tabpanel">
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
                <th>Sorumlu</th>
                <th class="text-end">Aksiyon</th>
              </tr>
            </thead>
            <tbody id="yapilacaklarTableBody"></tbody>
          </table>
          <nav><ul class="pagination justify-content-end m-3" id="yapilacaklarPagination"></ul></nav>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Satır Detay Paneli (JS ile eklenir) -->
<div id="rowDetailPanel"></div>

<!-- Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="infoModalLabel">Detaylı Bilgi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body" id="infoModalBody">
        <!-- İçerik JS ile doldurulacak -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>

@vite('resources/js/statusCheck.js')

<script>
// Filtreleme fonksiyonu
function filterActiveTabTable() {
  const search = document.getElementById('tabloAramaInput').value.trim().toLowerCase();
  // Aktif sekmeyi bul
  const activeTab = document.querySelector('.tab-pane.active.show');
  if (!activeTab) return;
  const tbody = activeTab.querySelector('tbody');
  if (!tbody) return;
  const rows = tbody.querySelectorAll('tr');
  rows.forEach(tr => {
    const text = tr.innerText.toLowerCase();
    tr.style.display = (search === '' || text.includes(search)) ? '' : 'none';
  });
}
document.getElementById('tabloAramaInput').addEventListener('input', filterActiveTabTable);
// Sekme değişince filtreyi uygula
const tabBtns = document.querySelectorAll('#statusTab button[data-bs-toggle="tab"]');
tabBtns.forEach(btn => {
  btn.addEventListener('shown.bs.tab', function() {
    filterActiveTabTable();
  });
});
</script>

@endsection