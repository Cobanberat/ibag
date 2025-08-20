$(document).ready(function () {
    function initSelect2(elem) {
        $(elem).select2({
            placeholder: "Ekipman Seç",
            allowClear: true,
            width: '100%'
        });
    }

    function refreshOptions() {
        let selectedIds = [];
        $('.equipment-select').each(function () {
            const val = $(this).val();
            if (val) selectedIds.push(val);
        });

        $('.equipment-select').each(function () {
            const currentVal = $(this).val();
            $(this).find('option').each(function () {
                if ($(this).val() === "") return;
                if (selectedIds.includes($(this).val()) && $(this).val() != currentVal) {
                    $(this).attr('disabled', true);
                } else {
                    $(this).attr('disabled', false);
                }
            });
        });
    }

    initSelect2('.equipment-select');

    function updatePhotoInputs(row) {
        const select = row.querySelector('.equipment-select');
        const qtyInput = row.querySelector('.equipment-qty');
        const photosDiv = row.querySelector('.equipment-photos');
        const selectedOption = select.selectedOptions[0];
        const individual = selectedOption?.dataset.individual == '1';
        const stock = parseInt(selectedOption?.dataset.stock || 1);
        const equipmentName = selectedOption?.text || 'Ekipman';

        photosDiv.innerHTML = '';

        if (individual) {
            qtyInput.value = 1;
            qtyInput.min = 1;
            qtyInput.max = 1;
            qtyInput.readOnly = true;
            photosDiv.innerHTML = `
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>${equipmentName}</strong> için fotoğraf yükleyin
                </div>
                <div class="mt-2">
                    <label class="form-label fw-bold">
                        <i class="fas fa-camera me-1"></i>Fotoğraf:
                    </label>
                    <input type="file" name="equipment_photo[]" class="form-control" accept="image/*" required>
                    <small class="text-muted">Ekipmanın mevcut durumunu gösteren fotoğraf çekin</small>
                </div>
            `;
        } else {
            qtyInput.readOnly = false;
            qtyInput.min = 1;
            qtyInput.max = stock;
            if (parseInt(qtyInput.value) > stock) qtyInput.value = stock;
            photosDiv.innerHTML = `
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>${equipmentName}</strong> için fotoğraf yükleyin
                </div>
                <div class="mt-2">
                    <label class="form-label fw-bold">
                        <i class="fas fa-camera me-1"></i>Fotoğraf:
                    </label>
                    <input type="file" name="equipment_photo[]" class="form-control" accept="image/*" required>
                    <small class="text-muted">Ekipmanın mevcut durumunu gösteren fotoğraf çekin</small>
                </div>
            `;
        }

        photosDiv.style.display = 'block';
    }

    // Ekipman seçimi değişince
    $('#equipment-list').on('change', '.equipment-select', function () {
        const row = $(this).closest('.equipment-row')[0];
        updatePhotoInputs(row);
        refreshOptions();
    });

    // Adet input değişirse stok sınırını kontrol et
    $('#equipment-list').on('input', '.equipment-qty', function () {
        const row = $(this).closest('.equipment-row')[0];
        const select = row.querySelector('.equipment-select');
        const stock = parseInt(select.selectedOptions[0]?.dataset.stock || 1);
        let qty = parseInt(this.value) || 1;

        if (qty > stock) this.value = stock;
        else if (qty < 1) this.value = 1;

        updatePhotoInputs(row);
    });

    // Ekipman ekle
    $('#add-equipment').click(function () {
        const originalRow = $('.equipment-row').first();
        originalRow.find('select').select2('destroy');

        const newRow = originalRow.clone();
        newRow.find('select').val('');
        newRow.find('.equipment-qty').val(1).prop('readonly', false).prop('max', 999);
        newRow.find('.equipment-photos').html(`
            <div class="alert alert-warning mb-0">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Fotoğraf:</strong> Ekipman seçildikten sonra fotoğraf yükleme alanı görünecektir.
            </div>
        `);

        $('#equipment-list').append(newRow);
        initSelect2(originalRow.find('select'));
        initSelect2(newRow.find('select'));
        refreshOptions();
    });

    // Ekipman kaldır
    $('#equipment-list').on('click', '.remove-equipment', function () {
        if ($('.equipment-row').length > 1) {
            $(this).closest('.equipment-row').remove();
            refreshOptions();
        }
    });

    // İlk satırı hazırlama
    $('.equipment-row').each(function () {
        $(this).find('.equipment-qty').prop('readonly', true);
        $(this).find('.equipment-photos').hide().empty();
    });

    refreshOptions();
});
