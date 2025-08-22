@extends('layouts.admin')
@section('content')

@vite('resources/css/statusCheck.css')
<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
      <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a></li>
      <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Durum Kontrolü' }}</li>
  </ol>
</nav>

<!-- İstatistik Kartları -->
<div class="row mb-4">
  <div class="col-md-2">
    <div class="card text-white bg-danger">
      <div class="card-body text-center">
        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
        <h4>{{ $stats['acil_durum'] ?? 0 }}</h4>
        <small>Acil Durum</small>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-white bg-warning">
      <div class="card-body text-center">
        <i class="fas fa-tools fa-2x mb-2"></i>
        <h4>{{ $stats['toplam_bakim'] ?? 0 }}</h4>
        <small>Bakım Gerekiyor</small>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-white bg-info">
      <div class="card-body text-center">
        <i class="fas fa-wrench fa-2x mb-2"></i>
        <h4>{{ $stats['toplam_arizali'] ?? 0 }}</h4>
        <small>Arızalı</small>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-white bg-success">
      <div class="card-body text-center">
        <i class="fas fa-check-circle fa-2x mb-2"></i>
        <h4>{{ $stats['bu_ay_cozulen'] ?? 0 }}</h4>
        <small>Bu Ay Çözülen</small>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-white bg-secondary">
      <div class="card-body text-center">
        <i class="fas fa-clock fa-2x mb-2"></i>
        <h4>{{ $stats['bekleyen_islem'] ?? 0 }}</h4>
        <small>Bekleyen İşlem</small>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-white bg-primary">
      <div class="card-body text-center">
        <i class="fas fa-calendar-alt fa-2x mb-2"></i>
        <h4>{{ $stats['yaklasan_bakim'] ?? 0 }}</h4>
        <small>Yaklaşan Bakım</small>
      </div>
    </div>
  </div>
</div>

<!-- Bildirim ve Hatırlatıcılar -->
@if(($stats['yaklasan_bakim'] ?? 0) > 0)
<div class="alert alert-warning d-flex align-items-center justify-content-between mb-3 clickable-alert" role="alert" id="upcomingAlert">
  <div class="d-flex align-items-center">
    <i class="fas fa-bell fa-lg me-2"></i>
    <div><b>{{ $stats['yaklasan_bakim'] }} ekipmanda yaklaşan bakım var!</b> <span class="small">Bakım planlaması yapmayı unutmayın.</span></div>
  </div>
  <button class="btn btn-sm btn-outline-warning" id="showUpcomingBtn"><i class="fas fa-list"></i> Ürünleri Gör</button>
</div>
@endif

<!-- Filtreleme Barı -->
<div class="mb-3">
  <input type="text" class="form-control" id="tabloAramaInput" placeholder="Ekipman, işlem, sorumlu...">
</div>

<!-- Sekmeler -->
<ul class="nav nav-tabs mb-3" id="statusTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="acil-tab" data-bs-toggle="tab" data-bs-target="#acil" type="button" role="tab">Acil Durumlar</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="yapilacaklar-tab" data-bs-toggle="tab" data-bs-target="#yapilacaklar" type="button" role="tab">Yapılması Gerekenler</button>
  </li>
