@extends('layouts.admin')
@section('content')
<div class="container mt-4">
    <h3 class="mb-4 fw-bold"><i class="fas fa-plus me-2 text-primary"></i>Yeni Ekipman Ekle</h3>
    <form id="addProductForm" enctype="multipart/form-data" method="POST" action="{{ route('stock.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label for="category_id" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">Kategori Seçiniz</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="name" class="form-label fw-bold">Ekipman Adı <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" required placeholder="Örn: Jeneratör">
            </div>
            <div class="col-md-4">
                <label for="brand" class="form-label fw-bold">Marka</label>
                <input type="text" class="form-control" id="brand" name="brand" placeholder="Örn: Honda">
            </div>
            <div class="col-md-4">
                <label for="model" class="form-label fw-bold">Model</label>
                <input type="text" class="form-control" id="model" name="model" placeholder="Örn: EU3000i">
            </div>
            <div class="col-md-4">
                <label for="size" class="form-label fw-bold">Beden/Özellik</label>
                <input type="text" class="form-control" id="size" name="size" placeholder="Örn: 3KW, XL, 1000W">
            </div>
            <div class="col-md-4">
                <label for="feature" class="form-label fw-bold">Özellik</label>
                <textarea class="form-control" id="feature" name="feature" rows="2" placeholder="Ekipman özellikleri..."></textarea>
            </div>
            <div class="col-md-2">
                <label for="quantity" class="form-label fw-bold">Adet <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1" required>
            </div>
            <div class="col-md-2">
                <label for="critical_level" class="form-label fw-bold">Kritik Seviye</label>
                <input type="number" class="form-control" id="critical_level" name="critical_level" min="1" value="3">
            </div>
            <div class="col-md-6">
                <label for="code" class="form-label fw-bold">Ekipman Kodu</label>
                <input type="text" class="form-control" id="code" name="code" placeholder="Otomatik oluşturulur veya elle girin">
            </div>
            <div class="col-md-6">
                <label for="location" class="form-label fw-bold">Konum</label>
                <input type="text" class="form-control" id="location" name="location" placeholder="Örn: Depo A, Raf 1">
            </div>
            <div class="col-md-6">
                <label for="status" class="form-label fw-bold">Durum</label>
                <select class="form-select" id="status" name="status">
                    <option value="aktif">Aktif</option>
                    <option value="pasif">Pasif</option>
                    <option value="bakımda">Bakımda</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="photo" class="form-label fw-bold">Ekipman Fotoğrafı</label>
                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
            </div>
            <div class="col-12">
                <label for="note" class="form-label fw-bold">Not</label>
                <textarea class="form-control" id="note" name="note" rows="2" placeholder="Ek bilgi (opsiyonel)"></textarea>
            </div>
            <div class="col-12">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="individual_tracking" name="individual_tracking" value="1">
                    <label class="form-check-label fw-bold" for="individual_tracking">
                        <i class="fas fa-barcode me-2"></i>Her ürünü ayrı ayrı takip et
                    </label>
                </div>
                <small class="text-muted">
                    <strong>Aktifse:</strong> Her ürün ayrı kod, ayrı resim, tek adet (Jeneratör, bilgisayar gibi)<br>
                    <strong>Kapalıysa:</strong> Tek kod, tek resim, miktar bazlı (Kablo, vida gibi)
                </small>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary btn-lg px-4"><i class="fas fa-save me-2"></i>Kaydet</button>
        </div>
    </form>
</div>
@endsection
