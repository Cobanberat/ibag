@extends('layouts.admin')
@section('content')
<style>
    .summary-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        margin-bottom: 1.5rem;
        padding: 1rem 1.2rem;
        display: flex;
        align-items: center;
        gap: 1.1rem;
        min-height: 70px;
        background: linear-gradient(90deg, #0d6efd 0%, #36b3f6 100%);
        color: #fff;
        transition: box-shadow 0.18s, transform 0.18s;
    }
    .summary-card.bg-warning {
        background: linear-gradient(90deg, #ffc107 0%, #ffe082 100%);
        color: #7a5c00;
    }
    .summary-card:hover {
        box-shadow: 0 6px 24px rgba(13,110,253,0.13);
        transform: translateY(-4px) scale(1.03);
    }
    .summary-card i {
        font-size: 1.7em;
        opacity: 0.8;
    }
    .summary-card .summary-value {
        font-size: 1.35em;
        font-weight: 600;
        margin-bottom: 0.1em;
        line-height: 1.1;
    }
    .summary-card .summary-label {
        font-size: 1em;
        color: #e3e3e3;
        font-weight: 500;
        line-height: 1.1;
    }
    .btn-add-category {
        display: flex;
        align-items: center;
        gap: 0.5em;
        background: linear-gradient(90deg, #0d6efd 0%, #36b3f6 100%);
        border: none;
        color: #fff;
        font-weight: 600;
        box-shadow: 0 2px 12px rgba(13,110,253,0.13);
        border-radius: 2em;
        padding: 0.55em 1.5em;
        font-size: 1.08em;
        letter-spacing: 0.01em;
        transition: background 0.18s, box-shadow 0.18s, transform 0.12s;
        position: relative;
        overflow: hidden;
    }
    .btn-add-category:hover, .btn-add-category:focus {
        background: linear-gradient(90deg, #36b3f6 0%, #0d6efd 100%);
        color: #fff;
        box-shadow: 0 4px 18px rgba(13,110,253,0.18);
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
        box-shadow: 0 1px 4px rgba(13,110,253,0.10);
        transition: background 0.18s, color 0.18s;
        display: inline-block;
    }
    .btn-add-category:hover i {
        background: #0d6efd;
        color: #fff;
    }
    .filter-bar {
        background: #fff;
        border-radius: 0.75rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        padding: 1rem 1.2rem;
        margin-bottom: 1.2rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
    }
    .filter-bar select, .filter-bar input {
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
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
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
    .table {
        border-radius: 1rem;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    .table thead th {
        background: #f6f8fa;
        font-weight: 600;
        border-bottom: 2px solid #e9ecef;
    }
    .table-hover tbody tr:hover {
        background: #f1f3f7;
        transition: background 0.2s;
    }
    .badge {
        font-size: 0.98em;
        border-radius: 0.5rem;
        padding: 0.45em 0.9em;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .category-label {
        display: inline-block;
        width: 1.2em;
        height: 1.2em;
        border-radius: 50%;
        margin-right: 0.5em;
        vertical-align: middle;
        border: 2px solid #fff;
        box-shadow: 0 1px 4px rgba(0,0,0,0.07);
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
    .collapse.bg-light {
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
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
    .modal-content {
        border-radius: 1rem;
    }
    .modal-header {
        border-bottom: none;
    }
    .modal-footer {
        border-top: none;
    }
</style>
<div class="row mb-4">
    <div class="col-md-3">
        <div class="summary-card mb-3">
            <i class="fas fa-list-alt fa-2x me-3"></i>
            <div>
                <div class="summary-value">8</div>
                <div class="summary-label">Toplam Kategori</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card bg-warning mb-3">
            <i class="fas fa-star fa-2x me-3"></i>
            <div>
                <div class="summary-value" style="color:#7a5c00">Elektronik</div>
                <div class="summary-label">En Fazla Ürünlü</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 d-flex align-items-center justify-content-end">
        <button class="btn btn-add-category" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fas fa-plus"></i> Yeni Kategori
        </button>
    </div>
</div>
<div class="filter-bar mb-2">
    <input type="text" class="form-control form-control-sm" style="width: 200px;" placeholder="Kategori ara...">
    <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-search"></i> Filtrele</button>
    <select class="form-select form-select-sm" style="width: 150px;">
        <option>Sırala</option>
        <option>En Çok Ürün</option>
        <option>En Az Ürün</option>
        <option>En Yeni</option>
        <option>En Eski</option>
    </select>
    <button class="btn btn-sm btn-outline-success"><i class="fas fa-file-excel"></i> Excel</button>
    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-file-pdf"></i> PDF</button>
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th><input type="checkbox"></th>
            <th>Kategori</th>
            <th>Açıklama</th>
            <th>Ürün Sayısı</th>
            <th>Eklenme Tarihi</th>
            <th>Renk</th>
            <th>İşlemler</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><input type="checkbox"></td>
            <td><i class="fas fa-laptop me-1 text-primary"></i> <span contenteditable="true">Elektronik</span></td>
            <td contenteditable="true">Elektronik cihazlar</td>
            <td>12</td>
            <td>2024-03-20</td>
            <td><span class="category-label" style="background:#0d6efd;"></span></td>
            <td>
                <button class="btn btn-sm btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#catDetail1">Detay</button>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editCategoryModal">Düzenle</button>
                <button class="btn btn-sm btn-danger">Sil</button>
            </td>
        </tr>
        <tr class="collapse bg-light" id="catDetail1">
            <td colspan="7">
                <strong>Kategori Açıklaması:</strong> Elektronik cihazlar ve aksesuarlar.<br>
                <strong>Ürünler:</strong> Laptop, Monitör, Klavye, Mouse, vb.
            </td>
        </tr>
        <tr>
            <td><input type="checkbox"></td>
            <td><i class="fas fa-tools me-1 text-secondary"></i> <span contenteditable="true">Donanım</span></td>
            <td contenteditable="true">Donanım ekipmanları</td>
            <td>7</td>
            <td>2024-03-18</td>
            <td><span class="category-label" style="background:#ffc107;"></span></td>
            <td>
                <button class="btn btn-sm btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#catDetail2">Detay</button>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editCategoryModal">Düzenle</button>
                <button class="btn btn-sm btn-danger">Sil</button>
            </td>
        </tr>
        <tr class="collapse bg-light" id="catDetail2">
            <td colspan="7">
                <strong>Kategori Açıklaması:</strong> Donanım ve yedek parça.<br>
                <strong>Ürünler:</strong> Kasa, Güç Kaynağı, Fan, vb.
            </td>
        </tr>
        <!-- Diğer kategoriler aynı şekilde eklenebilir -->
    </tbody>
</table>
<div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
    <button class="btn btn-danger btn-sm">
        <i class="fas fa-trash-alt me-1"></i> Seçili Kategorileri Sil
    </button>

    <nav aria-label="Sayfalama">
        <ul class="pagination mb-0">
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true" aria-label="Önceki">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li class="page-item active" aria-current="page">
                <span class="page-link">1</span>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">2</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">3</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Sonraki">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<!-- Kategori Ekle Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addCategoryModalLabel"><i class="fas fa-plus-circle me-2"></i>Yeni Kategori Ekle</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="categoryName" class="form-label">Kategori Adı</label>
            <input type="text" class="form-control" id="categoryName">
          </div>
          <div class="mb-3">
            <label for="categoryDesc" class="form-label">Açıklama</label>
            <textarea class="form-control" id="categoryDesc"></textarea>
          </div>
          <div class="mb-3">
            <label for="categoryColor" class="form-label">Renk</label>
            <input type="color" class="form-control form-control-color" id="categoryColor" value="#0d6efd" title="Kategori Rengi">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
        <button type="button" class="btn btn-primary">Kaydet</button>
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
        <form>
          <div class="mb-3">
            <label for="editCategoryName" class="form-label">Kategori Adı</label>
            <input type="text" class="form-control" id="editCategoryName" value="Elektronik">
          </div>
          <div class="mb-3">
            <label for="editCategoryDesc" class="form-label">Açıklama</label>
            <textarea class="form-control" id="editCategoryDesc">Elektronik cihazlar</textarea>
          </div>
          <div class="mb-3">
            <label for="editCategoryColor" class="form-label">Renk</label>
            <input type="color" class="form-control form-control-color" id="editCategoryColor" value="#0d6efd" title="Kategori Rengi">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
        <button type="button" class="btn btn-warning">Güncelle</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
@endpush
