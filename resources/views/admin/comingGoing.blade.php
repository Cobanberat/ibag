@extends('layouts.admin')
@section('content')
<style>
.table thead th {
  background: #0d6efd;
  color: #fff;
  font-weight: 700;
  border: none;
}
.table tbody tr {
  background: #fff;
  transition: background 0.18s;
}
.table tbody tr:hover {
  background: #f3f4f8;
}
/* Pagination güzelleştirme */
.custom-pagination {
  display: flex;
  justify-content: flex-end;
  margin-top: 1.5em;
  position: sticky;
  bottom: 0;
  background: #fff;
  z-index: 10;
  padding-bottom: 0.5em;
}
.custom-pagination .page-link { color: #0d6efd; font-weight: 600; border-radius: 0.7em; margin: 0 0.15em; border: 1.5px solid #e3e8f0; transition: background 0.18s, color 0.18s; }
.custom-pagination .page-item.active .page-link, .custom-pagination .page-link:hover { background: #0d6efd; color: #fff; border-color: #0d6efd; }
body.dark-mode .custom-pagination .page-link { color: #36b3f6; border-color: #23272f; background: #23272f; }
body.dark-mode .custom-pagination .page-item.active .page-link, body.dark-mode .custom-pagination .page-link:hover { background: #36b3f6; color: #fff; border-color: #36b3f6; }
/* Filtre barı ve tablo güzelleştirme */
.filter-bar {
  background: #f8fafc;
  border-radius: 1.2rem;
  box-shadow: 0 2px 12px #0d6efd11;
  padding: 1.2rem 1.5rem;
  margin-bottom: 1.2rem;
  display: flex;
  flex-wrap: wrap;
  gap: 0.7rem;
  align-items: center;
  border: 1.5px solid #e3e8f0;
  backdrop-filter: blur(8px);
}
body.dark-mode .filter-bar { background: #23272f; border-color: #23272f; }
.filter-bar input, .filter-bar select {
  min-width: 140px;
  border-radius: 0.7rem;
  font-size: 1.08em;
  background: #f8fafc;
  border: 1.5px solid #d1d5db;
  box-shadow: 0 1px 4px #0d6efd08;
}
.filter-bar input:focus, .filter-bar select:focus {
  border-color: #0d6efd;
  box-shadow: 0 0 0 2px #0d6efd22;
  outline: none;
}
.filter-bar label { font-weight: 600; color: #0d6efd; margin-right: 0.3em; }
body.dark-mode .filter-bar label { color: #36b3f6; }
.table thead th { font-size: 1.08em; letter-spacing: 0.01em; }
.table { background: #fff; border-radius: 1.2em; overflow: hidden; }
body.dark-mode .table { background: #23272f; }
.badge-success { background: #00c896; }
.badge-warning { background: #ffb300; }
.badge-danger { background: #ff4d4f; }
/* İşlemi Bitir Modal */
.modal-lg { max-width: 900px; }
.modal-header.bg-success { background: #00c896; }
.btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); }
/* Ekstra modal ve kart güzelleştirme */
.card.shadow-sm { border-radius: 1.2em; box-shadow: 0 4px 18px #0d6efd11; border: 1.5px solid #e3e8f0; }
.card .form-label { font-weight: 600; color: #0d6efd; }
.card .form-select, .card .form-control { border-radius: 0.7em; }
.card .badge.bg-primary { background: #36b3f6; }
.card .fw-bold { color: #0d6efd; }
body.dark-mode .card { background: #23272f; color: #f8f9fa; border-color: #23272f; }
body.dark-mode .card .form-label { color: #36b3f6; }
body.dark-mode .card .badge.bg-primary { background: #0d6efd; }
/* Sekmeler güzelleştirme */
.nav-tabs.approval-tabs {
  border-bottom: 2.5px solid #e3e8f0;
  background: linear-gradient(90deg, #f8fafc 60%, #e3e8f0 100%);
  border-radius: 1.2em 1.2em 0 0;
  box-shadow: 0 2px 12px #0d6efd11;
  padding: 0.5em 1em 0 1em;
}
.nav-tabs.approval-tabs .nav-link {
  color: #0d6efd;
  font-weight: 700;
  font-size: 1.13em;
  border: none;
  border-radius: 1.2em 1.2em 0 0;
  margin-right: 0.3em;
  background: none;
  transition: background 0.18s, color 0.18s;
}
.nav-tabs.approval-tabs .nav-link.active {
  background: linear-gradient(90deg, #0d6efd 0%, #36b3f6 100%);
  color: #fff;
  box-shadow: 0 4px 18px #0d6efd22;
}
.nav-tabs.approval-tabs .nav-link:hover:not(.active) {
  background: #e3e8f0;
  color: #0d6efd;
}
body.dark-mode .nav-tabs.approval-tabs { background: linear-gradient(90deg, #23272f 60%, #181c22 100%); border-color: #23272f; }
body.dark-mode .nav-tabs.approval-tabs .nav-link { color: #36b3f6; }
body.dark-mode .nav-tabs.approval-tabs .nav-link.active { background: linear-gradient(90deg, #23272f 0%, #0d6efd 100%); color: #fff; }
body.dark-mode .nav-tabs.approval-tabs .nav-link:hover:not(.active) { background: #23272f; color: #36b3f6; }

/* Tablo ve kutu güzelleştirme */
.table { background: #fff; border-radius: 1.2em; overflow: hidden; box-shadow: 0 4px 18px #0d6efd11; }
.table thead th { font-size: 1.08em; letter-spacing: 0.01em; background: linear-gradient(90deg, #f8fafc 60%, #e3e8f0 100%); color: #0d6efd; border: none; }
.table tbody tr { background: #fff; transition: background 0.18s; }
.table tbody tr:hover { background: #e3e8f0; }
body.dark-mode .table { background: #23272f; box-shadow: 0 4px 18px #0d6efd22; }
body.dark-mode .table thead th { background: linear-gradient(90deg, #23272f 60%, #0d6efd 100%); color: #fff; }
body.dark-mode .table tbody tr { background: #23272f; color: #f8f9fa; }
.table td, .table th { vertical-align: middle !important; }

/* Filtre barı güzelleştirme */
.filter-bar {
  background: linear-gradient(90deg, #f8fafc 60%, #e3e8f0 100%);
  border-radius: 1.2rem;
  box-shadow: 0 2px 12px #0d6efd11;
  padding: 1.2rem 1.5rem;
  margin-bottom: 1.2rem;
  display: flex;
  flex-wrap: wrap;
  gap: 0.7rem;
  align-items: center;
  border: 1.5px solid #e3e8f0;
  backdrop-filter: blur(8px);
}
body.dark-mode .filter-bar { background: linear-gradient(90deg, #23272f 60%, #181c22 100%); border-color: #23272f; }
.filter-bar input, .filter-bar select {
  min-width: 140px;
  border-radius: 0.7rem;
  font-size: 1.08em;
  background: #fff;
  border: 1.5px solid #d1d5db;
  box-shadow: 0 1px 4px #0d6efd08;
  padding: 0.5em 1em;
}
.filter-bar input:focus, .filter-bar select:focus {
  border-color: #0d6efd;
  box-shadow: 0 0 0 2px #0d6efd22;
  outline: none;
}
.filter-bar label { font-weight: 600; color: #0d6efd; margin-right: 0.3em; }
body.dark-mode .filter-bar label { color: #36b3f6; }
.filter-bar .btn { font-size: 1.05em; padding: 0.45em 1.2em; border-radius: 0.7em; }

/* Butonlar ve ikonlar */
.btn-info, .btn-success { font-weight: 600; letter-spacing: 0.01em; }
.btn-info i, .btn-success i { margin-right: 0.3em; }
.btn-info { background: linear-gradient(90deg, #36b3f6 0%, #0d6efd 100%); border: none; color: #fff; }
.btn-info:hover { background: #0d6efd; color: #fff; }
.btn-success { background: linear-gradient(90deg, #00c896 0%, #36b3f6 100%); border: none; color: #fff; }
.btn-success:hover { background: #00c896; color: #fff; }
.btn-outline-primary { border-width: 2px; }

/* Responsive iyileştirme */
@media (max-width: 900px) {
  .filter-bar { flex-direction: column; align-items: stretch; gap: 0.5em; padding: 1em 0.7em; }
  .table { font-size: 0.97em; }
}
@media (max-width: 600px) {
  .filter-bar { padding: 0.7em 0.3em; }
  .table thead th, .table td { padding: 0.5em 0.3em; }
}
</style>
<div class="animated-title"><i class="fas fa-truck"></i> Giden-Gelen Ekipman İşlemleri</div>
<!-- Sekmeler -->
<ul class="nav nav-tabs approval-tabs mb-3" id="comingGoingTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="giden-tab" data-bs-toggle="tab" data-bs-target="#gidenTab" type="button" role="tab">Gidenler</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="gelen-tab" data-bs-toggle="tab" data-bs-target="#gelenTab" type="button" role="tab">Gelenler</button>
  </li>
</ul>
<div class="tab-content" id="comingGoingTabContent">
  <div class="tab-pane fade show active" id="gidenTab" role="tabpanel">
<div class="col-md-12 mb-5">
  <form class="filter-bar mb-2" id="gidenFilterForm" onsubmit="return false;">
    <label>Arama:</label>
    <input type="text" class="form-control" id="gidenSearch" placeholder="Lokasyon, yetkili...">
    <label>Lokasyon:</label>
    <select class="form-select" id="gidenLokasyon">
      <option value="">Tümü</option>
    </select>
    <label>Yetkili:</label>
    <select class="form-select" id="gidenYetkili">
      <option value="">Tümü</option>
    </select>
    <button class="btn btn-outline-primary btn-sm ms-auto" type="button" onclick="clearGidenFilters()">Temizle</button>
  </form>
  <h4 class="mb-3">Gidenler</h4>
  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead>
        <tr>
          <th>Lokasyon</th>
          <th>Yetkili</th>
          <th>Tarih</th>
          <th>Detay</th>
          <th>İşlemi Bitir</th>
        </tr>
      </thead>
      <tbody id="gidenTableBody"></tbody>
    </table>
    <nav class="custom-pagination"><ul class="pagination" id="gidenPagination"></ul></nav>
  </div>
</div>
  </div>
  <div class="tab-pane fade" id="gelenTab" role="tabpanel">
    <div class="col-md-12">
      <form class="filter-bar mb-2" id="gelenFilterForm" onsubmit="return false;">
        <label>Arama:</label>
        <input type="text" class="form-control" id="gelenSearch" placeholder="Lokasyon, yetkili...">
        <label>Lokasyon:</label>
        <select class="form-select" id="gelenLokasyon">
          <option value="">Tümü</option>
        </select>
        <label>Yetkili:</label>
        <select class="form-select" id="gelenYetkili">
          <option value="">Tümü</option>
        </select>
        <label>Durum:</label>
        <select class="form-select" id="gelenDurum">
          <option value="">Tümü</option>
          <option value="Sorunsuz">Sorunsuz</option>
          <option value="Hasarlı">Hasarlı</option>
          <option value="Eksik">Eksik</option>
        </select>
        <button class="btn btn-outline-primary btn-sm ms-auto" type="button" onclick="clearGelenFilters()">Temizle</button>
      </form>
      <h4 class="mb-3">Gelenler</h4>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead>
            <tr>
              <th>Lokasyon</th>
              <th>Yetkili</th>
              <th>Tarih</th>
              <th>Durum</th>
              <th>Detay</th>
            </tr>
          </thead>
          <tbody id="gelenTableBody"></tbody>
        </table>
        <nav class="custom-pagination"><ul class="pagination" id="gelenPagination"></ul></nav>
      </div>
    </div>
  </div>
</div>
<!-- Detay Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel">İşlem Detayı</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body" id="detailModalBody">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>
<!-- İşlemi Bitir Modal -->
<div class="modal fade" id="finishModal" tabindex="-1" aria-labelledby="finishModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="finishModalLabel">Dönüş İşlemini Tamamla</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <form id="finishForm">
        <div class="modal-body" id="finishModalBody"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
          <button type="submit" class="btn btn-success">Onayla ve Gelenlere Ekle</button>
        </div>
      </form>
    </div>
  </div>
</div>
@push('scripts')
<script>
// Global pagination değişkenleri
let perPage = 5, gidenPage = 1, gelenPage = 1;
// Demo veri (sadeleştirilmiş)
const comingGoingData = [
  {
    id: '2025-ANK-021',
    date: '2025-07-20',
    type: 'Gidiş',
    location: 'Ankara - Sincan',
    person: 'Ayşe Yılmaz',
    ekipmanlar: [
      { isim: 'UPS 3kVA', adet: 2, resim: '/images/ups-return.jpg' },
      { isim: 'Kask', adet: 10, resim: '/images/helmet-return.jpg' }
    ],
    description: 'UPS ve Kask Ankara Sincan bölgesine gönderildi. Batarya değişimi sonrası sorunsuz döndü.'
  },
  {
    id: '2025-IST-011',
    date: '2025-07-18',
    type: 'Dönüş',
    location: 'İstanbul - Kadıköy',
    person: 'Mehmet Kara',
    status: 'Sorunsuz',
    statusClass: 'success',
    ekipmanlar: [
      { isim: 'El Feneri', adet: 5, resim: '/images/flashlight.jpg' }
    ],
    description: 'El fenerleri İstanbul Kadıköy bölgesinden sorunsuz şekilde döndü.'
  },
  {
    id: '2025-IZM-005',
    date: '2025-07-15',
    type: 'Gidiş',
    location: 'İzmir - Bornova',
    person: 'Fatma Demir',
    ekipmanlar: [
      { isim: 'Çadır', adet: 3, resim: '/images/tent.jpg' }
    ],
    description: 'Çadırlar İzmir Bornova bölgesine gönderildi. 1 çadır hasarlı olarak döndü.'
  },
  {
    id: '2025-IZM-005-RET',
    date: '2025-07-18',
    type: 'Dönüş',
    location: 'İzmir - Bornova',
    person: 'Fatma Demir',
    status: 'Hasarlı',
    statusClass: 'danger',
    ekipmanlar: [
      { isim: 'Çadır', adet: 3, resim: '/images/tent.jpg' }
    ],
    description: 'Çadırlar İzmir Bornova bölgesine gönderildi. 1 çadır hasarlı olarak döndü.'
  },
  {
    id: '2025-ANK-022-RET',
    date: '2025-07-22',
    type: 'Dönüş',
    location: 'Ankara - Sincan',
    person: 'Ayşe Yılmaz',
    status: 'Eksik',
    statusClass: 'warning',
    ekipmanlar: [
      { isim: 'Kask', adet: 8, resim: '/images/helmet-return.jpg' }
    ],
    description: 'Kasklar Ankara Sincan bölgesine gönderildi. 2 adet kayıp olarak bildirildi.'
  },
];
// Pagination ve tablo render
function getUnique(arr, key) {
  return [...new Set(arr.map(x => x[key]))].filter(Boolean);
}
function renderTables() {
  // Gidenler
  let gidenData = comingGoingData.filter(x => x.type === 'Gidiş');
  // Filtreler
  const gidenSearch = document.getElementById('gidenSearch').value.toLowerCase();
  const gidenLokasyon = document.getElementById('gidenLokasyon').value;
  const gidenYetkili = document.getElementById('gidenYetkili').value;
  if (gidenSearch) gidenData = gidenData.filter(x => x.location.toLowerCase().includes(gidenSearch) || x.person.toLowerCase().includes(gidenSearch));
  if (gidenLokasyon) gidenData = gidenData.filter(x => x.location === gidenLokasyon);
  if (gidenYetkili) gidenData = gidenData.filter(x => x.person === gidenYetkili);
  // Filtre selectlerini doldur
  const lokasyonlar = getUnique(comingGoingData.filter(x=>x.type==='Gidiş'), 'location');
  const yetkililer = getUnique(comingGoingData.filter(x=>x.type==='Gidiş'), 'person');
  document.getElementById('gidenLokasyon').innerHTML = '<option value="">Tümü</option>' + lokasyonlar.map(l=>`<option value="${l}">${l}</option>`).join('');
  document.getElementById('gidenYetkili').innerHTML = '<option value="">Tümü</option>' + yetkililer.map(y=>`<option value="${y}">${y}</option>`).join('');
  const gidenTotal = gidenData.length;
  const gidenPageCount = Math.ceil(gidenTotal/perPage);
  const gidenStart = (gidenPage-1)*perPage;
  const gidenEnd = gidenStart+perPage;
  const gidenRows = gidenData.slice(gidenStart, gidenEnd).map(item => `
    <tr>
      <td>${item.location}</td>
      <td>${item.person}</td>
      <td>${item.date}</td>
      <td><button class="btn btn-info btn-sm" onclick="showDetail('${item.id}')"><i class='fas fa-eye'></i>Detay</button></td>
      <td><button class="btn btn-success btn-sm" onclick="openFinishModal('${item.id}')"><i class='fas fa-check-circle'></i>İşlemi Bitir</button></td>
    </tr>
  `).join('');
  document.getElementById('gidenTableBody').innerHTML = gidenRows;
  // Gidenler pagination
  let gidenPag = '';
  for(let i=1;i<=gidenPageCount;i++) {
    gidenPag += `<li class="page-item${i===gidenPage?' active':''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
  }
  document.getElementById('gidenPagination').innerHTML = gidenPag;
  // Gelenler
  let gelenData = comingGoingData.filter(x => x.type === 'Dönüş');
  // Filtreler
  const gelenSearch = document.getElementById('gelenSearch').value.toLowerCase();
  const gelenLokasyon = document.getElementById('gelenLokasyon').value;
  const gelenYetkili = document.getElementById('gelenYetkili').value;
  const gelenDurum = document.getElementById('gelenDurum').value;
  if (gelenSearch) gelenData = gelenData.filter(x => x.location.toLowerCase().includes(gelenSearch) || x.person.toLowerCase().includes(gelenSearch));
  if (gelenLokasyon) gelenData = gelenData.filter(x => x.location === gelenLokasyon);
  if (gelenYetkili) gelenData = gelenData.filter(x => x.person === gelenYetkili);
  if (gelenDurum) gelenData = gelenData.filter(x => x.status === gelenDurum);
  // Filtre selectlerini doldur
  const gelenLokasyonlar = getUnique(comingGoingData.filter(x=>x.type==='Dönüş'), 'location');
  const gelenYetkililer = getUnique(comingGoingData.filter(x=>x.type==='Dönüş'), 'person');
  document.getElementById('gelenLokasyon').innerHTML = '<option value="">Tümü</option>' + gelenLokasyonlar.map(l=>`<option value="${l}">${l}</option>`).join('');
  document.getElementById('gelenYetkili').innerHTML = '<option value="">Tümü</option>' + gelenYetkililer.map(y=>`<option value="${y}">${y}</option>`).join('');
  const gelenTotal = gelenData.length;
  const gelenPageCount = Math.ceil(gelenTotal/perPage);
  const gelenStart = (gelenPage-1)*perPage;
  const gelenEnd = gelenStart+perPage;
  const gelenRows = gelenData.slice(gelenStart, gelenEnd).map(item => `
    <tr>
      <td>${item.location}</td>
      <td>${item.person}</td>
      <td>${item.date}</td>
      <td><span class="badge badge-${item.statusClass}">${item.status}</span></td>
      <td><button class="btn btn-info btn-sm" onclick="showDetail('${item.id}')"><i class='fas fa-eye'></i>Detay</button></td>
    </tr>
  `).join('');
  document.getElementById('gelenTableBody').innerHTML = gelenRows;
  // Gelenler pagination
  let gelenPag = '';
  for(let i=1;i<=gelenPageCount;i++) {
    gelenPag += `<li class="page-item${i===gelenPage?' active':''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
  }
  document.getElementById('gelenPagination').innerHTML = gelenPag;
}
// Detay modalı
function showDetail(id) {
  const item = comingGoingData.find(x => x.id === id);
  if (!item) return;
  let html = `<ul class='list-group mb-3'>
    <li class='list-group-item'><b>Lokasyon:</b> ${item.location}</li>
    <li class='list-group-item'><b>Yetkili:</b> ${item.person}</li>
    <li class='list-group-item'><b>Tarih:</b> ${item.date}</li>`;
  if (item.status) html += `<li class='list-group-item'><b>Durum:</b> <span class='badge badge-${item.statusClass}'>${item.status}</span></li>`;
  if (item.description) html += `<li class='list-group-item'><b>Açıklama:</b> ${item.description}</li>`;
  html += '</ul>';
  if (item.ekipmanlar && item.ekipmanlar.length > 0) {
    html += `<div class='mb-2'><b>Ekipmanlar:</b></div><div class='row'>`;
    item.ekipmanlar.forEach(eq => {
      html += `<div class='col-6 col-md-4 mb-3 text-center'>
        <img src='${eq.resim}' alt='${eq.isim}' class='img-fluid rounded mb-2' style='max-height:80px;'>
        <div><b>${eq.isim}</b></div>
        <div>Adet: ${eq.adet}</div>
      </div>`;
    });
    html += '</div>';
  }
  document.getElementById('detailModalBody').innerHTML = html;
  var modal = new bootstrap.Modal(document.getElementById('detailModal'));
  modal.show();
}
// İşlemi Bitir modalı
let finishItemId = null;
function openFinishModal(id) {
  finishItemId = id;
  const item = comingGoingData.find(x => x.id === id);
  if (!item) return;
  let html = `<div class='mb-3'><b>Lokasyon:</b> ${item.location} <br><b>Yetkili:</b> ${item.person}</div>`;
  if (item.ekipmanlar && item.ekipmanlar.length > 0) {
    html += `<div class='row'>`;
    item.ekipmanlar.forEach((eq, idx) => {
      html += `<div class='col-md-6 mb-4'>
        <div class='card shadow-sm h-100'>
          <div class='card-body text-center'>
            <img src='${eq.resim}' alt='${eq.isim}' class='img-fluid rounded mb-2' style='max-height:90px;'>
            <div class='fw-bold mb-2'>${eq.isim} <span class='badge bg-primary ms-2'>Adet: ${eq.adet}</span></div>
            <div class='mb-2'>
              <label class='form-label mb-1'>Açıklama</label>
              <input type='text' class='form-control' name='desc_${idx}' placeholder='Açıklama'>
            </div>
            <div class='mb-2'>
              <label class='form-label mb-1'>Arıza Var mı?</label>
              <select class='form-select' name='fault_${idx}'>
                <option value='Sorunsuz'>Sorunsuz</option>
                <option value='Hasarlı'>Hasarlı</option>
                <option value='Eksik'>Eksik</option>
              </select>
            </div>
          </div>
        </div>
      </div>`;
    });
    html += '</div>';
  }
  html += `<div class='mb-3'><label class='form-label'>Genel Not</label><textarea class='form-control' name='genelNot' rows='2'></textarea></div>`;
  document.getElementById('finishModalBody').innerHTML = html;
  var modal = new bootstrap.Modal(document.getElementById('finishModal'));
  modal.show();
}
document.getElementById('finishForm').onsubmit = function(e) {
  e.preventDefault();
  const item = comingGoingData.find(x => x.id === finishItemId);
  if (!item) return;
  const ekipmanlar = item.ekipmanlar.map((eq, idx) => {
    const desc = document.querySelector(`[name='desc_${idx}']`).value;
    const fault = document.querySelector(`[name='fault_${idx}']`).value;
    return {
      ...eq,
      desc,
      fault,
    };
  });
  const genelNot = document.querySelector(`[name='genelNot']`).value;
  // Gelenler tablosuna ekle
  const now = new Date();
  const tarih = now.toISOString().slice(0,10);
  ekipmanlar.forEach(eq => {
    comingGoingData.push({
      id: item.id + '-RET-' + Math.random().toString(36).substr(2,5),
      date: tarih,
      type: 'Dönüş',
      location: item.location,
      person: item.person,
      status: eq.fault,
      statusClass: eq.fault === 'Sorunsuz' ? 'success' : (eq.fault === 'Eksik' ? 'warning' : 'danger'),
      ekipmanlar: [
        { isim: eq.isim, adet: eq.adet, resim: eq.resim, desc: eq.desc }
      ],
      description: (eq.desc ? eq.desc + ' ' : '') + (genelNot ? 'Not: ' + genelNot : '')
    });
  });
  // Gidenler listesinden çıkar
  const idx = comingGoingData.findIndex(x => x.id === finishItemId);
  if (idx > -1) comingGoingData.splice(idx, 1);
  renderTables();
  bootstrap.Modal.getInstance(document.getElementById('finishModal')).hide();
  alert('Dönüş işlemi başarıyla kaydedildi ve Gelenler tablosuna eklendi!');
};
// Pagination tıklama eventleri
document.addEventListener('DOMContentLoaded', function() {
  renderTables();
  // Pagination tıklama
  document.getElementById('gidenPagination').onclick = function(e) {
    const a = e.target.closest('a');
    if (a) {
      gidenPage = parseInt(a.dataset.page);
      renderTables();
    }
  };
  document.getElementById('gelenPagination').onclick = function(e) {
    const a = e.target.closest('a');
    if (a) {
      gelenPage = parseInt(a.dataset.page);
      renderTables();
    }
  };
  // Filtre eventleri
  document.getElementById('gidenSearch').oninput = renderTables;
  document.getElementById('gidenLokasyon').onchange = renderTables;
  document.getElementById('gidenYetkili').onchange = renderTables;
  document.getElementById('gelenSearch').oninput = renderTables;
  document.getElementById('gelenLokasyon').onchange = renderTables;
  document.getElementById('gelenYetkili').onchange = renderTables;
  document.getElementById('gelenDurum').onchange = renderTables;
});
function clearGidenFilters() {
  document.getElementById('gidenSearch').value = '';
  document.getElementById('gidenLokasyon').value = '';
  document.getElementById('gidenYetkili').value = '';
  renderTables();
}
function clearGelenFilters() {
  document.getElementById('gelenSearch').value = '';
  document.getElementById('gelenLokasyon').value = '';
  document.getElementById('gelenYetkili').value = '';
  document.getElementById('gelenDurum').value = '';
  renderTables();
}
</script>
@endpush
@endsection