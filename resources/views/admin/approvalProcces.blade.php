@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
  .approval-tabs .nav-link { font-weight: 600; font-size: 1.08rem; }
  .approval-badge { font-size: 0.95rem; border-radius: 1em; padding: 0.3em 0.9em; font-weight: 600; }
  .approval-badge.acil { background: #ff4d4f; color: #fff; }
  .approval-badge.normal { background: #ffec3d; color: #333; }
  .approval-badge.tamam { background: #28a745; color: #fff; }
  .approval-badge.red { background: #b993d6; color: #fff; }
  .approval-table th { background: #f7f7fa; font-weight: bold; }
  .approval-actions .btn { margin-right: 0.2rem; }
  .approval-row.selected { background: #e0e7ff !important; }
</style>
<!-- Bildirim Dropdown -->
<div class="dropdown position-fixed top-0 end-0 m-4" style="z-index:1055;">
  <button class="btn btn-light position-relative" type="button" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fas fa-bell fa-lg"></i>
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">2</span>
  </button>
  <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notifDropdown" style="min-width:320px;">
    <li><h6 class="dropdown-header">Bildirimler</h6></li>
    <li><a class="dropdown-item" href="#"><i class="fas fa-exclamation-circle text-danger me-2"></i>2 yeni acil onay talebi var!</a></li>
    <li><a class="dropdown-item" href="#"><i class="fas fa-comment-dots text-info me-2"></i>Yorumunuza cevap geldi.</a></li>
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item text-center small" href="#">Tümünü Gör</a></li>
  </ul>
</div>
<!-- Hızlı Filtreler -->
<div class="d-flex gap-2 mb-2">
  <button class="btn btn-outline-primary btn-sm">Acil Talepler</button>
  <button class="btn btn-outline-secondary btn-sm">Bana Atananlar</button>
  <button class="btn btn-outline-success btn-sm">Son 7 Gün</button>
  <button class="btn btn-outline-dark btn-sm">Tüm Talepler</button>
</div>
<!-- Sekmeler -->
<ul class="nav nav-tabs approval-tabs mb-3" id="approvalTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="bekleyen-tab" data-bs-toggle="tab" data-bs-target="#bekleyen" type="button" role="tab">Bekleyen Talepler <span class="badge bg-warning">{{ count($bekleyen ?? []) }}</span></button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="onaylanan-tab" data-bs-toggle="tab" data-bs-target="#onaylanan" type="button" role="tab">Onaylananlar <span class="badge bg-success">{{ count($onaylanan ?? []) }}</span></button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="reddedilen-tab" data-bs-toggle="tab" data-bs-target="#reddedilen" type="button" role="tab">Reddedilenler <span class="badge bg-danger">{{ count($reddedilen ?? []) }}</span></button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="banaatanan-tab" data-bs-toggle="tab" data-bs-target="#banaatanan" type="button" role="tab">Bana Atananlar <span class="badge bg-primary">{{ count($banaatanan ?? []) }}</span></button>
  </li>
</ul>
<div class="tab-content" id="approvalTabContent">
  <!-- Bekleyen Talepler Tablosu -->
  <div class="tab-pane fade show active" id="bekleyen" role="tabpanel">
    <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
      <button class="btn btn-outline-success" id="bulkApproveBtn"><i class="fas fa-check"></i> Toplu Onayla</button>
      <button class="btn btn-outline-danger" id="bulkRejectBtn"><i class="fas fa-times"></i> Toplu Reddet</button>
      <input type="text" class="form-control form-control-sm ms-auto" style="max-width:180px;" id="searchInput" placeholder="Ara...">
      <input type="text" class="form-control form-control-sm" id="filterDate" placeholder="Tarih" style="max-width:140px;">
      <select class="form-select form-select-sm" id="filterType" style="max-width:120px;">
        <option value="">Tür (Tümü)</option>
        <option>Bakım</option>
        <option>Arıza</option>
        <option>Transfer</option>
        <option>Satın Alma</option>
        <option>İzin</option>
      </select>
      <select class="form-select form-select-sm" id="filterAciliyet" style="max-width:120px;">
        <option value="">Aciliyet (Tümü)</option>
        <option>Acil</option>
        <option>Normal</option>
      </select>
    </div>
    <div class="card shadow-sm mb-4">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0 approval-table" id="bekleyenTable">
            <thead>
              <tr>
                <th><input type="checkbox" id="selectAllRows"></th>
                <th>Talep Tipi</th>
                <th>Ekipman</th>
                <th>Talep Eden</th>
                <th>Tarih</th>
                <th>Açıklama</th>
                <th>Aciliyet</th>
                <th>Durum</th>
                <th class="text-end">Aksiyon</th>
              </tr>
            </thead>
            <tbody id="bekleyenTbody"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- Onaylananlar Tablosu -->
  <div class="tab-pane fade" id="onaylanan" role="tabpanel">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 approval-table">
        <thead><tr><th>Tip</th><th>Ekipman</th><th>Talep Eden</th><th>Tarih</th><th>Açıklama</th><th>Durum</th><th>Aksiyon</th></tr></thead>
        <tbody id="onaylananTableBody"></tbody>
      </table>
    </div>
  </div>
  <!-- Reddedilenler Tablosu -->
  <div class="tab-pane fade" id="reddedilen" role="tabpanel">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 approval-table">
        <thead><tr><th>Tip</th><th>Ekipman</th><th>Talep Eden</th><th>Tarih</th><th>Açıklama</th><th>Durum</th><th>Aksiyon</th></tr></thead>
        <tbody id="reddedilenTableBody"></tbody>
      </table>
    </div>
  </div>
  <!-- Bana Atananlar Tablosu -->
  <div class="tab-pane fade" id="banaatanan" role="tabpanel">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 approval-table">
        <thead><tr><th>Tip</th><th>Ekipman</th><th>Talep Eden</th><th>Tarih</th><th>Açıklama</th><th>Aciliyet</th><th>Aksiyon</th></tr></thead>
        <tbody id="banaatananTableBody"></tbody>
      </table>
    </div>
  </div>
</div>
<!-- Detay Modalı (gelişmiş) -->
<div class="modal fade" id="approvalDetailModal" tabindex="-1" aria-labelledby="approvalDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="approvalDetailModalLabel">Talep Detayı</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body" id="approvalDetailBody">
        <!-- JS ile doldurulacak -->
      </div>
      <div class="modal-footer flex-column align-items-stretch">
        <div class="mb-2">
          <label class="form-label">Dosya Yükle / Önizle:</label>
          <input type="file" class="form-control mb-2" id="fileUpload">
          <div id="filePreview"></div>
        </div>
        <div class="mb-2">
          <label class="form-label">Onay/Red Açıklaması:</label>
          <div class="input-group">
            <input type="text" class="form-control" id="aiCommentInput" placeholder="Açıklama yazın...">
            <button class="btn btn-outline-secondary" type="button" id="aiSuggestBtn"><i class="fas fa-robot"></i> AI ile Öner</button>
          </div>
        </div>
        <div class="mb-2">
          <button class="btn btn-success me-2" id="modalApproveBtn"><i class="fas fa-check"></i> Onayla</button>
          <button class="btn btn-danger" id="modalRejectBtn"><i class="fas fa-times"></i> Reddet</button>
        </div>
        <div class="mt-2">
          <button class="btn btn-link text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#logCollapse">İşlem Geçmişi <i class="fas fa-angle-down"></i></button>
          <div class="collapse" id="logCollapse">
            <ul class="list-group small mt-2" id="logList"></ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
// Tarih picker
flatpickr('#filterDate', {dateFormat:'d.m.Y'});
// Detay modalı örnek veri
let approvalData = [
  {tip:'Bakım', ekipman:'Jeneratör 5kVA', eden:'admin', tarih:'18.06.2025', aciklama:'Periyodik bakım onayı', aciliyet:'Acil', durum:'Bekliyor', dosya:'', log:[{islem:'Talep Oluşturuldu',kisi:'admin',tarih:'18.06.2025'}], atanan:'admin'},
  {tip:'Arıza', ekipman:'Oksijen Konsantratörü', eden:'teknisyen1', tarih:'17.06.2025', aciklama:'Arıza bildirimi onayı', aciliyet:'Normal', durum:'Bekliyor', dosya:'', log:[{islem:'Talep Oluşturuldu',kisi:'teknisyen1',tarih:'17.06.2025'}], atanan:'admin'},
  {tip:'Transfer', ekipman:'Hilti Kırıcı', eden:'lazBerat', tarih:'16.06.2025', aciklama:'Depodan şantiyeye transfer onayı', aciliyet:'Acil', durum:'Bekliyor', dosya:'', log:[{islem:'Talep Oluşturuldu',kisi:'lazBerat',tarih:'16.06.2025'}], atanan:'teknisyen1'},
  {tip:'Satın Alma', ekipman:'Akülü Matkap', eden:'admin', tarih:'15.06.2025', aciklama:'Yeni ekipman satın alma onayı', aciliyet:'Normal', durum:'Bekliyor', dosya:'', log:[{islem:'Talep Oluşturuldu',kisi:'admin',tarih:'15.06.2025'}], atanan:'admin'}
];
let notifications = [
  {type:'acil', text:'2 yeni acil onay talebi var!'},
  {type:'yorum', text:'Yorumunuza cevap geldi.'}
];
function renderTables() {
  // Bekleyenler
  let bekleyenHtml = '';
  approvalData.forEach((d,i)=>{
    if(d.durum!=='Bekliyor') return;
    bekleyenHtml += `<tr class='approval-row'>
      <td><input type='checkbox' class='rowCheckbox' data-idx='${i}'></td>
      <td><span class='badge bg-info'><i class='fas fa-tools'></i> ${d.tip}</span></td>
      <td>${d.ekipman}</td>
      <td>${d.eden}</td>
      <td>${d.tarih}</td>
      <td>${d.aciklama}</td>
      <td><span class='approval-badge ${d.aciliyet==='Acil'?'acil':'normal'}'>${d.aciliyet}</span></td>
      <td><span class='badge bg-warning text-dark'>Bekliyor</span></td>
      <td class='text-end approval-actions'>
        <button class='btn btn-sm btn-outline-info' onclick='showApprovalDetail(${i})'><i class='fas fa-eye'></i></button>
        <button class='btn btn-sm btn-outline-success' onclick='approveRequest(${i})'><i class='fas fa-check'></i></button>
        <button class='btn btn-sm btn-outline-danger' onclick='rejectRequest(${i})'><i class='fas fa-times'></i></button>
      </td>
    </tr>`;
  });
  document.getElementById('bekleyenTbody').innerHTML = bekleyenHtml;
  // Onaylananlar
  let onaylananHtml = '';
  approvalData.forEach((d,i)=>{
    if(d.durum!=='Onaylandı') return;
    onaylananHtml += `<tr><td>${d.tip}</td><td>${d.ekipman}</td><td>${d.eden}</td><td>${d.tarih}</td><td>${d.aciklama}</td><td><span class='approval-badge tamam'>Onaylandı</span></td><td><button class='btn btn-sm btn-outline-info' onclick='showApprovalDetail(${i})'><i class='fas fa-eye'></i></button></td></tr>`;
  });
  document.getElementById('onaylananTableBody').innerHTML = onaylananHtml;
  // Reddedilenler
  let redHtml = '';
  approvalData.forEach((d,i)=>{
    if(d.durum!=='Reddedildi') return;
    redHtml += `<tr><td>${d.tip}</td><td>${d.ekipman}</td><td>${d.eden}</td><td>${d.tarih}</td><td>${d.aciklama}</td><td><span class='approval-badge red'>Reddedildi</span></td><td><button class='btn btn-sm btn-outline-info' onclick='showApprovalDetail(${i})'><i class='fas fa-eye'></i></button></td></tr>`;
  });
  document.getElementById('reddedilenTableBody').innerHTML = redHtml;
  // Bana Atananlar
  let banaHtml = '';
  approvalData.forEach((d,i)=>{
    if(d.atanan!=='admin' || d.durum!=='Bekliyor') return;
    banaHtml += `<tr><td>${d.tip}</td><td>${d.ekipman}</td><td>${d.eden}</td><td>${d.tarih}</td><td>${d.aciklama}</td><td><span class='approval-badge ${d.aciliyet==='Acil'?'acil':'normal'}'>${d.aciliyet}</span></td><td><button class='btn btn-sm btn-outline-info' onclick='showApprovalDetail(${i})'><i class='fas fa-eye'></i></button><button class='btn btn-sm btn-outline-success' onclick='approveRequest(${i})'><i class='fas fa-check'></i></button><button class='btn btn-sm btn-outline-danger' onclick='rejectRequest(${i})'><i class='fas fa-times'></i></button></td></tr>`;
  });
  document.getElementById('banaatananTableBody').innerHTML = banaHtml;
}
function approveRequest(idx) {
  approvalData[idx].durum = 'Onaylandı';
  approvalData[idx].log.push({islem:'Onaylandı', kisi:'admin', tarih:new Date().toLocaleDateString()});
  notifications.unshift({type:'onay', text: approvalData[idx].ekipman+' talebi onaylandı!'});
  renderTables(); renderNotifications();
}
function rejectRequest(idx) {
  approvalData[idx].durum = 'Reddedildi';
  approvalData[idx].log.push({islem:'Reddedildi', kisi:'admin', tarih:new Date().toLocaleDateString()});
  notifications.unshift({type:'red', text: approvalData[idx].ekipman+' talebi reddedildi!'});
  renderTables(); renderNotifications();
}
function bulkApprove() {
  document.querySelectorAll('.rowCheckbox:checked').forEach(cb=>approveRequest(cb.dataset.idx));
}
function bulkReject() {
  document.querySelectorAll('.rowCheckbox:checked').forEach(cb=>rejectRequest(cb.dataset.idx));
}
function renderNotifications() {
  const notifBtn = document.getElementById('notifDropdown');
  const notifList = notifBtn.nextElementSibling;
  notifBtn.querySelector('.badge').innerText = notifications.length;
  notifList.innerHTML = '<li><h6 class="dropdown-header">Bildirimler</h6></li>' +
    notifications.slice(0,5).map(n=>`<li><a class='dropdown-item' href='#'><i class='fas fa-bell me-2'></i>${n.text}</a></li>`).join('')+
    '<li><hr class="dropdown-divider"></li><li><a class="dropdown-item text-center small" href="#">Tümünü Gör</a></li>';
}
document.getElementById('bulkApproveBtn').onclick = bulkApprove;
document.getElementById('bulkRejectBtn').onclick = bulkReject;
window.onload = function() { renderTables(); renderNotifications(); };
// Yorum ekleme ile ilgili kodları kaldır
let currentDetailIdx = null;
function showApprovalDetail(idx) {
  currentDetailIdx = idx;
  const d = approvalData[idx];
  let html = `<div class='mb-2'><b>${d.tip}</b> - <span class='badge bg-info'>${d.ekipman}</span></div>`;
  html += `<div class='mb-2'><b>Talep Eden:</b> ${d.eden}</div>`;
  html += `<div class='mb-2'><b>Tarih:</b> ${d.tarih}</div>`;
  html += `<div class='mb-2'><b>Açıklama:</b> ${d.aciklama}</div>`;
  html += `<div class='mb-2'><b>Aciliyet:</b> <span class='approval-badge ${d.aciliyet==='Acil'?'acil':'normal'}'>${d.aciliyet}</span></div>`;
  html += `<div class='mb-2'><b>Durum:</b> <span class='badge bg-warning text-dark'>${d.durum}</span></div>`;
  document.getElementById('approvalDetailBody').innerHTML = html;
  // Logları doldur
  let logHtml = '';
  d.log.forEach(l => {
    logHtml += `<li class='list-group-item'><i class='fas fa-user me-1'></i>${l.kisi}: ${l.islem} (${l.tarih})</li>`;
  });
  document.getElementById('logList').innerHTML = logHtml;
  new bootstrap.Modal(document.getElementById('approvalDetailModal')).show();
}
document.getElementById('modalApproveBtn').onclick = function() {
  if(currentDetailIdx!==null) approveRequest(currentDetailIdx);
  bootstrap.Modal.getInstance(document.getElementById('approvalDetailModal')).hide();
};
document.getElementById('modalRejectBtn').onclick = function() {
  if(currentDetailIdx!==null) rejectRequest(currentDetailIdx);
  bootstrap.Modal.getInstance(document.getElementById('approvalDetailModal')).hide();
};
// Toplu seçim örneği
const checkboxes = document.querySelectorAll('.rowCheckbox');
const selectAll = document.getElementById('selectAllRows');
const bulkApproveBtn = document.getElementById('bulkApproveBtn');
const bulkRejectBtn = document.getElementById('bulkRejectBtn');
function updateBulkBtns() {
  const anyChecked = Array.from(checkboxes).some(cb=>cb.checked);
  bulkApproveBtn.disabled = !anyChecked;
  bulkRejectBtn.disabled = !anyChecked;
}
checkboxes.forEach(cb=>{
  cb.onchange = function() {
    cb.closest('tr').classList.toggle('selected', cb.checked);
    updateBulkBtns();
  };
});
if(selectAll) {
  selectAll.onchange = function() {
    checkboxes.forEach(cb=>{cb.checked=selectAll.checked;cb.dispatchEvent(new Event('change'));});
  };
}
// Dosya önizleme
const fileUpload = document.getElementById('fileUpload');
const filePreview = document.getElementById('filePreview');
if(fileUpload) {
  fileUpload.onchange = function() {
    const file = fileUpload.files[0];
    if(!file) return filePreview.innerHTML = '';
    if(file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = e => filePreview.innerHTML = `<img src='${e.target.result}' class='img-fluid rounded' style='max-height:180px;'>`;
      reader.readAsDataURL(file);
    } else if(file.type==='application/pdf') {
      const url = URL.createObjectURL(file);
      filePreview.innerHTML = `<embed src='${url}' type='application/pdf' width='100%' height='180px'>`;
    } else {
      filePreview.innerHTML = `<span class='text-muted'>Dosya: ${file.name}</span>`;
    }
  };
}
// AI açıklama önerisi (örnek)
document.getElementById('aiSuggestBtn').onclick = function() {
  document.getElementById('aiCommentInput').value = 'Talebiniz incelenmiş ve uygun bulunmuştur. Onaylanmıştır.';
};
</script>
@endsection