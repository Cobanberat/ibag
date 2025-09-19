@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-clipboard-list text-primary me-2"></i>
                        {{ $pageTitle }}
                    </h2>
                    <p class="text-muted mb-0">Zimmet detayları ve teslim alma işlemleri</p>
                </div>
                <div>
                    <a href="{{ route('admin.teslimEt') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Geri Dön
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Zimmet Bilgileri -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Zimmet Bilgileri
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Zimmet No:</strong></td>
                                    <td>{{ $assignment->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Zimmet Alan:</strong></td>
                                    <td>{{ $assignment->assignedUser->name ?? 'Bilinmiyor' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Zimmet Veren:</strong></td>
                                    <td>{{ $assignment->assignedBy->name ?? 'Sistem' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Zimmet Tarihi:</strong></td>
                                    <td>{{ $assignment->created_at->format('d.m.Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Durum:</strong></td>
                                    <td>
                                        @if($assignment->status == 0)
                                            <span class="badge bg-warning">Beklemede</span>
                                        @elseif($assignment->status == 1)
                                            <span class="badge bg-success">Teslim Alındı</span>
                                        @else
                                            <span class="badge bg-danger">İptal</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Toplam Ekipman:</strong></td>
                                    <td>{{ $assignment->items->count() }} adet</td>
                                </tr>
                                <tr>
                                    <td><strong>Not:</strong></td>
                                    <td>{{ $assignment->note ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ekipman Listesi -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-tools me-2"></i>Zimmetli Ekipmanlar
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Sıra</th>
                                    <th>Ekipman</th>
                                    <th>Kod</th>
                                    <th>Marka</th>
                                    <th>Model</th>
                                    <th>Adet</th>
                                    <th>Zimmet Fotoğrafı</th>
                                    <th>Teslim Fotoğrafı</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignment->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $item->equipment->name ?? 'Bilinmiyor' }}</strong>
                                            @if($item->equipment && $item->equipment->category)
                                                <br><small class="text-muted">{{ $item->equipment->category->name }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->equipment_stock->code ?? '-' }}</td>
                                        <td>{{ $item->equipment_stock->brand ?? '-' }}</td>
                                        <td>{{ $item->equipment_stock->model ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $item->quantity }}</span>
                                        </td>
                                        <td>
                                            @if($item->photo_path)
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        onclick="showPhoto('{{ asset('storage/' . $item->photo_path) }}', 'Zimmet Fotoğrafı')">
                                                    <i class="fas fa-image"></i> Görüntüle
                                                </button>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->return_photo_path)
                                                <button type="button" class="btn btn-sm btn-outline-success" 
                                                        onclick="showPhoto('{{ asset('storage/' . $item->return_photo_path) }}', 'Teslim Fotoğrafı')">
                                                    <i class="fas fa-image"></i> Görüntüle
                                                </button>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->returned_at)
                                                <span class="badge bg-success">Teslim Edildi</span>
                                            @else
                                                <span class="badge bg-warning">Beklemede</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if(!$item->returned_at)
                                                    <button type="button" class="btn btn-sm btn-success" 
                                                            onclick="returnItem({{ $item->id }})">
                                                        <i class="fas fa-check"></i> Teslim Al
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-info" 
                                                        onclick="showItemDetails({{ $item->id }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                            <p class="text-muted">Bu zimmette ekipman bulunmuyor</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fotoğraf Modal -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">Fotoğraf</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalPhoto" src="" alt="Fotoğraf" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

<!-- Teslim Alma Modal -->
<div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returnModalLabel">Ekipman Teslim Alma</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="returnForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="returnNote" class="form-label">Teslim Notu</label>
                        <textarea class="form-control" id="returnNote" name="note" rows="3" 
                                  placeholder="Teslim alma ile ilgili notlarınızı yazın..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="returnPhoto" class="form-label">Teslim Fotoğrafı</label>
                        <input type="file" class="form-control" id="returnPhoto" name="photo" accept="image/*">
                        <div class="form-text">Teslim alma anını gösteren fotoğraf yükleyin</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Teslim Al
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentItemId = null;

// Fotoğraf göster
function showPhoto(imageUrl, title) {
    document.getElementById('modalPhoto').src = imageUrl;
    document.getElementById('photoModalLabel').textContent = title;
    new bootstrap.Modal(document.getElementById('photoModal')).show();
}

// Ekipman detaylarını göster
function showItemDetails(itemId) {
    // AJAX ile ekipman detaylarını getir
    fetch(`/admin/assignments/item/${itemId}/photos`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Detay modal'ı göster (bu fonksiyon mevcut değilse basit alert kullan)
                alert(`Ekipman: ${data.data.title}\nZimmet Fotoğrafı: ${data.data.initial ? 'Var' : 'Yok'}\nTeslim Fotoğrafı: ${data.data.return ? 'Var' : 'Yok'}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Teslim alma işlemi
function returnItem(itemId) {
    currentItemId = itemId;
    document.getElementById('returnForm').reset();
    new bootstrap.Modal(document.getElementById('returnModal')).show();
}

// Teslim alma formu gönder
document.getElementById('returnForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`/admin/assignments/item/${currentItemId}/return`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Başarılı!',
                text: data.message,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                title: 'Hata!',
                text: data.message || 'Teslim alma işlemi başarısız.',
                icon: 'error'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Hata!',
            text: 'Teslim alma işlemi sırasında bir hata oluştu.',
            icon: 'error'
        });
    });
});
</script>
@endpush
