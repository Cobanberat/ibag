<!-- Bakım Tamamlandı Modalı -->
<div class="modal fade" id="maintenanceCompleteModal" tabindex="-1" aria-labelledby="maintenanceCompleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="maintenanceCompleteModalLabel">
                    <i class="fas fa-tools me-2"></i>Bakım Tamamlandı
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <form id="maintenanceCompleteForm" action="{{ route('admin.fault.resolve') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="equipment_stock_id" id="maintenanceEquipmentId">
                    <input type="hidden" name="type" value="maintenance">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="maintenance_note" class="form-label">Bakım Açıklaması <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="maintenance_note" name="resolution_note" rows="4" required 
                                          placeholder="Yapılan bakım işlemlerini detaylı olarak açıklayın..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="maintenance_photo" class="form-label">Bakım Sonrası Fotoğraf</label>
                                <input type="file" class="form-control" id="maintenance_photo" name="resolved_photo" accept="image/*">
                                <small class="text-muted">Ekipmanın bakım sonrası durumunu gösteren fotoğraf</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="maintenance_cost" class="form-label">Bakım Maliyeti (₺)</label>
                                <input type="number" class="form-control" id="maintenance_cost" name="resolution_cost" 
                                       step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="maintenance_time" class="form-label">Bakım Süresi (Saat)</label>
                                <input type="number" class="form-control" id="maintenance_time" name="resolution_time" 
                                       step="0.5" min="0" placeholder="0.0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="next_maintenance_date" class="form-label">Sonraki Bakım Tarihi <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="next_maintenance_date" name="next_maintenance_date" required>
                        <small class="text-muted">Ekipman için bir sonraki bakım tarihini belirtin</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="submit" form="maintenanceCompleteForm" class="btn btn-success">
                    <i class="fas fa-save me-2"></i>Kaydet
                </button>
            </div>
        </div>
    </div>
</div>
