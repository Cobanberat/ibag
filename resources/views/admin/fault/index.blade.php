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
  <h3>Yeni Arıza Bildirimi</h3>
  <form id="faultForm" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="equipment" class="form-label">Ekipman</label>
      <select class="form-select" id="equipment" name="equipment" required>
        <option value="">Seçiniz...</option>
        <option>Jeneratör 5kVA</option>
        <option>Oksijen Konsantratörü</option>
        <option>Hilti Kırıcı</option>
        <option>Akülü Matkap</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Açıklama</label>
      <textarea class="form-control" id="description" name="description" rows="3" placeholder="Arızanın detaylarını yazınız..." required></textarea>
    </div>

    <div class="mb-3">
      <label for="file" class="form-label">Fotoğraf / Dosya</label>
      <input type="file" class="form-control" id="file" name="file" accept="image/*,application/pdf">
    </div>

    <div class="mb-3">
      <label for="priority" class="form-label">Öncelik</label>
      <select class="form-select" id="priority" name="priority" required>
        <option value="">Seçiniz...</option>
        <option>Normal</option>
        <option>Yüksek</option>
        <option>Acil</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="date" class="form-label">Tespit Tarihi</label>
      <input type="date" class="form-control" id="date" name="date" required>
    </div>

    <button type="submit" class="btn btn-primary">Bildirim Gönder</button>
  </form>
</div>
@vite(['resources/js/fault.js'])
@endsection