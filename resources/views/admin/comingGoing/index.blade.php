@extends('layouts.admin')
@section('content')
@vite(['resources/css/comingGoing.css'])
<div class="animated-title"><i class="fas fa-truck"></i> Giden-Gelen Ekipman İşlemleri</div>
<!-- Sekmeler -->
<ul class="nav nav-tabs approval-tabs mb-3" id="comingGoingTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="giden-tab" data-bs-toggle="tab" data-bs-target="#gidenTab" type="button" role="tab">Gidenler</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="gelen-tab" data-bs-toggle="tab" data-bs-target="#gelenTab" type="button" role="tab">Gelenler</button>
  </li>
</ul>
<div class="tab-content" id="comingGoingTabContent">
  <div class="tab-pane fade show active" id="gidenTab" role="tabpanel">
<div class="col-md-12 mb-5">
  <form class="filter-bar mb-2" id="gidenFilterForm" onsubmit="return false;">
    <label>Arama:</label>
    <input type="text" class="form-control" id="gidenSearch" placeholder="Lokasyon, yetkili...">
    <label>Lokasyon:</label>
    <select class="form-select" id="gidenLokasyon">
      <option value="">Tümü</option>
    </select>
    <label>Yetkili:</label>
    <select class="form-select" id="gidenYetkili">
      <option value="">Tümü</option>
    </select>
    <button class="btn btn-outline-primary btn-sm ms-auto" type="button" onclick="clearGidenFilters()">Temizle</button>
  </form>
  <h4 class="mb-3">Gidenler</h4>
  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead>
        <tr>
          <th>Lokasyon</th>
          <th>Yetkili</th>
          <th>Tarih</th>
          <th>Detay</th>
          <th>İşlemi Bitir</th>
        </tr>
      </thead>
      <tbody id="gidenTableBody"></tbody>
    </table>
    <nav class="custom-pagination"><ul class="pagination" id="gidenPagination"></ul></nav>
  </div>
</div>
  </div>
  <div class="tab-pane fade" id="gelenTab" role="tabpanel">
    <div class="col-md-12">
      <form class="filter-bar mb-2" id="gelenFilterForm" onsubmit="return false;">
        <label>Arama:</label>
        <input type="text" class="form-control" id="gelenSearch" placeholder="Lokasyon, yetkili...">
        <label>Lokasyon:</label>
        <select class="form-select" id="gelenLokasyon">
          <option value="">Tümü</option>
        </select>
        <label>Yetkili:</label>
        <select class="form-select" id="gelenYetkili">
          <option value="">Tümü</option>
        </select>
        <label>Durum:</label>
        <select class="form-select" id="gelenDurum">
          <option value="">Tümü</option>
          <option value="Sorunsuz">Sorunsuz</option>
          <option value="Hasarlı">Hasarlı</option>
          <option value="Eksik">Eksik</option>
        </select>
        <button class="btn btn-outline-primary btn-sm ms-auto" type="button" onclick="clearGelenFilters()">Temizle</button>
      </form>
      <h4 class="mb-3">Gelenler</h4>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead>
            <tr>
              <th>Lokasyon</th>
              <th>Yetkili</th>
              <th>Tarih</th>
              <th>Durum</th>
              <th>Detay</th>
            </tr>
          </thead>
          <tbody id="gelenTableBody"></tbody>
        </table>
        <nav class="custom-pagination"><ul class="pagination" id="gelenPagination"></ul></nav>
      </div>
    </div>
  </div>
</div>
<!-- Detay Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel">İşlem Detayı</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body" id="detailModalBody">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>
<!-- İşlemi Bitir Modal -->
<div class="modal fade" id="finishModal" tabindex="-1" aria-labelledby="finishModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="finishModalLabel">Dönüş İşlemini Tamamla</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <form id="finishForm">
        <div class="modal-body" id="finishModalBody"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
          <button type="submit" class="btn btn-success">Onayla ve Gelenlere Ekle</button>
        </div>
      </form>
    </div>
  </div>
</div>
@vite(['resources/js/comingGoing.js'])
@endsection