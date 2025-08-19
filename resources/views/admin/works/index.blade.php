@extends('layouts.admin')
@section('content')
    @vite('resources/css/works.css')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa fa-home"></i> Anasayfa</a>
            </li>
            <li class="breadcrumb-item"><a href="/admin/" class="text-decoration-none">Yönetim</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Zimmet' }}</li>
        </ol>
    </nav>

    <div class="container mt-4">
        <div class="card shadow-lg border-0 mb-4 modern-card">
            <div class="card-header text-white d-flex align-items-center modern-gradient rounded-top">
                <i class="fas fa-plus-circle fa-lg me-2"></i>
                <h4 class="mb-0" style="color: #fff;">Yeni zimmet al</h4>
            </div>
            <div class="card-body p-4 bg-light rounded-bottom">
                <form action="{{ route('assignments.store') }}" method="POST" enctype="multipart/form-data"
                    autocomplete="off">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold"><i class="fas fa-boxes me-1"></i> Götürülecek Ekipmanlar</label>
                        <div class="small text-muted mb-2">Ekipman seçin, adet girin. Her ekipman için fotoğraf yükleyin.
                        </div>
                        <div id="equipment-list">
                            <div
                                class="row g-2 align-items-end equipment-row mb-3 py-3 px-2 rounded modern-row bg-white shadow-sm position-relative">
                                <div class="col-md-4 d-flex align-items-center">
                                    <select name="equipment_id[]" class="form-select equipment-select modern-input select2"
                                        required>
                                        <option value="">Ekipman Seç</option>
                                        @foreach ($equipmentStocks as $stock)
                                            @if ($stock->equipment)
                                                <option value="{{ $stock->equipment->id }}"
                                                    data-individual="{{ $stock->equipment->individual_tracking }}"
                                                    data-stock="{{ $stock->quantity }}">
                                                    {{ $stock->equipment->name }} {{ $stock->code }}
                                                </option>
                                            @endif
                                        @endforeach


                                    </select>
                                </div>

                                <div class="col-md-3 d-flex align-items-center">
                                    <span class="badge bg-success me-2"><i class="fas fa-hashtag"></i></span>
                                    <input type="number" name="quantity[]" class="form-control equipment-qty modern-input"
                                        min="1" value="1" placeholder="Örn: 2" required>
                                </div>

                                <div class="col-md-2 text-end">
                                    <button type="button" class="btn btn-outline-danger remove-equipment w-100"
                                        title="Ekipmanı kaldır"><i class="fas fa-trash"></i></button>
                                </div>

                                <div class="col-12 mt-3 equipment-photos"></div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-equipment"><i
                                class="fas fa-plus"></i> Ekipman Ekle</button>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold"><i class="fas fa-sticky-note me-1"></i> Notlar</label>
                        <textarea class="form-control modern-input" name="note" rows="2" placeholder="Ek bilgi (opsiyonel)"></textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-gradient btn-lg px-4"><i class="fas fa-save me-1"></i>
                            Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @vite('resources/js/works.js')
@endsection
