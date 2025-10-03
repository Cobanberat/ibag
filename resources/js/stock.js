// Global variables
let currentPage = 1;
let currentFilters = {};

// Make functions globally available
window.loadStockData = loadStockData;
window.showStockOperation = showStockOperation;
window.viewStockDetails = viewStockDetails;
window.deleteStock = deleteStock;
window.submitStockOperation = submitStockOperation;
window.submitEditStock = submitEditStock;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadStockData();
    setupEventListeners();
    setupAddEquipmentForm();
    setupEquipmentSelectionHandler();
    
    // Initialize form switch
    setTimeout(() => {
        toggleQuantityMode();
    }, 100);
});

// Setup event listeners
function setupEventListeners() {
    // Filter events
    document.getElementById('filterSearch').addEventListener('input', debounce(applyFilters, 300));
    document.getElementById('filterCategory').addEventListener('change', applyFilters);
    document.getElementById('filterTracking').addEventListener('change', applyFilters);
    document.getElementById('perPageSelect').addEventListener('change', handlePerPageChange);
    document.getElementById('clearFiltersBtn').addEventListener('click', clearFilters);
    
    // Action events
    document.getElementById('selectAll').addEventListener('change', toggleSelectAll);
    document.getElementById('deleteSelected').addEventListener('click', deleteSelected);
    
    // Form switch events
    const quantityOnlyMode = document.getElementById('quantityOnlyMode');
    if (quantityOnlyMode) {
        quantityOnlyMode.addEventListener('change', toggleQuantityMode);
    }
}

// Toggle quantity mode
function toggleQuantityMode() {
    const quantityOnlyMode = document.getElementById('quantityOnlyMode');
    const quantityOnlySection = document.getElementById('quantityOnlySection');
    const manualEquipmentSection = document.getElementById('manualEquipmentSection');
    
    if (quantityOnlyMode.checked) {
        quantityOnlySection.style.display = 'block';
        manualEquipmentSection.style.display = 'none';
        
        // Disable required fields in manual section
        const manualInputs = manualEquipmentSection.querySelectorAll('[required]');
        manualInputs.forEach(input => {
            input.removeAttribute('required');
            input.disabled = true;
        });
        
        // Enable required fields in quantity section
        const quantityInputs = quantityOnlySection.querySelectorAll('[required]');
        quantityInputs.forEach(input => {
            input.setAttribute('required', 'required');
            input.disabled = false;
        });
        
        // Ensure equipment select is enabled in quantity section
        const equipmentSelect = quantityOnlySection.querySelector('select[name="equipment_id"]');
        if (equipmentSelect) {
            equipmentSelect.disabled = false;
            equipmentSelect.setAttribute('required', 'required');
        }
        
    } else {
        quantityOnlySection.style.display = 'none';
        manualEquipmentSection.style.display = 'block';
        
        // Disable required fields in quantity section
        const quantityInputs = quantityOnlySection.querySelectorAll('[required]');
        quantityInputs.forEach(input => {
            input.removeAttribute('required');
            input.disabled = true;
        });
        
        // Enable required fields in manual section
        const manualInputs = manualEquipmentSection.querySelectorAll('[required]');
        manualInputs.forEach(input => {
            input.setAttribute('required', 'required');
            input.disabled = false;
        });
        
        // Ensure name field is enabled and required
        const nameInput = document.querySelector('input[name="name"]');
        if (nameInput) {
            nameInput.disabled = false;
            nameInput.setAttribute('required', 'required');
        }
        
        // Ensure category and unit type selects are enabled and required
        const categorySelect = document.querySelector('select[name="category_id"]');
        if (categorySelect) {
            categorySelect.disabled = false;
            categorySelect.setAttribute('required', 'required');
        }
        
        const unitTypeSelect = document.querySelector('select[name="unit_type"]');
        if (unitTypeSelect) {
            unitTypeSelect.disabled = false;
            unitTypeSelect.setAttribute('required', 'required');
        }
        
        // Ensure manual quantity input is enabled and required
        const manualQuantityInput = document.querySelector('input[name="manual_quantity"]');
        if (manualQuantityInput) {
            manualQuantityInput.disabled = false;
            manualQuantityInput.style.display = 'block';
            manualQuantityInput.setAttribute('required', 'required');
        }
        
        // Re-setup equipment selection handlers for manual section
        setupEquipmentSelectionHandler();
    }
}

