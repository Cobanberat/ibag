@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@vite(['resources/css/reporting.css'])
<div class="container-fluid">
  <h3 class="mb-4">Raporlama & Analiz Paneli</h3>
  <!-- Bildirim -->
  <div class="d-flex justify-content-end align-items-center mb-3">
    <div id="snackbar" style="display:none;position:fixed;top:1.5em;right:2em;z-index:9999;" class="alert alert-info shadow">Yeni veri geldi!</div>
  </div>
  <!-- Otomatik Rapor Planlama ve Paylaşım -->
  <!-- Favori Raporlarım -->
 
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
        <span class="position-absolute top-0 end-0 badge bg-danger" style="margin:10px;">Kritik!</span>
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
      <div class="card kpi-card text-center p-3" id="kpiSupplyCard" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#supplyModal">
        <div class="kpi-icon text-info"><i class="fas fa-truck-loading"></i></div>
        <div class="kpi-value" id="kpiSupply">3</div>
        <div class="kpi-label">Tedarik Edilmesi Gereken Ürünler</div>
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
  {no:'T2024011', tarih:'2024-06-11', tip:'Arıza', ekipman:'UPS', kod:'EQ-008', bolge:'Karatay', eden:'teknisyen3', aciliyet:'Acil', durum:'Onaylandı', dosya:'ups.pdf', aciklama:'Akü değişimi yapıldı'},
  {no:'T2024012', tarih:'2024-06-12', tip:'Arıza', ekipman:'Klima', kod:'EQ-009', bolge:'Selçuklu', eden:'admin', aciliyet:'Normal', durum:'Onaylandı', dosya:'', aciklama:'Gaz dolumu ve bakım'},
  {no:'T2024013', tarih:'2024-06-13', tip:'Arıza', ekipman:'Su Pompası', kod:'EQ-010', bolge:'Meram', eden:'teknisyen2', aciliyet:'Acil', durum:'Onaylandı', dosya:'pompa.jpg', aciklama:'Motor sargısı değişti'},
  {no:'T2024014', tarih:'2024-06-14', tip:'Arıza', ekipman:'Asansör', kod:'EQ-011', bolge:'Karatay', eden:'teknisyen1', aciliyet:'Normal', durum:'Onaylandı', dosya:'asansor.pdf', aciklama:'Kabin sensörü arızası giderildi'},
  {no:'T2024015', tarih:'2024-06-15', tip:'Arıza', ekipman:'Yangın Alarmı', kod:'EQ-012', bolge:'Selçuklu', eden:'admin', aciliyet:'Acil', durum:'Onaylandı', dosya:'', aciklama:'Dedektör değişimi yapıldı'},
  {no:'T2024016', tarih:'2024-06-16', tip:'Arıza', ekipman:'Jeneratör', kod:'EQ-001', bolge:'Meram', eden:'teknisyen3', aciliyet:'Normal', durum:'Onaylandı', dosya:'jeneratör.pdf', aciklama:'Yakıt sistemi temizlendi'},
  {no:'T2024017', tarih:'2024-06-17', tip:'Arıza', ekipman:'Matkap', kod:'EQ-013', bolge:'Karatay', eden:'teknisyen2', aciliyet:'Acil', durum:'Onaylandı', dosya:'matkap.jpg', aciklama:'Kömür değişimi yapıldı'},
  {no:'T2024018', tarih:'2024-06-18', tip:'Arıza', ekipman:'Kompresör', kod:'EQ-005', bolge:'Selçuklu', eden:'admin', aciliyet:'Normal', durum:'Onaylandı', dosya:'', aciklama:'Basınç anahtarı değişti'},
  {no:'T2024019', tarih:'2024-06-19', tip:'Arıza', ekipman:'Kaynak Makinesi', kod:'EQ-006', bolge:'Meram', eden:'teknisyen1', aciliyet:'Acil', durum:'Onaylandı', dosya:'kaynak2.jpg', aciklama:'Fan tamiri yapıldı'},
  {no:'T2024020', tarih:'2024-06-20', tip:'Arıza', ekipman:'Oksijen Konsantratörü', kod:'EQ-002', bolge:'Karatay', eden:'teknisyen3', aciliyet:'Normal', durum:'Onaylandı', dosya:'oksijen.pdf', aciklama:'Filtre değişimi ve genel bakım'}
];

