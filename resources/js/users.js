// Kullanıcı Yönetimi JavaScript
let currentUserId = null;

// Global fonksiyonlar - HTML onclick'lerden erişilebilir olması için
window.showUserDetail = function(userId) {
    console.log('showUserDetail called with userId:', userId);
    currentUserId = userId;
    
    // AJAX ile kullanıcı detayını getir
    fetch(`/admin/kullanicilar/${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.user;
                const modalBody = document.getElementById('userDetailContent');
                
                let html = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center mb-3">
                                <div class="user-avatar" style="background: ${user.avatar_color || '#6366f1'}; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white; font-weight: bold; margin: 0 auto; position: relative;">
                                    <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); white-space: nowrap;">
                                        ${(user.name || 'U').substring(0, 2).toUpperCase()}
                                    </span>
                                </div>
                            </div>
                            <h6 class="fw-bold">Kullanıcı Bilgileri</h6>
                            <p><strong>Ad Soyad:</strong> ${user.name}</p>
                            <p><strong>E-posta:</strong> ${user.email}</p>
                            <p><strong>Kullanıcı Adı:</strong> ${user.username || 'Belirtilmemiş'}</p>
                            <p><strong>Rol:</strong> <span class="badge bg-${user.role === 'admin' ? 'primary' : user.role === 'ekip_yetkilisi' ? 'info' : 'secondary'}">${user.role === 'admin' ? 'Admin' : user.role === 'ekip_yetkilisi' ? 'Ekip Yetkilisi' : user.role === 'üye' ? 'Üye' : 'Kullanıcı'}</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Sistem Bilgileri</h6>
                            <p><strong>Durum:</strong> <span class="badge bg-${user.status === 'active' ? 'success' : 'danger'}">${user.status === 'active' ? 'Aktif' : 'Pasif'}</span></p>
                            <p><strong>Kayıt Tarihi:</strong> ${user.created_at}</p>
                            <p><strong>Son Giriş:</strong> ${user.last_login_at}</p>
                        </div>
                    </div>
                `;
                
                modalBody.innerHTML = html;
                
                // Modalı göster
                const modal = new bootstrap.Modal(document.getElementById('userDetailModal'));
                modal.show();
            } else {
                alert('Kullanıcı detayı alınamadı: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Kullanıcı detayı alınırken hata oluştu.');
        });
}

// Kullanıcı düzenleme - modal kaldırıldı, sadece bilgi göster
window.editUser = function(userId) {
    console.log('editUser called with userId:', userId);
    showToast('info', 'Bilgi', 'Kullanıcı düzenleme özelliği kaldırılmıştır.');
}

