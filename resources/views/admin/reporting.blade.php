@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
  body { background: #f6f7fb; }
  .kpi-card {
    min-width: 170px;
    height: 180px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    border-radius:1.5em; box-shadow:0 4px 24px 0 #e0e7ef; background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%); transition: transform .15s, box-shadow .15s;
    border: none; position: relative; overflow: hidden;
  }
  .kpi-card:hover { transform: translateY(-4px) scale(1.03); box-shadow:0 8px 32px 0 #b6c2e1; }
  .kpi-icon { font-size:2.6rem; margin-bottom: .5rem; filter: drop-shadow(0 2px 8px #b6c2e1); }
  .kpi-value { font-size:2rem; font-weight:700; letter-spacing:1px; }
  .kpi-label { font-size:1.08rem; color:#6b7280; letter-spacing:.5px; }
  .kpi-card .badge { font-size:.9rem; border-radius:1em; }
  .kpi-card .position-absolute { right:1.2em; top:1.2em; }
  .modern-card { border-radius:1.3em; box-shadow:0 2px 16px #e0e7ef; border:none; background:#fff; }
  .modern-card .card-header { border-radius:1.3em 1.3em 0 0; background:linear-gradient(90deg,#e0e7ff 0,#f8fafc 100%); font-weight:600; font-size:1.1rem; }
  .modern-table th, .modern-table td { vertical-align:middle; }
  .modern-table th { background:#f3f6fa; font-weight:600; color:#4b5563; border-top:none; }
  .modern-table tr { transition: background .12s; }
  .modern-table tr:hover { background:#f1f5fb; }
  .pagination .page-link { border-radius:1em; margin:0 2px; color:#6366f1; border:none; background:#f3f6fa; transition: all 0.2s ease; }
  .pagination .page-item.active .page-link { background:#6366f1; color:#fff; box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3); font-weight: 600; }
  .pagination .page-item.active .page-link:hover { background:#5a5fd8; color:#fff; }
  .pagination .page-link:hover { background:#e0e7ff; color:#6366f1; transform: translateY(-1px); }
  .filter-bar { background:linear-gradient(90deg,#f3f6fa 0,#e0e7ff 100%); border-radius:1.2em; box-shadow:0 2px 12px #e0e7ef; padding:.7em 1em; margin-bottom:1.5em; display:flex; flex-wrap:wrap; gap:.7em; align-items:center; }
  .filter-bar .input-group { flex:1 1 180px; min-width:160px; }
  .filter-bar .form-select, .filter-bar .form-control { border-radius:1em; border:none; background:#f7f8fa; font-size:.98rem; }
  .filter-bar .input-group-text { background:transparent; border:none; color:#6366f1; font-size:1.1em; }
  .filter-bar .btn { border-radius:1em; font-weight:500; min-width:90px; }
  .filter-chip { display:inline-block; background:#e0e7ff; color:#6366f1; border-radius:1em; padding:.2em .8em; margin-right:.3em; font-size:.95em; font-weight:500; }
  .chart-card { border-radius:1.3em; box-shadow:0 2px 16px #e0e7ef; border:none; background:#fff; }
  .chart-card h6 { font-weight:600; font-size:1.08rem; display:flex; align-items:center; gap:.5em; }
  .chart-card h6 i { color:#6366f1; }
  #map { border-radius:1.3em; box-shadow:0 2px 16px #e0e7ef; }
  @media (max-width: 768px) {
    .kpi-card, .modern-card, .chart-card { min-width:unset; }
    .filter-bar { flex-direction:column; gap:.5em; padding:.7em .5em; }
    .filter-bar .btn { min-width:unset; width:100%; }
  }
  .type-filter-group { display:flex; gap:.5em; }
  .type-filter-btn {
    border:none; outline:none; background:#f3f6fa; color:#6366f1; border-radius:1.2em; padding:.45em 1.1em; font-weight:600; font-size:1.01em; display:flex; align-items:center; gap:.5em; transition:.13s; cursor:pointer; box-shadow:0 1px 4px #e0e7ef;
  }
  .type-filter-btn.selected { background:#6366f1; color:#fff; box-shadow:0 2px 8px #b6c2e1; }
  .type-filter-btn .type-ico { font-size:1.1em; }
  .type-filter-btn[data-type='Bakım'] { --type-color:#ffc107; }
  .type-filter-btn[data-type='Arıza'] { --type-color:#dc3545; }
  .type-filter-btn[data-type='Transfer'] { --type-color:#6c757d; }
  .type-filter-btn[data-type='Satın Alma'] { --type-color:#17a2b8; }
  .type-filter-btn .type-ico { color:var(--type-color); }
  .type-filter-btn.selected .type-ico { color:#fff; }
  .type-filter-btn:hover:not(.selected) { background:#e0e7ff; }
  .type-filter-btn[title] { position:relative; }
  .type-filter-btn[title]:hover:after {
    content:attr(title); position:absolute; left:50%; top:110%; transform:translateX(-50%); background:#222; color:#fff; font-size:.92em; padding:.3em .7em; border-radius:.7em; white-space:nowrap; z-index:10;
  }
  .district-filter-group { display:flex; gap:.5em; }
  .district-filter-btn {
    border:none; outline:none; background:#f3f6fa; color:#6366f1; border-radius:1.2em; padding:.45em 1.1em; font-weight:600; font-size:1.01em; display:flex; align-items:center; gap:.5em; transition:.13s; cursor:pointer; box-shadow:0 1px 4px #e0e7ef;
  }
  .district-filter-btn.selected { background:#17a2b8; color:#fff; box-shadow:0 2px 8px #b6c2e1; }
  .district-filter-btn .district-ico { font-size:1.1em; color:#17a2b8; }
  .district-filter-btn.selected .district-ico { color:#fff; }
  .district-filter-btn:hover:not(.selected) { background:#e0e7ff; }
</style>
<div class="container-fluid">
  <h3 class="mb-4">Raporlama & Analiz Paneli</h3>
  <!-- Bildirim -->
  <div class="d-flex justify-content-end align-items-center mb-3">
    <div id="snackbar" style="display:none;position:fixed;top:1.5em;right:2em;z-index:9999;" class="alert alert-info shadow">Yeni veri geldi!</div>
  </div>
  <!-- Otomatik Rapor Planlama ve Paylaşım -->
  <div class="card modern-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span><i class="fas fa-calendar-alt me-2"></i>Otomatik Rapor Planlama</span>
      <button class="btn btn-outline-primary btn-sm"><i class="fas fa-share-alt"></i> Raporu Paylaş</button>
    </div>
    <div class="card-body d-flex flex-wrap gap-3 align-items-center">
      <select class="form-select w-auto" id="planPeriod">
        <option>Haftalık</option><option>2 Haftada Bir</option><option>Aylık</option>
      </select>
      <input type="email" class="form-control w-auto" placeholder="E-posta adresi">
      <button class="btn btn-success">Planla</button>
      <span class="text-muted small">Planlanan: Her Pazartesi 09:00</span>
    </div>
  </div>
  <!-- Favori Raporlarım -->
  <div class="card modern-card mb-4">
    <div class="card-header"><i class="fas fa-star me-2 text-warning"></i>Favori Raporlarım</div>
    <div class="card-body d-flex flex-wrap gap-2">
      <button class="btn btn-outline-primary btn-sm">Aylık Arıza Analizi</button>
      <button class="btn btn-outline-success btn-sm">Bölge Bazlı Bakım Raporu</button>
      <button class="btn btn-outline-info btn-sm">Kritik Ekipman Listesi</button>
      <button class="btn btn-outline-secondary btn-sm">Tüm Talepler Özeti</button>
      <button class="btn btn-outline-dark btn-sm"><i class="fas fa-plus"></i> Yeni Favori</button>
    </div>
  </div>
  <!-- KPI Kartları (animasyonlu uyarı, mini trend) -->
  <div class="row g-3 mb-4">
    <div class="col-md-2 col-6">
      <div class="card kpi-card text-center p-3 position-relative">
        <div class="kpi-icon text-primary"><i class="fas fa-boxes"></i></div>
        <div class="kpi-value" id="kpiTotal">120 <span class="text-success"><i class="fas fa-arrow-up"></i> %3</span></div>
        <div class="kpi-label">Toplam Ekipman</div>
        <canvas id="miniTrend1" height="18" style="width:100%;max-width:80px;"></canvas>
      </div>
    </div>
    <div class="col-md-2 col-6">
      <div class="card kpi-card text-center p-3 position-relative">
        <div class="kpi-icon text-success"><i class="fas fa-check-circle"></i></div>
        <div class="kpi-value" id="kpiActive">98 <span class="text-danger"><i class="fas fa-arrow-down"></i> %1</span></div>
        <div class="kpi-label">Aktif</div>
        <canvas id="miniTrend2" height="18" style="width:100%;max-width:80px;"></canvas>
      </div>
    </div>
    <div class="col-md-2 col-6">
      <div class="card kpi-card text-center p-3 position-relative animate__animated animate__flash animate__infinite" id="kpiFaultCard">
        <div class="kpi-icon text-danger"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="kpi-value" id="kpiFault">7 <span class="text-success"><i class="fas fa-arrow-down"></i> %2</span></div>
        <div class="kpi-label">Arızalı</div>
        <span class="position-absolute top-0 end-0 badge bg-danger">Kritik!</span>
        <canvas id="miniTrend3" height="18" style="width:100%;max-width:80px;"></canvas>
      </div>
    </div>
    <div class="col-md-2 col-6">
      <div class="card kpi-card text-center p-3">
        <div class="kpi-icon text-warning"><i class="fas fa-tools"></i></div>
        <div class="kpi-value" id="kpiMaintenance">5</div>
        <div class="kpi-label">Bakımda</div>
      </div>
    </div>
    <div class="col-md-2 col-6">
      <div class="card kpi-card text-center p-3">
        <div class="kpi-icon text-info"><i class="fas fa-file-alt"></i></div>
        <div class="kpi-value" id="kpiRequests">32</div>
        <div class="kpi-label">Toplam Talep</div>
      </div>
    </div>
    <div class="col-md-2 col-6">
      <div class="card kpi-card text-center p-3">
        <div class="kpi-icon text-success"><i class="fas fa-thumbs-up"></i></div>
        <div class="kpi-value" id="kpiApproved">24</div>
        <div class="kpi-label">Onaylanan</div>
      </div>
    </div>
  </div>
  <!-- Modern Filtreleme Barı (KPI kartlarının altında) -->
  <div class="filter-bar mt-2 mb-2">
    <div class="input-group">
      <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
      <input type="text" class="form-control" id="filterDate" placeholder="Tarih Aralığı">
    </div>
    <div class="type-filter-group" id="typeFilterGroup">
      <button class="type-filter-btn" data-type="Bakım" title="Bakım taleplerini göster"><span class="type-ico"><i class="fas fa-tools"></i></span>Bakım</button>
      <button class="type-filter-btn" data-type="Arıza" title="Arıza bildirimlerini göster"><span class="type-ico"><i class="fas fa-bolt"></i></span>Arıza</button>
      <button class="type-filter-btn" data-type="Transfer" title="Transfer taleplerini göster"><span class="type-ico"><i class="fas fa-exchange-alt"></i></span>Transfer</button>
      <button class="type-filter-btn" data-type="Satın Alma" title="Satın alma taleplerini göster"><span class="type-ico"><i class="fas fa-shopping-cart"></i></span>Satın Alma</button>
    </div>
    <div class="district-filter-group" id="districtFilterGroup">
      <button class="district-filter-btn" data-district="Selçuklu" title="Selçuklu ilçesini filtrele"><span class="district-ico"><i class="fas fa-map-marker-alt"></i></span>Selçuklu</button>
      <button class="district-filter-btn" data-district="Karatay" title="Karatay ilçesini filtrele"><span class="district-ico"><i class="fas fa-map-marker-alt"></i></span>Karatay</button>
      <button class="district-filter-btn" data-district="Meram" title="Meram ilçesini filtrele"><span class="district-ico"><i class="fas fa-map-marker-alt"></i></span>Meram</button>
    </div>
    <div class="input-group">
      <span class="input-group-text"><i class="fas fa-search"></i></span>
      <input type="text" class="form-control" id="searchInput" placeholder="Tabloda Ara...">
    </div>
    <button class="btn btn-outline-primary" onclick="printTable()"><i class="fas fa-print"></i> Yazdır</button>
    <button class="btn btn-outline-secondary" onclick="copyTable()"><i class="fas fa-copy"></i> Kopyala</button>
    <button class="btn btn-outline-success" onclick="downloadCSV()"><i class="fas fa-file-csv"></i> CSV İndir</button>
  </div>
  <div class="mb-3" id="activeFilters"></div>
  <!-- Detaylı Tablo (Tüm Talepler) + Gelişmiş Tablo Araçları -->
  <div class="card modern-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>Tüm Talepler</span>
      <div>
        <button class="btn btn-outline-primary btn-sm" onclick="printTable()"><i class="fas fa-print"></i></button>
        <button class="btn btn-outline-success btn-sm" onclick="downloadCSV()"><i class="fas fa-file-csv"></i></button>
        <button class="btn btn-outline-info btn-sm" onclick="copyTable()"><i class="fas fa-copy"></i></button>
        <button class="btn btn-outline-dark btn-sm" onclick="shareTable()"><i class="fas fa-share-alt"></i></button>
      </div>
    </div>
    <div class="card-body">
      <div class="mb-2">
        <input type="text" class="form-control" id="multiColSearch" placeholder="Çoklu kolon arama: örn. ekipman, talep eden, açıklama...">
        <button class="btn btn-outline-secondary btn-sm mt-2" onclick="saveFilterCombo()"><i class="fas fa-save"></i> Filtreyi Kaydet</button>
        <div id="savedFilters" class="mt-2"></div>
      </div>
      <div class="table-responsive">
        <table class="table modern-table table-striped table-hover mb-0" id="mainTalepTable">
          <thead><tr>
            <th>#</th>
            <th id="col-talepNo">Talep No</th>
            <th id="col-tarih">Tarih</th>
            <th id="col-tip">Tip</th>
            <th id="col-ekipman">Ekipman</th>
            <th id="col-kod">Kod</th>
            <th id="col-bolge">Bölge</th>
            <th id="col-eden">Talep Eden</th>
            <th id="col-aciliyet">Aciliyet</th>
            <th id="col-durum">Durum</th>
            <th id="col-dosya">Dosya</th>
            <th id="col-aciklama">Açıklama</th>
            <th></th>
          </tr></thead>
          <tbody id="talepTableBody"></tbody>
        </table>
      </div>
      <nav class="mt-2">
        <ul class="pagination justify-content-end mb-0" id="talepPagination"></ul>
      </nav>
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
    <div class="col-md-6 mb-4">
      <div class="card chart-card p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="mb-0"><i class="fas fa-chart-line"></i> Aylık Taleplerin Dağılımı</h6>
          <select class="form-select form-select-sm w-auto" id="chartTypeSelect">
            <option value="line">Çizgi</option>
            <option value="bar">Bar</option>
            <option value="doughnut">Donut</option>
          </select>
        </div>
        <canvas id="lineChart"></canvas>
        <div class="mt-2">
          <button class="btn btn-outline-info btn-sm" onclick="comparePeriods()"><i class="fas fa-random"></i> Dönem Karşılaştır</button>
          <button class="btn btn-outline-secondary btn-sm" onclick="filterFromChart()"><i class="fas fa-filter"></i> Grafikten Filtrele</button>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-4">
      <div class="card chart-card p-3">
        <h6><i class="fas fa-chart-pie"></i> Talep Tipine Göre Dağılım</h6>
        <canvas id="pieChart"></canvas>
      </div>
    </div>
    <div class="col-md-3 mb-4">
      <div class="card chart-card p-3">
        <h6><i class="fas fa-chart-bar"></i> En Çok Arıza Yapanlar</h6>
        <canvas id="barChart"></canvas>
      </div>
    </div>
  </div>
</div>
<script>
flatpickr('#filterDate', {mode:'range', dateFormat:'Y-m-d'});
// Chart.js örnek veriler
const lineChart = new Chart(document.getElementById('lineChart'), {
  type: 'line',
  data: {
    labels: ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran'],
    datasets: [{
      label: 'Talepler',
      data: [12, 19, 14, 17, 22, 15],
      borderColor: '#007bff',
      backgroundColor: 'rgba(0,123,255,0.1)',
      tension: 0.4,
      fill: true
    }]
  },
  options: {responsive:true, plugins:{legend:{display:false}}}
});
const pieChart = new Chart(document.getElementById('pieChart'), {
  type: 'pie',
  data: {
    labels: ['Bakım','Arıza','Transfer','Satın Alma'],
    datasets: [{
      data: [10, 7, 8, 7],
      backgroundColor: ['#ffc107','#dc3545','#6c757d','#17a2b8']
    }]
  },
  options: {responsive:true}
});
const barChart = new Chart(document.getElementById('barChart'), {
  type: 'bar',
  data: {
    labels: ['Jeneratör','Oksijen Kons.','Hilti','Matkap'],
    datasets: [{
      label: 'Arıza Sayısı',
      data: [5, 7, 3, 2],
      backgroundColor: '#dc3545'
    }]
  },
  options: {responsive:true, plugins:{legend:{display:false}}}
});
// Detaylı tablo için örnek veri
const talepData = [
  {no:'T2024001', tarih:'2024-06-01', tip:'Bakım', ekipman:'Jeneratör', kod:'EQ-001', bolge:'Selçuklu', eden:'admin', aciliyet:'Acil', durum:'Onaylandı', dosya:'', aciklama:'Periyodik bakım'},
  {no:'T2024002', tarih:'2024-06-02', tip:'Arıza', ekipman:'Oksijen Konsantratörü', kod:'EQ-002', bolge:'Karatay', eden:'teknisyen1', aciliyet:'Normal', durum:'Reddedildi', dosya:'ariza.pdf', aciklama:'Motor arızası'},
  {no:'T2024003', tarih:'2024-06-03', tip:'Transfer', ekipman:'Hilti Kırıcı', kod:'EQ-003', bolge:'Meram', eden:'lazBerat', aciliyet:'Acil', durum:'Bekliyor', dosya:'', aciklama:'Şantiye transferi'},
  {no:'T2024004', tarih:'2024-06-04', tip:'Satın Alma', ekipman:'Akülü Matkap', kod:'EQ-004', bolge:'Selçuklu', eden:'admin', aciliyet:'Normal', durum:'Onaylandı', dosya:'fatura.pdf', aciklama:'Yeni ekipman alımı'},
  {no:'T2024005', tarih:'2024-06-05', tip:'Bakım', ekipman:'Kompresör', kod:'EQ-005', bolge:'Karatay', eden:'teknisyen2', aciliyet:'Acil', durum:'Bekliyor', dosya:'', aciklama:'Filtre değişimi'},
  {no:'T2024006', tarih:'2024-06-06', tip:'Arıza', ekipman:'Kaynak Makinesi', kod:'EQ-006', bolge:'Meram', eden:'admin', aciliyet:'Normal', durum:'Onaylandı', dosya:'kaynak.jpg', aciklama:'Kablo kopması'},
  {no:'T2024007', tarih:'2024-06-07', tip:'Transfer', ekipman:'Vidalı Kompresör', kod:'EQ-007', bolge:'Selçuklu', eden:'teknisyen1', aciliyet:'Acil', durum:'Bekliyor', dosya:'', aciklama:'Depodan şantiyeye'},
  {no:'T2024008', tarih:'2024-06-08', tip:'Bakım', ekipman:'Jeneratör', kod:'EQ-001', bolge:'Karatay', eden:'admin', aciliyet:'Normal', durum:'Onaylandı', dosya:'', aciklama:'Yağ değişimi'},
  {no:'T2024009', tarih:'2024-06-09', tip:'Arıza', ekipman:'Oksijen Konsantratörü', kod:'EQ-002', bolge:'Meram', eden:'teknisyen2', aciliyet:'Acil', durum:'Reddedildi', dosya:'ariza2.pdf', aciklama:'Düşük basınç'},
  {no:'T2024010', tarih:'2024-06-10', tip:'Satın Alma', ekipman:'Akülü Matkap', kod:'EQ-004', bolge:'Selçuklu', eden:'admin', aciliyet:'Normal', durum:'Bekliyor', dosya:'', aciklama:'Yedek parça alımı'},
  // ... daha fazla satır eklenebilir ...
];
// Talep tipi ve ilçe filtreleri
let selectedTypes = [];
let selectedDistricts = [];
const typeBtns = document.querySelectorAll('.type-filter-btn');
typeBtns.forEach(btn => {
  btn.onclick = function() {
    const type = btn.dataset.type;
    if(selectedTypes.includes(type)) {
      selectedTypes = selectedTypes.filter(t=>t!==type);
      btn.classList.remove('selected');
    } else {
      selectedTypes.push(type);
      btn.classList.add('selected');
    }
    updateActiveFilters();
    applyFilters();
  };
});
const districtBtns = document.querySelectorAll('.district-filter-btn');
districtBtns.forEach(btn => {
  btn.onclick = function() {
    const district = btn.dataset.district;
    if(selectedDistricts.includes(district)) {
      selectedDistricts = selectedDistricts.filter(d=>d!==district);
      btn.classList.remove('selected');
    } else {
      selectedDistricts.push(district);
      btn.classList.add('selected');
    }
    updateActiveFilters();
    applyFilters();
  };
});
function updateActiveFilters() {
  let html = '';
  if(selectedTypes.length) html += selectedTypes.map(t=>`<span class='filter-chip'>${t}</span>`).join('');
  if(selectedDistricts.length) html += selectedDistricts.map(b=>`<span class='filter-chip'>${b}</span>`).join('');
  document.getElementById('activeFilters').innerHTML = html;
}
// Tabloyu filtrele (detaylı)
let filteredTalepData = [];
const pageSize = 5;
let currentPage = 1;
function applyFilters() {
  const searchVal = document.getElementById('searchInput').value.toLowerCase();
  const multiVal = document.getElementById('multiColSearch').value.toLowerCase();
  filteredTalepData = talepData.filter(d => {
    // Tip filtresi
    if(selectedTypes.length && !selectedTypes.includes(d.tip)) return false;
    // İlçe filtresi
    if(selectedDistricts.length && !selectedDistricts.includes(d.bolge)) return false;
    // Arama
    if(searchVal) {
      const all = Object.values(d).join(' ').toLowerCase();
      if(!all.includes(searchVal)) return false;
    }
    if(multiVal) {
      const all = Object.values(d).join(' ').toLowerCase();
      if(!multiVal.split(',').every(val=>all.includes(val.trim()))) return false;
    }
    return true;
  });
  currentPage = 1;
  renderTalepTable();
  renderTalepPagination();
}
document.getElementById('searchInput').oninput = applyFilters;
// Tabloyu ve pagination'ı filtreli veriyle göster
function renderTalepTable() {
  const data = filteredTalepData.length ? filteredTalepData : talepData;
  const start = (currentPage-1)*pageSize;
  const end = start+pageSize;
  let html = '';
  for(let i=0; i<pageSize; i++) {
    const d = data[start+i];
    if(d) {
      html += `<tr>
        <td>${start+i+1}</td>
        <td>${d.no}</td>
        <td>${d.tarih}</td>
        <td>${d.tip}</td>
        <td>${d.ekipman}</td>
        <td>${d.kod}</td>
        <td>${d.bolge}</td>
        <td>${d.eden}</td>
        <td><span class='badge ${d.aciliyet==='Acil'?'bg-danger':'bg-warning text-dark'}'>${d.aciliyet}</span></td>
        <td><span class='badge ${d.durum==='Onaylandı'?'bg-success':d.durum==='Reddedildi'?'bg-danger':'bg-warning text-dark'}'>${d.durum}</span></td>
        <td>${d.dosya?`<a href='#' class='btn btn-sm btn-outline-secondary'><i class='fas fa-paperclip'></i></a>`:''}</td>
        <td>${d.aciklama}</td>
        <td><button class='btn btn-sm btn-outline-info' onclick='showTalepDetail(${start+i})'><i class='fas fa-eye'></i></button></td>
      </tr>`;
    } else {
      html += `<tr><td colspan='13' style='height:48px;'></td></tr>`;
    }
  }
  document.getElementById('talepTableBody').innerHTML = html;
}
function renderTalepPagination() {
  const data = filteredTalepData.length ? filteredTalepData : talepData;
  const pageCount = Math.ceil(data.length/pageSize);
  let html = '';
  
  // Debug bilgisi
  console.log('Pagination render:', { currentPage, pageCount, dataLength: data.length });
  
  for(let i=1;i<=pageCount;i++) {
    const isActive = i === currentPage;
    html += `<li class='page-item${isActive?' active':''}'><a class='page-link' href='#' onclick='gotoTalepPage(${i});return false;'>${i}</a></li>`;
  }
  document.getElementById('talepPagination').innerHTML = html;
  
  // Aktif sayfa butonunu vurgula
  const activeButton = document.querySelector('.pagination .page-item.active .page-link');
  if (activeButton) {
    activeButton.style.background = '#6366f1';
    activeButton.style.color = '#fff';
    activeButton.style.fontWeight = '600';
  }
}
function gotoTalepPage(page) {
  currentPage = page;
  renderTalepTable();
  renderTalepPagination();
}
function showTalepDetail(idx) {
  const d = (filteredTalepData.length ? filteredTalepData : talepData)[idx];
  let html = `<div class='row mb-2'><div class='col-4 fw-bold'>Talep No:</div><div class='col-8'>${d.no}</div></div>`;
  html += `<div class='row mb-2'><div class='col-4 fw-bold'>Tarih:</div><div class='col-8'>${d.tarih}</div></div>`;
  html += `<div class='row mb-2'><div class='col-4 fw-bold'>Tip:</div><div class='col-8'>${d.tip}</div></div>`;
  html += `<div class='row mb-2'><div class='col-4 fw-bold'>Ekipman:</div><div class='col-8'>${d.ekipman} (${d.kod})</div></div>`;
  html += `<div class='row mb-2'><div class='col-4 fw-bold'>Bölge:</div><div class='col-8'>${d.bolge}</div></div>`;
  html += `<div class='row mb-2'><div class='col-4 fw-bold'>Talep Eden:</div><div class='col-8'>${d.eden}</div></div>`;
  html += `<div class='row mb-2'><div class='col-4 fw-bold'>Aciliyet:</div><div class='col-8'><span class='badge ${d.aciliyet==='Acil'?'bg-danger':'bg-warning text-dark'}'>${d.aciliyet}</span></div></div>`;
  html += `<div class='row mb-2'><div class='col-4 fw-bold'>Durum:</div><div class='col-8'><span class='badge ${d.durum==='Onaylandı'?'bg-success':d.durum==='Reddedildi'?'bg-danger':'bg-warning text-dark'}'>${d.durum}</span></div></div>`;
  html += `<div class='row mb-2'><div class='col-4 fw-bold'>Açıklama:</div><div class='col-8'>${d.aciklama}</div></div>`;
  if(d.dosya) html += `<div class='row mb-2'><div class='col-4 fw-bold'>Dosya:</div><div class='col-8'><a href='#' class='btn btn-sm btn-outline-secondary'><i class='fas fa-paperclip'></i> Dosya Görüntüle</a></div></div>`;
  document.getElementById('talepDetailBody').innerHTML = html;
  // İşlem geçmişi sekmesi
  let logHtml = '';
  logHtml += `<li class='list-group-item small'><i class='fas fa-user me-1'></i>admin: Talep Oluşturuldu (2024-06-01)</li>`;
  logHtml += `<li class='list-group-item small'><i class='fas fa-user-check me-1'></i>admin: Onaylandı (2024-06-02)</li>`;
  document.getElementById('talepLogList').innerHTML = logHtml;
  new bootstrap.Modal(document.getElementById('talepDetailModal')).show();
}
window.onload = function() {
  applyFilters();
  updateActiveFilters();
  // ... diğer grafik ve kpi kodları ...
};
// Tablo araçları
function printTable() { window.print(); }
function copyTable() { /* Tabloyu kopyala */ }
function downloadCSV() { /* CSV indir */ }
// Grafik türü seçme
let chartType = 'line';
document.getElementById('chartTypeSelect').onchange = function() {
  chartType = this.value;
  // Grafik türünü değiştir (örnek)
};
// Canlı bildirim/snackbar örneği
function showSnackbar(msg) {
  const sb = document.getElementById('snackbar');
  sb.innerText = msg;
  sb.style.display = 'block';
  setTimeout(()=>sb.style.display='none', 3000);
}
// Mini trend grafikler (örnek)
new Chart(document.getElementById('miniTrend1'), {type:'line',data:{labels:['',''],datasets:[{data:[110,120],borderColor:'#6366f1',tension:.4}]},options:{plugins:{legend:{display:false}},scales:{x:{display:false},y:{display:false}}}});
new Chart(document.getElementById('miniTrend2'), {type:'line',data:{labels:['',''],datasets:[{data:[100,98],borderColor:'#28a745',tension:.4}]},options:{plugins:{legend:{display:false}},scales:{x:{display:false},y:{display:false}}}});
new Chart(document.getElementById('miniTrend3'), {type:'line',data:{labels:['',''],datasets:[{data:[5,7],borderColor:'#dc3545',tension:.4}]},options:{plugins:{legend:{display:false}},scales:{x:{display:false},y:{display:false}}}});
// Tablo araçları (kolon gizle/göster, paylaş, export, kopyala)
function toggleColumns() { showSnackbar('Kolon gizle/göster özelliği örnek!'); }
function shareTable() { showSnackbar('Tablo paylaşma özelliği örnek!'); }
function saveFilterCombo() {
  const combo = (selectedTypes.join(',')||'Tümü')+' | '+(selectedDistricts.join(',')||'Tümü');
  const el = document.createElement('span');
  el.className = 'filter-chip';
  el.innerText = combo;
  document.getElementById('savedFilters').appendChild(el);
  showSnackbar('Filtre kombinasyonu kaydedildi!');
}
// Çoklu kolon arama
const multiColSearch = document.getElementById('multiColSearch');
multiColSearch.oninput = function() { applyFilters(); };
// Grafik üstünden tabloya filtreleme (örnek)
function filterFromChart() { selectedTypes=['Arıza']; updateActiveFilters(); applyFilters(); showSnackbar('Grafikten filtre uygulandı!'); }
function comparePeriods() { showSnackbar('Dönem karşılaştırma özelliği örnek!'); }
// Canlı veri simülasyonu
setTimeout(()=>showSnackbar('Yeni veri geldi!'), 5000);

// Kolon gizleme/gösterme fonksiyonları
let hiddenColumns = [];

function toggleColumn(columnName) {
  const columnIndex = getColumnIndex(columnName);
  const icon = document.querySelector(`#icon-${columnName}`);
  
  if (hiddenColumns.includes(columnName)) {
    // Kolonu göster
    hiddenColumns = hiddenColumns.filter(col => col !== columnName);
    icon.className = 'fas fa-check';
    showColumn(columnIndex);
    showSnackbar(`${getColumnDisplayName(columnName)} kolonu gösterildi`);
  } else {
    // Kolonu gizle
    hiddenColumns.push(columnName);
    icon.className = 'fas fa-times';
    hideColumn(columnIndex);
    showSnackbar(`${getColumnDisplayName(columnName)} kolonu gizlendi`);
  }
}

function getColumnIndex(columnName) {
  const columnMap = {
    'talepNo': 1,
    'tarih': 2,
    'tip': 3,
    'ekipman': 4,
    'kod': 5,
    'bolge': 6,
    'eden': 7,
    'aciliyet': 8,
    'durum': 9,
    'dosya': 10,
    'aciklama': 11
  };
  return columnMap[columnName];
}

function getColumnDisplayName(columnName) {
  const displayNames = {
    'talepNo': 'Talep No',
    'tarih': 'Tarih',
    'tip': 'Tip',
    'ekipman': 'Ekipman',
    'kod': 'Kod',
    'bolge': 'Bölge',
    'eden': 'Talep Eden',
    'aciliyet': 'Aciliyet',
    'durum': 'Durum',
    'dosya': 'Dosya',
    'aciklama': 'Açıklama'
  };
  return displayNames[columnName];
}

function hideColumn(index) {
  const table = document.getElementById('mainTalepTable');
  const headerRow = table.querySelector('thead tr');
  const bodyRows = table.querySelectorAll('tbody tr');
  
  // Başlık hücresini gizle
  if (headerRow.cells[index]) {
    headerRow.cells[index].style.display = 'none';
  }
  
  // Veri hücrelerini gizle
  bodyRows.forEach(row => {
    if (row.cells[index]) {
      row.cells[index].style.display = 'none';
    }
  });
}

function showColumn(index) {
  const table = document.getElementById('mainTalepTable');
  const headerRow = table.querySelector('thead tr');
  const bodyRows = table.querySelectorAll('tbody tr');
  
  // Başlık hücresini göster
  if (headerRow.cells[index]) {
    headerRow.cells[index].style.display = '';
  }
  
  // Veri hücrelerini göster
  bodyRows.forEach(row => {
    if (row.cells[index]) {
      row.cells[index].style.display = '';
    }
  });
}

function showAllColumns() {
  hiddenColumns = [];
  const table = document.getElementById('mainTalepTable');
  const headerRow = table.querySelector('thead tr');
  const bodyRows = table.querySelectorAll('tbody tr');
  
  // Tüm başlık hücrelerini göster
  for (let i = 0; i < headerRow.cells.length; i++) {
    headerRow.cells[i].style.display = '';
  }
  
  // Tüm veri hücrelerini göster
  bodyRows.forEach(row => {
    for (let i = 0; i < row.cells.length; i++) {
      row.cells[i].style.display = '';
    }
  });
  
  // Tüm ikonları güncelle
  const columnNames = ['talepNo', 'tarih', 'tip', 'ekipman', 'kod', 'bolge', 'eden', 'aciliyet', 'durum', 'dosya', 'aciklama'];
  columnNames.forEach(columnName => {
    const icon = document.querySelector(`#icon-${columnName}`);
    if (icon) icon.className = 'fas fa-check';
  });
  
  showSnackbar('Tüm kolonlar gösterildi');
}

function hideAllColumns() {
  const columnNames = ['talepNo', 'tarih', 'tip', 'ekipman', 'kod', 'bolge', 'eden', 'aciliyet', 'durum', 'dosya', 'aciklama'];
  hiddenColumns = [...columnNames];
  
  columnNames.forEach(columnName => {
    const index = getColumnIndex(columnName);
    hideColumn(index);
  });
  
  // Tüm ikonları güncelle
  columnNames.forEach(columnName => {
    const icon = document.querySelector(`#icon-${columnName}`);
    if (icon) icon.className = 'fas fa-times';
  });
  
  showSnackbar('Tüm kolonlar gizlendi');
}
</script>
@endsection