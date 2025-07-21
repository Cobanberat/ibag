@extends('layouts.admin')
@section('content')


@vite(['resources/css/equipmentStatus.css'])

<!-- Bakım Gerektiren Ekipmanlar Butonu -->
<div class="alert alert-warning shadow-sm d-flex align-items-center justify-content-between mb-4 mt-4 p-4" role="alert" style="border-left: 6px solid #ffc107;">
  <div class="d-flex align-items-center">
    <i class="fas fa-tools fa-2x me-3 text-warning"></i>
    <div>
      <h5 class="mb-1 fw-bold">Bakım Gerektiren Ekipmanlar</h5>
      <div class="small text-dark">Bakım zamanı gelen ekipmanlar için hızlıca aksiyon alın.</div>
    </div>
  </div>
  <button class="btn btn-warning d-flex align-items-center" id="bakimEkipmanModalBtn">
    <i class="fas fa-eye me-1"></i> Bakım Gerektiren Ekipmanları Gör
  </button>
</div>

<!-- Filtreleme Barı -->
<div class="card shadow-sm mb-4">
  <div class="card-body">
    <form class="row g-2 align-items-center">
      <div class="col-md-3">
        <input type="text" class="form-control" placeholder="Eşya Ara..."/>
      </div>
      <div class="col-md-2">
        <select class="form-select">
          <option>Kategori (Tümü)</option>
          <option>Elektrik</option>
          <option>Medikal</option>
          <option>İnşaat</option>
        </select>
      </div>
      <div class="col-md-2">
        <select class="form-select">
          <option>Durum (Tümü)</option>
          <option>Sorunsuz</option>
          <option>Bakım Gerekiyor</option>
          <option>Arızalı</option>
        </select>
      </div>
      <div class="col-md-2">
        <select class="form-select">
          <option>Sorumlu (Tümü)</option>
          <option>lazBerat</option>
          <option>admin</option>
          <option>teknisyen1</option>
        </select>
      </div>
      <div class="col-md-3 text-end">
        <button class="btn btn-primary"><i class="fas fa-search"></i> Filtrele</button>
      </div>
    </form>
  </div>
</div>

<!-- Modern Eşya Kartları Grid -->
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
  <!-- Kart 1 -->
  <div class="col">
    <div class="equipment-card card h-100 border-0 position-relative">
      <div class="equipment-img-box">
        <img src="/images/gen-return.jpg" class="equipment-img" alt="Jeneratör" onerror="this.style.display='none';this.parentNode.querySelector('.equipment-placeholder').style.display='flex';">
        <div class="equipment-img-overlay"></div>
        <div class="equipment-title-bar">
          <span class="fw-bold">Jeneratör 5kVA</span>
          <span class="equipment-status bg-warning text-dark">Bakım Gerekiyor</span>
        </div>
        <div class="equipment-placeholder" style="display:none;"><i class="fas fa-cogs"></i></div>
      </div>
      <div class="card-body">
        <div class="mb-2">
          <span class="badge bg-info text-dark me-1">Elektrik</span>
          <span class="badge bg-primary">Acil</span>
        </div>
        <div class="mb-1 small"><i class="fas fa-user me-1"></i> Sorumlu: lazBerat</div>
        <div class="mb-1 small"><i class="fas fa-calendar-alt me-1"></i> Son Bakım: 01.05.2025</div>
      </div>
      <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center gap-2">
        <button class="btn btn-outline-success btn-sm servis-talep-btn" data-eid="1"><i class="fas fa-tools"></i> Servis Talebi</button>
        <button class="btn btn-outline-primary btn-sm detay-gor-btn" data-eid="1"><i class="fas fa-eye"></i> Detay</button>
        <button class="favorite-btn btn btn-sm ms-auto" data-eid="1"><i class="far fa-star"></i></button>
      </div>
    </div>
  </div>
  <!-- Kart 2 -->
  <div class="col">
    <div class="equipment-card card h-100 border-0 position-relative">
      <div class="equipment-img-box">
        <img src="/images/oksijen.jpg" class="equipment-img" alt="Oksijen" onerror="this.style.display='none';this.parentNode.querySelector('.equipment-placeholder').style.display='flex';">
        <div class="equipment-img-overlay"></div>
        <div class="equipment-title-bar">
          <span class="fw-bold">Oksijen Konsantratörü</span>
          <span class="equipment-status bg-success text-white">Sorunsuz</span>
        </div>
        <div class="equipment-placeholder" style="display:none;"><i class="fas fa-lungs"></i></div>
      </div>
      <div class="card-body">
        <div class="mb-2">
          <span class="badge bg-info text-dark me-1">Medikal</span>
        </div>
        <div class="mb-1 small"><i class="fas fa-user me-1"></i> Sorumlu: admin</div>
        <div class="mb-1 small"><i class="fas fa-calendar-alt me-1"></i> Son Bakım: 10.05.2025</div>
      </div>
      <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center gap-2">
        <button class="btn btn-outline-success btn-sm servis-talep-btn" data-eid="2"><i class="fas fa-tools"></i> Servis Talebi</button>
        <button class="btn btn-outline-primary btn-sm detay-gor-btn" data-eid="2"><i class="fas fa-eye"></i> Detay</button>
        <button class="favorite-btn btn btn-sm ms-auto" data-eid="2"><i class="far fa-star"></i></button>
      </div>
    </div>
  </div>
  <!-- Kart 3 -->
  <div class="col">
    <div class="equipment-card card h-100 border-0 position-relative">
      <div class="equipment-img-box">
        <img src="/images/hilti.jpg" class="equipment-img" alt="Hilti" onerror="this.style.display='none';this.parentNode.querySelector('.equipment-placeholder').style.display='flex';">
        <div class="equipment-img-overlay"></div>
        <div class="equipment-title-bar">
          <span class="fw-bold">Hilti Kırıcı</span>
          <span class="equipment-status bg-danger text-white">Arızalı</span>
        </div>
        <div class="equipment-placeholder" style="display:none;"><i class="fas fa-hammer"></i></div>
      </div>
      <div class="card-body">
        <div class="mb-2">
          <span class="badge bg-info text-dark me-1">İnşaat</span>
        </div>
        <div class="mb-1 small"><i class="fas fa-user me-1"></i> Sorumlu: teknisyen1</div>
        <div class="mb-1 small"><i class="fas fa-calendar-alt me-1"></i> Son Bakım: 05.06.2025</div>
      </div>
      <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center gap-2">
        <button class="btn btn-outline-success btn-sm servis-talep-btn" data-eid="3"><i class="fas fa-tools"></i> Servis Talebi</button>
        <button class="btn btn-outline-primary btn-sm detay-gor-btn" data-eid="3"><i class="fas fa-eye"></i> Detay</button>
        <button class="favorite-btn btn btn-sm ms-auto" data-eid="3"><i class="far fa-star"></i></button>
      </div>
    </div>
  </div>
