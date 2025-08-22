<!-- Arıza Detay Modalı -->
<div class="modal fade" id="faultDetailModal" tabindex="-1" aria-labelledby="faultDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="faultDetailModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>Arıza Detayı
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body" id="faultDetailBody">
                <!-- AJAX ile doldurulacak -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                <button type="button" class="btn btn-success" onclick="showResolveModal(currentFaultId)">
                    <i class="fas fa-check me-2"></i>Çöz
                </button>
            </div>
        </div>
    </div>
</div>
