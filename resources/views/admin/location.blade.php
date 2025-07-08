@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
  .location-card { border-radius: 1.2rem; box-shadow: 0 2px 16px rgba(80,80,180,0.08); background: #fff; }
  .location-badge { font-size: 0.95rem; border-radius: 1em; padding: 0.3em 0.9em; font-weight: 600; }
  .location-badge.ofis { background: #6a82fb; color: #fff; }
  .location-badge.depo { background: #43e97b; color: #fff; }
  .location-badge.saha { background: #fc5c7d; color: #fff; }
  .location-badge.musteri { background: #ffb347; color: #fff; }
  .location-badge.santiye { background: #b993d6; color: #fff; }
  .location-badge.kritik { background: #ff4d4f; color: #fff; }
  .location-table th { background: #f7f7fa; font-weight: bold; }
  .location-actions .btn { margin-right: 0.2rem; }
  .location-heat { background: linear-gradient(90deg,#6a82fb 0%,#fc5c7d 100%); color: #fff; border-radius: 0.7rem; font-weight: 600; box-shadow: 0 2px 12px #fc5c7d22; border: none; }
</style>
<!-- Kritik bölge bildirimi -->
<div class="alert alert-danger d-flex align-items-center mb-3" role="alert">
  <i class="fas fa-exclamation-triangle fa-lg me-2"></i>
  <div><b>Kritik Bölge:</b> Saha - Karatay'da ekipman sayısı azaldı! Hızlı transfer önerilir.</div>
</div>
<!-- Lokasyon filtreleri ve aksiyonlar -->
<div class="d-flex flex-wrap gap-2 align-items-center mb-3">
  <select class="form-select form-select-sm" id="filterLokasyon" style="max-width:160px;">
    <option value="">Lokasyon (Tümü)</option>
    <option>Ofis - Selçuklu</option>
    <option>Depo - Meram</option>
    <option>Saha - Karatay</option>
    <option>Müşteri - Ereğli</option>
    <option>Şantiye - Beyşehir</option>
  </select>
  <select class="form-select form-select-sm" id="filterSorumlu" style="max-width:140px;">
    <option value="">Sorumlu (Tümü)</option>
    <option>admin</option>
    <option>teknisyen1</option>
    <option>lazBerat</option>
  </select>
  <button class="btn btn-outline-primary" id="addLocationBtn"><i class="fas fa-plus"></i> Lokasyon Ekle</button>
  <button class="btn btn-outline-info" id="showMapBtn"><i class="fas fa-map-marked-alt"></i> Haritada Göster</button>
  <button class="btn btn-outline-success ms-auto" id="qrUpdateBtn"><i class="fas fa-qrcode"></i> QR ile Konum Güncelle</button>
</div>
<!-- Eşya Listesi ve Konumlar -->
<div class="card location-card mb-4">
  <div class="card-header bg-light d-flex align-items-center">
    <i class="fas fa-boxes fa-lg me-2 text-primary"></i>
    <span class="fw-bold">Eşyaların Konum Listesi</span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 location-table" id="esyaKonumTable">
        <thead>
          <tr>
            <th>Eşya</th>
            <th>Mevcut Konum</th>
            <th>Sorumlu</th>
            <th>Lokasyon Türü</th>
            <th class="text-start">Aksiyon</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Jeneratör 5kVA</td>
            <td>Ofis - Selçuklu</td>
            <td>admin</td>
            <td><span class="location-badge ofis">Ofis</span></td>
            <td class="text-start location-actions">
              <button class="btn btn-sm btn-outline-info"><i class="fas fa-history"></i> Geçmiş</button>
              <button class="btn btn-sm btn-outline-primary"><i class="fas fa-map-marker-alt"></i> Haritada</button>
              <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i> Konum Güncelle</button>
            </td>
          </tr>
          <tr>
            <td>Oksijen Konsantratörü</td>
            <td>Saha - Karatay</td>
            <td>teknisyen1</td>
            <td><span class="location-badge saha">Saha</span></td>
            <td class="text-start location-actions">
              <button class="btn btn-sm btn-outline-info"><i class="fas fa-history"></i> Geçmiş</button>
              <button class="btn btn-sm btn-outline-primary"><i class="fas fa-map-marker-alt"></i> Haritada</button>
              <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i> Konum Güncelle</button>
            </td>
          </tr>
          <tr>
            <td>Hilti Kırıcı</td>
            <td>Depo - Meram</td>
            <td>lazBerat</td>
            <td><span class="location-badge depo">Depo</span></td>
            <td class="text-start location-actions">
              <button class="btn btn-sm btn-outline-info"><i class="fas fa-history"></i> Geçmiş</button>
              <button class="btn btn-sm btn-outline-primary"><i class="fas fa-map-marker-alt"></i> Haritada</button>
              <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i> Konum Güncelle</button>
            </td>
          </tr>
          <tr>
            <td>Akülü Matkap</td>
            <td>Müşteri - Ereğli</td>
            <td>teknisyen1</td>
            <td><span class="location-badge musteri">Müşteri</span></td>
            <td class="text-start location-actions">
              <button class="btn btn-sm btn-outline-info"><i class="fas fa-history"></i> Geçmiş</button>
              <button class="btn btn-sm btn-outline-primary"><i class="fas fa-map-marker-alt"></i> Haritada</button>
              <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i> Konum Güncelle</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<!-- Konum Geçmişi ve Harita -->
<div class="row g-4 mb-4">
  <div class="col-md-6">
    <div class="card location-card mb-4">
      <div class="card-header bg-light d-flex align-items-center">
        <i class="fas fa-history fa-lg me-2 text-info"></i>
        <span class="fw-bold">Seçili Eşyanın Konum Geçmişi</span>
      </div>
      <div class="card-body">
        <ul class="list-group">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            10.06.2025 <span>Depo - Meram</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            15.06.2025 <span>Saha - Karatay</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            18.06.2025 <span>Ofis - Selçuklu</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card location-card mb-4">
      <div class="card-header bg-light d-flex align-items-center">
        <i class="fas fa-map-marked-alt fa-lg me-2 text-success"></i>
        <span class="fw-bold">Haritada Göster</span>
      </div>
      <div class="card-body p-0">
        <div id="map" style="height: 260px; border-radius: 1rem;"></div>
      </div>
    </div>
  </div>
</div>
<!-- En çok iş yapılan bölgeler grafiği -->
<div class="card location-card mb-4">
  <div class="card-header bg-light d-flex align-items-center">
    <i class="fas fa-chart-bar fa-lg me-2 text-warning"></i>
    <span class="fw-bold">En Çok İş Yapılan Bölgeler</span>
  </div>
  <div class="card-body">
    <canvas id="bolgeBarChart" height="120"></canvas>
  </div>
</div>
<!-- Lokasyon Ekle Modalı -->
<div class="modal fade" id="addLocationModal" tabindex="-1" aria-labelledby="addLocationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addLocationModalLabel">Lokasyon Ekle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <form id="addLocationForm">
          <div class="mb-2">
            <label class="form-label">Lokasyon Adı</label>
            <input type="text" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Tür</label>
            <select class="form-select" required>
              <option>Ofis</option>
              <option>Depo</option>
              <option>Saha</option>
              <option>Müşteri</option>
              <option>Şantiye</option>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label">Adres</label>
            <input type="text" class="form-control">
          </div>
          <div class="mb-2">
            <label class="form-label">Harita Linki</label>
            <input type="text" class="form-control">
          </div>
          <div class="mb-2">
            <label class="form-label">İletişim</label>
            <input type="text" class="form-control">
          </div>
          <button type="submit" class="btn btn-primary w-100">Kaydet</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
// Harita örneği (Leaflet)
var map = L.map('map').setView([37.87, 32.48], 8);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 18,
  attribution: '© OpenStreetMap'
}).addTo(map);
L.marker([37.87, 32.48]).addTo(map).bindPopup('Ofis - Selçuklu');
L.marker([37.74, 32.48]).addTo(map).bindPopup('Depo - Meram');
L.marker([37.87, 32.53]).addTo(map).bindPopup('Saha - Karatay');
L.marker([37.51, 34.05]).addTo(map).bindPopup('Müşteri - Ereğli');
L.marker([37.68, 31.72]).addTo(map).bindPopup('Şantiye - Beyşehir');
// En çok iş yapılan bölgeler bar chart
new Chart(document.getElementById('bolgeBarChart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: ['Selçuklu', 'Meram', 'Karatay', 'Ereğli', 'Beyşehir'],
    datasets: [{
      label: 'İş Sayısı',
      data: [12, 9, 7, 5, 3],
      backgroundColor: ['#6a82fb','#43e97b','#fc5c7d','#ffb347','#b993d6']
    }]
  },
  options: {plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}
});
// Lokasyon ekle modalı aç
if(document.getElementById('addLocationBtn')) {
  document.getElementById('addLocationBtn').onclick = function() {
    new bootstrap.Modal(document.getElementById('addLocationModal')).show();
  };
}
// QR ile konum güncelle (örnek)
document.getElementById('qrUpdateBtn').onclick = function() {
  alert('QR ile konum güncelleme özelliği için mobil uygulama entegrasyonu gereklidir.');
};
</script>
@endsection