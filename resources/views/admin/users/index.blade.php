@extends('layouts.admin')
@section('content')
<!-- Gerekli kütüphaneler ve stiller -->
@vite('resources/css/users.css')

<div class="container-fluid">
  <!-- Ultra modern başlık -->
  <div class="users-header w-100 position-relative mb-4" style="background:linear-gradient(90deg,#6366f1 0%,#43e97b 100%);color:#fff;border-radius:1.2em;box-shadow:0 4px 24px #d1d9e6;padding:2.5em 2em 2em 2em;display:flex;flex-direction:column;align-items:flex-start;overflow:hidden;">
    <h2 style=" color:white; font-size:2.7rem;font-weight:900;letter-spacing:-1px;line-height:1.1;">👤 Kullanıcılar Yönetimi</h2>
    <p style="font-size:1.2rem;font-weight:500;opacity:.98;">Sistemdeki tüm kullanıcıları görüntüleyin, yönetin ve analiz edin.</p>
    <div class="position-absolute top-0 end-0 mt-3 me-3 d-flex gap-2">
      <button class="btn btn-sm" style="background:transparent;border:none;box-shadow:none;" data-bs-toggle="modal" data-bs-target="#helpModal" title="Yardım"><i class="bi bi-question-circle" style="color:#fff;font-size:1.3em;"></i></button>
    </div>
  </div>
  <!-- Animasyonlu KPI kartları -->
  <div class="user-kpi-row mb-4">
    <div class="user-kpi-card shadow-lg" onclick="filterByKpi('all')" data-bs-toggle="tooltip" title="Tüm kullanıcıları gösterir.">
      <div class="user-kpi-icon"><i class="fas fa-users"></i></div>
      <div class="user-kpi-value" id="kpiTotalUser">120</div>
      <div class="user-kpi-label">Toplam Kullanıcı</div>
      <div class="user-kpi-trend up"><i class="bi bi-arrow-up"></i> %3 artış</div>
    </div>
    <div class="user-kpi-card shadow-lg" onclick="filterByKpi('admin')" data-bs-toggle="tooltip" title="Sadece adminleri gösterir.">
      <div class="user-kpi-icon"><i class="fas fa-user-shield"></i></div>
      <div class="user-kpi-value" id="kpiAdminUser">8</div>
      <div class="user-kpi-label">Admin</div>
      <div class="user-kpi-trend up"><i class="bi bi-arrow-up"></i> %1 artış</div>
    </div>
    <div class="user-kpi-card shadow-lg" onclick="filterByKpi('active')" data-bs-toggle="tooltip" title="Sadece aktif kullanıcıları gösterir.">
      <div class="user-kpi-icon"><i class="fas fa-user-check"></i></div>
      <div class="user-kpi-value" id="kpiActiveUser">102</div>
      <div class="user-kpi-label">Aktif Kullanıcı</div>
      <div class="user-kpi-trend up"><i class="bi bi-arrow-up"></i> %2 artış</div>
    </div>
    <div class="user-kpi-card shadow-lg" onclick="filterByKpi('new')" data-bs-toggle="tooltip" title="Bu ay eklenen kullanıcıları gösterir.">
      <div class="user-kpi-icon"><i class="fas fa-user-plus"></i></div>
      <div class="user-kpi-value" id="kpiNewUser">5</div>
      <div class="user-kpi-label">Bu Ay Eklenen</div>
      <div class="user-kpi-trend down"><i class="bi bi-arrow-down"></i> %0.5 azalış</div>
    </div>
  </div>
  <!-- Modern filtre barı -->
  <div class="user-filter-bar mb-4 shadow-sm rounded-3 p-3" id="userFilterBar" style="background:linear-gradient(120deg,#f8fafc 60%,#e0e7ff 100%);">
    <input type="text" class="form-control" id="userFilterDate" placeholder="📅 Tarih Aralığı">
    <select class="form-select" id="userFilterRole">
      <option value="Admin">Admin</option>
      <option value="Kullanıcı">Kullanıcı</option>
    </select>
    <select class="form-select" id="userFilterStatus">
      <option value="Aktif">Aktif</option>
      <option value="Pasif">Pasif</option>
    </select>
    <input type="text" class="form-control" id="userSearch" placeholder="Kullanıcı ara">
    <button class="btn btn-outline-primary" id="clearUserFiltersBtn"><i class="fas fa-times"></i> Sıfırla</button>
    <button class="btn btn-outline-success" id="saveUserFiltersBtn"><i class="bi bi-bookmark"></i> Kaydet</button>
    <button class="btn btn-outline-info" id="loadUserFiltersBtn"><i class="bi bi-arrow-clockwise"></i> Geri Yükle</button>
    <div id="activeUserFilterChips" class="d-flex flex-wrap"></div>
    <span id="activeUserFilterCount" class="badge bg-info ms-2" style="display:none;"></span>
  </div>
  <!-- Sticky header'lı, avatar'lı, aksiyonlu kullanıcı tablosu -->
  <div class="card p-3 mb-4 shadow-lg">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h6 class="fw-bold mb-2" style="font-size:1.15rem;"><i class="fas fa-users"></i> Kullanıcı Listesi</h6>
      <div>
        <span id="selectedUserCount" class="badge bg-primary me-2" style="display:none;"></span>
        <button class="btn btn-outline-secondary btn-sm" id="exportUserExcelBtn" title="Excel'e Aktar"><i class="bi bi-file-earmark-excel"></i></button>
        <button class="btn btn-outline-primary btn-sm" id="addUserBtn" title="Yeni Kullanıcı Ekle"><i class="bi bi-plus-circle"></i></button>
      </div>
    </div>
    <div class="table-responsive" style="overflow-x:unset;">
      <table class="table user-table table-striped table-hover mb-0 w-100 align-middle" id="userTable">
        <thead class="sticky-top bg-white shadow-sm">
          <tr>
            <th><input type="checkbox" id="selectAllUserRows"></th>
            <th></th>
            <th>#</th>
            <th>Ad Soyad</th>
            <th>E-posta</th>
            <th>Rol</th>
            <th>Son Giriş</th>
            <th>Kayıt Tarihi</th>
            <th>Aksiyon</th>
          </tr>
        </thead>
        <tbody id="userTableBody">
          <tr>
            <td><input type="checkbox" class="form-check-input user-row-check"></td>
            <td><span class="user-avatar">AK</span></td>
            <td>1</td>
            <td>Ali Kaya</td>
            <td>ali.kaya@example.com</td>
            <td><span class="badge bg-primary">Admin</span></td>
            <td>2024-06-20 09:12</td>
            <td>2023-12-01</td>
            <td>
              <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-lock"></i></button>
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td><input type="checkbox" class="form-check-input user-row-check"></td>
            <td><span class="user-avatar">AY</span></td>
            <td>2</td>
            <td>Ayşe Yılmaz</td>
            <td>ayse.yilmaz@example.com</td>
            <td><span class="badge bg-primary">Admin</span></td>
            <td>2024-06-19 15:44</td>
            <td>2024-01-10</td>
            <td>
              <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-lock"></i></button>
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td><input type="checkbox" class="form-check-input user-row-check"></td>
            <td><span class="user-avatar">MD</span></td>
            <td>3</td>
            <td>Mehmet Demir</td>
            <td>mehmet.demir@example.com</td>
            <td><span class="badge bg-secondary">Kullanıcı</span></td>
            <td>2024-06-18 11:22</td>
            <td>2024-02-15</td>
            <td>
              <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-lock"></i></button>
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td><input type="checkbox" class="form-check-input user-row-check"></td>
            <td><span class="user-avatar">FK</span></td>
            <td>4</td>
            <td>Fatma Kaya</td>
            <td>fatma.kaya@example.com</td>
            <td><span class="badge bg-secondary">Kullanıcı</span></td>
            <td>2024-06-15 08:10</td>
            <td>2024-03-05</td>
            <td>
              <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-lock"></i></button>
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td><input type="checkbox" class="form-check-input user-row-check"></td>
            <td><span class="user-avatar">ZS</span></td>
            <td>5</td>
            <td>Zeynep Şahin</td>
            <td>zeynep.sahin@example.com</td>
            <td><span class="badge bg-secondary">Kullanıcı</span></td>
            <td>2024-06-10 17:30</td>
            <td>2024-04-12</td>
            <td>
              <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-lock"></i></button>
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
      <div id="noUserDataIllu" class="no-data-illu" style="display:none;">
        <img src="https://cdn.dribbble.com/users/1138875/screenshots/4669703/no-data.png" alt="No Data" style="max-width:180px;opacity:.7;"><br>
        <span>Veri bulunamadı.</span>
        <button class="btn btn-success mt-2" id="addUserBtnEmpty"><i class="bi bi-plus-circle"></i> Yeni Kullanıcı Ekle</button>
      </div>
    </div>
    <div class="mt-2 d-flex gap-2 flex-wrap">
      <button class="btn btn-danger btn-sm" id="bulkUserDeleteBtn"><i class="bi bi-trash"></i> Seçiliyi Sil</button>
    </div>
  </div>
  <div id="userSnackbar">Veriler güncellendi!</div>