// Load stock data
function loadStockData(page = 1) {
    currentPage = page;
    
    const params = new URLSearchParams({
        page: page,
        ...currentFilters
    });
    
    fetch(`/admin/stock/data?${params}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderStockTable(data.data);
                renderPagination(data.pagination);
                updateCriticalStockAlert(data.data);
            } else {
                showToast('error', 'Veri yüklenirken hata oluştu');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Bağlantı hatası');
        });
}

// Render stock table
function renderStockTable(stocks) {
    const tbody = document.getElementById('stockTableBody');
    
    if (stocks.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="11" class="text-center py-4">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted small">Stok bulunamadı</p>
                </td>
            </tr>
        `;
        return;
    }
    
    let html = '';
    stocks.forEach(stock => {
        // Güvenli değer kontrolü
        const statusClass = getStatusClass(stock.stock_status || 'Bilinmiyor');
        const trackingType = stock.individual_tracking ? 'Ayrı Takip' : 'Toplu Takip';
        const trackingBadge = stock.individual_tracking ? 'bg-info' : 'bg-warning';
        
        // Progress bar for stock level
        const percentage = stock.percentage || 0;
        const barClass = stock.bar_class || 'bg-secondary';
        const progressBar = `
            <div class="progress" style="height: 6px; width: 50px;">
                <div class="progress-bar ${barClass}" role="progressbar" style="width: ${percentage}%" aria-valuenow="${percentage}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        `;
        
        // Row class güvenli kontrolü
        const rowClass = stock.row_class || '';
        
        html += `
            <tr class="${rowClass}">
                <td class="d-none d-md-table-cell">
                    <input type="checkbox" class="stock-checkbox form-check-input" value="${stock.id}">
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="me-2">
                            <i class="fas fa-cube text-primary"></i>
                        </div>
                        <div>
                            <strong style="font-size: 0.8em;">${stock.name || 'İsimsiz'}</strong>
                            <br><small class="text-muted" style="font-size: 0.65em;">${stock.unit_type_label || 'Adet'}</small>
                        </div>
                    </div>
                </td>
                <td class="d-none d-sm-table-cell">
                    <span class="badge bg-secondary" style="font-size: 0.65em;">${stock.category?.name || 'Kategori yok'}</span>
                </td>
                <td class="d-none d-md-table-cell">
                    <span class="badge bg-light text-dark" style="font-size: 0.65em;">${stock.unit_type_label || 'Adet'}</span>
                </td>
                <td>
                    <strong style="font-size: 0.8em;">${stock.total_quantity || 0}</strong>
                </td>
                <td class="d-none d-sm-table-cell">
                    <span class="badge bg-info" style="font-size: 0.65em;">${stock.critical_level || 0}</span>
                </td>
                <td class="d-none d-lg-table-cell">
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <span class="badge ${statusClass}" style="font-size: 0.75em; min-width: 70px; text-align: center;">${stock.stock_status || 'Bilinmiyor'}</span>
                        ${progressBar}
                    </div>
                </td>
                <td class="d-none d-md-table-cell">
                    <div class="d-flex align-items-center justify-content-center">
                        <span class="badge ${trackingBadge} px-2 py-1" style="font-size: 0.75em; min-width: 80px; text-align: center;">${trackingType}</span>
                    </div>
                </td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-outline-primary btn-sm" onclick="showStockOperation(${stock.id}, 'add')" title="Stok Ekle" style="font-size: 0.65em; padding: 0.2rem 0.4rem;">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button class="btn btn-outline-warning btn-sm" onclick="showStockOperation(${stock.id}, 'remove')" title="Stok Çıkar" style="font-size: 0.65em; padding: 0.2rem 0.4rem;">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="viewStockDetails(${stock.id})" title="Detaylar" style="font-size: 0.65em; padding: 0.2rem 0.4rem;">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="deleteStock(${stock.id})" title="Sil" style="font-size: 0.65em; padding: 0.2rem 0.4rem;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

// Get status class
function getStatusClass(status) {
    switch(status) {
        case 'Yeterli': return 'bg-success';
        case 'Az': return 'bg-warning';
        case 'Tükendi': return 'bg-danger';
        default: return 'bg-secondary';
    }
}

// Render pagination
function renderPagination(pagination) {
    const paginationEl = document.getElementById('pagination');
    const paginationText = document.getElementById('paginationText');
    
    // Update pagination info
    const start = (pagination.current_page - 1) * pagination.per_page + 1;
    const end = Math.min(pagination.current_page * pagination.per_page, pagination.total);
    paginationText.textContent = `${start}-${end} / ${pagination.total} kayıt`;
    
    if (pagination.last_page <= 1) {
        paginationEl.innerHTML = '';
        return;
    }
    
    let html = '';
    
    // Previous button
    html += `
        <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="loadStockData(${pagination.current_page - 1})">
                <i class="fas fa-chevron-left"></i>
            </a>
        </li>
    `;
    
    // Page numbers
    const startPage = Math.max(1, pagination.current_page - 2);
    const endPage = Math.min(pagination.last_page, pagination.current_page + 2);
    
    if (startPage > 1) {
        html += `<li class="page-item"><a class="page-link" href="#" onclick="loadStockData(1)">1</a></li>`;
        if (startPage > 2) {
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
    }
    
    for (let i = startPage; i <= endPage; i++) {
        if (i === pagination.current_page) {
            html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
        } else {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="loadStockData(${i})">${i}</a></li>`;
        }
    }
    
    if (endPage < pagination.last_page) {
        if (endPage < pagination.last_page - 1) {
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
        html += `<li class="page-item"><a class="page-link" href="#" onclick="loadStockData(${pagination.last_page})">${pagination.last_page}</a></li>`;
    }
    
    // Next button
    html += `
        <li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="loadStockData(${pagination.current_page + 1})">
                <i class="fas fa-chevron-right"></i>
            </a>
        </li>
    `;
    
    paginationEl.innerHTML = html;
}

// Apply filters
function applyFilters() {
    currentFilters = {
        search: document.getElementById('filterSearch').value,
        category: document.getElementById('filterCategory').value,
        tracking: document.getElementById('filterTracking').value
    };
    
    // Remove empty filters
    Object.keys(currentFilters).forEach(key => {
        if (!currentFilters[key]) {
            delete currentFilters[key];
        }
    });
    
    loadStockData(1);
}

// Clear filters
function clearFilters() {
    document.getElementById('filterSearch').value = '';
    document.getElementById('filterCategory').value = '';
    document.getElementById('filterTracking').value = '';
    document.getElementById('perPageSelect').value = '15';
    
    currentFilters = {};
    loadStockData(1);
}

// Toggle select all
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.stock-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateDeleteButton();
}

