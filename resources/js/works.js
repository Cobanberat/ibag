function updatePhotoInputs(equipmentRow) {
    const qtyInput = equipmentRow.querySelector('.equipment-qty');
    const photosDiv = equipmentRow.querySelector('.equipment-photos');
    let qty = parseInt(qtyInput.value) || 1;
    photosDiv.innerHTML = '';
    const eqName = equipmentRow.querySelector('.equipment-select').value;
    
    for (let i = 1; i <= qty; i++) {
        const itemBox = document.createElement('div');
        itemBox.className = 'equipment-item-box shadow-sm rounded d-inline-block p-3 m-2 bg-white';
        itemBox.style.minWidth = '200px';
        itemBox.innerHTML = `
            <div class='mb-2 fw-bold text-primary border-bottom pb-1'>
                <i class='fas fa-cube me-1'></i> ${eqName || 'Ekipman'} 
                <span class='badge bg-gradient text-white ms-1'>${i}</span>
            </div>
            <div class='mb-2'>
                <label class='form-label small fw-bold text-muted'><i class='fas fa-barcode me-1'></i> Ekipman Kodu ${i}</label>
                <input type='text' class='form-control form-control-sm equipment-code-input' name='equipment_code[]' placeholder='Kod ${i}' required>
            </div>
            <div class='mb-1'>
                <label class='form-label small fw-bold text-muted'><i class='fas fa-camera me-1'></i> Fotoğraf ${i}</label>
                <input type='file' class='form-control form-control-sm' name='equipment_photo[]' accept='image/*' required>
            </div>
        `;
        photosDiv.appendChild(itemBox);
    }
    photosDiv.style.display = qty > 0 ? '' : 'none';
}
document.addEventListener('DOMContentLoaded', function() {
    // Ekipman ekle/çıkar
    document.getElementById('add-equipment').onclick = function() {
        const row = document.querySelector('.equipment-row').cloneNode(true);
        row.querySelector('select').value = '';
        row.querySelector('.equipment-qty').value = 1;
        row.querySelector('.equipment-code').value = '';
        row.querySelector('.equipment-qty').disabled = true;
        row.querySelector('.equipment-code').disabled = true;
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
            const codeInput = row.querySelector('.equipment-code');
            if (e.target.value) {
                qtyInput.disabled = false;
                codeInput.disabled = false;
                updatePhotoInputs(row);
            } else {
                qtyInput.disabled = true;
                codeInput.disabled = true;
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
        row.querySelector('.equipment-code').disabled = true;
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