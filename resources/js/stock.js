// CSRF token alma fonksiyonu
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

// Stok verilerini yükleme
function loadStockData(page = 1) {
    const searchValue = document.getElementById('filterSearch').value;
    const categoryValue = document.getElementById('filterCategory').value;
    const statusValue = document.getElementById('filterStatus').value;

    fetch(`/admin/stock/data?search=${encodeURIComponent(searchValue)}&category=${encodeURIComponent(categoryValue)}&status=${encodeURIComponent(statusValue)}&page=${page}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderStockTable(data.data);
            if (data.pagination) {
                updatePagination(data.pagination);
            }
        } else {
            showToast('Stok verileri yüklenirken hata oluştu', 'error');
        }
    })
    .catch(error => {
        console.error('Stok verileri yükleme hatası:', error);
        showToast('Stok verileri yüklenirken hata oluştu', 'error');
    });
}

// Stok girişi modalını açma
function stockIn(stockId) {
    // Modal açma işlemi
    const modal = new bootstrap.Modal(document.getElementById('stockOperationModal'));
    document.getElementById('operationType').value = 'in';
    document.getElementById('stockId').value = stockId;
    document.getElementById('operationTitle').textContent = 'Stok Girişi';
    document.getElementById('operationAmount').value = '';
    document.getElementById('operationNote').value = '';
    modal.show();
}

// Stok çıkışı modalını açma
function stockOut(stockId) {
    // Mevcut stok miktarını al
    const row = document.querySelector(`tr[data-id="${stockId}"]`);
    const currentQuantity = parseInt(row.querySelector('td:nth-child(4)').textContent);
    
    // Modal açma işlemi
    const modal = new bootstrap.Modal(document.getElementById('stockOperationModal'));
    document.getElementById('operationType').value = 'out';
    document.getElementById('stockId').value = stockId;
    document.getElementById('operationTitle').textContent = 'Stok Çıkışı';
    document.getElementById('operationAmount').value = '';
    document.getElementById('operationAmount').max = currentQuantity;
    document.getElementById('operationAmount').placeholder = `Maksimum: ${currentQuantity}`;
    document.getElementById('operationNote').value = '';
    modal.show();
}

// Stok işlemi gönderme
function submitStockOperation() {
    const stockId = document.getElementById('stockId').value;
    const type = document.getElementById('operationType').value;
    const amount = document.getElementById('operationAmount').value;
    const note = document.getElementById('operationNote').value;

    if (!amount || amount <= 0) {
        showToast('Lütfen geçerli bir miktar girin', 'error');
        return;
    }

    // Stok çıkışında maksimum miktar kontrolü
    if (type === 'out') {
        const maxAmount = parseInt(document.getElementById('operationAmount').max);
        if (parseInt(amount) > maxAmount) {
            showToast(`Maksimum çıkarılabilecek miktar: ${maxAmount}`, 'error');
            return;
        }
    }

    console.log('Stok işlemi gönderiliyor:', { stockId, type, amount, note });

    fetch(`/admin/stock/${stockId}/operation`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            type: type,
            amount: parseInt(amount),
            note: note
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showToast(data.message, 'success');
            // Modal'ı kapat
            const modal = bootstrap.Modal.getInstance(document.getElementById('stockOperationModal'));
            modal.hide();
            // Tabloyu yenile
            loadStockData();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Bir hata oluştu: ' + error.message, 'error');
    });
}

// Modal içindeki Enter tuşu olayını yakala
document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('stockOperationModal');
    if (modalElement) {
        modalElement.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                e.stopPropagation();
                submitStockOperation();
                return false;
            }
        });
    }

    // Düzenleme modalı için Enter tuşu desteği
    const editModalElement = document.getElementById('editStockModal');
    if (editModalElement) {
        editModalElement.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                e.stopPropagation();
                submitEditStock();
                return false;
            }
        });
    }
});

// Stok düzenleme
function editStock(stockId) {
    // Mevcut verileri al
    const row = document.querySelector(`tr[data-id="${stockId}"]`);
    const name = row.querySelector('td:nth-child(2) .fw-bold').textContent;
    const code = row.querySelector('td:nth-child(2) small').textContent;
    const category = row.querySelector('td:nth-child(3)').textContent;
    const criticalLevel = row.querySelector('td:nth-child(5)').textContent;
    
    // Modal açma işlemi
    const modal = new bootstrap.Modal(document.getElementById('editStockModal'));
    document.getElementById('editStockId').value = stockId;
    document.getElementById('editStockName').value = name;
    document.getElementById('editStockCode').value = code;
    document.getElementById('editStockCriticalLevel').value = criticalLevel;
    document.getElementById('editStockNote').value = '';
    modal.show();
}

// Stok düzenleme gönderme
function submitEditStock() {
    const stockId = document.getElementById('editStockId').value;
    const name = document.getElementById('editStockName').value;
    const code = document.getElementById('editStockCode').value;
    const criticalLevel = document.getElementById('editStockCriticalLevel').value;
    const note = document.getElementById('editStockNote').value;

    if (!name || !code || !criticalLevel) {
        showToast('Lütfen tüm zorunlu alanları doldurun', 'error');
        return;
    }

    if (criticalLevel <= 0) {
        showToast('Kritik seviye 0\'dan büyük olmalıdır', 'error');
        return;
    }

    console.log('Stok düzenleme gönderiliyor:', { stockId, name, code, criticalLevel, note });

    fetch(`/admin/stock/${stockId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            name: name,
            code: code,
            critical_level: parseInt(criticalLevel),
            note: note
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showToast(data.message, 'success');
            // Modal'ı kapat
            const modal = bootstrap.Modal.getInstance(document.getElementById('editStockModal'));
            modal.hide();
            // Tabloyu yenile
            loadStockData();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Bir hata oluştu: ' + error.message, 'error');
    });
}

