@extends('layouts.admin')
@section('content')
<style>
    .feature-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .feature-header h4 {
        font-weight: 700;
        margin-bottom: 0;
    }

    .filter-bar {
        background: #fff;
        border-radius: 0.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        padding: 1rem 1.2rem;
        margin-bottom: 1.2rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
    }

        .filter-bar select,
        .filter-bar input {
        min-width: 120px;
        border-radius: 0.5rem;
    }

    .filter-bar button {
        border-radius: 0.5rem;
    }

    .filter-bar select {
        background: linear-gradient(90deg, #f6f8fa 60%, #e9ecef 100%);
        border: 1.5px solid #d1d5db;
        color: #333;
        font-weight: 500;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        padding: 0.45em 2.2em 0.45em 1em;
        appearance: none;
        position: relative;
        transition: border-color 0.2s, box-shadow 0.2s;
        background-image:
            url('data:image/svg+xml;utf8,<svg fill="%230d6efd" height="20" viewBox="0 0 20 20" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M7.293 7.293a1 1 0 011.414 0L10 8.586l1.293-1.293a1 1 0 111.414 1.414l-2 2a1 1 0 01-1.414 0l-2-2a1 1 0 010-1.414z"/></svg>');
        background-repeat: no-repeat;
        background-position: right 0.8em center;
        background-size: 1.2em;
    }

    .filter-bar select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 2px #0d6efd22;
        outline: none;
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

    .feature-table {
        border-radius: 1rem;
        overflow: hidden;
        background: #fff;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        margin-top: 0.5rem;
    }

    .feature-table thead th {
        background: #f6f8fa;
        font-weight: 600;
        border-bottom: 2px solid #e9ecef;
        vertical-align: middle;
    }

    .feature-table tbody tr:hover {
        background: #f1f3f7;
        transition: background 0.2s;
    }

    .feature-table td[contenteditable] {
        cursor: pointer;
        background: #f8fafc;
        transition: background 0.2s;
    }

    .feature-table td[contenteditable]:focus {
        outline: 2px solid #0d6efd;
        background: #e7f1ff;
    }

    .badge {
        font-size: 0.98em;
        border-radius: 0.5rem;
        padding: 0.45em 0.9em;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
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

    .pagination {
        justify-content: flex-end;
        margin-top: 1.2rem;
    }

    .pagination .page-link {
        border-radius: 0.5rem !important;
        margin: 0 0.15em;
        color: #0d6efd;
        font-weight: 500;
    }

    .pagination .active .page-link {
        background: linear-gradient(90deg, #0d6efd 0%, #36b3f6 100%);
        color: #fff;
        border: none;
    }

    .feature-suggestion {
        background: #f6f8fa;
        border-radius: 0.75rem;
        padding: 1.2rem 1.5rem;
        margin-top: 2.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .feature-suggestion h6 {
        font-weight: 700;
        color: #0d6efd;
        margin-bottom: 0.7rem;
    }

    .feature-suggestion ul {
        margin-bottom: 0;
        padding-left: 1.2em;
    }

    .feature-suggestion li {
        margin-bottom: 0.3em;
    }

    .info-box {
        background: #e7f1ff;
        border-left: 5px solid #0d6efd;
        border-radius: 0.75rem;
        padding: 1.2rem 1.5rem;
        margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .info-box i {
        font-size: 2.2rem;
        color: #0d6efd;
    }

    .summary-cards {
        display: flex;
        gap: 1.2rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .summary-card {
        background: #fff;
        border-radius: 0.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        padding: 1.2rem 2rem 1.2rem 1.2rem;
        min-width: 200px;
        flex: 1 1 200px;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .summary-card .icon {
        font-size: 2rem;
        color: #0d6efd;
        background: #e7f1ff;
        border-radius: 50%;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .summary-card .value {
        font-size: 1.6rem;
        font-weight: 700;
        color: #222;
    }

    .summary-card .label {
        font-size: 1rem;
        color: #666;
    }

    .bulk-actions-panel {
        background: #f6f8fa;
        border-radius: 0.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        padding: 0.8rem 1.2rem;
        margin-bottom: 1.2rem;
        display: flex;
        gap: 0.7rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .bulk-actions-panel .btn {
        min-width: 120px;
    }

        .btn-add-feature {
            background: linear-gradient(90deg, #0d6efd 0%, #36b3f6 100%);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 2px 12px #0d6efd22;
            border-radius: 2em;
            padding: 0.65em 1.7em;
            font-size: 1.12em;
            letter-spacing: 0.01em;
            transition: background 0.18s, box-shadow 0.18s, transform 0.12s;
            position: relative;
            overflow: hidden;
            border: none;
        }

        .btn-add-feature:hover,
        .btn-add-feature:focus {
            background: linear-gradient(90deg, #43a1f7 0%, #0d6efd 100%);
            color: #fff;
            box-shadow: 0 4px 18px #0d6efd33;
            transform: translateY(-2px) scale(1.04);
            outline: none;
        }

        .feature-table select[data-field='required'],
        #featureRequired {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
            font-size: 0.98em;
            border-radius: 0.5rem;
            padding: 0.25em 0.2em;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        }
        .feature-active-select{
          border: none !important;
            outline: none !important;
            box-shadow: none !important;
            font-size: 0.98em;
            border-radius: 0.5rem;
            padding: 0.25em 0.2em;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        .feature-table select[data-field='required'].evet {
            background: #1cbb8c !important;
            color: white !important;
        }

        .feature-table select[data-field='required'].hayir {
            background: #dc3545 !important;
            color: white !important;
        }

        .feature-active-select.aktif {
            background: #1cbb8c !important;
            color: #fff !important;
        }
        .feature-active-select.pasif {
            background: #6c757d !important;
            color: #fff !important;
        }
</style>
<div class="feature-header">
    <h4 class="fw-bold">Ekipman Özellikleri</h4>
        <button class="btn btn-add-feature" data-bs-toggle="modal" data-bs-target="#addFeatureModal">
        <i class="fas fa-plus"></i> Yeni Özellik
    </button>
</div>
<!-- 1. Bilgilendirme Kutusu -->
<div class="info-box mb-3">
    <i class="fas fa-info-circle"></i>
    <div>
            <strong>Ekipman Özellikleri</strong> sayfasında, sistemdeki tüm ekipmanlara ait teknik ve fiziksel özellikleri
            yönetebilirsiniz. Özellik ekleyebilir, düzenleyebilir, filtreleyebilir ve raporlayabilirsiniz. <span
                class="text-primary">Daha fazla bilgi için <a href="#" data-bs-toggle="modal"
                    data-bs-target="#helpModal">Yardım</a> bölümüne bakın.</span>
        </div>
</div>
<!-- 2. Özet Kartlar -->
<div class="summary-cards mb-3">
    <div class="summary-card">
        <div class="icon"><i class="fas fa-list"></i></div>
        <div>
            <div class="value">12</div>
            <div class="label">Toplam Özellik</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="icon"><i class="fas fa-check-circle text-success"></i></div>
        <div>
            <div class="value">9</div>
            <div class="label">Aktif Özellik</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="icon"><i class="fas fa-star text-warning"></i></div>
        <div>
            <div class="value">Motor Gücü</div>
            <div class="label">En Çok Kullanılan</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="icon"><i class="fas fa-plus-circle text-primary"></i></div>
        <div>
            <div class="value">Renk</div>
            <div class="label">Son Eklenen</div>
        </div>
    </div>
</div>
<!-- 3. Toplu İşlemler Paneli -->
<div class="bulk-actions-panel mb-3">
    <span class="fw-bold me-2"><i class="fas fa-tasks"></i> Toplu İşlemler:</span>
    <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash-alt"></i> Sil</button>
    <button class="btn btn-outline-success btn-sm"><i class="fas fa-toggle-on"></i> Aktif Yap</button>
    <button class="btn btn-outline-secondary btn-sm"><i class="fas fa-toggle-off"></i> Pasif Yap</button>
    <button class="btn btn-outline-primary btn-sm"><i class="fas fa-exchange-alt"></i> Kategori Değiştir</button>
    <button class="btn btn-outline-info btn-sm"><i class="fas fa-layer-group"></i> Grup Değiştir</button>
    <button class="btn btn-outline-dark btn-sm"><i class="fas fa-file-export"></i> Dışa Aktar</button>
</div>
<div class="filter-bar mb-2">
    <select class="form-select form-select-sm" style="width: 180px;">
        <option>Kategori Seç</option>
        <option>Jeneratör</option>
        <option>Kırıcı</option>
        <option>Kask</option>
        <option>UPS</option>
        <option>Priz</option>
        <option>Akü</option>
        <!-- Diğer kategoriler -->
    </select>
    <input type="text" class="form-control form-control-sm" style="width: 200px;" placeholder="Özellik ara...">
    <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-search"></i> Ara</button>
    <button class="btn btn-sm btn-outline-success"><i class="fas fa-file-excel"></i> Excel</button>
    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-file-pdf"></i> PDF</button>
</div>
<table class="table feature-table">
    <thead>
        <tr>
            <th><input type="checkbox" id="selectAll"></th>
            <th>Özellik Adı</th>
            <th>Kategori</th>
            <th>Tip</th>
            <th>Birim</th>
            <th>Zorunlu</th>
            <th>Varsayılan</th>
            <th>Min/Max</th>
            <th>Seçenekler</th>
            <th>Grup</th>
            <th>Aktif</th>
            <th>İşlemler</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><input type="checkbox" name="select[]"></td>
            <td class="editable" data-field="name">Motor Gücü</td>
            <td class="editable" data-field="category">Jeneratör</td>
            <td class="editable" data-field="type">Sayı</td>
            <td class="editable" data-field="unit">kW</td>
            <td class="editable" data-field="required"><span class="badge bg-success">Evet</span></td>
            <td class="editable" data-field="default">5</td>
            <td class="editable" data-field="minmax">1 / 100</td>
            <td class="editable" data-field="options">-</td>
            <td class="editable" data-field="group">Teknik</td>
            <td class="editable" data-field="active"><span class="badge bg-success">Aktif</span></td>
            <td>
                <button class="btn btn-sm btn-warning">Düzenle</button>
                <button class="btn btn-sm btn-danger">Sil</button>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox" name="select[]"></td>
            <td class="editable" data-field="name">Ağırlık</td>
            <td class="editable" data-field="category">Kırıcı</td>
            <td class="editable" data-field="type">Sayı</td>
            <td class="editable" data-field="unit">kg</td>
            <td class="editable" data-field="required"><span class="badge bg-danger">Hayır</span></td>
            <td class="editable" data-field="default">-</td>
            <td class="editable" data-field="minmax">0 / 500</td>
            <td class="editable" data-field="options">-</td>
            <td class="editable" data-field="group">Fiziksel</td>
            <td class="editable" data-field="active"><span class="badge bg-success">Aktif</span></td>
            <td>
                <button class="btn btn-sm btn-warning">Düzenle</button>
                <button class="btn btn-sm btn-danger">Sil</button>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox" name="select[]"></td>
            <td class="editable" data-field="name">Çalışma Modu</td>
            <td class="editable" data-field="category">Jeneratör</td>
            <td class="editable" data-field="type">Seçim</td>
            <td class="editable" data-field="unit">-</td>
            <td class="editable" data-field="required"><span class="badge bg-success">Evet</span></td>
            <td class="editable" data-field="default">Dizel</td>
            <td class="editable" data-field="minmax">-</td>
            <td class="editable" data-field="options">Dizel,Benzin</td>
            <td class="editable" data-field="group">Teknik</td>
            <td class="editable" data-field="active"><span class="badge bg-success">Aktif</span></td>
            <td>
                <button class="btn btn-sm btn-warning">Düzenle</button>
                <button class="btn btn-sm btn-danger">Sil</button>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox" name="select[]"></td>
            <td class="editable" data-field="name">Üretim Yılı</td>
            <td class="editable" data-field="category">Kask</td>
            <td class="editable" data-field="type">Tarih</td>
            <td class="editable" data-field="unit">-</td>
            <td class="editable" data-field="required"><span class="badge bg-danger">Hayır</span></td>
            <td class="editable" data-field="default">2022</td>
            <td class="editable" data-field="minmax">2010 / 2024</td>
            <td class="editable" data-field="options">-</td>
            <td class="editable" data-field="group">Fiziksel</td>
            <td class="editable" data-field="active"><span class="badge bg-success">Aktif</span></td>
            <td>
                <button class="btn btn-sm btn-warning">Düzenle</button>
                <button class="btn btn-sm btn-danger">Sil</button>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox" name="select[]"></td>
            <td class="editable" data-field="name">Garanti Durumu</td>
            <td class="editable" data-field="category">Jeneratör</td>
            <td class="editable" data-field="type">Boolean</td>
            <td class="editable" data-field="unit">-</td>
            <td class="editable" data-field="required"><span class="badge bg-success">Evet</span></td>
            <td class="editable" data-field="default">Var</td>
            <td class="editable" data-field="minmax">-</td>
            <td class="editable" data-field="options">Var, Yok</td>
            <td class="editable" data-field="group">Teknik</td>
            <td class="editable" data-field="active"><span class="badge bg-success">Aktif</span></td>
            <td>
                <button class="btn btn-sm btn-warning">Düzenle</button>
                <button class="btn btn-sm btn-danger">Sil</button>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox" name="select[]"></td>
            <td class="editable" data-field="name">Akü Kapasitesi</td>
            <td class="editable" data-field="category">Akü</td>
            <td class="editable" data-field="type">Sayı</td>
            <td class="editable" data-field="unit">Ah</td>
            <td class="editable" data-field="required"><span class="badge bg-success">Evet</span></td>
            <td class="editable" data-field="default">100</td>
            <td class="editable" data-field="minmax">50 / 200</td>
            <td class="editable" data-field="options">-</td>
            <td class="editable" data-field="group">Elektriksel</td>
            <td class="editable" data-field="active"><span class="badge bg-success">Aktif</span></td>
            <td>
                <button class="btn btn-sm btn-warning">Düzenle</button>
                <button class="btn btn-sm btn-danger">Sil</button>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox" name="select[]"></td>
            <td class="editable" data-field="name">Priz Tipi</td>
            <td class="editable" data-field="category">Priz</td>
            <td class="editable" data-field="type">Seçim</td>
            <td class="editable" data-field="unit">-</td>
            <td class="editable" data-field="required"><span class="badge bg-danger">Hayır</span></td>
            <td class="editable" data-field="default">Schuko</td>
            <td class="editable" data-field="minmax">-</td>
            <td class="editable" data-field="options">Schuko,IEC</td>
            <td class="editable" data-field="group">Elektriksel</td>
            <td class="editable" data-field="active"><span class="badge bg-success">Aktif</span></td>
            <td>
                <button class="btn btn-sm btn-warning">Düzenle</button>
                <button class="btn btn-sm btn-danger">Sil</button>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox" name="select[]"></td>
            <td class="editable" data-field="name">Voltaj</td>
            <td class="editable" data-field="category">UPS</td>
            <td class="editable" data-field="type">Sayı</td>
            <td class="editable" data-field="unit">V</td>
            <td class="editable" data-field="required"><span class="badge bg-success">Evet</span></td>
            <td class="editable" data-field="default">220</td>
            <td class="editable" data-field="minmax">110 / 240</td>
            <td class="editable" data-field="options">-</td>
            <td class="editable" data-field="group">Elektriksel</td>
            <td class="editable" data-field="active"><span class="badge bg-success">Aktif</span></td>
            <td>
                <button class="btn btn-sm btn-warning">Düzenle</button>
                <button class="btn btn-sm btn-danger">Sil</button>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox" name="select[]"></td>
                <td class="editable" data-field="name" title="Düzenlemek için tıkla">Renk <button
                        class="btn btn-sm btn-light ms-1" data-bs-toggle="tooltip" title="Ekipmanın rengi"><i
                            class="fas fa-info-circle"></i></button></td>
            <td class="editable" data-field="category">Kask</td>
            <td class="editable" data-field="type">Metin</td>
            <td class="editable" data-field="unit">-</td>
            <td class="editable" data-field="required"><span class="badge bg-danger">Hayır</span></td>
            <td class="editable" data-field="default">Sarı</td>
            <td class="editable" data-field="minmax">-</td>
            <td class="editable" data-field="options">Kırmızı,Mavi,Sarı</td>
            <td class="editable" data-field="group">Fiziksel</td>
                <td class="editable" data-field="active">
                    <div class="form-check form-switch d-inline"><input class="form-check-input" type="checkbox" checked>
                    </div>
                </td>
            <td>
                <button class="btn btn-sm btn-warning">Düzenle</button>
                <button class="btn btn-sm btn-danger">Sil</button>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox" name="select[]"></td>
                <td class="editable" data-field="name" title="Düzenlemek için tıkla">Seri No <button
                        class="btn btn-sm btn-light ms-1" data-bs-toggle="tooltip" title="Seri numarası"><i
                            class="fas fa-info-circle"></i></button></td>
            <td class="editable" data-field="category">Jeneratör</td>
            <td class="editable" data-field="type">Metin</td>
            <td class="editable" data-field="unit">-</td>
            <td class="editable" data-field="required"><span class="badge bg-success">Evet</span></td>
            <td class="editable" data-field="minmax">-</td>
            <td class="editable" data-field="minmax">-</td>
            <td class="editable" data-field="options">-</td>
            <td class="editable" data-field="group">Teknik</td>
                <td class="editable" data-field="active">
                    <div class="form-check form-switch d-inline"><input class="form-check-input" type="checkbox" checked>
                    </div>
                </td>
            <td>
                <button class="btn btn-sm btn-warning">Düzenle</button>
                <button class="btn btn-sm btn-danger">Sil</button>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox" name="select[]"></td>
                <td class="editable" data-field="name" title="Düzenlemek için tıkla">Kullanım Süresi <button
                        class="btn btn-sm btn-light ms-1" data-bs-toggle="tooltip"
                        title="Toplam kullanım süresi (saat)"><i class="fas fa-info-circle"></i></button></td>
            <td class="editable" data-field="category">Jeneratör</td>
            <td class="editable" data-field="type">Sayı</td>
            <td class="editable" data-field="unit">saat</td>
            <td class="editable" data-field="required"><span class="badge bg-success">Evet</span></td>
            <td class="editable" data-field="minmax">0</td>
            <td class="editable" data-field="minmax">0 / 10000</td>
            <td class="editable" data-field="options">-</td>
            <td class="editable" data-field="group">Teknik</td>
                <td class="editable" data-field="active">
                    <div class="form-check form-switch d-inline"><input class="form-check-input" type="checkbox" checked>
                    </div>
                </td>
            <td>
                <button class="btn btn-sm btn-warning">Düzenle</button>
                <button class="btn btn-sm btn-danger">Sil</button>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox" name="select[]"></td>
                <td class="editable" data-field="name" title="Düzenlemek için tıkla">IP Koruma Sınıfı <button
                        class="btn btn-sm btn-light ms-1" data-bs-toggle="tooltip"
                        title="Toz ve suya karşı koruma seviyesi"><i class="fas fa-info-circle"></i></button></td>
            <td class="editable" data-field="category">UPS</td>
            <td class="editable" data-field="type">Seçim</td>
            <td class="editable" data-field="unit">-</td>
            <td class="editable" data-field="required"><span class="badge bg-danger">Hayır</span></td>
            <td class="editable" data-field="minmax">IP54</td>
            <td class="editable" data-field="minmax">IP20 / IP67</td>
            <td class="editable" data-field="options">IP20,IP54,IP67</td>
            <td class="editable" data-field="group">Teknik</td>
                <td class="editable" data-field="active">
                    <div class="form-check form-switch d-inline"><input class="form-check-input" type="checkbox"></div>
                </td>
            <td>
                <button class="btn btn-sm btn-warning">Düzenle</button>
                <button class="btn btn-sm btn-danger">Sil</button>
            </td>
        </tr>
        <!-- Diğer özellikler -->
    </tbody>
</table>
<div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
    <button class="btn btn-danger btn-sm">
        <i class="fas fa-trash-alt me-1"></i> Seçili Özellikleri Sil
    </button>
    <nav aria-label="Sayfalama">
        <ul class="pagination mb-0">
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true" aria-label="Önceki">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li class="page-item active" aria-current="page">
                <span class="page-link">1</span>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">2</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">3</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Sonraki">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
<div class="feature-suggestion mt-4">
    <h6>Daha Fazla Özellik ve Kullanım Kolaylığı İçin:</h6>
    <ul>
        <li>Satır içi düzenleme (hücreye tıklayınca hızlıca değişiklik yapma)</li>
        <li>Toplu seçim ve toplu silme/düzenleme (checkbox ile)</li>
        <li>Özellik sıralama (drag & drop ile sürükle-bırak)</li>
        <li>Her özelliğe özel ikon veya renk atama</li>
        <li>Özellik geçmişi ve değişiklik logu</li>
        <li>Raporlama ve grafiksel analiz (hangi özellikler en çok kullanılıyor?)</li>
        <li>Export/Import (Excel, CSV, JSON)</li>
        <li>Detaylı açıklama ve dokümantasyon alanı</li>
        <li>Mobil uyumlu ve responsive tasarım</li>
        <li>Kolay filtreleme: Aktif/Pasif, grup, tip, zorunlu/opsiyonel</li>
        <li>Hızlı ekleme: Modal yerine satır ekleme</li>
        <li>Arama kutusunda otomatik tamamlama (autocomplete)</li>
        <li>Tabloyu sütuna göre sıralama (tıklayınca artan/azalan)</li>
        <li>Her özelliğin yanında bilgi (tooltip) butonu</li>
    </ul>
</div>
<!-- Özellik Ekle Modal -->
    <div class="modal fade" id="addFeatureModal" tabindex="-1" aria-labelledby="addFeatureModalLabel"
        aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addFeatureModalLabel"><i class="fas fa-plus-circle me-2"></i>Yeni Özellik
                        Ekle</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="featureName" class="form-label">Özellik Adı</label>
            <input type="text" class="form-control" id="featureName">
          </div>
          <div class="mb-3">
            <label for="featureCategory" class="form-label">Kategori</label>
            <select class="form-select" id="featureCategory">
                <option>Jeneratör</option>
                <option>Kırıcı</option>
                <option>Kask</option>
                <option>UPS</option>
                <option>Priz</option>
                <option>Akü</option>
                <!-- Diğer kategoriler -->
            </select>
          </div>
          <div class="mb-3">
            <label for="featureType" class="form-label">Tip</label>
            <select class="form-select" id="featureType">
                <option>Sayı</option>
                <option>Metin</option>
                <option>Seçim</option>
                <option>Tarih</option>
                <option>Boolean</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="featureUnit" class="form-label">Birim</label>
            <input type="text" class="form-control" id="featureUnit" placeholder="Örn: kW, kg, adet">
          </div>
          <div class="mb-3">
            <label for="featureRequired" class="form-label">Zorunlu mu?</label>
                            <select class="form-select" style="border:none !important" id="featureRequired">
                <option>Evet</option>
                <option>Hayır</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="featureDefault" class="form-label">Varsayılan Değer</label>
            <input type="text" class="form-control" id="featureDefault">
          </div>
          <div class="mb-3">
            <label for="featureMin" class="form-label">Minimum Değer</label>
            <input type="text" class="form-control" id="featureMin">
          </div>
          <div class="mb-3">
            <label for="featureMax" class="form-label">Maksimum Değer</label>
            <input type="text" class="form-control" id="featureMax">
          </div>
          <div class="mb-3">
            <label for="featureOptions" class="form-label">Seçenekler (virgülle ayır)</label>
                            <input type="text" class="form-control" id="featureOptions"
                                placeholder="Örn: Dizel,Benzin">
          </div>
          <div class="mb-3">
            <label for="featureGroup" class="form-label">Grup</label>
                            <input type="text" class="form-control" id="featureGroup"
                                placeholder="Teknik, Fiziksel, vb.">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
        <button type="button" class="btn btn-primary">Kaydet</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script>
// Toplu seçim checkbox
const selectAll = document.getElementById('selectAll');
        if (selectAll) {
    selectAll.addEventListener('change', function() {
        document.querySelectorAll('input[name="select[]"]')
            .forEach(cb => cb.checked = this.checked);
    });
}
// Inline edit: hücreye tıklayınca input açılır, enter veya blur ile kaydedilir
document.querySelectorAll('.feature-table td.editable').forEach(cell => {
    cell.addEventListener('dblclick', function(e) {
                if (cell.querySelector('input,select')) return;
        let field = cell.getAttribute('data-field');
        let text = cell.innerText.trim();
        let input;
                if (field === 'type') {
            input = document.createElement('select');
                    ['Sayı', 'Metin', 'Seçim', 'Tarih', 'Boolean'].forEach(opt => {
                        let o = document.createElement('option');
                        o.value = opt;
                        o.text = opt;
                        if (opt === text) o.selected = true;
                        input.appendChild(o);
                    });
                } else if (field === 'required') {
            input = document.createElement('select');
                    ['Evet', 'Hayır'].forEach(opt => {
                        let o = document.createElement('option');
                        o.value = opt;
                        o.text = opt;
                        if (text.includes(opt)) o.selected = true;
                        input.appendChild(o);
                    });
                    input.setAttribute('data-field', 'required');
                    input.classList.add('feature-required-select');
                    input.className += text.includes('Evet') ? ' evet' : ' hayir';
                    input.onchange = function() {
                        input.className = 'feature-required-select ' + (input.value === 'Evet' ? 'evet' : 'hayir');
                    };
                } else if (field === 'active') {
            input = document.createElement('select');
                    ['Aktif', 'Pasif'].forEach(opt => {
                        let o = document.createElement('option');
                        o.value = opt;
                        o.text = opt;
                        if (text.includes(opt)) o.selected = true;
                        input.appendChild(o);
                    });
                    input.setAttribute('data-field', 'active');
                    input.classList.add('feature-active-select');
                    input.classList.add(text.includes('Aktif') ? 'aktif' : 'pasif');
                    input.onchange = function() {
                        input.classList.remove('aktif', 'pasif');
                        input.classList.add(input.value === 'Aktif' ? 'aktif' : 'pasif');
                    };
        } else {
                    input = document.createElement('input');
                    input.type = 'text';
                    input.value = text;
                    input.className = 'form-control form-control-sm d-inline w-auto';
        }
        input.style.maxWidth = '120px';
        let oldHtml = cell.innerHTML;
        cell.innerHTML = '';
        cell.appendChild(input);
        input.focus();

        function saveEdit() {
            let val = input.value;
                    if (field === 'required') {
                        cell.innerHTML =
                            `<span class="badge bg-${val==='Evet'?'success':'danger'}">${val}</span>`;
                    } else if (field === 'active') {
                        cell.innerHTML =
                            `<span class="badge bg-${val==='Aktif'?'success':'secondary'}">${val}</span>`;
            } else {
                cell.innerText = val;
            }
        }

        function cancelEdit() {
            cell.innerHTML = oldHtml;
        }
        input.addEventListener('keydown', function(ev) {
                    if (ev.key === 'Enter') saveEdit();
                    if (ev.key === 'Escape') cancelEdit();
        });
        input.addEventListener('blur', function() {
            saveEdit();
        });
    });
});
// Bootstrap tooltip
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>
@endpush    
