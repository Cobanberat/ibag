// Equipment Status sayfası için JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Filtreleme için event listener'ları ekle
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const filterBtn = document.getElementById('filterBtn');
    const clearFiltersBtn = document.getElementById('clearFilters');

    // Arama filtresi
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            if (this.value.trim() === '') {
                filterEquipment();
            }
        });
    }

    // Kategori filtresi
    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterEquipment);
    }

    // Durum filtresi
    if (statusFilter) {
        statusFilter.addEventListener('change', filterEquipment);
    }

    // Filtrele butonu
    if (filterBtn) {
        filterBtn.addEventListener('click', filterEquipment);
    }

    // Filtreleri temizle butonu
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (categoryFilter) categoryFilter.value = '';
            if (statusFilter) statusFilter.value = '';
            filterEquipment();
        });
    }

    // Ekipman filtreleme fonksiyonu
    function filterEquipment() {
        const searchValue = searchInput ? searchInput.value.toLowerCase() : '';
        const categoryValue = categoryFilter ? categoryFilter.value : '';
        const statusValue = statusFilter ? statusFilter.value : '';

        // URL parametrelerini oluştur
        const params = new URLSearchParams();
        if (searchValue) params.append('search', searchValue);
        if (categoryValue) params.append('category', categoryValue);
        if (statusValue) params.append('status', statusValue);

        // Sayfayı yeniden yükle
        const currentUrl = new URL(window.location);
        currentUrl.search = params.toString();
        window.location.href = currentUrl.toString();
    }

    // Detay görüntüleme butonları
    document.addEventListener('click', function(e) {
        if (e.target.closest('.detay-gor-btn')) {
            const btn = e.target.closest('.detay-gor-btn');
            const equipmentId = btn.getAttribute('data-eid');
            showEquipmentDetail(equipmentId);
        }
    });

    // Arıza giderildi butonları
    document.addEventListener('click', function(e) {
        if (e.target.closest('.ariza-giderildi-btn')) {
            const btn = e.target.closest('.ariza-giderildi-btn');
            const equipmentId = btn.getAttribute('data-eid');
            const equipmentName = btn.getAttribute('data-equipment');
            showArizaGiderildiModal(equipmentId, equipmentName);
        }
    });

    // Bakım giderildi butonları
    document.addEventListener('click', function(e) {
        if (e.target.closest('.bakim-giderildi-btn')) {
            const btn = e.target.closest('.bakim-giderildi-btn');
            const equipmentId = btn.getAttribute('data-eid');
            const equipmentName = btn.getAttribute('data-equipment');
            showBakimGiderildiModal(equipmentId, equipmentName);
        }
    });

    // Bakım ekipmanları modal butonu
    const bakimEkipmanModalBtn = document.getElementById('bakimEkipmanModalBtn');
    if (bakimEkipmanModalBtn) {
        bakimEkipmanModalBtn.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('bakimEkipmanModal'));
            modal.show();
        });
    }

    // Ekipman detayını göster
    function showEquipmentDetail(equipmentId) {
        const modal = new bootstrap.Modal(document.getElementById('detayModal'));
        const modalBody = document.getElementById('detayModalBody');
        const modalHeader = document.getElementById('detayModalHeader');
        const modalTitle = document.getElementById('detayModalLabel');

        // Loading göster
        modalBody.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                <p class="text-muted mt-2">Detaylar yükleniyor...</p>
            </div>
        `;

        modal.show();

        // AJAX ile ekipman detayını çek
        fetch(`/admin/stock/${equipmentId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const stock = data.data;
                    const status = stock.status;
                    
                    // Modal header'ı güncelle
                    if (status && (status.toLowerCase().includes('arıza') || status.toLowerCase().includes('arızalı'))) {
                        modalHeader.className = 'modal-header bg-danger bg-opacity-25';
                        modalTitle.innerHTML = '<i class="fas fa-exclamation-circle text-danger me-2"></i>Arızalı Ekipman Detayları';
                    } else if (status && status.toLowerCase().includes('bakım')) {
                        modalHeader.className = 'modal-header bg-warning bg-opacity-25';
                        modalTitle.innerHTML = '<i class="fas fa-tools text-warning me-2"></i>Bakım Gerektiren Ekipman Detayları';
                    } else {
                        modalHeader.className = 'modal-header bg-info bg-opacity-25';
                        modalTitle.innerHTML = '<i class="fas fa-info-circle text-info me-2"></i>Ekipman Detayları';
                    }

                    // Modal içeriğini doldur
                    modalBody.innerHTML = `
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="fw-bold">Ekipman Adı:</label>
                                <p>${stock.equipment?.name || 'Bilinmeyen'}</p>
                                <div class="mb-2">
                                    ${stock.equipment?.category ? `<span class="badge bg-info text-dark me-1">${stock.equipment.category.name}</span>` : ''}
                                    ${stock.equipment?.individual_tracking ? '<span class="badge bg-primary">Ayrı Takip</span>' : ''}
                                </div>
                                <label class="fw-bold">Durumu:</label>
                                <p>
                                    ${status && status.toLowerCase().includes('arıza') ? 
                                        '<span class="badge bg-danger">Arızalı</span>' : 
                                        status && status.toLowerCase().includes('bakım') ? 
                                        '<span class="badge bg-warning text-dark">Bakım Gerekiyor</span>' : 
                                        `<span class="badge bg-secondary">${status || 'Bilinmiyor'}</span>`
                                    }
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-bold">Kod:</label>
                                <p>${stock.code || '-'}</p>
                                <label class="fw-bold">Marka:</label>
                                <p>${stock.brand || '-'}</p>
                                <label class="fw-bold">Model:</label>
                                <p>${stock.model || '-'}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-bold">Miktar:</label>
                                <p>${stock.quantity || 0}</p>
                                <label class="fw-bold">Oluşturulma:</label>
                                <p>${stock.created_at ? new Date(stock.created_at).toLocaleDateString('tr-TR') : '-'}</p>
                                <label class="fw-bold">Güncellenme:</label>
                                <p>${stock.updated_at ? new Date(stock.updated_at).toLocaleDateString('tr-TR') : '-'}</p>
                            </div>
                        </div>
                        ${stock.note ? `
                        <div class="mb-3">
                            <label class="fw-bold">Not:</label>
                            <div class="border rounded p-2 bg-light fst-italic">
                                ${stock.note}
                            </div>
                        </div>
                        ` : ''}
                        ${stock.equipment?.images && stock.equipment.images.length > 0 ? `
                        <div class="mb-3">
                            <label class="fw-bold">Ekipman Fotoğrafı:</label><br>
                            <img src="/storage/${stock.equipment.images[0].path}" alt="Ekipman Fotoğrafı" class="img-fluid rounded border shadow-sm mt-1" style="max-width: 300px;">
                        </div>
                        ` : ''}
                    `;
                } else {
                    modalBody.innerHTML = `
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                            <p class="text-warning">Ekipman detayı yüklenirken hata oluştu</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Detay yükleme hatası:', error);
                modalBody.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                        <p class="text-warning">Ekipman detayı yüklenirken hata oluştu</p>
                    </div>
                `;
            });
    }

    // Arıza giderildi modal'ını göster
    function showArizaGiderildiModal(equipmentId, equipmentName) {
        const modal = new bootstrap.Modal(document.getElementById('arizaGiderildiModal'));
        const form = document.getElementById('arizaGiderildiForm');
        const equipmentStockIdInput = document.getElementById('arizaEquipmentStockId');
        
        // Form action'ını güncelle
        form.action = form.action.replace('PLACEHOLDER', equipmentId);
        equipmentStockIdInput.value = equipmentId;
        
        // Bugünün tarihini set et
        document.getElementById('giderilmeTarihi').value = new Date().toISOString().split('T')[0];
        
        modal.show();
    }

    // Bakım giderildi modal'ını göster
    function showBakimGiderildiModal(equipmentId, equipmentName) {
        const modal = new bootstrap.Modal(document.getElementById('bakimGiderildiModal'));
        const form = document.getElementById('bakimGiderildiForm');
        const equipmentStockIdInput = document.getElementById('bakimEquipmentStockId');
        
        // Form action'ını güncelle
        form.action = form.action.replace('PLACEHOLDER', equipmentId);
        equipmentStockIdInput.value = equipmentId;
        
        // Bugünün tarihini set et
        document.getElementById('bakimGiderilmeTarihi').value = new Date().toISOString().split('T')[0];
        
        modal.show();
    }

    // Form submit işlemleri
    const arizaGiderildiForm = document.getElementById('arizaGiderildiForm');
    if (arizaGiderildiForm) {
        arizaGiderildiForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // Form işlemi burada yapılacak
            alert('Arıza giderildi olarak işaretlendi!');
            bootstrap.Modal.getInstance(document.getElementById('arizaGiderildiModal')).hide();
        });
    }

    const talepOlusturForm = document.getElementById('talepOlusturForm');
    if (talepOlusturForm) {
        talepOlusturForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // Form işlemi burada yapılacak
            alert('Talep oluşturuldu!');
            bootstrap.Modal.getInstance(document.getElementById('talepOlusturModal')).hide();
        });
    }
});
