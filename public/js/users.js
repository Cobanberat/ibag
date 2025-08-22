// Kullanıcı Yönetimi JavaScript
let currentUserId = null;

// Kullanıcı detayını göster
function showUserDetail(userId) {
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
                                        ${strtoupper(substr(user.name || 'U', 0, 2))}
                                    </span>
                                </div>
                            </div>
                            <h6 class="fw-bold">Kullanıcı Bilgileri</h6>
                            <p><strong>Ad Soyad:</strong> ${user.name}</p>
                            <p><strong>E-posta:</strong> ${user.email}</p>
                            <p><strong>Kullanıcı Adı:</strong> ${user.username || 'Belirtilmemiş'}</p>
                            <p><strong>Rol:</strong> <span class="badge bg-${user.role === 'admin' ? 'primary' : 'secondary'}">${user.role === 'admin' ? 'Admin' : 'Kullanıcı'}</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Sistem Bilgileri</h6>
                            <p><strong>Durum:</strong> <span class="badge bg-${user.status === 'active' ? 'success' : 'danger'}">${user.status === 'active' ? 'Aktif' : 'Pasif'}</span></p>
                            <p><strong>Kayıt Tarihi:</strong> ${user.created_at}</p>
                            <p><strong>Son Giriş:</strong> ${user.last_login_at}</p>
                            <p><strong>E-posta Doğrulama:</strong> ${user.email_verified_at}</p>
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

// Kullanıcı düzenleme modalını göster
function editUser(userId) {
    currentUserId = userId;
    
    // AJAX ile kullanıcı bilgilerini getir
    fetch(`/admin/kullanicilar/${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.user;
                
                // Form alanlarını doldur
                document.getElementById('userId').value = user.id;
                document.getElementById('userName').value = user.name;
                document.getElementById('userEmail').value = user.email;
                document.getElementById('userUsername').value = user.username || '';
                document.getElementById('userRole').value = user.role;
                document.getElementById('userPassword').value = '';
                document.getElementById('userPasswordConfirm').value = '';
                
                // Modal başlığını güncelle
                document.getElementById('userModalLabel').textContent = 'Kullanıcı Düzenle';
                document.getElementById('saveUserBtn').textContent = 'Güncelle';
                
                // Şifre alanlarını opsiyonel yap
                document.getElementById('userPassword').required = false;
                document.getElementById('userPasswordConfirm').required = false;
                
                // Modalı göster
                const modal = new bootstrap.Modal(document.getElementById('userModal'));
                modal.show();
            } else {
                alert('Kullanıcı bilgileri alınamadı: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Kullanıcı bilgileri alınırken hata oluştu.');
        });
}

// Kullanıcı durumunu değiştir
function toggleUserStatus(userId) {
    Swal.fire({
        title: 'Emin misiniz?',
        text: 'Kullanıcı durumunu değiştirmek istediğinizden emin misiniz?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Evet, Değiştir!',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
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
                    Swal.fire({
                        title: 'Başarılı!',
                        text: data.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    setTimeout(() => location.reload(), 2000);
                } else {
                    Swal.fire({
                        title: 'Hata!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Hata!',
                    text: 'İşlem sırasında hata oluştu.',
                    icon: 'error'
                });
            });
        }
    });
}

// Kullanıcı sil
function deleteUser(userId) {
    Swal.fire({
        title: 'Emin misiniz?',
        text: 'Bu kullanıcıyı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Evet, Sil!',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
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
                    Swal.fire({
                        title: 'Silindi!',
                        text: data.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    setTimeout(() => location.reload(), 2000);
                } else {
                    Swal.fire({
                        title: 'Hata!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Hata!',
                    text: 'İşlem sırasında hata oluştu.',
                    icon: 'error'
                });
            });
        }
    });
}

// KPI filtreleme
function filterByKpi(type) {
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
    if (visibleCount === 0) {
        document.getElementById('noUserDataIllu').style.display = 'block';
    } else {
        document.getElementById('noUserDataIllu').style.display = 'none';
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
        Swal.fire({
            title: 'Uyarı!',
            text: 'Lütfen silinecek kullanıcıları seçin.',
            icon: 'warning'
        });
        return;
    }
    
    Swal.fire({
        title: 'Emin misiniz?',
        text: `${userIds.length} kullanıcıyı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Evet, Sil!',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
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
                    Swal.fire({
                        title: 'Başarılı!',
                        text: data.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    setTimeout(() => location.reload(), 2000);
                } else {
                    Swal.fire({
                        title: 'Hata!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Hata!',
                    text: 'İşlem sırasında hata oluştu.',
                    icon: 'error'
                });
            });
        }
    });
}

// Excel export
function exportUserExcel() {
    fetch('/admin/kullanicilar/export/excel')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Başarılı!',
                    text: data.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                // Burada gerçek Excel export işlemi yapılacak
            } else {
                Swal.fire({
                    title: 'Hata!',
                    text: data.message,
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Hata!',
                text: 'Export sırasında hata oluştu.',
                icon: 'error'
            });
        });
}

