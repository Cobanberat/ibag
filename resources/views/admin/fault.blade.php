@extends('layouts.admin')
@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a></li>
        <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Arıza Bildirimi' }}</li>
    </ol>
</nav><style>
  .fault-card {
    border-radius: 1.5rem;
    box-shadow: 0 4px 32px rgba(80,80,180,0.08), 0 1.5px 4px rgba(80,80,180,0.04);
    background: #fff;
    overflow: hidden;
  }
  .fault-header {
    background: linear-gradient(90deg, #6a82fb 0%, #fc5c7d 100%);
    color: #fff;
    border-top-left-radius: 1.5rem;
    border-top-right-radius: 1.5rem;
    padding: 1.2rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  .fault-header i {
    font-size: 2.2rem;
    opacity: 0.85;
  }
  .fault-form .form-label {
    font-weight: 600;
    color: #4a4a6a;
  }
  .fault-form .form-control, .fault-form .form-select {
    border-radius: 0;
    font-size: 1.08rem;
    min-height: 2.5rem;
    box-shadow: none;
    border: 1.5px solid #e0e7ff;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .fault-form .form-control:focus, .fault-form .form-select:focus {
    border-color: #6a82fb;
    box-shadow: 0 0 0 2px #6a82fb33;
  }
  .fault-form textarea.form-control {
    min-height: 80px;
  }
  .fault-form .btn-main {
    background: linear-gradient(90deg, #6a82fb 0%, #fc5c7d 100%);
    color: #fff;
    font-weight: 600;
    border: none;
    border-radius: 0.7rem;
    font-size: 1.15rem;
    padding: 0.8rem 0;
    box-shadow: 0 2px 12px #6a82fb22;
    transition: background 0.2s, box-shadow 0.2s;
  }
  .fault-form .btn-main:hover, .fault-form .btn-main:focus {
    background: linear-gradient(90deg, #fc5c7d 0%, #6a82fb 100%);
    box-shadow: 0 4px 24px #fc5c7d22;
    color: #fff;
  }
  .fault-success {
    background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
    color: #222;
    border-radius: 0.7rem;
    font-weight: 600;
    box-shadow: 0 2px 12px #38f9d733;
    border: none;
  }
  .fault-form .form-select {
    border-radius: 10px 10px 0px 0px;
    font-size: 1.08rem;
    min-height: 2.5rem;
    box-shadow: none;
    border: 1.5px solid #e0e7ff;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
</style>
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
              <label class="form-label">Arıza Tipi</label>
              <select class="form-select" required>
                <option value="">Seçiniz...</option>
                <option>Elektriksel</option>
                <option>Mekanik</option>
                <option>Yazılımsal</option>
                <option>Diğer</option>
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
              <div class="col-md-6">
                <label class="form-label">Öncelik</label>
                <select class="form-select" required>
                  <option value="">Seçiniz...</option>
                  <option>Normal</option>
                  <option>Yüksek</option>
                  <option>Acil</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Sorumlu</label>
                <select class="form-select" required>
                  <option value="">Seçiniz...</option>
                  <option>admin</option>
                  <option>teknisyen1</option>
                </select>
              </div>
            </div>
            <div class="mb-3 row g-2">
              <div class="col-md-6">
                <label class="form-label">Tespit Tarihi</label>
                <input type="date" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">İletişim (Telefon/E-posta)</label>
                <input type="text" class="form-control" placeholder="Telefon veya e-posta" required>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Ek Not</label>
              <textarea class="form-control" rows="2" placeholder="Varsa ek bilgi yazınız..."></textarea>
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
<script>
// Demo: Form submit sonrası başarı mesajı göster
const form = document.getElementById('faultForm');
if(form) {
  form.onsubmit = function(e) {
    e.preventDefault();
    document.getElementById('faultSuccessAlert').classList.remove('d-none');
    setTimeout(()=>{
      document.getElementById('faultSuccessAlert').classList.add('d-none');
      form.reset();
    }, 2500);
  };
}
</script>
@endsection