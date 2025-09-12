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
                tr.innerHTML = '<td colspan="15" class="text-center py-4"><i class="fas fa-search fa-2x text-muted mb-2"></i><p class="text-muted">Filtre kriterlerine uygun ekipman bulunamadı</p></td>';
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
                Swal.fire(
                    'Uyarı!',
                    'Lütfen silinecek ekipmanları seçin.',
                    'warning'
                );
                return;
            }

            Swal.fire({
                title: 'Emin misiniz?',
                text: `${selectedCheckboxes.length} ekipmanı silmek istediğinizden emin misiniz?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const ids = Array.from(selectedCheckboxes).map(cb => cb.value);
                    // AJAX ile silme işlemi yapılabilir
                    console.log('Silinecek ekipmanlar:', ids);
                    
                    Swal.fire(
                        'Başarılı!',
                        `${selectedCheckboxes.length} ekipman silindi.`,
                        'success'
                    );
                }
            });
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

    // Inline düzenleme özelliği
    initInlineEditing();
});

// Inline düzenleme fonksiyonları
function initInlineEditing() {
    const editableCells = document.querySelectorAll('.editable-cell');
    
    editableCells.forEach(cell => {
        cell.addEventListener('dblclick', function() {
            startEditing(this);
        });
        
        // Hover efekti
        cell.addEventListener('mouseenter', function() {
            if (!this.querySelector('input, select, textarea')) {
                this.style.backgroundColor = '#f8f9fa';
                this.style.cursor = 'pointer';
            }
        });
        
        cell.addEventListener('mouseleave', function() {
            if (!this.querySelector('input, select, textarea')) {
                this.style.backgroundColor = '';
                this.style.cursor = '';
            }
        });
    });
}

function startEditing(cell) {
    const field = cell.getAttribute('data-field');
    const id = cell.getAttribute('data-id');
    const currentValue = cell.textContent.trim();
    
    // Eğer zaten düzenleme modundaysa, çık
    if (cell.querySelector('input, select, textarea')) {
        return;
    }
    
    let input;
    
    // Alan tipine göre input oluştur
    switch(field) {
        case 'code':
            input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control form-control-sm';
            input.value = currentValue === '-' ? '' : currentValue;
            break;
            
        case 'brand':
        case 'model':
        case 'size':
            input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control form-control-sm';
            input.value = currentValue === '-' ? '' : currentValue;
            break;
            
        case 'feature':
        case 'note':
            input = document.createElement('textarea');
            input.className = 'form-control form-control-sm';
            input.rows = 2;
            input.value = currentValue === '-' ? '' : currentValue;
            break;
            
        case 'quantity':
            input = document.createElement('input');
            input.type = 'number';
            input.className = 'form-control form-control-sm';
            input.min = '1';
            input.value = currentValue === '-' ? '' : currentValue;
            break;
            
        case 'equipment_name':
            input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control form-control-sm';
            input.value = currentValue === '-' ? '' : currentValue;
            break;
            
        default:
            return; // Düzenlenemez alan
    }
    
    // Hücre içeriğini temizle ve input'u ekle
    cell.innerHTML = '';
    cell.appendChild(input);
    input.focus();
    input.select();
    
    // Kaydetme ve iptal etme event'leri
    input.isSaving = false; // Çift kaydetme önleme flag'i
    
    input.addEventListener('blur', function() {
        if (!this.isSaving) {
            saveEdit(cell, field, id, this.value);
        }
    });
    
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            if (!this.isSaving) {
                this.isSaving = true;
                saveEdit(cell, field, id, this.value);
            }
        } else if (e.key === 'Escape') {
            e.preventDefault();
            cancelEdit(cell, currentValue);
        }
    });
}

function saveEdit(cell, field, id, newValue) {
    // AJAX ile güncelleme yap
    fetch(`/admin/equipment-stock/${id}/update-field`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            field: field,
            value: newValue
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Başarılı güncelleme
            cell.innerHTML = newValue || '-';
            cell.style.backgroundColor = '#d4edda';
            
            // 2 saniye sonra normal renge dön
            setTimeout(() => {
                cell.style.backgroundColor = '';
            }, 2000);
            
            // Başarı mesajı
            showToast('Güncelleme başarılı!', 'success');
        } else {
            // Hata durumu
            cell.innerHTML = cell.getAttribute('data-original-value') || '-';
            showToast('Güncelleme başarısız: ' + (data.message || 'Bilinmeyen hata'), 'error');
        }
    })
    .catch(error => {
        console.error('Güncelleme hatası:', error);
        cell.innerHTML = cell.getAttribute('data-original-value') || '-';
        showToast('Güncelleme başarısız!', 'error');
    })
    .finally(() => {
        // İşlem tamamlandıktan sonra flag'i sıfırla
        const input = cell.querySelector('input, select, textarea');
        if (input) {
            input.isSaving = false;
        }
    });
}

function cancelEdit(cell, originalValue) {
    cell.innerHTML = originalValue || '-';
}

// Toast mesaj gösterme fonksiyonu
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Toast otomatik kaldırma
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}

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
                document.getElementById('detailQrCode').innerText = stock.qr_code ? 'Mevcut' : 'Yok';
                document.getElementById('detailType').innerText = stock.equipment?.name || '-';
                document.getElementById('detailBrand').innerText = stock.brand || '-';
                document.getElementById('detailModel').innerText = stock.model || '-';
                document.getElementById('detailSize').innerText = stock.size || '-';
                document.getElementById('detailFeature').innerText = stock.feature || '-';
                document.getElementById('detailUnitType').innerText = stock.equipment?.unit_type_label || 'Adet';
                document.getElementById('detailCount').innerText = stock.quantity || 0;
                document.getElementById('detailTrackingType').innerText = stock.equipment?.individual_tracking ? 'Ayrı Takip' : 'Toplu Takip';
                document.getElementById('detailStatus').innerText = stock.status || '-';
                document.getElementById('detailLocation').innerText = stock.location || '-';
                document.getElementById('detailDate').innerText = stock.created_at ? new Date(stock.created_at).toLocaleDateString('tr-TR') : '-';
                document.getElementById('detailNote').innerText = stock.note || '-';

                // Ekipman resmini göster
                const detailImage = document.getElementById('detailImage');
                if (detailImage && stock.equipment && stock.equipment.images && stock.equipment.images.length > 0) {
                    const imageUrl = `/storage/${stock.equipment.images[0].path}`;
                    detailImage.src = imageUrl;
                    detailImage.style.display = 'block';
                    console.log('Resim yüklendi:', imageUrl);
                } else {
                    if (detailImage) {
                        detailImage.style.display = 'none';
                        console.log('Resim bulunamadı');
                    }
                }

                // Modal'ı göster
                new bootstrap.Modal(document.getElementById('detailModal')).show();
            } else {
                Swal.fire(
                    'Hata!',
                    'Ekipman detayı yüklenirken hata oluştu.',
                    'error'
                );
            }
        })
        .catch(error => {
            console.error('Detay yükleme hatası:', error);
            Swal.fire(
                'Hata!',
                'Ekipman detayı yüklenirken hata oluştu.',
                'error'
            );
        });
};

window.deleteEquipment = function(id) {
    Swal.fire({
        title: 'Emin misiniz?',
        text: "Bu ekipmanı silmek istediğinizden emin misiniz?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Evet, sil!',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
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
                    
                    Swal.fire(
                        'Silindi!',
                        'Ekipman başarıyla silindi.',
                        'success'
                    );
                } else {
                    Swal.fire(
                        'Hata!',
                        'Ekipman silinirken hata oluştu.',
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Silme hatası:', error);
                Swal.fire(
                    'Hata!',
                    'Ekipman silinirken hata oluştu.',
                    'error'
                );
            });
        }
    });
};

// Resim modalını açan fonksiyon
window.showImageModal = function(imageUrl, title) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('imageModalLabel');
    
    if (modalImage) modalImage.src = imageUrl;
    if (modalTitle) modalTitle.textContent = title || 'Ekipman Resmi';
    
    new bootstrap.Modal(modal).show();
};

// QR kod modalını açan fonksiyon
window.showQrModal = function(qrCodeBase64, title) {
    const modal = document.getElementById('qrModal');
    const modalQrCode = document.getElementById('modalQrCode');
    const modalTitle = document.getElementById('qrModalLabel');
    
    if (modalQrCode) modalQrCode.src = 'data:image/svg+xml;base64,' + qrCodeBase64;
    if (modalTitle) modalTitle.textContent = title || 'QR Kod';
    
    // QR kod indirme için global değişken
    window.currentQrCodeBase64 = qrCodeBase64;
    
    new bootstrap.Modal(modal).show();
};

// QR kodu indirme fonksiyonu
window.downloadQrCode = function() {
    if (window.currentQrCodeBase64) {
        const link = document.createElement('a');
        link.href = 'data:image/png;base64,' + window.currentQrCodeBase64;
        link.download = 'qr_code.png';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
};

// Excel Import fonksiyonları
document.addEventListener('DOMContentLoaded', function() {
    const importExcelBtn = document.getElementById('importExcelBtn');
    const downloadTemplateBtn = document.getElementById('downloadTemplateBtn');
    const startImportBtn = document.getElementById('startImportBtn');
    const importExcelModal = document.getElementById('importExcelModal');
    const importExcelForm = document.getElementById('importExcelForm');
    const importProgress = document.getElementById('importProgress');
    const importResults = document.getElementById('importResults');
    const importPreview = document.getElementById('importPreview');
    const previewContent = document.getElementById('previewContent');
    const excelFileInput = document.getElementById('excelFile');

    // Excel import butonuna tıklama
    if (importExcelBtn) {
        importExcelBtn.addEventListener('click', function() {
            // Modal'ı sıfırla
            resetImportModal();
            new bootstrap.Modal(importExcelModal).show();
        });
    }

    // Şablon indirme butonuna tıklama
    if (downloadTemplateBtn) {
        downloadTemplateBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Doğrudan link ile indir
            const link = document.createElement('a');
            link.href = '/admin/stock/excel-template';
            link.download = 'ekipman_import_sablonu.xlsx';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    }

    // Import başlatma butonuna tıklama
    if (startImportBtn) {
        startImportBtn.addEventListener('click', function() {
            const fileInput = document.getElementById('excelFile');
            if (!fileInput.files.length) {
                alert('Lütfen bir Excel dosyası seçin.');
                return;
            }

            startImport();
        });
    }

    // Dosya seçildiğinde önizleme yap
    if (excelFileInput) {
        excelFileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                previewExcelFile();
            }
        });
    }

    // Modal'ı sıfırlama fonksiyonu
    function resetImportModal() {
        if (importExcelForm) importExcelForm.reset();
        if (importProgress) importProgress.classList.add('d-none');
        if (importResults) importResults.classList.add('d-none');
        if (importPreview) importPreview.classList.add('d-none');
        if (startImportBtn) startImportBtn.disabled = false;
    }

    // Excel dosyasını önizleme fonksiyonu
    function previewExcelFile() {
        const formData = new FormData();
        formData.append('excel_file', excelFileInput.files[0]);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        // UI'yi güncelle
        if (importPreview) importPreview.classList.remove('d-none');
        if (importResults) importResults.classList.add('d-none');
        if (previewContent) previewContent.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Dosya okunuyor...</div>';

        fetch('/admin/stock/preview-excel', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showPreview(data);
            } else {
                throw new Error(data.message || 'Önizleme oluşturulamadı');
            }
        })
        .catch(error => {
            console.error('Preview error:', error);
            if (previewContent) {
                previewContent.innerHTML = `<div class="alert alert-danger">Hata: ${error.message}</div>`;
            }
        });
    }

    // Önizleme gösterme fonksiyonu
    function showPreview(data) {
        if (!previewContent) return;

        let html = `
            <div class="mb-3">
                <strong>Toplam Satır:</strong> ${data.total_rows} 
                ${data.errors && data.errors.length > 0 ? `<span class="text-danger">(${data.errors.length} hata)</span>` : ''}
            </div>
        `;

        if (data.errors && data.errors.length > 0) {
            html += `
                <div class="alert alert-warning mb-3">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Uyarılar:</h6>
                    <ul class="mb-0">
                        ${data.errors.map(error => `<li>${error}</li>`).join('')}
                    </ul>
                </div>
            `;
        }

        if (data.preview && data.preview.length > 0) {
            html += `
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Satır</th>
                                <th>Kategori</th>
                                <th>Ekipman</th>
                                <th>Kod</th>
                                <th>Marka</th>
                                <th>Model</th>
                                <th>Miktar</th>
                                <th>Takip</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.preview.map(item => `
                                <tr class="${item.errors.length > 0 ? 'table-warning' : ''}">
                                    <td>${item.row}</td>
                                    <td>${item.category_name}</td>
                                    <td>${item.equipment_name}</td>
                                    <td>${item.code}</td>
                                    <td>${item.brand}</td>
                                    <td>${item.model}</td>
                                    <td>${item.quantity}</td>
                                    <td><span class="badge ${item.tracking_type === 'Ayrı Takip' ? 'bg-info' : 'bg-secondary'}">${item.tracking_type}</span></td>
                                    <td>${item.status}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        } else {
            html += '<div class="alert alert-info">Önizlenecek veri bulunamadı.</div>';
        }

        previewContent.innerHTML = html;
    }

    // Import başlatma fonksiyonu
    function startImport() {
        const formData = new FormData(importExcelForm);
        
        // UI'yi güncelle
        if (importProgress) importProgress.classList.remove('d-none');
        if (importResults) importResults.classList.add('d-none');
        if (startImportBtn) startImportBtn.disabled = true;

        // Progress bar'ı başlat
        updateProgress(0, 'Dosya yükleniyor...');

        // AJAX ile dosyayı gönder
        fetch('/admin/stock/import-excel', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.need_confirmation) {
                // Aynı kodlu kayıtlar bulundu → kullanıcıdan onay al
                const list = Array.isArray(data.duplicates) ? data.duplicates.map(x => `• ${x}`).join('\n') : '';
                Swal.fire({
                    title: 'Aynı kodlar bulundu',
                    html: `<div class="text-start"><p>Bu koda sahip kayıtlar bulundu. Otomatik yeni kod atansın mı?</p><pre style="white-space:pre-wrap">${list}</pre></div>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Evet, otomatik ata',
                    cancelButtonText: 'Hayır, atla'
                }).then((result) => {
                    if (result.isConfirmed) {
                        formData.set('auto_assign_codes', '1');
                    } else {
                        formData.set('auto_assign_codes', '0');
                    }
                    // Onay sonrası importu tekrar başlat
                    fetch('/admin/stock/import-excel', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(r => r.json())
                    .then(d => {
                        if (d.success) {
                            updateProgress(100, 'İşlem tamamlandı!');
                            showImportResults(d);
                        } else {
                            throw new Error(d.message || 'Import işlemi başarısız');
                        }
                    })
                    .catch(err => {
                        console.error('Import error:', err);
                        updateProgress(0, 'Hata: ' + err.message);
                        alert('Import işlemi başarısız: ' + err.message);
                    })
                    .finally(() => {
                        if (startImportBtn) startImportBtn.disabled = false;
                    });
                });
            } else if (data.success) {
                updateProgress(100, 'İşlem tamamlandı!');
                showImportResults(data);
            } else {
                throw new Error(data.message || 'Import işlemi başarısız');
            }
        })
        .catch(error => {
            console.error('Import error:', error);
            updateProgress(0, 'Hata: ' + error.message);
            alert('Import işlemi başarısız: ' + error.message);
        })
        .finally(() => {
            if (startImportBtn) startImportBtn.disabled = false;
        });
    }

    // Progress güncelleme fonksiyonu
    function updateProgress(percent, status) {
        const progressBar = document.querySelector('#importProgress .progress-bar');
        const statusText = document.getElementById('importStatus');
        
        if (progressBar) {
            progressBar.style.width = percent + '%';
            progressBar.setAttribute('aria-valuenow', percent);
        }
        
        if (statusText) {
            statusText.textContent = status;
        }
    }

    // Import sonuçlarını gösterme fonksiyonu
    function showImportResults(data) {
        const importResults = document.getElementById('importResults');
        const importSummary = document.getElementById('importSummary');
        
        if (importResults) importResults.classList.remove('d-none');
        
        if (importSummary && data.summary) {
            importSummary.innerHTML = `
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-success">${data.summary.success || 0}</h4>
                            <small>Başarılı</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-warning">${data.summary.skipped || 0}</h4>
                            <small>Atlandı</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-danger">${data.summary.errors || 0}</h4>
                            <small>Hata</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-info">${data.summary.categories_created || 0}</h4>
                            <small>Yeni Kategori</small>
                        </div>
                    </div>
                </div>
                ${Array.isArray(data.skipped_rows) && data.skipped_rows.length > 0 ? `
                    <div class="mt-3">
                        <h6><i class=\"fas fa-forward text-warning me-1\"></i>Atlananlar (sebebiyle):</h6>
                        <ul class="list-unstyled mb-0">
                            ${data.skipped_rows.map(msg => `<li class=\"text-warning\">• ${msg}</li>`).join('')}
                        </ul>
                    </div>
                ` : ''}
                ${data.errors && data.errors.length > 0 ? `
                    <div class="mt-3">
                        <h6><i class=\"fas fa-times-circle text-danger me-1\"></i>Hatalar:</h6>
                        <ul class="list-unstyled mb-0">
                            ${data.errors.map(error => `<li class=\"text-danger\">• ${error}</li>`).join('')}
                        </ul>
                    </div>
                ` : ''}
            `;
        }
    }
});