// Equipment sayfası için basit filtreleme sistemi
document.addEventListener('DOMContentLoaded', function() {
    // Filtreleme için event listener'ları ekle
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const codeFilter = document.getElementById('codeFilter');
    const clearFiltersBtn = document.getElementById('clearFilters');

    // Arama filtresi
    if (searchInput) {
        searchInput.addEventListener('input', filterTable);
    }

    // Kategori filtresi
    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterTable);
    }

    // Kod filtresi
    if (codeFilter) {
        codeFilter.addEventListener('input', filterTable);
    }

    // Filtreleri temizle butonu
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (categoryFilter) categoryFilter.value = '';
            if (codeFilter) codeFilter.value = '';
            filterTable();
        });
    }

    // Tablo filtreleme fonksiyonu
    function filterTable() {
        const searchValue = searchInput ? searchInput.value.toLowerCase() : '';
        const categoryValue = categoryFilter ? categoryFilter.value : '';
        const codeValue = codeFilter ? codeFilter.value.toLowerCase() : '';

        const tbody = document.querySelector('#equipmentTable tbody');
        const rows = tbody.querySelectorAll('tr[data-id]');

        let visibleCount = 0;

        rows.forEach(row => {
            let show = true;

            // Arama filtresi
            if (searchValue) {
                const text = row.textContent.toLowerCase();
                if (!text.includes(searchValue)) {
                    show = false;
                }
            }

            // Kategori filtresi
            if (categoryValue && show) {
                const categoryId = row.getAttribute('data-category');
                if (categoryId !== categoryValue) {
                    show = false;
                }
            }

            // Kod filtresi
            if (codeValue && show) {
                const codeCell = row.querySelector('td:nth-child(2)');
                if (codeCell && !codeCell.textContent.toLowerCase().includes(codeValue)) {
                    show = false;
                }
            }

            // Satırı göster/gizle
            if (show) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Sonuç mesajı
        if (visibleCount === 0) {
            const noResultsRow = tbody.querySelector('tr.no-results');
            if (!noResultsRow) {
                const tr = document.createElement('tr');
                tr.className = 'no-results';
                tr.innerHTML = '<td colspan="12" class="text-center py-4"><i class="fas fa-search fa-2x text-muted mb-2"></i><p class="text-muted">Filtre kriterlerine uygun ekipman bulunamadı</p></td>';
                tbody.appendChild(tr);
            }
        } else {
            const noResultsRow = tbody.querySelector('tr.no-results');
            if (noResultsRow) {
                noResultsRow.remove();
            }
        }
    }

    // CSV export
    const exportCsvBtn = document.getElementById('exportCsvBtn');
    if (exportCsvBtn) {
        exportCsvBtn.addEventListener('click', function() {
            window.location.href = '/admin/ekipmanlar/export/csv';
        });
    }

    // Seçili silme
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
    if (deleteSelectedBtn) {
        deleteSelectedBtn.addEventListener('click', function() {
            const selectedCheckboxes = document.querySelectorAll('.equipment-checkbox:checked');
            if (selectedCheckboxes.length === 0) {
                alert('Lütfen silinecek ekipmanları seçin');
                return;
            }

            if (confirm(`${selectedCheckboxes.length} ekipmanı silmek istediğinizden emin misiniz?`)) {
                const ids = Array.from(selectedCheckboxes).map(cb => cb.value);
                // AJAX ile silme işlemi yapılabilir
                console.log('Silinecek ekipmanlar:', ids);
            }
        });
    }

    // Checkbox event listener'ları
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.equipment-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = this.checked;
            });
            updateDeleteButton();
        });
    }

    // Tekil checkbox'lar için
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('equipment-checkbox')) {
            updateDeleteButton();
        }
    });

    function updateDeleteButton() {
        const selectedCount = document.querySelectorAll('.equipment-checkbox:checked').length;
        const deleteBtn = document.getElementById('deleteSelectedBtn');
        if (deleteBtn) {
            deleteBtn.disabled = selectedCount === 0;
            deleteBtn.innerHTML = `<i class="fas fa-trash"></i> Seçiliyi Sil (${selectedCount})`;
        }
    }
});

// Global fonksiyonlar
window.showDetail = function(id) {
    // AJAX ile ekipman detayını çek
    fetch(`/admin/ekipmanlar/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const stock = data.data;
                
                // Modal içeriğini doldur
                document.getElementById('detailSno').innerText = stock.id;
                document.getElementById('detailCode').innerText = stock.code || '-';
                document.getElementById('detailType').innerText = stock.equipment?.name || '-';
                document.getElementById('detailBrand').innerText = stock.brand || '-';
                document.getElementById('detailModel').innerText = stock.model || '-';
                document.getElementById('detailSize').innerText = stock.size || '-';
                document.getElementById('detailFeature').innerText = stock.feature || '-';
                document.getElementById('detailCount').innerText = stock.quantity || 0;
                document.getElementById('detailStatus').innerText = stock.status || '-';
                document.getElementById('detailDate').innerText = stock.created_at ? new Date(stock.created_at).toLocaleDateString('tr-TR') : '-';
                document.getElementById('detailNote').innerText = stock.note || '-';

                // Modal'ı göster
                new bootstrap.Modal(document.getElementById('detailModal')).show();
            } else {
                alert('Ekipman detayı yüklenirken hata oluştu');
            }
        })
        .catch(error => {
            console.error('Detay yükleme hatası:', error);
            alert('Ekipman detayı yüklenirken hata oluştu');
        });
};

window.deleteEquipment = function(id) {
    if (confirm('Bu ekipmanı silmek istediğinizden emin misiniz?')) {
        fetch(`/admin/ekipmanlar/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Satırı tablodan kaldır
                const row = document.querySelector(`tr[data-id="${id}"]`);
                if (row) {
                    row.remove();
                }
                alert('Ekipman başarıyla silindi');
            } else {
                alert('Ekipman silinirken hata oluştu');
            }
        })
        .catch(error => {
            console.error('Silme hatası:', error);
            alert('Ekipman silinirken hata oluştu');
        });
    }
};
