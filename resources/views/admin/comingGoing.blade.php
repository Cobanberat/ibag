@extends('layouts.admin')
@section('content')
<style>
  .modern-card-header {
    background: linear-gradient(90deg, #0d6efd 60%, #36b3f6 100%);
    color: #fff;
    box-shadow: 0 4px 16px rgba(13,110,253,0.08);
    border-radius: 1rem 1rem 0 0;
    cursor: pointer;
    transition: box-shadow 0.2s;
    position: relative;
    z-index: 1;
    min-height: 40px;
    font-size: 1.05rem;
    font-weight: 600;
    letter-spacing: 0.01em;
    padding: 0.45rem 0.7rem 0.45rem 0.7rem;
  }
  .modern-card-header:hover {
    box-shadow: 0 8px 32px rgba(13,110,253,0.16);
  }
  .modern-chevron {
    transition: transform 0.3s;
    font-size: 1.05rem;
    margin-left: 0.3rem;
  }
  .modern-card {
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(0,0,0,0.07);
    margin-bottom: 2rem;
    background: #f8fafd;
  }
  .modern-card-body {
    background: #fff;
    border-radius: 0 0 1rem 1rem;
    padding: 2rem 1.5rem 1.5rem 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
  }
  .modern-badge {
    font-size: 1em;
    border-radius: 0.5rem;
    padding: 0.45em 0.9em;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    transition: transform 0.2s;
  }
  .modern-badge:hover {
    transform: scale(1.1);
  }
  .modern-table th, .modern-table td {
    vertical-align: middle;
    font-size: 1.05em;
  }
  .modern-table tbody tr {
    transition: background 0.2s;
  }
  .modern-table tbody tr:hover {
    background: #e7f1ff;
  }
  .timeline {
    position: relative;
    margin-left: 1.5rem;
    margin-bottom: 1rem;
  }
  .timeline::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, #0d6efd 0%, #36b3f6 100%);
    border-radius: 2px;
  }
  .timeline-event {
    position: relative;
    margin-bottom: 1.2rem;
    padding-left: 2.2rem;
  }
  .timeline-event:last-child { margin-bottom: 0; }
  .timeline-dot {
    position: absolute;
    left: -0.1rem;
    top: 0.2rem;
    width: 1.1rem;
    height: 1.1rem;
    background: #fff;
    border: 3px solid #0d6efd;
    border-radius: 50%;
    z-index: 2;
    box-shadow: 0 2px 8px #0d6efd22;
  }
  .timeline-event .event-title {
    font-weight: 600;
    color: #0d6efd;
    font-size: 1.05em;
  }
  .timeline-event .event-date {
    font-size: 0.95em;
    color: #888;
    margin-left: 0.5rem;
  }
  .quick-actions {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
  }
  .quick-actions .btn {
    border-radius: 0.5rem;
    font-size: 0.98em;
    font-weight: 500;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
  }
  .avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 0.5rem;
    border: 2px solid #fff;
    box-shadow: 0 1px 4px #0d6efd22;
  }
  .progress {
    height: 1.1rem;
    border-radius: 0.5rem;
    background: #e7f1ff;
  }
  .progress-bar {
    font-size: 0.98em;
    font-weight: 600;
  }
  .copy-btn {
    border: none;
    background: none;
    color: #0d6efd;
    cursor: pointer;
    font-size: 1.1em;
    margin-left: 0.3em;
    transition: color 0.2s;
  }
  .copy-btn:hover { color: #36b3f6; }
  .more-text { display: none; }
  .show-more .more-text { display: inline; }
  .show-more .dots { display: none; }
  .modern-card-header .header-title {
    font-size: 1.08rem;
    font-weight: 700;
    margin-left: 0.15rem;
    margin-right: 0.5rem;
    line-height: 1.1;
    color: #fff;
    text-shadow: 0 2px 8px #0d6efd33;
    display: flex;
    align-items: center;
  }
  .modern-card-header .modern-chevron {
    font-size: 1.05rem;
    margin-left: 0.3rem;
  }
  .modern-card-header .badge {
    margin-left: 0.5rem !important;
    font-size: 0.92rem;
    padding: 0.35em 0.7em;
  }
  @media (max-width: 600px) {
    .modern-card-header, .modern-card-header .header-title {
      font-size: 0.95rem;
      padding: 0.3rem 0.4rem 0.3rem 0.4rem;
    }
    .modern-card-header .header-title {
      font-size: 0.98rem;
    }
    .modern-card-header .modern-chevron {
      font-size: 0.98rem;
    }
  }
</style>
<!-- Modern Kart Başlangıcı -->
<div class="modern-card">
  <div class="modern-card-header d-flex justify-content-between align-items-center collapsed" data-bs-toggle="collapse" data-bs-target="#collapseCard2" aria-expanded="false" aria-controls="collapseCard2" style="user-select:none;">
    <div class="d-flex align-items-center header-title">
      <i class="fas fa-truck me-2" style="font-size:1.1rem;"></i>
      <span>Giden - Gelen Ekipman İşlemi</span>
      <i class="fas fa-chevron-down modern-chevron" id="iconCard2"></i>
    </div>
    <span class="badge bg-light text-dark ms-2">#ID: 2025-ANKARA-021</span>
  </div>
  <div id="collapseCard2" class="collapse">
    <div class="modern-card-body">
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="fw-bold">Gönderilen Yer:</label>
          <p class="mb-1">Ankara - Sincan</p>
          <label class="fw-bold">Tarih (Gidiş):</label>
          <p>20 Temmuz 2025</p>
        </div>
        <div class="col-md-6">
          <label class="fw-bold">Geldiği Tarih:</label>
          <p class="mb-1">22 Temmuz 2025</p>
          <label class="fw-bold">Götüren Kişi:</label>
          <div class="d-flex align-items-center">
            <img src="/images/avatar1.jpg" class="avatar" alt="lazBerat">
            <span>ayseYilmaz</span>
          </div>
        </div>
      </div>
      <div class="quick-actions mb-3">
        <button class="btn btn-outline-primary btn-sm"><i class="fas fa-tools"></i> Servise Gönder</button>
        <button class="btn btn-outline-danger btn-sm"><i class="fas fa-exclamation-triangle"></i> Kayıp Bildir</button>
        <button class="btn btn-outline-success btn-sm"><i class="fas fa-qrcode"></i> QR ile Takip</button>
        <button class="btn btn-outline-secondary btn-sm copy-btn" onclick="navigator.clipboard.writeText('UPS 3kVA, Kask, Ankara - Sincan');"><i class="fas fa-copy"></i> Bilgiyi Kopyala</button>
        <button class="btn btn-outline-dark btn-sm"><i class="fas fa-share-alt"></i> Paylaş</button>
      </div>
      <div class="mb-3">
        <h6 class="fw-semibold"><i class="fas fa-boxes me-1"></i> Götürülen Malzemeler</h6>
        <ul class="list-group">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><i class="fas fa-bolt text-warning me-1"></i> UPS 3kVA</span>
            <span class="modern-badge bg-success">2 Adet</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><i class="fas fa-hard-hat text-primary me-1"></i> Kask</span>
            <span class="modern-badge bg-info">10 Adet</span>
          </li>
        </ul>
      </div>
      <div class="mb-3">
        <h6 class="fw-semibold"><i class="fas fa-camera me-1"></i> Malzeme Gidiş Fotoğrafı</h6>
        <img src="/images/before2.jpg" class="img-fluid rounded border shadow-sm" alt="Gidiş Fotoğrafı">
      </div>
      <div class="mb-3">
        <h6 class="fw-semibold"><i class="fas fa-undo-alt me-1"></i> Malzeme Dönüş Durumu</h6>
        <table class="table modern-table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Malzeme</th>
              <th>Durum</th>
              <th>Not</th>
              <th>Görsel</th>
              <th>İşlem</th>
              <th>İlerleme</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><i class="fas fa-bolt text-warning me-1"></i> UPS</td>
              <td><span class="modern-badge bg-success">Sorunsuz</span></td>
              <td><span class="dots">UPS cihazı yeni batarya ile döndü...</span><span class="more-text">UPS cihazı yeni batarya ile döndü. Batarya değişimi sonrası test edildi ve onaylandı.</span> <a href="#" class="show-more-link" onclick="event.preventDefault(); this.closest('td').classList.toggle('show-more');">daha fazla</a></td>
              <td><img src="/images/ups-return.jpg" width="60" class="rounded border"></td>
              <td>
                <button class="btn btn-outline-primary btn-sm" title="Servise Gönder"><i class="fas fa-tools"></i></button>
                <button class="btn btn-outline-danger btn-sm" title="Kayıp Bildir"><i class="fas fa-exclamation-triangle"></i></button>
                <button class="btn btn-outline-success btn-sm" title="QR ile Takip"><i class="fas fa-qrcode"></i></button>
              </td>
              <td>
                <div class="progress" title="Teslim Edildi">
                  <div class="progress-bar bg-success" style="width: 100%;">100%</div>
                </div>
              </td>
            </tr>
            <tr>
              <td><i class="fas fa-hard-hat text-primary me-1"></i> Kask</td>
              <td><span class="modern-badge bg-warning text-dark">Eksik</span></td>
              <td><span class="dots">Eksik kasklar için tutanak tutuldu...</span><span class="more-text">Eksik kasklar için tutanak tutuldu. 2 adet kayıp bildirildi ve tutanak eklendi.</span> <a href="#" class="show-more-link" onclick="event.preventDefault(); this.closest('td').classList.toggle('show-more');">daha fazla</a></td>
              <td><img src="/images/helmet-return.jpg" width="60" class="rounded border"></td>
              <td>
                <button class="btn btn-outline-primary btn-sm" title="Servise Gönder"><i class="fas fa-tools"></i></button>
                <button class="btn btn-outline-danger btn-sm" title="Kayıp Bildir"><i class="fas fa-exclamation-triangle"></i></button>
                <button class="btn btn-outline-success btn-sm" title="QR ile Takip"><i class="fas fa-qrcode"></i></button>
              </td>
              <td>
                <div class="progress" title="Eksik">
                  <div class="progress-bar bg-warning text-dark" style="width: 60%;">60%</div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="mb-4">
        <h6 class="fw-semibold mb-2"><i class="fas fa-history me-1"></i> Hareket Zaman Çizelgesi</h6>
        <div class="timeline">
          <div class="timeline-event">
            <div class="timeline-dot"></div>
            <span class="event-title">UPS Teslim Edildi</span>
            <span class="event-date">22 Temmuz 2025</span>
            <div class="text-muted small">Teslim Alan: <img src="/images/avatar2.jpg" class="avatar" alt="teknisyen3"> teknisyen3</div>
          </div>
          <div class="timeline-event">
            <div class="timeline-dot"></div>
            <span class="event-title">UPS Batarya Değişimi</span>
            <span class="event-date">21 Temmuz 2025</span>
            <div class="text-muted small">Servis: Batarya değişimi yapıldı.</div>
          </div>
          <div class="timeline-event">
            <div class="timeline-dot"></div>
            <span class="event-title">UPS Gönderildi</span>
            <span class="event-date">20 Temmuz 2025</span>
            <div class="text-muted small">Gönderen: <img src="/images/avatar1.jpg" class="avatar" alt="ayseYilmaz"> ayseYilmaz</div>
          </div>
        </div>
      </div>
      <div class="mb-3">
        <h6 class="fw-semibold mb-2"><i class="fas fa-comments me-1"></i> Yorumlar / Notlar</h6>
        <div class="border rounded p-2 bg-light mb-2">
          <div class="d-flex align-items-center mb-1">
            <img src="/images/avatar1.jpg" class="avatar" alt="ayseYilmaz">
            <span class="fw-bold me-2">ayseYilmaz</span>
            <span class="text-muted small">22.07.2025 14:10</span>
          </div>
          <div class="ps-4">UPS teslim edildi, batarya değişimi sonrası test edildi.</div>
        </div>
        <div class="border rounded p-2 bg-light">
          <div class="d-flex align-items-center mb-1">
            <img src="/images/avatar2.jpg" class="avatar" alt="teknisyen3">
            <span class="fw-bold me-2">teknisyen3</span>
            <span class="text-muted small">22.07.2025 15:00</span>
          </div>
          <div class="ps-4">Teslim alındı, cihaz sorunsuz çalışıyor.</div>
        </div>
        <div class="mt-2">
          <input type="text" class="form-control form-control-sm" placeholder="Yorum ekle...">
        </div>
      </div>
      <div class="d-flex justify-content-end gap-2">
        <button class="btn btn-secondary"><i class="fas fa-download"></i> PDF İndir</button>
        <button class="btn btn-primary"><i class="fas fa-edit"></i> İşlemi Düzenle</button>
      </div>
    </div>
  </div>
</div>
@push('scripts')
<script>
function setupCardCollapse(cardNum) {
  const collapse = document.getElementById('collapseCard' + cardNum);
  const icon = document.getElementById('iconCard' + cardNum);
  if (!collapse || !icon) return;
  collapse.addEventListener('show.bs.collapse', function () {
    icon.style.transform = 'rotate(180deg)';
  });
  collapse.addEventListener('hide.bs.collapse', function () {
    icon.style.transform = 'rotate(0deg)';
  });
}
setupCardCollapse(2);
// Daha fazla/daha az açıklama için
window.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.show-more-link').forEach(function(link) {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      this.closest('td').classList.toggle('show-more');
    });
  });
});
</script>
@endpush
@endsection