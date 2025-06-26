@extends('layouts.admin')
@section('content')
<!-- Gerekli kütüphaneler ve stiller -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/dt-2.0.7/datatables.min.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/dt-2.0.7/datatables.min.js"></script>
<style>
body.dark-mode { background: #181a1b; color: #e2e8f0; }
.users-header { background:linear-gradient(90deg,#6366f1 0%,#43e97b 100%);color:#fff;border-radius:1.2em;box-shadow:0 4px 24px #d1d9e6;padding:2.2em 1.5em 1.5em 1.5em;margin-bottom:2em;display:flex;flex-direction:column;align-items:flex-start;position:relative;overflow:hidden; }
.users-header h2 { font-size:2.3rem;font-weight:900;margin-bottom:.3em;letter-spacing:-1px;line-height:1.1; }
.users-header p { font-size:1.15rem;font-weight:500;opacity:.98; }
.user-kpi-row { display:flex;gap:1em;margin-bottom:1.5em;flex-wrap:wrap;justify-content:space-between; }
.user-kpi-card { flex:1 1 160px;min-width:140px;max-width:100%;background:linear-gradient(135deg,#43e97b 0%,#6366f1 100%);color:#fff;border-radius:1.2em;box-shadow:0 4px 24px #d1d9e6;padding:1em .8em .8em .8em;display:flex;flex-direction:column;align-items:center;position:relative;transition:all .3s cubic-bezier(0.4,0,0.2,1);cursor:pointer;overflow:visible; }
.user-kpi-card:hover { transform:translateY(-6px) scale(1.04);box-shadow:0 12px 40px rgba(99,102,241,0.18); }
.user-kpi-icon { width:36px;height:36px;display:flex;align-items:center;justify-content:center;border-radius:50%;font-size:1.2rem;margin-bottom:.3rem;background:rgba(99,102,241,0.12);color:#fff;box-shadow:0 2px 8px #e0e7ef;transition:all .3s cubic-bezier(0.4,0,0.2,1); }
.user-kpi-card:hover .user-kpi-icon { background:#fff!important;color:#6366f1!important;transform: rotate(360deg);transition: all .5s cubic-bezier(0.4,0,0.2,1); }
.user-kpi-value { font-size:1.2rem;font-weight:800;color:#fff;margin-bottom:.1rem;letter-spacing:-1px; }
.user-kpi-label { font-size:.93rem;color:#e0e7ef;font-weight:500;text-align:center; }
.user-kpi-trend { font-size:.92em; font-weight:600; margin-top:.2em; }
.user-kpi-trend.up { color:#43e97b; }
.user-kpi-trend.down { color:#dc3545; }
.user-filter-bar { background:linear-gradient(120deg,#f8fafc 60%,#e0e7ff 100%);border-radius:1.2em;box-shadow:0 2px 12px #e0e7ef;padding:1em 1em;margin-bottom:1.5em;display:flex;flex-wrap:wrap;gap:.7em;align-items:center; }
.user-filter-bar .form-control, .user-filter-bar .form-select { border-radius:.9em;border:1.5px solid #e0e7ef;background:#fff;font-size:1em;min-width:120px;padding:.6em 1em;transition:all .3s ease; }
.user-filter-bar .form-control:focus, .user-filter-bar .form-select:focus { border-color:#6366f1;box-shadow:0 2px 12px rgba(99,102,241,0.2);transform:scale(1.02); }
.user-filter-chip {background:#6366f1;color:#fff;border-radius:1em;padding:.2em .8em;margin-right:.4em;font-size:.95em;display:inline-flex;align-items:center;gap:.3em;}
.user-filter-chip .bi-x {cursor:pointer;}
.user-table th, .user-table td { padding:.7em 1em; font-size:1.05em; }
.user-table th { color:#6366f1;background:#f3f6fa;font-weight:700;position:sticky;top:0;z-index:10; }
.user-table tr { border-bottom:1px solid #e0e7ef;transition:all .2s ease; }
.user-table tr:hover { background:linear-gradient(90deg,rgba(99,102,241,0.05) 0%,rgba(67,233,123,0.05) 100%);transform:scale(1.01);box-shadow:0 2px 8px rgba(99,102,241,0.1); }
.user-table tr:last-child { border-bottom:none; }
.user-table tr:nth-child(even) { background:#f8fafc; }
.user-avatar {width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#6366f1 0%,#43e97b 100%);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.1em;box-shadow:0 2px 8px #e0e7ef;margin-right:.5em;}
.dark-mode .users-header, .dark-mode .user-filter-bar, .dark-mode .card { background:#23272b!important; color:#e2e8f0!important; }
.dark-mode .user-table th { background:#2d3748!important; color:#e2e8f0!important; }
.dark-mode .user-table tr:nth-child(even) { background:#23272b!important; }
#userSnackbar { display:none;position:fixed;bottom:30px;right:30px;z-index:9999;background:linear-gradient(135deg,#6366f1 0%,#43e97b 100%);color:#fff;padding:1em 2em;border-radius:1em;box-shadow:0 4px 20px rgba(99,102,241,0.3);font-weight:600; }
.no-data-illu {text-align:center;padding:2em 0;opacity:.7;}
@media (max-width: 900px) {
  .user-kpi-row {flex-direction:column;gap:.7em;}
  .users-header {padding:1.2em .7em 1em .7em;}
  .user-filter-bar {flex-direction:column;gap:.5em;}
}
@media (max-width: 600px) {
  .users-header h2 {font-size:1.3rem;}
  .users-header p {font-size:.95rem;}
  .user-kpi-card {padding:.7em .5em;}
  .user-table th, .user-table td {font-size:.95em;}
}
</style>
<div class="container-fluid">
  <!-- Ultra modern başlık -->
  <div class="users-header w-100 position-relative mb-4" style="background:linear-gradient(90deg,#6366f1 0%,#43e97b 100%);color:#fff;border-radius:1.2em;box-shadow:0 4px 24px #d1d9e6;padding:2.5em 2em 2em 2em;display:flex;flex-direction:column;align-items:flex-start;overflow:hidden;">
    <h2 style=" color:white; font-size:2.7rem;font-weight:900;letter-spacing:-1px;line-height:1.1;">👤 Kullanıcılar Yönetimi</h2>
    <p style="font-size:1.2rem;font-weight:500;opacity:.98;">Sistemdeki tüm kullanıcıları görüntüleyin, yönetin ve analiz edin.</p>
    <div class="position-absolute top-0 end-0 mt-3 me-3 d-flex gap-2">
      <button class="btn btn-light btn-sm" id="toggleThemeBtn" title="Karanlık/Aydınlık Mod"><i class="bi bi-moon"></i></button>
      <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#helpModal" title="Yardım"><i class="bi bi-question-circle"></i></button>
    </div>
  </div>
  <!-- Animasyonlu KPI kartları -->
  <div class="user-kpi-row mb-4">
    <div class="user-kpi-card shadow-lg" onclick="filterByKpi('all')" data-bs-toggle="tooltip" title="Tüm kullanıcıları gösterir.">
      <div class="user-kpi-icon"><i class="fas fa-users"></i></div>
      <div class="user-kpi-value" id="kpiTotalUser">120</div>
      <div class="user-kpi-label">Toplam Kullanıcı</div>
      <div class="user-kpi-trend up"><i class="bi bi-arrow-up"></i> %3 artış</div>
    </div>
    <div class="user-kpi-card shadow-lg" onclick="filterByKpi('admin')" data-bs-toggle="tooltip" title="Sadece adminleri gösterir.">
      <div class="user-kpi-icon"><i class="fas fa-user-shield"></i></div>
      <div class="user-kpi-value" id="kpiAdminUser">8</div>
      <div class="user-kpi-label">Admin</div>
      <div class="user-kpi-trend up"><i class="bi bi-arrow-up"></i> %1 artış</div>
    </div>
    <div class="user-kpi-card shadow-lg" onclick="filterByKpi('active')" data-bs-toggle="tooltip" title="Sadece aktif kullanıcıları gösterir.">
      <div class="user-kpi-icon"><i class="fas fa-user-check"></i></div>
      <div class="user-kpi-value" id="kpiActiveUser">102</div>
      <div class="user-kpi-label">Aktif Kullanıcı</div>
      <div class="user-kpi-trend up"><i class="bi bi-arrow-up"></i> %2 artış</div>
    </div>
    <div class="user-kpi-card shadow-lg" onclick="filterByKpi('new')" data-bs-toggle="tooltip" title="Bu ay eklenen kullanıcıları gösterir.">
      <div class="user-kpi-icon"><i class="fas fa-user-plus"></i></div>
      <div class="user-kpi-value" id="kpiNewUser">5</div>
      <div class="user-kpi-label">Bu Ay Eklenen</div>
      <div class="user-kpi-trend down"><i class="bi bi-arrow-down"></i> %0.5 azalış</div>
    </div>
  </div>
  <!-- Modern filtre barı -->
  <div class="user-filter-bar mb-4 shadow-sm rounded-3 p-3" id="userFilterBar" style="background:linear-gradient(120deg,#f8fafc 60%,#e0e7ff 100%);">
    <input type="text" class="form-control" id="userFilterDate" placeholder="📅 Tarih Aralığı">
    <select class="form-select" id="userFilterRole" multiple>
      <option value="Admin">Admin</option>
      <option value="Kullanıcı">Kullanıcı</option>
    </select>
    <select class="form-select" id="userFilterStatus" multiple>
      <option value="Aktif">Aktif</option>
      <option value="Pasif">Pasif</option>
    </select>
    <input type="text" class="form-control" id="userSearch" placeholder="Kullanıcı ara...">
    <button class="btn btn-outline-primary" id="clearUserFiltersBtn"><i class="fas fa-times"></i> Sıfırla</button>
    <button class="btn btn-outline-success" id="saveUserFiltersBtn"><i class="bi bi-bookmark"></i> Kaydet</button>
    <button class="btn btn-outline-info" id="loadUserFiltersBtn"><i class="bi bi-arrow-clockwise"></i> Geri Yükle</button>
    <div id="activeUserFilterChips" class="d-flex flex-wrap"></div>
    <span id="activeUserFilterCount" class="badge bg-info ms-2" style="display:none;"></span>
  </div>
  <!-- Sticky header'lı, avatar'lı, aksiyonlu kullanıcı tablosu -->
  <div class="card p-3 mb-4 shadow-lg">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h6 class="fw-bold mb-2" style="font-size:1.15rem;"><i class="fas fa-users"></i> Kullanıcı Listesi</h6>
      <div>
        <span id="selectedUserCount" class="badge bg-primary me-2" style="display:none;"></span>
        <button class="btn btn-outline-secondary btn-sm" id="exportUserExcelBtn" title="Excel'e Aktar"><i class="bi bi-file-earmark-excel"></i></button>
        <button class="btn btn-outline-primary btn-sm" id="addUserBtn" title="Yeni Kullanıcı Ekle"><i class="bi bi-plus-circle"></i></button>
      </div>
    </div>
    <div class="table-responsive" style="overflow-x:unset;">
      <table class="table user-table table-striped table-hover mb-0 w-100 align-middle" id="userTable">
        <thead class="sticky-top bg-white shadow-sm">
          <tr>
            <th><input type="checkbox" id="selectAllUserRows"></th>
            <th></th>
            <th>#</th>
            <th>Ad Soyad</th>
            <th>E-posta</th>
            <th>Rol</th>
            <th>Durum</th>
            <th>Son Giriş</th>
            <th>Kayıt Tarihi</th>
            <th>Aksiyon</th>
          </tr>
        </thead>
        <tbody id="userTableBody"></tbody>
      </table>
      <div id="noUserDataIllu" class="no-data-illu" style="display:none;">
        <img src="https://cdn.dribbble.com/users/1138875/screenshots/4669703/no-data.png" alt="No Data" style="max-width:180px;opacity:.7;"><br>
        <span>Veri bulunamadı.</span>
        <button class="btn btn-success mt-2" id="addUserBtnEmpty"><i class="bi bi-plus-circle"></i> Yeni Kullanıcı Ekle</button>
      </div>
    </div>
    <div class="mt-2 d-flex gap-2 flex-wrap">
      <button class="btn btn-danger btn-sm" id="bulkUserDeleteBtn"><i class="bi bi-trash"></i> Seçiliyi Sil</button>
      <button class="btn btn-success btn-sm" id="bulkUserActivateBtn"><i class="bi bi-check2-circle"></i> Seçiliyi Aktif Yap</button>
      <button class="btn btn-secondary btn-sm" id="bulkUserDeactivateBtn"><i class="bi bi-slash-circle"></i> Seçiliyi Pasif Yap</button>
      <button class="btn btn-warning btn-sm" id="bulkUserResetPwdBtn"><i class="bi bi-key"></i> Toplu Şifre Sıfırla</button>
      <button class="btn btn-info btn-sm" id="bulkUserRoleBtn"><i class="bi bi-person-badge"></i> Toplu Rol Değiştir</button>
    </div>
  </div>
  <div id="userSnackbar">Veriler güncellendi!</div>
</div>
<!-- Kullanıcı Ekle Modalı -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalLabel">Yeni Kullanıcı Ekle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control mb-2" id="newUserName" placeholder="Ad Soyad">
        <input type="email" class="form-control mb-2" id="newUserEmail" placeholder="E-posta">
        <select class="form-select mb-2" id="newUserRole">
          <option value="Kullanıcı">Kullanıcı</option>
          <option value="Admin">Admin</option>
        </select>
        <button class="btn btn-success w-100" id="saveNewUserBtn">Kaydet</button>
      </div>
    </div>
  </div>
</div>
<!-- Kullanıcı Detay Modalı -->
<div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userDetailModalLabel">Kullanıcı Detayı</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body" id="userDetailContent"></div>
    </div>
  </div>
</div>
<!-- Yardım Modalı -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="helpModalLabel">Kullanım Kılavuzu & Kısayollar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <ul>
          <li>KPI kartlarına tıklayarak hızlı filtre uygulayabilirsiniz.</li>
          <li>Filtre barında filtreleri kaydedip geri yükleyebilirsiniz.</li>
          <li>Tabloda arama, sıralama, sayfalama ve toplu işlem yapabilirsiniz.</li>
          <li>Satırdaki üç nokta ile daha fazla aksiyona ulaşabilirsiniz.</li>
          <li>Karanlık mod için sağ üstteki ay simgesine tıklayın.</li>
        </ul>
        <b>Klavye Kısayolları:</b>
        <ul>
          <li><kbd>Ctrl</kbd> + <kbd>F</kbd>: Tablo arama kutusuna odaklan</li>
          <li><kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>N</kbd>: Yeni kullanıcı ekle</li>
          <li><kbd>Esc</kbd>: Açık modalı kapat</li>
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
flatpickr('#userFilterDate', {mode:'range', dateFormat:'Y-m-d', locale:{rangeSeparator:' - '}});
// KPI animasyonları
function animateUserCount(id, target, duration = 1200) {
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
animateUserCount('kpiTotalUser', 120);
animateUserCount('kpiAdminUser', 8);
animateUserCount('kpiActiveUser', 102);
animateUserCount('kpiNewUser', 5);
// Kullanıcı örnek veri
let userData = [
  {ad:'Ali Korkmaz', email:'ali@site.com', rol:'Admin', durum:'Aktif', tarih:'2024-01-12'},
  {ad:'Ayşe Yılmaz', email:'ayse@site.com', rol:'Kullanıcı', durum:'Aktif', tarih:'2024-02-03'},
  {ad:'Mehmet Demir', email:'mehmet@site.com', rol:'Kullanıcı', durum:'Pasif', tarih:'2024-03-15'},
  {ad:'Fatma Kaya', email:'fatma@site.com', rol:'Kullanıcı', durum:'Aktif', tarih:'2024-04-21'},
  {ad:'Zeynep Çelik', email:'zeynep@site.com', rol:'Admin', durum:'Aktif', tarih:'2024-05-10'},
  {ad:'Burak Şahin', email:'burak@site.com', rol:'Kullanıcı', durum:'Aktif', tarih:'2024-06-01'},
  {ad:'Elif Güneş', email:'elif@site.com', rol:'Kullanıcı', durum:'Pasif', tarih:'2024-06-10'}
];
// DataTable başlat
let userTable = null;
function renderUserTable(data, useDataTable = true) {
  let html = '';
  if(data.length===0) {
    document.getElementById('noUserDataIllu').style.display = '';
  } else {
    document.getElementById('noUserDataIllu').style.display = 'none';
  }
  data.forEach((d,i)=>{
    let avatar = `<span class='user-avatar' title='${d.ad}'>${d.ad.split(' ').map(x=>x[0]).join('').substring(0,2).toUpperCase()}</span>`;
    html += `<tr>
      <td><input type='checkbox' class='form-check-input user-row-check'></td>
      <td>${avatar}</td>
      <td>${i+1}</td>
      <td contenteditable='true' onblur='updateUserField(${i},\"ad\",this.innerText)'>${d.ad}</td>
      <td contenteditable='true' onblur='updateUserField(${i},\"email\",this.innerText)'>${d.email}</td>
      <td><span class='badge bg-${d.rol==='Admin'?'primary':'success'}'>${d.rol}</span></td>
      <td><span class='badge bg-${d.durum==='Aktif'?'success':'secondary'}'>${d.durum}</span></td>
      <td>${d.tarih}</td>
      <td>
        <button class='btn btn-sm btn-outline-info' onclick='showUserDetail(${i})' title='Detay'><i class='fas fa-eye'></i></button>
        <button class='btn btn-sm btn-outline-warning' onclick='editUser(${i})' title='Düzenle'><i class='fas fa-edit'></i></button>
        <button class='btn btn-sm btn-outline-success' onclick='setUserStatus(${i},\"Aktif\")' title='Aktif Yap'><i class='bi bi-check2-circle'></i></button>
        <button class='btn btn-sm btn-outline-secondary' onclick='setUserStatus(${i},\"Pasif\")' title='Pasif Yap'><i class='bi bi-slash-circle'></i></button>
        <button class='btn btn-sm btn-outline-danger' onclick='deleteUser(${i})' title='Sil'><i class='bi bi-trash'></i></button>
      </td>
    </tr>`;
  });
  document.getElementById('userTableBody').innerHTML = html;
  if (userTable && useDataTable) { userTable.destroy(); userTable = null; }
  if (useDataTable) {
    userTable = new DataTable('#userTable', {
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
}
// Sayfa ilk yüklendiğinde DataTable ile başlat
renderUserTable(userData, true);
// Filtre fonksiyonu sadece HTML günceller, DataTable yok
function filterUserTable() {
  let roles = Array.from(userFilterRole.selectedOptions).map(o=>o.value);
  let statuses = Array.from(userFilterStatus.selectedOptions).map(o=>o.value);
  let filtered = userData.filter(u=>
    (roles.length===0 || roles.includes(u.rol)) &&
    (statuses.length===0 || statuses.includes(u.durum))
  );
  document.getElementById('userSearch').value = '';
  if (userTable) { userTable.destroy(); userTable = null; }
  renderUserTable(filtered, false);
}
// Inline edit fonksiyonu
window.updateUserField = function(idx, field, value) {
  userData[idx][field] = value;
  showUserSnackbar('📝 Kullanıcı bilgisi güncellendi!');
}
// Satır silme
window.deleteUser = function(idx) {
  userData.splice(idx,1);
  filterUserTable();
  showUserSnackbar('🗑️ Kullanıcı silindi!');
}
// Satır durum değiştir
window.setUserStatus = function(idx, status) {
  userData[idx].durum = status;
  filterUserTable();
  showUserSnackbar(status==='Aktif'?'✅ Kullanıcı aktif yapıldı!':'⏸️ Kullanıcı pasif yapıldı!');
}
// Toplu seçim
const selectAllUserRows = document.getElementById('selectAllUserRows');
selectAllUserRows.addEventListener('change', function() {
  document.querySelectorAll('.user-row-check').forEach(cb=>{cb.checked = selectAllUserRows.checked;});
});
// Toplu silme
const bulkUserDeleteBtn = document.getElementById('bulkUserDeleteBtn');
bulkUserDeleteBtn.addEventListener('click', function() {
  let selected = Array.from(document.querySelectorAll('.user-row-check')).map((cb,i)=>cb.checked?i:null).filter(i=>i!==null).reverse();
  if(selected.length===0) return showUserSnackbar('Seçili kullanıcı yok!');
  selected.forEach(idx=>userData.splice(idx,1));
  filterUserTable();
  showUserSnackbar('🗑️ Seçili kullanıcılar silindi!');
});
// Toplu aktif yap
const bulkUserActivateBtn = document.getElementById('bulkUserActivateBtn');
bulkUserActivateBtn.addEventListener('click', function() {
  document.querySelectorAll('.user-row-check').forEach((cb,i)=>{if(cb.checked) userData[i].durum='Aktif';});
  filterUserTable();
  showUserSnackbar('✅ Seçili kullanıcılar aktif yapıldı!');
});
// Toplu pasif yap
const bulkUserDeactivateBtn = document.getElementById('bulkUserDeactivateBtn');
bulkUserDeactivateBtn.addEventListener('click', function() {
  document.querySelectorAll('.user-row-check').forEach((cb,i)=>{if(cb.checked) userData[i].durum='Pasif';});
  filterUserTable();
  showUserSnackbar('⏸️ Seçili kullanıcılar pasif yapıldı!');
});
// Filtre ve arama
const userSearch = document.getElementById('userSearch');
userSearch.addEventListener('input', function() {
  userTable.search(this.value).draw();
  showUserSnackbar('🔍 Arama uygulandı!');
});
document.getElementById('clearUserFiltersBtn').addEventListener('click', ()=>{
  userSearch.value = '';
  document.getElementById('userFilterRole').selectedIndex = -1;
  document.getElementById('userFilterStatus').selectedIndex = -1;
  document.getElementById('activeUserFilterChips').innerHTML = '';
  renderUserTable(userData, true);
  showUserSnackbar('🧹 Filtreler temizlendi!');
});
// Rol ve durum filtreleri (çoklu seçim ve chip gösterimi)
const userFilterRole = document.getElementById('userFilterRole');
const userFilterStatus = document.getElementById('userFilterStatus');
const activeUserFilterChips = document.getElementById('activeUserFilterChips');
function updateUserFilterChips() {
  activeUserFilterChips.innerHTML = '';
  Array.from(userFilterRole.selectedOptions).forEach(opt=>{
    let chip = document.createElement('span');
    chip.className = 'user-filter-chip';
    chip.innerHTML = `<i class='bi bi-person-badge'></i> ${opt.value} <i class='bi bi-x' onclick='removeUserRoleFilter("${opt.value}")'></i>`;
    activeUserFilterChips.appendChild(chip);
  });
  Array.from(userFilterStatus.selectedOptions).forEach(opt=>{
    let chip = document.createElement('span');
    chip.className = 'user-filter-chip';
    chip.innerHTML = `<i class='bi bi-person-check'></i> ${opt.value} <i class='bi bi-x' onclick='removeUserStatusFilter("${opt.value}")'></i>`;
    activeUserFilterChips.appendChild(chip);
  });
}
window.removeUserRoleFilter = function(val) {
  Array.from(userFilterRole.options).forEach(opt=>{if(opt.value===val) opt.selected=false;});
  updateUserFilterChips();
  filterUserTable();
}
window.removeUserStatusFilter = function(val) {
  Array.from(userFilterStatus.options).forEach(opt=>{if(opt.value===val) opt.selected=false;});
  updateUserFilterChips();
  filterUserTable();
}
userFilterRole.addEventListener('change', filterUserTable);
userFilterStatus.addEventListener('change', filterUserTable);
updateUserFilterChips();
// Export Excel
const exportUserExcelBtn = document.getElementById('exportUserExcelBtn');
exportUserExcelBtn.addEventListener('click', function() {
  let ws = XLSX.utils.json_to_sheet(userData);
  let wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, "Kullanicilar");
  XLSX.writeFile(wb, "kullanicilar.xlsx");
  showUserSnackbar('📁 Excel dosyası indirildi!');
});
// Yeni kullanıcı ekle (dummy)
document.getElementById('addUserBtn').addEventListener('click', function() {
  userData.push({ad:'Yeni Kullanıcı', email:'yeni@site.com', rol:'Kullanıcı', durum:'Aktif', tarih:'2024-06-30'});
  filterUserTable();
  showUserSnackbar('➕ Yeni kullanıcı eklendi!');
});
// Snackbar
function showUserSnackbar(msg) {
  let sb = document.getElementById('userSnackbar');
  sb.innerText = msg;
  sb.style.display = 'block';
  setTimeout(()=>{sb.style.display='none';}, 2200);
}
// Kullanıcı detay (SweetAlert2 ile modal)
window.showUserDetail = function(idx) {
  const d = userData[idx];
  Swal.fire({
    title: d.ad,
    html: `<b>E-posta:</b> ${d.email}<br><b>Rol:</b> ${d.rol}<br><b>Durum:</b> ${d.durum}<br><b>Kayıt Tarihi:</b> ${d.tarih}`,
    icon: d.durum==='Aktif'?'success':'info',
    confirmButtonText: 'Kapat'
  });
}
// Kullanıcı düzenle (demo)
window.editUser = function(idx) {
  Swal.fire({
    title: 'Kullanıcı Düzenle',
    html: `<input id='editAd' class='swal2-input' value='${userData[idx].ad}'><input id='editEmail' class='swal2-input' value='${userData[idx].email}'>`,
    showCancelButton: true,
    confirmButtonText: 'Kaydet',
    cancelButtonText: 'İptal',
    preConfirm: () => {
      userData[idx].ad = document.getElementById('editAd').value;
      userData[idx].email = document.getElementById('editEmail').value;
      filterUserTable();
      showUserSnackbar('✅ Kullanıcı güncellendi!');
    }
  });
}
</script>
@endsection