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
      <div class="user-kpi-trend down"><i class="bi bi-arrow-down"></i> {{ $stats['new_growth'] ?? 0 }}% azalış</div>
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
          <option value="user">Kullanıcı</option>
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
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h6 class="fw-bold mb-0" style="font-size:1.15rem;">
        <i class="fas fa-users"></i> Kullanıcı Listesi
        <span class="badge bg-primary ms-2" id="userCount">{{ count($users ?? []) }}</span>
      </h6>
      <div class="d-flex gap-2">
        <button class="btn btn-outline-success btn-sm" id="addUserBtn" title="Yeni Kullanıcı Ekle">
          <i class="bi bi-plus-circle"></i> Yeni Kullanıcı
        </button>
        <button class="btn btn-outline-info btn-sm" id="exportUserExcelBtn" title="Excel'e Aktar">
          <i class="bi bi-file-earmark-excel"></i> Excel
        </button>
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
                    @if($user->username)
                      <br><small class="text-muted">@{{ $user->username }}</small>
                    @endif
                  </div>
                </div>
              </td>
              <td>{{ $user->email ?? 'E-posta yok' }}</td>
              <td>
                @if($user->role === 'admin')
                  <span class="badge bg-primary">Admin</span>
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
                  <button class="btn btn-success mt-3" id="addUserBtnEmpty">
                    <i class="bi bi-plus-circle"></i> İlk Kullanıcıyı Ekle
                  </button>
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

<!-- Kullanıcı Ekle/Düzenle Modalı -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userModalLabel">Yeni Kullanıcı Ekle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <form id="userForm">
          @csrf
          <input type="hidden" id="userId" name="user_id">
          
          <div class="mb-3">
            <label for="userName" class="form-label">Ad Soyad <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="userName" name="name" required placeholder="Ad Soyad giriniz">
          </div>
          
          <div class="mb-3">
            <label for="userEmail" class="form-label">E-posta <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="userEmail" name="email" required placeholder="E-posta adresi giriniz">
          </div>
          
          <div class="mb-3">
            <label for="userUsername" class="form-label">Kullanıcı Adı</label>
            <input type="text" class="form-control" id="userUsername" name="username" placeholder="Kullanıcı adı (opsiyonel)">
          </div>
          
          <div class="mb-3">
            <label for="userRole" class="form-label">Rol <span class="text-danger">*</span></label>
            <select class="form-select" id="userRole" name="role" required>
              <option value="">Rol seçiniz</option>
              <option value="user">Kullanıcı</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          
          <div class="mb-3">
            <label for="userPassword" class="form-label">Şifre <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="userPassword" name="password" required placeholder="Şifre giriniz">
            <small class="text-muted">En az 8 karakter olmalıdır</small>
          </div>
          
          <div class="mb-3">
            <label for="userPasswordConfirm" class="form-label">Şifre Tekrar <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="userPasswordConfirm" name="password_confirmation" required placeholder="Şifreyi tekrar giriniz">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
        <button type="submit" form="userForm" class="btn btn-primary" id="saveUserBtn">
          <i class="bi bi-save"></i> Kaydet
        </button>
      </div>
    </div>
  </div>
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

@vite('resources/js/users.js')
@endsection