// Stok silme
function deleteStock(stockId) {
    Swal.fire({
        title: 'Emin misiniz?',
        text: "Bu stok kaydı kalıcı olarak silinecek!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Evet, sil!',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/stock/${stockId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    loadStockData();
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Bir hata oluştu', 'error');
            });
        }
    });
}

// Stok tablosunu render etme
function renderStockTable(stocks) {
    const tbody = document.getElementById('stockTableBody');
    
    if (stocks.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4">
                    <i class="fas fa-boxes fa-2x text-muted mb-2"></i>
                    <p class="text-muted">Stok bulunamadı</p>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = stocks.map(stock => {
        const totalQuantity = stock.total_quantity || 0;
        const criticalLevel = stock.critical_level || 3;
        const isLowStock = totalQuantity <= criticalLevel && totalQuantity > 0;
        const isEmpty = totalQuantity == 0;
        const isSufficient = totalQuantity > criticalLevel;
        
        const rowClass = isEmpty ? 'table-danger' : (isLowStock ? 'table-warning' : 'table-success');
        const percentage = totalQuantity > 0 ? Math.min(100, (totalQuantity / Math.max(1, criticalLevel)) * 100) : 0;
        const barClass = isEmpty ? 'bg-danger' : (isLowStock ? 'bg-warning' : 'bg-success');
        
        let statusBadge = '';
        if (isEmpty) {
            statusBadge = '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Tükendi</span>';
        } else if (isLowStock) {
            statusBadge = '<span class="badge bg-warning"><i class="fas fa-exclamation-triangle"></i> Az Stok</span>';
        } else {
            statusBadge = '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Yeterli</span>';
        }

        return `
            <tr class="${rowClass}" data-id="${stock.id}">
                <td><input type="checkbox" class="stock-checkbox" value="${stock.id}"></td>
                <td>
                    <span class="fw-bold">${stock.name || '-'}</span>
                    <br><small class="text-muted">${stock.code || '-'}</small>
                </td>
                <td>${stock.category?.name || '-'}</td>
                <td>${totalQuantity}</td>
                <td>${criticalLevel}</td>
                <td>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar ${barClass}" style="width: ${percentage}%"></div>
                    </div>
                </td>
                <td>${statusBadge}</td>
                <td class="category-actions">
                    <button class="btn btn-outline-secondary btn-sm" style="padding:0.45em 1em;border-radius:1.2em;" onclick="editStock(${stock.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-success btn-sm" style="padding:0.45em 1em;border-radius:1.2em;" onclick="stockIn(${stock.id})">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button class="btn btn-outline-warning btn-sm" style="padding:0.45em 1em;border-radius:1.2em;" onclick="stockOut(${stock.id})">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm" style="padding:0.45em 1em;border-radius:1.2em;" onclick="deleteStock(${stock.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    }).join('');
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
                <a class="page-link" href="#" onclick="loadStockData(${pagination.current_page - 1}); return false;">
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
                <a class="page-link" href="#" onclick="loadStockData(${i}); return false;">
                    ${i}
                </a>
            </li>
        `;
    }

    // Sonraki sayfa
    if (pagination.current_page < pagination.last_page) {
        paginationHTML += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="loadStockData(${pagination.current_page + 1}); return false;">
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
        infoText.textContent = `Toplam ${pagination.total} stoktan ${startItem}-${endItem} arası gösteriliyor`;
    }
}

// Stok ekleme
function addStock() {
    const formData = new FormData(document.getElementById('addProductForm'));
    formData.append('_token', getCsrfToken());

    fetch('/admin/stock', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Stok başarıyla oluşturuldu', 'success');
            document.getElementById('addProductForm').reset();
            bootstrap.Modal.getInstance(document.getElementById('addProductModal')).hide();
            loadStockData(1);
        } else {
            showToast(data.message || 'Stok oluşturulurken hata oluştu', 'error');
        }
    })
    .catch(error => {
        console.error('Stok ekleme hatası:', error);
        showToast('Stok oluşturulurken hata oluştu', 'error');
    });
}







