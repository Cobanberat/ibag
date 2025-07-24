// Demo kategori verisi
let categories = [
    { id: 1, name: 'Elektronik', desc: 'Elektronik cihazlar', color: '#0d6efd', icon: 'fa-laptop', productCount: 12, date: '2024-03-20', products: ['Laptop', 'Monitör', 'Klavye', 'Mouse'] },
    { id: 2, name: 'Donanım', desc: 'Donanım ekipmanları', color: '#ffc107', icon: 'fa-tools', productCount: 7, date: '2024-03-18', products: ['Kasa', 'Güç Kaynağı', 'Fan'] },
    { id: 3, name: 'Aksesuar', desc: 'Çeşitli aksesuarlar', color: '#36b3f6', icon: 'fa-star', productCount: 5, date: '2024-03-15', products: ['Kılıf', 'Çanta'] }
];
let perPage = 5;
let currentPage = 1;
function renderTable() {
    let search = document.getElementById('categorySearch').value.toLowerCase();
    let sort = document.getElementById('sortSelect').value;
    let filtered = categories.filter(c => !search || c.name.toLowerCase().includes(search));
    if (sort === 'most') filtered.sort((a, b) => b.productCount - a.productCount);
    if (sort === 'least') filtered.sort((a, b) => a.productCount - b.productCount);
    if (sort === 'newest') filtered.sort((a, b) => b.date.localeCompare(a.date));
    if (sort === 'oldest') filtered.sort((a, b) => a.date.localeCompare(b.date));
    let total = filtered.length;
    let start = (currentPage - 1) * perPage;
    let end = start + perPage;
    let pageData = filtered.slice(start, end);
    let tbody = '';
    pageData.forEach(c => {
        tbody += `<tr>
            <td class="table-success"><input type="checkbox" class="rowCheck" data-id="${c.id}"></td>
            <td class="table-success"><span class="category-color" style="background:${c.color}"></span> <i class="fas ${c.icon} me-1 text-primary"></i> <span class="fw-bold">${c.name}</span></td>
            <td class="table-success">${c.desc || ''}</td>
            <td class="table-success">${c.productCount}</td>
            <td class="table-success">${c.date}</td>
            <td class="table-success"><span class="category-color" style="background:${c.color}"></span></td>
            <td class="category-actions table-success">
                <button type="button" class="btn btn-sm btn-info detailBtn   pt-2 pb-2" data-id="${c.id}"><i class="fas fa-info-circle"></i></button>
                <button type="button" class="btn btn-sm btn-warning editBtn  pt-2 pb-2" data-id="${c.id}"><i class="fas fa-edit"></i></button>
                <button type="button" class="btn btn-sm btn-danger deleteBtn pt-2 pb-2" data-id="${c.id}"><i class="fas fa-trash"></i></button>
            </td>
        </tr>`;
    });
    document.getElementById('categoryTableBody').innerHTML = tbody;
    renderPagination(total);
}
function renderPagination(total) {
    let pageCount = Math.ceil(total / perPage);
    let pag = '';
    for (let i = 1; i <= pageCount; i++) {
        pag += `<li class="page-item${i === currentPage ? ' active' : ''}"><a class="page-link py-2 p-3" href="#" onclick="gotoPage(${i});return false;">${i}</a></li>`;
    }
    document.getElementById('pagination').innerHTML = pag;
}
window.gotoPage = function (page) { currentPage = page; renderTable(); }
document.getElementById('filterBtn').onclick = function (e) { e.preventDefault(); currentPage = 1; renderTable(); };
document.getElementById('sortSelect').onchange = function () { currentPage = 1; renderTable(); };
document.getElementById('categorySearch').oninput = function () { currentPage = 1; renderTable(); };
document.getElementById('selectAll').onchange = function () {
    document.querySelectorAll('.rowCheck').forEach(cb => cb.checked = this.checked);
};
document.getElementById('deleteSelected').onclick = function (e) {
    e.preventDefault();
    let checked = Array.from(document.querySelectorAll('.rowCheck:checked')).map(cb => parseInt(cb.getAttribute('data-id')));
    categories = categories.filter(c => !checked.includes(c.id));
    renderTable();
};
document.getElementById('categoryTableBody').onclick = function (e) {
    const btn = e.target.closest('button');
    if (!btn) return;
    let id = parseInt(btn.getAttribute('data-id'));
    if (btn.classList.contains('editBtn')) {
        let cat = categories.find(c => c.id === id);
        document.getElementById('editCategoryId').value = cat.id;
        document.getElementById('editCategoryName').value = cat.name;
        document.getElementById('editCategoryDesc').value = cat.desc;
        document.getElementById('editCategoryColor').value = cat.color;
        document.getElementById('editCategoryIcon').value = cat.icon;
        new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
    }
    if (btn.classList.contains('deleteBtn')) {
        categories = categories.filter(c => c.id !== id);
        renderTable();
    }
    if (btn.classList.contains('detailBtn')) {
        let cat = categories.find(c => c.id === id);
        let html = `<div class='mb-2'><span class='category-color' style='background:${cat.color}'></span> <i class='fas ${cat.icon} me-1 text-primary'></i> <span class='fw-bold'>${cat.name}</span></div>`;
        html += `<div class='mb-2'><strong>Açıklama:</strong> ${cat.desc || '-'}</div>`;
        html += `<div class='mb-2'><strong>Ürün Sayısı:</strong> ${cat.productCount}</div>`;
        html += `<div class='mb-2'><strong>Eklenme Tarihi:</strong> ${cat.date}</div>`;
        html += `<div class='mb-2'><strong>Ürünler:</strong> ${(cat.products || []).join(', ') || '-'}</div>`;
        document.getElementById('categoryDetailContent').innerHTML = html;
        new bootstrap.Modal(document.getElementById('categoryDetailModal')).show();
    }
};
document.getElementById('saveCategoryBtn').onclick = function () {
    let name = document.getElementById('categoryName').value.trim();
    let desc = document.getElementById('categoryDesc').value.trim();
    let color = document.getElementById('categoryColor').value;
    let icon = document.getElementById('categoryIcon').value;
    if (!name) return alert('Kategori adı zorunlu!');
    let newId = categories.length ? Math.max(...categories.map(c => c.id)) + 1 : 1;
    categories.push({ id: newId, name, desc, color, icon, productCount: 0, date: new Date().toISOString().slice(0, 10), products: [] });
    document.getElementById('addCategoryForm').reset();
    bootstrap.Modal.getInstance(document.getElementById('addCategoryModal')).hide();
    renderTable();
};
document.getElementById('updateCategoryBtn').onclick = function () {
    let id = parseInt(document.getElementById('editCategoryId').value);
    let name = document.getElementById('editCategoryName').value.trim();
    let desc = document.getElementById('editCategoryDesc').value.trim();
    let color = document.getElementById('editCategoryColor').value;
    let icon = document.getElementById('editCategoryIcon').value;
    let cat = categories.find(c => c.id === id);
    if (cat) {
        cat.name = name;
        cat.desc = desc;
        cat.color = color;
        cat.icon = icon;
    }
    bootstrap.Modal.getInstance(document.getElementById('editCategoryModal')).hide();
    renderTable();
};
renderTable();