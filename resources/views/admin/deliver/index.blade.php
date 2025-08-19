@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        {{-- Sekmeler --}}
        <ul class="nav nav-tabs mb-3" id="assignmentTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="current-tab" data-bs-toggle="tab" data-bs-target="#current" type="button"
                    role="tab">
                    Aldıklarım
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button"
                    role="tab">
                    Geçmiş Zimmetler
                </button>
            </li>
        </ul>

        <div class="tab-content" id="assignmentTabsContent">
            {{-- Aldıklarım --}}
            <div class="tab-pane fade show active" id="current" role="tabpanel">
                <div class="row g-4">
                    @forelse($assignments->where('status', 0) as $index => $assignment)
                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-lg border-primary rounded-4 hover-card">
                                <div
                                    class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                                    <h6 class="mb-0">Zimmet #{{ $index + 1 }}</h6>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-light text-primary" data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $assignment->id }}">
                                            <i data-feather="eye"></i> Detay
                                        </button>
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#returnModal{{ $assignment->id }}">
                                            <i data-feather="corner-up-left"></i> Teslim Et
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="mb-2"><strong>Not:</strong> {{ $assignment->note ?? '-' }}</p>
                                    <p class="mb-0"><strong>Toplam Ekipman:</strong> {{ $assignment->items->count() }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Detay Modal --}}
                        <div class="modal fade" id="detailModal{{ $assignment->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Zimmet #{{ $index + 1 }} Detay</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            @foreach ($assignment->items as $item)
                                                <div class="col-4 text-center">
                                                    @if ($item->photo_path)
                                                        <img src="{{ asset('storage/' . $item->photo_path) }}"
                                                            alt="Ekipman"
                                                            class="img-fluid rounded mb-1 border border-secondary p-1">
                                                    @else
                                                        <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center"
                                                            style="height:80px;">-</div>
                                                    @endif
                                                    <small
                                                        class="d-block mt-1">{{ $item->equipment?->name ?? 'Bilinmiyor' }}</small>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Teslim Et Modal --}}
                        {{-- Teslim Et Modal --}}
                        <div class="modal fade" id="returnModal{{ $assignment->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Zimmet #{{ $index + 1 }} Teslim</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('admin.teslimAl', $assignment->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            @foreach ($assignment->items as $key => $item)
                                               
                                                    <div class="mb-3">
                                                        <label>{{ $item->equipment->name }} (#{{ $key + 1 }})
                                                            Fotoğraf:</label>
                                                        <input type="file" name="return_photos[{{ $item->id }}]"
                                                            class="form-control" required>
                                                    </div>
                                                     @if ($item->equipment->individual_tracking == 0)
                                                    <div class="mb-3">
                                                        <label>Kaç tane kullandınız?</label>
                                                        <input type="number" name="used_qty[{{ $item->id }}]"
                                                            class="form-control" min="1" max="{{ $item->quantity }}"
                                                            value="{{ $item->quantity }}" required>
                                                    </div>
                                                    @endif
                                                    
                                            @endforeach

                                            <div class="mb-3">
                                                <label>Arıza Notu (opsiyonel):</label>
                                                <textarea name="damage_note" class="form-control" rows="2"></textarea>
                                            </div>

                                            <button type="submit" class="btn btn-success w-100">Teslim Et</button>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>


                    @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i data-feather="info"></i> Şu an size teslim edilmiş ekipman bulunmamaktadır.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Geçmiş Zimmetler --}}
            <div class="tab-pane fade" id="history" role="tabpanel">
                <div class="row g-4">
                    @forelse($assignments->where('status', 1) as $index => $assignment)
                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-lg border-secondary rounded-4 hover-card">
                                <div
                                    class="card-header bg-gradient-secondary text-white d-flex justify-content-between align-items-center rounded-top-4">
                                    <h6 class="mb-0">Zimmet #{{ $index + 1 }}</h6>
                                    <button class="btn btn-sm btn-light text-secondary" data-bs-toggle="modal"
                                        data-bs-target="#detailModalHistory{{ $assignment->id }}">
                                        <i data-feather="eye"></i> Detay
                                    </button>
                                </div>
                                <div class="card-body">
                                    <p class="mb-2"><strong>Not:</strong> {{ $assignment->note ?? '-' }}</p>
                                    <p class="mb-0"><strong>Toplam Ekipman:</strong> {{ $assignment->items->count() }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Detay Modal --}}
                        <div class="modal fade" id="detailModalHistory{{ $assignment->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Zimmet #{{ $index + 1 }} Detay</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            @foreach ($assignment->items as $item)
                                                <div class="col-4 text-center">
                                                    @if ($item->photo_path)
                                                        <img src="{{ asset('storage/' . $item->photo_path) }}"
                                                            alt="Ekipman"
                                                            class="img-fluid rounded mb-1 border border-secondary p-1">
                                                    @else
                                                        <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center"
                                                            style="height:80px;">-</div>
                                                    @endif
                                                    <small
                                                        class="d-block mt-1">{{ $item->equipment?->name ?? 'Bilinmiyor' }}</small>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="col-12">
                            <div class="alert alert-secondary text-center">
                                <i data-feather="archive"></i> Önceki teslim edilmiş zimmet bulunmamaktadır.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .hover-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .bg-gradient-primary {
            background: linear-gradient(90deg, #4e73df, #1cc88a);
        }

        .bg-gradient-secondary {
            background: linear-gradient(90deg, #858796, #6c757d);
        }

        .card-header button {
            font-size: 0.8rem;
        }
    </style>
@endsection

@section('js')
    <script>
        feather.replace()
    </script>
@endsection
