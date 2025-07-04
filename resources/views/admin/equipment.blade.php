@extends('layouts.admin')
@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a></li>
        <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Ekipmanlar' }}</li>
    </ol>
</nav>
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2 class="mb-0 fw-bold"><i class="fas fa-cubes me-2 text-primary"></i>Ekipman Yönetimi</h2>
        </div>
        <div class="col-md-6 text-end">
            <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
                <i class="fas fa-plus"></i> Ekipman Ekle
            </button>
            <button class="btn btn-outline-primary ms-2 shadow-sm" id="exportCsvBtn">
                <i class="fas fa-file-csv"></i> CSV Aktar
            </button>
            <button class="btn btn-outline-danger ms-2 shadow-sm" id="deleteSelectedBtn" disabled>
                <i class="fas fa-trash"></i> Seçiliyi Sil
            </button>
        </div>
    </div>
    <div class="row mb-4 g-2">
        <div class="col-md-2">
            <div class="card text-center shadow-sm border-success">
                <div class="card-body py-2">
                    <div class="fw-bold text-success">Aktif</div>
                    <div class="fs-5" id="activeCount">2</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center shadow-sm border-warning">
                <div class="card-body py-2">
                    <div class="fw-bold text-warning">Bakımda</div>
                    <div class="fs-5" id="maintenanceCount">1</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center shadow-sm border-primary">
                <div class="card-body py-2">
                    <div class="fw-bold text-primary">Toplam</div>
                    <div class="fs-5" id="totalCount">3</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex align-items-center justify-content-end gap-2">
            <input type="text" class="form-control w-auto" placeholder="Ekipman Adı Ara..." id="searchInput" style="max-width:180px;">
            <select class="form-select w-auto" id="typeFilter" style="max-width:180px;">
                <option value="">Tüm Türler</option>
                <option>Elektronik</option>
                <option>El Aleti</option>
            </select>
            <select class="form-select w-auto" id="statusFilter" style="max-width:180px;">
                <option value="">Tüm Durumlar</option>
                <option>Aktif</option>
                <option>Bakımda</option>
            </select>
        </div>
    </div>
    <div class="card shadow-lg border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="equipmentTable" style="min-height:400px;">
                    <thead class="table-light">
                        <tr>
                            <th style="width:36px"><input type="checkbox" id="selectAll"></th>
                            <th class="sortable" data-sort="name">Ekipman Adı <i class="fas fa-sort"></i></th>
                            <th class="sortable" data-sort="serial">Seri No <i class="fas fa-sort"></i></th>
                            <th class="sortable" data-sort="type">Türü <i class="fas fa-sort"></i></th>
                            <th class="sortable" data-sort="status">Durumu <i class="fas fa-sort"></i></th>
                            <th class="sortable" data-sort="date">Kayıt Tarihi <i class="fas fa-sort"></i></th>
                            <th class="sortable" data-sort="location">Lokasyon <i class="fas fa-sort"></i></th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Satırlar JS ile doldurulacak -->
                    </tbody>
                </table>
            </div>
            <nav class="mt-3 sticky-pagination">
                <ul class="pagination justify-content-end mb-0" id="pagination">
                    <!-- Pagination JS ile doldurulacak -->
                </ul>
            </nav>
        </div>
    </div>
    <!-- Ekipman Ekle Modal -->
    <div class="modal fade" id="addEquipmentModal" tabindex="-1" aria-labelledby="addEquipmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEquipmentModalLabel">Ekipman Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addEquipmentForm">
                        <div class="mb-3">
                            <label for="equipmentName" class="form-label">Ekipman Adı</label>
                            <input type="text" class="form-control" id="equipmentName" required>
                        </div>
                        <div class="mb-3">
                            <label for="equipmentSerial" class="form-label">Seri No</label>
                            <input type="text" class="form-control" id="equipmentSerial" required>
                        </div>
                        <div class="mb-3">
                            <label for="equipmentType" class="form-label">Türü</label>
                            <select class="form-select" id="equipmentType" required>
                                <option>Elektronik</option>
                                <option>El Aleti</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="equipmentStatus" class="form-label">Durumu</label>
                            <select class="form-select" id="equipmentStatus" required>
                                <option>Aktif</option>
                                <option>Bakımda</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="equipmentDate" class="form-label">Kayıt Tarihi</label>
                            <input type="date" class="form-control" id="equipmentDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="equipmentLocation" class="form-label">Lokasyon</label>
                            <input type="text" class="form-control" id="equipmentLocation" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Detay Modalı -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Ekipman Detayı</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Ekipman Adı:</strong> <span id="detailName">-</span></p>
                    <p><strong>Seri No:</strong> <span id="detailSerial">-</span></p>
                    <p><strong>Türü:</strong> <span id="detailType">-</span></p>
                    <p><strong>Durumu:</strong> <span id="detailStatus">-</span></p>
                    <p><strong>Kayıt Tarihi:</strong> <span id="detailDate">-</span></p>
                    <p><strong>Lokasyon:</strong> <span id="detailLocation">-</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
