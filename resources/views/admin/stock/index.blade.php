@extends('layouts.admin')
@section('content')
@vite('resources/css/stock.css')

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a></li>
        <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Stok' }}</li>
    </ol>
</nav>
   
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
        <input type="text" class="form-control form-control-sm" id="filterSearch" style="width: 200px;" placeholder="Ürün ara...">
        <button class="btn btn-sm btn-outline-secondary" id="filterBtn"><i class="fas fa-filter"></i> Filtrele</button>
        <a href="{{ route('stock.create') }}" class="btn btn-add-product d-flex align-items-center gap-2">
            <i class="fas fa-plus"></i> Yeni Ekipman
        </a>
    </div>
    <!-- Ürün Ekle Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-slide-down">
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
                            <label class="form-label">İhtiyaç Adet</label>
                            <input type="number" class="form-control" name="critical" min="0" required>
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
        <div class="modal-dialog modal-dialog-centered modal-slide-down">
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
    <!-- Stok Girişi/Çıkışı Modalı -->
    <div class="modal fade" id="stockInOutModal" tabindex="-1" aria-labelledby="stockInOutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
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
    <!-- Stok Hareketleri Modalı -->
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
    <!-- Ürün Fotoğrafı Modalı -->
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
    <!-- Bootstrap JS -->
    <form>
        <div class="card mt-2 p-2" style="border-radius:1.2rem;box-shadow:0 4px 24px #0d6efd11;">
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
                <button class="btn btn-primary btn-sm" id="bulkActionBtn" disabled>
                    <i class="fas fa-cogs me-1"></i> Seçili Ekipmanlara İşlem Yap
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

@vite('resources/js/stock.js')
