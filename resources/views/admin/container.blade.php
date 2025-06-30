@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <!-- Breadcrumb (Sayfa Yönlendirme) -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a></li>
            <li class="breadcrumb-item"><a href="admin/" class="text-decoration-none">Yönetim</a></li>
            <li class="breadcrumb-item active" aria-current="page"></li>
        </ol>
    </nav>
    <!-- Üst Bar: Kullanıcı Kartı, Yardım, Duyuru, Hava Durumu, Bildirimler -->
    <div class="row g-3 mb-4 align-items-center">
        <div class="col-lg-8 d-flex align-items-center gap-3 flex-wrap">
            <!-- Duyuru Alanı -->
            <div class="alert alert-info mb-0 py-2 px-3 shadow-sm"><i class="fa fa-bullhorn me-2"></i> Hoşgeldin! Sistem güncellemeleri başarıyla tamamlandı.</div>
            <!-- Hava Durumu Kartı (örnek veriyle) -->
            <div class="card shadow-sm border-0 mb-0" style="min-width:180px;">
                <div class="card-body d-flex align-items-center gap-2 p-2">
                    <i class="fa fa-cloud-sun fa-2x text-primary"></i>
                    <div>
                        <div class="fw-bold">İstanbul</div>
                        <div class="small">24°C, Parçalı Bulutlu</div>
                    </div>
                </div>
            </div>
            <!-- Bildirimler -->
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
            <!-- Gelişmiş Arama -->
            <div class="input-group shadow-sm" style="max-width:220px;">
                <input type="text" class="form-control form-control-sm" placeholder="Panelde ara...">
                <button class="btn btn-outline-secondary btn-sm"><i class="fa fa-search"></i></button>
            </div>
            <!-- Yardım Butonu -->
            <button class="btn btn-outline-info btn-sm shadow-sm" data-bs-toggle="tooltip" data-bs-placement="left" title="Yardım almak için tıkla!"><i class="fa fa-question-circle"></i></button>
            <!-- Kullanıcı Kartı -->
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
    <!-- Hızlı İşlemler ve Takvim/Son Girişler Alanı (Yan Yana) -->
    <div class="row g-3 mb-4">
        <!-- Hızlı İşlemler (Sol) -->
        <div class="col-md-6">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-header bg-white border-0 pb-1 text-center">
                    <span class="fw-bold fs-5"><i class="fa fa-bolt text-warning me-2"></i>Hızlı İşlemler</span>
                </div>
                <div class="card-body d-flex flex-wrap justify-content-center gap-3 p-3">
                    <a href="{{route('admin.equipments')}}" class="quick-action-btn" data-bs-toggle="tooltip" title="Yeni ekipman ekle">
                        <div class="quick-action-icon bg-gradient-primary"><i class="fa fa-plus-circle"></i></div>
                        <div class="quick-action-label">Ekipman Ekle</div>
                    </a>
                    <a href="{{route('admin.fault')}}" class="quick-action-btn" data-bs-toggle="tooltip" title="Arıza bildirimi yap">
                        <div class="quick-action-icon bg-gradient-danger"><i class="fa fa-bug"></i></div>
                        <div class="quick-action-label">Arıza Bildir</div>
                    </a> 
                    <a href="{{route('admin.location')}}" class="quick-action-btn" data-bs-toggle="tooltip" title="Konum takibi yap">
                        <div class="quick-action-icon bg-gradient-success"><i class="fa fa-map-marker-alt"></i></div>
                        <div class="quick-action-label">Konum Takibi</div>
                    </a>
                    <a href="{{route('admin.users')}}" class="quick-action-btn" data-bs-toggle="tooltip" title="Kullanıcıları yönet">
                        <div class="quick-action-icon bg-gradient-info"><i class="fa fa-users-cog"></i></div>
                        <div class="quick-action-label">Kullanıcı Yönetimi</div>
                    </a>
                    <a href="/admin/stock" class="quick-action-btn" data-bs-toggle="tooltip" title="Stokları kontrol et">
                        <div class="quick-action-icon bg-gradient-warning"><i class="fa fa-boxes-stacked"></i></div>
                        <div class="quick-action-label">Stok Kontrol</div>
                    </a>
                    <a href="#" class="quick-action-btn" data-bs-toggle="tooltip" title="Yeni görev oluştur">
                        <div class="quick-action-icon bg-gradient-secondary"><i class="fa fa-tasks"></i></div>
                        <div class="quick-action-label">Görev Oluştur</div>
                    </a>
                </div>
            </div>
        </div>
        <!-- Takvim ve Son Girişler (Sağ) -->
        <div class="col-md-6 d-flex flex-column gap-3">
            <div class="card shadow-sm border-0 mb-2 flex-fill">
                <div class="card-header bg-white border-0 pb-1"><span class="fw-bold"><i class="fa fa-calendar-alt text-success me-1"></i>Takvim</span></div>
                <div class="card-body p-2">
                    <input type="text" id="dashboardCalendar" class="form-control form-control-sm" placeholder="Tarih seç...">
                </div>
            </div>
            <div class="card shadow-sm border-0 flex-fill">
                <div class="card-header bg-white border-0 pb-1"><span class="fw-bold"><i class="fa fa-sign-in-alt text-primary me-1"></i>Son Girişler</span></div>
                <div class="card-body p-2">
                    <ul class="list-unstyled mb-0 small">
                        <li><i class="fa fa-user-circle text-success me-1"></i> Ali K. <span class="text-muted">09:12</span></li>
                        <li><i class="fa fa-user-circle text-info me-1"></i> Ayşe Y. <span class="text-muted">08:55</span></li>
                        <li><i class="fa fa-user-circle text-warning me-1"></i> Mehmet D. <span class="text-muted">08:30</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Sistem Durumu -->
    <div class="row g-3 mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pb-1"><span class="fw-bold"><i class="fa fa-server text-secondary me-1"></i>Sistem Durumu</span></div>
                <div class="card-body p-2 d-flex gap-3 flex-wrap">
                    <span class="badge bg-success"><i class="fa fa-database me-1"></i>Veritabanı: Aktif</span>
                    <span class="badge bg-success"><i class="fa fa-cloud me-1"></i>API: Aktif</span>
                    <span class="badge bg-success"><i class="fa fa-server me-1"></i>Sunucu: Aktif</span>
                    <span class="badge bg-warning text-dark"><i class="fa fa-exclamation-triangle me-1"></i>Disk: %80</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Gradientli başlık ve hoşgeldin -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-5 fw-bold" style="background:linear-gradient(90deg,#6366f1 0%,#43e97b 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Yönetim Paneline Hoşgeldin!</h1>
            <p class="lead text-muted mb-0">Tüm işlemlerini ve analizlerini tek ekrandan yönet.</p>
        </div>
        <div id="clock" class="fs-5 text-primary"></div>
    </div>
    <!-- KPI Kartları -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow h-100 kpi-card text-white position-relative" style="background:linear-gradient(135deg,#43e97b 0%,#6366f1 100%);">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="display-6 mb-2"><i class="fa fa-box"></i></div>
                    <div class="h3 mb-0 counter" data-count="128">0</div>
                    <div class="small">Toplam Ekipman</div>
                </div>
                <span class="position-absolute top-0 end-0 m-2 badge bg-light text-primary">+5 bugün</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow h-100 kpi-card text-white position-relative" style="background:linear-gradient(135deg,#6366f1 0%,#43e97b 100%);">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="display-6 mb-2"><i class="fa fa-users"></i></div>
                    <div class="h3 mb-0 counter" data-count="42">0</div>
                    <div class="small">Aktif Kullanıcı</div>
                </div>
                <span class="position-absolute top-0 end-0 m-2 badge bg-light text-primary">+1 yeni</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow h-100 kpi-card text-white position-relative" style="background:linear-gradient(135deg,#fbbf24 0%,#f43f5e 100%);">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="display-6 mb-2"><i class="fa fa-exclamation-triangle"></i></div>
                    <div class="h3 mb-0 counter" data-count="7">0</div>
                    <div class="small">Bekleyen Arıza</div>
                </div>
                <span class="position-absolute top-0 end-0 m-2 badge bg-light text-danger">2 kritik</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow h-100 kpi-card text-white position-relative" style="background:linear-gradient(135deg,#6366f1 0%,#fbbf24 100%);">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="display-6 mb-2"><i class="fa fa-boxes-stacked"></i></div>
                    <div class="h3 mb-0 counter" data-count="3">0</div>
                    <div class="small">Kritik Stok</div>
                </div>
                <span class="position-absolute top-0 end-0 m-2 badge bg-light text-danger">Dikkat!</span>
            </div>
        </div>
    </div>
    <!-- Motivasyon ve Görev Alanı -->
    <div class="row g-3 mb-4">
        <!-- Motivasyon kartı tamamen kaldırıldı, sadece Hızlı Görev Ekle kaldı -->
        <div class="col-md-6 offset-md-3">
            <div class="card shadow h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="fw-bold fs-5">Hızlı Görev Ekle</div>
                        <input type="text" id="quickTaskInput" class="form-control form-control-sm mt-2" placeholder="Görev yaz..." onkeydown="if(event.key==='Enter'){addTask();}">
                    </div>
                    <button class="btn btn-outline-success btn-sm ms-2" onclick="addTask()"><i class="fa fa-plus"></i> Ekle</button>
                </div>
                <ul class="list-group list-group-flush" id="taskList"></ul>
            </div>
        </div>
    </div>
    <!-- Son İşlemler Tablosu -->
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card shadow h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">Son İşlemler</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Tarih</th>
                                <th>İşlem</th>
                                <th>Kullanıcı</th>
                                <th>Detay</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2024-06-20</td>
                                <td><span class="badge bg-success">Ekipman Eklendi</span></td>
                                <td>Ali Korkmaz</td>
                                <td>HP Laptop - Envantere eklendi</td>
                            </tr>
                            <tr>
                                <td>2024-06-19</td>
                                <td><span class="badge bg-warning">Arıza Bildirildi</span></td>
                                <td>Ayşe Yılmaz</td>
                                <td>Projeksiyon cihazı arızası</td>
                            </tr>
                            <tr>
                                <td>2024-06-18</td>
                                <td><span class="badge bg-info">Kullanıcı Eklendi</span></td>
                                <td>Mehmet Demir</td>
                                <td>Yeni kullanıcı kaydı</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 rounded-4 h-100 position-relative animate__animated animate__fadeIn">
                <div class="card-header bg-white border-0 rounded-top-4 d-flex align-items-center justify-content-between pb-2 pt-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fs-4 text-danger"><i class="fa fa-truck-loading"></i></span>
                        <span class="fw-bold fs-5">Tedarik Edilmesi Gereken Ürünler</span>
                    </div>
                    <button class="btn btn-outline-primary btn-sm rounded-pill px-3" title="Ürün Ekle"><i class="fa fa-plus"></i> Ekle</button>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0 align-middle table-borderless">
                        <thead>
                            <tr style="background:rgba(0,0,0,0.03);">
                                <th class="text-secondary small fw-bold py-2">Ürün Adı</th>
                                <th class="text-secondary small fw-bold py-2">Stok</th>
                                <th class="text-secondary small fw-bold py-2">Talep Eden</th>
                                <th class="text-secondary small fw-bold py-2">Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-row-hover">
                                <td class="fw-semibold">Klavye</td>
                                <td><span class="badge bg-light text-danger fw-bold px-2 py-1">0</span></td>
                                <td>Ali K.</td>
                                <td><span class="badge bg-danger bg-gradient rounded-pill px-3 py-2 fs-7">Tedarik Bekliyor</span></td>
                            </tr>
                            <tr class="table-row-hover">
                                <td class="fw-semibold">Ethernet Kablosu</td>
                                <td><span class="badge bg-warning text-dark fw-bold px-2 py-1">2</span></td>
                                <td>Ayşe Y.</td>
                                <td><span class="badge bg-warning text-dark bg-gradient rounded-pill px-3 py-2 fs-7">Az Stok</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- Boş veri için örnek ilüstrasyon ve mesaj (gerekirse göster) -->
                    <!--
                    <div class="text-center py-5">
                        <img src="https://cdn.jsdelivr.net/gh/edent/SuperTinyIcons/images/svg/box.svg" width="48" class="mb-2 opacity-50" alt="Boş">
                        <div class="text-muted">Tedarik edilmesi gereken ürün yok.</div>
                    </div>
                    -->
                </div>
            </div>
        </div>
    </div>
    <!-- Snackbar -->
    <div id="snackbar" class="position-fixed bottom-0 end-0 m-4 bg-dark text-white px-4 py-2 rounded shadow" style="display:none;z-index:9999;">Mesaj</div>
</div>
<style>
.kpi-card, .quick-action-card { transition: transform .2s cubic-bezier(.4,2,.6,1.2), box-shadow .2s; }
.kpi-card:hover, .quick-action-card:hover { transform: scale(1.04) translateY(-4px); box-shadow:0 8px 32px #6366f155; z-index:2; }
#clock { font-variant-numeric: tabular-nums; }
.quick-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none;
    min-width: 110px;
    transition: transform .18s cubic-bezier(.4,2,.6,1.2), box-shadow .18s;
}
.quick-action-btn:hover {
    transform: scale(1.08) translateY(-4px);
    z-index: 2;
    text-decoration: none;
}
.quick-action-icon {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #fff;
    margin-bottom: 8px;
    box-shadow: 0 4px 16px #6366f133;
    transition: box-shadow .18s;
}
.bg-gradient-primary { background: linear-gradient(135deg,#6366f1 0%,#43e97b 100%); }
.bg-gradient-danger { background: linear-gradient(135deg,#f43f5e 0%,#fbbf24 100%); }
.bg-gradient-success { background: linear-gradient(135deg,#43e97b 0%,#6366f1 100%); }
.bg-gradient-info { background: linear-gradient(135deg,#38bdf8 0%,#6366f1 100%); }
.bg-gradient-warning { background: linear-gradient(135deg,#fbbf24 0%,#f43f5e 100%); }
.bg-gradient-secondary { background: linear-gradient(135deg,#6c757d 0%,#6366f1 100%); }
.quick-action-label {
    font-size: .97rem;
    color: #222;
    font-weight: 500;
    text-align: center;
}
.card.rounded-4 { border-radius: 1.25rem !important; }
.card-header.rounded-top-4 { border-top-left-radius: 1.25rem !important; border-top-right-radius: 1.25rem !important; }
.table-row-hover:hover { background: #f3f4f6 !important; transition: background .18s; }
.badge.bg-danger.bg-gradient { background: linear-gradient(90deg,#f43f5e 0%,#fbbf24 100%) !important; color: #fff !important; }
.badge.bg-warning.bg-gradient { background: linear-gradient(90deg,#fbbf24 0%,#f43f5e 100%) !important; color: #222 !important; }
.fs-7 { font-size: 0.93rem; }
</style>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
// Dinamik sayaçlar
function animateCounters() {
    document.querySelectorAll('.counter').forEach(function(el) {
        let count = +el.getAttribute('data-count');
        let i = 0;
        let step = Math.ceil(count / 40);
        let interval = setInterval(function() {
            i += step;
            if(i >= count) { el.textContent = count; clearInterval(interval); }
            else { el.textContent = i; }
        }, 20);
    });
}
// Canlı saat
function updateClock() {
    const now = new Date();
    document.getElementById('clock').textContent = now.toLocaleTimeString('tr-TR');
}
setInterval(updateClock, 1000); updateClock();
// Snackbar
function showSnackbar(msg) {
    const sb = document.getElementById('snackbar');
    sb.textContent = msg;
    sb.style.display = 'block';
    setTimeout(()=>{sb.style.display='none';}, 2500);
}
// Görev ekle
function addTask() {
    const input = document.getElementById('quickTaskInput');
    const val = input.value.trim();
    if(val) {
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = val + '<button class="btn btn-sm btn-danger ms-2" onclick="this.parentNode.remove()"><i class="fa fa-trash"></i></button>';
        document.getElementById('taskList').appendChild(li);
        input.value = '';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    animateCounters();
});
// Flatpickr Takvim
if(document.getElementById('dashboardCalendar')) {
    flatpickr('#dashboardCalendar', { locale: 'tr', dateFormat: 'd.m.Y' });
}
// Bootstrap tooltip
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>
@endpush
@endsection
