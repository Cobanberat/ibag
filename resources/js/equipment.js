let equipmentData = [];
  let currentPage = 1;
const pageSize = 15;

// Global fonksiyonları tanımla
window.showDetail = function(id) {
    // AJAX ile ekipman detayını çek
    fetch(`/admin/ekipmanlar/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const stock = data.data;
                document.getElementById('detailSno').innerText = stock.id;
                document.getElementById('detailCode').innerText = stock.code || '-';
                document.getElementById('detailType').innerText = stock.equipment?.name || '-';
                document.getElementById('detailBrand').innerText = stock.brand || '-';
                document.getElementById('detailModel').innerText = stock.model || '-';
                document.getElementById('detailSize').innerText = stock.size || '-';
                document.getElementById('detailFeature').innerText = stock.feature || '-';
                
                // Birim türü bilgisini göster
                if (stock.equipment && stock.equipment.unit_type) {
                    const unitTypes = {
                        'adet': 'Adet',
                        'metre': 'Metre',
                        'kilogram': 'Kilogram',
                        'litre': 'Litre',
                        'paket': 'Paket',
                        'kutu': 'Kutu',
                        'çift': 'Çift',
                        'takım': 'Takım'
                    };
                    document.getElementById('detailUnitType').innerText = unitTypes[stock.equipment.unit_type] || 'Adet';
                } else {
                    document.getElementById('detailUnitType').innerText = 'Adet';
                }
                
                // Individual tracking kontrolü
                if (stock.equipment && stock.equipment.individual_tracking) {
                    document.getElementById('detailCount').innerText = stock.quantity || 0;
                    document.getElementById('detailTrackingType').innerText = 'Ayrı Takip (Her ürün tek adet)';
                } else {
                    document.getElementById('detailCount').innerText = stock.quantity || 0;
                    document.getElementById('detailTrackingType').innerText = 'Toplu Takip (Miktar bazlı)';
                }
                
                document.getElementById('detailStatus').innerText = stock.status || '-';
                document.getElementById('detailLocation').innerText = stock.location || '-';
                document.getElementById('detailDate').innerText = stock.created_at ? new Date(stock.created_at).toLocaleDateString('tr-TR') : '-';
                document.getElementById('detailNote').innerText = stock.note || '-';
                new bootstrap.Modal(document.getElementById('detailModal')).show();
            } else {
                showAlert('Ekipman detayı yüklenirken hata oluştu', 'danger');
            }
        })
        .catch(error => {
            console.error('Detay yükleme hatası:', error);
            showAlert('Ekipman detayı yüklenirken hata oluştu', 'danger');
        });
};

window.deleteEquipment = function(id) {
    Swal.fire({
        title: 'Emin misiniz?',
        text: "Bu ekipmanı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Evet, sil!',
        cancelButtonText: 'İptal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Loading göster
            Swal.fire({
                title: 'Siliniyor...',
                text: 'Ekipman siliniyor, lütfen bekleyin.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/admin/ekipmanlar/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Loading ekranını kapat
                    Swal.close();
                    
                    Swal.fire({
                        title: 'Başarılı!',
                        text: 'Ekipman başarıyla silindi.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Sayfayı yenile
                        window.location.reload();
                    });
                } else {
                    // Loading ekranını kapat
                    Swal.close();
                    
                    Swal.fire({
                        title: 'Hata!',
                        text: 'Ekipman silinirken hata oluştu.',
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                // Loading ekranını kapat
                Swal.close();
                
                console.error('Silme hatası:', error);
                Swal.fire({
                    title: 'Hata!',
                    text: 'Ekipman silinirken hata oluştu.',
                    icon: 'error'
                });
            });
        }
    });
};

// CSRF token'ı al
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

  // Dropdown seçenekleri
  const dropdownOptions = {
    MARKA: ["TOPTICER", "FULL", "POWER FULL", "KAMA"],
    BEDEN: ["Küçük", "Orta", "Büyük"],
    DURUM: ["Sıfır", "Açık"]
  };

  // Aktif düzenleme hücresini takip et
  let activeEditingCell = null;

  // Düzenleme fonksiyonları
  function makeEditable(cell, field, rowIndex) {
      // Eğer başka bir hücre düzenleniyorsa, onu kapat
      if (activeEditingCell && activeEditingCell !== cell) {
          const activeInput = activeEditingCell.querySelector('input, select');
          if (activeInput) {
              const activeField = activeEditingCell.getAttribute('data-field');
              const activeId = activeEditingCell.getAttribute('data-id');
              const originalText = activeEditingCell.getAttribute('data-original-text') || activeInput.value;
              saveEdit(activeEditingCell, activeInput.value, activeField, activeId, originalText);
          }
      }

      const originalText = cell.textContent;
      let input;
      
      // Orijinal metni sakla
      cell.setAttribute('data-original-text', originalText);
      
      // Düzenleme durumunu göster
      cell.classList.add('editing');
      activeEditingCell = cell;
      
      // Alan türüne göre input oluştur
      if (field === 'quantity') {
          // Individual tracking kontrolü - quantity alanı düzenlenebilir mi?
          const row = cell.closest('tr');
          const equipmentId = row.getAttribute('data-id');
          
          // Eğer individual tracking ise quantity düzenlenemez
          if (cell.querySelector('.badge.bg-info')) {
              // Individual tracking - quantity düzenlenemez
              showToast('Ayrı takip özelliği olan ekipmanlarda adet düzenlenemez', 'warning');
              return;
          }
          
          // Number input
          input = document.createElement('input');
          input.type = 'number';
          input.min = '0';
          input.max = '999';
          input.className = 'form-control form-control-sm';
          input.value = originalText;
          input.title = 'Adet giriniz (0-999)';
      } else if (field === 'status') {
          // Select dropdown for status
          input = document.createElement('select');
          input.className = 'form-select form-select-sm';
          input.title = 'Durum seçiniz';
          
          const statusOptions = ['Sıfır', 'Açık'];
          statusOptions.forEach(option => {
              const optionElement = document.createElement('option');
              optionElement.value = option;
              optionElement.textContent = option;
              if (option === originalText) {
                  optionElement.selected = true;
              }
              input.appendChild(optionElement);
          });
      } else {
          // Text input
          input = document.createElement('input');
          input.type = 'text';
          input.className = 'form-control form-control-sm';
          input.value = originalText;
          input.title = `${field} giriniz`;
      }
      
      input.style.width = '100%';
      input.style.minWidth = '80px';
      
      // Input'a focus ol
      cell.innerHTML = '';
      cell.appendChild(input);
      input.focus();
      
      // Text input için select
      if (input.type === 'text' || input.type === 'number') {
          input.select();
      }
      
      // Klavye kısayolları
      input.addEventListener('keydown', function(e) {
          if (e.key === 'Enter') {
              e.preventDefault();
              const id = cell.getAttribute('data-id');
              saveEdit(cell, input.value, field, id, originalText);
          } else if (e.key === 'Escape') {
              e.preventDefault();
              cancelEdit(cell, originalText);
          } else if (e.key === 'Tab') {
              // Tab ile sonraki hücreye geç
              e.preventDefault();
              const nextCell = getNextEditableCell(cell);
              if (nextCell) {
                  const nextField = nextCell.getAttribute('data-field');
                  const nextId = nextCell.getAttribute('data-id');
                  const id = cell.getAttribute('data-id');
                  saveEdit(cell, input.value, field, id, originalText);
                  setTimeout(() => {
                      makeEditable(nextCell, nextField, nextId);
                  }, 100);
              } else {
                  const id = cell.getAttribute('data-id');
                  saveEdit(cell, input.value, field, id, originalText);
              }
          }
      });
      
      // Select için change event
      if (input.tagName === 'SELECT') {
          input.addEventListener('change', function() {
              const id = cell.getAttribute('data-id');
              saveEdit(cell, input.value, field, id, originalText);
          });
      }
      
      // Focus kaybı ile kaydet (sadece text ve number için)
      if (input.type === 'text' || input.type === 'number') {
          input.addEventListener('blur', function() {
              setTimeout(() => {
                  if (cell.contains(input)) {
                      const id = cell.getAttribute('data-id');
                      saveEdit(cell, input.value, field, id, originalText);
                  }
              }, 100);
          });
      }
  }
  
  // Sonraki düzenlenebilir hücreyi bul
  function getNextEditableCell(currentCell) {
      const allCells = Array.from(document.querySelectorAll('.editable-cell'));
      const currentIndex = allCells.indexOf(currentCell);
      return allCells[currentIndex + 1] || null;
  }
  
  function saveEdit(cell, newValue, field, id, originalText) {
      // Düzenleme durumunu kaldır
      cell.classList.remove('editing');
      cell.removeAttribute('data-original-text');
      activeEditingCell = null;
      
      // Validasyon
      if (field === 'quantity') {
          const numValue = parseInt(newValue);
          if (isNaN(numValue) || numValue < 0 || numValue > 999) {
              cancelEdit(cell, originalText);
              return;
          }
          newValue = numValue.toString();
      }
      
      if (newValue.trim() === '') {
          newValue = originalText;
      }
      
      // AJAX ile veritabanına kaydet
      const data = {};
      data[field] = newValue;
      
      fetch(`/admin/ekipmanlar/${id}`, {
          method: 'PUT',
          headers: {
              'X-CSRF-TOKEN': getCsrfToken(),
              'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
      })
      .then(response => response.json())
             .then(result => {
           if (result.success) {
      // Hücreyi güncelle
      cell.textContent = newValue;
      cell.classList.add('saved');
      
      // 2 saniye sonra saved class'ını kaldır
      setTimeout(() => {
          cell.classList.remove('saved');
      }, 2000);
               
               // Toast bildirimi göster
               showToast('Ekipman başarıyla güncellendi', 'success');
           } else {
               cancelEdit(cell, originalText);
               showToast('Güncelleme başarısız', 'error');
           }
       })
       .catch(error => {
           console.error('Güncelleme hatası:', error);
           cancelEdit(cell, originalText);
           showToast('Güncelleme sırasında hata oluştu', 'error');
       });
  }
  
  function cancelEdit(cell, originalText) {
      cell.classList.remove('editing');
      cell.removeAttribute('data-original-text');
      cell.textContent = originalText;
      activeEditingCell = null;
  }
  
  // Tarih validasyonu
  function isValidDate(dateString) {
      const date = new Date(dateString);
      return date instanceof Date && !isNaN(date);
  }
  
  function getFilteredData() {
      return equipmentData.filter(row => {
          let search = document.getElementById('searchInput').value.toLowerCase();
          let type = document.getElementById('typeFilter').value;
          let brand = document.getElementById('brandFilter').value.toLowerCase();
          let status = document.getElementById('statusFilter').value;
          let match = true;
          if(search && !(
              row.URUN_CINSI.toLowerCase().includes(search) ||
              row.MARKA.toLowerCase().includes(search) ||
              row.MODEL.toLowerCase().includes(search) ||
              row.OZELLIK.toLowerCase().includes(search) ||
              row.NOT.toLowerCase().includes(search)
          )) match = false;
          if(type && row.URUN_CINSI !== type) match = false;
          if(brand && !row.MARKA.toLowerCase().includes(brand)) match = false;
          if(status && row.DURUM !== status) match = false;
          return match;
      });
  }

  function renderTable() {
      let tbody = document.querySelector('#equipmentTable tbody');
      tbody.innerHTML = '';
      
      if (equipmentData.length === 0) {
          tbody.innerHTML = '<tr><td colspan="13" class="text-center py-4"><i class="fas fa-inbox fa-2x text-muted mb-2"></i><p class="text-muted">Henüz ekipman bulunmuyor</p></td></tr>';
          return;
      }
      
      // Sayfalama
      const start = (currentPage - 1) * pageSize;
      const end = start + pageSize;
      const pageRows = equipmentData.slice(start, end);
      
      if (pageRows.length === 0) {
          tbody.innerHTML = '<tr><td colspan="13" class="text-center py-4"><i class="fas fa-inbox fa-2x text-muted mb-2"></i><p class="text-muted">Bu sayfada ekipman bulunmuyor</p></td></tr>';
          return;
      }
      
      pageRows.forEach((row, index) => {
          const actualIndex = start + index;
          let tr = document.createElement('tr');
          tr.setAttribute('data-id', row.id);
          tr.innerHTML = `
              <td>${actualIndex + 1}</td>
              <td class="editable-cell" data-field="code" data-id="${row.id}">${row.CODE}</td>
              <td class="editable-cell" data-field="equipment_name" data-id="${row.id}">${row.URUN_CINSI}</td>
              <td class="editable-cell" data-field="brand" data-id="${row.id}">${row.MARKA}</td>
              <td class="editable-cell" data-field="model" data-id="${row.id}">${row.MODEL}</td>
              <td class="editable-cell" data-field="size" data-id="${row.id}">${row.BEDEN}</td>
              <td class="editable-cell" data-field="feature" data-id="${row.id}">${row.OZELLIK}</td>
              <td class="editable-cell" data-field="quantity" data-id="${row.id}">${row.ADET}</td>
              <td class="editable-cell" data-field="status" data-id="${row.id}">${row.DURUM}</td>
              <td class="editable-cell" data-field="location" data-id="${row.id}">-</td>
              <td>${row.TARIH}</td>
              <td class="editable-cell" data-field="note" data-id="${row.id}">${row.NOT}</td>
              <td>
                  <div class="btn-group" role="group">
                      <button type="button" class="btn btn-sm btn-outline-primary" onclick="showDetail(${row.id})">
                          <i class="fas fa-eye"></i>
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteEquipment(${row.id})">
                          <i class="fas fa-trash"></i>
                      </button>
                  </div>
              </td>
          `;
          tbody.appendChild(tr);
      });
      
      // Düzenlenebilir hücrelere çift tıklama event listener'ı ekle
      document.querySelectorAll('.editable-cell').forEach(cell => {
          cell.addEventListener('dblclick', function() {
              const field = this.getAttribute('data-field');
              const id = this.getAttribute('data-id');
              makeEditable(this, field, id);
          });
          
          // Hover efekti
          cell.addEventListener('mouseenter', function() {
              if (!this.querySelector('input') && !this.querySelector('select')) {
                  this.style.backgroundColor = '#f8f9fa';
                  this.style.cursor = 'pointer';
              }
          });
          
          cell.addEventListener('mouseleave', function() {
              if (!this.querySelector('input') && !this.querySelector('select')) {
                  this.style.backgroundColor = '';
              }
          });
      });
      
      // Pagination güncelle
      const totalPages = Math.ceil(equipmentData.length / pageSize);
      renderPagination(totalPages);
  }
  function renderPagination(pageCount) {
      let pag = document.getElementById('pagination');
      pag.innerHTML = '';
      if(pageCount<=1) return;

    // Geri butonu
    let prevLi = document.createElement('li');
    prevLi.className = 'page-item' + (currentPage === 1 ? ' disabled' : '');
    let prevA = document.createElement('a');
    prevA.className = 'page-link';
    prevA.href = '#';
    prevA.innerHTML = '‹';
    prevA.onclick = function(e) { e.preventDefault(); if(currentPage>1){ currentPage--; renderTable(); } };
    prevLi.appendChild(prevA);
    pag.appendChild(prevLi);

    // Sayfa numaraları (maksimum 5 göster)
    let startPage = Math.max(1, currentPage-2);
    let endPage = Math.min(pageCount, currentPage+2);
    if (currentPage <= 3) endPage = Math.min(5, pageCount);
    if (currentPage >= pageCount-2) startPage = Math.max(1, pageCount-4);
    for(let i=startPage;i<=endPage;i++) {
          let li = document.createElement('li');
          li.className = 'page-item'+(i===currentPage?' active':'');
          let a = document.createElement('a');
          a.className = 'page-link';
          a.href = '#';
          a.innerText = i;
          a.onclick = function(e) { e.preventDefault(); currentPage=i; renderTable(); };
          li.appendChild(a);
          pag.appendChild(li);
      }

    // İleri butonu
    let nextLi = document.createElement('li');
    nextLi.className = 'page-item' + (currentPage === pageCount ? ' disabled' : '');
    let nextA = document.createElement('a');
    nextA.className = 'page-link';
    nextA.href = '#';
    nextA.innerHTML = '›';
    nextA.onclick = function(e) { e.preventDefault(); if(currentPage<pageCount){ currentPage++; renderTable(); } };
    nextLi.appendChild(nextA);
    pag.appendChild(nextLi);
    
    // Sayfa bilgisi güncelleme
    const infoText = document.querySelector('.text-muted');
    if (infoText) {
        const startItem = (currentPage - 1) * pageSize + 1;
        const endItem = Math.min(currentPage * pageSize, equipmentData.length);
        infoText.textContent = `Toplam ${equipmentData.length} kayıttan ${startItem}-${endItem} arası gösteriliyor`;
    }
  }
  // Event listener'lar DOMContentLoaded içinde eklendi

// Veri yükleme fonksiyonu
function loadEquipmentData() {
    const search = document.getElementById('searchInput').value;
    const type = document.getElementById('typeFilter').value;
    const brand = document.getElementById('brandFilter').value;
    const status = document.getElementById('statusFilter').value;
    const tracking = document.getElementById('trackingFilter').value;

    // Loading göster
    const tbody = document.querySelector('#equipmentTable tbody');
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="13" class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-muted mb-2"></i><p class="text-muted">Filtrelenmiş veriler yükleniyor...</p></td></tr>';
    }

    const params = new URLSearchParams({
        page: currentPage,
        search: search,
        type: type,
        brand: brand,
        status: status,
        individual_tracking: tracking
    });

    fetch(`/admin/ekipmanlar/data?${params}`)
        .then(response => response.json())
        .then(data => {
            equipmentData = data.data.map(item => ({
                id: item.id,
                SNO: item.id,
                URUN_CINSI: item.equipment?.name || '-',
                MARKA: item.brand || '-',
                MODEL: item.model || '-',
                BEDEN: item.size || '-',
                OZELLIK: item.feature || '-',
                ADET: item.quantity || 0,
                DURUM: item.status || '-',
                TARIH: item.created_at ? new Date(item.created_at).toLocaleDateString('tr-TR') : '-',
                NOT: item.note || '-',
                INDIVIDUAL_TRACKING: item.equipment?.individual_tracking || false,
                CODE: item.code || '-'
            }));
            
            renderTable();
            updatePagination(data.pagination);
        })
        .catch(error => {
            console.error('Veri yükleme hatası:', error);
            const tbody = document.querySelector('#equipmentTable tbody');
            if (tbody) {
                tbody.innerHTML = '<tr><td colspan="13" class="text-center py-4"><i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i><p class="text-danger">Veriler yüklenirken hata oluştu</p></td></tr>';
            }
            showToast('Veriler yüklenirken hata oluştu', 'error');
        });
}

// Pagination güncelleme
function updatePagination(pagination) {
    const paginationContainer = document.querySelector('#pagination');
    if (paginationContainer) {
        paginationContainer.innerHTML = '';
        
        if (pagination.last_page <= 1) return;

        // Önceki sayfa
        if (pagination.current_page > 1) {
            const prevLi = document.createElement('li');
            prevLi.className = 'page-item';
            const prevA = document.createElement('a');
            prevA.className = 'page-link';
            prevA.href = '#';
            prevA.innerHTML = '‹';
            prevA.onclick = function(e) { 
                e.preventDefault(); 
                currentPage = pagination.current_page - 1; 
                loadEquipmentData(); 
            };
            prevLi.appendChild(prevA);
            paginationContainer.appendChild(prevLi);
        } else {
            const prevLi = document.createElement('li');
            prevLi.className = 'page-item disabled';
            const prevSpan = document.createElement('span');
            prevSpan.className = 'page-link';
            prevSpan.innerHTML = '‹';
            prevLi.appendChild(prevSpan);
            paginationContainer.appendChild(prevLi);
        }

        // Sayfa numaraları (maksimum 5 göster)
        const startPage = Math.max(1, pagination.current_page - 2);
        const endPage = Math.min(pagination.last_page, pagination.current_page + 2);
        
        if (pagination.current_page <= 3) {
            endPage = Math.min(5, pagination.last_page);
        }
        if (pagination.current_page >= pagination.last_page - 2) {
            startPage = Math.max(1, pagination.last_page - 4);
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const li = document.createElement('li');
            li.className = 'page-item' + (i === pagination.current_page ? ' active' : '');
            const a = document.createElement('a');
            a.className = 'page-link';
            a.href = '#';
            a.innerText = i;
            a.onclick = function(e) { 
                e.preventDefault(); 
                currentPage = i; 
                loadEquipmentData(); 
            };
            li.appendChild(a);
            paginationContainer.appendChild(li);
        }

        // Sonraki sayfa
        if (pagination.current_page < pagination.last_page) {
            const nextLi = document.createElement('li');
            nextLi.className = 'page-item';
            const nextA = document.createElement('a');
            nextA.className = 'page-link';
            nextA.href = '#';
            nextA.innerHTML = '›';
            nextA.onclick = function(e) { 
                e.preventDefault(); 
                currentPage = pagination.current_page + 1; 
                loadEquipmentData(); 
            };
            nextLi.appendChild(nextA);
            paginationContainer.appendChild(nextLi);
        } else {
            const nextLi = document.createElement('li');
            nextLi.className = 'page-item disabled';
            const nextSpan = document.createElement('span');
            nextSpan.className = 'page-link';
            nextSpan.innerHTML = '›';
            nextLi.appendChild(nextSpan);
            paginationContainer.appendChild(nextLi);
        }
    }
    
    // Sayfa bilgisi güncelleme
    const infoText = document.querySelector('.text-muted');
    if (infoText) {
        const startItem = (pagination.current_page - 1) * pagination.per_page + 1;
        const endItem = Math.min(pagination.current_page * pagination.per_page, pagination.total);
        infoText.textContent = `Toplam ${pagination.total} kayıttan ${startItem}-${endItem} arası gösteriliyor`;
    }
}



// Toast bildirimi gösterme fonksiyonu
window.showToast = function(message, type = 'info') {
    // Toast container oluştur (eğer yoksa)
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        `;
        document.body.appendChild(toastContainer);
    }

    // Toast elementi oluştur
    const toast = document.createElement('div');
    const iconMap = {
        'success': '✓',
        'error': '✕',
        'warning': '⚠',
        'info': 'ℹ'
    };
    
    const colorMap = {
        'success': '#28a745',
        'error': '#dc3545',
        'warning': '#ffc107',
        'info': '#17a2b8'
    };

    toast.style.cssText = `
        background: white;
        border-left: 4px solid ${colorMap[type] || colorMap.info};
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-radius: 4px;
        padding: 12px 16px;
        min-width: 300px;
        max-width: 400px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-size: 14px;
        color: #333;
        animation: slideIn 0.3s ease forwards;
    `;

    toast.innerHTML = `
        <div style="
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: ${colorMap[type] || colorMap.info};
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        ">${iconMap[type] || iconMap.info}</div>
        <div style="flex: 1;">${message}</div>
        <button onclick="this.parentElement.remove()" style="
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            font-size: 18px;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        ">&times;</button>
    `;

    toastContainer.appendChild(toast);

    // 3 saniye sonra otomatik kaldır
    setTimeout(() => {
        if (toast.parentElement) {
            toast.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 300);
        }
    }, 3000);
};

