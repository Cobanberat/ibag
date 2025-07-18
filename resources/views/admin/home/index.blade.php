@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    @include('admin.partials.breadcrumb', ['pageTitle' => 'Yönetim Paneli'])
    <!-- Üst Bar: Kullanıcı Kartı, Yardım, Duyuru, Hava Durumu, Bildirimler -->
   @vite('resources/css/home.css')
   @vite('resources/js/home.js')

    <div class="row g-3 mb-4">
        <!-- Hızlı İşlemler (Sol) -->
        <div class="col-md-12">
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
                  
                    <a href="{{route('admin.users')}}" class="quick-action-btn" data-bs-toggle="tooltip" title="Kullanıcıları yönet">
                        <div class="quick-action-icon bg-gradient-info"><i class="fa fa-users-cog"></i></div>
                        <div class="quick-action-label">Kullanıcı Yönetimi</div>
                    </a>
                    <a href="/admin/stock" class="quick-action-btn" data-bs-toggle="tooltip" title="Stokları kontrol et">
                        <div class="quick-action-icon bg-gradient-warning"><i class="fa fa-boxes-stacked"></i></div>
                        <div class="quick-action-label">Stok Kontrol</div>
                    </a>
                    
                </div>
            </div>
        </div>
  
    </div>
  
    <!-- Gradientli başlık ve hoşgeldin -->
   
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
      
    </div>
    <!-- Son İşlemler Tablosu -->
    <div class="row g-3 mb-4">
        <div class="col-12 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">Son İşlemler</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 table-lg">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tarih</th>
                                <th>İşlem</th>
                                <th>Kullanıcı</th>
                                <th>Detay</th>
                                <th>Açıklama</th>
                                <th>İşlem Tipi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>2024-06-20</td>
                                <td><span class="badge bg-success">Ekipman Eklendi</span></td>
                                <td>Ali Korkmaz</td>
                                <td>HP Laptop - Envantere eklendi</td>
                                <td>Yeni ekipman kaydı</td>
                                <td>Donanım</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>2024-06-19</td>
                                <td><span class="badge bg-warning">Arıza Bildirildi</span></td>
                                <td>Ayşe Yılmaz</td>
                                <td>Projeksiyon cihazı arızası</td>
                                <td>Arıza bildirimi</td>
                                <td>Bakım</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>2024-06-18</td>
                                <td><span class="badge bg-info">Kullanıcı Eklendi</span></td>
                                <td>Mehmet Demir</td>
                                <td>Yeni kullanıcı kaydı</td>
                                <td>Kullanıcı ekleme</td>
                                <td>Yönetim</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 mb-4">
            <div class="card shadow h-100">
                 <div class="d-flex align-items-center gap-2 p-1">
                        <span class="fs-6 text-danger"><i class="fa fa-truck-loading"></i></span>
                        <span class="fw-bold" style="font-size:1.05rem;">Tedarik Edilmesi Gereken Ürünler</span>
                    </div>
                <div class="card-body p-1">
                   <table class="table table-sm mb-0 align-middle tedarik-table table-lg">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ürün Adı</th>
                                <th>Stok</th>
                                <th>Kritik Seviye</th>
                                <th>Durum</th>
                                <th>Tarih</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-row-hover">
                                <td>1</td>
                                <td>Klavye</td>
                                <td><span>0</span></td>
                                <td>5</td>
                                <td><span class="badge bg-info">Tedarik Bekliyor</span></td>
                                <td>2024-06-20</td>
                            </tr>
                            <tr class="table-row-hover">
                                <td>2</td>
                                <td>Ethernet Kablosu</td>
                                <td>2</td>
                                <td>10</td>
                                <td><span span class="badge bg-danger">Az Stok</span></td>
                                <td>2024-06-19</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
     
    </div>
    <div id="snackbar" class="position-fixed bottom-0 end-0 m-4 bg-dark text-white px-4 py-2 rounded shadow" style="display:none;z-index:9999;">Mesaj</div>
</div>


@endsection

<style>
/* Tedarik Edilmesi Gereken Ürünler tablosu için kompakt görünüm */
.tedarik-table th, .tedarik-table td { padding: 0.18em 0.35em !important; font-size: 0.89em; height: 24px; vertical-align: middle; }
.tedarik-table thead th { background: #f3f6fa; }
.tedarik-table tbody tr { transition: background 0.12s; }
.tedarik-table tbody tr:hover { background: #f1f5fb; }
</style>
