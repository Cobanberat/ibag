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
            <a href="{{ route('stock.create') }}" class="btn btn-success shadow-sm">
                <i class="fas fa-plus"></i> Ekipman Ekle
            </a>
            <button class="btn btn-outline-primary ms-2 shadow-sm" id="exportCsvBtn">
                <i class="fas fa-file-csv"></i> CSV Aktar
            </button>
            <button class="btn btn-outline-danger ms-2 shadow-sm" id="deleteSelectedBtn" disabled>
                <i class="fas fa-trash"></i> Seçiliyi Sil
            </button>
        </div>
    </div>
    <div class="row mb-4 g-2">
        <div class="col-md-3">
            <input type="text" class="form-control w-auto" placeholder="Ara..." id="searchInput" style="max-width:180px;">
        </div>
        <div class="col-md-3">
            <select class="form-select w-auto" id="typeFilter" style="max-width:180px;">
                <option value="">Tüm Ürün Cinsleri</option>
                <option>2.5 KW Benzinli Jeneratör</option>
                <option>3.5 KW Benzinli Jeneratör</option>
                <option>4.4 KW Benzinli Jeneratör</option>
                <option>7.5 KW Dizel Jeneratör</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control w-auto" placeholder="Marka..." id="brandFilter" style="max-width:140px;">
        </div>
        <div class="col-md-3">
            <select class="form-select w-auto" id="statusFilter" style="max-width:140px;">
                <option value="">Tüm Durumlar</option>
                <option>Sıfır</option>
                <option>Açık</option>
            </select>
        </div>
    </div>
    <div class="card shadow-lg border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="equipmentTable" style="min-height:400px;">
                    <thead class="table-light">
                        <tr>
                            <th>Sıra</th>
                            <th>Ürün Cinsi</th>
                            <th>Marka</th>
                            <th>Model</th>
                            <th>Beden</th>
                            <th>Özellik</th>
                            <th>Adet</th>
                            <th>Durum</th>
                            <th>Tarih</th>
                            <th>Not</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Satırlar JS ile doldurulacak -->
                    </tbody>
                </table>
            </div>
            <nav class="mt-3 sticky-pagination p-2">
                <ul class="pagination justify-content-end mb-0" id="pagination">
                    <!-- Pagination JS ile doldurulacak -->
                </ul>
            </nav>
        </div>
    </div>
    <!-- Detay Modalı -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Ekipman Detayı</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Sıra:</strong> <span id="detailSno">-</span></p>
                    <p><strong>Ürün Cinsi:</strong> <span id="detailType">-</span></p>
                    <p><strong>Marka:</strong> <span id="detailBrand">-</span></p>
                    <p><strong>Model:</strong> <span id="detailModel">-</span></p>
                    <p><strong>Beden:</strong> <span id="detailSize">-</span></p>
                    <p><strong>Özellik:</strong> <span id="detailFeature">-</span></p>
                    <p><strong>Adet:</strong> <span id="detailCount">-</span></p>
                    <p><strong>Durum:</strong> <span id="detailStatus">-</span></p>
                    <p><strong>Tarih:</strong> <span id="detailDate">-</span></p>
                    <p><strong>Not:</strong> <span id="detailNote">-</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
