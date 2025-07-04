@extends('layouts.admin')
@section('content')
<style>
:root {
  --main-gradient: linear-gradient(90deg, #0d6efd 0%, #36b3f6 100%);
  --glass-bg: rgba(255,255,255,0.75);
  --glass-bg-dark: rgba(35,39,47,0.85);
  --card-bg: #fff;
  --card-bg-dark: #23272f;
  --text-main: #222;
  --text-main-dark: #f8f9fa;
  --badge-success: #00c896;
  --badge-warning: #ffb300;
  --badge-danger: #ff4d4f;
  --badge-info: #36b3f6;
  --shadow: 0 8px 32px #0d6efd22;
  --shadow-hover: 0 16px 48px #0d6efd33;
}
body.dark-mode {
  background: #181c22 !important;
}
.animated-title {
  font-size: 2.3rem;
  font-weight: 900;
  background: var(--main-gradient);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  animation: slideIn 1.2s cubic-bezier(.77,0,.18,1) 0.1s both;
  letter-spacing: 0.01em;
  margin-bottom: 1.2em;
  text-shadow: 0 4px 32px #0d6efd22;
}
@keyframes slideIn {
  from { opacity: 0; transform: translateY(-30px) scale(0.95); }
  to { opacity: 1; transform: translateY(0) scale(1); }
}
.stats-bar {
  display: flex; gap: 2em; margin-bottom: 2em; flex-wrap: wrap; justify-content: flex-start;
}
.stats-card {
  background: var(--glass-bg);
  border-radius: 1.5em;
  box-shadow: var(--shadow);
  padding: 1.5em 2.2em;
  display: flex; align-items: center; gap: 1.2em;
  min-width: 220px;
  transition: box-shadow 0.25s, transform 0.22s, background 0.18s;
  animation: fadeInUp 0.7s cubic-bezier(.77,0,.18,1);
  cursor: pointer;
  position: relative;
  overflow: hidden;
  border: 1.5px solid #e3e8f0;
  backdrop-filter: blur(8px);
}
body.dark-mode .stats-card { background: var(--glass-bg-dark); color: var(--text-main-dark); border-color: #23272f; }
.stats-card:hover { box-shadow: var(--shadow-hover); transform: translateY(-4px) scale(1.04); background: var(--main-gradient); color: #fff; }
.stats-card .icon {
  font-size: 2.2em;
  background: var(--main-gradient);
  color: #fff;
  border-radius: 1em;
  padding: 0.4em 0.7em;
  box-shadow: 0 2px 8px #0d6efd22;
  animation: popIn 0.7s cubic-bezier(.77,0,.18,1);
}
@keyframes popIn {
  from { opacity: 0; transform: scale(0.7); }
  to { opacity: 1; transform: scale(1); }
}
.stats-card .value { font-size: 2em; font-weight: 900; letter-spacing: 0.01em; }
.stats-card .label { font-size: 1.1em; color: #888; font-weight: 600; }
.stats-card .pulse {
  position: absolute; right: 1.2em; top: 1.2em; width: 18px; height: 18px;
  border-radius: 50%; background: #ff4d4f; box-shadow: 0 0 0 0 #ff4d4f44;
  animation: pulseAnim 1.2s infinite;
}
@keyframes pulseAnim {
  0% { box-shadow: 0 0 0 0 #ff4d4f44; }
  70% { box-shadow: 0 0 0 12px #ff4d4f00; }
  100% { box-shadow: 0 0 0 0 #ff4d4f00; }
}
.filter-bar {
  background: var(--glass-bg);
  border-radius: 1.2rem;
  box-shadow: 0 2px 12px #0d6efd11;
  padding: 1.2rem 1.5rem;
  margin-bottom: 2rem;
  display: flex;
  flex-wrap: wrap;
  gap: 0.7rem;
  align-items: center;
  border: 1.5px solid #e3e8f0;
  backdrop-filter: blur(8px);
}
body.dark-mode .filter-bar { background: var(--glass-bg-dark); border-color: #23272f; }
.filter-bar input, .filter-bar select {
  min-width: 140px;
  border-radius: 0.7rem;
  font-size: 1.08em;
  background: #f8fafc;
  border: 1.5px solid #d1d5db;
  box-shadow: 0 1px 4px #0d6efd08;
}
.filter-bar input[type="date"] {
  padding: 0.3em 0.7em;
}
.filter-bar input:focus, .filter-bar select:focus {
  border-color: #0d6efd;
  box-shadow: 0 0 0 2px #0d6efd22;
  outline: none;
}
.equip-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
  gap: 2em;
  margin-bottom: 2em;
}
.equip-card {
  background: var(--glass-bg);
  border-radius: 1.5em;
  box-shadow: var(--shadow);
  padding: 1.5em 1.7em 1.2em 1.7em;
  display: flex; flex-direction: column; gap: 0.9em;
  position: relative;
  overflow: hidden;
  animation: fadeInUp 0.7s cubic-bezier(.77,0,.18,1);
  transition: box-shadow 0.2s, transform 0.2s, background 0.18s;
  border: 1.5px solid #e3e8f0;
  backdrop-filter: blur(8px);
  cursor: pointer;
}
body.dark-mode .equip-card { background: var(--glass-bg-dark); color: var(--text-main-dark); border-color: #23272f; }
.equip-card:hover { box-shadow: var(--shadow-hover); transform: translateY(-4px) scale(1.04); background: #f3f4f8; color: inherit; }
body.dark-mode .equip-card:hover { background: #23272f; color: #fff; }
.equip-card .equip-header {
  display: flex; align-items: center; gap: 0.7em; margin-bottom: 0.2em;
}
.equip-card .equip-type {
  font-size: 1.2em; font-weight: 800; letter-spacing: 0.01em;
  background: var(--main-gradient);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
.equip-card .equip-id {
  font-size: 1em; color: #888; font-weight: 600; margin-left: auto;
}
.equip-card .equip-status {
  font-size: 1.1em; font-weight: 700; border-radius: 0.7em; padding: 0.3em 1.1em;
  color: #fff; display: flex; align-items: center; gap:3px; margin-left: 0.5em;
  box-shadow: 0 1px 4px #0d6efd11;
  animation: badgePop 0.7s cubic-bezier(.77,0,.18,1);
  border: none;
  background: var(--badge-success);
}
.equip-card .equip-status.success { background: var(--badge-success); }
.equip-card .equip-status.warning { background: var(--badge-warning); }
.equip-card .equip-status.danger { background: var(--badge-danger); }
.equip-card .equip-status.info { background: var(--badge-info); }
@keyframes badgePop {
  from { opacity: 0; transform: scale(0.7); }
  to { opacity: 1; transform: scale(1); }
}
.equip-card .equip-meta { font-size: 1.08em; color: #666; font-weight: 500; }
.equip-card .equip-footer {
  font-size: 1em; color: #888; margin-top: 0.7em;
  display: flex; align-items: center; gap: 0.7em;
}
.equip-card .avatar {
  width: 36px; height: 36px; border-radius: 50%; object-fit: cover;
  border: 2px solid #fff; box-shadow: 0 1px 4px #0d6efd22; margin-right: 0.5em;
}
.equip-card .equip-anim {
  position: absolute; right: 1.2em; top: 1.2em; font-size: 2.5em; opacity: 0.09; pointer-events: none;
  animation: floatAnim 2.5s infinite alternate cubic-bezier(.77,0,.18,1);
}
@keyframes floatAnim {
  from { transform: translateY(0) rotate(-8deg); }
  to { transform: translateY(-12px) rotate(8deg); }
}
.equip-card .equip-actions {
  display: flex; gap: 0.7em; margin-top: 0.7em; flex-wrap: wrap;
}
.equip-card .equip-actions .btn {
  border-radius: 0.7em; font-size: 1.08em; font-weight: 600;
  box-shadow: 0 1px 4px #0d6efd11;
  transition: background 0.18s, box-shadow 0.18s, transform 0.12s;
  animation: fadeInUp 0.7s cubic-bezier(.77,0,.18,1);
}
.equip-card .equip-actions .btn:hover { transform: scale(1.12); }

@media (max-width: 900px) {
  .stats-bar { gap: 1em; }
  .equip-grid { gap: 1em; }
}
@media (max-width: 768px) {
  .animated-title { font-size: 1.3rem; }
  .stats-card { min-width: 140px; padding: 1em 1em; }
  .equip-card { padding: 1em 0.7em; }
}

/* Modal ve sekmeler */
#detailModal .modal-header { background: var(--main-gradient); color: #fff; }
#detailModal .nav-tabs .nav-link.active { background: var(--main-gradient); color: #fff; border: none; }
#detailModal .nav-tabs .nav-link { color: #0d6efd; font-weight: 600; border-radius: 0.7em 0.7em 0 0; }
#detailModal .tab-pane { padding: 1.2em 0.2em; }
#detailModal .avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; margin-right: 0.5em; }
.emoji-btn { background: none; border: none; font-size: 1.2em; cursor: pointer; margin-right: 0.2em; }
.emoji-btn:hover { transform: scale(1.2); }
body.dark-mode { background: #181c22 !important; color: #f8f9fa; }
body.dark-mode .equip-card, body.dark-mode .stats-card { background: var(--glass-bg-dark); color: #f8f9fa; border-color: #23272f; }
body.dark-mode .equip-card .equip-meta, body.dark-mode .stats-card .label, body.dark-mode .equip-card .equip-footer { color: #b0b8c1; }
body.dark-mode .equip-card .equip-status { color: #fff; }
body.dark-mode .modal-content { background: #23272f; color: #f8f9fa; }
body.dark-mode .nav-tabs .nav-link { color: #36b3f6; }
body.dark-mode .nav-tabs .nav-link.active { color: #fff; }

.filter-bar input:hover, .filter-bar select:hover, .btn:hover, .stats-card:hover {
  background: #f3f4f8 !important;
  color: inherit !important;
}
body.dark-mode .filter-bar input:hover, body.dark-mode .filter-bar select:hover, body.dark-mode .btn:hover, body.dark-mode .stats-card:hover {
  background: #23272f !important;
  color: #fff !important;
}
.date-filter-input {
  min-width: 180px;
  width: 200px;
}
</style>
<div class="animated-title"><i class="fas fa-truck"></i> Giden-Gelen Ekipman İşlemleri</div>
<div class="stats-bar">
  <div class="stats-card" id="statTotalCard"><span class="icon"><i class="fas fa-list"></i></span><span class="value" id="statTotal">0</span><span class="label">Toplam İşlem</span></div>
  <div class="stats-card" id="statProblemCard"><span class="icon"><i class="fas fa-exclamation-triangle"></i></span><span class="value" id="statProblem">0</span><span class="label">Eksik/Hasarlı</span><span class="pulse" id="problemPulse" style="display:none"></span></div>
  <div class="stats-card" id="statLastCard"><span class="icon"><i class="fas fa-clock"></i></span><span class="value" id="statLast">-</span><span class="label">Son İşlem</span></div>
</div>
<div class="filter-bar mb-3 align-items-end">
  <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Ekipman, kişi veya işlem ID ara...">
  <div class="d-flex align-items-end" style="gap: 0.5em;">
    <div>
      <label for="startDate" class="form-label mb-0" style="font-size:0.95em;">Başlangıç Tarihi</label>
      <input type="date" class="form-control form-control-sm date-filter-input" id="startDate">
    </div>
    <span class="mx-1" style="font-weight:600;">-</span>
    <div>
      <label for="endDate" class="form-label mb-0" style="font-size:0.95em;">Bitiş Tarihi</label>
      <input type="date" class="form-control form-control-sm date-filter-input" id="endDate">
    </div>
  </div>
  <button class="btn btn-primary btn-sm" id="filterBtn"><i class="fas fa-filter"></i> Filtrele</button>
  <button class="btn btn-outline-secondary btn-sm ms-auto" id="clearFilterBtn"><i class="fas fa-times"></i> Temizle</button>
</div>
<div class="equip-grid" id="equipGrid">
  <!-- JS ile kartlar doldurulacak -->
</div>
<div class="d-flex justify-content-center mt-3">
  <nav aria-label="Sayfalama">
    <ul class="pagination" id="equipPagination">
      <!-- JS ile doldurulacak -->
    </ul>
  </nav>
</div>
<!-- Detay Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel"><i class="fas fa-info-circle me-2"></i>İşlem Detayı</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs" id="detailTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-genel" data-bs-toggle="tab" data-bs-target="#tabGenel" type="button" role="tab">Genel Bilgi</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-hareket" data-bs-toggle="tab" data-bs-target="#tabHareket" type="button" role="tab">Hareket Geçmişi</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-not" data-bs-toggle="tab" data-bs-target="#tabNot" type="button" role="tab">Notlar</button>
          </li>
        </ul>
        <div class="tab-content mt-3">
          <div class="tab-pane fade show active" id="tabGenel" role="tabpanel">
            <div id="modalGenel"></div>
          </div>
          <div class="tab-pane fade" id="tabHareket" role="tabpanel">
            <div id="modalHareket"></div>
          </div>
          <div class="tab-pane fade" id="tabNot" role="tabpanel">
            <div id="modalNot"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>
<!-- Hasarlı/Eksik Modal -->
<div class="modal fade" id="problemModal" tabindex="-1" aria-labelledby="problemModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="problemModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Eksik/Hasarlı İşlemler</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body" id="problemModalBody">
        <!-- JS ile doldurulacak -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script>
// Demo veri
const comingGoingData = [
  {
    id: '2025-ANK-021',
    date: '2025-07-20',
    type: 'Gidiş',
    equipment: 'UPS 3kVA',
    count: 2,
    location: 'Ankara - Sincan',
    person: 'ayseYilmaz',
    avatar: '/images/avatar1.jpg',
    status: 'Sorunsuz',
    note: 'UPS cihazı yeni batarya ile döndü.',
    deliveryDate: '2025-07-22',
    deliveredTo: 'teknisyen3',
    deliveredToAvatar: '/images/avatar2.jpg',
    photo: '/images/ups-return.jpg',
    statusClass: 'success',
    description: 'UPS cihazı Ankara Sincan bölgesine gönderildi. Batarya değişimi sonrası sorunsuz döndü.',
    hareket: [
      {tarih:'2025-07-20', aciklama:'UPS gönderildi', kisi:'ayseYilmaz', avatar:'/images/avatar1.jpg'},
      {tarih:'2025-07-21', aciklama:'Batarya değişimi yapıldı', kisi:'teknisyen3', avatar:'/images/avatar2.jpg'},
      {tarih:'2025-07-22', aciklama:'Teslim alındı', kisi:'teknisyen3', avatar:'/images/avatar2.jpg'}
    ],
    notlar: [
      {kisi:'ayseYilmaz', avatar:'/images/avatar1.jpg', tarih:'2025-07-22 14:10', metin:'UPS teslim edildi, batarya değişimi sonrası test edildi.'},
      {kisi:'teknisyen3', avatar:'/images/avatar2.jpg', tarih:'2025-07-22 15:00', metin:'Teslim alındı, cihaz sorunsuz çalışıyor.'}
    ]
  },
  {
    id: '2025-ANK-022',
    date: '2025-07-20',
    type: 'Gidiş',
    equipment: 'Kask',
    count: 10,
    location: 'Ankara - Sincan',
    person: 'ayseYilmaz',
    avatar: '/images/avatar1.jpg',
    status: 'Eksik',
    note: '2 adet kayıp bildirildi.',
    deliveryDate: '2025-07-22',
    deliveredTo: 'teknisyen3',
    deliveredToAvatar: '/images/avatar2.jpg',
    photo: '/images/helmet-return.jpg',
    statusClass: 'warning',
    description: 'Kasklar Ankara Sincan bölgesine gönderildi. 2 adet kayıp olarak bildirildi.',
    hareket: [
      {tarih:'2025-07-20', aciklama:'Kasklar gönderildi', kisi:'ayseYilmaz', avatar:'/images/avatar1.jpg'},
      {tarih:'2025-07-22', aciklama:'Teslim alındı, 2 adet eksik', kisi:'teknisyen3', avatar:'/images/avatar2.jpg'}
    ],
    notlar: [
      {kisi:'ayseYilmaz', avatar:'/images/avatar1.jpg', tarih:'2025-07-22 14:10', metin:'Kasklar teslim edildi, 2 adet kayıp bildirildi.'}
    ]
  },
  {
    id: '2025-IST-011',
    date: '2025-07-18',
    type: 'Dönüş',
    equipment: 'El Feneri',
    count: 5,
    location: 'İstanbul - Kadıköy',
    person: 'mehmetKara',
    avatar: '/images/avatar2.jpg',
    status: 'Sorunsuz',
    note: 'Tüm fenerler çalışır durumda döndü.',
    deliveryDate: '2025-07-19',
    deliveredTo: 'fatmaDemir',
    deliveredToAvatar: '/images/avatar3.jpg',
    photo: '/images/flashlight.jpg',
    statusClass: 'success',
    description: 'El fenerleri İstanbul Kadıköy bölgesinden sorunsuz şekilde döndü.',
    hareket: [
      {tarih:'2025-07-18', aciklama:'El fenerleri gönderildi', kisi:'mehmetKara', avatar:'/images/avatar2.jpg'},
      {tarih:'2025-07-19', aciklama:'Teslim alındı', kisi:'fatmaDemir', avatar:'/images/avatar3.jpg'}
    ],
    notlar: [
      {kisi:'mehmetKara', avatar:'/images/avatar2.jpg', tarih:'2025-07-19 10:00', metin:'El fenerleri sorunsuz döndü.'}
    ]
  },
  {
    id: '2025-IZM-005',
    date: '2025-07-15',
    type: 'Gidiş',
    equipment: 'Çadır',
    count: 3,
    location: 'İzmir - Bornova',
    person: 'fatmaDemir',
    avatar: '/images/avatar3.jpg',
    status: 'Hasarlı',
    note: '1 çadırda yırtık var.',
    deliveryDate: '2025-07-18',
    deliveredTo: 'mehmetKara',
    deliveredToAvatar: '/images/avatar2.jpg',
    photo: '/images/tent.jpg',
    statusClass: 'danger',
    description: 'Çadırlar İzmir Bornova bölgesine gönderildi. 1 çadır hasarlı olarak döndü.',
    hareket: [
      {tarih:'2025-07-15', aciklama:'Çadırlar gönderildi', kisi:'fatmaDemir', avatar:'/images/avatar3.jpg'},
      {tarih:'2025-07-18', aciklama:'Teslim alındı, 1 çadır hasarlı', kisi:'mehmetKara', avatar:'/images/avatar2.jpg'}
    ],
    notlar: [
      {kisi:'fatmaDemir', avatar:'/images/avatar3.jpg', tarih:'2025-07-18 17:00', metin:'1 çadırda yırtık var.'}
    ]
  }
];

function updateStats(data) {
  document.getElementById('statTotal').textContent = data.length;
  const problemCount = data.filter(x=>x.status!=='Sorunsuz').length;
  document.getElementById('statProblem').textContent = problemCount;
  document.getElementById('problemPulse').style.display = problemCount ? 'block' : 'none';
  document.getElementById('statLast').textContent = data.length ? data[0].date : '-';
}

let currentPage = 1;
const perPage = 6;
function renderGrid(data) {
  let html = '';
  const start = (currentPage-1)*perPage;
  const end = start+perPage;
  const pageData = data.slice(start, end);
  pageData.forEach((row, idx) => {
    html += `<div class=\"equip-card animate__animated animate__fadeInUp\">\n      <div class=\"equip-header\">\n        <span class=\"equip-type\"><i class=\"fas fa-box-open me-1\"></i> ${row.equipment}</span>\n        <span class=\"equip-id\">${row.id}</span>\n        <span class=\"equip-status ${row.statusClass}\"><i class=\"fas ${row.statusClass==='success'?'fa-check-circle':row.statusClass==='warning'?'fa-exclamation-triangle':row.statusClass==='danger'?'fa-times-circle':'fa-info-circle'} me-1\"></i>${row.status}</span>\n      </div>\n      <div class=\"equip-meta\">${row.type} • ${row.count} adet • ${row.location}</div>\n      <div class=\"equip-footer\">\n        <img src=\"${row.avatar}\" class=\"avatar\" alt=\"${row.person}\"> <span>${row.person}</span>\n        <span class=\"ms-auto\"><i class=\"fas fa-calendar-alt\"></i> ${row.date}</span>\n      </div>\n      <div class=\"equip-actions\">\n        <button type=\"button\" class=\"btn btn-detail btn-sm\" data-idx=\"${start+idx}\"><i class=\"fas fa-eye\"></i> Detay</button>\n        <button type=\"button\" class=\"btn btn-outline-secondary btn-sm\" onclick=\"navigator.clipboard.writeText('${row.equipment}, ${row.count} adet, ${row.location}');\"><i class=\"fas fa-copy\"></i></button>\n        <button type=\"button\" class=\"btn btn-outline-success btn-sm\"><i class=\"fas fa-file-pdf\"></i></button>\n        <button type=\"button\" class=\"btn btn-outline-dark btn-sm\"><i class=\"fas fa-share-alt\"></i></button>\n      </div>\n      <span class=\"equip-anim\"><i class=\"fas fa-cogs\"></i></span>\n    </div>`;
  });
  document.getElementById('equipGrid').innerHTML = html;
  updateStats(data);
  renderPagination(data.length);
}
function renderPagination(total) {
  const pageCount = Math.ceil(total/perPage);
  let pag = '';
  for(let i=1;i<=pageCount;i++) {
    pag += `<li class=\"page-item${i===currentPage?' active':''}\"><a class=\"page-link\" href=\"#\" onclick=\"gotoPage(${i});return false;\">${i}</a></li>`;
  }
  document.getElementById('equipPagination').innerHTML = pag;
}
window.gotoPage = function(page) { currentPage=page; filterData(); }

function filterData() {
  const search = document.getElementById('searchInput').value.toLowerCase();
  const start = document.getElementById('startDate').value;
  const end = document.getElementById('endDate').value;
  let filtered = comingGoingData.filter(row => {
    let match = true;
    if(search) {
      match = row.equipment.toLowerCase().includes(search) || row.person.toLowerCase().includes(search) || row.location.toLowerCase().includes(search) || row.id.toLowerCase().includes(search);
    }
    if(match && start) {
      match = row.date >= start;
    }
    if(match && end) {
      match = row.date <= end;
    }
    return match;
  });
  renderGrid(filtered);
}
document.getElementById('filterBtn').onclick = function(e){ e.preventDefault(); filterData(); };
document.getElementById('clearFilterBtn').onclick = function(e){
  e.preventDefault();
  document.getElementById('searchInput').value = '';
  document.getElementById('startDate').value = '';
  document.getElementById('endDate').value = '';
  renderGrid(comingGoingData);
};
renderGrid(comingGoingData);

// Detay modalı açma
const equipGrid = document.getElementById('equipGrid');
equipGrid.onclick = function(e) {
  const card = e.target.closest('.equip-card');
  if (!card) return;
  // Kartın indexini bul
  const idx = Array.from(equipGrid.children).indexOf(card);
  if (idx === -1) return;
  const row = comingGoingData[(currentPage-1)*perPage + idx];
  // Genel Bilgi
  let genel = `<div class='row'>
    <div class='col-md-6 mb-3'>
      <div class='mb-2'><span class='badge bg-light text-dark'>${row.id}</span></div>
      <div class='mb-2'><strong>İşlem Tipi:</strong> <span class='equip-status ${row.statusClass}'>${row.type}</span></div>
      <div class='mb-2'><strong>Ekipman:</strong> ${row.equipment}</div>
      <div class='mb-2'><strong>Adet:</strong> ${row.count}</div>
      <div class='mb-2'><strong>Giden Yer:</strong> ${row.location}</div>
      <div class='mb-2'><strong>Götüren:</strong> <img src='${row.avatar}' class='avatar' alt='${row.person}'> ${row.person}</div>
      <div class='mb-2'><strong>Durum:</strong> <span class='equip-status ${row.statusClass}'>${row.status}</span></div>
      <div class='mb-2'><strong>Teslim Tarihi:</strong> ${row.deliveryDate}</div>
      <div class='mb-2'><strong>Teslim Alan:</strong> <img src='${row.deliveredToAvatar}' class='avatar' alt='${row.deliveredTo}'> ${row.deliveredTo}</div>
    </div>
    <div class='col-md-6 mb-3'>
      <div class='mb-2'><strong>Açıklama:</strong> ${row.description}</div>
      <div class='mb-2'><strong>Not:</strong> ${row.note}</div>
      <div class='mb-2'><strong>İlgili Fotoğraf:</strong><br><img src='${row.photo}' class='img-fluid rounded border shadow-sm mt-1' style='max-width:220px;'></div>
    </div>
  </div>`;
  // Hareket Geçmişi
  let hareket = `<ul class='list-group'>`;
  row.hareket.forEach(ev => {
    hareket += `<li class='list-group-item d-flex align-items-center'>
      <img src='${ev.avatar}' class='avatar' alt='${ev.kisi}'>
      <span class='fw-bold me-2'>${ev.kisi}</span>
      <span class='text-muted small me-2'>${ev.tarih}</span>
      <span>${ev.aciklama}</span>
    </li>`;
  });
  hareket += `</ul>`;
  // Notlar
  let notlar = `<div id='notList'>`;
  row.notlar.forEach(n => {
    notlar += `<div class='border rounded p-2 bg-light mb-2'>
      <div class='d-flex align-items-center mb-1'>
        <img src='${n.avatar}' class='avatar' alt='${n.kisi}'>
        <span class='fw-bold me-2'>${n.kisi}</span>
        <span class='text-muted small'>${n.tarih}</span>
      </div>
      <div class='ps-4'>${n.metin}</div>
    </div>`;
  });
  notlar += `</div>
  <div class='mt-2 d-flex align-items-center'>
    <input type='text' class='form-control form-control-sm me-2' id='newNoteInput' placeholder='Yorum ekle...'>
    <button class='emoji-btn' onclick="insertEmoji('😊')">😊</button>
    <button class='emoji-btn' onclick="insertEmoji('👍')">👍</button>
    <button class='emoji-btn' onclick="insertEmoji('🔥')">🔥</button>
    <button class='btn btn-primary btn-sm' id='addNoteBtn'><i class='fas fa-paper-plane'></i></button>
  </div>`;
  document.getElementById('modalGenel').innerHTML = genel;
  document.getElementById('modalHareket').innerHTML = hareket;
  document.getElementById('modalNot').innerHTML = notlar;
  new bootstrap.Modal(document.getElementById('detailModal')).show();
  // Not ekleme demo
  document.getElementById('addNoteBtn').onclick = function() {
    let val = document.getElementById('newNoteInput').value.trim();
    if(val) {
      let html = `<div class='border rounded p-2 bg-light mb-2'>
        <div class='d-flex align-items-center mb-1'>
          <img src='${row.avatar}' class='avatar' alt='${row.person}'>
          <span class='fw-bold me-2'>${row.person}</span>
          <span class='text-muted small'>${new Date().toLocaleString('tr-TR')}</span>
        </div>
        <div class='ps-4'>${val}</div>
      </div>`;
      document.getElementById('notList').insertAdjacentHTML('beforeend', html);
      document.getElementById('newNoteInput').value = '';
    }
  };
};
window.insertEmoji = function(emoji) {
  let inp = document.getElementById('newNoteInput');
  inp.value += emoji;
  inp.focus();
};
// Eksik/Hasarlı modalı
const statProblemCard = document.getElementById('statProblemCard');
statProblemCard.onclick = function() {
  const problems = comingGoingData.filter(x=>x.status!=='Sorunsuz');
  let html = '';
  if(!problems.length) html = '<div class="alert alert-success">Eksik veya hasarlı işlem yok.</div>';
  problems.forEach(row => {
    html += `<div class="equip-card mb-3">
      <div class="equip-header">
        <span class="equip-type"><i class="fas fa-box-open me-1"></i> ${row.equipment}</span>
        <span class="equip-id">${row.id}</span>
        <span class="equip-status ${row.statusClass}"><i class="fas ${row.statusClass==='success'?'fa-check-circle':row.statusClass==='warning'?'fa-exclamation-triangle':'fa-times-circle'} me-1"></i>${row.status}</span>
      </div>
      <div class="equip-meta">${row.type} • ${row.count} adet • ${row.location}</div>
      <div class="equip-footer">
        <img src="${row.avatar}" class="avatar" alt="${row.person}"> <span>${row.person}</span>
        <span class="ms-auto"><i class="fas fa-calendar-alt"></i> ${row.date}</span>
      </div>
      <div class="equip-actions">
        <button type="button" class="btn btn-detail btn-sm" onclick="showDetailModal('${row.id}')"><i class="fas fa-eye"></i> Detay</button>
      </div>
    </div>`;
  });
  document.getElementById('problemModalBody').innerHTML = html;
  new bootstrap.Modal(document.getElementById('problemModal')).show();
};
// Detay modalını dışarıdan açmak için
window.showDetailModal = function(id) {
  const idx = comingGoingData.findIndex(x=>x.id===id);
  if(idx>-1) {
    equipGrid.querySelectorAll('.btn-detail')[idx].click();
  }
};
// Bugünün tarihini yyyy-mm-dd formatında al
const today = new Date();
const yyyy = today.getFullYear();
const mm = String(today.getMonth() + 1).padStart(2, '0');
const dd = String(today.getDate()).padStart(2, '0');
const maxDate = `${yyyy}-${mm}-${dd}`;
document.getElementById('startDate').setAttribute('max', maxDate);
document.getElementById('endDate').setAttribute('max', maxDate);
</script>
@endpush
@endsection