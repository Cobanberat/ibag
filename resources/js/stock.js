        // Demo ürün verisi
        let products = [
            {id:1, name:'Klavye', category:'Donanım', quantity:12, critical:5, desc:'Son bakımda tuşlar değiştirildi.', history:'2024-03-01 giriş, 2024-03-10 çıkış, 2024-03-15 bakım', status:'Yeterli', photo:'', log:[{date:'2024-03-01', type:'Giriş', amount:12, desc:'İlk stok'}]},
            {id:2, name:'Ethernet Kablosu', category:'Ağ', quantity:3, critical:5, desc:'Kablo başı değiştirildi.', history:'2024-02-20 giriş, 2024-03-05 çıkış', status:'Az Stok', photo:'', log:[{date:'2024-02-20', type:'Giriş', amount:5, desc:'Depo yenileme'},{date:'2024-03-05', type:'Çıkış', amount:2, desc:'Kullanım'}]},
            {id:3, name:'Monitör', category:'Donanım', quantity:0, critical:2, desc:'Panel arızası nedeniyle stokta yok.', history:'2024-01-10 giriş, 2024-02-01 çıkış', status:'Tükendi', photo:'', log:[{date:'2024-01-10', type:'Giriş', amount:2, desc:'Yeni monitör'},{date:'2024-02-01', type:'Çıkış', amount:2, desc:'Arıza'}]}
        ];
        let perPage = 5;
        let currentPage = 1;
        function getStatusClass(status) {
            if(status==='Yeterli') return 'table-success';
            if(status==='Az Stok') return 'table-warning';
            if(status==='Tükendi') return 'table-danger';
            return '';
        }
        function getBadge(status) {
            if(status==='Yeterli') return '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Yeterli</span>';
            if(status==='Az Stok') return '<span class="badge bg-warning"><i class="fas fa-exclamation-triangle"></i> Az Stok</span>';
            if(status==='Tükendi') return '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Tükendi</span>';
            return '';
        }
        function renderTable() {
            let filtered = products.filter(p => {
                let cat = document.getElementById('filterCategory').value;
                let stat = document.getElementById('filterStatus').value;
                let search = document.getElementById('filterSearch').value.toLowerCase();
                return (!cat || p.category===cat) && (!stat || p.status===stat) && (!search || p.name.toLowerCase().includes(search));
            });
            let total = filtered.length;
            let start = (currentPage-1)*perPage;
            let end = start+perPage;
            let pageData = filtered.slice(start,end);
            let tbody = '';
            pageData.forEach((p,i) => {
                let rowId = 'detail'+p.id;
                tbody += `<tr class="${getStatusClass(p.status)}">
                    <td><input type="checkbox" name="select[]" data-id="${p.id}"></td>
                    <td contenteditable="true">
                        ${p.photo ? `<img src="${p.photo}" class="product-photo-thumb" alt="Foto">` : ''}
                        <span class="fw-bold">${p.name}</span>
                    </td>
                    <td>${p.category}</td>
                    <td contenteditable="true">${p.quantity}</td>
                    <td contenteditable="true">${p.critical}</td>
                    <td><div class="progress" style="height: 10px;"><div class="progress-bar bg-${p.status==='Yeterli'?'success':p.status==='Az Stok'?'warning':'danger'}" style="width: ${Math.min(100,Math.round((p.quantity/(p.critical||1))*100))}%"></div></div></td>
                    <td>${getBadge(p.status)}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-success pt-2 pb-2 stockInBtn" data-id="${p.id}" data-type="Giriş"><i class="fas fa-plus"></i></button>
                        <button type="button" class="btn btn-sm btn-danger pt-2 pb-2 stockOutBtn" data-id="${p.id}" data-type="Çıkış"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-sm btn-secondary pt-2 pb-2 logBtn" data-id="${p.id}"><i class="fas fa-history"></i></button>
                        <button type="button" class="btn btn-sm btn-info photoBtn pt-2 pb-2" data-id="${p.id}"><i class="fas fa-image"></i></button>
                        <button type="button" class="btn btn-sm btn-warning editBtn" data-id="${p.id}">Düzenle</button>
                        <button type="button" class="btn btn-sm btn-danger deleteBtn" data-id="${p.id}">Sil</button>
                    </td>
                </tr>
                <tr class="collapse bg-light" id="${rowId}">
                    <td colspan="8">
                        <strong>Ürün Geçmişi:</strong> ${p.history || '-'}<br>
                        <strong>Ek Açıklama:</strong> ${p.desc || '-'}
                    </td>
                </tr>`;
            });
            var tbodyEl = document.getElementById('stockTableBody');
            if (tbodyEl) tbodyEl.innerHTML = tbody;
            renderPagination(total);
        }
        function renderPagination(total) {
            let pageCount = Math.ceil(total/perPage);
            let pag = '';
            for(let i=1;i<=pageCount;i++) {
                pag += `<li class="page-item${i===currentPage?' active':''}"><a class="page-link" href="#" onclick="gotoPage(${i});return false;">${i}</a></li>`;
            }
            var pagEl = document.getElementById('pagination');
            if (pagEl) pagEl.innerHTML = pag;
        }
        window.gotoPage = function(page) { currentPage=page; renderTable(); }
        var filterBtn = document.getElementById('filterBtn');
        if (filterBtn) filterBtn.onclick = function(e){ e.preventDefault(); currentPage=1; renderTable(); };
        var filterCategory = document.getElementById('filterCategory');
        var filterStatus = document.getElementById('filterStatus');
        if (filterCategory) filterCategory.onchange = function(){ currentPage=1; renderTable(); };
        if (filterStatus) filterStatus.onchange = function(){ currentPage=1; renderTable(); };
        var filterSearch = document.getElementById('filterSearch');
        if (filterSearch) filterSearch.oninput = function(){ currentPage=1; renderTable(); };
        var addProductForm = document.getElementById('addProductForm');
        if (addProductForm) {
            addProductForm.onsubmit = function(e){
                e.preventDefault();
                let f = e.target;
                let newId = products.length ? Math.max(...products.map(p=>p.id))+1 : 1;
                let photo = '';
                if(f.photo && f.photo.files && f.photo.files[0]) {
                    photo = URL.createObjectURL(f.photo.files[0]);
                }
                products.push({
                    id: newId,
                    name: f.name.value,
                    category: f.category.value,
                    quantity: parseInt(f.quantity.value),
                    critical: parseInt(f.critical.value),
                    desc: f.desc.value,
                    history: '-',
                    status: (parseInt(f.quantity.value)===0?'Tükendi':parseInt(f.quantity.value)<=parseInt(f.critical.value)?'Az Stok':'Yeterli'),
                    photo: photo,
                    log: []
                });
                f.reset();
                var modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
                if (modal) modal.hide();
                renderTable();
            };
        }
        var stockTableBody = document.getElementById('stockTableBody');
        if (stockTableBody) {
            stockTableBody.onclick = function(e) {
                const btn = e.target.closest('button');
                if (!btn) return;
                let id = btn.getAttribute('data-id');
                if(btn.classList.contains('deleteBtn')) {
                    products = products.filter(p=>p.id!=id);
                    renderTable();
                }
                if(btn.classList.contains('stockInBtn') || btn.classList.contains('stockOutBtn')) {
                    let type = btn.getAttribute('data-type');
                    let product = products.find(p=>p.id==id);
                    var label = document.getElementById('stockInOutModalLabel');
                    if (label) label.innerText = type === 'Giriş' ? 'Stok Girişi' : 'Stok Çıkışı';
                    let form = document.getElementById('stockInOutForm');
                    if (form && product) {
                        form.productId.value = id;
                        form.type.value = type;
                        form.amount.value = '';
                        form.desc.value = '';
                        new bootstrap.Modal(document.getElementById('stockInOutModal')).show();
                    }
                }
                if(btn.classList.contains('logBtn')) {
                    let product = products.find(p=>p.id==id);
                    let logBody = '';
                    (product.log||[]).forEach(l => {
                        logBody += `<tr><td>${l.date}</td><td>${l.type}</td><td>${l.amount}</td><td>${l.desc||''}</td></tr>`;
                    });
                    var logTableBody = document.getElementById('logTableBody');
                    if (logTableBody) logTableBody.innerHTML = logBody || '<tr><td colspan="4" class="text-center">Hareket yok</td></tr>';
                    new bootstrap.Modal(document.getElementById('logModal')).show();
                }
                if(btn.classList.contains('photoBtn')) {
                    let product = products.find(p=>p.id==id);
                    var modalPhoto = document.getElementById('modalPhoto');
                    if (modalPhoto) modalPhoto.src = product.photo || '';
                    new bootstrap.Modal(document.getElementById('photoModal')).show();
                }
                if(btn.classList.contains('editBtn')) {
                    // (Düzenle işlemi için kod eklenebilir)
                }
            };
        }
        var stockInOutForm = document.getElementById('stockInOutForm');
        if (stockInOutForm) {
            stockInOutForm.onsubmit = function(e) {
                e.preventDefault();
                let f = e.target;
                let product = products.find(p=>p.id==f.productId.value);
                let amount = parseInt(f.amount.value);
                let type = f.type.value;
                if(type==='Giriş') product.quantity += amount;
                if(type==='Çıkış') product.quantity = Math.max(0, product.quantity-amount);
                product.status = (product.quantity===0?'Tükendi':product.quantity<=product.critical?'Az Stok':'Yeterli');
                product.log = product.log || [];
                product.log.unshift({date:f.date.value, type:type, amount:amount, desc:f.desc.value});
                var modal = bootstrap.Modal.getInstance(document.getElementById('stockInOutModal'));
                if (modal) modal.hide();
                renderTable();
            };
        }
        var deleteSelected = document.getElementById('deleteSelected');
        if (deleteSelected) {
            deleteSelected.onclick = function(e) {
                e.preventDefault();
                let checked = Array.from(document.querySelectorAll('input[name="select[]"]:checked')).map(cb=>parseInt(cb.getAttribute('data-id')));
                products = products.filter(p=>!checked.includes(p.id));
                renderTable();
            };
        }
        var selectAll = document.getElementById('selectAll');
        if (selectAll) {
            selectAll.onchange = function() {
                document.querySelectorAll('input[name="select[]"]').forEach(cb => cb.checked = this.checked);
            }
        }
        // Toplu Kategori Değiştir
        var bulkCategoryBtn = document.getElementById('bulkCategoryBtn');
        if (bulkCategoryBtn) {
            bulkCategoryBtn.onclick = function(){
                new bootstrap.Modal(document.getElementById('bulkCategoryModal')).show();
            };
        }
        var bulkCategoryForm = document.getElementById('bulkCategoryForm');
        if (bulkCategoryForm) {
            bulkCategoryForm.onsubmit = function(e){
                e.preventDefault();
                let newCat = e.target.newCategory.value;
                let checked = Array.from(document.querySelectorAll('input[name="select[]"]:checked')).map(cb=>parseInt(cb.getAttribute('data-id')));
                products.forEach(p=>{if(checked.includes(p.id))p.category=newCat;});
                var modal = bootstrap.Modal.getInstance(document.getElementById('bulkCategoryModal'));
                if (modal) modal.hide();
                renderTable();
            };
        }
        // Seçili ekipmanlara işlem yap butonu aktif/pasif
        function updateBulkActionBtn() {
            const anyChecked = document.querySelectorAll('input[name="select[]"]:checked').length > 0;
            var bulkActionBtn = document.getElementById('bulkActionBtn');
            if (bulkActionBtn) bulkActionBtn.disabled = !anyChecked;
        }
        var stockTableBody2 = document.getElementById('stockTableBody');
        if (stockTableBody2) stockTableBody2.addEventListener('change', updateBulkActionBtn);
        if (selectAll) selectAll.addEventListener('change', updateBulkActionBtn);
        renderTable();