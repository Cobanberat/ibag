@extends('layouts.admin')
@section('content')
<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
      <li class="breadcrumb-item">
          <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center">
              <img src="{{ asset('images/ibag-logo.svg') }}" alt="İBAG Logo" style="width: 24px; height: 24px; margin-right: 8px;">
              <i class="fa fa-home me-1"></i> Ana Sayfa
          </a>
      </li>
      <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Kategoriler' }}</li>
  </ol>
</nav>

  @vite(['resources/css/category.css'])
<style>
.table th {
    position: sticky;
    top: 0;
    z-index: 10;
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}
</style>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h3 class="fw-bold mb-0">Kategoriler</h3>
</div>
<div class="row mb-4 g-2">
    <div class="col-12 col-md-6">
        <input type="text" class="form-control form-control-sm" id="categorySearch" placeholder="Kategori ara...">
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <button class="btn btn-sm btn-outline-secondary w-100" id="clearFilters">
            <i class="fas fa-times"></i> Temizle
        </button>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <button class="btn btn-add-category w-100" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fas fa-plus"></i> Yeni Kategori
        </button>
    </div>
</div>
<div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
    <table class="table table-hover mb-0" id="categoryTable" style="font-size:0.95em;">
        <thead class="table-light sticky-top">
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
        @forelse($categories as $category)
            <tr data-id="{{ $category->id }}" style="background-color: {{ $category->color ?? '#0d6efd' }}20;">
                <td><input type="checkbox" class="category-checkbox" value="{{ $category->id }}"></td>
                <td>
                    <b>{{ $category->name }}</b>
                    @if($category->icon)
                        <i class="fas {{ $category->icon }} ms-2"></i>
                    @endif
                </td>
                <td>{{ $category->description ?? '-' }}</td>
                <td>{{ $category->equipments_count ?? 0 }}</td>
                <td>{{ $category->created_at ? $category->created_at->format('d.m.Y') : '-' }}</td>
                <td>
                    <span style="background:{{ $category->color ?? '#0d6efd' }};width:18px;height:18px;display:inline-block;border-radius:4px;"></span>
                </td>
                <td class="category-actions">
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-sm" style="padding:0.32em 0.7em;border-radius:1.2em;" onclick="editCategory({{ $category->id }})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-sm" style="padding:0.32em 0.7em;border-radius:1.2em;" onclick="deleteCategory({{ $category->id }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-4">
                    <i class="fas fa-folder-open fa-2x text-muted mb-2"></i>
                    <p class="text-muted">Henüz kategori bulunmuyor</p>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2 p-2">
    <button class="btn btn-danger btn-sm p-2" id="deleteSelected"><i class="fas fa-trash-alt me-1"></i> Seçili Kategorileri Sil</button>
    <div class="d-flex align-items-center gap-3">
        <div class="text-muted small">
            Toplam {{ $categories->total() }} kategoriden {{ $categories->firstItem() ?? 0 }}-{{ $categories->lastItem() ?? 0 }} arası gösteriliyor
        </div>
        <nav aria-label="Sayfalama">
            <ul class="pagination mb-0" id="pagination">
                @if($categories->hasPages())
                    {{ $categories->links() }}
                @endif
            </ul>
        </nav>
    </div>
</div>
<!-- Kategori Ekle Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCategoryModalLabel"><i class="fas fa-plus-circle me-2"></i>Yeni Kategori Ekle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
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

