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
  .clickable-alert { cursor: pointer; }
</style>
<!-- Bildirim ve Hatırlatıcılar -->

<div class="alert alert-warning d-flex align-items-center justify-content-between mb-3 clickable-alert" role="alert" id="upcomingAlert">
  <div class="d-flex align-items-center">
    <i class="fas fa-bell fa-lg me-2"></i>
    <div><b>2 ekipmanda yaklaşan bakım var!</b> <span class="small">Bakım planlaması yapmayı unutmayın.</span></div>
  </div>
  <button class="btn btn-sm btn-outline-warning" id="showUpcomingBtn"><i class="fas fa-list"></i> Ürünleri Gör</button>
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

<!-- Gelişmiş Filtreler ve Toplu İşlem Barı -->


<!-- Kontroller Ne Zaman Yapılmış? Tablosu -->


<!-- Satır Detay Paneli (JS ile eklenir) -->

<!-- Yaklaşan Kontroller Modalı -->


<!-- Geciken Kontroller Modalı -->


<!-- Yeni Kontrol Ekle Modalı -->
<div class="modal fade" id="addControlModal" tabindex="-1" aria-labelledby="addControlModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addControlModalLabel">Yeni Kontrol Ekle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <!-- Yeni kontrol ekleme formu buraya gelecek -->
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

