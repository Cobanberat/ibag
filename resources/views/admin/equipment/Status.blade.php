@extends('layouts.admin')
@section('content')

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center">
                <img src="{{ asset('images/ibag-logo.svg') }}" alt="İBAG Logo" style="width: 24px; height: 24px; margin-right: 8px;">
                <i class="fa fa-home me-1"></i> Ana Sayfa
            </a>
        </li>
        <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ekipman Durumu</li>
    </ol>
</nav>

<style>
  .equipment-card {
    overflow: hidden;
    border-radius: 1rem;
    box-shadow: 0 2px 16px rgba(0,0,0,0.08), 0 1.5px 4px rgba(0,0,0,0.04);
    transition: box-shadow 0.3s, transform 0.3s;
    background: #fff;
    opacity: 1;
    transform: none;
  }
  
  .equipment-card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.08);
    transform: translateY(-2px);
  }
  
  .equipment-img-box {
    position: relative;
    height: 170px;
    background: #f5f5f5;
    overflow: hidden;
  }
  
  .equipment-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s cubic-bezier(.4,0,.2,1), filter 0.4s;
    filter: brightness(0.92) saturate(1.1);
  }
  
  .equipment-card:hover .equipment-img {
    transform: scale(1.08);
    filter: brightness(1) saturate(1.2) blur(1px);
  }
  
  .equipment-img-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: linear-gradient(180deg,rgba(0,0,0,0.18) 60%,rgba(0,0,0,0.45) 100%);
    z-index: 1;
  }
  
  .equipment-title-bar {
    position: absolute;
    left: 0; right: 0; top: 0;
    z-index: 2;
    padding: 0.75rem 1rem;
    background: rgba(255,255,255,0.95);
    border-bottom-left-radius: 1rem;
    border-bottom-right-radius: 1rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(0,0,0,0.05);
  }
  
  .equipment-title-bar .fw-bold {
    font-size: 1rem;
    color: #212529;
    text-shadow: 0 1px 2px rgba(255,255,255,0.8);
    text-align: center;
    width: 100%;
  }
  
  .equipment-status {
    font-size: 0.85rem;
    font-weight: 600;
    padding: 0.4em 0.8em;
    border-radius: 1.5em;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 2px solid transparent;
    text-align: center;
    min-width: 120px;
    width: 100%;
  }
  
  .equipment-status.bg-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%) !important;
    color: #212529 !important;
    border-color: #ffb300;
  }
  
  .equipment-status.bg-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
    color: #fff !important;
    border-color: #c82333;
  }
  
  .equipment-status.bg-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%) !important;
    color: #fff !important;
    border-color: #5a6268;
  }
  
  .equipment-placeholder {
    width: 100%; 
    height: 100%;
    display: flex; 
    align-items: center; 
    justify-content: center;
    font-size: 3rem;
    color: #6c757d;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%);
    border-radius: 0.5rem;
  }
  
  .equipment-card .card-body {
    padding: 1.25rem;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
  }
  
  .equipment-card .card-footer {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-top: 1px solid rgba(0,0,0,0.05);
    padding: 1rem;
  }
  
  .equipment-card .btn {
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
  }
  
  .equipment-card .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  }
  
  .equipment-card .badge {
    font-size: 0.75rem;
    padding: 0.4em 0.7em;
    border-radius: 1rem;
  }
  
  .equipment-card .badge.bg-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
  }
  
  .equipment-card .badge.bg-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
  }
  
  /* Boş durum mesajı için ortalama */
  .equipment-empty-state {
    display: flex !important;
    flex-direction: column !important;
    justify-content: center !important;
    align-items: center !important;
    text-align: center !important;
    min-height: 300px;
    width: 100%;
  }
</style>
<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
      <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a></li>
      <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Ekipman Durumu' }}</li>
  </ol>
