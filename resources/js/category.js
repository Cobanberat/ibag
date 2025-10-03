// CSRF token alma fonksiyonu
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

// Kategori verilerini yükleme
function loadCategoryData(page = 1) {
    const searchValue = document.getElementById('categorySearch').value;

    fetch(`/admin/kategori/data?search=${encodeURIComponent(searchValue)}&page=${page}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderCategoryTable(data.data);
            if (data.pagination) {
                updatePagination(data.pagination);
            }
        } else {
            showToast('Kategori verileri yüklenirken hata oluştu', 'error');
        }
    })
    .catch(error => {
        console.error('Kategori verileri yükleme hatası:', error);
        showToast('Kategori verileri yüklenirken hata oluştu', 'error');
    });
}

// Kategori tablosunu render etme
function renderCategoryTable(categories) {
    const tbody = document.getElementById('categoryTableBody');
    
    if (categories.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4">
                    <i class="fas fa-folder-open fa-2x text-muted mb-2"></i>
                    <p class="text-muted">Kategori bulunamadı</p>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = categories.map(category => `
        <tr data-id="${category.id}" style="background-color: ${category.color || '#0d6efd'}20;">
            <td><input type="checkbox" class="category-checkbox" value="${category.id}"></td>
            <td>
                <b>${category.name}</b>
                ${category.icon ? `<i class="fas ${category.icon} ms-2"></i>` : ''}
            </td>
            <td>${category.description || '-'}</td>
            <td>${category.equipments_count || 0}</td>
            <td>${category.created_at ? new Date(category.created_at).toLocaleDateString('tr-TR') : '-'}</td>
            <td>
                <span style="background:${category.color || '#0d6efd'};width:18px;height:18px;display:inline-block;border-radius:4px;"></span>
            </td>
            <td class="category-actions">
                <button class="btn btn-outline-secondary btn-sm" style="padding:0.32em 0.7em;border-radius:1.2em;" onclick="editCategory(${category.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-outline-danger btn-sm" style="padding:0.32em 0.7em;border-radius:1.2em;" onclick="deleteCategory(${category.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

// Pagination güncelleme
function updatePagination(pagination) {
    const paginationContainer = document.getElementById('pagination');
    
    if (!paginationContainer) return;

    let paginationHTML = '';
    
    // Önceki sayfa
    if (pagination.current_page > 1) {
        paginationHTML += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="loadCategoryData(${pagination.current_page - 1}); return false;">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
        `;
    }

    // Sayfa numaraları
    const startPage = Math.max(1, pagination.current_page - 2);
    const endPage = Math.min(pagination.last_page, pagination.current_page + 2);

    for (let i = startPage; i <= endPage; i++) {
        const isActive = i === pagination.current_page;
        paginationHTML += `
            <li class="page-item ${isActive ? 'active' : ''}">
                <a class="page-link" href="#" onclick="loadCategoryData(${i}); return false;">
                    ${i}
                </a>
            </li>
        `;
    }

    // Sonraki sayfa
    if (pagination.current_page < pagination.last_page) {
        paginationHTML += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="loadCategoryData(${pagination.current_page + 1}); return false;">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        `;
    }

    paginationContainer.innerHTML = paginationHTML;
    
    // Sayfa bilgisi güncelleme
    const infoText = document.querySelector('.text-muted.small');
    if (infoText) {
        const startItem = (pagination.current_page - 1) * pagination.per_page + 1;
        const endItem = Math.min(pagination.current_page * pagination.per_page, pagination.total);
        infoText.textContent = `Toplam ${pagination.total} kategoriden ${startItem}-${endItem} arası gösteriliyor`;
    }
}

// Kategori ekleme
function addCategory() {
    const formData = new FormData();
    formData.append('name', document.getElementById('categoryName').value);
    formData.append('description', document.getElementById('categoryDesc').value);
    formData.append('color', document.getElementById('categoryColor').value);
    formData.append('icon', document.getElementById('categoryIcon').value);
    formData.append('_token', getCsrfToken());

    fetch('/admin/kategori', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Kategori başarıyla oluşturuldu', 'success');
            document.getElementById('addCategoryForm').reset();
            
            // Modal'ı kapat ve backdrop'ı temizle
            const modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
            if (modal) {
                modal.hide();
                // Modal kapandıktan sonra backdrop'ı temizle
                setTimeout(() => {
                    document.body.classList.remove('modal-open');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                }, 300);
            }
            
            loadCategoryData(1); // İlk sayfaya dön
        } else {
            showToast(data.message || 'Kategori oluşturulurken hata oluştu', 'error');
        }
    })
    .catch(error => {
        console.error('Kategori ekleme hatası:', error);
        showToast('Kategori oluşturulurken hata oluştu', 'error');
    });
}