// Update delete button
function updateDeleteButton() {
    const checkboxes = document.querySelectorAll('.stock-checkbox:checked');
    const deleteBtn = document.getElementById('deleteSelected');
    
    deleteBtn.disabled = checkboxes.length === 0;
    deleteBtn.textContent = `Seçili Sil (${checkboxes.length})`;
}

// Update critical stock alert
function updateCriticalStockAlert(stocks) {
    const criticalStocks = stocks.filter(stock => stock.stock_status === 'Az' || stock.stock_status === 'Tükendi');
    const alertEl = document.getElementById('criticalStockAlert');
    
    if (criticalStocks.length > 0) {
        alertEl.classList.remove('d-none');
        document.getElementById('criticalStockMessage').textContent = 
            `${criticalStocks.length} ekipmanın stok seviyesi kritik! Lütfen stokları kontrol edin.`;
    } else {
        alertEl.classList.add('d-none');
    }
}

// Toast notification
function showToast(type, message) {
    const toastContainer = getOrCreateToastContainer();
    const toastId = 'toast-' + Date.now();
    
    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', function() {
        toastElement.remove();
    });
}

// Get or create toast container
function getOrCreateToastContainer() {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
    }
    return container;
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Add event listener for checkboxes
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('stock-checkbox')) {
        updateDeleteButton();
    }
});

