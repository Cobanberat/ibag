@extends('layouts.admin')
@section('content')
<style>
    body, .bg-light { background: #f6f8fb !important; }
    .category-card {
        border: none;
        border-radius: 1.2rem;
        box-shadow: 0 4px 24px #0d6efd11;
        background: #fff;
        margin-bottom: 1.5rem;
        padding: 1.2rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.2rem;
        transition: box-shadow 0.18s, transform 0.18s;
    }
    .category-card:hover {
        box-shadow: 0 8px 32px #0d6efd22;
        transform: translateY(-4px) scale(1.03);
    }
    .category-icon-box {
        width: 48px; height: 48px; border-radius: 1em; display: flex; align-items: center; justify-content: center;
        font-size: 1.7em; background: #f6f8fa; box-shadow: 0 1px 4px #0d6efd11; margin-right: 0.7em;
    }
    .category-color {
        width: 1.5em; height: 1.5em; border-radius: 50%; display: inline-block; margin-right: 0.5em; border: 2px solid #fff; box-shadow: 0 1px 4px #0d6efd11;
    }
    .category-title { font-size: 1.18em; font-weight: 600; }
    .category-desc { color: #666; font-size: 1em; }
    .category-actions .btn { margin-right: 0.3em; }
    .filter-bar {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px #0d6efd11;
        padding: 1.2rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.7rem;
        align-items: center;
    }
    .filter-bar select, .filter-bar input {
        min-width: 140px;
        border-radius: 0.7rem;
        font-size: 1.08em;
        background: #f8fafc;
        border: 1.5px solid #d1d5db;
        box-shadow: 0 1px 4px #0d6efd08;
    }
    .filter-bar input[type="text"]:focus, .filter-bar select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 2px #0d6efd22;
        outline: none;
    }
    .filter-bar button {
        border-radius: 0.7rem;
        font-size: 1.08em;
        font-weight: 500;
        transition: background 0.18s, box-shadow 0.18s, transform 0.12s;
    }
    .btn-add-category {
        background: linear-gradient(90deg, #0d6efd 0%, #36b3f6 100%);
        color: #fff;
        font-weight: 600;
        box-shadow: 0 2px 12px #0d6efd22;
        border-radius: 2em;
        padding: 0.65em 1.7em;
        font-size: 1.12em;
        letter-spacing: 0.01em;
        transition: background 0.18s, box-shadow 0.18s, transform 0.12s;
        position: relative;
        overflow: hidden;
    }
    .btn-add-category:hover, .btn-add-category:focus {
        background: linear-gradient(90deg, #36b3f6 0%, #0d6efd 100%);
        color: #fff;
        box-shadow: 0 4px 18px #0d6efd33;
        transform: translateY(-2px) scale(1.04);
        outline: none;
    }
    .btn-add-category i {
        font-size: 1.25em;
        margin-right: 0.5em;
        background: #fff;
        color: #0d6efd;
        border-radius: 50%;
        padding: 0.18em 0.22em 0.18em 0.22em;
        box-shadow: 0 1px 4px #0d6efd22;
        transition: background 0.18s, color 0.18s;
        display: inline-block;
    }
    .btn-add-category:hover i { background: #0d6efd; color: #fff; }
    .table {
        border-radius: 1.2rem;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 4px 24px #0d6efd11;
    }
    .table thead th {
        background: linear-gradient(90deg, #f6f8fa 60%, #e9ecef 100%);
        font-weight: 700;
        border-bottom: 2px solid #e9ecef;
        font-size: 1.08em;
    }
    .table-hover tbody tr:hover {
        background: #e7f1ff !important;
        transition: background 0.2s;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background: #f8fafc;
    }
    .table td, .table th { vertical-align: middle !important; }
    .badge {
        font-size: 1.05em;
        border-radius: 0.7rem;
        padding: 0.5em 1em;
        box-shadow: 0 1px 4px #0d6efd11;
        font-weight: 600;
    }
    .btn { border-radius: 0.7rem !important; font-weight: 500; letter-spacing: 0.01em; }
    .btn-info { color: #fff; background: linear-gradient(90deg, #36b3f6 0%, #007bff 100%); border: none; }
    .btn-info:hover { background: linear-gradient(90deg, #007bff 0%, #36b3f6 100%); }
    .btn-warning { color: #fff; background: linear-gradient(90deg, #ffc107 0%, #ff9800 100%); border: none; }
    .btn-warning:hover { background: linear-gradient(90deg, #ff9800 0%, #ffc107 100%); }
    .btn-danger { background: linear-gradient(90deg, #dc3545 0%, #ff6f6f 100%); border: none; color: #fff; }
    .btn-danger:hover { background: linear-gradient(90deg, #ff6f6f 0%, #dc3545 100%); color: #fff; }
    .pagination .page-link { border-radius: 0.7rem !important; margin: 0 0.15em; color: #0d6efd; font-weight: 600; }
    .pagination .active .page-link { background: linear-gradient(90deg, #0d6efd 0%, #36b3f6 100%); color: #fff; border: none; }
    .modal-content { border-radius: 1.2rem; box-shadow: 0 4px 32px #0d6efd18; }
    .modal-header { background: linear-gradient(90deg, #0d6efd 60%, #36b3f6 100%); color: #fff; border-top-left-radius: 1.2rem; border-top-right-radius: 1.2rem; }
    .modal-title { font-weight: 700; font-size: 1.25em; letter-spacing: 0.01em; }
    .form-control:focus { border-color: #0d6efd; box-shadow: 0 0 0 2px #0d6efd22; }
    .modal-footer { background: #f6f8fa; border-bottom-left-radius: 1.2rem; border-bottom-right-radius: 1.2rem; }
    @media (max-width: 768px) {
        .category-card { flex-direction: column; align-items: flex-start; gap: 0.7em; }
        .filter-bar { flex-direction: column; gap: 0.7em; }
    }
</style>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h3 class="fw-bold mb-0">Kategoriler</h3>
    <button class="btn btn-add-category" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="fas fa-plus"></i> Yeni Kategori
    </button>
</div>
<div class="filter-bar mb-2">
    <input type="text" class="form-control form-control-sm" id="categorySearch" style="width: 200px;" placeholder="Kategori ara...">
    <button class="btn btn-sm btn-outline-secondary" id="filterBtn"><i class="fas fa-search"></i> Filtrele</button>
    <select class="form-select form-select-sm" id="sortSelect" style="width: 150px;">
        <option value="">Sırala</option>
        <option value="most">En Çok Ürün</option>
        <option value="least">En Az Ürün</option>
        <option value="newest">En Yeni</option>
        <option value="oldest">En Eski</option>
    </select>
    <button class="btn btn-sm btn-outline-success"><i class="fas fa-file-excel"></i> Excel</button>
    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-file-pdf"></i> PDF</button>
    <button class="btn btn-danger btn-sm ms-auto" id="deleteSelected"><i class="fas fa-trash-alt me-1"></i> Seçili Kategorileri Sil</button>
</div>
<table class="table table-hover table-striped mb-0" id="categoryTable">
    <thead>
        <tr>
            <th><input type="checkbox" id="selectAll"></th>
            <th>Kategori</th>
            <th>Açıklama</th>
            <th>Ürün Sayısı</th>
            <th>Eklenme Tarihi</th>
            <th>Renk</th>
            <th>İkon</th>
            <th>İşlemler</th>
        </tr>
    </thead>
    <tbody id="categoryTableBody">
        <!-- JS ile doldurulacak -->
    </tbody>
</table>
<div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
    <nav aria-label="Sayfalama">
        <ul class="pagination mb-0" id="pagination">
            <!-- JS ile doldurulacak -->
        </ul>
    </nav>
</div>
<!-- Kategori Ekle Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCategoryModalLabel"><i class="fas fa-plus-circle me-2"></i>Yeni Kategori Ekle</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <form id="addCategoryForm">
          <div class="mb-3">
            <label for="categoryName" class="form-label">Kategori Adı</label>
            <input type="text" class="form-control" id="categoryName" required>
          </div>
          <div class="mb-3">
            <label for="categoryDesc" class="form-label">Açıklama</label>
            <textarea class="form-control" id="categoryDesc"></textarea>
          </div>
          <div class="mb-3">
            <label for="categoryColor" class="form-label">Renk</label>
            <input type="color" class="form-control form-control-color" id="categoryColor" value="#0d6efd" title="Kategori Rengi">
          </div>
          <div class="mb-3">
            <label for="categoryIcon" class="form-label">İkon</label>
            <select class="form-select" id="categoryIcon">
                <option value="fa-laptop">Laptop</option>
                <option value="fa-tools">Alet</option>
                <option value="fa-star">Yıldız</option>
                <option value="fa-cube">Küp</option>
                <option value="fa-bolt">Yıldırım</option>
                <option value="fa-cogs">Dişli</option>
                <option value="fa-box">Kutu</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
        <button type="button" class="btn btn-primary" id="saveCategoryBtn">Kaydet</button>
      </div>
    </div>
  </div>
</div>
<!-- Kategori Düzenle Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="editCategoryModalLabel"><i class="fas fa-edit me-2"></i>Kategori Düzenle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <form id="editCategoryForm">
          <input type="hidden" id="editCategoryId">
          <div class="mb-3">
            <label for="editCategoryName" class="form-label">Kategori Adı</label>
            <input type="text" class="form-control" id="editCategoryName" required>
          </div>
          <div class="mb-3">
            <label for="editCategoryDesc" class="form-label">Açıklama</label>
            <textarea class="form-control" id="editCategoryDesc"></textarea>
          </div>
          <div class="mb-3">
            <label for="editCategoryColor" class="form-label">Renk</label>
            <input type="color" class="form-control form-control-color" id="editCategoryColor" value="#0d6efd" title="Kategori Rengi">
          </div>
          <div class="mb-3">
            <label for="editCategoryIcon" class="form-label">İkon</label>
            <select class="form-select" id="editCategoryIcon">
                <option value="fa-laptop">Laptop</option>
                <option value="fa-tools">Alet</option>
                <option value="fa-star">Yıldız</option>
                <option value="fa-cube">Küp</option>
                <option value="fa-bolt">Yıldırım</option>
                <option value="fa-cogs">Dişli</option>
                <option value="fa-box">Kutu</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
        <button type="button" class="btn btn-warning" id="updateCategoryBtn">Güncelle</button>
      </div>
    </div>
  </div>
</div>
<!-- Kategori Detay Modal -->
<div class="modal fade" id="categoryDetailModal" tabindex="-1" aria-labelledby="categoryDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="categoryDetailModalLabel"><i class="fas fa-info-circle me-2"></i>Kategori Detayı</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <div id="categoryDetailContent"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script>
// Demo kategori verisi
let categories = [
    {id:1, name:'Elektronik', desc:'Elektronik cihazlar', color:'#0d6efd', icon:'fa-laptop', productCount:12, date:'2024-03-20', products:['Laptop','Monitör','Klavye','Mouse']},
    {id:2, name:'Donanım', desc:'Donanım ekipmanları', color:'#ffc107', icon:'fa-tools', productCount:7, date:'2024-03-18', products:['Kasa','Güç Kaynağı','Fan']},
    {id:3, name:'Aksesuar', desc:'Çeşitli aksesuarlar', color:'#36b3f6', icon:'fa-star', productCount:5, date:'2024-03-15', products:['Kılıf','Çanta']}
];
let perPage = 5;
let currentPage = 1;
function renderTable() {
    let search = document.getElementById('categorySearch').value.toLowerCase();
    let sort = document.getElementById('sortSelect').value;
    let filtered = categories.filter(c => !search || c.name.toLowerCase().includes(search));
    if(sort==='most') filtered.sort((a,b)=>b.productCount-a.productCount);
    if(sort==='least') filtered.sort((a,b)=>a.productCount-b.productCount);
    if(sort==='newest') filtered.sort((a,b)=>b.date.localeCompare(a.date));
    if(sort==='oldest') filtered.sort((a,b)=>a.date.localeCompare(b.date));
    let total = filtered.length;
    let start = (currentPage-1)*perPage;
    let end = start+perPage;
    let pageData = filtered.slice(start,end);
    let tbody = '';
    pageData.forEach(c => {
        tbody += `<tr>
            <td><input type="checkbox" class="rowCheck" data-id="${c.id}"></td>
            <td><span class="category-color" style="background:${c.color}"></span> <i class="fas ${c.icon} me-1 text-primary"></i> <span class="fw-bold">${c.name}</span></td>
            <td>${c.desc||''}</td>
            <td>${c.productCount}</td>
            <td>${c.date}</td>
            <td><span class="category-color" style="background:${c.color}"></span></td>
            <td><i class="fas ${c.icon} text-primary"></i></td>
            <td class="category-actions">
                <button type="button" class="btn btn-sm btn-info detailBtn" data-id="${c.id}"><i class="fas fa-info-circle"></i></button>
                <button type="button" class="btn btn-sm btn-warning editBtn" data-id="${c.id}"><i class="fas fa-edit"></i></button>
                <button type="button" class="btn btn-sm btn-danger deleteBtn" data-id="${c.id}"><i class="fas fa-trash"></i></button>
            </td>
        </tr>`;
    });
    document.getElementById('categoryTableBody').innerHTML = tbody;
    renderPagination(total);
}
function renderPagination(total) {
    let pageCount = Math.ceil(total/perPage);
    let pag = '';
    for(let i=1;i<=pageCount;i++) {
        pag += `<li class="page-item${i===currentPage?' active':''}"><a class="page-link" href="#" onclick="gotoPage(${i});return false;">${i}</a></li>`;
    }
    document.getElementById('pagination').innerHTML = pag;
}
window.gotoPage = function(page) { currentPage=page; renderTable(); }
document.getElementById('filterBtn').onclick = function(e){ e.preventDefault(); currentPage=1; renderTable(); };
document.getElementById('sortSelect').onchange = function(){ currentPage=1; renderTable(); };
document.getElementById('categorySearch').oninput = function(){ currentPage=1; renderTable(); };
document.getElementById('selectAll').onchange = function(){
    document.querySelectorAll('.rowCheck').forEach(cb => cb.checked = this.checked);
};
document.getElementById('deleteSelected').onclick = function(e){
    e.preventDefault();
    let checked = Array.from(document.querySelectorAll('.rowCheck:checked')).map(cb=>parseInt(cb.getAttribute('data-id')));
    categories = categories.filter(c=>!checked.includes(c.id));
    renderTable();
};
document.getElementById('categoryTableBody').onclick = function(e) {
    const btn = e.target.closest('button');
    if (!btn) return;
    let id = parseInt(btn.getAttribute('data-id'));
    if(btn.classList.contains('editBtn')) {
        let cat = categories.find(c=>c.id===id);
        document.getElementById('editCategoryId').value = cat.id;
        document.getElementById('editCategoryName').value = cat.name;
        document.getElementById('editCategoryDesc').value = cat.desc;
        document.getElementById('editCategoryColor').value = cat.color;
        document.getElementById('editCategoryIcon').value = cat.icon;
        new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
    }
    if(btn.classList.contains('deleteBtn')) {
        categories = categories.filter(c=>c.id!==id);
        renderTable();
    }
    if(btn.classList.contains('detailBtn')) {
        let cat = categories.find(c=>c.id===id);
        let html = `<div class='mb-2'><span class='category-color' style='background:${cat.color}'></span> <i class='fas ${cat.icon} me-1 text-primary'></i> <span class='fw-bold'>${cat.name}</span></div>`;
        html += `<div class='mb-2'><strong>Açıklama:</strong> ${cat.desc||'-'}</div>`;
        html += `<div class='mb-2'><strong>Ürün Sayısı:</strong> ${cat.productCount}</div>`;
        html += `<div class='mb-2'><strong>Eklenme Tarihi:</strong> ${cat.date}</div>`;
        html += `<div class='mb-2'><strong>Ürünler:</strong> ${(cat.products||[]).join(', ')||'-'}</div>`;
        document.getElementById('categoryDetailContent').innerHTML = html;
        new bootstrap.Modal(document.getElementById('categoryDetailModal')).show();
    }
};
document.getElementById('saveCategoryBtn').onclick = function(){
    let name = document.getElementById('categoryName').value.trim();
    let desc = document.getElementById('categoryDesc').value.trim();
    let color = document.getElementById('categoryColor').value;
    let icon = document.getElementById('categoryIcon').value;
    if(!name) return alert('Kategori adı zorunlu!');
    let newId = categories.length ? Math.max(...categories.map(c=>c.id))+1 : 1;
    categories.push({id:newId, name, desc, color, icon, productCount:0, date:new Date().toISOString().slice(0,10), products:[]});
    document.getElementById('addCategoryForm').reset();
    bootstrap.Modal.getInstance(document.getElementById('addCategoryModal')).hide();
    renderTable();
};
document.getElementById('updateCategoryBtn').onclick = function(){
    let id = parseInt(document.getElementById('editCategoryId').value);
    let name = document.getElementById('editCategoryName').value.trim();
    let desc = document.getElementById('editCategoryDesc').value.trim();
    let color = document.getElementById('editCategoryColor').value;
    let icon = document.getElementById('editCategoryIcon').value;
    let cat = categories.find(c=>c.id===id);
    if(cat) {
        cat.name = name;
        cat.desc = desc;
        cat.color = color;
        cat.icon = icon;
    }
    bootstrap.Modal.getInstance(document.getElementById('editCategoryModal')).hide();
    renderTable();
};
renderTable();
</script>
@endpush