<script>
// --- ÖRNEK VERİ ---
let equipmentData = [
    {id:1, name:'Dizüstü Bilgisayar', serial:'SN123456', type:'Elektronik', status:'Aktif', date:'2024-04-01', location:'Ofis 1', icon:'laptop'},
    {id:2, name:'Projeksiyon Cihazı', serial:'SN654321', type:'Elektronik', status:'Bakımda', date:'2024-03-15', location:'Toplantı Salonu', icon:'video'},
    {id:3, name:'Matkap', serial:'SN987654', type:'El Aleti', status:'Aktif', date:'2024-02-20', location:'Depo', icon:'tools'},
    {id:4, name:'Yazıcı', serial:'SN111222', type:'Elektronik', status:'Aktif', date:'2024-01-10', location:'Ofis 2', icon:'print'},
    {id:5, name:'Tornavida', serial:'SN333444', type:'El Aleti', status:'Bakımda', date:'2024-03-01', location:'Depo', icon:'screwdriver'},
    {id:6, name:'Tablet', serial:'SN555666', type:'Elektronik', status:'Aktif', date:'2024-04-10', location:'Ofis 3', icon:'tablet-alt'},
    {id:7, name:'Projeksiyon Perdesi', serial:'SN777888', type:'Elektronik', status:'Aktif', date:'2024-02-28', location:'Toplantı Salonu', icon:'border-all'},
    {id:8, name:'Çekiç', serial:'SN999000', type:'El Aleti', status:'Aktif', date:'2024-01-25', location:'Depo', icon:'hammer'},
];
let currentPage = 1;
const pageSize = 5;
let sortField = null;
let sortAsc = true;
let selectedIds = new Set();

