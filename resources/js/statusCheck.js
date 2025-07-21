// --- Tablo ve modal işlemleri için güvenli JS ---
// Demo veriler
const acilDurumlarData = [
    { ekipman: 'Akülü Matkap', kategori: 'İnşaat', islem: 'Arıza', tarih: '17.06.2025', sorumlu: 'teknisyen1' },
    { ekipman: 'Hilti Kırıcı', kategori: 'İnşaat', islem: 'Test', tarih: '18.06.2025', sorumlu: 'teknisyen1' },
    { ekipman: 'Test Cihazı', kategori: 'Elektrik', islem: 'Bakım', tarih: '19.06.2025', sorumlu: 'admin' },
  ];
  const yapilacaklarData = [
    { ekipman: 'Jeneratör 5kVA', kategori: 'Elektrik', islem: 'Bakım', tarih: '15.07.2025', sorumlu: 'admin' },
    { ekipman: 'Oksijen Konsantratörü', kategori: 'Medikal', islem: 'Bakım', tarih: '20.06.2025', sorumlu: 'admin' },
    { ekipman: 'Hilti Kırıcı', kategori: 'İnşaat', islem: 'Test', tarih: '18.06.2025', sorumlu: 'teknisyen1' },
    { ekipman: 'Akülü Matkap', kategori: 'İnşaat', islem: 'Arıza', tarih: '17.06.2025', sorumlu: 'teknisyen1' },
    { ekipman: 'UPS 3kVA', kategori: 'Elektrik', islem: 'Bakım', tarih: '25.06.2025', sorumlu: 'admin' },
  ];
  let acilPage = 1, acilPerPage = 2;
  let yapPage = 1, yapPerPage = 3;
  function renderAcilDurumlarTable() {
    const tbody = document.getElementById('acilDurumlarTableBody');
    tbody.innerHTML = '';
    const start = (acilPage-1)*acilPerPage;
    const end = start+acilPerPage;
    const pageData = acilDurumlarData.slice(start, end);
    pageData.forEach((d, i) => {
      tbody.innerHTML += `<tr>
        <td><b>${d.ekipman}</b> <span class='badge bg-info'>${d.kategori}</span></td>
        <td><span class='badge bg-danger'>${d.islem}</span></td>
        <td>${d.tarih}</td>
        <td>${d.sorumlu}</td>
        <td class='text-end table-actions'>
          <button class='btn btn-sm btn-outline-info' onclick='showAcilModal(${start+i},"detay")'><i class='fas fa-eye'></i></button>
          <button class='btn btn-sm btn-outline-secondary' onclick='showAcilModal(${start+i},"edit")'><i class='fas fa-edit'></i></button>
          <button class='btn btn-sm btn-outline-danger' onclick='showAcilModal(${start+i},"delete")'><i class='fas fa-trash'></i></button>
        </td>
      </tr>`;
    });
    renderAcilDurumlarPagination();
  }
  function renderAcilDurumlarPagination() {
    const pageCount = Math.ceil(acilDurumlarData.length/acilPerPage);
    const pag = document.getElementById('acilDurumlarPagination');
    pag.innerHTML = '';
    for(let i=1;i<=pageCount;i++) {
      pag.innerHTML += `<li class='page-item${i===acilPage?' active':''}'><a class='page-link' href='#' onclick='gotoAcilPage(${i});return false;'>${i}</a></li>`;
    }
  }
  function gotoAcilPage(page) {
    acilPage = page;
    renderAcilDurumlarTable();
  }
  window.gotoAcilPage = gotoAcilPage;
  function showAcilModal(idx, type) {
    const d = acilDurumlarData[idx];
    let html = '';
    if(type==='detay') {
      html = `<b>Detay:</b><br>Ekipman: ${d.ekipman}<br>Kategori: ${d.kategori}<br>İşlem: ${d.islem}<br>Tarih: ${d.tarih}<br>Sorumlu: ${d.sorumlu}`;
    } else if(type==='edit') {
      html = `<b>Düzenle:</b><br><input class='form-control mb-2' value='${d.ekipman}'><br><button class='btn btn-primary'>Kaydet</button>`;
    } else if(type==='delete') {
      html = `<b>Silmek istediğinize emin misiniz?</b><br><button class='btn btn-danger mt-2' onclick='deleteAcilDurum(${idx})'>Evet, Sil</button>`;
    }
    var modalBody = document.getElementById('infoModalBody');
    if (modalBody) modalBody.innerHTML = html;
    var modalEl = document.getElementById('infoModal');
    if (modalEl && typeof bootstrap !== 'undefined') {
      var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.show();
    }
  }
  window.showAcilModal = showAcilModal;
  function deleteAcilDurum(idx) {
    acilDurumlarData.splice(idx,1);
    renderAcilDurumlarTable();
    var modalEl = document.getElementById('infoModal');
    if (modalEl && typeof bootstrap !== 'undefined') {
      var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.hide();
    }
  }
  window.deleteAcilDurum = deleteAcilDurum;
  // Yapılması Gerekenler için aynı yapı
  function renderYapilacaklarTable() {
    const tbody = document.getElementById('yapilacaklarTableBody');
    tbody.innerHTML = '';
    const start = (yapPage-1)*yapPerPage;
    const end = start+yapPerPage;
    const pageData = yapilacaklarData.slice(start, end);
    pageData.forEach((d, i) => {
      tbody.innerHTML += `<tr>
        <td><b>${d.ekipman}</b> <span class='badge bg-info'>${d.kategori}</span></td>
        <td><span class='badge bg-warning text-dark'>${d.islem}</span></td>
        <td>${d.tarih}</td>
        <td>${d.sorumlu}</td>
        <td class='text-end table-actions'>
          <button class='btn btn-sm btn-outline-info' onclick='showYapModal(${start+i},"detay")'><i class='fas fa-eye'></i></button>
          <button class='btn btn-sm btn-outline-secondary' onclick='showYapModal(${start+i},"edit")'><i class='fas fa-edit'></i></button>
          <button class='btn btn-sm btn-outline-danger' onclick='showYapModal(${start+i},"delete")'><i class='fas fa-trash'></i></button>
        </td>
      </tr>`;
    });
    renderYapilacaklarPagination();
  }
  function renderYapilacaklarPagination() {
    const pageCount = Math.ceil(yapilacaklarData.length/yapPerPage);
    const pag = document.getElementById('yapilacaklarPagination');
    pag.innerHTML = '';
    for(let i=1;i<=pageCount;i++) {
      pag.innerHTML += `<li class='page-item${i===yapPage?' active':''}'><a class='page-link' href='#' onclick='gotoYapPage(${i});return false;'>${i}</a></li>`;
    }
  }
  function gotoYapPage(page) {
    yapPage = page;
    renderYapilacaklarTable();
  }
  window.gotoYapPage = gotoYapPage;
  function showYapModal(idx, type) {
    const d = yapilacaklarData[idx];
    let html = '';
    if(type==='detay') {
      html = `<b>Detay:</b><br>Ekipman: ${d.ekipman}<br>Kategori: ${d.kategori}<br>İşlem: ${d.islem}<br>Tarih: ${d.tarih}<br>Sorumlu: ${d.sorumlu}`;
    } else if(type==='edit') {
      html = `<b>Düzenle:</b><br><input class='form-control mb-2' value='${d.ekipman}'><br><button class='btn btn-primary'>Kaydet</button>`;
    } else if(type==='delete') {
      html = `<b>Silmek istediğinize emin misiniz?</b><br><button class='btn btn-danger mt-2' onclick='deleteYapilacak(${idx})'>Evet, Sil</button>`;
    }
    var modalBody = document.getElementById('infoModalBody');
    if (modalBody) modalBody.innerHTML = html;
    var modalEl = document.getElementById('infoModal');
    if (modalEl && typeof bootstrap !== 'undefined') {
      var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.show();
    }
  }
  window.showYapModal = showYapModal;
  function deleteYapilacak(idx) {
    yapilacaklarData.splice(idx,1);
    renderYapilacaklarTable();
    var modalEl = document.getElementById('infoModal');
    if (modalEl && typeof bootstrap !== 'undefined') {
      var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.hide();
    }
  }
  window.deleteYapilacak = deleteYapilacak;
  // Sayfa yüklendiğinde tabloları render et
  window.addEventListener('DOMContentLoaded', function() {
    renderAcilDurumlarTable();
    renderYapilacaklarTable();
  });