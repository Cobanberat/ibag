let equipmentData = [
    {SNO: 1, URUN_CINSI: "2.5 KW Benzinli Jeneratör", MARKA: "TOPTICER", MODEL: "TG 3700S", BEDEN: "Küçük", OZELLIK: "2.5 KW Benzinli", ADET: 4, DURUM: "Sıfır", TARIH: "2025-02-09", NOT: "İlk listeden 1 eksik"},
    {SNO: 2, URUN_CINSI: "3.5 KW Benzinli Jeneratör", MARKA: "FULL", MODEL: "FGL 3500-LE", BEDEN: "Orta", OZELLIK: "3.5 KW Benzinli", ADET: 1, DURUM: "Sıfır", TARIH: "2025-02-09", NOT: ""},
    {SNO: 3, URUN_CINSI: "4.4 KW Benzinli Jeneratör", MARKA: "POWER FULL", MODEL: "HH3305-C", BEDEN: "Orta", OZELLIK: "4.4 KW Benzinli", ADET: 1, DURUM: "Sıfır", TARIH: "2025-02-09", NOT: ""},
    {SNO: 4, URUN_CINSI: "7.5 KW Dizel Jeneratör", MARKA: "KAMA", MODEL: "KDK10000", BEDEN: "Büyük", OZELLIK: "7.5 KW Dizel", ADET: 1, DURUM: "Sıfır", TARIH: "2025-02-09", NOT: ""},
  ];
  let currentPage = 1;
  const pageSize = 5;

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
              const activeRowIndex = parseInt(activeEditingCell.getAttribute('data-row'));
              const originalText = activeEditingCell.getAttribute('data-original-text') || activeInput.value;
              saveEdit(activeEditingCell, activeInput.value, activeField, activeRowIndex, originalText);
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
      if (field === 'ADET') {
          // Number input
          input = document.createElement('input');
          input.type = 'number';
          input.min = '0';
          input.max = '999';
          input.className = 'form-control form-control-sm';
          input.value = originalText;
          input.title = 'Adet giriniz (0-999)';
      } else if (field === 'TARIH') {
          // Date input
          input = document.createElement('input');
          input.type = 'date';
          input.className = 'form-control form-control-sm';
          input.value = originalText;
          input.title = 'Tarih seçiniz';
      } else if (dropdownOptions[field]) {
          // Select dropdown
          input = document.createElement('select');
          input.className = 'form-select form-select-sm';
          input.title = `${field} seçiniz`;
          
          // Mevcut değer yoksa boş seçenek ekle
          if (!dropdownOptions[field].includes(originalText)) {
              const emptyOption = document.createElement('option');
              emptyOption.value = '';
              emptyOption.textContent = 'Seçiniz...';
              input.appendChild(emptyOption);
          }
          
          // Seçenekleri ekle
          dropdownOptions[field].forEach(option => {
              const optionElement = document.createElement('option');
              optionElement.value = option;
              optionElement.textContent = option;
              if (option === originalText) {
                  optionElement.selected = true;
              }
              input.appendChild(optionElement);
          });
      } else {
          // Text input (URUN_CINSI dahil)
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
              saveEdit(cell, input.value, field, rowIndex, originalText);
          } else if (e.key === 'Escape') {
              e.preventDefault();
              cancelEdit(cell, originalText);
          } else if (e.key === 'Tab') {
              // Tab ile sonraki hücreye geç
              e.preventDefault();
              const nextCell = getNextEditableCell(cell);
              if (nextCell) {
                  const nextField = nextCell.getAttribute('data-field');
                  const nextRowIndex = parseInt(nextCell.getAttribute('data-row'));
                  saveEdit(cell, input.value, field, rowIndex, originalText);
                  setTimeout(() => {
                      makeEditable(nextCell, nextField, nextRowIndex);
                  }, 100);
              } else {
                  saveEdit(cell, input.value, field, rowIndex, originalText);
              }
          }
      });
      
      // Select için change event
      if (input.tagName === 'SELECT') {
          input.addEventListener('change', function() {
              saveEdit(cell, input.value, field, rowIndex, originalText);
          });
      }
      
      // Focus kaybı ile kaydet (sadece text ve number için)
      if (input.type === 'text' || input.type === 'number') {
          input.addEventListener('blur', function() {
              setTimeout(() => {
                  if (cell.contains(input)) {
                      saveEdit(cell, input.value, field, rowIndex, originalText);
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
  
  function saveEdit(cell, newValue, field, rowIndex, originalText) {
      // Düzenleme durumunu kaldır
      cell.classList.remove('editing');
      cell.removeAttribute('data-original-text');
      activeEditingCell = null;
      
      // Validasyon
      if (field === 'ADET') {
          const numValue = parseInt(newValue);
          if (isNaN(numValue) || numValue < 0 || numValue > 999) {
              cancelEdit(cell, originalText);
              return;
          }
          newValue = numValue.toString();
      } else if (field === 'TARIH') {
          if (!isValidDate(newValue)) {
              cancelEdit(cell, originalText);
              return;
          }
      }
      
      if (newValue.trim() === '') {
          newValue = originalText;
      }
      
      // Veriyi güncelle
      const filtered = getFilteredData();
      const start = (currentPage-1)*pageSize;
      const actualRowIndex = start + rowIndex;
      const originalDataIndex = equipmentData.findIndex(item => item.SNO === filtered[actualRowIndex].SNO);
      
      if (originalDataIndex !== -1) {
          equipmentData[originalDataIndex][field] = newValue;
      }
      
      // Hücreyi güncelle
      cell.textContent = newValue;
      cell.classList.add('saved');
      
      // 2 saniye sonra saved class'ını kaldır
      setTimeout(() => {
          cell.classList.remove('saved');
      }, 2000);
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
  ['searchInput','typeFilter','brandFilter','statusFilter'].forEach(id => {
      document.getElementById(id).addEventListener('input', function(){ currentPage=1; renderTable(); });
      document.getElementById(id).addEventListener('change', function(){ currentPage=1; renderTable(); });
  });
  renderTable();