@extends('layouts.admin')
@section('content')
@vite(['resources/css/fault.css'])

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center">
                <img src="{{ asset('images/ibag-logo.svg') }}" alt="İBAG Logo" style="width: 24px; height: 24px; margin-right: 8px;">
                <i class="fa fa-home me-1"></i> Ana Sayfa
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.fault') }}" class="text-decoration-none">Arıza Bildirimi</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Yeni Arıza Bildirimi</li>
    </ol>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Yeni Arıza/Bakım Bildirimi</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Başarılı!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Hata!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Hata!</strong> Lütfen aşağıdaki hataları düzeltin:
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form id="faultForm" action="{{ route('admin.fault.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="equipment_stock_id" class="form-label">
                                        <i class="fas fa-tools me-1"></i>Ekipman Seçin <span class="text-danger">*</span>
                                    </label>
                                    <div class="d-flex gap-2">
                                        <select class="form-select @error('equipment_stock_id') is-invalid @enderror" 
                                                id="equipment_stock_id" name="equipment_stock_id" required>
                                            <option value="">Ekipman Seçin</option>
                                            @foreach($equipmentStocks as $stock)
                                                <option value="{{ $stock->id }}" 
                                                        data-code="{{ $stock->code ?? '' }}"
                                                        {{ old('equipment_stock_id') == $stock->id ? 'selected' : '' }}>
                                                    {{ $stock->equipment->name }} - {{ $stock->code }} ({{ $stock->equipment->category->name }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-primary qr-scan-btn" title="QR Kod Tara">
                                            <i class="fas fa-qrcode"></i>
                                        </button>
                                    </div>
                                    @error('equipment_stock_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">
                                        <i class="fas fa-tag me-1"></i>Bildirim Türü <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="">Tür Seçin</option>
                                        <option value="arıza" {{ old('type') == 'arıza' ? 'selected' : '' }}>Arıza</option>
                                        <option value="bakım" {{ old('type') == 'bakım' ? 'selected' : '' }}>Bakım</option>
                                        <option value="diğer" {{ old('type') == 'diğer' ? 'selected' : '' }}>Diğer</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">
                                        <i class="fas fa-exclamation-circle me-1"></i>Öncelik <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('priority') is-invalid @enderror" 
                                            id="priority" name="priority" required>
                                        <option value="">Öncelik Seçin</option>
                                        <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="yüksek" {{ old('priority') == 'yüksek' ? 'selected' : '' }}>Yüksek</option>
                                        <option value="acil" {{ old('priority') == 'acil' ? 'selected' : '' }}>Acil</option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reported_date" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Bildirim Tarihi <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control @error('reported_date') is-invalid @enderror" 
                                           id="reported_date" name="reported_date" 
                                           value="{{ old('reported_date', now()->format('Y-m-d\TH:i')) }}" required>
                                    @error('reported_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>Açıklama <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Arıza/bakım durumunu detaylı olarak açıklayın..." 
                                      minlength="10" required>{{ old('description') }}</textarea>
                            <div class="form-text">Minimum 10 karakter gerekli</div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">
                                <i class="fas fa-camera me-1"></i>Fotoğraf (Opsiyonel)
                            </label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                   id="photo" name="photo" accept="image/*">
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maksimum 2MB, JPG/PNG formatında</div>
                        </div>

                        <div class="form-actions mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Bildirimi Gönder
                            </button>
                            <a href="{{ route('admin.fault') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Geri Dön
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Bilgilendirme</h6>
                </div>
                <div class="card-body">
                    <h6>Bildirim Türleri:</h6>
                    <ul class="list-unstyled">
                        <li><span class="badge bg-danger me-2">Arıza</span> Ekipman çalışmıyor</li>
                        <li><span class="badge bg-warning me-2">Bakım</span> Periyodik bakım gerekli</li>
                        <li><span class="badge bg-secondary me-2">Diğer</span> Diğer durumlar</li>
                    </ul>
                    
                    <h6 class="mt-3">Öncelik Seviyeleri:</h6>
                    <ul class="list-unstyled">
                        <li><span class="badge bg-success me-2">Normal</span> Rutin işlemler</li>
                        <li><span class="badge bg-warning me-2">Yüksek</span> Acil müdahale gerekli</li>
                        <li><span class="badge bg-danger me-2">Acil</span> Kritik durum</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Kod Tarama Modal -->
<div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrScannerModalLabel">
                    <i class="fas fa-qrcode me-2"></i>QR Kod Tarama
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="qr-reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select2 initialization
    $('#equipment_stock_id').select2({
        placeholder: "Ekipman Seçin",
        allowClear: true,
        width: '100%'
    });

    const form = document.getElementById('faultForm');
    
    // Form validation
    if(form) {
        form.addEventListener('submit', function(e) {
            // Açıklama alanı kontrolü
            const description = document.getElementById('description').value.trim();
            if (description.length < 10) {
                e.preventDefault();
                showToast('Açıklama en az 10 karakter olmalıdır!', 'error');
                return;
            }

            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Gönderiliyor...';
            submitBtn.disabled = true;
            
            // Let the form submit naturally to the server
        });
    }

    // QR kod tarama özelliği
    $('.qr-scan-btn').click(function() {
        const selectElement = $('#equipment_stock_id');
        
        // QR kod tarama modalını aç
        showQrScanner(selectElement);
    });

    // QR kod tarama modalı
    function showQrScanner(selectElement) {
        // Modal'ı göster
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
            video.srcObject = stream;
            video.play();
            
            // Video yüklendiğinde QR kod tarama başlat
            video.onloadedmetadata = function() {
                startQrDetection(selectElement, video);
                showToast('Kamera başlatıldı! QR kodu kameraya doğru tutun.', 'success');
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
            }
            
            document.getElementById('qr-video-container').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Kamera Hatası:</strong><br>
                    ${errorMessage}<br>
                    <small>Hata: ${error.message}</small>
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
                
                // jsQR ile QR kod tespiti
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
                            showToast('Ekipman seçildi!', 'success');
                            return;
                        }
                    } catch (error) {
                        console.error('QR kod tespit hatası:', error);
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
        stopCamera();
    }

    // QR kod ile ekipman seç
    function selectEquipmentByQr(selectElement, qrCode) {
        // QR kod içeriğini parse et
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

        // Ekipman koduna göre seçenekleri ara
        let found = false;
        selectElement.find('option').each(function() {
            const optionCode = $(this).data('code');
            
            if (optionCode && optionCode.includes(equipmentCode)) {
                selectElement.val($(this).val()).trigger('change');
                found = true;
                return false; // Döngüyü durdur
            }
        });

        if (!found) {
            showToast('QR kod ile eşleşen ekipman bulunamadı!', 'error');
        }
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
});
</script>
@endsection