// Show stock operation modal
function showStockOperation(stockId, operationType) {
    // Set modal data
    document.getElementById('stockId').value = stockId;
    document.getElementById('operationType').value = operationType;
    
    // Show operation section, hide edit section
    document.getElementById('operationSection').style.display = 'block';
    document.getElementById('editSection').style.display = 'none';
    
    // Update modal title and labels
    const modalTitle = document.getElementById('stockOperationModalLabel');
    const operationTitle = document.getElementById('operationTitle');
    const operationAmountLabel = document.getElementById('operationAmountLabel');
    const btnSubmit = document.getElementById('btnSubmitOperation');
    const btnEdit = document.getElementById('btnSubmitEdit');
    
    if (operationType === 'add') {
        modalTitle.innerHTML = '<i class="fas fa-plus me-2"></i>Stok Ekle';
        operationTitle.textContent = 'Stok Girişi';
        operationAmountLabel.textContent = 'Eklenecek Miktar';
        btnSubmit.style.display = 'inline-block';
        btnEdit.style.display = 'none';
        
        // Load existing codes for this equipment
        loadExistingCodesForEquipment(stockId);
    } else if (operationType === 'remove') {
        modalTitle.innerHTML = '<i class="fas fa-minus me-2"></i>Stok Çıkar';
        operationTitle.textContent = 'Stok Çıkışı';
        operationAmountLabel.textContent = 'Çıkarılacak Miktar';
        btnSubmit.style.display = 'inline-block';
        btnEdit.style.display = 'none';
        
        // Load existing codes for this equipment
        loadExistingCodesForEquipment(stockId);
    } else if (operationType === 'edit') {
        modalTitle.innerHTML = '<i class="fas fa-edit me-2"></i>Ekipman Düzenle';
        operationTitle.textContent = 'Ekipman Düzenleme';
        operationAmountLabel.textContent = 'Miktar';
        btnSubmit.style.display = 'none';
        btnEdit.style.display = 'inline-block';
        
        // Load equipment data for editing
        loadEquipmentForEdit(stockId);
    }
    
    // Clear form
    clearStockOperationForm();
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('stockOperationModal'));
    modal.show();
}

// Load existing codes for equipment
function loadExistingCodesForEquipment(equipmentId) {
    fetch(`/admin/stock/${equipmentId}/detailed-codes`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.codes) {
                populateExistingCodesSelect(data.codes);
            }
        })
        .catch(error => {
            console.error('Error loading existing codes:', error);
        });
}

// Populate existing codes select
function populateExistingCodesSelect(codes) {
    const existingCodesSelect = document.getElementById('existingCodesSelect');
    const existingCodesSelectWrapper = document.getElementById('existingCodesSelectWrapper');
    
    if (!existingCodesSelect || !existingCodesSelectWrapper) return;
    
    // Clear existing options
    existingCodesSelect.innerHTML = '<option value="">Stok kodu seçiniz</option>';
    
    if (codes && codes.length > 0) {
        codes.forEach(code => {
            const option = document.createElement('option');
            option.value = code.code;
            option.textContent = `${code.code} - ${code.brand || 'Marka yok'} ${code.model || 'Model yok'}`;
            option.dataset.brand = code.brand || '';
            option.dataset.model = code.model || '';
            option.dataset.size = code.size || '';
            option.dataset.feature = code.feature || '';
            existingCodesSelect.appendChild(option);
        });
        
        // Show the select wrapper
        existingCodesSelectWrapper.style.display = 'block';
        
        // If only one code exists, auto-select it
        if (codes.length === 1) {
            existingCodesSelect.value = codes[0].code;
            // Trigger the change event to auto-fill fields
            existingCodesSelect.dispatchEvent(new Event('change'));
        }
        
        // Add change event listener
        existingCodesSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                // Auto-fill the code field
                document.getElementById('operationCode').value = selectedOption.value;
                
                // Auto-fill other fields if they exist
                if (selectedOption.dataset.brand) {
                    document.getElementById('operationBrand').value = selectedOption.dataset.brand;
                }
                if (selectedOption.dataset.model) {
                    document.getElementById('operationModel').value = selectedOption.dataset.model;
                }
                if (selectedOption.dataset.size) {
                    document.getElementById('operationSize').value = selectedOption.dataset.size;
                }
                if (selectedOption.dataset.feature) {
                    document.getElementById('operationFeature').value = selectedOption.dataset.feature;
                }
            }
        });
    } else {
        // Hide the select wrapper if no codes
        existingCodesSelectWrapper.style.display = 'none';
    }
}

// View stock details
function viewStockDetails(stockId) {
    // Load stock details and show in modal instead of redirecting
    fetch(`/admin/equipment/${stockId}/details`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showStockDetailsModal(data.data);
            } else {
                showToast('error', data.message || 'Stok detayları yüklenemedi');
            }
        })
        .catch(error => {
            console.error('Error loading stock details:', error);
            showToast('error', 'Stok detayları yüklenirken hata oluştu: ' + error.message);
        });
}

