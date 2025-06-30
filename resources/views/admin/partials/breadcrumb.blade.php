<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a></li>
        <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Yönetim</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Sayfa' }}</li>
    </ol>
</nav>
<!-- Üst Bar: Duyuru, Hava Durumu, Bildirim, Arama, Kullanıcı Kartı -->
<div class="row g-3 mb-4 align-items-center">
    <div class="col-lg-8 d-flex align-items-center gap-3 flex-wrap">
        <div class="alert alert-info mb-0 py-2 px-3 shadow-sm"><i class="fa fa-bullhorn me-2"></i> Hoşgeldin! Sistem güncellemeleri başarıyla tamamlandı.</div>
        <div class="card shadow-sm border-0 mb-0" style="min-width:180px;">
            <div class="card-body d-flex align-items-center gap-2 p-2">
                <i class="fa fa-cloud-sun fa-2x text-primary"></i>
                <div>
                    <div class="fw-bold">İstanbul</div>
                    <div class="small">24°C, Parçalı Bulutlu</div>
                </div>
            </div>
        </div>
        <div class="dropdown">
            <button class="btn btn-light shadow-sm position-relative" type="button" data-bs-toggle="dropdown"><i class="fa fa-bell"></i><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span></button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"><i class="fa fa-exclamation-circle text-warning me-2"></i> Kritik stok azaldı!</a></li>
                <li><a class="dropdown-item" href="#"><i class="fa fa-bug text-danger me-2"></i> Yeni arıza bildirimi</a></li>
                <li><a class="dropdown-item" href="#"><i class="fa fa-user-plus text-success me-2"></i> Yeni kullanıcı eklendi</a></li>
            </ul>
        </div>
    </div>
    <div class="col-lg-4 d-flex justify-content-end align-items-center gap-3">
        <div class="input-group shadow-sm" style="max-width:220px;">
            <input type="text" class="form-control form-control-sm" placeholder="Panelde ara...">
            <button class="btn btn-outline-secondary btn-sm"><i class="fa fa-search"></i></button>
        </div>
        <button class="btn btn-outline-info btn-sm shadow-sm" data-bs-toggle="tooltip" data-bs-placement="left" title="Yardım almak için tıkla!"><i class="fa fa-question-circle"></i></button>
        <div class="card border-0 shadow-sm mb-0" style="min-width:120px;">
            <div class="card-body py-2 px-3 d-flex align-items-center gap-2">
                <img src="https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff&rounded=true" width="32" height="32" alt="Avatar">
                <div>
                    <div class="fw-bold small mb-0">Admin</div>
                    <div class="text-muted small">Yönetici</div>
                </div>
                <a href="/logout" class="btn btn-sm btn-outline-danger ms-2" title="Çıkış"><i class="fa fa-sign-out-alt"></i></a>
            </div>
        </div>
    </div>
</div> 