// Alert gösterme fonksiyonu (SweetAlert2 ile)
window.showAlert = function(message, type = 'info') {
    const iconMap = {
        'success': 'success',
        'error': 'error',
        'warning': 'warning',
        'info': 'info'
    };
    
    Swal.fire({
        title: type === 'success' ? 'Başarılı!' : type === 'error' ? 'Hata!' : 'Bilgi',
        text: message,
        icon: iconMap[type] || 'info',
        timer: type === 'success' ? 2000 : null,
        showConfirmButton: type !== 'success'
    });
};

// CSV export fonksiyonu
document.getElementById('exportCsvBtn').addEventListener('click', function() {
    window.location.href = '/admin/ekipmanlar/export/csv';
});

// Sayfa yüklendiğinde event listener'ları ekle
document.addEventListener('DOMContentLoaded', function() {
    // Filtreleme için event listener'ları ekle
    ['searchInput','typeFilter','brandFilter','statusFilter','trackingFilter'].forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', function(){ 
                // Sadece filtre değeri varsa JavaScript ile yükle
                const hasFilters = document.getElementById('searchInput').value || 
                                 document.getElementById('typeFilter').value || 
                                 document.getElementById('brandFilter').value || 
                                 document.getElementById('statusFilter').value || 
                                 document.getElementById('trackingFilter').value;
                
                if (hasFilters) {
                    currentPage = 1; 
                    loadEquipmentData(); 
                }
            });
            element.addEventListener('change', function(){ 
                // Sadece filtre değeri varsa JavaScript ile yükle
                const hasFilters = document.getElementById('searchInput').value || 
                                 document.getElementById('typeFilter').value || 
                                 document.getElementById('brandFilter').value || 
                                 document.getElementById('statusFilter').value || 
                                 document.getElementById('trackingFilter').value;
                
                if (hasFilters) {
                    currentPage = 1; 
                    loadEquipmentData(); 
                }
            });
        }
    });

    // Düzenlenebilir hücrelere çift tıklama event listener'ı ekle
    document.querySelectorAll('.editable-cell').forEach(cell => {
        cell.addEventListener('dblclick', function() {
            const field = this.getAttribute('data-field');
            const id = this.getAttribute('data-id');
            makeEditable(this, field, id);
        });
        
        // Hover efekti
        cell.addEventListener('mouseenter', function() {
            if (!this.querySelector('input') && !this.querySelector('select')) {
                this.style.backgroundColor = '#f8f9fa';
                this.style.cursor = 'pointer';
            }
        });
        
        cell.addEventListener('mouseleave', function() {
            if (!this.querySelector('input') && !this.querySelector('select')) {
                this.style.backgroundColor = '';
            }
        });
    });
    
    // Filtreleri temizleme butonu
    const clearFiltersBtn = document.getElementById('clearFilters');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            // Tüm filtreleri temizle
            document.getElementById('searchInput').value = '';
            document.getElementById('typeFilter').value = '';
            document.getElementById('brandFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('trackingFilter').value = '';
            
            // Sayfayı yenile (PHP verilerine geri dön)
            window.location.reload();
        });
    }
    
    // Sayfa yüklendiğinde sadece event listener'ları ekle, veri yükleme yapma
    // PHP ile veriler zaten yüklenmiş durumda
});