// Show stock details modal
function showStockDetailsModal(equipment) {
    // Create stocks table if there are multiple stocks
    let stocksTable = '';
    if (equipment.stocks && equipment.stocks.length > 0) {
        stocksTable = `
            <div class="mt-3">
                <h6 class="fw-bold">Stok Kayıtları</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Kod</th>
                                <th>Marka</th>
                                <th>Model</th>
                                <th>Miktar</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${equipment.stocks.map(stock => `
                                <tr>
                                    <td>${stock.code || 'Kod yok'}</td>
                                    <td>${stock.brand || 'Marka yok'}</td>
                                    <td>${stock.model || 'Model yok'}</td>
                                    <td>${stock.quantity || 0}</td>
                                    <td><span class="badge bg-success">${stock.status || 'Aktif'}</span></td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        `;
    }
    
    // Create modal content
    const modalContent = `
        <div class="modal fade" id="stockDetailsModal" tabindex="-1" aria-labelledby="stockDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="stockDetailsModalLabel">
                            <i class="fas fa-info-circle me-2"></i>Ekipman Detayları
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold">Ekipman Bilgileri</h6>
                                <p><strong>Adı:</strong> ${equipment.name || 'Bilinmiyor'}</p>
                                <p><strong>Kategori:</strong> ${equipment.category?.name || 'Kategori yok'}</p>
                                <p><strong>Birim Türü:</strong> ${equipment.unit_type_label || 'Adet'}</p>
                                <p><strong>Kritik Seviye:</strong> ${equipment.critical_level || 0}</p>
                                <p><strong>Takip Türü:</strong> ${equipment.individual_tracking ? 'Ayrı Takip' : 'Toplu Takip'}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">Stok Bilgileri</h6>
                                <p><strong>Toplam Miktar:</strong> <span class="badge bg-primary">${equipment.total_quantity || 0}</span></p>
                                <p><strong>Durum:</strong> <span class="badge bg-success">${equipment.status || 'Aktif'}</span></p>
                                <p><strong>Stok Kayıt Sayısı:</strong> ${equipment.stocks ? equipment.stocks.length : 0}</p>
                            </div>
                        </div>
                        ${equipment.note ? `<div class="mt-3"><h6 class="fw-bold">Not:</h6><p>${equipment.note}</p></div>` : ''}
                        ${equipment.feature ? `<div class="mt-3"><h6 class="fw-bold">Özellik:</h6><p>${equipment.feature}</p></div>` : ''}
                        ${stocksTable}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('stockDetailsModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalContent);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('stockDetailsModal'));
    modal.show();
    
    // Clean up modal when hidden
    document.getElementById('stockDetailsModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// Delete stock
function deleteStock(stockId) {
    Swal.fire({
        title: 'Emin misiniz?',
        text: "Bu ekipmanı ve tüm stok kayıtlarını silmek istediğinizden emin misiniz?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Evet, Sil!',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
    
    fetch(`/admin/equipment/${stockId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('success', 'Ekipman ve tüm stok kayıtları başarıyla silindi');
            loadStockData(currentPage);
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Ekipman silinirken hata oluştu: ' + error.message);
    });
        }
    });
}

// Delete selected
function deleteSelected() {
    const checkboxes = document.querySelectorAll('.stock-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        showToast('error', 'Silinecek ekipman seçin');
        return;
    }
    
    Swal.fire({
        title: 'Emin misiniz?',
        text: `${ids.length} ekipmanı silmek istediğinizden emin misiniz?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Evet, Sil!',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Delete işlemi
    fetch('/admin/stock/bulk-delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ ids })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', `${ids.length} ekipman başarıyla silindi`);
            loadStockData(currentPage);
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Ekipmanlar silinirken hata oluştu');
            });
        }
    });
}

// Submit stock operation
function submitStockOperation() {
    const stockId = document.getElementById('stockId').value;
    const operationType = document.getElementById('operationType').value;
    const amount = document.getElementById('operationAmount').value;
    const code = document.getElementById('operationCode').value;
    const note = document.getElementById('operationNote').value;
    const brand = document.getElementById('operationBrand').value;
    const model = document.getElementById('operationModel').value;
    const size = document.getElementById('operationSize').value;
    const feature = document.getElementById('operationFeature').value;
    const unitType = document.getElementById('operationUnitType').value;
    
    if (!amount) {
        showToast('error', 'Miktar gereklidir');
        return;
    }
    
    // Convert operation type to controller expected values
    const operationTypeMap = {
        'add': 'in',
        'remove': 'out'
    };
    
    const data = {
        operation_type: operationTypeMap[operationType] || operationType,
        amount: amount,
        code: code,
        note: note,
        brand: brand,
        model: model,
        size: size,
        feature: feature,
        unit_type: unitType
    };
    
    console.log('Sending stock operation request:', {
        url: `/admin/stock/${stockId}/operation`,
        data: data
    });
    
    fetch(`/admin/stock/${stockId}/operation`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error(`Expected JSON response, got: ${contentType}`);
        }
        
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('success', 'Stok işlemi başarıyla tamamlandı');
            loadStockData(currentPage);
            const modal = bootstrap.Modal.getInstance(document.getElementById('stockOperationModal'));
            modal.hide();
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Stok işlemi sırasında hata oluştu: ' + error.message);
    });
}

