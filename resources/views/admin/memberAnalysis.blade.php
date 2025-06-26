@extends('layouts.admin')
@section('content')
<!-- Gerekli kütüphaneler ve stiller -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/dt-2.0.7/datatables.min.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/v/bs5/dt-2.0.7/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body.dark-mode { background: #181a1b; color: #e2e8f0; }
.member-header { background:linear-gradient(90deg,#6366f1 0%,#43e97b 100%);color:#fff;border-radius:1.2em;box-shadow:0 4px 24px #d1d9e6;padding:2.2em 1.5em 1.5em 1.5em;margin-bottom:2em;display:flex;flex-direction:column;align-items:flex-start;position:relative;overflow:hidden; }
.member-header h2 { font-size:2.3rem;font-weight:900;margin-bottom:.3em;letter-spacing:-1px;line-height:1.1; }
.member-header p { font-size:1.15rem;font-weight:500;opacity:.98; }
.member-kpi-row { display:flex;gap:1em;margin-bottom:1.5em;flex-wrap:wrap;justify-content:space-between; }
.member-kpi-card { flex:1 1 160px;min-width:140px;max-width:100%;background:linear-gradient(135deg,#43e97b 0%,#6366f1 100%);color:#fff;border-radius:1.2em;box-shadow:0 4px 24px #d1d9e6;padding:1em .8em .8em .8em;display:flex;flex-direction:column;align-items:center;position:relative;transition:all .3s cubic-bezier(0.4,0,0.2,1);cursor:pointer;overflow:visible; }
.member-kpi-card:hover { transform:translateY(-6px) scale(1.04);box-shadow:0 12px 40px rgba(99,102,241,0.18); }
.member-kpi-icon {
  width:36px;height:36px;display:flex;align-items:center;justify-content:center;
  border-radius:50%;font-size:1.2rem;margin-bottom:.3rem;
  background:rgba(99,102,241,0.12);
  color:#fff;
  box-shadow:0 2px 8px #e0e7ef;transition:all .3s cubic-bezier(0.4,0,0.2,1);
}
.member-kpi-card:hover .member-kpi-icon {
  background:#fff!important;
  color:#6366f1!important;
  transform: rotate(360deg);
  transition: all .5s cubic-bezier(0.4,0,0.2,1);
}
.member-kpi-value { font-size:1.2rem;font-weight:800;color:#fff;margin-bottom:.1rem;letter-spacing:-1px; }
.member-kpi-label { font-size:.93rem;color:#e0e7ef;font-weight:500;text-align:center; }
.member-kpi-trend { font-size:.92em; font-weight:600; margin-top:.2em; }
.member-kpi-trend.up { color:#43e97b; }
.member-kpi-trend.down { color:#dc3545; }
.member-filter-bar { background:linear-gradient(120deg,#f8fafc 60%,#e0e7ff 100%);border-radius:1.2em;box-shadow:0 2px 12px #e0e7ef;padding:1em 1em;margin-bottom:1.5em;display:flex;flex-wrap:wrap;gap:.7em;align-items:center; }
.member-filter-bar .form-control, .member-filter-bar .form-select { border-radius:.9em;border:1.5px solid #e0e7ef;background:#fff;font-size:1em;min-width:120px;padding:.6em 1em;transition:all .3s ease; }
.member-filter-bar .form-control:focus, .member-filter-bar .form-select:focus { border-color:#6366f1;box-shadow:0 2px 12px rgba(99,102,241,0.2);transform:scale(1.02); }
.member-filter-chip {background:#6366f1;color:#fff;border-radius:1em;padding:.2em .8em;margin-right:.4em;font-size:.95em;display:inline-flex;align-items:center;gap:.3em;}
.member-filter-chip .bi-x {cursor:pointer;}
.member-table th, .member-table td { padding:.7em 1em; font-size:1.05em; }
.member-table th { color:#6366f1;background:#f3f6fa;font-weight:700;position:sticky;top:0;z-index:10; }
.member-table tr { border-bottom:1px solid #e0e7ef;transition:all .2s ease; }
.member-table tr:hover { background:linear-gradient(90deg,rgba(99,102,241,0.05) 0%,rgba(67,233,123,0.05) 100%);transform:scale(1.01);box-shadow:0 2px 8px rgba(99,102,241,0.1); }
.member-table tr:last-child { border-bottom:none; }
.member-table tr:nth-child(even) { background:#f8fafc; }
.dark-mode .member-header, .dark-mode .member-filter-bar, .dark-mode .card { background:#23272b!important; color:#e2e8f0!important; }
.dark-mode .member-table th { background:#2d3748!important; color:#e2e8f0!important; }
.dark-mode .member-table tr:nth-child(even) { background:#23272b!important; }
#memberSnackbar { display:none;position:fixed;bottom:30px;right:30px;z-index:9999;background:linear-gradient(135deg,#6366f1 0%,#43e97b 100%);color:#fff;padding:1em 2em;border-radius:1em;box-shadow:0 4px 20px rgba(99,102,241,0.3);font-weight:600; }
@media (max-width: 900px) {
  .member-kpi-row {flex-direction:column;gap:.7em;}
  .member-header {padding:1.2em .7em 1em .7em;}
  .member-filter-bar {flex-direction:column;gap:.5em;}
}
@media (max-width: 600px) {
  .member-header h2 {font-size:1.3rem;}
  .member-header p {font-size:.95rem;}
  .member-kpi-card {padding:.7em .5em;}
  .member-table th, .member-table td {font-size:.95em;}
}
</style>
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <div class="member-header w-100">
      <h2 style="color:#fff" >👥 Üye İstatistikleri & Aktivite Raporu</h2>
      <p>Sistemdeki üyelerin istatistikleri, aktiviteleri ve rolleri hakkında detaylı analiz.</p>
      <div class="position-absolute top-0 end-0 mt-3 me-3 d-flex gap-2">
        <button class="btn btn-light btn-sm" id="toggleThemeBtn" title="Karanlık/Aydınlık Mod"><i class="bi bi-moon"></i></button>
        <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#helpModal" title="Yardım"><i class="bi bi-question-circle"></i></button>
      </div>
    </div>
  </div>
  <div class="member-kpi-row">
    <div class="member-kpi-card" onclick="showKpiDetail('total')">
      <div class="member-kpi-icon"><i class="fas fa-users"></i></div>
      <div class="member-kpi-value" id="kpiTotalMember">320</div>
      <div class="member-kpi-label">Toplam Üye</div>
      <div class="member-kpi-trend up"><i class="bi bi-arrow-up"></i> %4 artış</div>
    </div>
    <div class="member-kpi-card" onclick="showKpiDetail('active')">
      <div class="member-kpi-icon"><i class="fas fa-user-check"></i></div>
      <div class="member-kpi-value" id="kpiActiveMember">278</div>
      <div class="member-kpi-label">Aktif Üye</div>
      <div class="member-kpi-trend up"><i class="bi bi-arrow-up"></i> %2 artış</div>
    </div>
    <div class="member-kpi-card" onclick="showKpiDetail('new')">
      <div class="member-kpi-icon"><i class="fas fa-user-plus"></i></div>
      <div class="member-kpi-value" id="kpiNewMember">12</div>
      <div class="member-kpi-label">Bu Ay Katılan</div>
      <div class="member-kpi-trend down"><i class="bi bi-arrow-down"></i> %1 azalış</div>
    </div>
    <div class="member-kpi-card" onclick="showKpiDetail('passive')">
      <div class="member-kpi-icon"><i class="fas fa-user-slash"></i></div>
      <div class="member-kpi-value" id="kpiPassiveMember">18</div>
      <div class="member-kpi-label">Askıda Üye</div>
      <div class="member-kpi-trend up"><i class="bi bi-arrow-up"></i> %0.5 artış</div>
    </div>
  </div>
  <div class="member-filter-bar mb-3" id="memberFilterBar">
    <input type="text" class="form-control" id="memberFilterDate" placeholder="📅 Tarih Aralığı">
    <select class="form-select" id="memberFilterRole" multiple>
      <option value="Admin">Admin</option>
      <option value="Teknisyen">Teknisyen</option>
      <option value="Üye">Üye</option>
    </select>
    <select class="form-select" id="memberFilterStatus" multiple>
      <option value="Aktif">Aktif</option>
      <option value="Askıda">Askıda</option>
    </select>
    <input type="text" class="form-control" id="memberSearch" placeholder="Üye ara...">
    <button class="btn btn-outline-primary" id="clearMemberFiltersBtn"><i class="fas fa-times"></i> Temizle</button>
    <div id="activeFilterChips" class="d-flex flex-wrap"></div>
  </div>
  <div class="row g-3 mb-3">
    <div class="col-lg-4 col-md-6 col-12">
      <div class="card p-3 h-100">
        <h6 class="fw-bold mb-2"><i class="fas fa-chart-bar"></i> Rol Dağılımı</h6>
        <canvas id="roleChart" height="180"></canvas>
      </div>
    </div>
    <div class="col-lg-4 col-md-6 col-12">
      <div class="card p-3 h-100">
        <h6 class="fw-bold mb-2"><i class="fas fa-chart-line"></i> Üye Zaman Trendi</h6>
        <canvas id="trendChart" height="180"></canvas>
      </div>
    </div>
    <div class="col-lg-4 col-12">
      <div class="card p-3 h-100">
        <h6 class="fw-bold mb-2"><i class="fas fa-chart-pie"></i> Aktif/Pasif Oran</h6>
        <canvas id="statusChart" height="180"></canvas>
      </div>
    </div>
  </div>
  <div class="row g-3 mb-3">
    <div class="col-lg-8 col-12">
      <div class="card p-3 mb-2">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="fw-bold mb-2" style="font-size:1.15rem;"><i class="fas fa-users"></i> Üye Listesi</h6>
          <div>
            <button class="btn btn-outline-secondary btn-sm" id="exportExcelBtn" title="Excel'e Aktar"><i class="bi bi-file-earmark-excel"></i></button>
            <button class="btn btn-outline-primary btn-sm" id="addMemberBtn" title="Yeni Üye Ekle"><i class="bi bi-plus-circle"></i></button>
          </div>
        </div>
        <div class="table-responsive" style="overflow-x:unset;">
          <table class="table member-table table-striped table-hover mb-0 w-100" id="memberTable">
            <thead><tr>
              <th><input type="checkbox" id="selectAllRows"></th>
              <th></th>
              <th>#</th>
              <th>Ad Soyad</th>
              <th>Rol</th>
              <th>Durum</th>
              <th>Kayıt Tarihi</th>
              <th>Aksiyon</th>
            </tr></thead>
            <tbody id="memberTableBody"></tbody>
          </table>
          <div id="noDataIllu" class="no-data-illu" style="display:none;">
            <img src="https://cdn.dribbble.com/users/1138875/screenshots/4669703/no-data.png" alt="No Data" style="max-width:180px;opacity:.7;"><br>
            <span>Veri bulunamadı.</span>
          </div>
        </div>
        <div class="mt-2 d-flex gap-2 flex-wrap">
          <button class="btn btn-danger btn-sm" id="bulkDeleteBtn"><i class="bi bi-trash"></i> Seçiliyi Sil</button>
          <button class="btn btn-success btn-sm" id="bulkActivateBtn"><i class="bi bi-check2-circle"></i> Seçiliyi Aktif Yap</button>
          <button class="btn btn-secondary btn-sm" id="bulkSuspendBtn"><i class="bi bi-slash-circle"></i> Seçiliyi Askıya Al</button>
        </div>
      </div>
    </div>
  </div>
  <div id="memberSnackbar">Veriler güncellendi!</div>
</div>
<!-- Yardım Modalı -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="helpModalLabel">Kullanım Kılavuzu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <ul>
          <li>KPI kartlarına tıklayarak detaylı istatistik görebilirsiniz.</li>
          <li>Filtre barı ile üyeleri rol, durum ve tarihe göre filtreleyin.</li>
          <li>Tabloda arama, sıralama, sayfalama ve toplu işlem yapabilirsiniz.</li>
          <li>Satırdaki göz butonuna tıklayarak üye detayını görebilirsiniz.</li>
          <li>Karanlık mod için sağ üstteki ay simgesine tıklayın.</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<script>
// Karanlık mod toggle
const themeBtn = document.getElementById('toggleThemeBtn');
themeBtn.addEventListener('click', function() {
  document.body.classList.toggle('dark-mode');
  themeBtn.innerHTML = document.body.classList.contains('dark-mode') ? '<i class="bi bi-brightness-high"></i>' : '<i class="bi bi-moon"></i>';
});
// Flatpickr
flatpickr('#memberFilterDate', {mode:'range', dateFormat:'Y-m-d', locale:{rangeSeparator:' - '}});
// KPI animasyonları
function animateMemberCount(id, target, duration = 1200) {
  let el = document.getElementById(id);
  let start = Math.max(1, Math.floor(target * 0.7));
  let startTime = null;
  function animate(ts) {
    if (!startTime) startTime = ts;
    let progress = Math.min((ts - startTime) / duration, 1);
    el.innerText = Math.floor(start + (target-start)*progress);
    if (progress < 1) requestAnimationFrame(animate);
    else el.innerText = target;
  }
  el.innerText = start;
  requestAnimationFrame(animate);
}
animateMemberCount('kpiTotalMember', 320);
animateMemberCount('kpiActiveMember', 278);
animateMemberCount('kpiNewMember', 12);
animateMemberCount('kpiPassiveMember', 18);
// Üye listesi örnek veri
let memberData = [
  {ad:'Ali Korkmaz', rol:'Admin', durum:'Aktif', tarih:'2024-01-12'},
  {ad:'Ayşe Yılmaz', rol:'Teknisyen', durum:'Aktif', tarih:'2024-02-03'},
  {ad:'Mehmet Demir', rol:'Üye', durum:'Askıda', tarih:'2024-03-15'},
  {ad:'Fatma Kaya', rol:'Üye', durum:'Aktif', tarih:'2024-04-21'},
  {ad:'Zeynep Çelik', rol:'Teknisyen', durum:'Aktif', tarih:'2024-05-10'},
  {ad:'Burak Şahin', rol:'Üye', durum:'Aktif', tarih:'2024-06-01'},
  {ad:'Elif Güneş', rol:'Üye', durum:'Askıda', tarih:'2024-06-10'}
];
// Chart.js grafikler
let roleChart, trendChart, statusChart;
function renderCharts() {
  // Rol Dağılımı
  let roleCounts = {Admin:0, Teknisyen:0, Üye:0};
  memberData.forEach(m=>roleCounts[m.rol] = (roleCounts[m.rol]||0)+1);
  if(roleChart) roleChart.destroy();
  roleChart = new Chart(document.getElementById('roleChart'), {
    type:'bar',
    data:{
      labels:Object.keys(roleCounts),
      datasets:[{label:'Üye Sayısı',data:Object.values(roleCounts),backgroundColor:['#6366f1','#43e97b','#fbbf24']}]
    },
    options:{plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}
  });
  // Zaman Trendi
  let months = ['01','02','03','04','05','06'];
  let trend = months.map(m=>memberData.filter(d=>d.tarih.split('-')[1]===m).length);
  if(trendChart) trendChart.destroy();
  trendChart = new Chart(document.getElementById('trendChart'), {
    type:'line',
    data:{labels:['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran'],datasets:[{label:'Yeni Üye',data:trend,fill:true,borderColor:'#6366f1',backgroundColor:'rgba(99,102,241,0.15)',tension:.4}]},
    options:{plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}
  });
  // Aktif/Pasif Oran
  let statusCounts = {Aktif:0, Askıda:0};
  memberData.forEach(m=>statusCounts[m.durum] = (statusCounts[m.durum]||0)+1);
  if(statusChart) statusChart.destroy();
  statusChart = new Chart(document.getElementById('statusChart'), {
    type:'doughnut',
    data:{labels:Object.keys(statusCounts),datasets:[{data:Object.values(statusCounts),backgroundColor:['#43e97b','#6366f1']}]},
    options:{plugins:{legend:{position:'bottom'}}}
  });
}
renderCharts();
// KPI detay modalı
window.showKpiDetail = function(type) {
  let title = '', html = '';
  if(type==='total') {title='Toplam Üye'; html='Sistemde kayıtlı tüm üyelerin toplamı.';}
  if(type==='active') {title='Aktif Üye'; html='Şu anda aktif olan üyeler.';}
  if(type==='new') {title='Bu Ay Katılan'; html='Bu ay sisteme katılan yeni üyeler.';}
  if(type==='passive') {title='Askıda Üye'; html='Askıya alınmış üyeler.';}
  Swal.fire({title, html, icon:'info', confirmButtonText:'Kapat'});
}
// DataTable başlat
let memberTable = null;
function renderMemberTable(data) {
  let html = '';
  data.forEach((d,i)=>{
    html += `<tr>
      <td><input type='checkbox' class='form-check-input member-row-check'></td>
      <td>${i+1}</td>
      <td contenteditable='true' onblur='updateMemberField(${i},"ad",this.innerText)'>${d.ad}</td>
      <td><span class='badge bg-${d.rol==='Admin'?'primary':d.rol==='Teknisyen'?'success':'warning text-dark'}'>${d.rol}</span></td>
      <td><span class='badge bg-${d.durum==='Aktif'?'success':'secondary'}'>${d.durum}</span></td>
      <td>${d.tarih}</td>
      <td>
        <button class='btn btn-sm btn-outline-info' onclick='showMemberDetail(${i})'><i class='fas fa-eye'></i></button>
        <button class='btn btn-sm btn-outline-success' onclick='setMemberStatus(${i},"Aktif")'><i class='bi bi-check2-circle'></i></button>
        <button class='btn btn-sm btn-outline-secondary' onclick='setMemberStatus(${i},"Askıda")'><i class='bi bi-slash-circle'></i></button>
        <button class='btn btn-sm btn-outline-danger' onclick='deleteMember(${i})'><i class='bi bi-trash'></i></button>
      </td>
    </tr>`;
  });
  document.getElementById('memberTableBody').innerHTML = html;
  if (memberTable) memberTable.destroy();
  memberTable = new DataTable('#memberTable', {
    paging: true,
    searching: true,
    ordering: true,
    info: true,
    responsive: true,
    pageLength: 10,
    lengthMenu: [10, 20, 50, 100],
    language: {search: "Tabloda ara:"}
  });
}
renderMemberTable(memberData);
// Inline edit fonksiyonu
window.updateMemberField = function(idx, field, value) {
  memberData[idx][field] = value;
  showMemberSnackbar('📝 Üye adı güncellendi!');
}
// Satır silme
window.deleteMember = function(idx) {
  memberData.splice(idx,1);
  renderMemberTable(memberData);
  renderCharts();
  showMemberSnackbar('🗑️ Üye silindi!');
}
// Satır durum değiştir
window.setMemberStatus = function(idx, status) {
  memberData[idx].durum = status;
  renderMemberTable(memberData);
  renderCharts();
  showMemberSnackbar(status==='Aktif'?'✅ Üye aktif yapıldı!':'⏸️ Üye askıya alındı!');
}
// Toplu seçim
const selectAllRows = document.getElementById('selectAllRows');
selectAllRows.addEventListener('change', function() {
  document.querySelectorAll('.member-row-check').forEach(cb=>{cb.checked = selectAllRows.checked;});
});
// Toplu silme
const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
bulkDeleteBtn.addEventListener('click', function() {
  let selected = Array.from(document.querySelectorAll('.member-row-check')).map((cb,i)=>cb.checked?i:null).filter(i=>i!==null).reverse();
  if(selected.length===0) return showMemberSnackbar('Seçili üye yok!');
  selected.forEach(idx=>memberData.splice(idx,1));
  renderMemberTable(memberData);
  renderCharts();
  showMemberSnackbar('🗑️ Seçili üyeler silindi!');
});
// Toplu aktif yap
const bulkActivateBtn = document.getElementById('bulkActivateBtn');
bulkActivateBtn.addEventListener('click', function() {
  document.querySelectorAll('.member-row-check').forEach((cb,i)=>{if(cb.checked) memberData[i].durum='Aktif';});
  renderMemberTable(memberData);
  renderCharts();
  showMemberSnackbar('✅ Seçili üyeler aktif yapıldı!');
});
// Toplu askıya al
const bulkSuspendBtn = document.getElementById('bulkSuspendBtn');
bulkSuspendBtn.addEventListener('click', function() {
  document.querySelectorAll('.member-row-check').forEach((cb,i)=>{if(cb.checked) memberData[i].durum='Askıda';});
  renderMemberTable(memberData);
  renderCharts();
  showMemberSnackbar('⏸️ Seçili üyeler askıya alındı!');
});
// Filtre ve arama
const memberSearch = document.getElementById('memberSearch');
memberSearch.addEventListener('input', function() {
  memberTable.search(this.value).draw();
  showMemberSnackbar('🔍 Arama uygulandı!');
});
document.getElementById('clearMemberFiltersBtn').addEventListener('click', ()=>{
  memberSearch.value = '';
  memberTable.search('').draw();
  document.getElementById('memberFilterRole').selectedIndex = -1;
  document.getElementById('memberFilterStatus').selectedIndex = -1;
  document.getElementById('activeFilterChips').innerHTML = '';
  showMemberSnackbar('🧹 Filtreler temizlendi!');
});
// Rol ve durum filtreleri (çoklu seçim ve chip gösterimi)
const memberFilterRole = document.getElementById('memberFilterRole');
const memberFilterStatus = document.getElementById('memberFilterStatus');
const activeFilterChips = document.getElementById('activeFilterChips');
function updateFilterChips() {
  activeFilterChips.innerHTML = '';
  Array.from(memberFilterRole.selectedOptions).forEach(opt=>{
    let chip = document.createElement('span');
    chip.className = 'member-filter-chip';
    chip.innerHTML = `<i class='bi bi-person-badge'></i> ${opt.value} <i class='bi bi-x' onclick='removeRoleFilter("${opt.value}")'></i>`;
    activeFilterChips.appendChild(chip);
  });
  Array.from(memberFilterStatus.selectedOptions).forEach(opt=>{
    let chip = document.createElement('span');
    chip.className = 'member-filter-chip';
    chip.innerHTML = `<i class='bi bi-person-check'></i> ${opt.value} <i class='bi bi-x' onclick='removeStatusFilter("${opt.value}")'></i>`;
    activeFilterChips.appendChild(chip);
  });
}
window.removeRoleFilter = function(val) {
  Array.from(memberFilterRole.options).forEach(opt=>{if(opt.value===val) opt.selected=false;});
  updateFilterChips();
  filterTable();
}
window.removeStatusFilter = function(val) {
  Array.from(memberFilterStatus.options).forEach(opt=>{if(opt.value===val) opt.selected=false;});
  updateFilterChips();
  filterTable();
}
memberFilterRole.addEventListener('change', ()=>{updateFilterChips();filterTable();});
memberFilterStatus.addEventListener('change', ()=>{updateFilterChips();filterTable();});
function filterTable() {
  let roles = Array.from(memberFilterRole.selectedOptions).map(o=>o.value);
  let statuses = Array.from(memberFilterStatus.selectedOptions).map(o=>o.value);
  let filtered = memberData.filter(m=>
    (roles.length===0 || roles.includes(m.rol)) &&
    (statuses.length===0 || statuses.includes(m.durum))
  );
  renderMemberTable(filtered);
  renderCharts();
}
updateFilterChips();
// Snackbar
function showMemberSnackbar(msg) {
  let sb = document.getElementById('memberSnackbar');
  sb.innerText = msg;
  sb.style.display = 'block';
  setTimeout(()=>{sb.style.display='none';}, 2200);
}
// Üye detay (SweetAlert2 ile modal)
window.showMemberDetail = function(idx) {
  const d = memberData[idx];
  Swal.fire({
    title: d.ad,
    html: `<b>Rol:</b> ${d.rol}<br><b>Durum:</b> ${d.durum}<br><b>Kayıt Tarihi:</b> ${d.tarih}`,
    icon: d.durum==='Aktif'?'success':'info',
    confirmButtonText: 'Kapat'
  });
}
</script>
@endsection