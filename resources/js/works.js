$(document).ready(function () {
    function initSelect2(elem) {
        $(elem).select2({
            placeholder: "Ekipman Seç",
            allowClear: true,
            width: '100%'
        });
    }

    function refreshOptions() {
        // Tekrar seçimlere izin ver: bilinçli olarak hiçbir seçeneği devre dışı bırakma
        $('.equipment-select').each(function () {
            $(this).find('option').prop('disabled', false);
        });
    }

    initSelect2('.equipment-select');

    function updatePhotoInputs(row) {
        const select = row.querySelector('.equipment-select');
        const qtyInput = row.querySelector('.equipment-qty');
        const photosDiv = row.querySelector('.equipment-photos');
        const selectedOption = select.selectedOptions[0];
        const individual = selectedOption?.dataset.individual == '1';
        const stock = parseInt(selectedOption?.dataset.stock || 1);
        const equipmentName = selectedOption?.text || 'Ekipman';

        // Ekipman durumunu kontrol et
        const statusMatch = equipmentName.match(/\s-\s(.+)$/);
        const equipmentStatus = statusMatch ? statusMatch[1].trim() : '';

        // Arızalı veya bakımda ekipmanlar için özel işlem
        if (equipmentStatus.toLowerCase().includes('arızalı') || 
            equipmentStatus.toLowerCase().includes('faulty') ||
            equipmentStatus.toLowerCase().includes('bakımda') ||
            equipmentStatus.toLowerCase().includes('maintenance')) {
            
            // Miktar alanını devre dışı bırak
            qtyInput.value = 0;
            qtyInput.readOnly = true;
            qtyInput.disabled = true;
            
            // Fotoğraf alanını kapat ve durum mesajı göster
            photosDiv.innerHTML = `
                <div class="alert alert-danger mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>${equipmentStatus}</strong> - Bu ekipman zimmet alınamaz
                </div>
            `;
            photosDiv.style.display = 'block';
            return;
        }

        // Normal ekipmanlar için template'den içerik al
        let templateId;
        if (individual) {
            qtyInput.value = 1;
            qtyInput.min = 1;
            qtyInput.max = 1;
            qtyInput.readOnly = true;
            qtyInput.disabled = false;
            templateId = 'photo-template-individual';
        } else {
            qtyInput.readOnly = false;
            qtyInput.disabled = false;
            qtyInput.min = 1;
            qtyInput.max = stock;
            if (parseInt(qtyInput.value) > stock) qtyInput.value = stock;
            templateId = 'photo-template-bulk';
        }

        // Template'i klonla ve içeriği güncelle
        const template = document.getElementById(templateId);
        const clone = template.content.cloneNode(true);
        clone.querySelector('.equipment-name').textContent = equipmentName;
        
        photosDiv.innerHTML = '';
        photosDiv.appendChild(clone);
        photosDiv.style.display = 'block';
    }

    // Ekipman seçimi değişince
    $('#equipment-list').on('change', '.equipment-select', function () {
        const row = $(this).closest('.equipment-row')[0];
        updatePhotoInputs(row);
        refreshOptions();
    });

    // Adet input değişirse stok sınırını kontrol et
    $('#equipment-list').on('input', '.equipment-qty', function () {
        const row = $(this).closest('.equipment-row')[0];
        const select = row.querySelector('.equipment-select');
        const stock = parseInt(select.selectedOptions[0]?.dataset.stock || 1);
        let qty = parseInt(this.value) || 1;

        if (qty > stock) this.value = stock;
        else if (qty < 1) this.value = 1;

        updatePhotoInputs(row);
    });

    // Ekipman ekle
    $('#add-equipment').click(function () {
        const originalRow = $('.equipment-row').first();
        originalRow.find('select').select2('destroy');

        const newRow = originalRow.clone();
        newRow.find('select').val('');
        newRow.find('.equipment-qty').val(1).prop('readonly', false).prop('max', 999);
        
        // Template'den uyarı mesajını al
        const warningTemplate = document.getElementById('photo-template-warning');
        const warningClone = warningTemplate.content.cloneNode(true);
        newRow.find('.equipment-photos').html('').append(warningClone);

        $('#equipment-list').append(newRow);
        initSelect2(originalRow.find('select'));
        initSelect2(newRow.find('select'));
        refreshOptions();
    });

    // Ekipman kaldır
    $('#equipment-list').on('click', '.remove-equipment', function () {
        if ($('.equipment-row').length > 1) {
            $(this).closest('.equipment-row').remove();
            refreshOptions();
        }
    });

    // İlk satırı hazırlama
    $('.equipment-row').each(function () {
        $(this).find('.equipment-qty').prop('readonly', true);
        $(this).find('.equipment-photos').hide();
        
        // Template'den uyarı mesajını al
        const warningTemplate = document.getElementById('photo-template-warning');
        const warningClone = warningTemplate.content.cloneNode(true);
        $(this).find('.equipment-photos').html('').append(warningClone);
    });

    refreshOptions();

    // QR kod tarama özelliği
    $('#equipment-list').on('click', '.qr-scan-btn', function() {
        const row = $(this).closest('.equipment-row');
        const select = row.find('.equipment-select');
        
        // Önce basit bir test yap
        testCameraSupport();
        
        // QR kod tarama modalını aç
        showQrScanner(select);
    });

    // Kamera desteği test fonksiyonu
    function testCameraSupport() {
        console.log('=== KAMERA DESTEĞİ TEST ===');
        console.log('User Agent:', navigator.userAgent);
        console.log('Protocol:', window.location.protocol);
        console.log('Host:', window.location.host);
        console.log('navigator.mediaDevices:', navigator.mediaDevices);
        console.log('getUserMedia:', navigator.mediaDevices?.getUserMedia);
        console.log('Permissions API:', navigator.permissions);
        
        // Permissions API test
        if (navigator.permissions) {
            navigator.permissions.query({name: 'camera'}).then(function(result) {
                console.log('Kamera izni durumu:', result.state);
            }).catch(function(err) {
                console.log('Permissions API hatası:', err);
            });
        }
        
        console.log('=== TEST BİTTİ ===');
    }

    // QR kod tarama modalı
    function showQrScanner(selectElement) {
        // Modal'ı göster (Blade template'inde zaten mevcut)
        const modal = new bootstrap.Modal(document.getElementById('qrScannerModal'));
        modal.show();

        // QR kod tarayıcısını başlat
        startQrScanner(selectElement);

        // Modal kapandığında temizlik yap
        $('#qrScannerModal').off('hidden.bs.modal').on('hidden.bs.modal', function() {
            stopQrScanner();
        });
    }

    // QR kod tarayıcısını başlat
    function startQrScanner(selectElement) {
        const qrReader = document.getElementById('qr-reader');
        
        // QR tarayıcı içeriğini oluştur
        qrReader.innerHTML = `
            <div class="text-center">
                <div class="mb-3">
                    <i class="fas fa-qrcode fa-3x text-primary mb-3"></i>
                    <h4>QR Kod Tarama</h4>
                </div>
                
                <div class="mb-3">
                    <div id="qr-video-container" style="position: relative; max-width: 400px; margin: 0 auto; border-radius: 12px; overflow: hidden;">
                        <video id="qr-video" style="width: 100%; height: auto; display: block;" autoplay muted playsinline></video>
                        <div id="qr-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; pointer-events: none;">
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 250px; height: 250px; border: 3px solid #00ff00; border-radius: 12px; background: rgba(0, 255, 0, 0.1);">
                                <div style="position: absolute; top: -3px; left: -3px; width: 30px; height: 30px; border-top: 6px solid #00ff00; border-left: 6px solid #00ff00; border-radius: 6px 0 0 0;"></div>
                                <div style="position: absolute; top: -3px; right: -3px; width: 30px; height: 30px; border-top: 6px solid #00ff00; border-right: 6px solid #00ff00; border-radius: 0 6px 0 0;"></div>
                                <div style="position: absolute; bottom: -3px; left: -3px; width: 30px; height: 30px; border-bottom: 6px solid #00ff00; border-left: 6px solid #00ff00; border-radius: 0 0 0 6px;"></div>
                                <div style="position: absolute; bottom: -3px; right: -3px; width: 30px; height: 30px; border-bottom: 6px solid #00ff00; border-right: 6px solid #00ff00; border-radius: 0 0 6px 0;"></div>
                            </div>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 250px; height: 2px; background: linear-gradient(90deg, transparent, #00ff00, transparent); animation: scan 2s linear infinite;"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <style>
                @keyframes scan {
                    0% { transform: translate(-50%, -50%) translateY(-125px); }
                    100% { transform: translate(-50%, -50%) translateY(125px); }
                }
            </style>
        `;
        
        // Kamera başlat
        startCamera(selectElement);
    }
    
    // Kamera başlat
    function startCamera(selectElement) {
        const video = document.getElementById('qr-video');
        
        // Debug bilgileri
        console.log('Navigator:', navigator);
        console.log('MediaDevices:', navigator.mediaDevices);
        console.log('getUserMedia:', navigator.mediaDevices?.getUserMedia);
        console.log('Protocol:', window.location.protocol);
        console.log('Host:', window.location.host);
        
        // HTTPS kontrolü
        if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost') {
            document.getElementById('qr-video-container').innerHTML = `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>HTTPS Gerekli:</strong><br>
                    Kamera erişimi için HTTPS bağlantısı gereklidir.<br>
                    <small>Mevcut protokol: ${window.location.protocol}</small>
                </div>
            `;
            return;
        }
        
        // Kamera API kontrolü
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            document.getElementById('qr-video-container').innerHTML = `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Tarayıcı Desteği:</strong><br>
                    Tarayıcınız kamera erişimini desteklemiyor.<br>
                    <small>Lütfen güncel bir tarayıcı kullanın (Chrome, Firefox, Safari).</small>
                    <div class="mt-2">
                        <small class="text-muted">
                            Debug: navigator.mediaDevices = ${navigator.mediaDevices ? 'mevcut' : 'yok'}<br>
                            getUserMedia = ${navigator.mediaDevices?.getUserMedia ? 'mevcut' : 'yok'}
                        </small>
                    </div>
                </div>
            `;
            return;
        }
        
        // Kamera izni iste
            navigator.mediaDevices.getUserMedia({ 
                video: { 
                    facingMode: 'environment', // Arka kamera
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                } 
            })
            .then(function(stream) {
            console.log('Kamera stream alındı:', stream);
                video.srcObject = stream;
                video.play();
                
                // Video yüklendiğinde QR kod tarama başlat
                video.onloadedmetadata = function() {
                console.log('Video metadata yüklendi');
                    startQrDetection(selectElement, video);
                };
            })
            .catch(function(error) {
                console.error('Kamera erişim hatası:', error);
                let errorMessage = 'Kamera erişim hatası oluştu.';
                
                if (error.name === 'NotAllowedError') {
                    errorMessage = 'Kamera izni reddedildi. Lütfen tarayıcı ayarlarından kamera iznini verin.';
                } else if (error.name === 'NotFoundError') {
                    errorMessage = 'Kamera bulunamadı. Lütfen cihazınızda kamera olduğundan emin olun.';
                } else if (error.name === 'NotSupportedError') {
                    errorMessage = 'HTTPS bağlantısı gerekli. Lütfen güvenli bağlantı kullanın.';
            } else if (error.name === 'OverconstrainedError') {
                errorMessage = 'Kamera ayarları desteklenmiyor. Daha basit ayarlar deneniyor...';
                // Daha basit ayarlarla tekrar dene
                return navigator.mediaDevices.getUserMedia({ video: true });
                }
                
                document.getElementById('qr-video-container').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Kamera Hatası:</strong><br>
                        ${errorMessage}<br>
                        <small>Hata: ${error.message}</small>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="location.reload()">
                                <i class="fas fa-refresh me-1"></i>Sayfayı Yenile
                            </button>
                        </div>
                    </div>
                `;
            });
    }
    
    // QR kod tespiti
    function startQrDetection(selectElement, video) {
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        let isScanning = true;
        
        function detectQR() {
            if (!isScanning) return;
            
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                
                // jsQR ile gerçek QR kod tespiti
                if (typeof jsQR !== 'undefined') {
                    try {
                        const code = jsQR(imageData.data, imageData.width, imageData.height, {
                            inversionAttempts: "dontInvert"
                        });
                        
                        if (code && code.data) {
                            console.log('QR kod tespit edildi:', code.data);
                            
                            // QR kod bulundu, ekipman seç
                            isScanning = false;
                            selectEquipmentByQr(selectElement, code.data);
                            stopCamera();
                            bootstrap.Modal.getInstance(document.getElementById('qrScannerModal')).hide();
                            return;
                        }
                    } catch (error) {
                        console.error('QR kod tespit hatası:', error);
                    }
                } else {
                    console.warn('jsQR kütüphanesi yüklenmemiş');
                    // jsQR yüklenmemişse test modu
                    if (Math.random() < 0.001) { // %0.1 şans
                        const testCodes = ['EQ-123456', 'EQ-789012', 'EQ-345678'];
                        const randomCode = testCodes[Math.floor(Math.random() * testCodes.length)];
                        
                        isScanning = false;
                        selectEquipmentByQr(selectElement, randomCode);
                        stopCamera();
                        bootstrap.Modal.getInstance(document.getElementById('qrScannerModal')).hide();
                        return;
                    }
                }
            }
            
            // Devam et
            if (isScanning) {
                requestAnimationFrame(detectQR);
            }
        }
        
        detectQR();
        
        // Modal kapatıldığında taramayı durdur
        $('#qrScannerModal').on('hidden.bs.modal', function() {
            isScanning = false;
        });
    }
    
    // Kamera durdur
    function stopCamera() {
        const video = document.getElementById('qr-video');
        if (video && video.srcObject) {
            const tracks = video.srcObject.getTracks();
            tracks.forEach(track => track.stop());
            video.srcObject = null;
        }
    }

    // QR kod tarayıcısını durdur
    function stopQrScanner() {
        // Event listener'ları temizle
        $('#stop-camera-btn').off();
        
        // Kamera durdur
        stopCamera();
    }

    // QR kod ile ekipman seç
    function selectEquipmentByQr(selectElement, qrCode) {
        // QR kod içeriğini parse et (JSON formatında olabilir)
        let equipmentCode = qrCode;
        try {
            const qrData = JSON.parse(qrCode);
            if (qrData.code) {
                equipmentCode = qrData.code;
            } else if (qrData.id) {
                // Ekipman ID'si varsa direkt seç
                selectElement.val(qrData.id).trigger('change');
                return;
            }
        } catch (e) {
            // JSON değilse direkt kodu kullan
        }

        // Tüm ekipmanları API'den al ve kontrol et
        fetch('/admin/zimmet/all-equipment')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const allEquipment = data.data;
                    let found = false;
                    
                    // QR kod ile eşleşen ekipmanı bul
                    allEquipment.forEach(equipment => {
                        if (equipment.code && equipment.code.includes(equipmentCode)) {
                            found = true;
                            
                            // Arızalı veya bakımda ekipmanları kontrol et
                            if (equipment.status.toLowerCase().includes('arızalı') || 
                                equipment.status.toLowerCase().includes('faulty') ||
                                equipment.status.toLowerCase().includes('bakımda') ||
                                equipment.status.toLowerCase().includes('maintenance')) {
                                
                                // Durum mesajını göster
                                let statusMessage = '';
                                if (equipment.status.toLowerCase().includes('arızalı') || equipment.status.toLowerCase().includes('faulty')) {
                                    statusMessage = 'Bu ekipman arızalı durumda! Zimmet alınamaz.';
                                } else if (equipment.status.toLowerCase().includes('bakımda') || equipment.status.toLowerCase().includes('maintenance')) {
                                    statusMessage = 'Bu ekipman bakımda! Zimmet alınamaz.';
                                }
                                
                                showToast(statusMessage, 'warning');
                                return;
                            }
                            
                            // Normal durumda seçim yap
                            selectElement.val(equipment.id).trigger('change');
                        }
                    });
                    
                    if (!found) {
                        showToast('QR kod ile eşleşen ekipman bulunamadı!', 'error');
                    }
                } else {
                    showToast('Ekipman bilgileri alınamadı!', 'error');
                }
            })
            .catch(error => {
                console.error('QR kod kontrol hatası:', error);
                showToast('QR kod kontrol edilirken hata oluştu!', 'error');
            });
    }



    // Toast mesaj gösterme fonksiyonu
    function showToast(message, type = 'info') {
        const toastContainer = document.getElementById('toast-container');
        
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

    // Form submit kontrolü - Arızalı ekipman kontrolü
    const assignmentForm = document.getElementById('assignmentForm');
    if (assignmentForm) {
        assignmentForm.addEventListener('submit', function(e) {
            // Form validation kontrolü
            let isValid = true;
            let errorMessage = '';
            
            // Ekipman seçimi kontrolü
            $('.equipment-select').each(function(index) {
                const select = $(this);
                const selectedValue = select.val();
                
                if (!selectedValue || selectedValue === '') {
                    isValid = false;
                    errorMessage = 'Lütfen tüm ekipmanları seçin!';
                    select.addClass('is-invalid');
                    return false;
                } else {
                    select.removeClass('is-invalid');
                }
            });
            
            // Miktar kontrolü
            $('.equipment-qty').each(function(index) {
                const qty = $(this);
                const qtyValue = parseInt(qty.val());
                
                if (!qtyValue || qtyValue < 1) {
                    isValid = false;
                    errorMessage = 'Lütfen geçerli miktar girin!';
                    qty.addClass('is-invalid');
                    return false;
                } else {
                    qty.removeClass('is-invalid');
                }
            });
            
            // Fotoğraf kontrolü
            $('input[name="equipment_photo[]"]').each(function(index) {
                const photo = $(this);
                if (photo.length && photo[0].files.length === 0) {
                    isValid = false;
                    errorMessage = 'Lütfen tüm ekipmanlar için fotoğraf yükleyin!';
                    photo.addClass('is-invalid');
                    return false;
                } else {
                    photo.removeClass('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showToast(errorMessage, 'error');
                return false;
            }
            
            // Arızalı ekipman kontrolü
            let hasFaultyEquipment = false;
            $('.equipment-select').each(function() {
                const selectedOption = $(this).find('option:selected');
                const optionText = selectedOption.text();
                const statusMatch = optionText.match(/\s-\s(.+)$/);
                const equipmentStatus = statusMatch ? statusMatch[1].trim() : '';
                
                if (equipmentStatus.toLowerCase().includes('arızalı') || 
                    equipmentStatus.toLowerCase().includes('faulty') ||
                    equipmentStatus.toLowerCase().includes('bakımda') ||
                    equipmentStatus.toLowerCase().includes('maintenance')) {
                    hasFaultyEquipment = true;
                    return false; // Döngüyü durdur
                }
            });
            
            if (hasFaultyEquipment) {
                e.preventDefault(); // Form submit'i engelle
                showToast('Arızalı veya bakımda olan ekipmanlar zimmet alınamaz!', 'error');
                return false;
            }
            
            // Normal durumda form submit edilsin
        });
    } else {
        console.error('Assignment form not found!');
    }
});