// Kategori düzenleme
function editCategory(id) {
    fetch(`/admin/kategori/${id}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const category = data.data;
            document.getElementById('editCategoryId').value = category.id;
            document.getElementById('editCategoryName').value = category.name;
            document.getElementById('editCategoryDesc').value = category.description || '';
            document.getElementById('editCategoryColor').value = category.color || '#0d6efd';
            document.getElementById('editCategoryIcon').value = category.icon || '';
            
            const modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
            modal.show();
        } else {
            showToast('Kategori bilgileri alınırken hata oluştu', 'error');
        }
    })
    .catch(error => {
        console.error('Kategori bilgileri alma hatası:', error);
        showToast('Kategori bilgileri alınırken hata oluştu', 'error');
    });
}

// Kategori güncelleme
function updateCategory() {
    const id = document.getElementById('editCategoryId').value;
    const formData = new FormData();
    formData.append('name', document.getElementById('editCategoryName').value);
    formData.append('description', document.getElementById('editCategoryDesc').value);
    formData.append('color', document.getElementById('editCategoryColor').value);
    formData.append('icon', document.getElementById('editCategoryIcon').value);
    formData.append('_token', getCsrfToken());
    formData.append('_method', 'PUT');

    fetch(`/admin/kategori/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Kategori başarıyla güncellendi', 'success');
            
            // Modal'ı kapat ve backdrop'ı temizle
            const modal = bootstrap.Modal.getInstance(document.getElementById('editCategoryModal'));
            if (modal) {
                modal.hide();
                // Modal kapandıktan sonra backdrop'ı temizle
                setTimeout(() => {
                    document.body.classList.remove('modal-open');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                }, 300);
            }
            
            loadCategoryData(1); // İlk sayfaya dön
        } else {
            showToast(data.message || 'Kategori güncellenirken hata oluştu', 'error');
        }
    })
    .catch(error => {
        console.error('Kategori güncelleme hatası:', error);
        showToast('Kategori güncellenirken hata oluştu', 'error');
    });
}

// Kategori silme
function deleteCategory(id) {
    Swal.fire({
        title: 'Emin misiniz?',
        text: "Bu kategori kalıcı olarak silinecek!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Evet, sil!',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Siliniyor...',
                text: 'Lütfen bekleyin',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/admin/kategori/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Loading ekranını kapat
                    Swal.close();
                    
                    Swal.fire(
                        'Silindi!',
                        'Kategori başarıyla silindi.',
                        'success'
                    );
                    loadCategoryData(1); // İlk sayfaya dön
                } else {
                    // Loading ekranını kapat
                    Swal.close();
                    
                    Swal.fire(
                        'Hata!',
                        data.message || 'Kategori silinirken hata oluştu',
                        'error'
                    );
                }
            })
            .catch(error => {
                // Loading ekranını kapat
                Swal.close();
                
                console.error('Kategori silme hatası:', error);
                Swal.fire(
                    'Hata!',
                    'Kategori silinirken hata oluştu',
                    'error'
                );
            });
        }
    });
}