// Submit edit stock
function submitEditStock() {
    const stockId = document.getElementById('editStockId').value;
    const name = document.getElementById('editStockName').value;
    const code = document.getElementById('editStockCode').value;
    const unitType = document.getElementById('editStockUnitType').value;
    const criticalLevel = document.getElementById('editStockCriticalLevel').value;
    const note = document.getElementById('editStockNote').value;
    
    if (!name || !code || !unitType || !criticalLevel) {
        showToast('error', 'Tüm alanlar gereklidir');
        return;
    }
    
    const data = {
        id: stockId,
        name: name,
        code: code,
        unit_type: unitType,
        critical_level: criticalLevel,
        note: note
    };
    
    fetch('/admin/stock/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Ekipman başarıyla güncellendi');
            loadStockData(currentPage);
            const modal = bootstrap.Modal.getInstance(document.getElementById('stockOperationModal'));
            modal.hide();
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Ekipman güncellenirken hata oluştu');
    });
}

// Clear stock operation form
function clearStockOperationForm() {
    document.getElementById('operationAmount').value = '';
    document.getElementById('operationCode').value = '';
    document.getElementById('operationNote').value = '';
    document.getElementById('operationBrand').value = '';
    document.getElementById('operationModel').value = '';
    document.getElementById('operationSize').value = '';
    document.getElementById('operationFeature').value = '';
    document.getElementById('operationUnitType').value = '';
    document.getElementById('operationPhoto').value = '';
}