// Kullanıcı durumunu değiştir
window.toggleUserStatus = function(userId) {
    if (confirm('Kullanıcı durumunu değiştirmek istediğinizden emin misiniz?')) {
            fetch(`/admin/kullanicilar/${userId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                showToast('success', 'Başarılı!', data.message);
                    setTimeout(() => location.reload(), 2000);
                } else {
                showToast('error', 'Hata!', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            showToast('error', 'Hata!', 'İşlem sırasında hata oluştu.');
        });
    }
}

// Kullanıcı sil
window.deleteUser = function(userId) {
    if (confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!')) {
            fetch(`/admin/kullanicilar/${userId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                showToast('success', 'Silindi!', data.message);
                    setTimeout(() => location.reload(), 2000);
                } else {
                showToast('error', 'Hata!', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            showToast('error', 'Hata!', 'İşlem sırasında hata oluştu.');
        });
    }
}

// KPI filtreleme
window.filterByKpi = function(type) {
    const roleFilter = document.getElementById('userFilterRole');
    const statusFilter = document.getElementById('userFilterStatus');
    
    switch(type) {
        case 'all':
            if (roleFilter) roleFilter.value = '';
            if (statusFilter) statusFilter.value = '';
            break;
        case 'admin':
            if (roleFilter) roleFilter.value = 'admin';
            if (statusFilter) statusFilter.value = '';
            break;
        case 'active':
            if (roleFilter) roleFilter.value = '';
            if (statusFilter) statusFilter.value = 'active';
            break;
        case 'new':
            // Bu ay eklenen kullanıcıları filtrele
            const currentMonth = new Date().getMonth();
            const currentYear = new Date().getFullYear();
            const rows = document.querySelectorAll('#userTableBody tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const createdDateText = row.querySelector('td:nth-child(9)').textContent;
                if (createdDateText && createdDateText !== 'Tarih yok') {
                    try {
                        // Türkçe tarih formatını parse et (dd.mm.yyyy)
                        const dateParts = createdDateText.split('.');
                        if (dateParts.length === 3) {
                            const day = parseInt(dateParts[0]);
                            const month = parseInt(dateParts[1]) - 1; // JavaScript'te ay 0-11 arası
                            const year = parseInt(dateParts[2]);
                            
                            if (month === currentMonth && year === currentYear) {
                                row.style.display = '';
                                visibleCount++;
                            } else {
                                row.style.display = 'none';
                            }
                        } else {
                            row.style.display = 'none';
                        }
                    } catch (e) {
                        row.style.display = 'none';
                    }
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Kullanıcı sayısını güncelle
            const userCountElement = document.getElementById('userCount');
            if (userCountElement) {
                userCountElement.textContent = visibleCount;
            }
            
            // Görünür kullanıcı yoksa mesaj göster
            if (visibleCount === 0) {
                const noUserDataIllu = document.getElementById('noUserDataIllu');
                if (noUserDataIllu) {
                    noUserDataIllu.style.display = 'block';
                }
            } else {
                const noUserDataIllu = document.getElementById('noUserDataIllu');
                if (noUserDataIllu) {
                    noUserDataIllu.style.display = 'none';
                }
            }
            return;
    }
    
    applyUserFilters();
}

// Kullanıcı filtrelerini uygula
function applyUserFilters() {
    const role = document.getElementById('userFilterRole').value;
    const status = document.getElementById('userFilterStatus').value;
    const search = document.getElementById('userSearch').value.toLowerCase();
    
    const rows = document.querySelectorAll('#userTableBody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        let show = true;
        
        // Rol filtresi
        if (role && row.getAttribute('data-role') !== role) {
            show = false;
        }
        
        // Durum filtresi
        if (status && row.getAttribute('data-status') !== status) {
            show = false;
        }
        
        // Arama filtresi
        if (search) {
            const name = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            const email = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
            const username = row.querySelector('td:nth-child(4) small')?.textContent.toLowerCase() || '';
            
            if (!name.includes(search) && !email.includes(search) && !username.includes(search)) {
                show = false;
            }
        }
        
        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });
    
    // Kullanıcı sayısını güncelle
    document.getElementById('userCount').textContent = visibleCount;
    
    // Görünür kullanıcı yoksa mesaj göster
    const noUserDataIllu = document.getElementById('noUserDataIllu');
    if (noUserDataIllu) {
    if (visibleCount === 0) {
            noUserDataIllu.style.display = 'block';
    } else {
            noUserDataIllu.style.display = 'none';
        }
    }
}

// Toplu işlemler
function updateBulkButtons() {
    const selectedCheckboxes = document.querySelectorAll('.user-row-check:checked');
    const selectedCount = selectedCheckboxes.length;
    
    const selectedCountBadge = document.getElementById('selectedUserCount');
    const bulkDeleteBtn = document.getElementById('bulkUserDeleteBtn');
    const bulkStatusBtn = document.getElementById('bulkUserStatusBtn');
    
    if (selectedCount > 0) {
        selectedCountBadge.style.display = 'inline';
        selectedCountBadge.textContent = `${selectedCount} kullanıcı seçildi`;
        bulkDeleteBtn.style.display = 'inline';
        bulkStatusBtn.style.display = 'inline';
    } else {
        selectedCountBadge.style.display = 'none';
        bulkDeleteBtn.style.display = 'none';
        bulkStatusBtn.style.display = 'none';
    }
}

// Toplu silme
function bulkDeleteUsers() {
    const selectedCheckboxes = document.querySelectorAll('.user-row-check:checked');
    const userIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    if (userIds.length === 0) {
        showToast('warning', 'Uyarı!', 'Lütfen silinecek kullanıcıları seçin.');
        return;
    }
    
    if (confirm(`${userIds.length} kullanıcıyı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!`)) {
            fetch('/admin/kullanicilar/bulk-delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ user_ids: userIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                showToast('success', 'Başarılı!', data.message);
                    setTimeout(() => location.reload(), 2000);
                } else {
                showToast('error', 'Hata!', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            showToast('error', 'Hata!', 'İşlem sırasında hata oluştu.');
        });
    }
}


// Toast bildirimi göster
window.showToast = function(type, title, message) {
    const toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) return;
    
    const toastId = 'toast-' + Date.now();
    const iconClass = type === 'success' ? 'fa-check-circle' : 
                     type === 'error' ? 'fa-exclamation-circle' : 
                     type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
    
    const bgClass = type === 'success' ? 'bg-success' : 
                   type === 'error' ? 'bg-danger' : 
                   type === 'warning' ? 'bg-warning' : 'bg-info';
    
    const toastHtml = `
        <div id="${toastId}" class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header ${bgClass} text-white">
                <i class="fas ${iconClass} me-2"></i>
                <strong class="me-auto">${title}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    // 5 saniye sonra otomatik kapat
    setTimeout(() => {
        const toastElement = document.getElementById(toastId);
        if (toastElement) {
            const toast = new bootstrap.Toast(toastElement);
            toast.hide();
        }
    }, 5000);
}

// Snackbar göster (eski fonksiyon - geriye uyumluluk için)
function showSnackbar(message) {
    showToast('info', 'Bilgi', message);
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Element kontrolü yap
    const userFilterRole = document.getElementById('userFilterRole');
    const userFilterStatus = document.getElementById('userFilterStatus');
    const userSearch = document.getElementById('userSearch');
    const clearUserFiltersBtn = document.getElementById('clearUserFiltersBtn');
    const bulkUserDeleteBtn = document.getElementById('bulkUserDeleteBtn');
    const bulkUserStatusBtn = document.getElementById('bulkUserStatusBtn');
    const selectAllUserRows = document.getElementById('selectAllUserRows');
    
    // Filtre event listeners
    if (userFilterRole) {
        userFilterRole.addEventListener('change', applyUserFilters);
    }
    if (userFilterStatus) {
        userFilterStatus.addEventListener('change', applyUserFilters);
    }
    if (userSearch) {
        userSearch.addEventListener('input', applyUserFilters);
    }
    
    // Filtreleri temizle
    if (clearUserFiltersBtn) {
        clearUserFiltersBtn.addEventListener('click', function() {
            if (userFilterRole) userFilterRole.value = '';
            if (userFilterStatus) userFilterStatus.value = '';
            if (userSearch) userSearch.value = '';
            applyUserFilters();
        });
    }
    
    // Yeni kullanıcı ekle butonu kaldırıldı
    
    // Toplu işlem butonları
    if (bulkUserDeleteBtn) {
        bulkUserDeleteBtn.addEventListener('click', bulkDeleteUsers);
    }
    if (bulkUserStatusBtn) {
        bulkUserStatusBtn.addEventListener('click', function() {
            showToast('info', 'Bilgi', 'Toplu durum değiştirme özelliği yakında eklenecek.');
        });
    }
    
    
    // Tüm satırları seç/kaldır
    if (selectAllUserRows) {
        selectAllUserRows.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-row-check');
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkButtons();
        });
    }
    
    // Tekil satır seçimi
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('user-row-check')) {
            updateBulkButtons();
            
            // Tüm seçili mi kontrol et
            const allCheckboxes = document.querySelectorAll('.user-row-check');
            const selectedCheckboxes = document.querySelectorAll('.user-row-check:checked');
            if (selectAllUserRows) {
                selectAllUserRows.checked = allCheckboxes.length === selectedCheckboxes.length;
            }
        }
    });
    
    // Kullanıcı formu kaldırıldı
    
    // Klavye kısayolları kaldırıldı
    
    // İlk yükleme
    applyUserFilters();
});