function renderTable() {
    let filtered = equipmentData.filter(row => {
        let search = document.getElementById('searchInput').value.toLowerCase();
        let type = document.getElementById('typeFilter').value;
        let status = document.getElementById('statusFilter').value;
        let match = true;
        if(search && !row.name.toLowerCase().includes(search)) match = false;
        if(type && row.type !== type) match = false;
        if(status && row.status !== status) match = false;
        return match;
    });
    // Sıralama
    if(sortField) {
        filtered.sort((a,b) => {
            let va = a[sortField], vb = b[sortField];
            if(sortField==='date') { va = va.replace(/-/g,''); vb = vb.replace(/-/g,''); }
            if(va < vb) return sortAsc ? -1 : 1;
            if(va > vb) return sortAsc ? 1 : -1;
            return 0;
        });
    }
    // Pagination
    let total = filtered.length;
    let start = (currentPage-1)*pageSize;
    let end = start+pageSize;
    let pageRows = filtered.slice(start,end);
    // Tablo
    let tbody = document.querySelector('#equipmentTable tbody');
    tbody.innerHTML = '';
    pageRows.forEach(row => {
        let tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="checkbox-cell"><input type="checkbox" class="row-select" data-id="${row.id}" ${selectedIds.has(row.id)?'checked':''}></td>
            <td class="editable" data-field="name"><span class="editable-text">${row.name}</span></td>
            <td class="editable" data-field="serial"><span class="editable-text">${row.serial}</span></td>
            <td class="editable" data-field="type"><span class="editable-text">${row.type}</span></td>
            <td class="editable" data-field="status"><span class="editable-text">${row.status}</span> ${row.status==='Aktif'?'<span class="badge bg-success ms-1">Aktif</span>':'<span class="badge bg-warning text-dark ms-1">Bakımda</span>'}</td>
            <td class="editable" data-field="date"><span class="editable-text">${row.date}</span></td>
            <td class="editable" data-field="location"><span class="editable-text">${row.location}</span></td>
            <td>
                <button class="btn btn-sm btn-info me-1 detail-btn" data-id="${row.id}" title="Detay"><i class="fas fa-info-circle"></i></button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}" title="Sil"><i class="fas fa-trash-alt"></i></button>
            </td>
        `;
        tbody.appendChild(tr);
    });
    // Boş satır ekle (tablo yüksekliği sabit kalsın)
    let minRows = pageSize;
    for(let i=pageRows.length; i<minRows; i++) {
        let tr = document.createElement('tr');
        tr.innerHTML = '<td colspan="8" style="height:48px; background:#fcfcfc; border:none;"></td>';
        tbody.appendChild(tr);
    }
    // Sayaçlar
    document.getElementById('totalCount').innerText = filtered.length;
    document.getElementById('activeCount').innerText = filtered.filter(r=>r.status==='Aktif').length;
    document.getElementById('maintenanceCount').innerText = filtered.filter(r=>r.status==='Bakımda').length;
    // Pagination
    renderPagination(Math.ceil(total/pageSize));
    // Checkbox event
    document.querySelectorAll('.row-select').forEach(cb => {
        cb.addEventListener('change', function() {
            let id = parseInt(this.getAttribute('data-id'));
            if(this.checked) selectedIds.add(id); else selectedIds.delete(id);
            document.getElementById('deleteSelectedBtn').disabled = selectedIds.size===0;
        });
    });
    // Checkbox hücresine tıklama ile checkbox'ı tetikle
    document.querySelectorAll('td.checkbox-cell').forEach(td => {
        td.addEventListener('click', function(e) {
            // Eğer doğrudan checkbox'a tıklandıysa tekrar tetikleme
            if(e.target.tagName.toLowerCase() === 'input') return;
            let cb = td.querySelector('input.row-select');
            cb.checked = !cb.checked;
            cb.dispatchEvent(new Event('change', {bubbles:true}));
        });
    });
    // Satır içi düzenleme
    document.querySelectorAll('#equipmentTable .editable').forEach(cell => {
        cell.addEventListener('dblclick', function(e) {
            if(cell.querySelector('input,select')) return;
            let field = cell.getAttribute('data-field');
            let text = cell.querySelector('.editable-text').innerText;
            let input;
            if(field==='type') {
                input = document.createElement('select');
                ['Elektronik','El Aleti'].forEach(opt => {
                    let o = document.createElement('option'); o.value=opt; o.text=opt; if(opt===text) o.selected=true; input.appendChild(o);
                });
            } else if(field==='status') {
                input = document.createElement('select');
                ['Aktif','Bakımda'].forEach(opt => {
                    let o = document.createElement('option'); o.value=opt; o.text=opt; if(opt===text) o.selected=true; input.appendChild(o);
                });
            } else if(field==='date') {
                input = document.createElement('input'); input.type='date'; input.value=text; input.className='form-control form-control-sm d-inline w-auto';
            } else {
                input = document.createElement('input'); input.type='text'; input.value=text; input.className='form-control form-control-sm d-inline w-auto';
            }
            input.style.maxWidth = '150px';
            let oldHtml = cell.innerHTML;
            cell.innerHTML = '';
            cell.appendChild(input);
            input.focus();
            // Kaydet fonksiyonu
            function saveEdit() {
                let val = input.value;
                let id = getRowId(cell);
                let eq = equipmentData.find(r=>r.id===id);
                eq[field] = val;
                renderTable();
            }
            // Vazgeç fonksiyonu
            function cancelEdit() {
                cell.innerHTML = oldHtml;
            }
            input.addEventListener('keydown', function(ev) {
                if(ev.key==='Enter') saveEdit();
                if(ev.key==='Escape') cancelEdit();
            });
            input.addEventListener('blur', function() {
                saveEdit();
            });
        });
    });
    // Detay modalı
    document.querySelectorAll('.detail-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let id = parseInt(this.getAttribute('data-id'));
            let eq = equipmentData.find(r=>r.id===id);
            document.getElementById('detailName').innerText = eq.name;
            document.getElementById('detailSerial').innerText = eq.serial;
            document.getElementById('detailType').innerText = eq.type;
            document.getElementById('detailStatus').innerText = eq.status;
            document.getElementById('detailDate').innerText = eq.date;
            document.getElementById('detailLocation').innerText = eq.location;
        });
    });
    // Satır silme
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let id = parseInt(this.getAttribute('data-id'));
            equipmentData = equipmentData.filter(r=>r.id!==id);
            selectedIds.delete(id);
            renderTable();
        });
    });
    // Select all
    document.getElementById('selectAll').checked = pageRows.every(r=>selectedIds.has(r.id));
    document.getElementById('selectAll').indeterminate = pageRows.some(r=>selectedIds.has(r.id)) && !pageRows.every(r=>selectedIds.has(r.id));
}
function getRowId(cell) {
    return parseInt(cell.parentElement.querySelector('.row-select').getAttribute('data-id'));
}
function renderPagination(pageCount) {
    let pag = document.getElementById('pagination');
    pag.innerHTML = '';
    if(pageCount<=1) return;
    for(let i=1;i<=pageCount;i++) {
        let li = document.createElement('li');
        li.className = 'page-item'+(i===currentPage?' active':'');
        let a = document.createElement('a');
        a.className = 'page-link';
        a.href = '#';
        a.innerText = i;
        a.onclick = function(e) { e.preventDefault(); currentPage=i; renderTable(); };
        li.appendChild(a);
        pag.appendChild(li);
    }
}
// Sıralama
Array.from(document.querySelectorAll('.sortable')).forEach(th => {
    th.addEventListener('click', function() {
        let field = th.getAttribute('data-sort');
        if(sortField===field) sortAsc=!sortAsc; else { sortField=field; sortAsc=true; }
        renderTable();
    });
});
// Filtreler
['searchInput','typeFilter','statusFilter'].forEach(id => {
    document.getElementById(id).addEventListener('input', function(){ currentPage=1; renderTable(); });
    document.getElementById(id).addEventListener('change', function(){ currentPage=1; renderTable(); });
});
// Toplu silme
 document.getElementById('deleteSelectedBtn').addEventListener('click', function() {
    equipmentData = equipmentData.filter(r=>!selectedIds.has(r.id));
    selectedIds.clear();
    renderTable();
 });
// Select all
 document.getElementById('selectAll').addEventListener('change', function() {
    let check = this.checked;
    let filtered = equipmentData.filter(row => {
        let search = document.getElementById('searchInput').value.toLowerCase();
        let type = document.getElementById('typeFilter').value;
        let status = document.getElementById('statusFilter').value;
        let match = true;
        if(search && !row.name.toLowerCase().includes(search)) match = false;
        if(type && row.type !== type) match = false;
        if(status && row.status !== status) match = false;
        return match;
    });
    let start = (currentPage-1)*pageSize;
    let end = start+pageSize;
    let pageRows = filtered.slice(start,end);
    pageRows.forEach(r=>{ if(check) selectedIds.add(r.id); else selectedIds.delete(r.id); });
    document.getElementById('deleteSelectedBtn').disabled = selectedIds.size===0;
    renderTable();
 });
// CSV Export
 document.getElementById('exportCsvBtn').addEventListener('click', function() {
    let csv = 'Ekipman Adı,Seri No,Türü,Durumu,Kayıt Tarihi,Lokasyon\n';
    equipmentData.forEach(r=>{
        csv += `${r.name},${r.serial},${r.type},${r.status},${r.date},${r.location}\n`;
    });
    let blob = new Blob([csv], {type:'text/csv'});
    let url = URL.createObjectURL(blob);
    let a = document.createElement('a');
    a.href = url; a.download = 'ekipmanlar.csv'; a.click();
    URL.revokeObjectURL(url);
 });
// Ekipman ekle
 document.getElementById('addEquipmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let newId = Math.max(...equipmentData.map(r=>r.id))+1;
    let icon = 'cubes';
    let type = document.getElementById('equipmentType').value;
    if(type==='Elektronik') icon='laptop'; else if(type==='El Aleti') icon='tools';
    equipmentData.push({
        id:newId,
        name:document.getElementById('equipmentName').value,
        serial:document.getElementById('equipmentSerial').value,
        type:type,
        status:document.getElementById('equipmentStatus').value,
        date:document.getElementById('equipmentDate').value,
        location:document.getElementById('equipmentLocation').value,
        icon:icon
    });
    document.getElementById('addEquipmentForm').reset();
    var modal = bootstrap.Modal.getInstance(document.getElementById('addEquipmentModal'));
    modal.hide();
    renderTable();
 });
// İlk render
renderTable();
// Avatar ve hover için stil
var style = document.createElement('style');
style.innerHTML = `
.avatar { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; font-size:1.1rem; border-radius:50%; box-shadow:0 2px 8px #e3e3e3; transition:transform .2s; }
tr:hover { background:#f8f9fa !important; }
.editable { cursor:pointer; transition:background .2s; }
.editable:active, .editable:focus-within { background:#e9ecef !important; }
.table thead th { user-select:none; cursor:pointer; }
.table thead th .fa-sort { opacity:.5; }
.table thead th.sorted { color:#0d6efd; }
.btn { transition:box-shadow .2s, background .2s; }
.btn:active { box-shadow:0 0 0 0.2rem #0d6efd33; }
.pagination .page-item.active .page-link { background:#0d6efd; border-color:#0d6efd; }
`;
document.head.appendChild(style);
</script>
<style>
/* Sticky pagination bar */
.card .sticky-pagination {
    position: sticky;
    bottom: 0;
    background: #fff;
    z-index: 10;
    box-shadow: 0 -2px 8px #e3e3e3;
    border-top: 1px solid #e9ecef;
    padding-top: 8px;
    padding-bottom: 8px;
}
@media (max-width: 768px) {
    .card .sticky-pagination { padding-left: 0; padding-right: 0; }
}
</style>
@endpush
@endsection