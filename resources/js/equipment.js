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
                document.getElementById('detailCount').innerText = stock.quantity || 0;
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
                    Swal.fire({
                        title: 'Hata!',
                        text: 'Ekipman silinirken hata oluştu.',
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
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
      let filtered = getFilteredData();
      let total = filtered.length;
      let start = (currentPage-1)*pageSize;
      let end = start+pageSize;
      let pageRows = filtered.slice(start,end);
      let tbody = document.querySelector('#equipmentTable tbody');
      tbody.innerHTML = '';
      pageRows.forEach((row, index) => {
          let tr = document.createElement('tr');
          tr.innerHTML = `
              <td class="editable-cell" data-field="SNO" data-row="${index}">${row.SNO}</td>
              <td class="editable-cell" data-field="URUN_CINSI" data-row="${index}">${row.URUN_CINSI}</td>
              <td class="editable-cell" data-field="MARKA" data-row="${index}">${row.MARKA}</td>
              <td class="editable-cell" data-field="MODEL" data-row="${index}">${row.MODEL}</td>
              <td class="editable-cell" data-field="BEDEN" data-row="${index}">${row.BEDEN}</td>
              <td class="editable-cell" data-field="OZELLIK" data-row="${index}">${row.OZELLIK}</td>
              <td class="editable-cell" data-field="ADET" data-row="${index}">${row.ADET}</td>
              <td class="editable-cell" data-field="DURUM" data-row="${index}">${row.DURUM}</td>
              <td class="editable-cell" data-field="TARIH" data-row="${index}">${row.TARIH}</td>
              <td class="editable-cell" data-field="NOT" data-row="${index}">${row.NOT}</td>
              <td class="E-actions">
                  <button class="btn btn-sm btn-info me-1 detail-btn" data-sno="${row.SNO}" title="Detay"><i class="fas fa-info-circle"></i></button>
                  <button class="btn btn-sm btn-danger delete-btn" data-sno="${row.SNO}" title="Sil"><i class="fas fa-trash-alt"></i></button>
              </td>
          `;
          tbody.appendChild(tr);
      });
      
      // Düzenlenebilir hücrelere çift tıklama event listener'ı ekle
      document.querySelectorAll('.editable-cell').forEach(cell => {
          cell.addEventListener('dblclick', function() {
              const field = this.getAttribute('data-field');
              const rowIndex = parseInt(this.getAttribute('data-row'));
              makeEditable(this, field, rowIndex);
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
      
      let minRows = pageSize;
      for(let i=pageRows.length; i<minRows; i++) {
          let tr = document.createElement('tr');
          tr.innerHTML = '<td colspan="11" style="height:48px; background:#fcfcfc; border:none;"></td>';
          tbody.appendChild(tr);
      }
      renderPagination(Math.ceil(total/pageSize));
      document.querySelectorAll('.detail-btn').forEach(btn => {
          btn.addEventListener('click', function() {
              let sno = parseInt(this.getAttribute('data-sno'));
              let eq = equipmentData.find(r=>r.SNO===sno);
              document.getElementById('detailSno').innerText = eq.SNO;
              document.getElementById('detailType').innerText = eq.URUN_CINSI;
              document.getElementById('detailBrand').innerText = eq.MARKA;
              document.getElementById('detailModel').innerText = eq.MODEL;
              document.getElementById('detailSize').innerText = eq.BEDEN;
              document.getElementById('detailFeature').innerText = eq.OZELLIK;
              document.getElementById('detailCount').innerText = eq.ADET;
              document.getElementById('detailStatus').innerText = eq.DURUM;
              document.getElementById('detailDate').innerText = eq.TARIH;
              document.getElementById('detailNote').innerText = eq.NOT;
              new bootstrap.Modal(document.getElementById('detailModal')).show();
          });
      });
      document.querySelectorAll('.delete-btn').forEach(btn => {
          btn.addEventListener('click', function() {
              let sno = parseInt(this.getAttribute('data-sno'));
              equipmentData = equipmentData.filter(r=>r.SNO!==sno);
              renderTable();
          });
      });
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
  }
  // Event listener'lar DOMContentLoaded içinde eklendi

// Veri yükleme fonksiyonu
function loadEquipmentData() {
    const search = document.getElementById('searchInput').value;
    const type = document.getElementById('typeFilter').value;
    const brand = document.getElementById('brandFilter').value;
    const status = document.getElementById('statusFilter').value;

    const params = new URLSearchParams({
        page: currentPage,
        search: search,
        type: type,
        brand: brand,
        status: status
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
                NOT: item.note || '-'
            }));
            
            renderTable();
            updatePagination(data.pagination);
        })
        .catch(error => {
            console.error('Veri yükleme hatası:', error);
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
  ['searchInput','typeFilter','brandFilter','statusFilter'].forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', function(){ 
                currentPage = 1; 
                loadEquipmentData(); 
            });
            element.addEventListener('change', function(){ 
                currentPage = 1; 
                loadEquipmentData(); 
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
});