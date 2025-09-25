@extends('layouts.admin')
@section('content')
<!-- Gerekli kütüphaneler ve stiller -->
@vite('resources/css/users.css')

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center">
                <img src="{{ asset('images/ibag-logo.svg') }}" alt="İBAG Logo" class="d-none d-sm-inline" style="width: 24px; height: 24px; margin-right: 8px;">
                <i class="fa fa-home me-1"></i> 
                <span class="d-none d-sm-inline">Ana Sayfa</span>
                <span class="d-sm-none">Ana</span>
            </a>
        </li>
        <li class="breadcrumb-item d-none d-md-inline">
            <a href="/admin/" class="text-decoration-none">Yönetim</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-users me-1 d-sm-none"></i>
            <span class="d-none d-sm-inline">Kullanıcılar</span>
            <span class="d-sm-none">Kullanıcılar</span>
        </li>
    </ol>
</nav>

<div class="container-fluid">
  <!-- Ultra modern başlık -->
  <div class="users-header w-100 position-relative mb-4" style="background:linear-gradient(90deg,#6366f1 0%,#43e97b 100%);color:#fff;border-radius:1.2em;box-shadow:0 4px 24px #d1d9e6;padding:2.5em 2em 2em 2em;display:flex;flex-direction:column;align-items:flex-start;overflow:hidden;">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start w-100">
      <div class="flex-grow-1">
        <h2 class="mb-2" style="color:white; font-size:2.7rem;font-weight:900;letter-spacing:-1px;line-height:1.1;">
          <span class="d-none d-sm-inline">👤 Kullanıcılar Yönetimi</span>
          <span class="d-sm-none">👤 Kullanıcılar</span>
        </h2>
        <p class="mb-0" style="font-size:1.2rem;font-weight:500;opacity:.98;">
          <span class="d-none d-md-inline">Sistemdeki tüm kullanıcıları görüntüleyin, yönetin ve analiz edin.</span>
          <span class="d-md-none">Kullanıcıları yönetin ve analiz edin.</span>
        </p>
      </div>
      <div class="d-flex gap-2 mt-3 mt-md-0">
        <a href="{{ route('admin.users.create') }}" class="btn btn-light btn-sm d-flex align-items-center" style="background:rgba(255,255,255,0.2);border:1px solid rgba(255,255,255,0.3);color:#fff;font-weight:600;">
          <i class="fas fa-user-plus me-1"></i> 
          <span class="d-none d-sm-inline">Yeni Kullanıcı</span>
          <span class="d-sm-none">Yeni</span>
        </a>
        <button class="btn btn-sm" style="background:transparent;border:none;box-shadow:none;" data-bs-toggle="modal" data-bs-target="#helpModal" title="Yardım">
          <i class="bi bi-question-circle" style="color:#fff;font-size:1.3em;"></i>
        </button>
      </div>
    </div>
  </div>
  
  <!-- Animasyonlu KPI kartları -->
  <div class="user-kpi-row mb-4">
    <div class="user-kpi-card shadow-lg" onclick="filterByKpi('all')" data-bs-toggle="tooltip" title="Tüm kullanıcıları gösterir.">
      <div class="user-kpi-icon"><i class="fas fa-users"></i></div>
      <div class="user-kpi-value" id="kpiTotalUser">{{ $stats['total'] ?? 0 }}</div>
      <div class="user-kpi-label">Toplam Kullanıcı</div>
      <div class="user-kpi-trend up"><i class="bi bi-arrow-up"></i> {{ $stats['growth'] ?? 0 }}% artış</div>
    </div>
    <div class="user-kpi-card shadow-lg" onclick="filterByKpi('admin')" data-bs-toggle="tooltip" title="Sadece adminleri gösterir.">
      <div class="user-kpi-icon"><i class="fas fa-user-shield"></i></div>
      <div class="user-kpi-value" id="kpiAdminUser">{{ $stats['admin'] ?? 0 }}</div>
      <div class="user-kpi-label">Admin</div>
      <div class="user-kpi-trend up"><i class="bi bi-arrow-up"></i> {{ $stats['admin_growth'] ?? 0 }}% artış</div>
    </div>
    <div class="user-kpi-card shadow-lg" onclick="filterByKpi('active')" data-bs-toggle="tooltip" title="Sadece aktif kullanıcıları gösterir.">
      <div class="user-kpi-icon"><i class="fas fa-user-check"></i></div>
      <div class="user-kpi-value" id="kpiActiveUser">{{ $stats['active'] ?? 0 }}</div>
      <div class="user-kpi-label">Aktif Kullanıcı</div>
      <div class="user-kpi-trend up"><i class="bi bi-arrow-up"></i> {{ $stats['active_growth'] ?? 0 }}% artış</div>
    </div>
    <div class="user-kpi-card shadow-lg" onclick="filterByKpi('new')" data-bs-toggle="tooltip" title="Bu ay eklenen kullanıcıları gösterir.">
      <div class="user-kpi-icon"><i class="fas fa-user-plus"></i></div>
      <div class="user-kpi-value" id="kpiNewUser">{{ $stats['new_this_month'] ?? 0 }}</div>
      <div class="user-kpi-label">Bu Ay Eklenen</div>
      <div class="user-kpi-trend up"><i class="bi bi-calendar-check"></i> Bu Ay</div>
    </div>
  </div>
  
  <!-- Modern filtre barı -->
  <div class="user-filter-bar mb-4 shadow-sm rounded-3 p-3" id="userFilterBar" style="background:linear-gradient(120deg,#f8fafc 60%,#e0e7ff 100%);">
    <div class="row g-3 align-items-end">
      <div class="col-md-3">
        <label class="form-label mb-1">Rol</label>
        <select class="form-select" id="userFilterRole">
          <option value="">Tüm Roller</option>
          <option value="admin">Admin</option>
          <option value="ekip_yetkilisi">Ekip Yetkilisi</option>
          <option value="üye">Üye</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label mb-1">Durum</label>
        <select class="form-select" id="userFilterStatus">
          <option value="">Tüm Durumlar</option>
          <option value="active">Aktif</option>
          <option value="inactive">Pasif</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label mb-1">Arama</label>
        <input type="text" class="form-control" id="userSearch" placeholder="Ad, e-posta veya kullanıcı adı ara...">
      </div>
      <div class="col-md-2">
        <button class="btn btn-outline-secondary w-100" id="clearUserFiltersBtn">
          <i class="fas fa-times"></i> Sıfırla
        </button>
      </div>
    </div>
  </div>
  
  <!-- Sticky header'lı, avatar'lı, aksiyonlu kullanıcı tablosu -->
  <div class="card p-3 mb-4 shadow-lg">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
      <h6 class="fw-bold mb-0" style="font-size:1.15rem;">
        <i class="fas fa-users me-2"></i> 
        <span class="d-none d-sm-inline">Kullanıcı Listesi</span>
        <span class="d-sm-none">Kullanıcılar</span>
        <span class="badge bg-primary ms-2" id="userCount">{{ count($users ?? []) }}</span>
      </h6>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.users.create') }}" class="btn btn-outline-success btn-sm" title="Yeni Kullanıcı Ekle">
          <i class="bi bi-plus-circle me-1"></i> 
          <span class="d-none d-sm-inline">Yeni Kullanıcı</span>
          <span class="d-sm-none">Yeni</span>
        </a>
      </div>
    </div>
    
    <div class="table-responsive" style="overflow-x: visible;">
      <table class="table user-table table-striped table-hover mb-0 w-100 align-middle" id="userTable">
        <thead class="sticky-top bg-white shadow-sm">
          <tr>
            <th style="width: 50px;">
              <input type="checkbox" id="selectAllUserRows" class="form-check-input">
            </th>
            <th style="width: 60px;">Avatar</th>
            <th style="width: 60px;">#</th>
            <th>Ad Soyad</th>
            <th>E-posta</th>
            <th>Rol</th>
            <th>Durum</th>
            <th>Son Giriş</th>
            <th>Kayıt Tarihi</th>
            <th style="width: 120px;">Aksiyon</th>
          </tr>
        </thead>
        <tbody id="userTableBody">
          @forelse($users ?? [] as $user)
            <tr data-user-id="{{ $user->id }}" data-role="{{ $user->role }}" data-status="{{ $user->status }}">
              <td>
                <input type="checkbox" class="form-check-input user-row-check" value="{{ $user->id }}">
              </td>
              <td>
                <span class="user-avatar" style="background: {{ $user->avatar_color ?? '#6366f1' }};">
                  {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                </span>
              </td>
              <td>{{ $loop->iteration }}</td>
              <td>
                <div class="d-flex align-items-center">
                  <div>
                    <strong>{{ $user->name ?? 'İsimsiz' }}</strong>
                    <div class="d-sm-none text-muted small">{{ $user->email ?? 'E-posta yok' }}</div>
                  </div>
                </div>
              </td>
              <td class="d-none d-sm-table-cell">{{ $user->email ?? 'E-posta yok' }}</td>
              <td>
                @if($user->role === 'admin')
                  <span class="badge bg-primary">Admin</span>
                @elseif($user->role === 'ekip_yetkilisi')
                  <span class="badge bg-info">Ekip Yetkilisi</span>
                @elseif($user->role === 'üye')
                  <span class="badge bg-secondary">Üye</span>
                @else
                  <span class="badge bg-secondary">Kullanıcı</span>
                @endif
              </td>
              <td>
                @if($user->status === 'active')
                  <span class="badge bg-success">Aktif</span>
                @else
                  <span class="badge bg-danger">Pasif</span>
                @endif
              </td>
              <td>
                @if($user->last_login_at)
                  {{ \Carbon\Carbon::parse($user->last_login_at)->format('d.m.Y H:i') }}
                @else
                  <span class="text-muted">Hiç giriş yapmamış</span>
                @endif
              </td>
              <td>
                @if($user->created_at)
                  {{ \Carbon\Carbon::parse($user->created_at)->format('d.m.Y') }}
                @else
                  <span class="text-muted">Tarih yok</span>
                @endif
              </td>
              <td>
                <div class="btn-group btn-group-sm">
                  <button class="btn btn-outline-info btn-sm" onclick="showUserDetail({{ $user->id }})" title="Detay">
                    <i class="bi bi-eye"></i>
                  </button>
                  <button class="btn btn-outline-warning btn-sm" onclick="editUser({{ $user->id }})" title="Düzenle">
                    <i class="bi bi-pencil"></i>
                  </button>
                  @if($user->status === 'active')
                    <button class="btn btn-outline-secondary btn-sm" onclick="toggleUserStatus({{ $user->id }})" title="Pasif Yap">
                      <i class="bi bi-lock"></i>
                    </button>
                  @else
                    <button class="btn btn-outline-success btn-sm" onclick="toggleUserStatus({{ $user->id }})" title="Aktif Yap">
                      <i class="bi bi-unlock"></i>
                    </button>
                  @endif
                  <button class="btn btn-outline-danger btn-sm" onclick="deleteUser({{ $user->id }})" title="Sil">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="text-center py-5">
                <div id="noUserDataIllu" class="no-data-illu">
                  <img src="https://cdn.dribbble.com/users/1138875/screenshots/4669703/no-data.png" alt="No Data" style="max-width:180px;opacity:.7;"><br>
                  <span class="text-muted">Henüz kullanıcı bulunmuyor.</span>
                  <a href="{{ route('admin.users.create') }}" class="btn btn-success mt-3">
                    <i class="bi bi-plus-circle"></i> İlk Kullanıcıyı Ekle
                  </a>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    @if(($users ?? [])->count() > 0)
      <div class="mt-3 d-flex justify-content-between align-items-center">
        <div>
          <span id="selectedUserCount" class="badge bg-primary" style="display:none;">0 kullanıcı seçildi</span>
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-danger btn-sm" id="bulkUserDeleteBtn" style="display:none;">
            <i class="bi bi-trash"></i> Seçiliyi Sil
          </button>
          <button class="btn btn-warning btn-sm" id="bulkUserStatusBtn" style="display:none;">
            <i class="bi bi-lock"></i> Durum Değiştir
          </button>
        </div>
      </div>
    @endif
  </div>
  
  <div id="userSnackbar" class="user-snackbar">Veriler güncellendi!</div>
</div>


<!-- Kullanıcı Detay Modalı -->
<div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userDetailModalLabel">Kullanıcı Detayı</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body" id="userDetailContent">
        <!-- AJAX ile doldurulacak -->
      </div>
    </div>
  </div>
</div>

<!-- Kullanıcı Düzenleme Modalı -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-primary text-white">
        <h5 class="modal-title" id="editUserModalLabel">
          <i class="fas fa-user-edit me-2"></i>Kullanıcı Düzenle
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body p-4">
        <form id="editUserForm">
          @csrf
          @method('PUT')
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="edit_name" class="form-label">
                <i class="fas fa-user me-1"></i>Ad Soyad <span class="text-danger">*</span>
              </label>
              <input type="text" class="form-control" id="edit_name" name="name" required>
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="edit_email" class="form-label">
                <i class="fas fa-envelope me-1"></i>E-posta <span class="text-danger">*</span>
              </label>
              <input type="email" class="form-control" id="edit_email" name="email" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="edit_username" class="form-label">
                <i class="fas fa-at me-1"></i>Kullanıcı Adı
              </label>
              <input type="text" class="form-control" id="edit_username" name="username">
              <div class="form-text">Boş bırakılırsa e-posta adresi kullanılır</div>
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="edit_role" class="form-label">
                <i class="fas fa-user-tag me-1"></i>Rol <span class="text-danger">*</span>
              </label>
              <select class="form-select" id="edit_role" name="role" required>
                <option value="">Rol Seçin</option>
                <option value="admin">Admin</option>
                <option value="ekip_yetkilisi">Ekip Yetkilisi</option>
                <option value="üye">Üye</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="edit_status" class="form-label">
                <i class="fas fa-toggle-on me-1"></i>Durum
              </label>
              <select class="form-select" id="edit_status" name="status">
                <option value="active">Aktif</option>
                <option value="inactive">Pasif</option>
              </select>
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="edit_avatar_color" class="form-label">
                <i class="fas fa-palette me-1"></i>Avatar Rengi
              </label>
              <input type="color" class="form-control form-control-color" id="edit_avatar_color" name="avatar_color">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="edit_password" class="form-label">
                <i class="fas fa-lock me-1"></i>Yeni Şifre
              </label>
              <div class="input-group">
                <input type="password" class="form-control" id="edit_password" name="password" placeholder="Değiştirmek için yeni şifre girin">
                <button class="btn btn-outline-secondary" type="button" id="toggleEditPassword">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
              <div class="form-text">Boş bırakılırsa mevcut şifre korunur</div>
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="edit_password_confirmation" class="form-label">
                <i class="fas fa-lock me-1"></i>Şifre Tekrar
              </label>
              <div class="input-group">
                <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation" placeholder="Yeni şifreyi tekrar girin">
                <button class="btn btn-outline-secondary" type="button" id="toggleEditPasswordConfirmation">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i> İptal
        </button>
        <button type="button" class="btn btn-primary" id="saveUserBtn">
          <i class="fas fa-save me-1"></i> Kaydet
        </button>
      </div>
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
          <li>Filtre barında rol ve durum filtrelerini kullanabilirsiniz.</li>
          <li>Arama kutusunda ad, e-posta veya kullanıcı adı ile arama yapabilirsiniz.</li>
          <li>Tabloda toplu işlem yapabilirsiniz (seçili kullanıcıları silme, durum değiştirme).</li>
          <li>Her kullanıcı için detay görüntüleme, düzenleme ve silme işlemleri yapabilirsiniz.</li>
        </ul>
        <b>Klavye Kısayolları:</b>
        <ul>
          <li><kbd>Ctrl</kbd> + <kbd>F</kbd>: Arama kutusuna odaklan</li>
          <li><kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>N</kbd>: Yeni kullanıcı ekle</li>
          <li><kbd>Esc</kbd>: Açık modalı kapat</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(90deg, #0d6efd 60%, #0dcaf0 100%) !important;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.25);
}

.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.25);
}

.modal-lg {
    max-width: 800px;
}

@media (max-width: 768px) {
    .modal-lg {
        max-width: 95%;
        margin: 0.5rem;
    }
}
</style>

@vite('resources/js/users.js')
@endsection