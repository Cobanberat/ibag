@extends('layouts.admin')
@section('content')
  @vite(['resources/css/category.css'])
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h3 class="fw-bold mb-0">Kategoriler</h3>
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
    <button class="btn btn-add-category p-2 ms-auto" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="fas fa-plus"></i> Yeni Kategori
    </button>
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
            <th>İşlemler</th>
        </tr>
    </thead>
    <tbody id="categoryTableBody">
  

    </tbody>
</table>
<div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2 p-2">
    <button class="btn btn-danger btn-sm p-2" id="deleteSelected"><i class="fas fa-trash-alt me-1"></i> Seçili Kategorileri Sil</button>
    <nav aria-label="Sayfalama">
        <ul class="pagination mb-0" id="pagination">
            <!-- JS ile doldurulacak -->
        </ul>
    </nav>
</div>
<!-- Kategori Ekle Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
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
  <div class="modal-dialog modal-dialog-centered">
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
<div class="modal fade " id="categoryDetailModal" tabindex="-1" aria-labelledby="categoryDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
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
@vite(['resources/js/category.js'])

