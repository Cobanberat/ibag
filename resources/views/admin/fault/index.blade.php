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
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7">
      <div class="fault-card">
        <div class="fault-header">
          <i class="fas fa-bug"></i>
          <span class="fw-bold fs-4">Arıza Bildirimi</span>
        </div>
        <div class="card-body p-4">
          <div class="mb-3 text-muted small">
            Lütfen tespit ettiğiniz arızayı detaylıca bildiriniz. Teknik ekibimiz en kısa sürede sizinle iletişime geçecektir.
          </div>
          <div class="alert fault-success d-none" id="faultSuccessAlert">
            <i class="fas fa-check-circle me-1"></i> Arıza bildiriminiz başarıyla iletildi!
          </div>
          <form id="faultForm" class="fault-form" enctype="multipart/form-data" autocomplete="off">
            <div class="mb-3">
              <label class="form-label">Ekipman</label>
              <select class="form-select" required>
                <option value="">Seçiniz...</option>
                <option>Jeneratör 5kVA</option>
                <option>Oksijen Konsantratörü</option>
                <option>Hilti Kırıcı</option>
                <option>Akülü Matkap</option>
              </select>
            </div>
      
            <div class="mb-3">
              <label class="form-label">Açıklama</label>
              <textarea class="form-control" rows="3" placeholder="Arızanın detaylarını yazınız..." required></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Fotoğraf / Dosya Ekle</label>
              <input type="file" class="form-control" accept="image/*,application/pdf">
            </div>
            <div class="mb-3 row g-2">
              <div class="col-md-12">
                <label class="form-label">Öncelik</label>
                <select class="form-select" required>
                  <option value="">Seçiniz...</option>
                  <option>Normal</option>
                  <option>Yüksek</option>
                  <option>Acil</option>
                </select>
              </div>
            
            </div>
            <div class="mb-3 row g-2">
              <div class="col-md-12">
                <label class="form-label">Tespit Tarihi</label>
                <input type="date" class="form-control" required>
              </div>
            
            </div>
            
            <div class="d-grid gap-2 mt-4">
              <button type="submit" class="btn btn-main btn-lg shadow-sm"><i class="fas fa-paper-plane me-1"></i> Arızayı Bildir</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@vite(['resources/js/fault.js'])
@endsection