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
                                    <tbody id="faultyTableBody">
<tr>
    <td>Matkap</td>
    <td>2024-06-18</td>
    <td><span class='badge bg-warning text-dark'>Normal</span></td>
    <td>Çalışmıyor, motor arızası</td>
    <td><span class='badge bg-danger'>Arızalı</span></td>
    <td>
      <button class='btn btn-success btn-sm me-1 faulty-fix-btn'><i class='fas fa-check'></i></button>
      <button class='btn btn-outline-info btn-sm faulty-detail-btn'><i class='fas fa-eye'></i></button>
    </td>
</tr>
<tr>
    <td>Projeksiyon Cihazı</td>
    <td>2024-06-17</td>
    <td><span class='badge bg-danger'>Acil</span></td>
    <td>Güç gelmiyor</td>
    <td><span class='badge bg-danger'>Arızalı</span></td>
    <td>
      <button class='btn btn-success btn-sm me-1 faulty-fix-btn'><i class='fas fa-check'></i></button>
      <button class='btn btn-outline-info btn-sm faulty-detail-btn'><i class='fas fa-eye'></i></button>
    </td>
</tr>
<tr>
    <td>Jeneratör 5kVA</td>
    <td>2024-06-16</td>
    <td><span class='badge bg-warning text-dark'>Normal</span></td>
    <td>Yağ kaçağı var</td>
    <td><span class='badge bg-danger'>Arızalı</span></td>
    <td>
      <button class='btn btn-success btn-sm me-1 faulty-fix-btn'><i class='fas fa-check'></i></button>
      <button class='btn btn-outline-info btn-sm faulty-detail-btn'><i class='fas fa-eye'></i></button>
    </td>
</tr>
</tbody>
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
                            <tbody id="historyTableBody">
<tr>
    <td>Jeneratör 5kVA</td>
    <td>2024-05-10</td>
    <td>Mekanik</td>
    <td><span class='badge bg-warning text-dark'>Normal</span></td>
    <td>Periyodik bakım yapıldı</td>
    <td>Ofis 1</td>
    <td><span class='badge bg-success'>Giderildi</span></td>
</tr>
<tr>
    <td>Oksijen Konsantratörü</td>
    <td>2024-04-22</td>
    <td>Elektriksel</td>
    <td><span class='badge bg-danger'>Acil</span></td>
    <td>Sigorta değişimi</td>
    <td>Depo</td>
    <td><span class='badge bg-success'>Giderildi</span></td>
</tr>
<tr>
    <td>Projeksiyon Cihazı</td>
    <td>2024-03-15</td>
    <td>Elektriksel</td>
    <td><span class='badge bg-danger'>Acil</span></td>
    <td>Kablo tamiri</td>
    <td>Toplantı Salonu</td>
    <td><span class='badge bg-success'>Giderildi</span></td>
</tr>
<tr>
    <td>Matkap</td>
    <td>2024-02-10</td>
    <td>Mekanik</td>
    <td><span class='badge bg-warning text-dark'>Normal</span></td>
    <td>Bakım yapıldı</td>
    <td>Depo</td>
    <td><span class='badge bg-success'>Giderildi</span></td>
