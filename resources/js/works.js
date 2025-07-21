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