// Snackbar göster
function showSnackbar(message) {
    const snackbar = document.getElementById('userSnackbar');
    snackbar.textContent = message;
    snackbar.style.display = 'block';
    
    setTimeout(() => {
        snackbar.style.display = 'none';
    }, 3000);
}

// Yardımcı fonksiyonlar
function strtoupper(str) {
    return str.toUpperCase();
}

function substr(str, start, length) {
    return str.substring(start, start + length);
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Element kontrolü yap
    const userFilterRole = document.getElementById('userFilterRole');
    const userFilterStatus = document.getElementById('userFilterStatus');
    const userSearch = document.getElementById('userSearch');
    const clearUserFiltersBtn = document.getElementById('clearUserFiltersBtn');
    const addUserBtn = document.getElementById('addUserBtn');
    const addUserBtnEmpty = document.getElementById('addUserBtnEmpty');
    const bulkUserDeleteBtn = document.getElementById('bulkUserDeleteBtn');
    const bulkUserStatusBtn = document.getElementById('bulkUserStatusBtn');
    const exportUserExcelBtn = document.getElementById('exportUserExcelBtn');
    const selectAllUserRows = document.getElementById('selectAllUserRows');
    const userForm = document.getElementById('userForm');
    
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
    
    // Yeni kullanıcı ekle butonu
    if (addUserBtn) {
        addUserBtn.addEventListener('click', function() {
            // Form alanlarını temizle
            if (userForm) {
                userForm.reset();
                const userIdField = document.getElementById('userId');
                if (userIdField) userIdField.value = '';
            }
            
            // Modal başlığını güncelle
            const modalLabel = document.getElementById('userModalLabel');
            const saveBtn = document.getElementById('saveUserBtn');
            if (modalLabel) modalLabel.textContent = 'Yeni Kullanıcı Ekle';
            if (saveBtn) saveBtn.textContent = 'Kaydet';
            
            // Şifre alanlarını zorunlu yap
            const passwordField = document.getElementById('userPassword');
            const passwordConfirmField = document.getElementById('userPasswordConfirm');
            if (passwordField) passwordField.required = true;
            if (passwordConfirmField) passwordConfirmField.required = true;
            
            // Modalı göster
            const modalElement = document.getElementById('userModal');
            if (modalElement && typeof bootstrap !== 'undefined') {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        });
    }
    
    // Boş veri durumunda yeni kullanıcı ekle
    if (addUserBtnEmpty) {
        addUserBtnEmpty.addEventListener('click', function() {
            if (addUserBtn) addUserBtn.click();
        });
    }
    
    // Toplu işlem butonları
    if (bulkUserDeleteBtn) {
        bulkUserDeleteBtn.addEventListener('click', bulkDeleteUsers);
    }
    if (bulkUserStatusBtn) {
        bulkUserStatusBtn.addEventListener('click', function() {
            Swal.fire({
                title: 'Bilgi',
                text: 'Toplu durum değiştirme özelliği yakında eklenecek.',
                icon: 'info'
            });
        });
    }
    
    // Excel export
    if (exportUserExcelBtn) {
        exportUserExcelBtn.addEventListener('click', exportUserExcel);
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
    
    // Kullanıcı formu submit
    if (userForm) {
        userForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const userId = document.getElementById('userId')?.value;
            const url = userId ? `/admin/kullanicilar/${userId}` : '/admin/kullanicilar';
            const method = userId ? 'PUT' : 'POST';
            
            // PUT method için _method field ekle
            if (method === 'PUT') {
                formData.append('_method', 'PUT');
            }
            
            fetch(url, {
                method: method === 'PUT' ? 'POST' : 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                            if (data.success) {
                Swal.fire({
                    title: 'Başarılı!',
                    text: data.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                const modalElement = document.getElementById('userModal');
                if (modalElement && typeof bootstrap !== 'undefined') {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                }
                setTimeout(() => location.reload(), 2000);
            } else {
                if (data.errors) {
                    let errorMessage = 'Validasyon hataları:\n';
                    Object.keys(data.errors).forEach(key => {
                        errorMessage += `- ${data.errors[key][0]}\n`;
                    });
                    Swal.fire({
                        title: 'Validasyon Hatası!',
                        text: errorMessage,
                        icon: 'error'
                    });
                } else {
                    Swal.fire({
                        title: 'Hata!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            }
            })
                    .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Hata!',
                text: 'İşlem sırasında hata oluştu.',
                icon: 'error'
            });
        });
        });
    }
    
    // Klavye kısayolları
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'N') {
            e.preventDefault();
            if (addUserBtn) addUserBtn.click();
        }
    });
    
    // İlk yükleme
    applyUserFilters();
});