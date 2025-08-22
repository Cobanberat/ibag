// Arıza Yönetimi JavaScript
let currentFaultId = null;

// Arıza detayını göster
function showFaultDetail(faultId) {
    currentFaultId = faultId;
    
    // AJAX ile arıza detayını getir
    fetch(`/admin/fault/${faultId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const fault = data.fault;
                const modalBody = document.getElementById('faultDetailBody');
                
                let html = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Ekipman Bilgileri</h6>
                            <p><strong>Ad:</strong> ${fault.equipment_name}</p>
                            <p><strong>Kod:</strong> ${fault.equipment_code}</p>
                            <p><strong>Kategori:</strong> ${fault.category_name}</p>
                            <p><strong>Durum:</strong> <span class="badge bg-${getStatusColor(fault.status)}">${fault.status}</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Arıza Bilgileri</h6>
                            <p><strong>Tip:</strong> ${fault.type}</p>
                            <p><strong>Öncelik:</strong> <span class="badge priority-${fault.priority.toLowerCase()}">${fault.priority}</span></p>
                            <p><strong>Bildirim Tarihi:</strong> ${fault.reported_date}</p>
                            <p><strong>Bildiren:</strong> ${fault.reporter_name}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="fw-bold">Açıklama</h6>
                            <p>${fault.description}</p>
                        </div>
                    </div>
                `;
                
                if (fault.photo_path) {
                    html += `
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="fw-bold">Arıza Fotoğrafı</h6>
                                <img src="/storage/${fault.photo_path}" alt="Arıza Fotoğrafı" class="img-fluid rounded" style="max-width: 300px;">
                            </div>
                        </div>
                    `;
                }
                
                modalBody.innerHTML = html;
                
                // Modalı göster
                const modal = new bootstrap.Modal(document.getElementById('faultDetailModal'));
                modal.show();
            } else {
                alert('Arıza detayı alınamadı: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Arıza detayı alınırken hata oluştu.');
        });
}

// Arıza çözme modalını göster
function showResolveModal(faultId) {
    currentFaultId = faultId;
    
    // Form action URL'ini güncelle
    const form = document.getElementById('resolveFaultForm');
    form.action = form.action.replace(':id', faultId);
    
    // Fault ID'yi hidden input'a ekle
    document.getElementById('resolveFaultId').value = faultId;
    
    // Modalı göster
    const modal = new bootstrap.Modal(document.getElementById('resolveFaultModal'));
    modal.show();
}

// Durum güncelleme modalını göster
function showUpdateStatusModal(faultId) {
    currentFaultId = faultId;
    
    // Form action URL'ini güncelle
    const form = document.getElementById('updateStatusForm');
    form.action = form.action.replace(':id', faultId);
    
    // Fault ID'yi hidden input'a ekle
    document.getElementById('updateStatusFaultId').value = faultId;
    
    // Modalı göster
    const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
    modal.show();
}

// Bakım tamamlandı modalını göster
function showMaintenanceCompleteModal(equipmentId) {
    // Equipment ID'yi hidden input'a ekle
    document.getElementById('maintenanceEquipmentId').value = equipmentId;
    
    // Modalı göster
    const modal = new bootstrap.Modal(document.getElementById('maintenanceCompleteModal'));
    modal.show();
}

// Arıza giderildi modalını göster
function showFaultFixedModal(equipmentId) {
    // Equipment ID'yi hidden input'a ekle
    document.getElementById('faultFixedEquipmentId').value = equipmentId;
    
    // Modalı göster
    const modal = new bootstrap.Modal(document.getElementById('faultFixedModal'));
    modal.show();
}

// Çözülen arıza detayını göster
function showResolvedFaultDetail(faultId) {
    // AJAX ile çözülen arıza detayını getir
    fetch(`/admin/fault/${faultId}/resolved`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const fault = data.fault;
                const modalBody = document.getElementById('resolvedFaultDetailBody');
                
                let html = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Ekipman Bilgileri</h6>
                            <p><strong>Ad:</strong> ${fault.equipment_name}</p>
                            <p><strong>Kod:</strong> ${fault.equipment_code}</p>
                            <p><strong>Kategori:</strong> ${fault.category_name}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Çözüm Bilgileri</h6>
                            <p><strong>Çözüm Tarihi:</strong> ${fault.resolved_date}</p>
                            <p><strong>Çözen:</strong> ${fault.resolver_name}</p>
                            <p><strong>Maliyet:</strong> ${fault.resolution_cost ? fault.resolution_cost + ' ₺' : 'Belirtilmemiş'}</p>
                            <p><strong>Süre:</strong> ${fault.resolution_time ? fault.resolution_time + ' saat' : 'Belirtilmemiş'}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="fw-bold">Çözüm Açıklaması</h6>
                            <p>${fault.resolution_note}</p>
                        </div>
                    </div>
                `;
                
                if (fault.resolved_photo_path) {
                    html += `
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="fw-bold">Çözüm Sonrası Fotoğraf</h6>
                                <img src="/storage/${fault.resolved_photo_path}" alt="Çözüm Sonrası Fotoğraf" class="img-fluid rounded" style="max-width: 300px;">
                            </div>
                        </div>
                    `;
                }
                
                modalBody.innerHTML = html;
                
                // Modalı göster
                const modal = new bootstrap.Modal(document.getElementById('resolvedFaultDetailModal'));
                modal.show();
            } else {
                alert('Çözülen arıza detayı alınamadı: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Çözülen arıza detayı alınırken hata oluştu.');
        });
}

// Durum rengini al
function getStatusColor(status) {
    switch(status.toLowerCase()) {
        case 'beklemede': return 'secondary';
        case 'işlemde': return 'info';
        case 'çözüldü': return 'success';
        case 'iptal edildi': return 'danger';
        default: return 'secondary';
    }
}

// Form submit işlemleri
document.addEventListener('DOMContentLoaded', function() {
    // Arıza çözme formu
    const resolveForm = document.getElementById('resolveFaultForm');
    if (resolveForm) {
        resolveForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Arıza başarıyla çözüldü!');
                    location.reload();
                } else {
                    alert('Hata: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('İşlem sırasında hata oluştu.');
            });
        });
    }
    
    // Durum güncelleme formu
    const updateStatusForm = document.getElementById('updateStatusForm');
    if (updateStatusForm) {
        updateStatusForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'PATCH',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Durum başarıyla güncellendi!');
                    location.reload();
                } else {
                    alert('Hata: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('İşlem sırasında hata oluştu.');
            });
        });
    }
    
    // Bakım tamamlandı formu
    const maintenanceForm = document.getElementById('maintenanceCompleteForm');
    if (maintenanceForm) {
        maintenanceForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Bakım başarıyla tamamlandı!');
                    location.reload();
                } else {
                    alert('Hata: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('İşlem sırasında hata oluştu.');
            });
        });
    }
    
    // Arıza giderildi formu
    const faultFixedForm = document.getElementById('faultFixedForm');
    if (faultFixedForm) {
        faultFixedForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Arıza başarıyla giderildi!');
                    location.reload();
                } else {
                    alert('Hata: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('İşlem sırasında hata oluştu.');
            });
        });
    }
});
