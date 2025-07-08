@extends('layouts.admin')
@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 mb-4 modern-card">
        <div class="card-header text-white d-flex align-items-center modern-gradient rounded-top">
            <i class="fas fa-plus-circle fa-lg me-2"></i>
            <h4 class="mb-0">Yeni İş Ekle</h4>
        </div>
        <div class="card-body p-4 bg-light rounded-bottom">
            <form autocomplete="off">
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="fas fa-map-marker-alt me-1"></i> İl</label>
                        <input type="text" class="form-control modern-input" placeholder="Konya" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="fas fa-map-pin me-1"></i> İlçe</label>
                        <input type="text" class="form-control modern-input" placeholder="Selçuklu" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="fas fa-location-arrow me-1"></i> Mahalle</label>
                        <input type="text" class="form-control modern-input" placeholder="Bosna Hersek" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="fas fa-road me-1"></i> Açık Adres</label>
                        <input type="text" class="form-control modern-input" placeholder="Örn: Hoca Ahmet Yesevi Cad. No:12 Daire:5" required>
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold"><i class="fas fa-calendar-alt me-1"></i> Görev Tarihi</label>
                        <input type="date" class="form-control modern-input" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-bold"><i class="fas fa-users me-1"></i> Giden Kişiler</label>
                        <div class="small text-muted mb-2">Çalışan seç, ekle diyince aşağıya düşer.</div>
                        <div class="row g-2 align-items-end mb-2 py-3 px-2 rounded modern-row bg-white shadow-sm position-relative justify-content-center" id="person-form-row">
                            <div class="col-md-8 d-flex align-items-center justify-content-center">
                                <span class="badge bg-primary me-2"><i class="fas fa-user"></i></span>
                                <select class="form-select modern-input" id="person-select">
                                    <option value="">Çalışan Seç</option>
                                    <option value="Berat Çoban">Berat Çoban</option>
                                    <option value="Ayşe Yılmaz">Ayşe Yılmaz</option>
                                    <option value="Mehmet Demir">Mehmet Demir</option>
                                    <option value="Zeynep Kaya">Zeynep Kaya</option>
                                </select>
                            </div>
                            <div class="col-md-3 text-center">
                                <button type="button" class="btn btn-outline-primary btn-lg px-4" id="add-person-btn"><i class="fas fa-user-plus"></i> Ekle</button>
                            </div>
                        </div>
                        <div class="table-responsive mb-2">
                            <table class="table table-bordered table-striped align-middle mb-0" id="person-list-table" style="display:none;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Çalışan</th>
                                        <th style="width:40px"></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold"><i class="fas fa-boxes me-1"></i> Götürülecek Ekipmanlar</label>
                    <div class="small text-muted mb-2">Ekipman seçin, adet girin. Her ekipman için adet kadar fotoğraf yükleyin.</div>
                    <div id="equipment-list">
                        <div class="row g-2 align-items-end equipment-row mb-3 py-3 px-2 rounded modern-row bg-white shadow-sm position-relative">
                            <div class="col-md-6 d-flex align-items-center">
                                <span class="badge bg-secondary me-2"><i class="fas fa-cube"></i></span>
                                <select class="form-select equipment-select modern-input" required>
                                    <option value="">Ekipman Seç</option>
                                    <option>UPS 3kVA</option>
                                    <option>Kask</option>
                                    <option>Jeneratör</option>
                                    <option>Kırıcı</option>
                                    <option>Akü</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-center">
                                <span class="badge bg-success me-2"><i class="fas fa-hashtag"></i></span>
                                <input type="number" class="form-control equipment-qty modern-input" min="1" max="50" value="1" placeholder="Örn: 2" required>
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="button" class="btn btn-outline-danger remove-equipment w-100" title="Ekipmanı kaldır"><i class="fas fa-trash"></i></button>
                            </div>
                            <div class="col-12 mt-2 equipment-photos" style="display:none;"></div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-equipment"><i class="fas fa-plus"></i> Ekipman Ekle</button>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold"><i class="fas fa-sticky-note me-1"></i> Notlar</label>
                    <textarea class="form-control modern-input" rows="2" placeholder="Ek bilgi (opsiyonel)"></textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-gradient btn-lg px-4"><i class="fas fa-save me-1"></i> Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function updatePhotoInputs(equipmentRow) {
    const qtyInput = equipmentRow.querySelector('.equipment-qty');
    const photosDiv = equipmentRow.querySelector('.equipment-photos');
    let qty = parseInt(qtyInput.value) || 1;
    photosDiv.innerHTML = '';
    const eqName = equipmentRow.querySelector('.equipment-select').value;
    for (let i = 1; i <= qty; i++) {
        const photoBox = document.createElement('div');
        photoBox.className = 'photo-upload-box shadow-sm rounded d-inline-block p-2 m-1 bg-white';
        photoBox.style.minWidth = '160px';
        photoBox.innerHTML = `
            <div class='mb-1 fw-bold text-primary'><i class='fas fa-cube me-1'></i> ${eqName || 'Ekipman'} <span class='badge bg-gradient text-white ms-1'>${i}</span></div>
            <input type='file' class='form-control form-control-sm mb-1' name='equipment_photo[]'>
        `;
        photosDiv.appendChild(photoBox);
    }
    photosDiv.style.display = qty > 0 ? '' : 'none';
}
document.addEventListener('DOMContentLoaded', function() {
    // Ekipman ekle/çıkar
    document.getElementById('add-equipment').onclick = function() {
        const row = document.querySelector('.equipment-row').cloneNode(true);
        row.querySelector('select').value = '';
        row.querySelector('.equipment-qty').value = 1;
        row.querySelector('.equipment-qty').disabled = true;
        row.querySelector('.equipment-photos').style.display = 'none';
        updatePhotoInputs(row);
        document.getElementById('equipment-list').appendChild(row);
    };
    document.getElementById('equipment-list').addEventListener('click', function(e) {
        if (e.target.closest('.remove-equipment')) {
            const rows = document.querySelectorAll('.equipment-row');
            if (rows.length > 1) e.target.closest('.equipment-row').remove();
        }
    });
    // Ekipman seçilmeden adet ve fotoğraf pasif
    document.getElementById('equipment-list').addEventListener('change', function(e) {
        if (e.target.classList.contains('equipment-select')) {
            const row = e.target.closest('.equipment-row');
            const qtyInput = row.querySelector('.equipment-qty');
            if (e.target.value) {
                qtyInput.disabled = false;
                row.querySelector('.equipment-photos').style.display = '';
                updatePhotoInputs(row);
            } else {
                qtyInput.disabled = true;
                row.querySelector('.equipment-photos').style.display = 'none';
            }
        }
    });
    // Fotoğraf alanlarını adet kadar güncelle
    document.getElementById('equipment-list').addEventListener('input', function(e) {
        if (e.target.classList.contains('equipment-qty')) {
            const row = e.target.closest('.equipment-row');
            updatePhotoInputs(row);
        }
    });
    // Sayfa yüklenince ilk satır için fotoğraf alanı oluştur
    document.querySelectorAll('.equipment-row').forEach(row => {
        row.querySelector('.equipment-qty').disabled = true;
        row.querySelector('.equipment-photos').style.display = 'none';
        updatePhotoInputs(row);
    });
    // Giden Kişiler: Select ile ekleme ve tabloya düşürme
    const personSelect = document.getElementById('person-select');
    const addPersonBtn = document.getElementById('add-person-btn');
    const personListTable = document.getElementById('person-list-table');
    const personListTbody = personListTable.querySelector('tbody');

    addPersonBtn.onclick = function() {
        const name = personSelect.value;
        if (!name) { personSelect.classList.add('is-invalid'); return; } else { personSelect.classList.remove('is-invalid'); }
        // Tabloya ekle
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${name}</td><td><button type="button" class="btn btn-sm btn-danger remove-person-row"><i class="fas fa-trash"></i></button></td>`;
        personListTbody.appendChild(tr);
        personListTable.style.display = '';
        // Temizle
        personSelect.value = '';
    };
    personListTbody.addEventListener('click', function(e) {
        if (e.target.closest('.remove-person-row')) {
            e.target.closest('tr').remove();
            if (personListTbody.children.length === 0) personListTable.style.display = 'none';
        }
    });
});
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('equipment-qty')) {
        if (parseInt(e.target.value) > 50) {
            e.target.value = 50;
        }
        if (parseInt(e.target.value) < 1) {
            e.target.value = 1;
        }
        // Değeri düzelttikten sonra tekrar fotoğraf kutularını güncelle
        const row = e.target.closest('.equipment-row');
        updatePhotoInputs(row);
    }
});
</script>
<style>
.modern-card {
    border-radius: 1.2rem !important;
    overflow: hidden;
    box-shadow: 0 4px 32px #0d6efd18 !important;
}
.modern-gradient {
    background: linear-gradient(90deg, #0d6efd 60%, #36b3f6 100%) !important;
    border-top-left-radius: 1.2rem !important;
    border-top-right-radius: 1.2rem !important;
    box-shadow: 0 2px 12px #0d6efd22;
}
.bg-gradient {
    background: linear-gradient(90deg, #36b3f6 0%, #0d6efd 100%) !important;
}
.btn-gradient {
    background: linear-gradient(90deg, #36b3f6 0%, #0d6efd 100%) !important;
    color: #fff !important;
    border: none;
    box-shadow: 0 2px 8px #0d6efd22;
    font-weight: 600;
    letter-spacing: 0.01em;
    border-radius: 0.7rem;
    transition: background 0.2s;
}
.btn-gradient:hover {
    background: linear-gradient(90deg, #0d6efd 0%, #36b3f6 100%) !important;
    color: #fff !important;
}
.modern-row {
    border-radius: 1rem !important;
    border: 1.5px solid #e3eafc !important;
    margin-bottom: 1.1rem !important;
    transition: box-shadow 0.2s, background 0.2s;
}
.modern-row:hover {
    box-shadow: 0 4px 16px #0d6efd22 !important;
    background: #f0f6ff !important;
}
.modern-input {
    border-radius: 0.7rem !important;
    border: 1.5px solid #d1d5db !important;
    background: #f8fafc !important;
    font-weight: 500;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.modern-input:focus {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 2px #0d6efd22 !important;
    outline: none !important;
}
.photo-upload-box {
    border: 1.5px solid #e3eafc;
    border-radius: 0.8rem;
    background: #f8fafc;
    min-height: 80px;
    margin-bottom: 0.5rem;
    transition: box-shadow 0.2s, border 0.2s;
}
.photo-upload-box:hover {
    box-shadow: 0 2px 12px #0d6efd22;
    border-color: #36b3f6;
}
.badge.bg-gradient {
    background: linear-gradient(90deg, #36b3f6 0%, #0d6efd 100%) !important;
    color: #fff !important;
}
</style>
@endsection