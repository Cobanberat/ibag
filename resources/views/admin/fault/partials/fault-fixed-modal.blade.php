<!-- Arıza Giderildi Modalı -->
<div class="modal fade" id="faultFixedModal" tabindex="-1" aria-labelledby="faultFixedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="faultFixedModalLabel">
                    <i class="fas fa-check-circle me-2"></i>Arıza Giderildi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <form id="faultFixedForm" action="{{ route('admin.fault.resolve') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="equipment_stock_id" id="faultFixedEquipmentId">
                    <input type="hidden" name="type" value="fault">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fault_fix_note" class="form-label">Arıza Giderme Açıklaması <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="fault_fix_note" name="resolution_note" rows="4" required 
                                          placeholder="Arızanın nasıl giderildiğini detaylı olarak açıklayın..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fault_fix_photo" class="form-label">Arıza Giderme Sonrası Fotoğraf</label>
                                <input type="file" class="form-control" id="fault_fix_photo" name="resolved_photo" accept="image/*">
                                <small class="text-muted">Ekipmanın arıza giderme sonrası durumunu gösteren fotoğraf</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fault_fix_cost" class="form-label">Giderme Maliyeti (₺)</label>
                                <input type="number" class="form-control" id="fault_fix_cost" name="resolution_cost" 
                                       step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fault_fix_time" class="form-label">Giderme Süresi (Saat)</label>
                                <input type="number" class="form-control" id="fault_fix_time" name="resolution_time" 
                                       step="0.5" min="0" placeholder="0.0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="fault_next_maintenance_date" class="form-label">Sonraki Bakım Tarihi</label>
                        <input type="date" class="form-control" id="fault_next_maintenance_date" name="next_maintenance_date">
                        <small class="text-muted">Ekipman için bir sonraki bakım tarihini belirtin (opsiyonel)</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="submit" form="faultFixedForm" class="btn btn-success">
                    <i class="fas fa-save me-2"></i>Kaydet
                </button>
            </div>
        </div>
    </div>
</div>