<script>
// --- Tablo ve modal işlemleri için güvenli JS ---
// Demo veriler
const acilDurumlarData = [
  { ekipman: 'Akülü Matkap', kategori: 'İnşaat', islem: 'Arıza', tarih: '17.06.2025', sorumlu: 'teknisyen1' },
  { ekipman: 'Hilti Kırıcı', kategori: 'İnşaat', islem: 'Test', tarih: '18.06.2025', sorumlu: 'teknisyen1' },
  { ekipman: 'Test Cihazı', kategori: 'Elektrik', islem: 'Bakım', tarih: '19.06.2025', sorumlu: 'admin' },
];
const yapilacaklarData = [
  { ekipman: 'Jeneratör 5kVA', kategori: 'Elektrik', islem: 'Bakım', tarih: '15.07.2025', sorumlu: 'admin' },
  { ekipman: 'Oksijen Konsantratörü', kategori: 'Medikal', islem: 'Bakım', tarih: '20.06.2025', sorumlu: 'admin' },
  { ekipman: 'Hilti Kırıcı', kategori: 'İnşaat', islem: 'Test', tarih: '18.06.2025', sorumlu: 'teknisyen1' },
  { ekipman: 'Akülü Matkap', kategori: 'İnşaat', islem: 'Arıza', tarih: '17.06.2025', sorumlu: 'teknisyen1' },
  { ekipman: 'UPS 3kVA', kategori: 'Elektrik', islem: 'Bakım', tarih: '25.06.2025', sorumlu: 'admin' },
];
let acilPage = 1, acilPerPage = 2;
let yapPage = 1, yapPerPage = 3;
function renderAcilDurumlarTable() {
  const tbody = document.getElementById('acilDurumlarTableBody');
  tbody.innerHTML = '';
  const start = (acilPage-1)*acilPerPage;
  const end = start+acilPerPage;
  const pageData = acilDurumlarData.slice(start, end);
  pageData.forEach((d, i) => {
    tbody.innerHTML += `<tr>
      <td><b>${d.ekipman}</b> <span class='badge bg-info'>${d.kategori}</span></td>
      <td><span class='badge bg-danger'>${d.islem}</span></td>
      <td>${d.tarih}</td>
      <td>${d.sorumlu}</td>
      <td class='text-end table-actions'>
        <button class='btn btn-sm btn-outline-info' onclick='showAcilModal(${start+i},"detay")'><i class='fas fa-eye'></i></button>
        <button class='btn btn-sm btn-outline-secondary' onclick='showAcilModal(${start+i},"edit")'><i class='fas fa-edit'></i></button>
        <button class='btn btn-sm btn-outline-danger' onclick='showAcilModal(${start+i},"delete")'><i class='fas fa-trash'></i></button>
      </td>
    </tr>`;
  });
  renderAcilDurumlarPagination();
}
function renderAcilDurumlarPagination() {
  const pageCount = Math.ceil(acilDurumlarData.length/acilPerPage);
  const pag = document.getElementById('acilDurumlarPagination');
  pag.innerHTML = '';
  for(let i=1;i<=pageCount;i++) {
    pag.innerHTML += `<li class='page-item${i===acilPage?' active':''}'><a class='page-link' href='#' onclick='gotoAcilPage(${i});return false;'>${i}</a></li>`;
  }
}
function gotoAcilPage(page) {
  acilPage = page;
  renderAcilDurumlarTable();
}
window.gotoAcilPage = gotoAcilPage;
function showAcilModal(idx, type) {
  const d = acilDurumlarData[idx];
  let html = '';
  if(type==='detay') {
    html = `<b>Detay:</b><br>Ekipman: ${d.ekipman}<br>Kategori: ${d.kategori}<br>İşlem: ${d.islem}<br>Tarih: ${d.tarih}<br>Sorumlu: ${d.sorumlu}`;
  } else if(type==='edit') {
    html = `<b>Düzenle:</b><br><input class='form-control mb-2' value='${d.ekipman}'><br><button class='btn btn-primary'>Kaydet</button>`;
  } else if(type==='delete') {
    html = `<b>Silmek istediğinize emin misiniz?</b><br><button class='btn btn-danger mt-2' onclick='deleteAcilDurum(${idx})'>Evet, Sil</button>`;
  }
  var modalBody = document.getElementById('infoModalBody');
  if (modalBody) modalBody.innerHTML = html;
  var modalEl = document.getElementById('infoModal');
  if (modalEl && typeof bootstrap !== 'undefined') {
    var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
  }
}
window.showAcilModal = showAcilModal;
function deleteAcilDurum(idx) {
  acilDurumlarData.splice(idx,1);
  renderAcilDurumlarTable();
  var modalEl = document.getElementById('infoModal');
  if (modalEl && typeof bootstrap !== 'undefined') {
    var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.hide();
  }
}
window.deleteAcilDurum = deleteAcilDurum;
// Yapılması Gerekenler için aynı yapı
function renderYapilacaklarTable() {
  const tbody = document.getElementById('yapilacaklarTableBody');
  tbody.innerHTML = '';
  const start = (yapPage-1)*yapPerPage;
  const end = start+yapPerPage;
  const pageData = yapilacaklarData.slice(start, end);
  pageData.forEach((d, i) => {
    tbody.innerHTML += `<tr>
      <td><b>${d.ekipman}</b> <span class='badge bg-info'>${d.kategori}</span></td>
      <td><span class='badge bg-warning text-dark'>${d.islem}</span></td>
      <td>${d.tarih}</td>
      <td>${d.sorumlu}</td>
      <td class='text-end table-actions'>
        <button class='btn btn-sm btn-outline-info' onclick='showYapModal(${start+i},"detay")'><i class='fas fa-eye'></i></button>
        <button class='btn btn-sm btn-outline-secondary' onclick='showYapModal(${start+i},"edit")'><i class='fas fa-edit'></i></button>
        <button class='btn btn-sm btn-outline-danger' onclick='showYapModal(${start+i},"delete")'><i class='fas fa-trash'></i></button>
      </td>
    </tr>`;
  });
  renderYapilacaklarPagination();
}
function renderYapilacaklarPagination() {
  const pageCount = Math.ceil(yapilacaklarData.length/yapPerPage);
  const pag = document.getElementById('yapilacaklarPagination');
  pag.innerHTML = '';
  for(let i=1;i<=pageCount;i++) {
    pag.innerHTML += `<li class='page-item${i===yapPage?' active':''}'><a class='page-link' href='#' onclick='gotoYapPage(${i});return false;'>${i}</a></li>`;
  }
}
function gotoYapPage(page) {
  yapPage = page;
  renderYapilacaklarTable();
}
window.gotoYapPage = gotoYapPage;
function showYapModal(idx, type) {
  const d = yapilacaklarData[idx];
  let html = '';
  if(type==='detay') {
    html = `<b>Detay:</b><br>Ekipman: ${d.ekipman}<br>Kategori: ${d.kategori}<br>İşlem: ${d.islem}<br>Tarih: ${d.tarih}<br>Sorumlu: ${d.sorumlu}`;
  } else if(type==='edit') {
    html = `<b>Düzenle:</b><br><input class='form-control mb-2' value='${d.ekipman}'><br><button class='btn btn-primary'>Kaydet</button>`;
  } else if(type==='delete') {
    html = `<b>Silmek istediğinize emin misiniz?</b><br><button class='btn btn-danger mt-2' onclick='deleteYapilacak(${idx})'>Evet, Sil</button>`;
  }
  var modalBody = document.getElementById('infoModalBody');
  if (modalBody) modalBody.innerHTML = html;
  var modalEl = document.getElementById('infoModal');
  if (modalEl && typeof bootstrap !== 'undefined') {
    var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
  }
}
window.showYapModal = showYapModal;
function deleteYapilacak(idx) {
  yapilacaklarData.splice(idx,1);
  renderYapilacaklarTable();
  var modalEl = document.getElementById('infoModal');
  if (modalEl && typeof bootstrap !== 'undefined') {
    var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.hide();
  }
}
window.deleteYapilacak = deleteYapilacak;
// Sayfa yüklendiğinde tabloları render et
window.addEventListener('DOMContentLoaded', function() {
  renderAcilDurumlarTable();
  renderYapilacaklarTable();
});
</script>
@endsection