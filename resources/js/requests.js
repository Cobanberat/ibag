// Filtreleme fonksiyonu
function filterRequestsTable() {
    const search = document.getElementById('requestSearch').value.trim().toLowerCase();
    const type = document.getElementById('requestTypeFilter').value;
    const status = document.getElementById('requestStatusFilter').value;
    const rows = document.querySelectorAll('#requestsTableBody tr');
    rows.forEach(tr => {
        const tds = tr.querySelectorAll('td');
        const ekipman = tds[1].innerText.toLowerCase();
        const talepTipi = tds[2].innerText.trim();
        const aciklama = tds[3].innerText.toLowerCase();
        const durum = tds[4].innerText.trim();
        let show = true;
        if (search && !(ekipman.includes(search) || aciklama.includes(search))) show = false;
        if (type && talepTipi !== type) show = false;
        if (status && durum !== status) show = false;
        tr.style.display = show ? '' : 'none';
    });
}
document.getElementById('requestSearch').addEventListener('input', filterRequestsTable);
document.getElementById('requestTypeFilter').addEventListener('change', filterRequestsTable);
document.getElementById('requestStatusFilter').addEventListener('change', filterRequestsTable);
document.getElementById('filterBtn').addEventListener('click', function(e) {
    e.preventDefault();
    filterRequestsTable();
});
// Detay modalı işlevi
const detailBtns = document.querySelectorAll('.request-detail-btn');
detailBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        const tr = btn.closest('tr');
        const tds = tr.querySelectorAll('td');
        let html = '';
        html += `<div class='mb-2'><span class='fw-bold'>Ekipman:</span> ${tds[1].innerText}</div>`;
        html += `<div class='mb-2'><span class='fw-bold'>Talep Tipi:</span> ${tds[2].innerHTML}</div>`;
        html += `<div class='mb-2'><span class='fw-bold'>Açıklama:</span> ${tds[3].innerText}</div>`;
        html += `<div class='mb-2'><span class='fw-bold'>Durum:</span> ${tds[4].innerHTML}</div>`;
        html += `<div class='mb-2'><span class='fw-bold'>Tarih:</span> ${tds[5].innerText}</div>`;
        document.getElementById('requestDetailBody').innerHTML = html;
        var modal = new bootstrap.Modal(document.getElementById('requestDetailModal'));
        modal.show();
    });
});
// Yeni talep ekleme formu submit (şimdilik sadece modalı kapat)
const addForm = document.querySelector('#addRequestModal form');
if(addForm) {
    addForm.onsubmit = function(e) {
        e.preventDefault();
        var modal = bootstrap.Modal.getInstance(document.getElementById('addRequestModal'));
        if(modal) modal.hide();
        // Burada backend'e gönderme işlemi eklenebilir
    };
}