</ul>
<div class="tab-content" id="statusTabContent">
  <div class="tab-pane fade show active" id="acil" role="tabpanel">
    <!-- Acil Durumlar Tablosu -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-danger text-white"><i class="fas fa-exclamation-circle me-2"></i> Acil Durumlar</div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" id="acilDurumlarTable">
            <thead>
              <tr>
                <th>Ekipman</th>
                <th>İşlem</th>
                <th>Planlanan Tarih</th>
                <th>Sorumlu</th>
                <th class="text-end">Aksiyon</th>
              </tr>
            </thead>
            <tbody id="acilDurumlarTableBody">
              @forelse($acilDurumlar as $item)
                <tr data-id="{{ $item['id'] }}" data-type="fault" class="aciliyet-{{ $item['aciliyet'] ?? 'normal' }}">
                  <td>
                    <div class="d-flex align-items-center">
                      <i class="fas fa-tools me-2 text-muted"></i>
                      <span>{{ $item['ekipman'] }}</span>
                    </div>
                  </td>
                  <td>
                    <span class="badge bg-{{ $item['priority'] === 'Yüksek' ? 'danger' : ($item['priority'] === 'Orta' ? 'warning' : 'secondary') }}">
                      {{ $item['islem'] }}
                    </span>
                  </td>
                  <td>
                    <small class="text-muted">{{ $item['planlanan_tarih'] }}</small>
                  </td>
                  <td>
                    <div class="d-flex align-items-center">
                      <i class="fas fa-user-circle me-1 text-muted"></i>
                      <small>{{ $item['sorumlu'] }}</small>
                    </div>
                  </td>
                  <td class="text-end">
                    <button class="btn btn-sm btn-outline-info me-1" onclick="showDetail('fault', {{ $item['id'] }})" title="Detay">
                      <i class="fas fa-eye"></i>
                    </button>
                    <div class="btn-group" role="group">
                      <button class="btn btn-sm btn-outline-success" onclick="updateStatus('fault', {{ $item['id'] }}, 'İşlemde')" title="İşleme Al">
                        <i class="fas fa-play"></i>
                      </button>
                      <button class="btn btn-sm btn-outline-primary" onclick="updateStatus('fault', {{ $item['id'] }}, 'Çözüldü')" title="Çözüldü Olarak İşaretle">
                        <i class="fas fa-check"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">
                    <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                    <br>Acil durum bulunmuyor
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
          <nav><ul class="pagination justify-content-end m-3" id="acilDurumlarPagination"></ul></nav>
        </div>
      </div>
    </div>
  </div>
  <div class="tab-pane fade" id="yapilacaklar" role="tabpanel">
    <!-- Yapılması Gereken Durumlar Tablosu -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-info text-white"><i class="fas fa-calendar-check me-2"></i> Yapılması Gereken Durumlar</div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" id="yapilacaklarTable">
            <thead>
              <tr>
                <th>Ekipman</th>
                <th>İşlem</th>
                <th>Planlanan Tarih</th>
                <th>Sorumlu</th>
                <th class="text-end">Aksiyon</th>
              </tr>
            </thead>
            <tbody id="yapilacaklarTableBody">
              @forelse($yapilacaklar as $item)
                <tr data-id="{{ $item['id'] }}" data-type="{{ $item['type'] }}">
                  <td>
                    <div class="d-flex align-items-center">
                      <i class="fas fa-{{ $item['type'] === 'maintenance' ? 'calendar-check' : 'tools' }} me-2 text-muted"></i>
                      <span>{{ $item['ekipman'] }}</span>
                    </div>
                  </td>
                  <td>
                    <span class="badge bg-{{ $item['priority'] === 'Yüksek' ? 'danger' : ($item['priority'] === 'Orta' ? 'warning' : 'info') }}">
                      {{ $item['islem'] }}
                    </span>
                  </td>
                  <td>
                    <small class="text-muted">{{ $item['planlanan_tarih'] }}</small>
                  </td>
                  <td>
                    <div class="d-flex align-items-center">
                      <i class="fas fa-user-circle me-1 text-muted"></i>
                      <small>{{ $item['sorumlu'] }}</small>
                    </div>
                  </td>
                  <td class="text-end">
                    <button class="btn btn-sm btn-outline-info me-1" onclick="showDetail('{{ $item['type'] }}', '{{ $item['id'] }}')" title="Detay">
                      <i class="fas fa-eye"></i>
                    </button>
                    @if($item['type'] === 'fault')
                    <div class="btn-group" role="group">
                      <button class="btn btn-sm btn-outline-warning" onclick="updateStatus('fault', {{ $item['id'] }}, 'İşlemde')" title="İşleme Al">
                        <i class="fas fa-play"></i>
                      </button>
                    </div>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">
                    <i class="fas fa-calendar-check fa-3x mb-3 text-info"></i>
                    <br>Yapılması gereken işlem bulunmuyor
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
          <nav><ul class="pagination justify-content-end m-3" id="yapilacaklarPagination"></ul></nav>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Satır Detay Paneli (JS ile eklenir) -->
<div id="rowDetailPanel"></div>

<!-- Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="infoModalLabel">Detaylı Bilgi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body" id="infoModalBody">
        <!-- İçerik JS ile doldurulacak -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>

@vite('resources/js/statusCheck.js')

