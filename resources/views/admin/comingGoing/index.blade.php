@extends('layouts.admin')
@section('content')
@vite(['resources/css/comingGoing.css'])

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
        <li class="breadcrumb-item">
            <a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a>
        </li>
        <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Giden-Gelen' }}</li>
    </ol>
</nav>

<div class="animated-title"><i class="fas fa-truck"></i> Giden-Gelen Ekipman İşlemleri</div>

<!-- Sekmeler -->
<ul class="nav nav-tabs approval-tabs mb-3" id="comingGoingTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="giden-tab" data-bs-toggle="tab" data-bs-target="#gidenTab" type="button" role="tab">Gidenler</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="gelen-tab" data-bs-toggle="tab" data-bs-target="#gelenTab" type="button" role="tab">Gelenler</button>
    </li>
</ul>

<div class="tab-content" id="comingGoingTabContent">
    <!-- Gidenler Tab -->
    <div class="tab-pane fade show active" id="gidenTab" role="tabpanel">
        <div class="col-md-12 mb-5">
            <h4 class="mb-3">Gidenler</h4>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Kullanıcı</th>
                            <th>Tarih</th>
                            <th>Detay</th>
                            <th>İşlemi Bitir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gidenAssignments as $assignment)
                        <tr>
                            <td>{{ $assignment->user->name ?? 'Bilinmiyor' }}</td>
                            <td>{{ $assignment->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModalGiden{{ $assignment->id }}">
                                    <i class='fas fa-eye'></i> Detay
                                </button>
                            </td>
                            <td>
                                @if($assignment->status == 0)
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#finishModalGiden{{ $assignment->id }}">
                                    <i class='fas fa-check-circle'></i> İşlemi Bitir
                                </button>
                                @else
                                <span class="text-muted">Tamamlandı</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Giden Detay Modal -->
                        <div class="modal fade" id="detailModalGiden{{ $assignment->id }}" tabindex="-1" aria-labelledby="detailModalGidenLabel{{ $assignment->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title" id="detailModalGidenLabel{{ $assignment->id }}">Giden İşlem Detayı</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Kullanıcı:</strong> {{ $assignment->user->name ?? 'Bilinmiyor' }}</p>
                                        <p><strong>Tarih:</strong> {{ $assignment->created_at->format('d.m.Y H:i') }}</p>
                                        <p><strong>Durum:</strong>
                                            @if($assignment->status == 0)
                                            <span class="badge bg-warning">Devam Ediyor</span>
                                            @else
                                            <span class="badge bg-success">Tamamlandı</span>
                                            @endif
                                        </p>
                                        <p><strong>Ekipmanlar:</strong></p>
                                        <ul class="list-group">
                                            @foreach($assignment->items as $item)
                                            <li class="list-group-item">
                                                {{ $item->equipment->name ?? 'Bilinmeyen Ekipman' }}
                                                @if($item->photo_path)
                                                - <a href="{{ asset($item->photo_path) }}" target="_blank">Fotoğraf</a>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Giden Finish Modal -->
                        <div class="modal fade" id="finishModalGiden{{ $assignment->id }}" tabindex="-1" aria-labelledby="finishModalGidenLabel{{ $assignment->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title" id="finishModalGidenLabel{{ $assignment->id }}">Dönüş İşlemini Tamamla</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                    </div>
                                    <form action="{{ route('assignments.finish', $assignment->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p><strong>Ekipmanlar:</strong></p>
                                            <ul class="list-group">
                                                @foreach($assignment->items as $item)
                                                <li class="list-group-item">
                                                    {{ $item->equipment->name ?? 'Bilinmeyen Ekipman' }}
                                                    @if($item->photo_path)
                                                    - <a href="{{ asset($item->photo_path) }}" target="_blank">Fotoğraf</a>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                            <div class="mt-3">
                                                <label for="noteGiden{{ $assignment->id }}">Not:</label>
                                                <textarea name="note" class="form-control" id="noteGiden{{ $assignment->id }}" rows="3" placeholder="Opsiyonel not..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                                            <button type="submit" class="btn btn-success">Onayla ve Gelenlere Ekle</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Gelenler Tab -->
    <div class="tab-pane fade" id="gelenTab" role="tabpanel">
        <div class="col-md-12 mb-5">
            <h4 class="mb-3">Gelenler</h4>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Kullanıcı</th>
                            <th>Tarih</th>
                            <th>Durum</th>
                            <th>Detay</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gelenAssignments as $assignment)
                        <tr>
                            <td>{{ $assignment->user->name ?? 'Bilinmiyor' }}</td>
                            <td>{{ $assignment->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                @if($assignment->status == 0)
                                <span class="badge bg-warning">Devam Ediyor</span>
                                @else
                                <span class="badge bg-success">Tamamlandı</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModalGelen{{ $assignment->id }}">
                                    <i class='fas fa-eye'></i> Detay
                                </button>
                            </td>
                            <td>
                                @if($assignment->status == 0)
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#finishModalGelen{{ $assignment->id }}">
                                    <i class='fas fa-check-circle'></i> İşlemi Bitir
                                </button>
                                @else
                                <span class="text-muted">Tamamlandı</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Gelen Detay Modal -->
                        <div class="modal fade" id="detailModalGelen{{ $assignment->id }}" tabindex="-1" aria-labelledby="detailModalGelenLabel{{ $assignment->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title" id="detailModalGelenLabel{{ $assignment->id }}">Gelen İşlem Detayı</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Kullanıcı:</strong> {{ $assignment->user->name ?? 'Bilinmiyor' }}</p>
                                        <p><strong>Tarih:</strong> {{ $assignment->created_at->format('d.m.Y H:i') }}</p>
                                        <p><strong>Durum:</strong>
                                            @if($assignment->status == 0)
                                            <span class="badge bg-warning">Devam Ediyor</span>
                                            @else
                                            <span class="badge bg-success">Tamamlandı</span>
                                            @endif
                                        </p>
                                        <p><strong>Ekipmanlar:</strong></p>
                                        <ul class="list-group">
                                            @foreach($assignment->items as $item)
                                            <li class="list-group-item">
                                                {{ $item->equipment->name ?? 'Bilinmeyen Ekipman' }}
                                                @if($item->photo_path)
                                                - <a href="{{ asset($item->photo_path) }}" target="_blank">Fotoğraf</a>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gelen Finish Modal -->
                        <div class="modal fade" id="finishModalGelen{{ $assignment->id }}" tabindex="-1" aria-labelledby="finishModalGelenLabel{{ $assignment->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title" id="finishModalGelenLabel{{ $assignment->id }}">Dönüş İşlemini Tamamla</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                    </div>
                                    <form action="{{ route('assignments.finish', $assignment->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p><strong>Ekipmanlar:</strong></p>
                                            <ul class="list-group">
                                                @foreach($assignment->items as $item)
                                                <li class="list-group-item">
                                                    {{ $item->equipment->name ?? 'Bilinmeyen Ekipman' }}
                                                    @if($item->photo_path)
                                                    - <a href="{{ asset($item->photo_path) }}" target="_blank">Fotoğraf</a>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                            <div class="mt-3">
                                                <label for="noteGelen{{ $assignment->id }}">Not:</label>
                                                <textarea name="note" class="form-control" id="noteGelen{{ $assignment->id }}" rows="3" placeholder="Opsiyonel not..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                                            <button type="submit" class="btn btn-success">Onayla ve Gelenlere Ekle</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