// Sadece tamir (arızası onaylanmış) ekipmanlar
const tamirGorenler = talepData.filter(d => d.tip === 'Arıza' && d.durum === 'Onaylandı');

// Talep tipi filtreleri
let selectedTypes = [];
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
function updateActiveFilters() {
  let html = '';
  if(selectedTypes.length) html += selectedTypes.map(t=>`<span class='filter-chip'>${t}</span>`).join('');
  document.getElementById('activeFilters').innerHTML = html;
}
// Tabloyu filtrele (detaylı)
let filteredTalepData = [];
const pageSize = 5;
let currentPage = 1;
function applyFilters() {
  const searchVal = document.getElementById('searchInput').value.toLowerCase();
  const multiVal = document.getElementById('multiColSearch').value.toLowerCase();
  // Sadece tamir görenler üzerinden filtrele
  filteredTalepData = tamirGorenler.filter(d => {
    // Tip filtresi
    if(selectedTypes.length && !selectedTypes.includes(d.tip)) return false;
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
  const data = filteredTalepData.length ? filteredTalepData : tamirGorenler; // Sadece tamir görenleri göster
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
        <td>${d.eden}</td>
        <td><span class='badge ${d.aciliyet==='Acil'?'bg-danger':'bg-warning text-dark'}'>${d.aciliyet}</span></td>
        <td><span class='badge ${d.durum==='Onaylandı'?'bg-success':d.durum==='Reddedildi'?'bg-danger':'bg-warning text-dark'}'>${d.durum}</span></td>
        <td>${d.dosya?`<a href='#' class='btn btn-sm btn-outline-secondary'><i class='fas fa-paperclip'></i></a>`:''}</td>
        <td>${d.aciklama}</td>
        <td><button class='btn btn-sm btn-outline-info' onclick='showTalepDetail(${start+i})'><i class='fas fa-eye'></i></button></td>
      </tr>`;
    } else {
      html += `<tr><td colspan='12' style='height:48px;'></td></tr>`;
    }
  }
  document.getElementById('talepTableBody').innerHTML = html;
}
function renderTalepPagination() {
  const data = filteredTalepData.length ? filteredTalepData : tamirGorenler; // Sadece tamir görenleri göster
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
  const d = (filteredTalepData.length ? filteredTalepData : tamirGorenler)[idx]; // Sadece tamir görenleri göster
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
  const combo = (selectedTypes.join(',')||'Tümü');
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

// Tedarik edilmesi gereken ürünler örnek veri
const supplyData = [
  { name: 'Akülü Matkap', adet: 2, aciklama: 'Yedek parça bekleniyor' },
  { name: 'Oksijen Konsantratörü', adet: 1, aciklama: 'Arızalı, yeni sipariş verildi' },
  { name: 'Jeneratör Yağı', adet: 5, aciklama: 'Stokta yok' }
];

function renderSupplyTable() {
  let html = '';
  supplyData.forEach((item, idx) => {
    html += `<tr><td>${idx+1}</td><td>${item.name}</td><td>${item.adet}</td><td>${item.aciklama}</td></tr>`;
  });
  document.getElementById('supplyTableBody').innerHTML = html;
  document.getElementById('kpiSupply').innerText = supplyData.length;
}

// Modal açıldığında tabloyu doldur
const supplyModal = document.getElementById('supplyModal');
supplyModal.addEventListener('show.bs.modal', renderSupplyTable);

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
  const columnNames = ['talepNo', 'tarih', 'tip', 'ekipman', 'kod', 'eden', 'aciliyet', 'durum', 'dosya', 'aciklama'];
  columnNames.forEach(columnName => {
    const icon = document.querySelector(`#icon-${columnName}`);
    if (icon) icon.className = 'fas fa-check';
  });
  
  showSnackbar('Tüm kolonlar gösterildi');
}

function hideAllColumns() {
  const columnNames = ['talepNo', 'tarih', 'tip', 'ekipman', 'kod', 'eden', 'aciliyet', 'durum', 'dosya', 'aciklama'];
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