</tr>
</tbody>
                        </table>
                        <!-- Pagination -->
                        <nav>
                            <ul class="pagination justify-content-end my-2" id="historyPagination"></ul>
                        </nav>
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
        const pageSize = 2;
        let currentPage = 1;
        let currentHistoryPage = 1;

        function applyFiltersAndPaginate() {
            // Arızalı Olanlar Tablosu
            const rows = Array.from(document.querySelectorAll('#faultyTableBody tr'));
            const urgency = document.getElementById('filterUrgency').value;
            const search = document.getElementById('filterSearch').value.trim().toLowerCase();
            let filtered = rows.filter(tr => {
                const tds = tr.querySelectorAll('td');
                const aciliyet = tds[2].innerText.trim();
                const ekipman = tds[0].innerText.trim().toLowerCase();
                const aciklama = tds[3].innerText.trim().toLowerCase();
                let show = true;
                if (urgency && aciliyet !== urgency) show = false;
                if (search && !(ekipman.includes(search) || aciklama.includes(search))) show = false;
                return show;
            });
            // Pagination
            rows.forEach(tr => tr.style.display = 'none');
            const start = (currentPage - 1) * pageSize;
            const end = start + pageSize;
            filtered.slice(start, end).forEach(tr => tr.style.display = '');
            renderPagination('faultyPagination', filtered.length, pageSize, currentPage, gotoFaultyPage);

            // Geçmiş İşlemler Tablosu
            const hRows = Array.from(document.querySelectorAll('#historyTableBody tr'));
            let hFiltered = hRows.filter(tr => {
                const tds = tr.querySelectorAll('td');
                const aciliyet = tds[3].innerText.trim();
                const ekipman = tds[0].innerText.trim().toLowerCase();
                const aciklama = tds[4].innerText.trim().toLowerCase();
                let show = true;
                if (urgency && aciliyet !== urgency) show = false;
                if (search && !(ekipman.includes(search) || aciklama.includes(search))) show = false;
                return show;
            });
            hRows.forEach(tr => tr.style.display = 'none');
            const hStart = (currentHistoryPage - 1) * pageSize;
            const hEnd = hStart + pageSize;
            hFiltered.slice(hStart, hEnd).forEach(tr => tr.style.display = '');
            renderPagination('historyPagination', hFiltered.length, pageSize, currentHistoryPage, gotoHistoryPage);
        }
        function renderPagination(ulId, total, pageSize, current, gotoFunc) {
            const ul = document.getElementById(ulId);
            if (!ul) return;
            ul.innerHTML = '';
            const pageCount = Math.ceil(total / pageSize);
            for (let i = 1; i <= pageCount; i++) {
                const li = document.createElement('li');
                li.className = 'page-item' + (i === current ? ' active' : '');
                const a = document.createElement('a');
                a.className = 'page-link';
                a.href = '#';
                a.textContent = i;
                a.onclick = function(e) { e.preventDefault(); gotoFunc(i); };
                li.appendChild(a);
                ul.appendChild(li);
            }
        }
        function gotoFaultyPage(page) { currentPage = page; applyFiltersAndPaginate(); }
        function gotoHistoryPage(page) { currentHistoryPage = page; applyFiltersAndPaginate(); }
        document.getElementById('filterUrgency').onchange = function() { currentPage = 1; currentHistoryPage = 1; applyFiltersAndPaginate(); };
        document.getElementById('filterSearch').oninput = function() { currentPage = 1; currentHistoryPage = 1; applyFiltersAndPaginate(); };
        // Buton eventleri (örnek: detay ve arıza giderildi modalı açma)
        document.querySelectorAll('.faulty-detail-btn').forEach(btn => {
            btn.onclick = function() {
                // Detay modalı açma işlemi
                const tr = btn.closest('tr');
                const tds = tr.querySelectorAll('td');
                const ekipman = tds[0].innerText.trim();
                const tarih = tds[1].innerText.trim();
                const aciliyet = tds[2].innerHTML.trim();
                const aciklama = tds[3].innerText.trim();
                const durum = tds[4].innerHTML.trim();
                let html = '';
                html += `<div class='mb-2'><span class='fw-bold'>Ekipman:</span> ${ekipman}</div>`;
                html += `<div class='mb-2'><span class='fw-bold'>Aciliyet:</span> <span style='font-size:1.1em;'>${aciliyet}</span></div>`;
                html += `<div class='mb-2'><span class='fw-bold'>Açıklama:</span> ${aciklama}</div>`;
                html += `<div class='mb-2'><span class='fw-bold'>Bildirim Tarihi:</span> ${tarih}</div>`;
                html += `<div class='mb-2'><span class='fw-bold'>Durum:</span> ${durum}</div>`;
                document.getElementById('faultyDetailBody').innerHTML = html;
                var modal = new bootstrap.Modal(document.getElementById('faultyDetailModal'));
                modal.show();
            };
        });
        document.querySelectorAll('.faulty-fix-btn').forEach(btn => {
            btn.onclick = function() {
                // Arıza giderildi modalı açma işlemi burada yapılabilir
                var modal = new bootstrap.Modal(document.getElementById('faultFixedModal'));
                modal.show();
            };
        });
        function populateUrgencySelect() {
            const select = document.getElementById('filterUrgency');
            const allRows = document.querySelectorAll('#faultyTableBody tr, #historyTableBody tr');
            const aciliyetSet = new Set();
            allRows.forEach(tr => {
                const tds = tr.querySelectorAll('td');
                // Arızalı tablosunda 3. td, geçmişte 4. td
                let aciliyet = '';
                if (tds[2]) aciliyet = tds[2].innerText.trim();
                if (tds[3] && !aciliyet) aciliyet = tds[3].innerText.trim();
                if (aciliyet) aciliyetSet.add(aciliyet);
            });
            select.innerHTML = '<option value="">Tümü</option>';
            Array.from(aciliyetSet).forEach(a => {
                select.innerHTML += `<option value="${a}">${a}</option>`;
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            populateUrgencySelect();
            applyFiltersAndPaginate();
        });
    </script>
    <!-- Arıza Giderildi Modalı -->
    <div class="modal fade" id="faultFixedModal" tabindex="-1" aria-labelledby="faultFixedModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success bg-opacity-25">
                    <h5 class="modal-title" id="faultFixedModalLabel"><i class="fas fa-check-circle text-success me-2"></i>Arıza Giderildi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body">
                    <form id="faultFixedForm">
                        <div class="mb-3">
                            <label for="fixedDate" class="form-label">Giderilme Tarihi</label>
                            <input type="date" class="form-control" id="fixedDate" name="fixedDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="fixedPhoto" class="form-label">Ekipman Fotoğrafı</label>
                            <input type="file" class="form-control" id="fixedPhoto" name="fixedPhoto" accept="image/*" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" form="faultFixedForm" class="btn btn-success">Kaydet</button>
                </div>
            </div>
        </div>
    </div>
@endsection