// Load equipment for editing
function loadEquipmentForEdit(equipmentId) {
    fetch(`/admin/equipment/${equipmentId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const equipment = data.data;
                document.getElementById('editStockId').value = equipment.id;
                document.getElementById('editStockName').value = equipment.name || '';
                document.getElementById('editStockCode').value = equipment.code || '';
                document.getElementById('editStockUnitType').value = equipment.unit_type || 'adet';
                document.getElementById('editStockCriticalLevel').value = equipment.critical_level || 0;
                document.getElementById('editStockNote').value = equipment.note || '';
            }
        })
        .catch(error => {
            console.error('Error loading equipment:', error);
            showToast('error', 'Ekipman bilgileri yüklenemedi');
        });
}

// Add equipment form submission
function setupAddEquipmentForm() {
    const addProductForm = document.getElementById('addProductForm');
    if (addProductForm) {
        addProductForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitAddEquipment();
        });
    }
    
    // Setup modal event listeners
    const addProductModal = document.getElementById('addProductModal');
    if (addProductModal) {
        addProductModal.addEventListener('shown.bs.modal', function() {
            // Re-setup equipment selection handlers when modal is shown
            setupEquipmentSelectionHandler();
            
            // Setup equipment selection handler for quick add mode
            const quickAddEquipmentSelect = document.querySelector('#quantityOnlySection select[name="equipment_id"]');
            if (quickAddEquipmentSelect) {
                quickAddEquipmentSelect.addEventListener('change', function() {
                    const equipmentId = this.value;
                    console.log('Equipment selected, ID:', equipmentId);
                    
                    if (equipmentId) {
                        loadEquipmentStocks(equipmentId);
                        document.getElementById('quickAddCodeSelect').style.display = 'block';
                    } else {
                        document.getElementById('quickAddCodeSelect').style.display = 'none';
                    }
                });
            }
            
        });
        
        addProductModal.addEventListener('hidden.bs.modal', function() {
            // Clear backdrop when modal is hidden
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            
            // Reset form
            document.getElementById('addProductForm').reset();
            
            // Reset to quantity only mode
            const quantityOnlyMode = document.getElementById('quantityOnlyMode');
            if (quantityOnlyMode) {
                quantityOnlyMode.checked = true;
                toggleQuantityMode();
            }
        });
    }
}

// Submit add equipment
function submitAddEquipment() {
    const formData = new FormData(document.getElementById('addProductForm'));
    const quantityOnlyMode = document.getElementById('quantityOnlyMode').checked;
    
    // Prepare data based on mode
    let data = {};
    
    if (quantityOnlyMode) {
        // Quick add mode
        const equipmentId = formData.get('equipment_id');
        const quantityInput = document.querySelector('#quantityOnlySection input[name="quantity"]');
        const quantity = quantityInput ? quantityInput.value : formData.get('quantity');
        
        if (!equipmentId) {
            showToast('error', 'Lütfen bir ekipman seçin');
            return;
        }
        
        if (!quantity || quantity <= 0) {
            showToast('error', 'Lütfen geçerli bir miktar girin');
            return;
        }
        
        // Seçilen ekipmanın individual_tracking değerini al
        const equipmentSelect = document.querySelector('#quantityOnlySection select[name="equipment_id"]');
        const selectedOption = equipmentSelect.options[equipmentSelect.selectedIndex];
        const individualTracking = selectedOption.getAttribute('data-individual-tracking') === 'true';
        
        data = {
            equipment_id: equipmentId,
            quantity: quantity,
            operation_type: 'in',
            individual_tracking: individualTracking
        };
    } else {
        // Manual add mode
        const name = formData.get('name');
        const categoryId = formData.get('category_id');
        const quantity = formData.get('manual_quantity');
        
        if (!name || !categoryId || !quantity) {
            showToast('error', 'Ekipman adı, kategori ve miktar gereklidir');
            return;
        }
        
        data = {
            name: name,
            category_id: categoryId,
            brand: formData.get('brand') || '',
            model: formData.get('model') || '',
            size: formData.get('size') || '',
            feature: formData.get('feature') || '',
            quantity: quantity,
            unit_type: formData.get('unit_type') || 'adet',
            critical_level: formData.get('critical_level') || 3,
            individual_tracking: formData.get('individual_tracking') === 'on' ? true : false,
            note: formData.get('note') || '',
            operation_type: 'in'
        };
    }
    
    // Add photo if exists
    const photo = formData.get('photo');
    if (photo && photo.size > 0) {
        data.photo = photo;
    }
    
    // Create FormData for submission
    const submitFormData = new FormData();
    
    // Add all data fields
    Object.keys(data).forEach(key => {
        if (data[key] !== null && data[key] !== undefined) {
            // Convert boolean values to string for FormData
            const value = typeof data[key] === 'boolean' ? data[key].toString() : data[key];
            submitFormData.append(key, value);
        }
    });
    
    // Add photo if exists
    if (photo && photo.size > 0) {
        submitFormData.append('photo', photo);
    }
    
    // Determine endpoint based on mode
    let endpoint, method, headers, body;
    
    if (quantityOnlyMode) {
        // Quick add mode - use stock operation endpoint
        const equipmentId = data.equipment_id;
        endpoint = `/admin/stock/${equipmentId}/operation`;
        method = 'POST';
        headers = {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };
        body = JSON.stringify({
            operation_type: 'in',
            amount: parseInt(data.quantity),
            note: 'Hızlı ekleme'
        });
    } else {
        // Manual add mode - use store endpoint
        endpoint = '/admin/stock/store';
        method = 'POST';
        headers = {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };
        body = submitFormData;
    }
    
    // Submit to backend
    fetch(endpoint, {
        method: method,
        headers: headers,
        body: body
    })
    .then(response => {
        console.log('Add equipment response status:', response.status);
        console.log('Add equipment response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error(`Expected JSON response, got: ${contentType}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Add equipment response data:', data);
        if (data.success) {
            showToast('success', 'Ekipman başarıyla eklendi');
            loadStockData(currentPage);
            const modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
            modal.hide();
            document.getElementById('addProductForm').reset();
        } else {
            console.error('Add equipment error response:', data);
            showToast('error', data.message || 'Ekipman eklenirken hata oluştu');
        }
    })
    .catch(error => {
        console.error('Add equipment error:', error);
        console.error('Error details:', {
            message: error.message,
            stack: error.stack
        });
        showToast('error', 'Ekipman eklenirken hata oluştu: ' + error.message);
    });
}