</div>
<!-- Kullanıcı Ekle Modalı -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalLabel">Yeni Kullanıcı Ekle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control mb-2" id="newUserName" placeholder="Ad Soyad">
        <input type="email" class="form-control mb-2" id="newUserEmail" placeholder="E-posta">
        <select class="form-select mb-2" id="newUserRole">
          <option value="Kullanıcı">Kullanıcı</option>
          <option value="Admin">Admin</option>
        </select>
        <button class="btn btn-success w-100" id="saveNewUserBtn">Kaydet</button>
      </div>
    </div>
  </div>
</div>
<!-- Kullanıcı Detay Modalı -->
<div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userDetailModalLabel">Kullanıcı Detayı</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body" id="userDetailContent"></div>
    </div>
  </div>
</div>
<!-- Yardım Modalı -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="helpModalLabel">Kullanım Kılavuzu & Kısayollar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <ul>
          <li>KPI kartlarına tıklayarak hızlı filtre uygulayabilirsiniz.</li>
          <li>Filtre barında filtreleri kaydedip geri yükleyebilirsiniz.</li>
          <li>Tabloda arama, sıralama, sayfalama ve toplu işlem yapabilirsiniz.</li>
          <li>Satırdaki üç nokta ile daha fazla aksiyona ulaşabilirsiniz.</li>
          <li>Karanlık mod için sağ üstteki ay simgesine tıklayın.</li>
        </ul>
        <b>Klavye Kısayolları:</b>
        <ul>
          <li><kbd>Ctrl</kbd> + <kbd>F</kbd>: Tablo arama kutusuna odaklan</li>
          <li><kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>N</kbd>: Yeni kullanıcı ekle</li>
          <li><kbd>Esc</kbd>: Açık modalı kapat</li>
        </ul>
      </div>
    </div>
  </div>
</div>
@vite('resources/js/users.js')
@endsection