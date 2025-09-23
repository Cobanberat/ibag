// Profil Yönetimi JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Profil formu
    const profileForm = document.getElementById('profileForm');
    const passwordForm = document.getElementById('passwordForm');
    const resetFormBtn = document.getElementById('resetForm');

    // Profil formu submit
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
            const formData = new FormData(this);
            
            fetch('/admin/profilim', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-HTTP-Method-Override': 'PUT',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast('success', 'Başarılı!', data.message);
                    // Sayfayı yenile
                    setTimeout(() => location.reload(), 1500);
                } else {
                    if (data.errors) {
                        let errorMessage = 'Validasyon hataları:\n';
                        Object.keys(data.errors).forEach(key => {
                            errorMessage += `- ${data.errors[key][0]}\n`;
                        });
                        showToast('error', 'Validasyon Hatası!', errorMessage);
                    } else {
                        showToast('error', 'Hata!', data.message || 'Bilinmeyen bir hata oluştu.');
                    }
                }
            })
            .catch(error => {
                console.error('Profil güncelleme hatası:', error);
                let errorMessage = 'Profil güncelleme sırasında hata oluştu.';
                
                if (error.message.includes('HTTP 422')) {
                    errorMessage = 'Gönderilen veriler geçersiz. Lütfen formu kontrol edin.';
                } else if (error.message.includes('HTTP 500')) {
                    errorMessage = 'Sunucu hatası oluştu. Lütfen daha sonra tekrar deneyin.';
                } else if (error.message.includes('HTTP 404')) {
                    errorMessage = 'İstek edilen sayfa bulunamadı.';
                } else if (error.message.includes('NetworkError') || error.message.includes('Failed to fetch')) {
                    errorMessage = 'İnternet bağlantınızı kontrol edin ve tekrar deneyin.';
                } else if (error.message) {
                    errorMessage = `Hata detayı: ${error.message}`;
                }
                
                showToast('error', 'Profil Güncelleme Hatası!', errorMessage);
            });
        });
    }

    // Şifre değiştirme formu
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
            // Şifre eşleşme kontrolü
            if (newPassword !== confirmPassword) {
                showToast('error', 'Hata!', 'Yeni şifreler eşleşmiyor.');
            return;
        }
        
            const formData = new FormData(this);
            
            fetch('/admin/profilim/password', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-HTTP-Method-Override': 'PUT',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast('success', 'Başarılı!', data.message);
                    // Formu temizle
                    passwordForm.reset();
                } else {
                    if (data.errors) {
                        let errorMessage = 'Validasyon hataları:\n';
                        Object.keys(data.errors).forEach(key => {
                            errorMessage += `- ${data.errors[key][0]}\n`;
                        });
                        showToast('error', 'Validasyon Hatası!', errorMessage);
                    } else {
                        showToast('error', 'Hata!', data.message || 'Bilinmeyen bir hata oluştu.');
                    }
                }
            })
            .catch(error => {
                console.error('Şifre değiştirme hatası:', error);
                let errorMessage = 'Şifre değiştirme sırasında hata oluştu.';
                
                if (error.message.includes('HTTP 422')) {
                    errorMessage = 'Gönderilen veriler geçersiz. Lütfen formu kontrol edin.';
                } else if (error.message.includes('HTTP 500')) {
                    errorMessage = 'Sunucu hatası oluştu. Lütfen daha sonra tekrar deneyin.';
                } else if (error.message.includes('HTTP 404')) {
                    errorMessage = 'İstek edilen sayfa bulunamadı.';
                } else if (error.message.includes('NetworkError') || error.message.includes('Failed to fetch')) {
                    errorMessage = 'İnternet bağlantınızı kontrol edin ve tekrar deneyin.';
                } else if (error.message) {
                    errorMessage = `Hata detayı: ${error.message}`;
                }
                
                showToast('error', 'Şifre Değiştirme Hatası!', errorMessage);
            });
        });
    }

    // Form sıfırlama
    if (resetFormBtn) {
        resetFormBtn.addEventListener('click', function() {
            if (confirm('Formu sıfırlamak istediğinizden emin misiniz?')) {
                profileForm.reset();
                showToast('info', 'Bilgi', 'Form sıfırlandı.');
            }
        });
    }

    // Şifre gücü göstergesi
    const newPasswordField = document.getElementById('newPassword');
    if (newPasswordField) {
        newPasswordField.addEventListener('input', function() {
            const password = this.value;
            const strength = getPasswordStrength(password);
            updatePasswordStrengthIndicator(strength);
        });
    }
});

// Şifre gücü hesaplama
function getPasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    
    return strength;
}

// Şifre gücü göstergesi güncelleme
function updatePasswordStrengthIndicator(strength) {
    // Bu fonksiyon şifre gücü göstergesi için kullanılabilir
    // Şimdilik basit bir implementasyon
    const strengthLabels = ['Çok Zayıf', 'Zayıf', 'Orta', 'Güçlü', 'Çok Güçlü'];
    const strengthColors = ['danger', 'warning', 'info', 'success', 'success'];
    
    if (strength > 0) {
        console.log(`Şifre gücü: ${strengthLabels[strength - 1]}`);
    }
}

// Toast bildirimi göster
function showToast(type, title, message) {
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
