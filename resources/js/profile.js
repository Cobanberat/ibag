// Profil sayfası JS

document.addEventListener('DOMContentLoaded', function() {
    // Fotoğraf değiştir (demo)
    document.getElementById('changePhotoBtn').addEventListener('click', function() {
        alert('Profil fotoğrafı değiştirme özelliği demo amaçlıdır.');
    });
    
    // Profil bilgileri güncelle
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Form verilerini al
        const formData = {
            name: document.getElementById('profileName').value,
            email: document.getElementById('profileEmail').value,
            phone: document.getElementById('profilePhone').value,
            department: document.getElementById('profileDepartment').value,
            address: document.getElementById('profileAddress').value,
            city: document.getElementById('profileCity').value,
            country: document.getElementById('profileCountry').value
        };
        
        // Demo mesajı
        alert('Profil bilgileri başarıyla güncellendi!\n\nGüncellenen bilgiler:\n' + 
              'Ad: ' + formData.name + '\n' +
              'E-posta: ' + formData.email + '\n' +
              'Telefon: ' + formData.phone);
    });
    
    // Form sıfırla
    document.getElementById('resetForm').addEventListener('click', function() {
        if (confirm('Tüm değişiklikleri sıfırlamak istediğinizden emin misiniz?')) {
            document.getElementById('profileForm').reset();
            // Varsayılan değerleri geri yükle
            document.getElementById('profileName').value = 'Admin User';
            document.getElementById('profileEmail').value = 'admin@example.com';
            document.getElementById('profilePhone').value = '+90 555 123 4567';
            document.getElementById('profileDepartment').value = 'Yönetim';
            document.getElementById('profileAddress').value = 'İstanbul, Türkiye';
            document.getElementById('profileCity').value = 'İstanbul';
            document.getElementById('profileCountry').value = 'Türkiye';
            
            alert('Form sıfırlandı!');
        }
    });
    
    // Şifre değiştir
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const currentPassword = document.getElementById('currentPassword').value;
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        // Validasyon
        if (!currentPassword || !newPassword || !confirmPassword) {
            alert('Lütfen tüm şifre alanlarını doldurun!');
            return;
        }
        
        if (newPassword !== confirmPassword) {
            alert('Yeni şifreler eşleşmiyor!');
            return;
        }
        
        if (newPassword.length < 6) {
            alert('Yeni şifre en az 6 karakter olmalıdır!');
            return;
        }
        
        // Demo mesajı
        alert('Şifre başarıyla değiştirildi!');
        
        // Formu temizle
        document.getElementById('passwordForm').reset();
    });
    
    // Input focus efektleri
    const inputs = document.querySelectorAll('.profile-input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
            this.parentElement.style.transition = 'transform 0.2s ease';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });
});
