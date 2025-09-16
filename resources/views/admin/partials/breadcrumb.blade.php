<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a>
        </li>
        <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Yönetim</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Sayfa' }}</li>
    </ol>
</nav>
<!-- Üst Bar: Duyuru, Hava Durumu, Bildirim, Arama, Kullanıcı Kartı -->
<div class="row g-3 mb-4 align-items-center">
    <div class="col-lg-8 d-flex align-items-center gap-3 flex-wrap">
        <div class="alert alert-info mb-0 py-3 px-3 shadow-sm">
            <i class="fa fa-bullhorn me-2"></i> Hoşgeldin! {{ $user->name ?? 'Kullanıcı' }}
        </div>
        <div class="card shadow-sm border-0 mb-0" style="min-width:180px;">
            <div class="card-body d-flex align-items-center gap-2 p-2">
                <i class="fa {{ $weather['icon'] ?? 'fa-cloud-sun' }} fa-2x text-primary"></i>
                <div>
                    <div class="fw-bold">{{ $weather['city'] ?? 'Konya' }}</div>
                    <div class="small">{{ $weather['temperature'] ?? '24°C' }}, {{ $weather['condition'] ?? 'Parçalı Bulutlu' }}</div>
                </div>
            </div>
        </div>
        <div class="btn-group">
            <button class="btn btn-light shadow-sm py-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell"></i>
                @if($notifications->count() > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $notifications->count() }}</span>
                @endif
            </button>   
            <ul class="dropdown-menu">
                @forelse($notifications as $notification)
                    <li>
                        <a class="dropdown-item" href="{{ $notification['url'] ?? '#' }}">
                            <i class="fa {{ $notification['icon'] }} text-{{ $notification['type'] }} me-2"></i>
                            {{ $notification['title'] }}
                            <small class="text-muted d-block">{{ $notification['message'] }}</small>
                        </a>
                    </li>
                @empty
                    <li><span class="dropdown-item text-muted">Bildirim bulunmuyor</span></li>
                @endforelse
            </ul>
        </div>

    </div>
    <div class="col-lg-4 d-flex justify-content-end align-items-center gap-3">
        <div class="card border-0 shadow-sm mb-0" style="min-width:120px;">
            <div class="card-body py-2 px-3 d-flex align-items-center gap-2">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'Admin') }}&background=6366f1&color=fff&rounded=true"
                    width="32" height="32" alt="Avatar">
                <div>
                    <div class="fw-bold small mb-0">{{ $user->name ?? 'Admin' }}</div>
                    <div class="text-muted small">{{ $user->role ?? 'Yönetici' }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger ms-2" title="Çıkış">
                        <i class="fa fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
