@extends('layouts.admin')

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a></li>
            <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
            <li class="breadcrumb-item active" aria-current="page">Teslim İşlemleri</li>
        </ol>
    </nav>

    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="mb-0 fw-bold"><i class="fas fa-undo me-2 text-primary"></i>Teslim İşlemleri</h2>
                <p class="text-muted mt-2">Zimmet aldığınız ekipmanları teslim edin ve geçmiş zimmetlerinizi görüntüleyin</p>
            </div>
        </div>

        {{-- Sekmeler --}}
        <ul class="nav nav-tabs mb-4" id="assignmentTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="current-tab" data-bs-toggle="tab" data-bs-target="#current" type="button"
                    role="tab">
                    <i class="fas fa-boxes me-2"></i>Aldıklarım
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button"
                    role="tab">
                    <i class="fas fa-history me-2"></i>Geçmiş Zimmetler
                </button>
            </li>
        </ul>

        <div class="tab-content" id="assignmentTabsContent">
            {{-- Aldıklarım --}}
            <div class="tab-pane fade show active" id="current" role="tabpanel">
                <div class="row g-4">
                    @forelse($assignments->where('status', 0) as $index => $assignment)
                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-lg border-0 rounded-4 hover-card h-100">
                                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                                    <div>
                                        <h6 class="mb-0 fw-bold">Zimmet #{{ $assignment->id }}</h6>
                                        <small class="opacity-75">{{ $assignment->created_at ? $assignment->created_at->format('d.m.Y H:i') : '-' }}</small>
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-light text-primary" data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $assignment->id }}" title="Detay Görüntüle">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#returnModal{{ $assignment->id }}" title="Teslim Et">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-3">
                                        <p class="mb-2">
                                            <strong><i class="fas fa-sticky-note me-1"></i>Not:</strong> 
                                            {{ $assignment->note ?? 'Not bulunmuyor' }}
                                        </p>
                                        <p class="mb-0">
                                            <strong><i class="fas fa-cubes me-1"></i>Toplam Ekipman:</strong> 
                                            <span class="badge bg-primary">{{ $assignment->items->count() }}</span>
                                        </p>
                                    </div>
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $assignment->created_at ? $assignment->created_at->diffForHumans() : '-' }}
                                            </small>
                                            <span class="badge bg-warning">Aktif</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Detay Modal --}}
                        <div class="modal fade" id="detailModal{{ $assignment->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-gradient-primary text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-boxes me-2"></i>Zimmet #{{ $assignment->id }} Detay
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <p><strong>Oluşturulma Tarihi:</strong> {{ $assignment->created_at ? $assignment->created_at->format('d.m.Y H:i') : '-' }}</p>
                                                <p><strong>Not:</strong> {{ $assignment->note ?? 'Not bulunmuyor' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Toplam Ekipman:</strong> {{ $assignment->items->count() }}</p>
                                                <p><strong>Durum:</strong> <span class="badge bg-warning">Aktif</span></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <h6 class="mb-3"><i class="fas fa-cubes me-2"></i>Ekipman Listesi</h6>
                                        <div class="row g-3">
                                            @foreach ($assignment->items as $item)
                                                <div class="col-md-4 text-center">
                                                    <div class="border rounded p-2 h-100">
                                                    @if ($item->photo_path)
                                                        <img src="{{ asset('storage/' . $item->photo_path) }}"
                                                            alt="Ekipman"
                                                                class="img-fluid rounded mb-2 border border-secondary p-1" style="max-height: 120px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center mb-2"
                                                                style="height:120px;">
                                                                <i class="fas fa-image fa-2x"></i>
                                                            </div>
                                                        @endif
                                                        <div class="small">
                                                            <strong class="d-block">{{ $item->equipment?->name ?? 'Bilinmiyor' }}</strong>
                                                            @if($item->equipment && $item->equipment->individual_tracking)
                                                                <span class="badge bg-info">Ayrı Takip</span>
                                                                <br><small class="text-muted">Kod: {{ $item->code ?? '-' }}</small>
                                                    @else
                                                                <span class="badge bg-secondary">Toplu Takip</span>
                                                                <br><small class="text-muted">Miktar: {{ $item->quantity ?? 0 }}</small>
                                                    @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Teslim Et Modal --}}
                        <div class="modal fade" id="returnModal{{ $assignment->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-gradient-success text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-undo me-2"></i>Zimmet #{{ $assignment->id }} Teslim
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('admin.teslimAl', $assignment->id) }}" method="POST"
                                            enctype="multipart/form-data" id="returnForm{{ $assignment->id }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <strong>Bilgi:</strong> Her ekipman için teslim fotoğrafı yüklemek zorunludur.
                                            </div>
                                            <div class="row g-3">
                                            @foreach ($assignment->items as $key => $item)
                                                    <div class="col-md-6">
                                                        <div class="card border">
                                                            <div class="card-header bg-light">
                                                                <h6 class="mb-0">
                                                                    <i class="fas fa-cube me-2"></i>
                                                                    {{ $item->equipment->name ?? 'Bilinmiyor' }}
                                                                    @if($item->equipment && $item->equipment->individual_tracking)
                                                                        <span class="badge bg-info ms-2">Ayrı Takip</span>
                                                                    @else
                                                                        <span class="badge bg-secondary ms-2">Toplu Takip</span>
                                                                    @endif
                                                                </h6>
                                                            </div>
                                                            <div class="card-body">
                                                                @if ($item->photo_path)
                                                                    <div class="text-center mb-3">
                                                                        <img src="{{ asset('storage/' . $item->photo_path) }}"
                                                                            alt="Orijinal Ekipman"
                                                                            class="img-fluid rounded border" style="max-height: 100px;">
                                                                        <small class="d-block text-muted mt-1">Orijinal Ekipman</small>
                                                                    </div>
                                                                @endif
                                               
                                                    <div class="mb-3">
                                                                    <label class="form-label fw-bold">
                                                                        <i class="fas fa-camera me-1"></i>Teslim Fotoğrafı:
                                                                    </label>
                                                        <input type="file" name="return_photos[{{ $item->id }}]"
                                                                        class="form-control" accept="image/*" required>
                                                                    <small class="text-muted">Teslim edilen ekipmanın fotoğrafını çekin</small>
                                                                </div>
                                                                
                                                                <div class="alert alert-warning mb-3">
                                                                    <small>
                                                                        <i class="fas fa-info-circle me-1"></i>
                                                                        <strong>Aldığınız Miktar:</strong> {{ $item->quantity ?? 0 }} adet
                                                                    </small>
                                                    </div>
                                                                
                                                                @if ($item->equipment && $item->equipment->individual_tracking == 0)
                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">
                                                                            <i class="fas fa-exclamation-triangle me-1 text-warning"></i>Kullanılan/Kayıp Miktar:
                                                                        </label>
                                                        <input type="number" name="used_qty[{{ $item->id }}]"
                                                                            class="form-control" min="0" max="{{ $item->quantity }}"
                                                                            value="0" required>
                                                                        <small class="text-muted">
                                                                            <strong>0:</strong> Hiç kullanılmadı (tamamı geri dönüyor)<br>
                                                                            <strong>{{ $item->quantity }}:</strong> Tamamı kullanıldı/kayboldu<br>
                                                                            <strong>Geri dönen miktar:</strong> {{ $item->quantity }} - kullanılan miktar
                                                                        </small>
                                                                    </div>
                                                                @else
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">
                                                                            <i class="fas fa-exclamation-triangle me-1 text-warning"></i>Ekipman Durumu:
                                                                        </label>
                                                                        <select name="used_qty[{{ $item->id }}]" class="form-select" required>
                                                                            <option value="0">Sağlam - Geri Dönüyor</option>
                                                                            <option value="1">Hasarlı/Kayıp - Geri Dönmüyor</option>
                                                                        </select>
                                                                        <small class="text-muted">
                                                                            Ekipmanın mevcut durumunu seçin
                                                                        </small>
                                                    </div>
                                                    @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                            @endforeach
                                            </div>

                                            <div class="mb-3 mt-4">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Arıza/Hasar Notu:
                                                </label>
                                                <textarea name="damage_note" class="form-control" rows="3" 
                                                    placeholder="Ekipmanlarda herhangi bir arıza veya hasar varsa belirtin..."></textarea>
                                            </div>

                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="fas fa-times me-1"></i>İptal
                                                </button>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check me-1"></i>Teslim Et
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Henüz Zimmet Alınmamış</h5>
                                <p class="text-muted">Size teslim edilmiş ekipman bulunmamaktadır.</p>
                                <a href="{{ route('admin.zimmetAl') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>Zimmet Al
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Geçmiş Zimmetler --}}
            <div class="tab-pane fade" id="history" role="tabpanel">
                <div class="row g-4">
                    @forelse($assignments->where('status', 1) as $index => $assignment)
                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-lg border-0 rounded-4 hover-card h-100">
                                <div class="card-header bg-gradient-secondary text-white d-flex justify-content-between align-items-center rounded-top-4">
                                    <div>
                                        <h6 class="mb-0 fw-bold">Zimmet #{{ $assignment->id }}</h6>
                                        <small class="opacity-75">{{ $assignment->updated_at ? $assignment->updated_at->format('d.m.Y H:i') : '-' }}</small>
                                    </div>
                                    <button class="btn btn-sm btn-light text-secondary" data-bs-toggle="modal"
                                        data-bs-target="#detailModalHistory{{ $assignment->id }}" title="Detay Görüntüle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-3">
                                        <p class="mb-2">
                                            <strong><i class="fas fa-sticky-note me-1"></i>Not:</strong> 
                                            {{ $assignment->note ?? 'Not bulunmuyor' }}
                                        </p>
                                        <p class="mb-0">
                                            <strong><i class="fas fa-cubes me-1"></i>Toplam Ekipman:</strong> 
                                            <span class="badge bg-secondary">{{ $assignment->items->count() }}</span>
                                        </p>
                                    </div>
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $assignment->updated_at ? $assignment->updated_at->diffForHumans() : '-' }}
                                            </small>
                                            <span class="badge bg-success">Teslim Edildi</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Detay Modal --}}
                        <div class="modal fade" id="detailModalHistory{{ $assignment->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-gradient-secondary text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-boxes me-2"></i>Zimmet #{{ $assignment->id }} Detay
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <p><strong>Oluşturulma Tarihi:</strong> {{ $assignment->created_at ? $assignment->created_at->format('d.m.Y H:i') : '-' }}</p>
                                                <p><strong>Teslim Tarihi:</strong> {{ $assignment->updated_at ? $assignment->updated_at->format('d.m.Y H:i') : '-' }}</p>
                                                <p><strong>Not:</strong> {{ $assignment->note ?? 'Not bulunmuyor' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Toplam Ekipman:</strong> {{ $assignment->items->count() }}</p>
                                                <p><strong>Durum:</strong> <span class="badge bg-success">Teslim Edildi</span></p>
                                                @if($assignment->damage_note)
                                                    <p><strong>Arıza Notu:</strong> {{ $assignment->damage_note }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <hr>
                                        <h6 class="mb-3"><i class="fas fa-cubes me-2"></i>Ekipman Listesi</h6>
                                        <div class="row g-3">
                                            @foreach ($assignment->items as $item)
                                                <div class="col-md-4 text-center">
                                                    <div class="border rounded p-2 h-100 d-flex flex-column justify-content-between">
                                                        <div class="small mb-2">
                                                            <strong class="d-block">{{ $item->equipment?->name ?? 'Bilinmiyor' }}</strong>
                                                            @if($item->equipment && $item->equipment->individual_tracking)
                                                                <span class="badge bg-info">Ayrı Takip</span>
                                                                <br><small class="text-muted">
                                                                    Durum: {{ ($item->returned_quantity ?? 0) > 0 ? 'Sağlam' : 'Hasarlı/Kayıp' }}
                                                                </small>
                                                            @else
                                                                <span class="badge bg-secondary">Toplu Takip</span>
                                                                <br><small class="text-muted">
                                                                    Alınan: {{ $item->quantity ?? 0 }} adet<br>
                                                                    Geri dönen: {{ $item->returned_quantity ?? 0 }} adet<br>
                                                                    Kullanılan: {{ ($item->quantity ?? 0) - ($item->returned_quantity ?? 0) }} adet
                                                                </small>
                                                            @endif
                                                        </div>
                                                        <div class="mt-2">
                                                            <button type="button" class="btn btn-outline-primary btn-sm btn-toggle-photos" 
                                                                data-target="#photos-{{ $assignment->id }}-{{ $item->id }}">
                                                                <i class="fas fa-images me-1"></i>Fotoğrafları Gör
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div id="photos-{{ $assignment->id }}-{{ $item->id }}" class="p-2 border rounded" style="display:none;">
                                                        <div class="row g-3">
                                                            <div class="col-md-6 text-center">
                                                                <h6 class="fw-bold mb-2"><i class="fas fa-download me-1"></i>Alınırken</h6>
                                                                @if (!empty($item->photo_path))
                                                                    <img src="{{ asset('storage/' . $item->photo_path) }}" alt="Alınırken Fotoğraf" class="img-fluid rounded border" style="max-height: 300px; object-fit: contain;">
                                                                @else
                                                                    <div class="alert alert-warning small mb-0">Alınırken fotoğraf bulunmuyor.</div>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6 text-center">
                                                                <h6 class="fw-bold mb-2"><i class="fas fa-upload me-1"></i>Teslim Ederken</h6>
                                                                @if (!empty($item->return_photo_path))
                                                                    <img src="{{ asset('storage/' . $item->return_photo_path) }}" alt="Teslim Fotoğrafı" class="img-fluid rounded border" style="max-height: 300px; object-fit: contain;">
                                                                @else
                                                                    <div class="alert alert-warning small mb-0">Teslim fotoğrafı bulunmuyor.</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-archive fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Geçmiş Zimmet Bulunamadı</h5>
                                <p class="text-muted">Henüz teslim edilmiş zimmet bulunmamaktadır.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Kaldırıldı: Modal yapı. Yerine inline aç/kapa paneli kullanılıyor. -->

    <style>
        .hover-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(79, 172, 254, 0.25) !important;
            border-color: #4facfe;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .bg-gradient-secondary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
        }

        .card-header button {
            font-size: 0.85rem;
            padding: 0.375rem 0.75rem;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 0.5rem 0.5rem 0 0;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border: none;
        }

        .nav-tabs .nav-link:not(.active) {
            color: #6c757d;
            background: #f8f9fa;
        }

        .nav-tabs .nav-link:not(.active):hover {
            background: #e3f2fd;
            color: #1976d2;
            border-color: #bbdefb;
        }

        .modal-header {
            border-bottom: none;
        }

        .card {
            border: none;
            overflow: hidden;
        }

        .btn-close-white {
            filter: brightness(0) invert(1);
        }

        /* Breadcrumb stil */
        .breadcrumb {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%) !important;
            border: 1px solid rgba(79, 172, 254, 0.2);
        }

        .breadcrumb-item a {
            color: #1976d2 !important;
        }

        .breadcrumb-item a:hover {
            color: #4facfe !important;
        }

        .breadcrumb-item.active {
            color: #0d47a1 !important;
        }

        /* Ek mavimsi tonlar */
        .card-header {
            border-bottom: 2px solid rgba(79, 172, 254, 0.1);
        }

        .modal-header {
            border-bottom: 2px solid rgba(79, 172, 254, 0.1);
        }

        /* Badge renkleri */
        .badge.bg-primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
        }

        .badge.bg-secondary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .badge.bg-success {
            background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%) !important;
        }

        .badge.bg-warning {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%) !important;
            color: #8b4513 !important;
        }

        .badge.bg-info {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%) !important;
            color: #2c5aa0 !important;
        }

        /* Buton hover efektleri */
        .btn-primary:hover {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-color: #4facfe;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
            border-color: #36d1dc;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(54, 209, 220, 0.3);
        }

        /* Form input focus efektleri */
        .form-control:focus,
        .form-select:focus {
            border-color: #4facfe;
            box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
        }

        /* Alert renkleri */
        .alert-info {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-color: #4facfe;
            color: #1976d2;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            border-color: #ff9800;
            color: #e65100;
        }

        /* Sayfa başlığı */
        h2 i {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Kart gölge efektleri */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.15);
        }

        /* Modal gölge efektleri */
        .modal-content {
            box-shadow: 0 20px 60px rgba(79, 172, 254, 0.2);
            border: 1px solid rgba(79, 172, 254, 0.1);
        }
    </style>
@endsection

@section('js')
    <script>
        // Form validation
        document.querySelectorAll('[id^="returnForm"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                const fileInputs = this.querySelectorAll('input[type="file"]');
                let isValid = true;
                
                fileInputs.forEach(input => {
                    if (!input.files[0]) {
                        isValid = false;
                        input.classList.add('is-invalid');
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Lütfen tüm ekipmanlar için teslim fotoğrafı yükleyin.');
                }
            });
        });

        // File input change event
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                if (this.files[0]) {
                    this.classList.remove('is-invalid');
                }
            });
        });
    </script>
    <script>
        // Toggle inline photos panel
        document.addEventListener('click', function(e){
            const btn = e.target.closest('.btn-toggle-photos');
            if (!btn) return;
            e.preventDefault();
            const selector = btn.getAttribute('data-target');
            if (!selector) return;
            const panel = document.querySelector(selector);
            if (!panel) return;
            const isHidden = panel.style.display === 'none' || getComputedStyle(panel).display === 'none';
            panel.style.display = isHidden ? 'block' : 'none';
        });
    </script>
@endsection