<script>
let equipmentData = [
  {SNO: 1, URUN_CINSI: "2.5 KW Benzinli Jeneratör", MARKA: "TOPTICER", MODEL: "TG 3700S", BEDEN: "Küçük", OZELLIK: "2.5 KW Benzinli", ADET: 4, DURUM: "Sıfır", TARIH: "2025-02-09", NOT: "İlk listeden 1 eksik"},
  {SNO: 2, URUN_CINSI: "3.5 KW Benzinli Jeneratör", MARKA: "FULL", MODEL: "FGL 3500-LE", BEDEN: "Orta", OZELLIK: "3.5 KW Benzinli", ADET: 1, DURUM: "Sıfır", TARIH: "2025-02-09", NOT: ""},
  {SNO: 3, URUN_CINSI: "4.4 KW Benzinli Jeneratör", MARKA: "POWER FULL", MODEL: "HH3305-C", BEDEN: "Orta", OZELLIK: "4.4 KW Benzinli", ADET: 1, DURUM: "Sıfır", TARIH: "2025-02-09", NOT: ""},
  {SNO: 4, URUN_CINSI: "7.5 KW Dizel Jeneratör", MARKA: "KAMA", MODEL: "KDK10000", BEDEN: "Büyük", OZELLIK: "7.5 KW Dizel", ADET: 1, DURUM: "Sıfır", TARIH: "2025-02-09", NOT: ""}
];
let currentPage = 1;
const pageSize = 5;
function renderTable() {
    let filtered = equipmentData.filter(row => {
        let search = document.getElementById('searchInput').value.toLowerCase();
        let type = document.getElementById('typeFilter').value;
        let brand = document.getElementById('brandFilter').value.toLowerCase();
        let status = document.getElementById('statusFilter').value;
        let match = true;
        if(search && !(
            row.URUN_CINSI.toLowerCase().includes(search) ||
            row.MARKA.toLowerCase().includes(search) ||
            row.MODEL.toLowerCase().includes(search) ||
            row.OZELLIK.toLowerCase().includes(search) ||
            row.NOT.toLowerCase().includes(search)
        )) match = false;
        if(type && row.URUN_CINSI !== type) match = false;
        if(brand && !row.MARKA.toLowerCase().includes(brand)) match = false;
        if(status && row.DURUM !== status) match = false;
        return match;
    });
    let total = filtered.length;
    let start = (currentPage-1)*pageSize;
    let end = start+pageSize;
    let pageRows = filtered.slice(start,end);
    let tbody = document.querySelector('#equipmentTable tbody');
    tbody.innerHTML = '';
    pageRows.forEach(row => {
        let tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${row.SNO}</td>
            <td>${row.URUN_CINSI}</td>
            <td>${row.MARKA}</td>
            <td>${row.MODEL}</td>
            <td>${row.BEDEN}</td>
            <td>${row.OZELLIK}</td>
            <td>${row.ADET}</td>
            <td>${row.DURUM}</td>
            <td>${row.TARIH}</td>
            <td>${row.NOT}</td>
            <td>
                <button class="btn btn-sm btn-info me-1 detail-btn" data-sno="${row.SNO}" title="Detay"><i class="fas fa-info-circle"></i></button>
                <button class="btn btn-sm btn-danger delete-btn" data-sno="${row.SNO}" title="Sil"><i class="fas fa-trash-alt"></i></button>
            </td>
        `;
        tbody.appendChild(tr);
    });
    let minRows = pageSize;
    for(let i=pageRows.length; i<minRows; i++) {
        let tr = document.createElement('tr');
        tr.innerHTML = '<td colspan="11" style="height:48px; background:#fcfcfc; border:none;"></td>';
        tbody.appendChild(tr);
    }
    renderPagination(Math.ceil(total/pageSize));
    document.querySelectorAll('.detail-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let sno = parseInt(this.getAttribute('data-sno'));
            let eq = equipmentData.find(r=>r.SNO===sno);
            document.getElementById('detailSno').innerText = eq.SNO;
            document.getElementById('detailType').innerText = eq.URUN_CINSI;
            document.getElementById('detailBrand').innerText = eq.MARKA;
            document.getElementById('detailModel').innerText = eq.MODEL;
            document.getElementById('detailSize').innerText = eq.BEDEN;
            document.getElementById('detailFeature').innerText = eq.OZELLIK;
            document.getElementById('detailCount').innerText = eq.ADET;
            document.getElementById('detailStatus').innerText = eq.DURUM;
            document.getElementById('detailDate').innerText = eq.TARIH;
            document.getElementById('detailNote').innerText = eq.NOT;
            new bootstrap.Modal(document.getElementById('detailModal')).show();
        });
    });
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let sno = parseInt(this.getAttribute('data-sno'));
            equipmentData = equipmentData.filter(r=>r.SNO!==sno);
            renderTable();
        });
    });
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
['searchInput','typeFilter','brandFilter','statusFilter'].forEach(id => {
    document.getElementById(id).addEventListener('input', function(){ currentPage=1; renderTable(); });
    document.getElementById(id).addEventListener('change', function(){ currentPage=1; renderTable(); });
});
renderTable();
</script>
<style>
/* features.blade.php'den modern görünüm */
.filter-bar {
    background: #fff;
    border-radius: 0.75rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    padding: 1rem 1.2rem;
    margin-bottom: 1.2rem;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
}
.filter-bar select,
.filter-bar input {
    min-width: 120px;
    border-radius: 0.5rem;
}
.filter-bar button {
    border-radius: 0.5rem;
}
.filter-bar select {
    background: linear-gradient(90deg, #f6f8fa 60%, #e9ecef 100%);
    border: 1.5px solid #d1d5db;
    color: #333;
    font-weight: 500;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
    padding: 0.45em 2.2em 0.45em 1em;
    appearance: none;
    position: relative;
    transition: border-color 0.2s, box-shadow 0.2s;
    background-image:
        url('data:image/svg+xml;utf8,<svg fill="%230d6efd" height="20" viewBox="0 0 20 20" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M7.293 7.293a1 1 0 011.414 0L10 8.586l1.293-1.293a1 1 0 111.414 1.414l-2 2a1 1 0 01-1.414 0l-2-2a1 1 0 010-1.414z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 0.8em center;
    background-size: 1.2em;
}
.filter-bar select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 2px #0d6efd22;
    outline: none;
}
.filter-bar input[type="text"] {
    border: 1.5px solid #d1d5db;
    background: #f6f8fa;
    font-weight: 500;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.filter-bar input[type="text"]:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 2px #0d6efd22;
    outline: none;
}
.feature-table {
    border-radius: 1rem;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    margin-top: 0.5rem;
}
.feature-table thead th {
    background: #f6f8fa;
    font-weight: 600;
    border-bottom: 2px solid #e9ecef;
    vertical-align: middle;
}
.feature-table tbody tr:hover {
    background: #f1f3f7;
    transition: background 0.2s;
}
.feature-table td[contenteditable] {
    cursor: pointer;
    background: #f8fafc;
    transition: background 0.2s;
}
.feature-table td[contenteditable]:focus {
    outline: 2px solid #0d6efd;
    background: #e7f1ff;
}
.badge {
    font-size: 0.98em;
    border-radius: 0.5rem;
    padding: 0.45em 0.9em;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
}
.btn {
    border-radius: 0.5rem !important;
    font-weight: 500;
    letter-spacing: 0.01em;
}
.btn-info {
    color: #fff;
    background: linear-gradient(90deg, #36b3f6 0%, #007bff 100%);
    border: none;
}
.btn-info:hover {
    background: linear-gradient(90deg, #007bff 0%, #36b3f6 100%);
}
.pagination {
    justify-content: flex-end;
    margin-top: 1.2rem;
}
.pagination .page-link {
    border-radius: 0.5rem !important;
    margin: 0 0.15em;
    color: #0d6efd;
    font-weight: 500;
}
.pagination .active .page-link {
    background: linear-gradient(90deg, #0d6efd 0%, #36b3f6 100%);
    color: #fff;
    border: none;
}
.info-box {
    background: #e7f1ff;
    border-left: 5px solid #0d6efd;
    border-radius: 0.75rem;
    padding: 1.2rem 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    display: flex;
    align-items: center;
    gap: 1rem;
}
.info-box i {
    font-size: 2.2rem;
    color: #0d6efd;
}
.summary-cards {
    display: flex;
    gap: 1.2rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}
.summary-card {
    background: #fff;
    border-radius: 0.75rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    padding: 1.2rem 2rem 1.2rem 1.2rem;
    min-width: 200px;
    flex: 1 1 200px;
    display: flex;
    align-items: center;
    gap: 1rem;
}
.summary-card .icon {
    font-size: 2rem;
    color: #0d6efd;
    background: #e7f1ff;
    border-radius: 50%;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.summary-card .value {
    font-size: 1.6rem;
    font-weight: 700;
    color: #222;
}
.summary-card .label {
    font-size: 1rem;
    color: #666;
}
.bulk-actions-panel {
    background: #f6f8fa;
    border-radius: 0.75rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    padding: 0.8rem 1.2rem;
    margin-bottom: 1.2rem;
    display: flex;
    gap: 0.7rem;
    align-items: center;
    flex-wrap: wrap;
}
.bulk-actions-panel .btn {
    min-width: 120px;
}
</style>
@endpush
@endsection