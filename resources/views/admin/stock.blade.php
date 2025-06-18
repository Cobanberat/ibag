@extends('layouts.admin')

@section('content')
<style>
    .summary-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        transition: transform 0.15s;
        margin-bottom: 1.5rem;
    }
    .summary-card:hover {
        transform: translateY(-4px) scale(1.03);
        box-shadow: 0 6px 24px rgba(0,0,0,0.13);
    }
    .summary-card .card-body {
        padding: 1.5rem 1.2rem;
    }
    .summary-card i {
        opacity: 0.8;
    }
    .chart-card {
        border-radius: 1rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        padding: 1.2rem 1rem 0.5rem 1rem;
        background: #fff;
        margin-bottom: 1.5rem;
    }
    .alert-warning {
        border-radius: 0.75rem;
        font-size: 1.05em;
        box-shadow: 0 2px 8px rgba(255,193,7,0.08);
        margin-bottom: 1.5rem;
    }
    .filter-bar {
        background: #fff;
        border-radius: 0.75rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        padding: 1rem 1.2rem;
        margin-bottom: 1.2rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
    }
    .filter-bar select, .filter-bar input {
        min-width: 120px;
        border-radius: 0.5rem;
    }
    .filter-bar button {
        border-radius: 0.5rem;
    }
    /* Custom select style */
    .filter-bar select {
        background: linear-gradient(90deg, #f6f8fa 60%, #e9ecef 100%);
        border: 1.5px solid #d1d5db;
        color: #333;
        font-weight: 500;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        padding: 0.45em 2.2em 0.45em 1em;
        appearance: none;
        position: relative;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .filter-bar select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 2px #0d6efd22;
        outline: none;
    }
    .filter-bar select::-ms-expand {
        display: none;
    }
    .filter-bar select {
        background-image:
            url('data:image/svg+xml;utf8,<svg fill="%230d6efd" height="20" viewBox="0 0 20 20" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M7.293 7.293a1 1 0 011.414 0L10 8.586l1.293-1.293a1 1 0 111.414 1.414l-2 2a1 1 0 01-1.414 0l-2-2a1 1 0 010-1.414z"/></svg>');
        background-repeat: no-repeat;
        background-position: right 0.8em center;
        background-size: 1.2em;
    }
    .filter-bar input[type="text"] {
        border: 1.5px solid #d1d5db;
        background: #f6f8fa;
        font-weight: 500;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .filter-bar input[type="text"]:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 2px #0d6efd22;
        outline: none;
    }
    .table {
        border-radius: 1rem;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    .table thead th {
        background: #f6f8fa;
        font-weight: 600;
        border-bottom: 2px solid #e9ecef;
    }
    .table-hover tbody tr:hover {
        background: #f1f3f7;
        transition: background 0.2s;
    }
    .table-success {
        background: #e6f9ed !important;
    }
    .table-warning {
        background: #fffbe6 !important;
    }
    .table-danger {
        background: #ffeaea !important;
    }
    .progress {
        background: #e9ecef;
        border-radius: 0.5rem;
    }
    .badge {
        font-size: 0.98em;
        border-radius: 0.5rem;
        padding: 0.45em 0.9em;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .btn {
        border-radius: 0.5rem !important;
        font-weight: 500;
        letter-spacing: 0.01em;
    }
    .btn-info {
        color: #fff;
        background: linear-gradient(90deg, #36b3f6 0%, #007bff 100%);
        border: none;
    }
    .btn-info:hover {
        background: linear-gradient(90deg, #007bff 0%, #36b3f6 100%);
    }
    .card-footer {
        background: #f6f8fa;
        border-top: none;
        border-radius: 0 0 1rem 1rem;
    }
    .collapse.bg-light {
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .filter-bar .btn-primary {
        display: flex;
        align-items: center;
        gap: 0.5em;
        background: linear-gradient(90deg, #0d6efd 60%, #36b3f6 100%);
        border: none;
        color: #fff;
        font-weight: 600;
        box-shadow: 0 1px 4px rgba(13,110,253,0.08);
        transition: background 0.2s, box-shadow 0.2s;
    }
    .filter-bar .btn-primary:hover {
        background: linear-gradient(90deg, #36b3f6 0%, #0d6efd 100%);
        color: #fff;
        box-shadow: 0 2px 8px rgba(13,110,253,0.13);
    }
    .filter-bar .btn-primary i {
        font-size: 1.1em;
        margin-right: 0.3em;
        vertical-align: middle;
        display: inline-block;
    }
    .filter-bar .btn-add-product {
        display: flex;
        align-items: center;
        gap: 0.5em;
        background: linear-gradient(90deg, #0d6efd 0%, #36b3f6 100%);
        border: none;
        color: #fff;
        font-weight: 600;
        box-shadow: 0 2px 12px rgba(13,110,253,0.13);
        border-radius: 2em;
        padding: 0.55em 1.5em;
        font-size: 1.08em;
        letter-spacing: 0.01em;
        transition: background 0.18s, box-shadow 0.18s, transform 0.12s;
        position: relative;
        overflow: hidden;
    }
    .filter-bar .btn-add-product:hover, .filter-bar .btn-add-product:focus {
        background: linear-gradient(90deg, #36b3f6 0%, #0d6efd 100%);
        color: #fff;
        box-shadow: 0 4px 18px rgba(13,110,253,0.18);
        transform: translateY(-2px) scale(1.04);
        outline: none;
    }
    .filter-bar .btn-add-product i {
        font-size: 1.25em;
        margin-right: 0.5em;
        background: #fff;
        color: #0d6efd;
        border-radius: 50%;
        padding: 0.18em 0.22em 0.18em 0.22em;
        box-shadow: 0 1px 4px rgba(13,110,253,0.10);
        transition: background 0.18s, color 0.18s;
        display: inline-block;
    }
    .filter-bar .btn-add-product:hover i {
        background: #0d6efd;
        color: #fff;
    }
</style>
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card summary-card text-white bg-primary mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fas fa-box fa-2x me-3"></i>
                    <div>
                        <div class="h5 mb-0">15</div>
                        <small>Toplam Ürün</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card summary-card text-white bg-warning mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                    <div>
                        <div class="h5 mb-0">2</div>
                        <small>Az Stok</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card summary-card text-white bg-danger mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fas fa-times-circle fa-2x me-3"></i>
                    <div>
                        <div class="h5 mb-0">1</div>
                        <small>Tükendi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="chart-card">
            <canvas id="stockChart" height="60"></canvas>
        </div>
    </div>
</div>

<!-- Kritik stok uyarısı -->
<div class="alert alert-warning d-flex align-items-center" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    Kritik seviyenin altına düşen ürünler var! Lütfen stokları kontrol edin.
</div>

<!-- Filtreleme ve toplu işlem -->
<div class="filter-bar mb-2">
    <select class="form-select form-select-sm" style="width: 150px;">
        <option>Kategori Seç</option>
        <option>Donanım</option>
        <option>Ağ</option>
    </select>
    <select class="form-select form-select-sm" style="width: 150px;">
        <option>Durum Seç</option>
        <option>Yeterli</option>
        <option>Az Stok</option>
        <option>Tükendi</option>
    </select>
    <select class="form-select form-select-sm" style="width: 150px;">
        <option>Konum Seç</option>
        <option>Depo A</option>
        <option>Depo B</option>
        <option>Depo C</option>
    </select>
    <input type="text" class="form-control form-control-sm" style="width: 200px;" placeholder="Ürün ara...">
    <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-filter"></i> Filtrele</button>
    <button class="btn btn-sm btn-outline-success"><i class="fas fa-file-excel"></i> Excel</button>
    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-file-pdf"></i> PDF</button>
    <button class="btn btn-add-product ms-auto">
        <i class="fas fa-plus"></i> Yeni Ürün
    </button>
</div>

<form>
<div class="card mt-2" style="border-radius:1rem;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Ürün Adı</th>
                    <th>Kategori</th>
                    <th>Miktar</th>
                    <th>Kritik Seviye</th>
                    <th>Stok Durumu</th>
                    <th>Konum</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <tr class="table-success">
                    <td><input type="checkbox" name="select[]"></td>
                    <td contenteditable="true">Klavye</td>
                    <td>Donanım</td>
                    <td contenteditable="true">12</td>
                    <td contenteditable="true">5</td>
                    <td>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: 80%"></div>
                        </div>
                    </td>
                    <td>Depo A</td>
                    <td><span class="badge bg-success"><i class="fas fa-check-circle"></i> Yeterli</span></td>
                    <td>
                        <button class="btn btn-sm btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#detail1">Detay</button>
                        <button class="btn btn-sm btn-warning">Düzenle</button>
                        <button class="btn btn-sm btn-danger">Sil</button>
                    </td>
                </tr>
                <tr class="collapse bg-light" id="detail1">
                    <td colspan="9">
                        <strong>Ürün Geçmişi:</strong> 2024-03-01 giriş, 2024-03-10 çıkış, 2024-03-15 bakım<br>
                        <strong>Ek Açıklama:</strong> Son bakımda tuşlar değiştirildi.
                    </td>
                </tr>
                <tr class="table-warning">
                    <td><input type="checkbox" name="select[]"></td>
                    <td contenteditable="true">Ethernet Kablosu</td>
                    <td>Ağ</td>
                    <td contenteditable="true">3</td>
                    <td contenteditable="true">5</td>
                    <td>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: 60%"></div>
                        </div>
                    </td>
                    <td>Depo B</td>
                    <td><span class="badge bg-warning"><i class="fas fa-exclamation-triangle"></i> Az Stok</span></td>
                    <td>
                        <button class="btn btn-sm btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#detail2">Detay</button>
                        <button class="btn btn-sm btn-warning">Düzenle</button>
                        <button class="btn btn-sm btn-danger">Sil</button>
                    </td>
                </tr>
                <tr class="collapse bg-light" id="detail2">
                    <td colspan="9">
                        <strong>Ürün Geçmişi:</strong> 2024-02-20 giriş, 2024-03-05 çıkış<br>
                        <strong>Ek Açıklama:</strong> Kablo başı değiştirildi.
                    </td>
                </tr>
                <tr class="table-danger">
                    <td><input type="checkbox" name="select[]"></td>
                    <td contenteditable="true">Monitör</td>
                    <td>Donanım</td>
                    <td contenteditable="true">0</td>
                    <td contenteditable="true">2</td>
                    <td>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-danger" style="width: 0%"></div>
                        </div>
                    </td>
                    <td>Depo C</td>
                    <td><span class="badge bg-danger"><i class="fas fa-times-circle"></i> Tükendi</span></td>
                    <td>
                        <button class="btn btn-sm btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#detail3">Detay</button>
                        <button class="btn btn-sm btn-warning">Düzenle</button>
                        <button class="btn btn-sm btn-danger">Sil</button>
                    </td>
                </tr>
                <tr class="collapse bg-light" id="detail3">
                    <td colspan="9">
                        <strong>Ürün Geçmişi:</strong> 2024-01-10 giriş, 2024-02-01 çıkış<br>
                        <strong>Ek Açıklama:</strong> Panel arızası nedeniyle stokta yok.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <button class="btn btn-danger btn-sm" type="button"><i class="fas fa-trash"></i> Seçili Ürünleri Sil</button>
    </div>
</div>
</form>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart.js mini pasta grafik
const ctx = document.getElementById('stockChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Yeterli', 'Az Stok', 'Tükendi'],
        datasets: [{
            data: [12, 2, 1],
            backgroundColor: ['#198754', '#ffc107', '#dc3545'],
            borderWidth: 1
        }]
    },
    options: {
        plugins: {
            legend: { display: false }
        },
        cutout: '70%'
    }
});
// Toplu seçim
const selectAll = document.getElementById('selectAll');
if(selectAll) {
    selectAll.addEventListener('change', function() {
        document.querySelectorAll('input[name="select[]"]').forEach(cb => cb.checked = this.checked);
    });
}
</script>
@endpush