</nav>
<!-- Durum Özeti -->
<div class="row mb-4">
  <div class="col-md-4">
    <div class="card bg-opacity-10 border-warning border-0 shadow-sm" style="background: linear-gradient(135deg, #2d3e52 0%, #556d89 100%); border: 1px solid rgba(79, 172, 254, 0.2); border-radius: 1rem;">
      <div class="card-body text-center">
        <i class="fas fa-tools fa-3x text-warning mb-2"></i>
        <h4 class="text-warning fw-bold">{{ $stats['bakim'] ?? 0 }}</h4>
        <p class="text-white mb-0">Bakım Gerektiren</p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card bg-danger bg-opacity-10 border-danger border-0 shadow-sm"style="background: linear-gradient(135deg, #2d3e52 0%, #556d89 100%); border: 1px solid rgba(79, 172, 254, 0.2); border-radius: 1rem;">
      <div class="card-body text-center">
        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-2"></i>
        <h4 class="text-danger fw-bold">{{ $stats['arizali'] ?? 0 }}</h4>
        <p class="text-white mb-0">Arızalı</p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card bg-info bg-opacity-10 border-info border-0 shadow-sm" style="background: linear-gradient(135deg, #2d3e52 0%, #556d89 100%); border: 1px solid rgba(79, 172, 254, 0.2); border-radius: 1rem;">
      <div class="card-body text-center">
        <i class="fas fa-clipboard-list fa-3x text-info mb-2"></i>
        <h4 class="text-info fw-bold">{{ $stats['toplam'] ?? 0 }}</h4>
        <p class="text-white mb-0">Toplam</p>
      </div>
    </div>
  </div>
</div>

<!-- Filtreleme Barı -->
<div class="card shadow-sm mb-4">
  <div class="card-body">
    <form class="row g-2 align-items-center" id="filterForm">
      <div class="col-md-3">
        <input type="text" class="form-control" id="searchInput" placeholder="Ekipman Ara..." value="{{ request('search') }}">
      </div>
      <div class="col-md-2">
        <select class="form-select" id="categoryFilter">
          <option value="">Tüm Kategoriler</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <select class="form-select" id="statusFilter">
          <option value="">Tüm Durumlar</option>
          <option value="Bakım Gerekiyor" {{ request('status') == 'Bakım Gerekiyor' ? 'selected' : '' }}>Bakım Gerekiyor</option>
          <option value="Arızalı" {{ request('status') == 'Arızalı' ? 'selected' : '' }}>Arızalı</option>
        </select>
      </div>
      <div class="col-md-3 text-end">
        <button type="button" class="btn btn-outline-secondary me-2" id="clearFilters">
          <i class="fas fa-times"></i> Temizle
        </button>
        <button type="button" class="btn" style="background: #2d3e52" id="filterBtn">
          <i class="fas fa-search"></i> Filtrele
        </button>
      </div>
    </form>
  </div>
</div>



<!-- Modern Ekipman Kartları Grid -->
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="equipmentGrid">
  @forelse($equipmentStocks as $stock)
    <div class="col">
      <div class="equipment-card card h-100 border-0 position-relative">
        <div class="equipment-img-box">
          @php
            $imagePath = null;
            $imageFound = false;
            $needsMaintenance = $stock->hasActiveMaintenance();
            $hasFault = $stock->hasActiveFault();
            
            if($stock->equipment && $stock->equipment->images && $stock->equipment->images->count() > 0) {
                $imagePath = 'storage/' . $stock->equipment->images->first()->path;
                $imageFound = true;
            }
            // Sonra stock'un photo_path'ini kontrol et
            elseif($stock->photo_path) {
                $imagePath = 'storage/' . $stock->photo_path;
                $imageFound = true;
            }
          @endphp
          @if($imageFound)
            <img src="{{ asset($imagePath) }}" class="equipment-img" alt="{{ $stock->equipment->name ?? 'Ekipman' }}" onerror="this.style.display='none';this.parentNode.querySelector('.equipment-placeholder').style.display='flex';">
          @else
            <div class="equipment-placeholder" style="display:flex;">
              @if($needsMaintenance)
                <i class="fas fa-tools text-warning"></i>
              @elseif($hasFault)
                <i class="fas fa-exclamation-triangle text-danger"></i>
              @else
                <i class="fas fa-cogs text-secondary"></i>
              @endif
            </div>
          @endif
        <div class="equipment-img-overlay"></div>
        <div class="equipment-title-bar">
            <span class="fw-bold">{{ $stock->equipment->name ?? 'Bilinmeyen Ekipman' }}</span>
            @if($needsMaintenance)
              <span class="equipment-status bg-warning text-dark">Bakım Gerekiyor</span>
            @elseif($hasFault)
              <span class="equipment-status bg-danger text-white">Arızalı</span>
            @else
              <span class="equipment-status bg-secondary text-white">{{ $stock->equipment_status }}</span>
            @endif
          </div>
          @if($imageFound)
            <div class="equipment-placeholder" style="display:none;">
              @if($needsMaintenance)
                <i class="fas fa-tools text-warning"></i>
              @elseif($hasFault)
                <i class="fas fa-exclamation-triangle text-danger"></i>
              @else
                <i class="fas fa-cogs text-secondary"></i>
              @endif
            </div>
          @endif
        </div>
        <div class="card-body">
        <div class="mb-2">
          @if($stock->equipment && $stock->equipment->category)
            <span class="badge bg-info text-dark me-1">{{ $stock->equipment->category->name }}</span>
          @endif
          @if($stock->equipment && $stock->equipment->individual_tracking)
            <span class="badge bg-primary">Ayrı Takip</span>
          @endif
        </div>
        <div class="mb-1 small">
          <i class="fas fa-code me-1"></i> Kod: {{ $stock->code ?? '-' }}
        </div>
        <div class="mb-1 small">
          <i class="fas fa-calendar-alt me-1"></i> Güncellenme: {{ $stock->updated_at ? $stock->updated_at->format('d.m.Y') : '-' }}
        </div>
        @if($stock->note)
          <div class="mb-1 small">
            <i class="fas fa-sticky-note me-1"></i> Not: {{ Str::limit($stock->note, 50) }}
          </div>
        @endif
      </div>
        <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center gap-2">
          <button class="btn btn-outline-primary btn-sm detay-gor-btn" data-eid="{{ $stock->id }}">
            <i class="fas fa-eye"></i> Detay
          </button>
          @if($stock->hasActiveFault())
            @if(!$stock->equipment || !$stock->equipment->individual_tracking)
              @if($stock->faults->where('type', 'arıza')->whereIn('status', ['Beklemede', 'İşlemde'])->count() > 0)
                <button class="btn btn-success btn-sm ariza-giderildi-btn" data-eid="{{ $stock->id }}" data-equipment="{{ $stock->equipment->name ?? 'Bilinmeyen Ekipman' }}">
                  <i class="fas fa-check"></i> Arıza Giderildi
                </button>
              @endif
              @if($stock->faults->where('type', 'bakım')->whereIn('status', ['Beklemede', 'İşlemde'])->count() > 0)
                <button class="btn btn-success btn-sm bakim-giderildi-btn" data-eid="{{ $stock->id }}" data-equipment="{{ $stock->equipment->name ?? 'Bilinmeyen Ekipman' }}">
                  <i class="fas fa-tools"></i> Bakım Giderildi
                </button>
              @endif
            @endif
          @endif
        </div>
      </div>
    </div>
  @empty
  <div class="equipment-empty-state">
    <div class="col-12">
      <div class="text-center py-5">
        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">Bakım veya Arıza Gerektiren Ekipman Bulunamadı</h5>
        <p class="text-muted">Tüm ekipmanlar sorunsuz durumda görünüyor.</p>
      </div>
    </div>
  </div>
  @endforelse
</div>
<!-- Pagination Bar -->
@if($equipmentStocks->hasPages())
<nav aria-label="Ekipman Sayfalama" class="mt-3">
  <div class="d-flex justify-content-between align-items-center">
    <div class="text-muted">
      Toplam {{ $equipmentStocks->total() }} kayıttan {{ $equipmentStocks->firstItem() ?? 0 }}-{{ $equipmentStocks->lastItem() ?? 0 }} arası gösteriliyor
    </div>
    <div class="mb-0">
      {{ $equipmentStocks->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
  </div>
</nav>
@endif

<!-- Bakım Gerektiren Ekipmanlar Modal -->
<div class="modal fade" id="bakimEkipmanModal" tabindex="-1" aria-labelledby="bakimEkipmanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-warning bg-opacity-25">
        <h5 class="modal-title" id="bakimEkipmanModalLabel"><i class="fas fa-tools text-warning me-2"></i>Bakım Gerektiren Ekipmanlar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <div class="row row-cols-1 row-cols-md-2 g-3">
          <div class="col-12 text-center">
            <p class="text-muted">Bu modal dinamik olarak doldurulacak.</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>

<!-- Genel Detay Modalı -->
<div class="modal fade" id="detayModal" tabindex="-1" aria-labelledby="detayModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" id="detayModalHeader">
        <h5 class="modal-title" id="detayModalLabel">
          <i class="fas fa-info-circle me-2"></i>Ekipman Detayları
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body" id="detayModalBody">
        <!-- Detay içeriği buraya Blade ile eklenecek -->
        <div class="text-center py-4">
          <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
          <p class="text-muted">Ekipman detayları gösterilecek</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>


<!-- Servis Talep Modalı (örnek) -->

<!-- Arıza Giderildi Modalı -->
<div class="modal fade" id="arizaGiderildiModal" tabindex="-1" aria-labelledby="arizaGiderildiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success bg-opacity-25">
        <h5 class="modal-title" id="arizaGiderildiModalLabel"><i class="fas fa-check-circle text-success me-2"></i>Arıza Giderildi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <form id="arizaGiderildiForm" action="{{ route('admin.fault.resolve', ['id' => 'PLACEHOLDER']) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="arizaEquipmentStockId" name="equipment_stock_id">
          <div class="mb-3">
            <label for="giderilmeTarihi" class="form-label">Giderilme Tarihi</label>
            <input type="date" class="form-control" id="giderilmeTarihi" name="resolved_date" required>
          </div>
          <div class="mb-3">
            <label for="resolutionNote" class="form-label">Çözüm Açıklaması</label>
            <textarea class="form-control" id="resolutionNote" name="resolution_note" rows="3" placeholder="Arıza nasıl giderildi?" required></textarea>
          </div>
          <div class="mb-3">
            <label for="ekipmanFoto" class="form-label">Ekipman Fotoğrafı</label>
            <input type="file" class="form-control" id="ekipmanFoto" name="resolved_photo" accept="image/*" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
        <button type="submit" form="arizaGiderildiForm" class="btn btn-success">Kaydet</button>
      </div>
    </div>
  </div>
</div>

<!-- Bakım Giderildi Modalı -->
<div class="modal fade" id="bakimGiderildiModal" tabindex="-1" aria-labelledby="bakimGiderildiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success bg-opacity-25">
        <h5 class="modal-title" id="bakimGiderildiModalLabel"><i class="fas fa-tools text-success me-2"></i>Bakım Giderildi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>  
      <div class="modal-body">
        <form id="bakimGiderildiForm" action="{{ route('admin.fault.resolve', ['id' => 'PLACEHOLDER']) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="bakimEquipmentStockId" name="equipment_stock_id">
          <div class="mb-3">
            <label for="bakimGiderilmeTarihi" class="form-label">Bakım Tarihi</label>
            <input type="date" class="form-control" id="bakimGiderilmeTarihi" name="resolved_date" required>
          </div>
          <div class="mb-3">
            <label for="bakimResolutionNote" class="form-label">Bakım Açıklaması</label>
            <textarea class="form-control" id="bakimResolutionNote" name="resolution_note" rows="3" placeholder="Bakım nasıl yapıldı?" required></textarea>
          </div>
          <div class="mb-3">
            <label for="bakimEkipmanFoto" class="form-label">Ekipman Fotoğrafı</label>
            <input type="file" class="form-control" id="bakimEkipmanFoto" name="resolved_photo" accept="image/*" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
        <button type="submit" form="bakimGiderildiForm" class="btn btn-success">Kaydet</button>
      </div>
    </div>
  </div>
</div>

<!-- Talep Oluştur Modalı -->
<div class="modal fade" id="talepOlusturModal" tabindex="-1" aria-labelledby="talepOlusturModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning bg-opacity-25">
        <h5 class="modal-title" id="talepOlusturModalLabel"><i class="fas fa-plus-circle text-warning me-2"></i>Talep Oluştur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <form id="talepOlusturForm">
          <div class="mb-3">
            <label class="form-label">Ekipman</label>
            <input type="text" class="form-control" id="talepEkipmanAdi" name="talepEkipmanAdi" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Talep Tipi</label>
            <select class="form-select" id="talepTipi" name="talepTipi" required>
              <option value="">Seçiniz</option>
              <option value="Bakım">Bakım Talebi</option>
              <option value="Arıza">Arıza Talebi</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Açıklama</label>
            <textarea class="form-control" id="talepAciklama" name="talepAciklama" rows="3" required></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
        <button type="submit" form="talepOlusturForm" class="btn btn-warning">Gönder</button>
      </div>
    </div>
  </div>
</div>

@vite(['resources/js/equipmentStatus.js'])
@endsection