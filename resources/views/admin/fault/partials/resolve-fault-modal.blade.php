<!-- Arıza Çözme Modalı -->
<div class="modal fade" id="resolveFaultModal" tabindex="-1" aria-labelledby="resolveFaultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="resolveFaultModalLabel">
                    <i class="fas fa-check-circle me-2"></i>Arıza Çözüldü
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <form id="resolveFaultForm" action="{{ route('admin.fault.resolve', ':id') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="fault_id" id="resolveFaultId">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="resolution_note" class="form-label">Çözüm Açıklaması <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="resolution_note" name="resolution_note" rows="4" required 
                                          placeholder="Arızanın nasıl çözüldüğünü detaylı olarak açıklayın..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="resolved_photo" class="form-label">Çözüm Sonrası Fotoğraf</label>
                                <input type="file" class="form-control" id="resolved_photo" name="resolved_photo" accept="image/*">
                                <small class="text-muted">Ekipmanın çözüm sonrası durumunu gösteren fotoğraf</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="resolution_cost" class="form-label">Çözüm Maliyeti (₺)</label>
                                <input type="number" class="form-control" id="resolution_cost" name="resolution_cost" 
                                       step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="resolution_time" class="form-label">Çözüm Süresi (Saat)</label>
                                <input type="number" class="form-control" id="resolution_time" name="resolution_time" 
                                       step="0.5" min="0" placeholder="0.0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="next_maintenance_date" class="form-label">Sonraki Bakım Tarihi</label>
                        <input type="date" class="form-control" id="next_maintenance_date" name="next_maintenance_date">
                        <small class="text-muted">Ekipman için bir sonraki bakım tarihini belirtin</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="submit" form="resolveFaultForm" class="btn btn-success">
                    <i class="fas fa-save me-2"></i>Kaydet
                </button>
            </div>
        </div>
    </div>
</div>
