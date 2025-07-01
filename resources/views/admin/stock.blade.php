@extends('layouts.admin')
@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a></li>
        <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Stok' }}</li>
    </ol>
</nav>
    <style>
        body, .bg-light { background: #f6f8fb !important; }
        .alert-warning {
            border-radius: 1rem;
            font-size: 1.08em;
            box-shadow: 0 2px 8px #ffc10722;
            margin-bottom: 1.7rem;
            background: linear-gradient(90deg, #fffbe6 60%, #fff3cd 100%);
            border: none;
        }
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
        .filter-bar .btn-add-product {
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
        .filter-bar .btn-add-product:hover, .filter-bar .btn-add-product:focus {
            background: linear-gradient(90deg, #36b3f6 0%, #0d6efd 100%);
            color: #fff;
            box-shadow: 0 4px 18px #0d6efd33;
            transform: translateY(-2px) scale(1.04);
            outline: none;
        }
        .filter-bar .btn-add-product i {
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
        .filter-bar .btn-add-product:hover i { background: #0d6efd; color: #fff; }
        .table {
            border-radius: 1.2rem;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 4px 24px #0d6efd11;
        }
        .table thead th {
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
        .table-success { background: #e6f9ed !important; }
        .table-warning { background: #fffbe6 !important; }
        .table-danger { background: #ffeaea !important; }
        .progress { background: #e9ecef; border-radius: 0.7rem; height: 10px; }
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
        .card-footer { background: #f6f8fa; border-top: none; border-radius: 0 0 1.2rem 1.2rem; }
        .collapse.bg-light { border-radius: 0.7rem; margin-bottom: 0.5rem; box-shadow: 0 1px 4px #0d6efd11; }
        .pagination .page-link { border-radius: 0.7rem !important; margin: 0 0.15em; color: #0d6efd; font-weight: 600; }
        .pagination .active .page-link { background: linear-gradient(90deg, #0d6efd 0%, #36b3f6 100%); color: #fff; border: none; }
        .modal-content { border-radius: 1.3rem; box-shadow: 0 4px 32px #0d6efd18; }
        .modal-header { background: linear-gradient(90deg, #0d6efd 60%, #36b3f6 100%); color: #fff; border-top-left-radius: 1.3rem; border-top-right-radius: 1.3rem; }
        .modal-title { font-weight: 700; font-size: 1.35em; letter-spacing: 0.01em; }
        .form-control:focus { border-color: #0d6efd; box-shadow: 0 0 0 2px #0d6efd22; }
        .modal-footer { background: #f6f8fa; border-bottom-left-radius: 1.3rem; border-bottom-right-radius: 1.3rem; }
        .table td, .table th { vertical-align: middle !important; }
        .editBtn, .deleteBtn, .stockInBtn, .stockOutBtn, .logBtn, .photoBtn { transition: transform 0.13s; }
        .editBtn:hover, .deleteBtn:hover, .stockInBtn:hover, .stockOutBtn:hover, .logBtn:hover, .photoBtn:hover { transform: scale(1.13); }
        .table td[contenteditable="true"] { background: #f8fafc; transition: background 0.2s; }
        .table td[contenteditable="true"]:focus { outline: 2px solid #0d6efd; background: #e7f1ff; }
        .collapse.bg-light { font-size: 1.08em; }
        .product-photo-thumb { width: 38px; height: 38px; object-fit: cover; border-radius: 0.5em; box-shadow: 0 1px 4px #0d6efd11; margin-right: 0.5em; }
    </style>
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        Kritik seviyenin altına düşen ürünler var! Lütfen stokları kontrol edin.
    </div>
    <div class="filter-bar mb-2">
        <select class="form-select form-select-sm" id="filterCategory" style="width: 150px;">
            <option value="">Kategori Seç</option>
            <option>Donanım</option>
            <option>Ağ</option>
        </select>
        <select class="form-select form-select-sm" id="filterStatus" style="width: 150px;">
            <option value="">Durum Seç</option>
            <option>Yeterli</option>
            <option>Az Stok</option>
            <option>Tükendi</option>
        </select>
        <select class="form-select form-select-sm" id="filterLocation" style="width: 150px;">
            <option value="">Konum Seç</option>
            <option>Depo A</option>
            <option>Depo B</option>
            <option>Depo C</option>
        </select>
        <input type="text" class="form-control form-control-sm" id="filterSearch" style="width: 200px;" placeholder="Ürün ara...">
        <button class="btn btn-sm btn-outline-secondary" id="filterBtn"><i class="fas fa-filter"></i> Filtrele</button>
        <button class="btn btn-sm btn-outline-success"><i class="fas fa-file-excel"></i> Excel</button>
        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-file-pdf"></i> PDF</button>
        <button type="button" class="btn btn-outline-primary ms-2" id="bulkCategoryBtn"><i class="fas fa-layer-group"></i> Toplu Kategori Değiştir</button>
        <button type="button" class="btn btn-outline-info ms-2" id="bulkLocationBtn"><i class="fas fa-map-marker-alt"></i> Toplu Konum Değiştir</button>
        <button type="button" class="btn btn-add-product ms-auto" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="fas fa-plus"></i> Yeni Ürün
        </button>
    </div>
    <!-- Ürün Ekle Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel"><i class="fas fa-plus me-2"></i>Yeni Ürün Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addProductForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Ürün Adı</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" name="category" required>
                                <option value="">Seçiniz</option>
                                <option>Donanım</option>
                                <option>Ağ</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Miktar</label>
                            <input type="number" class="form-control" name="quantity" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kritik Seviye</label>
                            <input type="number" class="form-control" name="critical" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konum</label>
                            <select class="form-select" name="location" required>
                                <option value="">Seçiniz</option>
                                <option>Depo A</option>
                                <option>Depo B</option>
                                <option>Depo C</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" name="desc" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ürün Fotoğrafı</label>
                            <input type="file" class="form-control" name="photo" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Toplu Kategori Değiştir Modal -->
    <div class="modal fade" id="bulkCategoryModal" tabindex="-1" aria-labelledby="bulkCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkCategoryModalLabel"><i class="fas fa-layer-group me-2"></i>Toplu Kategori Değiştir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bulkCategoryForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Yeni Kategori</label>
                            <select class="form-select" name="newCategory" required>
                                <option value="">Seçiniz</option>
                                <option>Donanım</option>
                                <option>Ağ</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Toplu Konum Değiştir Modal -->
    <div class="modal fade" id="bulkLocationModal" tabindex="-1" aria-labelledby="bulkLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkLocationModalLabel"><i class="fas fa-map-marker-alt me-2"></i>Toplu Konum Değiştir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bulkLocationForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Yeni Konum</label>
                            <select class="form-select" name="newLocation" required>
                                <option value="">Seçiniz</option>
                                <option>Depo A</option>
                                <option>Depo B</option>
                                <option>Depo C</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Hareketler Modal -->
    <div class="modal fade" id="logModal" tabindex="-1" aria-labelledby="logModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logModalLabel"><i class="fas fa-history me-2"></i>Stok Hareketleri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tarih</th>
                                <th>İşlem</th>
                                <th>Miktar</th>
                                <th>Açıklama</th>
                            </tr>
                        </thead>
                        <tbody id="logTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Stok Girişi/Çıkışı Modal -->
    <div class="modal fade" id="stockInOutModal" tabindex="-1" aria-labelledby="stockInOutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockInOutModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="stockInOutForm">
                    <div class="modal-body">
                        <input type="hidden" name="productId">
                        <input type="hidden" name="type">
                        <div class="mb-3">
                            <label class="form-label">Miktar</label>
                            <input type="number" class="form-control" name="amount" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" name="desc" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tarih</label>
                            <input type="date" class="form-control" name="date" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Ürün Fotoğrafı Modal -->
    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalLabel"><i class="fas fa-image me-2"></i>Ürün Fotoğrafı</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalPhoto" src="" alt="Ürün Fotoğrafı" style="max-width:100%;max-height:350px;border-radius:1em;box-shadow:0 2px 12px #0d6efd22;">
                </div>
            </div>
        </div>
    </div>
    <form>
        <div class="card mt-2" style="border-radius:1.2rem;box-shadow:0 4px 24px #0d6efd11;">
            <div class="card-body p-0">
                <table class="table table-hover table-striped mb-0" id="stockTable">
                    <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Ürün</th>
                            <th>Kategori</th>
                            <th>Miktar</th>
                            <th>Kritik Seviye</th>
                            <th>Stok Durumu</th>
                            <th>Konum</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody id="stockTableBody">
                        <!-- JS ile doldurulacak -->
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                <button class="btn btn-danger btn-sm" id="deleteSelected">
                    <i class="fas fa-trash-alt me-1"></i> Seçili Ekipmanları Sil
                </button>
                <nav aria-label="Sayfalama">
                    <ul class="pagination mb-0" id="pagination">
                        <!-- JS ile doldurulacak -->
                    </ul>
                </nav>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <!-- Flatpickr Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Demo ürün verisi
        let products = [
            {id:1, name:'Klavye', category:'Donanım', quantity:12, critical:5, location:'Depo A', desc:'Son bakımda tuşlar değiştirildi.', history:'2024-03-01 giriş, 2024-03-10 çıkış, 2024-03-15 bakım', status:'Yeterli', photo:'', log:[{date:'2024-03-01', type:'Giriş', amount:12, desc:'İlk stok'}]},
            {id:2, name:'Ethernet Kablosu', category:'Ağ', quantity:3, critical:5, location:'Depo B', desc:'Kablo başı değiştirildi.', history:'2024-02-20 giriş, 2024-03-05 çıkış', status:'Az Stok', photo:'', log:[{date:'2024-02-20', type:'Giriş', amount:5, desc:'Depo yenileme'},{date:'2024-03-05', type:'Çıkış', amount:2, desc:'Kullanım'}]},
            {id:3, name:'Monitör', category:'Donanım', quantity:0, critical:2, location:'Depo C', desc:'Panel arızası nedeniyle stokta yok.', history:'2024-01-10 giriş, 2024-02-01 çıkış', status:'Tükendi', photo:'', log:[{date:'2024-01-10', type:'Giriş', amount:2, desc:'Yeni monitör'},{date:'2024-02-01', type:'Çıkış', amount:2, desc:'Arıza'}]}
        ];
        let perPage = 5;
        let currentPage = 1;
        function getStatusClass(status) {
            if(status==='Yeterli') return 'table-success';
            if(status==='Az Stok') return 'table-warning';
            if(status==='Tükendi') return 'table-danger';
            return '';
        }
        function getBadge(status) {
            if(status==='Yeterli') return '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Yeterli</span>';
            if(status==='Az Stok') return '<span class="badge bg-warning"><i class="fas fa-exclamation-triangle"></i> Az Stok</span>';
            if(status==='Tükendi') return '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Tükendi</span>';
            return '';
        }
        function renderTable() {
            let filtered = products.filter(p => {
                let cat = document.getElementById('filterCategory').value;
                let stat = document.getElementById('filterStatus').value;
                let loc = document.getElementById('filterLocation').value;
                let search = document.getElementById('filterSearch').value.toLowerCase();
                return (!cat || p.category===cat) && (!stat || p.status===stat) && (!loc || p.location===loc) && (!search || p.name.toLowerCase().includes(search));
            });
            let total = filtered.length;
            let start = (currentPage-1)*perPage;
            let end = start+perPage;
            let pageData = filtered.slice(start,end);
            let tbody = '';
            pageData.forEach((p,i) => {
                let rowId = 'detail'+p.id;
                tbody += `<tr class="${getStatusClass(p.status)}">
                    <td><input type="checkbox" name="select[]" data-id="${p.id}"></td>
                    <td contenteditable="true">
                        ${p.photo ? `<img src="${p.photo}" class="product-photo-thumb" alt="Foto">` : ''}
                        <span class="fw-bold">${p.name}</span>
                    </td>
                    <td>${p.category}</td>
                    <td contenteditable="true">${p.quantity}</td>
                    <td contenteditable="true">${p.critical}</td>
                    <td><div class="progress" style="height: 10px;"><div class="progress-bar bg-${p.status==='Yeterli'?'success':p.status==='Az Stok'?'warning':'danger'}" style="width: ${Math.min(100,Math.round((p.quantity/(p.critical||1))*100))}%"></div></div></td>
                    <td>${p.location}</td>
                    <td>${getBadge(p.status)}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-success stockInBtn" data-id="${p.id}" data-type="Giriş"><i class="fas fa-plus"></i></button>
                        <button type="button" class="btn btn-sm btn-danger stockOutBtn" data-id="${p.id}" data-type="Çıkış"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-sm btn-secondary logBtn" data-id="${p.id}"><i class="fas fa-history"></i></button>
                        <button type="button" class="btn btn-sm btn-info photoBtn" data-id="${p.id}"><i class="fas fa-image"></i></button>
                        <button type="button" class="btn btn-sm btn-warning editBtn" data-id="${p.id}">Düzenle</button>
                        <button type="button" class="btn btn-sm btn-danger deleteBtn" data-id="${p.id}">Sil</button>
                    </td>
                </tr>
                <tr class="collapse bg-light" id="${rowId}">
                    <td colspan="9">
                        <strong>Ürün Geçmişi:</strong> ${p.history || '-'}<br>
                        <strong>Ek Açıklama:</strong> ${p.desc || '-'}
                    </td>
                </tr>`;
            });
            document.getElementById('stockTableBody').innerHTML = tbody;
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
        document.getElementById('filterCategory').onchange = document.getElementById('filterStatus').onchange = document.getElementById('filterLocation').onchange = function(){ currentPage=1; renderTable(); };
        document.getElementById('filterSearch').oninput = function(){ currentPage=1; renderTable(); };
        document.getElementById('addProductForm').onsubmit = function(e){
            e.preventDefault();
            let f = e.target;
            let newId = products.length ? Math.max(...products.map(p=>p.id))+1 : 1;
            let photo = '';
            if(f.photo.files && f.photo.files[0]) {
                photo = URL.createObjectURL(f.photo.files[0]);
            }
            products.push({
                id: newId,
                name: f.name.value,
                category: f.category.value,
                quantity: parseInt(f.quantity.value),
                critical: parseInt(f.critical.value),
                location: f.location.value,
                desc: f.desc.value,
                history: '-',
                status: (parseInt(f.quantity.value)===0?'Tükendi':parseInt(f.quantity.value)<=parseInt(f.critical.value)?'Az Stok':'Yeterli'),
                photo: photo,
                log: []
            });
            f.reset();
            var modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
            modal.hide();
            renderTable();
        };
        document.getElementById('stockTableBody').onclick = function(e) {
            const btn = e.target.closest('button');
            if (!btn) return;
            let id = btn.getAttribute('data-id');
            if(btn.classList.contains('deleteBtn')) {
                products = products.filter(p=>p.id!=id);
                renderTable();
            }
            if(btn.classList.contains('stockInBtn') || btn.classList.contains('stockOutBtn')) {
                let type = btn.getAttribute('data-type');
                let product = products.find(p=>p.id==id);
                document.getElementById('stockInOutModalLabel').innerText = type === 'Giriş' ? 'Stok Girişi' : 'Stok Çıkışı';
                let form = document.getElementById('stockInOutForm');
                form.productId.value = id;
                form.type.value = type;
                form.amount.value = '';
                form.desc.value = '';
                form.date.value = new Date().toISOString().slice(0,10);
                new bootstrap.Modal(document.getElementById('stockInOutModal')).show();
            }
            if(btn.classList.contains('logBtn')) {
                let product = products.find(p=>p.id==id);
                let logBody = '';
                (product.log||[]).forEach(l => {
                    logBody += `<tr><td>${l.date}</td><td>${l.type}</td><td>${l.amount}</td><td>${l.desc||''}</td></tr>`;
                });
                document.getElementById('logTableBody').innerHTML = logBody || '<tr><td colspan="4" class="text-center">Hareket yok</td></tr>';
                new bootstrap.Modal(document.getElementById('logModal')).show();
            }
            if(btn.classList.contains('photoBtn')) {
                let product = products.find(p=>p.id==id);
                document.getElementById('modalPhoto').src = product.photo || '';
                new bootstrap.Modal(document.getElementById('photoModal')).show();
            }
            if(btn.classList.contains('editBtn')) {
                // (Düzenle işlemi için kod eklenebilir)
            }
        };
        document.getElementById('stockInOutForm').onsubmit = function(e) {
            e.preventDefault();
            let f = e.target;
            let product = products.find(p=>p.id==f.productId.value);
            let amount = parseInt(f.amount.value);
            let type = f.type.value;
            if(type==='Giriş') product.quantity += amount;
            if(type==='Çıkış') product.quantity = Math.max(0, product.quantity-amount);
            product.status = (product.quantity===0?'Tükendi':product.quantity<=product.critical?'Az Stok':'Yeterli');
            product.log = product.log || [];
            product.log.unshift({date:f.date.value, type:type, amount:amount, desc:f.desc.value});
            var modal = bootstrap.Modal.getInstance(document.getElementById('stockInOutModal'));
            modal.hide();
            renderTable();
        };
        document.getElementById('deleteSelected').onclick = function(e) {
            e.preventDefault();
            let checked = Array.from(document.querySelectorAll('input[name="select[]"]:checked')).map(cb=>parseInt(cb.getAttribute('data-id')));
            products = products.filter(p=>!checked.includes(p.id));
            renderTable();
        };
        document.getElementById('selectAll').onchange = function() {
            document.querySelectorAll('input[name="select[]"]').forEach(cb => cb.checked = this.checked);
        };
        // Toplu Kategori/Konum Değiştir
        document.getElementById('bulkCategoryBtn').onclick = function(){
            new bootstrap.Modal(document.getElementById('bulkCategoryModal')).show();
        };
        document.getElementById('bulkLocationBtn').onclick = function(){
            new bootstrap.Modal(document.getElementById('bulkLocationModal')).show();
        };
        document.getElementById('bulkCategoryForm').onsubmit = function(e){
            e.preventDefault();
            let newCat = e.target.newCategory.value;
            let checked = Array.from(document.querySelectorAll('input[name="select[]"]:checked')).map(cb=>parseInt(cb.getAttribute('data-id')));
            products.forEach(p=>{if(checked.includes(p.id))p.category=newCat;});
            var modal = bootstrap.Modal.getInstance(document.getElementById('bulkCategoryModal'));
            modal.hide();
            renderTable();
        };
        document.getElementById('bulkLocationForm').onsubmit = function(e){
            e.preventDefault();
            let newLoc = e.target.newLocation.value;
            let checked = Array.from(document.querySelectorAll('input[name="select[]"]:checked')).map(cb=>parseInt(cb.getAttribute('data-id')));
            products.forEach(p=>{if(checked.includes(p.id))p.location=newLoc;});
            var modal = bootstrap.Modal.getInstance(document.getElementById('bulkLocationModal'));
            modal.hide();
            renderTable();
        };
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('#stockInOutForm input[name="date"]', {
                dateFormat: 'd.m.Y',
                locale: 'tr',
                maxDate: 'today',
                allowInput: true,
                monthSelectorType: 'dropdown',
                showMonths: 1
            });
        });
        renderTable();
    </script>
@endpush
