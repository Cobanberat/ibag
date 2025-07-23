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
<!-- Bildirim ve Hatırlatıcılar -->

<div class="alert alert-warning d-flex align-items-center justify-content-between mb-3 clickable-alert" role="alert" id="upcomingAlert">
  <div class="d-flex align-items-center">
    <i class="fas fa-bell fa-lg me-2"></i>
    <div><b>2 ekipmanda yaklaşan bakım var!</b> <span class="small">Bakım planlaması yapmayı unutmayın.</span></div>
  </div>
  <button class="btn btn-sm btn-outline-warning" id="showUpcomingBtn"><i class="fas fa-list"></i> Ürünleri Gör</button>
</div>


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
        <tbody id="acilDurumlarTableBody"></tbody>
      </table>
      <nav><ul class="pagination justify-content-end m-3" id="acilDurumlarPagination"></ul></nav>
    </div>
  </div>
</div>

<!-- Gelişmiş Filtreler ve Toplu İşlem Barı -->


<!-- Kontroller Ne Zaman Yapılmış? Tablosu -->


<!-- Satır Detay Paneli (JS ile eklenir) -->

<!-- Yaklaşan Kontroller Modalı -->


<!-- Geciken Kontroller Modalı -->


<!-- Yeni Kontrol Ekle Modalı -->
<div class="modal fade" id="addControlModal" tabindex="-1" aria-labelledby="addControlModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addControlModalLabel">Yeni Kontrol Ekle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <!-- Yeni kontrol ekleme formu buraya gelecek -->
      </div>
    </div>
  </div>
</div>

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
        <tbody id="yapilacaklarTableBody"></tbody>
      </table>
      <nav><ul class="pagination justify-content-end m-3" id="yapilacaklarPagination"></ul></nav>
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

@endsection