// Setup equipment selection change handler
function setupEquipmentSelectionHandler() {
    // Setup for both quantity section and manual section equipment selects
    const equipmentSelects = document.querySelectorAll('select[name="equipment_id"]');
    
    equipmentSelects.forEach(equipmentSelect => {
        equipmentSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const individualTracking = selectedOption.getAttribute('data-individual-tracking') === 'true';
            
            // Find the corresponding quantity input in the same section
            const section = this.closest('.row') || this.closest('#quantityOnlySection') || this.closest('#manualEquipmentSection');
            const quantityInput = section ? section.querySelector('input[name="quantity"]') : document.querySelector('input[name="quantity"]');
            const quantityHelp = section ? section.querySelector('#quantityHelp') : document.getElementById('quantityHelp');
            
            if (quantityInput) {
                if (individualTracking) {
                    quantityInput.value = '1';
                    quantityInput.disabled = true;
                    if (quantityHelp) {
                        quantityHelp.textContent = 'Ayrı takip ekipmanları için miktar otomatik 1 olur';
                    }
                } else {
                    quantityInput.disabled = false;
                    if (quantityHelp) {
                        quantityHelp.textContent = 'Toplu takip ekipmanları için miktar girebilirsiniz';
                    }
                }
            }
        });
    });
    
    // Setup individual tracking checkbox handler for manual section
    const individualTrackingCheckbox = document.querySelector('input[name="individual_tracking"]');
    if (individualTrackingCheckbox) {
        individualTrackingCheckbox.addEventListener('change', function() {
            const manualQuantityInput = document.querySelector('input[name="manual_quantity"]');
            const manualQuantityLabel = document.getElementById('manualQuantityLabel');
            
            if (manualQuantityInput) {
                if (this.checked) {
                    // Individual tracking enabled - set quantity to 1 and hide input
                    manualQuantityInput.value = '1';
                    manualQuantityInput.style.display = 'none';
                    if (manualQuantityLabel) {
                        manualQuantityLabel.innerHTML = 'Miktar <small class="text-muted">(Ayrı takip için otomatik 1)</small>';
                    }
                } else {
                    // Individual tracking disabled - show quantity input
                    manualQuantityInput.style.display = 'block';
                    manualQuantityInput.disabled = false;
                    if (manualQuantityLabel) {
                        manualQuantityLabel.textContent = 'Miktar';
                    }
                }
            }
        });
    }
}

// Load equipment stocks for quick add
function loadEquipmentStocks(equipmentId) {
    console.log('Loading equipment stocks for ID:', equipmentId);
    
    fetch(`/admin/equipment/${equipmentId}/stocks`)
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Stocks data:', data);
            
            if (data.success && data.stocks) {
                const stocks = data.stocks;
                const codeSelect = document.getElementById('quickAddExistingCodesSelect');
                
                console.log('Stocks length:', stocks.length);
                
                // Clear existing options
                codeSelect.innerHTML = '<option value="">Kod seçiniz...</option>';
                
                // Add stock codes as options
                if (stocks && stocks.length > 0) {
                    stocks.forEach(stock => {
                        console.log('Adding stock option:', stock);
                        const option = document.createElement('option');
                        option.value = stock.id;
                        option.textContent = `${stock.code} - ${stock.brand || 'Marka yok'} ${stock.model || 'Model yok'}`;
                        option.dataset.brand = stock.brand || '';
                        option.dataset.model = stock.model || '';
                        option.dataset.size = stock.size || '';
                        option.dataset.feature = stock.feature || '';
                        codeSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Bu ekipman için kod bulunamadı';
                    option.disabled = true;
                    codeSelect.appendChild(option);
                }
            } else {
                console.log('No stocks data found');
            }
        })
        .catch(error => {
            console.error('Error loading equipment stocks:', error);
        });
}


// Handle per page change
function handlePerPageChange() {
    const perPageSelect = document.getElementById('perPageSelect');
    const perPage = perPageSelect.value;
    
    // Update current filters with per_page
    currentFilters.per_page = perPage;
    currentPage = 1; // Reset to first page
    
    // Reload data with new per_page
    loadStockData();
}

// Ensure functions are globally available
window.loadStockData = loadStockData;
window.showStockOperation = showStockOperation;
window.viewStockDetails = viewStockDetails;
window.deleteStock = deleteStock;
window.submitStockOperation = submitStockOperation;
window.submitEditStock = submitEditStock;
window.toggleQuantityMode = toggleQuantityMode;
window.submitAddEquipment = submitAddEquipment;
window.handlePerPageChange = handlePerPageChange;
