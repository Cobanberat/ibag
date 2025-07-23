@extends('layouts.admin')
@section('content')
  <div class="container mt-4">
    <h3>Yeni Ekipman Ekle</h3>
    <form id="addProductForm" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="productName" class="form-label">Ekipman Adı</label>
        <input type="text" class="form-control" id="productType" name="productName" required>
      </div>
      <div class="mb-3">
        <label for="brand" class="form-label">Marka</label>
        <input type="text" class="form-control" id="brand" name="brand" required>
      </div>
      <div class="mb-3">
        <label for="model" class="form-label">Model</label>
        <input type="text" class="form-control" id="model" name="model" required>
      </div>
      <div class="mb-3">
        <label for="size" class="form-label">Beden</label>
        <input type="text" class="form-control" id="size" name="size">
      </div>
      <div class="mb-3">
        <label for="feature" class="form-label">Özellik</label>
        <input type="text" class="form-control" id="feature" name="feature">
      </div>
      <div class="mb-3">
        <label for="quantity" class="form-label">Adet</label>
        <input type="number" class="form-control" id="quantity" name="quantity" min="0" required>
      </div>
      <div class="mb-3">
        <label for="category" class="form-label">Kategori</label>
        <select type="number" class="form-control" id="category" name="category" required>
          <option value="" selected>Kategori Seç</option>
          <option value="elektrik">Elektrik</option>
          <option value="Delici">Delici</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="photo" class="form-label">Resim</label>
        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
      </div>
      <div class="mb-3">
        <label for="note" class="form-label">Not</label>
        <textarea class="form-control" id="note" name="note" rows="2"></textarea>
      </div>
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="yes" name="yes">
        <label class="form-check-label" for="yes">Evet</label>
      </div>
      <div class="mb-3">
        <label for="status" class="form-label">Durum</label>
        <input type="text" class="form-control" id="status" name="status" >
      </div>
      <button type="submit" class="btn btn-primary">Kaydet</button>
    </form>
  </div>
@endsection
