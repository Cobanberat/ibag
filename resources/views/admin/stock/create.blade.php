@extends('layouts.admin')
@section('content')
<div class="container mt-4">
    <h3 class="mb-4 fw-bold"><i class="fas fa-plus me-2 text-primary"></i>Yeni Ekipman Ekle</h3>
    <form id="addProductForm" enctype="multipart/form-data" method="POST" action="{{ route('stock.store') }}">
        @csrf
        <div class="row g-3">
            <!-- Kategori Seçimi -->
            <div class="col-md-6">
                <label for="category_id" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">Kategori Seçiniz</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Ekipman Adı -->
            <div class="col-md-6">
                <label for="name" class="form-label fw-bold">Ekipman Adı <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" required placeholder="Örn: Jeneratör">
            </div>
            
            <!-- Marka ve Model -->
            <div class="col-md-4">
                <label for="brand" class="form-label fw-bold">Marka</label>
                <input type="text" class="form-control" id="brand" name="brand" placeholder="Örn: Honda">
            </div>
            <div class="col-md-4">
                <label for="model" class="form-label fw-bold">Model</label>
                <input type="text" class="form-control" id="model" name="model" placeholder="Örn: EU3000i">
            </div>
            <div class="col-md-4">
                <label for="size" class="form-label fw-bold">Beden/Özellik</label>
                <input type="text" class="form-control" id="size" name="size" placeholder="Örn: 3KW, XL, 1000W">
            </div>
            
            <!-- Özellik ve Açıklama -->
            <div class="col-md-6">
                <label for="feature" class="form-label fw-bold">Özellik</label> 
                <textarea class="form-control" id="feature" name="feature" rows="2" placeholder="Ekipman özellikleri..."></textarea>
            </div>
            
            <!-- Miktar ve Birim -->
            <div class="col-md-3">
                <label for="quantity" class="form-label fw-bold">Adet <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1" required>
            </div>
            <div class="col-md-3">
                <label for="unit_type" class="form-label fw-bold">Birim Türü <span class="text-danger">*</span></label>
                <select class="form-select" id="unit_type" name="unit_type" required>
                    <option value="adet">Adet</option>
                    <option value="metre">Metre</option>
                    <option value="kilogram">Kilogram</option>  
                    <option value="litre">Litre</option>
                    <option value="paket">Paket</option>
                    <option value="kutu">Kutu</option>
                    <option value="çift">Çift</option>
                    <option value="takım">Takım</option>
                </select>
            </div>
            
            <!-- Kritik Seviye -->
            <div class="col-md-6">
                <label for="critical_level" class="form-label fw-bold">Kritik Seviye</label>
                <input type="number" class="form-control" id="critical_level" name="critical_level" min="1" value="3" step="0.01">
                <small class="form-text text-muted" id="criticalLevelHelp">Birim türüne göre kritik seviye</small>
            </div>
            
            <!-- Kod ve Konum -->
            <div class="col-md-6">
                <label for="code" class="form-label fw-bold">Ekipman Kodu</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="code" name="code" placeholder="Otomatik oluşturulur veya elle girin">
                    <button class="btn btn-outline-primary" type="button" id="generateCodeBtn" title="Kod Oluştur">
                        <i class="fas fa-magic"></i>
                    </button>
                </div>
                <small class="form-text text-muted">
                    <strong>Ayrı Takip:</strong> Her ekipmana benzersiz kod<br>
                    <strong>Çoklu Takip:</strong> Tek kod (otomatik oluşturulur)
                </small>
            </div>
            
            <div class="col-md-6">
                <label for="location" class="form-label fw-bold">Konum</label>
                <input type="text" class="form-control" id="location" name="location" placeholder="Örn: Depo A, Raf 1">
            </div>
            
            <!-- Stok Durumu (Yeni sistemde stock_depo.status) -->
            <div class="col-md-6">
                <label for="stock_status" class="form-label fw-bold">Stok Durumu</label>
                <select class="form-select" id="stock_status" name="stock_status">
                    <option value="Aktif">Aktif</option>
                    <option value="Kullanımda">Kullanımda</option>
                    <option value="Yok">Yok</option>
                    <option value="Sıfır">Sıfır</option>
                </select>
                <small class="form-text text-muted">Stok miktarı durumu (Arıza/Bakım durumu ayrı tabloda tutulur)</small>
            </div>
            
            <!-- Sonraki Bakım Tarihi -->
            <div class="col-md-6">
                <label for="next_maintenance_date" class="form-label fw-bold">Sonraki Bakım Tarihi</label>
                <input type="date" class="form-control" id="next_maintenance_date" name="next_maintenance_date">
                <small class="form-text text-muted">Planlanan bakım tarihi (opsiyonel)</small>
            </div>
            
            <!-- Fotoğraf -->
            <div class="col-md-6">
                <label for="photo" class="form-label fw-bold">Ekipman Fotoğrafı</label>
                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                <small class="form-text text-muted">JPG, PNG, GIF formatında (max: 2MB)</small>
            </div>
            
            <!-- Not -->
            <div class="col-12">
                <label for="note" class="form-label fw-bold">Not</label>
                <textarea class="form-control" id="note" name="note" rows="2" placeholder="Ek bilgi (opsiyonel)"></textarea>
            </div>
            
            <!-- Ayrı Takip Seçeneği -->
            <div class="col-12">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="individual_tracking" name="individual_tracking" value="1">
                    <label class="form-check-label fw-bold" for="individual_tracking">
                        <i class="fas fa-barcode me-2"></i>Her ürünü ayrı ayrı takip et
                    </label>
                </div>
                <small class="text-muted">
                    <strong>Aktifse:</strong> Her ürün ayrı kod, ayrı resim, tek adet (Jeneratör, bilgisayar gibi)<br>
                    <strong>Kapalıysa:</strong> Tek kod, tek resim, miktar bazlı (Kablo, vida gibi)
                </small>
            </div>
            
            <!-- Arıza/Bakım Durumu (Yeni sistemde faults tablosunda) -->
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Bilgi:</strong> Arıza ve bakım durumları ayrı bir sistemde yönetilir. 
                    Ekipman ekledikten sonra gerekirse arıza/bakım bildirimi yapabilirsiniz.
                </div>
            </div>
        </div>
        
        <!-- Kaydet Butonu -->
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary btn-lg px-4">
                <i class="fas fa-save me-2"></i>Kaydet
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addProductForm');
    const unitTypeSelect = document.getElementById('unit_type');
    const criticalLevelInput = document.getElementById('critical_level');
    const criticalLevelHelp = document.getElementById('criticalLevelHelp');
    
    // Birim türü değiştiğinde kritik seviye yardım metnini güncelle
    function updateCriticalLevelHelp() {
        const unitType = unitTypeSelect.value;
        const unitLabels = {
            'adet': 'Adet',
            'metre': 'Metre',
            'kilogram': 'Kilogram',
            'litre': 'Litre',
            'paket': 'Paket',
            'kutu': 'Kutu',
            'çift': 'Çift',
            'takım': 'Takım'
        };
        
        const label = unitLabels[unitType] || 'Adet';
        criticalLevelHelp.textContent = `${label} cinsinden kritik seviye (örn: ${unitType === 'adet' ? '3' : unitType === 'metre' ? '100' : '5'})`;
        
        // Birim türüne göre step değerini ayarla
        if (unitType === 'adet' || unitType === 'paket' || unitType === 'kutu' || unitType === 'çift' || unitType === 'takım') {
            criticalLevelInput.step = '1';
            criticalLevelInput.min = '1';
        } else {
            criticalLevelInput.step = '0.01';
            criticalLevelInput.min = '0.01';
        }
        
        // Birim türüne göre placeholder güncelle
        if (unitType === 'metre') {
            criticalLevelInput.placeholder = '100';
        } else if (unitType === 'kilogram') {
            criticalLevelInput.placeholder = '5';
        } else if (unitType === 'litre') {
            criticalLevelInput.placeholder = '10';
        } else {
            criticalLevelInput.placeholder = '3';
        }
    }
    
    // Sayfa yüklendiğinde ve birim türü değiştiğinde yardım metnini güncelle
    updateCriticalLevelHelp();
    unitTypeSelect.addEventListener('change', updateCriticalLevelHelp);
    
    // Form submit işlemi
    form.addEventListener('submit', function(e) {
        // Form verilerini kontrol et
        const formData = new FormData(form);
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }
        
        // Form submit edilirken loading göster
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Kaydediliyor...';
        submitBtn.disabled = true;
        
        // Form normal şekilde submit edilsin
    });
    
    // Individual tracking checkbox kontrolü
    const individualTrackingCheckbox = document.getElementById('individual_tracking');
    const quantityInput = document.getElementById('quantity');
    
    individualTrackingCheckbox.addEventListener('change', function() {
        if (this.checked) {
            quantityInput.value = '1';
            quantityInput.readOnly = true;
            quantityInput.style.backgroundColor = '#f8f9fa';
        } else {
            quantityInput.readOnly = false;
            quantityInput.style.backgroundColor = '';
        }
    });
    
    // Kod oluşturma işlemi
    const codeInput = document.getElementById('code');
    const generateCodeBtn = document.getElementById('generateCodeBtn');
    
    // Ekipman kodu oluştur
    generateCodeBtn.addEventListener('click', function() {
        const equipmentName = document.getElementById('name').value;
        const brand = document.getElementById('brand').value;
        const model = document.getElementById('model').value;
        
        if (!equipmentName) {
            showAlert('Önce ekipman adını girin!', 'warning');
            return;
        }
        
        // Benzersiz kod oluştur
        const timestamp = Date.now().toString().slice(-6); // Son 6 hane
        const random = Math.random().toString(36).substring(2, 5).toUpperCase();
        const namePrefix = equipmentName.substring(0, 3).toUpperCase().replace(/[^A-Z]/g, '');
        const brandPrefix = brand ? brand.substring(0, 2).toUpperCase().replace(/[^A-Z]/g, '') : '';
        
        let generatedCode;
        if (brandPrefix) {
            generatedCode = `${namePrefix}-${brandPrefix}-${timestamp}-${random}`;
        } else {
            generatedCode = `${namePrefix}-${timestamp}-${random}`;
        }
        
        codeInput.value = generatedCode;
        showAlert('Ekipman kodu oluşturuldu!', 'success');
    });
    
    // Alert gösterme fonksiyonu
    function showAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        // 3 saniye sonra otomatik kapat
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 3000);
    }
    
    // Fotoğraf boyut kontrolü
    const photoInput = document.getElementById('photo');
    photoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (file.size > maxSize) {
                alert('Dosya boyutu 2MB\'dan büyük olamaz!');
                this.value = '';
            }
        }
    });
});
</script>
@endsection
