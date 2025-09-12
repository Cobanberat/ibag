@extends('layouts.admin')
@section('content')
@vite(['resources/css/fault.css'])
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a></li>
        <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Arıza Bildirimi' }}</li>
    </ol>
</nav>

<div class="container mt-4">
  <!-- Debug Bilgisi -->
  <div class="row">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Yeni Arıza/Bakım Bildirimi</h5>
        </div>
        <div class="card-body">
          <!-- Success Alert -->
          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="fas fa-check-circle me-2"></i>
              <strong>Başarılı!</strong> {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
          
          @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-triangle me-2"></i>
              <strong>Hata!</strong> {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
          
          @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-triangle me-2"></i>
              <strong>Hata!</strong> Lütfen aşağıdaki hataları düzeltin:
              <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
          
          <form id="faultForm" action="{{ route('admin.fault.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="equipment_stock_id" class="form-label">Ekipman</label>
                  <select class="form-select" id="equipment_stock_id" name="equipment_stock_id" required>
                    <option value="">Seçiniz...</option>
                    @if($equipmentStocks && $equipmentStocks->count() > 0)
                      @foreach($equipmentStocks as $stock)
                        <option value="{{ $stock->id }}" data-category="{{ $stock->equipment->category->name ?? '' }}">
                          {{ $stock->equipment->name ?? 'Bilinmeyen' }} 
                          @if($stock->equipment && $stock->equipment->category)
                            ({{ $stock->equipment->category->name }})
                          @endif
                          @if($stock->code)
                            - {{ $stock->code }}
                          @endif
                        </option>
                      @endforeach
                    @else
                      <option value="" disabled>Kullanılabilir ekipman bulunamadı</option>
                    @endif
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="type" class="form-label">Bildirim Tipi</label>
                  <select class="form-select" id="type" name="type" required>
                    <option value="">Seçiniz...</option>
                    <option value="arıza">Arıza</option>
                    <option value="bakım">Bakım</option>
                    <option value="diğer">Diğer</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="priority" class="form-label">Öncelik</label>
                  <select class="form-select" id="priority" name="priority" required>
                    <option value="">Seçiniz...</option>
                    <option value="normal">Normal</option>
                    <option value="yüksek">Yüksek</option>
                    <option value="acil">Acil</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="reported_date" class="form-label">Tespit Tarihi</label>
                  <input type="date" class="form-control" id="reported_date" name="reported_date" value="{{ date('Y-m-d') }}" required>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label for="description" class="form-label">Açıklama</label>
              <textarea class="form-control" id="description" name="description" rows="4" placeholder="Arıza/bakım detaylarını yazınız..." required></textarea>
            </div>

            <div class="mb-3">
              <label for="photo" class="form-label">Fotoğraf / Dosya</label>
              <input type="file" class="form-control" id="photo" name="photo" accept="image/*,application/pdf">
              <div class="form-text">Arıza/bakım durumunu gösteren fotoğraf ekleyebilirsiniz</div>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-paper-plane me-2"></i>Bildirim Gönder
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
          <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Bilgi</h6>
        </div>
        <div class="card-body">
          <div class="alert alert-info">
            <h6><i class="fas fa-lightbulb me-2"></i>Nasıl Kullanılır?</h6>
            <ul class="mb-0 small">
              <li>Ekipman seçin ve bildirim tipini belirleyin</li>
              <li>Öncelik seviyesini seçin</li>
              <li>Detaylı açıklama yazın</li>
              <li>Varsa fotoğraf ekleyin</li>
              <li>Bildirimi gönderin</li>
            </ul>
          </div>
          
          <div class="alert alert-warning">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Önemli</h6>
            <p class="mb-0 small">Arıza bildirimi yapıldığında ekipman otomatik olarak "Arızalı" durumuna geçer. Bakım bildirimi yapıldığında "Bakım Gerekiyor" durumuna geçer.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@vite(['resources/js/fault.js'])
@endsection