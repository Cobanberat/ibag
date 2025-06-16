@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3"><strong>Yönetim Paneli</strong> Ana Sayfa</h1>

    <div class="row">
        <div class="col-xl-6 col-xxl-5 d-flex">
            <div class="w-100">
                <div class="row">
                    <!-- Hızlı İşlemler -->
                    <div class="col-sm-6">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <h5 class="card-title text-white">Yeni Ekipman Ekle</h5>
                                <p class="card-text">Envantere yeni bir ekipman kaydedin.</p>
                                <a href="" class="btn btn-light btn-sm">
                                    <i data-feather="plus-circle" class="me-1"></i> Ekle
                                </a>
                            </div>
                        </div>
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h5 class="card-title text-white">Arıza Bildir</h5>
                                <p class="card-text">Ekipman arızasını hızlıca bildir.</p>
                                <a href="" class="btn btn-light btn-sm">
                                    <i data-feather="alert-triangle" class="me-1"></i> Bildir
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <h5 class="card-title text-white">Konum Takibi</h5>
                                <p class="card-text">Ekipmanların güncel konumunu görüntüleyin.</p>
                                <a href="" class="btn btn-light btn-sm">
                                    <i data-feather="map-pin" class="me-1"></i> Takip Et
                                </a>
                            </div>
                        </div>
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <h5 class="card-title text-white">Kullanıcı Yönetimi</h5>
                                <p class="card-text">Üyeleri yönetin veya yeni kullanıcı ekleyin.</p>
                                <a href="" class="btn btn-light btn-sm">
                                    <i data-feather="users" class="me-1"></i> Yönet
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Bitiş -->
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-xxl-7">
            <div class="card flex-fill w-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Son Hareketler</h5>
                </div>
                <div class="card-body py-3">
                    <div class="chart chart-sm">
                        <canvas id="chartjs-dashboard-line"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alt Panel -->
    <div class="row mt-3">
        <div class="col-12 col-md-6 col-xxl-4 d-flex">
            <div class="card flex-fill w-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tarayıcı Kullanımı</h5>
                </div>
                <div class="card-body d-flex">
                    <div class="align-self-center w-100">
                        <div class="py-3 chart chart-xs">
                            <canvas id="chartjs-dashboard-pie"></canvas>
                        </div>
                        <table class="table mb-0">
                            <tbody>
                                <tr><td>Chrome</td><td class="text-end">4306</td></tr>
                                <tr><td>Firefox</td><td class="text-end">3801</td></tr>
                                <tr><td>Edge</td><td class="text-end">1689</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- YENİ: Tedarik Edilmesi Gereken Ürünler -->
        <div class="col-12 col-md-6 col-xxl-8 d-flex">
            <div class="card flex-fill w-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">Tedarik Edilmesi Gereken Ürünler</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Ürün Adı</th>
                                <th>Stok</th>
                                <th>Talep Eden</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Klavye</td>
                                <td>0</td>
                                <td>Ali K.</td>
                                <td><span class="badge bg-danger">Tedarik Bekliyor</span></td>
                            </tr>
                            <tr>
                                <td>Ethernet Kablosu</td>
                                <td>2</td>
                                <td>Ayşe Y.</td>
                                <td><span class="badge bg-warning">Az Stok</span></td>
                            </tr>
                            <!-- Daha fazla satır eklenebilir -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Projeler ve Aylık Satış -->
    <div class="row mt-3">
        <div class="col-12 col-lg-8 col-xxl-9 d-flex">
            <div class="card flex-fill">
                <div class="card-header">
                    <h5 class="card-title mb-0">Son Projeler</h5>
                </div>
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>Proje Adı</th>
                            <th class="d-none d-xl-table-cell">Başlangıç</th>
                            <th class="d-none d-xl-table-cell">Bitiş</th>
                            <th>Durum</th>
                            <th class="d-none d-md-table-cell">Sorumlu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>IBAG Envanter Sistemi</td>
                            <td class="d-none d-xl-table-cell">01/06/2025</td>
                            <td class="d-none d-xl-table-cell">30/06/2025</td>
                            <td><span class="badge bg-warning">Devam ediyor</span></td>
                            <td class="d-none d-md-table-cell">lazBerat</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-12 col-lg-4 col-xxl-3 d-flex">
            <div class="card flex-fill w-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aylık Satış</h5>
                </div>
                <div class="card-body d-flex w-100">
                    <div class="align-self-center chart chart-lg">
                        <canvas id="chartjs-dashboard-bar"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
