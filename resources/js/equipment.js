let equipmentData = [
    {SNO: 1, URUN_CINSI: "2.5 KW Benzinli Jeneratör", MARKA: "TOPTICER", MODEL: "TG 3700S", BEDEN: "Küçük", OZELLIK: "2.5 KW Benzinli", ADET: 4, DURUM: "Sıfır", TARIH: "2025-02-09", NOT: "İlk listeden 1 eksik"},
    {SNO: 2, URUN_CINSI: "3.5 KW Benzinli Jeneratör", MARKA: "FULL", MODEL: "FGL 3500-LE", BEDEN: "Orta", OZELLIK: "3.5 KW Benzinli", ADET: 1, DURUM: "Sıfır", TARIH: "2025-02-09", NOT: ""},
    {SNO: 3, URUN_CINSI: "4.4 KW Benzinli Jeneratör", MARKA: "POWER FULL", MODEL: "HH3305-C", BEDEN: "Orta", OZELLIK: "4.4 KW Benzinli", ADET: 1, DURUM: "Sıfır", TARIH: "2025-02-09", NOT: ""},
    {SNO: 4, URUN_CINSI: "7.5 KW Dizel Jeneratör", MARKA: "KAMA", MODEL: "KDK10000", BEDEN: "Büyük", OZELLIK: "7.5 KW Dizel", ADET: 1, DURUM: "Sıfır", TARIH: "2025-02-09", NOT: ""}
  ];
  let currentPage = 1;
  const pageSize = 5;
  function renderTable() {
      let filtered = equipmentData.filter(row => {
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
      let total = filtered.length;
      let start = (currentPage-1)*pageSize;
      let end = start+pageSize;
      let pageRows = filtered.slice(start,end);
      let tbody = document.querySelector('#equipmentTable tbody');
      tbody.innerHTML = '';
      pageRows.forEach(row => {
          let tr = document.createElement('tr');
          tr.innerHTML = `
              <td>${row.SNO}</td>
              <td>${row.URUN_CINSI}</td>
              <td>${row.MARKA}</td>
              <td>${row.MODEL}</td>
              <td>${row.BEDEN}</td>
              <td>${row.OZELLIK}</td>
              <td>${row.ADET}</td>
              <td>${row.DURUM}</td>
              <td>${row.TARIH}</td>
              <td>${row.NOT}</td>
              <td>
                  <button class="btn btn-sm btn-info me-1 detail-btn" data-sno="${row.SNO}" title="Detay"><i class="fas fa-info-circle"></i></button>
                  <button class="btn btn-sm btn-danger delete-btn" data-sno="${row.SNO}" title="Sil"><i class="fas fa-trash-alt"></i></button>
              </td>
          `;
          tbody.appendChild(tr);
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
      for(let i=1;i<=pageCount;i++) {
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
  }
  ['searchInput','typeFilter','brandFilter','statusFilter'].forEach(id => {
      document.getElementById(id).addEventListener('input', function(){ currentPage=1; renderTable(); });
      document.getElementById(id).addEventListener('change', function(){ currentPage=1; renderTable(); });
  });
  renderTable();