// Toplu kategori silme
function deleteSelectedCategories() {
    const selectedCheckboxes = document.querySelectorAll('.category-checkbox:checked');
    const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);

    if (selectedIds.length === 0) {
        showToast('Silinecek kategori seçilmedi', 'warning');
        return;
    }

    Swal.fire({
        title: 'Emin misiniz?',
        text: `${selectedIds.length} kategori kalıcı olarak silinecek!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Evet, sil!',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Siliniyor...',
                text: 'Lütfen bekleyin',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData();
            formData.append('ids', JSON.stringify(selectedIds));
            formData.append('_token', getCsrfToken());

            fetch('/admin/kategori/bulk-delete', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Loading ekranını kapat
                    Swal.close();
                    
                    Swal.fire(
                        'Silindi!',
                        data.message,
                        'success'
                    );
                    loadCategoryData(1); // İlk sayfaya dön
                } else {
                    // Loading ekranını kapat
                    Swal.close();
                    
                    Swal.fire(
                        'Hata!',
                        data.message,
                        'error'
                    );
                }
            })
            .catch(error => {
                // Loading ekranını kapat
                Swal.close();
                
                console.error('Toplu silme hatası:', error);
                Swal.fire(
                    'Hata!',
                    'Kategoriler silinirken hata oluştu',
                    'error'
                );
            });
        }
    });
}

// CSV export
function exportCategories() {
    window.location.href = '/admin/kategori/export/csv';
}

// Toast bildirimi gösterme fonksiyonu
function showToast(message, type = 'info') {
    // Toast container oluştur (eğer yoksa)
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        `;
        document.body.appendChild(toastContainer);
    }

    // Toast elementi oluştur
    const toast = document.createElement('div');
    const iconMap = {
        'success': '✓',
        'error': '✕',
        'warning': '⚠',
        'info': 'ℹ'
    };
    
    const colorMap = {
        'success': '#28a745',
        'error': '#dc3545',
        'warning': '#ffc107',
        'info': '#17a2b8'
    };

    toast.style.cssText = `
        background: white;
        border-left: 4px solid ${colorMap[type] || colorMap.info};
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-radius: 4px;
        padding: 12px 16px;
        min-width: 300px;
        max-width: 400px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-size: 14px;
        color: #333;
        animation: slideIn 0.3s ease forwards;
    `;

    toast.innerHTML = `
        <div style="
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: ${colorMap[type] || colorMap.info};
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        ">${iconMap[type] || iconMap.info}</div>
        <div style="flex: 1;">${message}</div>
        <button onclick="this.parentElement.remove()" style="
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            font-size: 18px;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        ">&times;</button>
    `;

    toastContainer.appendChild(toast);

    // 3 saniye sonra otomatik kaldır
    setTimeout(() => {
        if (toast.parentElement) {
            toast.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 300);
        }
    }, 3000);
}

// Global fonksiyonları window objesine ekle
window.editCategory = editCategory;
window.deleteCategory = deleteCategory;
window.showToast = showToast;

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Filtreleme ve arama
    const searchInput = document.getElementById('categorySearch');
    const sortSelect = document.getElementById('sortSelect');
    const filterBtn = document.getElementById('filterBtn');

    if (searchInput) {
        searchInput.addEventListener('input', loadCategoryData);
    }

    // Clear filters butonu
    const clearFiltersBtn = document.getElementById('clearFilters');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            searchInput.value = '';
            loadCategoryData(1);
        });
    }

    // Kategori ekleme
    const saveCategoryBtn = document.getElementById('saveCategoryBtn');
    if (saveCategoryBtn) {
        saveCategoryBtn.addEventListener('click', addCategory);
    }

    // Kategori güncelleme
    const updateCategoryBtn = document.getElementById('updateCategoryBtn');
    if (updateCategoryBtn) {
        updateCategoryBtn.addEventListener('click', updateCategory);
    }

    // Modal event listener'ları - backdrop temizliği için
    const addCategoryModal = document.getElementById('addCategoryModal');
    const editCategoryModal = document.getElementById('editCategoryModal');
    
    if (addCategoryModal) {
        addCategoryModal.addEventListener('hidden.bs.modal', function() {
            // Modal kapandığında backdrop'ı temizle
            setTimeout(() => {
                document.body.classList.remove('modal-open');
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }, 100);
        });
    }
    
    if (editCategoryModal) {
        editCategoryModal.addEventListener('hidden.bs.modal', function() {
            // Modal kapandığında backdrop'ı temizle
            setTimeout(() => {
                document.body.classList.remove('modal-open');
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }, 100);
        });
    }

    // Toplu silme
    const deleteSelectedBtn = document.getElementById('deleteSelected');
    if (deleteSelectedBtn) {
        deleteSelectedBtn.addEventListener('click', deleteSelectedCategories);
    }

    // Tümünü seç
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.category-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Excel export
    const excelBtn = document.querySelector('button[onclick*="Excel"]');
    if (excelBtn) {
        excelBtn.addEventListener('click', exportCategories);
    }

    // PDF export (şimdilik CSV olarak)
    const pdfBtn = document.querySelector('button[onclick*="PDF"]');
    if (pdfBtn) {
        pdfBtn.addEventListener('click', exportCategories);
    }
});