</div>

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
          <!-- Jeneratör -->
          <div class="col">
            <div class="card shadow-sm border-0 card-hover">
              <div class="d-flex align-items-center p-2">
                <img src="/images/gen-return.jpg" alt="Jeneratör" class="rounded me-3" style="width:60px;height:60px;object-fit:cover;">
                <div>
                  <div class="fw-bold">Jeneratör 5kVA</div>
                  <div class="small text-muted">Sorumlu: lazBerat</div>
                  <span class="badge bg-warning text-dark mt-1">Bakım Gerekiyor</span>
                </div>
              </div>
              <div class="card-footer bg-white border-0 d-flex justify-content-end gap-2">
                <button class="btn btn-outline-success btn-sm servis-talep-btn" data-eid="1"><i class="fas fa-tools"></i> Servis Talebi</button>
                <button class="btn btn-outline-primary btn-sm detay-gor-btn" data-eid="1"><i class="fas fa-eye"></i> Detay</button>
              </div>
            </div>
          </div>
          <!-- Hilti Kırıcı -->
          <div class="col">
            <div class="card shadow-sm border-0 card-hover">
              <div class="d-flex align-items-center p-2">
                <img src="/images/hilti.jpg" alt="Hilti" class="rounded me-3" style="width:60px;height:60px;object-fit:cover;">
                <div>
                  <div class="fw-bold">Hilti Kırıcı</div>
                  <div class="small text-muted">Sorumlu: teknisyen1</div>
                  <span class="badge bg-danger mt-1">Arızalı</span>
                </div>
              </div>
              <div class="card-footer bg-white border-0 d-flex justify-content-end gap-2">
                <button class="btn btn-outline-success btn-sm servis-talep-btn" data-eid="3"><i class="fas fa-tools"></i> Servis Talebi</button>
                <button class="btn btn-outline-primary btn-sm detay-gor-btn" data-eid="3"><i class="fas fa-eye"></i> Detay</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>

