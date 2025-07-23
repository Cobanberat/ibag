@extends('layouts.admin')
@section('content')
    <style>
        .approval-tabs .nav-link {
            font-weight: 600;
            font-size: 1.08rem;
        }

        .approval-badge {
            font-size: 0.95rem;
            border-radius: 1em;
            padding: 0.3em 0.9em;
            font-weight: 600;
        }

        .approval-badge.acil {
            background: #ff4d4f;
            color: #fff;
        }

        .approval-badge.normal {
            background: #ffec3d;
            color: #333;
        }

        .approval-badge.tamam {
            background: #28a745;
            color: #fff;
        }

        .approval-badge.red {
            background: #b993d6;
            color: #fff;
        }

        .approval-table th {
            background: #f7f7fa;
            font-weight: bold;
        }

        .approval-actions .btn {
            margin-right: 0.2rem;
        }

        .approval-row.selected {
            background: #e0e7ff !important;
        }
    </style>
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a>
            </li>
            <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Arıza Durumu' }}</li>
        </ol>
    </nav>
    <!-- Filtreler -->
    <div class="card mb-3 shadow-sm border-0">
        <div class="card-body py-3">
            <div class="row g-2 align-items-end">
                <!-- Lokasyon filtresi kaldırıldı -->
                <div class="col-md-6">
                    <label class="form-label mb-1">Aciliyet</label>
                    <select class="form-select" id="filterUrgency">
                        <option value="">Tümü</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label mb-1">Arama</label>
                    <input type="text" class="form-control" id="filterSearch" placeholder="Ekipman veya açıklama...">
                </div>
            </div>
        </div>
    </div>
    <!-- Sekmeler -->
    <ul class="nav nav-tabs approval-tabs mb-3" id="approvalTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="arizali-tab" data-bs-toggle="tab" data-bs-target="#arizali" type="button"
                role="tab">Arızalı</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="gecmis-tab" data-bs-toggle="tab" data-bs-target="#gecmis" type="button"
                role="tab">Geçmiş İşlemler</button>
        </li>
    </ul>
    <div class="tab-content" id="approvalTabContent">
        <div class="tab-pane fade show active" id="arizali" role="tabpanel">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header bg-danger text-white">Arızalı Olanlar</div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 approval-table" id="faultyTable">
                                    <thead>
                                        <tr>
                                            <th>Ekipman</th>
                                            <th>Arıza Bildirim Tarihi</th>
                                            <th>Aciliyet</th>
                                            <th>Açıklama</th>
                                            <!-- <th>Lokasyon</th> kaldırıldı -->
                                            <th>Durum</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody id="faultyTableBody"></tbody>
                                </table>
                                <!-- Pagination -->
                                <nav>
                                    <ul class="pagination justify-content-end my-2" id="faultyPagination"></ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="gecmis" role="tabpanel">
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">Geçmiş İşlemler</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 approval-table">
                            <thead>
                                <tr>
                                    <th>Ekipman</th>
                                    <th>İşlem Tarihi</th>
                                    <th>Arıza Tipi</th>
                                    <th>Aciliyet</th>
                                    <th>Açıklama</th>
                                    <th>Lokasyon</th>
                                    <th>Sonuç</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Jeneratör 5kVA</td>
                                    <td>2024-05-10</td>
                                    <td>Mekanik</td>
                                    <td><span class="badge bg-warning text-dark">Normal</span></td>
                                    <td>Periyodik bakım yapıldı</td>
                                    <td>Ofis 1</td>
                                    <td><span class="badge bg-success">Giderildi</span></td>
                                </tr>
                                <tr>
                                    <td>Oksijen Konsantratörü</td>
                                    <td>2024-04-22</td>
                                    <td>Elektriksel</td>
                                    <td><span class="badge bg-danger">Acil</span></td>
                                    <td>Sigorta değişimi</td>
                                    <td>Depo</td>
                                    <td><span class="badge bg-success">Giderildi</span></td>
                                </tr>
                                <tr>
                                    <td>Projeksiyon Cihazı</td>
                                    <td>2024-03-15</td>
                                    <td>Elektriksel</td>
                                    <td><span class="badge bg-danger">Acil</span></td>
                                    <td>Kablo tamiri</td>
                                    <td>Toplantı Salonu</td>
                                    <td><span class="badge bg-success">Giderildi</span></td>
                                </tr>
                                <tr>
                                    <td>Matkap</td>
                                    <td>2024-02-10</td>
                                    <td>Mekanik</td>
                                    <td><span class="badge bg-warning text-dark">Normal</span></td>
                                    <td>Bakım yapıldı</td>
                                    <td>Depo</td>
                                    <td><span class="badge bg-success">Giderildi</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Detay Modalı (Arızalı Ekipman) -->
    <div class="modal fade" id="faultyDetailModal" tabindex="-1" aria-labelledby="faultyDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="faultyDetailModalLabel">Arızalı Ekipman Detayı</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body" id="faultyDetailBody">
                    <!-- JS ile doldurulacak -->
                </div>
            </div>
        </div>
    </div>
    <script>
        // Arızalı Olanlar tablosu için veri ve pagination
        const faultyData = [{
                ekipman: 'Matkap',
                tarih: '2024-06-18',
                tip: 'Mekanik',
                aciliyet: 'Normal',
                aciklama: 'Çalışmıyor, motor arızası',
                lokasyon: 'Depo',
                durum: 'Arızalı',
                resim: 'https://cdn.pixabay.com/photo/2016/03/31/19/14/drill-1299342_1280.png'
            },
            {
                ekipman: 'Projeksiyon Cihazı',
                tarih: '2024-06-17',
                tip: 'Elektriksel',
                aciliyet: 'Acil',
                aciklama: 'Güç gelmiyor',
                lokasyon: 'Toplantı Salonu',
                durum: 'Arızalı',
                resim: 'https://cdn.pixabay.com/photo/2013/07/12/13/58/projector-147413_1280.png'
            },
            {
                ekipman: 'Jeneratör 5kVA',
                tarih: '2024-06-16',
                tip: 'Mekanik',
                aciliyet: 'Normal',
                aciklama: 'Yağ kaçağı var',
                lokasyon: 'Ofis 1',
                durum: 'Arızalı',
                resim: 'https://cdn.pixabay.com/photo/2012/04/13/21/07/generator-33637_1280.png'
            }
        ];
        const pageSize = 2;
        let currentPage = 1;
        // --- Filtreleme için yardımcılar ---
        function getUnique(arr, key) {
            return [...new Set(arr.map(item => item[key]))];
        }

        function populateFilters() {
            // Lokasyon kaldırıldı
            // Aciliyet
            const urgencySel = document.getElementById('filterUrgency');
            const urgencies = getUnique(faultyData, 'aciliyet');
            urgencies.forEach(u => {
                const opt = document.createElement('option');
                opt.value = u;
                opt.textContent = u;
                urgencySel.appendChild(opt);
            });
        }
        // --- Filtreleme işlemi ---
        let filteredData = faultyData.slice();

        function applyFilters() {
            // Lokasyon kaldırıldı
            const urgency = document.getElementById('filterUrgency').value;
            const search = document.getElementById('filterSearch').value.trim().toLowerCase();
            filteredData = faultyData.filter(d => {
                return (urgency === '' || d.aciliyet === urgency) &&
                    (search === '' || d.ekipman.toLowerCase().includes(search) || d.aciklama.toLowerCase().includes(
                        search));
            });
            currentPage = 1;
            renderFaultyTable();
        }
        // --- Tablo render'ı filtreli veriyle ---
        function renderFaultyTable() {
            const tbody = document.getElementById('faultyTableBody');
            tbody.innerHTML = '';
            const start = (currentPage - 1) * pageSize;
            const end = start + pageSize;
            const pageData = filteredData.slice(start, end);
            if (pageData.length === 0) {
                tbody.innerHTML =
                    `<tr><td colspan="6" class="text-center text-muted py-4">Kriterlere uygun kayıt bulunamadı.</td></tr>`;
            } else {
                pageData.forEach((d, i) => {
                    tbody.innerHTML += `<tr>
        <td>${d.ekipman}</td>
        <td>${d.tarih}</td>
        <td><span class='badge ${d.aciliyet==='Acil'?'bg-danger':'bg-warning text-dark'}'>${d.aciliyet}</span></td>
        <td>${d.aciklama}</td>
        <!-- <td>${d.lokasyon}</td> kaldırıldı -->
        <td><span class='badge bg-danger'>Arızalı</span></td>
        <td>
          <button class='btn btn-success btn-sm me-1 faulty-fix-btn' data-idx='${faultyData.indexOf(d)}'><i class='fas fa-check'></i></button>
          <button class='btn btn-outline-info btn-sm faulty-detail-btn' data-idx='${faultyData.indexOf(d)}'><i class='fas fa-eye'></i></button>
        </td>
      </tr>`;
                });
            }
            renderFaultyPagination();
            attachFaultyBtnEvents();
        }

        function renderFaultyPagination() {
            const pageCount = Math.ceil(filteredData.length / pageSize);
            const pag = document.getElementById('faultyPagination');
            pag.innerHTML = '';
            for (let i = 1; i <= pageCount; i++) {
                pag.innerHTML +=
                    `<li class='page-item${i===currentPage?' active':''}'><a class='page-link' href='#' onclick='gotoFaultyPage(${i});return false;'>${i}</a></li>`;
            }
        }

        function gotoFaultyPage(page) {
            currentPage = page;
            renderFaultyTable();
        }
        window.gotoFaultyPage = gotoFaultyPage;

        function attachFaultyBtnEvents() {
            document.querySelectorAll('.faulty-detail-btn').forEach(btn => {
                btn.onclick = function() {
                    const idx = parseInt(btn.getAttribute('data-idx'));
                    showFaultyDetail(idx);
                };
            });
            document.querySelectorAll('.faulty-fix-btn').forEach(btn => {
                btn.onclick = function() {
                    const idx = parseInt(btn.getAttribute('data-idx'));
                    markFaultyFixed(idx);
                };
            });
        }

        function showFaultyDetail(idx) {
            const d = faultyData[idx];
            let html =
                `<div class='text-center mb-3'><img src='${d.resim}' alt='${d.ekipman}' class='img-fluid rounded shadow' style='max-height:160px;'></div>`;
            html += `<div class='mb-2'><span class='fw-bold'>Ekipman:</span> ${d.ekipman}</div>`;
            html +=
                `<div class='mb-2'><span class='fw-bold'>Aciliyet:</span> <span class='badge ${d.aciliyet==='Acil'?'bg-danger':'bg-warning text-dark'}'>${d.aciliyet}</span></div>`;
            html += `<div class='mb-2'><span class='fw-bold'>Açıklama:</span> ${d.aciklama}</div>`;
            // Lokasyon kaldırıldı
            html += `<div class='mb-2'><span class='fw-bold'>Bildirim Tarihi:</span> ${d.tarih}</div>`;
            html +=
                `<div class='mb-2'><span class='fw-bold'>Durum:</span> <span class='badge bg-danger'>Arızalı</span></div>`;
            const modalBody = document.getElementById('faultyDetailBody');
            if (modalBody) modalBody.innerHTML = html;
            const modalEl = document.getElementById('faultyDetailModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        }

        function markFaultyFixed(idx) {
            faultyData.splice(idx, 1);
            applyFilters();
            if ((currentPage - 1) * pageSize >= filteredData.length && currentPage > 1) currentPage--;
            renderFaultyTable();
        }
        window.onload = function() {
            populateFilters();
            applyFilters();
            // Filtre eventleri
            document.getElementById('filterUrgency').onchange = applyFilters;
            document.getElementById('filterSearch').oninput = applyFilters;
        };
    </script>
@endsection
