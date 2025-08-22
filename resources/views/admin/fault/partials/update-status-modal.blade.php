<!-- Durum Güncelleme Modalı -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="updateStatusModalLabel">
                    <i class="fas fa-edit me-2"></i>Durum Güncelle
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm" action="{{ route('admin.fault.updateStatus', ':id') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="fault_id" id="updateStatusFaultId">
                    
                    <div class="mb-3">
                        <label for="new_status" class="form-label">Yeni Durum <span class="text-danger">*</span></label>
                        <select class="form-select" id="new_status" name="status" required>
                            <option value="">Durum Seçin</option>
                            <option value="Beklemede">Beklemede</option>
                            <option value="İşlemde">İşlemde</option>
                            <option value="Çözüldü">Çözüldü</option>
                            <option value="İptal Edildi">İptal Edildi</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status_note" class="form-label">Durum Notu</label>
                        <textarea class="form-control" id="status_note" name="status_note" rows="3" 
                                  placeholder="Durum değişikliği hakkında not ekleyin..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="submit" form="updateStatusForm" class="btn btn-warning">
                    <i class="fas fa-save me-2"></i>Güncelle
                </button>
            </div>
        </div>
    </div>
</div>