<!-- Detay Modalı (Jeneratör) -->
<div class="modal fade" id="detayModal1" tabindex="-1" aria-labelledby="detayModal1Label" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-warning bg-opacity-25">
        <h5 class="modal-title" id="detayModal1Label"><i class="fas fa-tools text-warning me-2"></i>Jeneratör 5kVA Detayları</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <!-- Detay içeriği -->
        <div class="row mb-3">
          <div class="col-md-4">
            <label class="fw-bold">Eşya Adı:</label>
            <p>Jeneratör 5kVA</p>
            <div class="mb-2">
              <span class="badge bg-info text-dark me-1">Elektrik</span>
              <span class="badge bg-primary">Acil</span>
            </div>
            <label class="fw-bold">Durumu:</label>
            <p><span class="badge bg-warning text-dark">Bakım Gerekiyor</span></p>
          </div>
          <div class="col-md-4">
            <label class="fw-bold">Son Kullanım Yeri:</label>
            <p>Hatay - Kırıkhan</p>
          </div>
          <div class="col-md-4">
            <label class="fw-bold">Sorumlu Kişi:</label>
            <p>lazBerat</p>
            <label class="fw-bold">Seri No:</label>
            <p>GEN-5K-2023</p>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="fw-bold">Son Durum Fotoğrafı:</label><br>
            <img src="/images/gen-return.jpg" alt="Durum Fotoğrafı" class="img-fluid rounded border shadow-sm mt-1">
          </div>
          <div class="col-md-6">
            <label class="fw-bold">Yetkili Notu:</label>
            <div class="border rounded p-2 bg-light fst-italic">
              Cihaz çalışıyor fakat yağ filtresi sızdırıyor. Servis yönlendirildi.
            </div>
          </div>
        </div>
        <div class="mb-3">
          <h6 class="fw-semibold"><i class="fas fa-history me-1"></i> Durum Geçmişi</h6>
          <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between">
              <span>17.06.2025 - Geri döndü: <em>Bakım Gerekiyor</em></span>
              <span class="text-muted">lazBerat</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>15.06.2025 - Gönderildi: <em>Sorunsuz</em></span>
              <span class="text-muted">admin</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>01.05.2025 - Akü değişimi yapıldı</span>
              <span class="text-muted">teknisyen1</span>
            </li>
          </ul>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>
<!-- Detay Modalı (Hilti Kırıcı) -->
<div class="modal fade" id="detayModal3" tabindex="-1" aria-labelledby="detayModal3Label" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-danger bg-opacity-25">
        <h5 class="modal-title" id="detayModal3Label"><i class="fas fa-exclamation-circle text-danger me-2"></i>Hilti Kırıcı Detayları</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-md-4">
            <label class="fw-bold">Eşya Adı:</label>
            <p>Hilti Kırıcı</p>
            <div class="mb-2">
              <span class="badge bg-info text-dark me-1">İnşaat</span>
            </div>
            <label class="fw-bold">Durumu:</label>
            <p><span class="badge bg-danger">Arızalı</span></p>
          </div>
          <div class="col-md-4">
            <label class="fw-bold">Son Kullanım Yeri:</label>
            <p>Gaziantep - Şahinbey</p>
           
          </div>
          <div class="col-md-4">
            <label class="fw-bold">Sorumlu Kişi:</label>
            <p>teknisyen1</p>
            <label class="fw-bold">Seri No:</label>
            <p>HILT-2024-01</p>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="fw-bold">Son Durum Fotoğrafı:</label><br>
            <img src="/images/hilti.jpg" alt="Durum Fotoğrafı" class="img-fluid rounded border shadow-sm mt-1">
          </div>
          <div class="col-md-6">
            <label class="fw-bold">Yetkili Notu:</label>
            <div class="border rounded p-2 bg-light fst-italic">
              Motor arızası tespit edildi. Servis bekleniyor.
            </div>
          </div>
        </div>
        <div class="mb-3">
          <h6 class="fw-semibold"><i class="fas fa-history me-1"></i> Durum Geçmişi</h6>
          <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between">
              <span>19.06.2025 - Arızalı olarak bildirildi</span>
              <span class="text-muted">teknisyen1</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>10.06.2025 - Kullanımda</span>
              <span class="text-muted">teknisyen1</span>
            </li>
          </ul>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>

<!-- Servis Talep Modalı (örnek) -->
<div class="modal fade" id="servisTalepModal" tabindex="-1" aria-labelledby="servisTalepModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="servisTalepModalLabel">Servis Talebi Oluştur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-2">
            <label class="form-label">Açıklama</label>
            <textarea class="form-control" rows="2"></textarea>
          </div>
          <div class="mb-2">
            <label class="form-label">Öncelik</label>
            <select class="form-select">
              <option>Normal</option>
              <option>Yüksek</option>
              <option>Acil</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
        <button type="button" class="btn btn-success">Talep Gönder</button>
      </div>
    </div>
  </div>
</div>

@vite(['resources/js/equipmentStatus.js'])
@endsection