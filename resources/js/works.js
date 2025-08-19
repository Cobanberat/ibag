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

        photosDiv.innerHTML = '';

        if (individual) {
            qtyInput.value = 1;
            qtyInput.min = 1;
            qtyInput.max = 1;
            qtyInput.readOnly = true;
            photosDiv.innerHTML = `<input type="file" name="equipment_photo[]" class="form-control" required>`;
        } else {
            qtyInput.readOnly = false;
            qtyInput.min = 1;
            qtyInput.max = stock;
            if (parseInt(qtyInput.value) > stock) qtyInput.value = stock;
            photosDiv.innerHTML = `<input type="file" name="equipment_photo[]" class="form-control" required>`;
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
        newRow.find('.equipment-qty').val(1).prop('readonly', true).prop('max', 1);
        newRow.find('.equipment-photos').hide().empty();

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