// Toplu stok silme
function deleteSelectedStocks() {
    const selectedCheckboxes = document.querySelectorAll('.stock-checkbox:checked');
    const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);

    if (selectedIds.length === 0) {
        showToast('Silinecek stok seçilmedi', 'warning');
        return;
    }

    Swal.fire({
        title: 'Emin misiniz?',
        text: `${selectedIds.length} stok kalıcı olarak silinecek!`,
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

            fetch('/admin/stock/bulk-delete', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Silindi!',
                        data.message,
                        'success'
                    );
                    loadStockData(1);
                } else {
                    Swal.fire(
                        'Hata!',
                        data.message,
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Toplu silme hatası:', error);
                Swal.fire(
                    'Hata!',
                    'Stoklar silinirken hata oluştu',
                    'error'
                );
            });
        }
    });
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
window.editStock = editStock;
window.deleteStock = deleteStock;
window.stockIn = stockIn;
window.stockOut = stockOut;
window.submitStockOperation = submitStockOperation;
window.showToast = showToast;

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Filtreleme ve arama
    const searchInput = document.getElementById('filterSearch');
    const categorySelect = document.getElementById('filterCategory');
    const statusSelect = document.getElementById('filterStatus');
    const filterBtn = document.getElementById('filterBtn');

    if (searchInput) {
        searchInput.addEventListener('input', () => loadStockData(1));
    }

    if (categorySelect) {
        categorySelect.addEventListener('change', () => loadStockData(1));
    }

    if (statusSelect) {
        statusSelect.addEventListener('change', () => loadStockData(1));
    }

    if (filterBtn) {
        filterBtn.addEventListener('click', () => loadStockData(1));
    }

    // Stok ekleme
    const addProductForm = document.getElementById('addProductForm');
    if (addProductForm) {
        addProductForm.addEventListener('submit', function(e) {
                e.preventDefault();
            addStock();
        });
    }

    // Toplu silme
    const deleteSelectedBtn = document.getElementById('deleteSelected');
    if (deleteSelectedBtn) {
        deleteSelectedBtn.addEventListener('click', deleteSelectedStocks);
    }

    // Tümünü seç
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.stock-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
});