<script>
// Detay modalını göster
function showDetail(type, id) {
    fetch(`{{ route('admin.statusCheck.detail') }}?type=${type}&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const modalBody = document.getElementById('infoModalBody');
                let html = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Ekipman Bilgileri</h6>
                            <p><strong>Ekipman:</strong> ${data.data.ekipman}</p>
                            <p><strong>İşlem Tipi:</strong> ${data.data.tip}</p>
                            <p><strong>Öncelik:</strong> <span class="badge bg-${data.data.oncelik === 'Yüksek' ? 'danger' : (data.data.oncelik === 'Orta' ? 'warning' : 'secondary')}">${data.data.oncelik}</span></p>
                            <p><strong>Durum:</strong> <span class="badge bg-info">${data.data.durum}</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">İşlem Bilgileri</h6>
                            <p><strong>Bildirim Tarihi:</strong> ${data.data.bildirim_tarihi}</p>
                            <p><strong>Bildiren:</strong> ${data.data.bildiren}</p>
                            <p><strong>Sorumlu:</strong> ${data.data.sorumlu}</p>
                            ${data.data.cozum_tarihi !== '-' ? `<p><strong>Çözüm Tarihi:</strong> ${data.data.cozum_tarihi}</p>` : ''}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="fw-bold">Açıklama</h6>
                            <p class="text-muted">${data.data.aciklama || 'Açıklama bulunmuyor'}</p>
                            ${data.data.cozum_notu !== '-' ? `
                                <h6 class="fw-bold">Çözüm Notu</h6>
                                <p class="text-success">${data.data.cozum_notu}</p>
                            ` : ''}
                        </div>
                    </div>
                `;
                modalBody.innerHTML = html;
                
                const modal = new bootstrap.Modal(document.getElementById('infoModal'));
                modal.show();
            } else {
                Swal.fire({
                    title: 'Hata!',
                    text: data.error || 'Detay bilgisi alınamadı',
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Hata!',
                text: 'Detay bilgisi alınırken hata oluştu',
                icon: 'error'
            });
        });
}

// Durum güncelle
function updateStatus(type, id, status) {
    const statusText = status === 'İşlemde' ? 'işleme almak' : 'çözüldü olarak işaretlemek';
    
    Swal.fire({
        title: 'Emin misiniz?',
        text: `Bu kaydı ${statusText} istediğinizden emin misiniz?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Evet, Güncelle!',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('admin.statusCheck.updateStatus') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    type: type,
                    id: id,
                    status: status
                })
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
                    });
                    setTimeout(() => location.reload(), 2000);
                } else {
                    Swal.fire({
                        title: 'Hata!',
                        text: data.error || 'İşlem başarısız',
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Hata!',
                    text: 'İşlem sırasında hata oluştu',
                    icon: 'error'
                });
            });
        }
    });
}

// Filtreleme fonksiyonu
function filterActiveTabTable() {
  const search = document.getElementById('tabloAramaInput').value.trim().toLowerCase();
  // Aktif sekmeyi bul
  const activeTab = document.querySelector('.tab-pane.active.show');
  if (!activeTab) return;
  const tbody = activeTab.querySelector('tbody');
  if (!tbody) return;
  const rows = tbody.querySelectorAll('tr');
  rows.forEach(tr => {
    const text = tr.innerText.toLowerCase();
    tr.style.display = (search === '' || text.includes(search)) ? '' : 'none';
  });
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('tabloAramaInput').addEventListener('input', filterActiveTabTable);
    
    // Sekme değişince filtreyi uygula
    const tabBtns = document.querySelectorAll('#statusTab button[data-bs-toggle="tab"]');
    tabBtns.forEach(btn => {
      btn.addEventListener('shown.bs.tab', function() {
        filterActiveTabTable();
      });
    });
    
    // Yaklaşan bakım butonuna tıklama
    const showUpcomingBtn = document.getElementById('showUpcomingBtn');
    if (showUpcomingBtn) {
        showUpcomingBtn.addEventListener('click', function() {
            // Yapılacaklar sekmesine geç
            const yapilacaklarTab = document.getElementById('yapilacaklar-tab');
            if (yapilacaklarTab) {
                yapilacaklarTab.click();
            }
        });
    }
});
</script>

<style>
.aciliyet-critical {
    background-color: #ffebee !important;
    border-left: 4px solid #f44336;
}

.aciliyet-warning {
    background-color: #fff8e1 !important;
    border-left: 4px solid #ff9800;
}

.aciliyet-normal {
    background-color: #f9f9f9;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.badge {
    font-size: 0.75rem;
}

.btn-group .btn {
    border-radius: 0.25rem !important;
    margin-left: 2px;
}

.clickable-alert {
    cursor: pointer;
}

.clickable-alert:hover {
    opacity: 0.9;
}
</style>

@endsection