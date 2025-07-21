// Global pagination değişkenleri
let perPage = 5, gidenPage = 1, gelenPage = 1;
// Demo veri (sadeleştirilmiş)
const comingGoingData = [
  {
    id: '2025-ANK-021',
    date: '2025-07-20',
    type: 'Gidiş',
    location: 'Ankara - Sincan',
    person: 'Ayşe Yılmaz',
    ekipmanlar: [
      { isim: 'UPS 3kVA', adet: 2, resim: '/images/ups-return.jpg' },
      { isim: 'Kask', adet: 10, resim: '/images/helmet-return.jpg' }
    ],
    description: 'UPS ve Kask Ankara Sincan bölgesine gönderildi. Batarya değişimi sonrası sorunsuz döndü.'
  },
  {
    id: '2025-IST-011',
    date: '2025-07-18',
    type: 'Dönüş',
    location: 'İstanbul - Kadıköy',
    person: 'Mehmet Kara',
    status: 'Sorunsuz',
    statusClass: 'success',
    ekipmanlar: [
      { isim: 'El Feneri', adet: 5, resim: '/images/flashlight.jpg' }
    ],
    description: 'El fenerleri İstanbul Kadıköy bölgesinden sorunsuz şekilde döndü.'
  },
  {
    id: '2025-IZM-005',
    date: '2025-07-15',
    type: 'Gidiş',
    location: 'İzmir - Bornova',
    person: 'Fatma Demir',
    ekipmanlar: [
      { isim: 'Çadır', adet: 3, resim: '/images/tent.jpg' }
    ],
    description: 'Çadırlar İzmir Bornova bölgesine gönderildi. 1 çadır hasarlı olarak döndü.'
  },
  {
    id: '2025-IZM-005-RET',
    date: '2025-07-18',
    type: 'Dönüş',
    location: 'İzmir - Bornova',
    person: 'Fatma Demir',
    status: 'Hasarlı',
    statusClass: 'danger',
    ekipmanlar: [
      { isim: 'Çadır', adet: 3, resim: '/images/tent.jpg' }
    ],
    description: 'Çadırlar İzmir Bornova bölgesine gönderildi. 1 çadır hasarlı olarak döndü.'
  },
  {
    id: '2025-ANK-022-RET',
    date: '2025-07-22',
    type: 'Dönüş',
    location: 'Ankara - Sincan',
    person: 'Ayşe Yılmaz',
    status: 'Eksik',
    statusClass: 'warning',
    ekipmanlar: [
      { isim: 'Kask', adet: 8, resim: '/images/helmet-return.jpg' }
    ],
    description: 'Kasklar Ankara Sincan bölgesine gönderildi. 2 adet kayıp olarak bildirildi.'
  },
];
// Pagination ve tablo render
function getUnique(arr, key) {
  return [...new Set(arr.map(x => x[key]))].filter(Boolean);
}
function renderTables() {
  // Gidenler
  let gidenData = comingGoingData.filter(x => x.type === 'Gidiş');
  // Filtreler
  const gidenSearch = document.getElementById('gidenSearch').value.toLowerCase();
  const gidenLokasyon = document.getElementById('gidenLokasyon').value;
  const gidenYetkili = document.getElementById('gidenYetkili').value;
  if (gidenSearch) gidenData = gidenData.filter(x => x.location.toLowerCase().includes(gidenSearch) || x.person.toLowerCase().includes(gidenSearch));
  if (gidenLokasyon) gidenData = gidenData.filter(x => x.location === gidenLokasyon);
  if (gidenYetkili) gidenData = gidenData.filter(x => x.person === gidenYetkili);
  // Filtre selectlerini doldur
  const lokasyonlar = getUnique(comingGoingData.filter(x=>x.type==='Gidiş'), 'location');
  const yetkililer = getUnique(comingGoingData.filter(x=>x.type==='Gidiş'), 'person');
  document.getElementById('gidenLokasyon').innerHTML = '<option value="">Tümü</option>' + lokasyonlar.map(l=>`<option value="${l}">${l}</option>`).join('');
  document.getElementById('gidenYetkili').innerHTML = '<option value="">Tümü</option>' + yetkililer.map(y=>`<option value="${y}">${y}</option>`).join('');
  const gidenTotal = gidenData.length;
  const gidenPageCount = Math.ceil(gidenTotal/perPage);
  const gidenStart = (gidenPage-1)*perPage;
  const gidenEnd = gidenStart+perPage;
  const gidenRows = gidenData.slice(gidenStart, gidenEnd).map(item => `
    <tr>
      <td>${item.location}</td>
      <td>${item.person}</td>
      <td>${item.date}</td>
      <td><button class="btn btn-info btn-sm" onclick="showDetail('${item.id}')"><i class='fas fa-eye'></i>Detay</button></td>
      <td><button class="btn btn-success btn-sm" onclick="openFinishModal('${item.id}')"><i class='fas fa-check-circle'></i>İşlemi Bitir</button></td>
    </tr>
  `).join('');
  document.getElementById('gidenTableBody').innerHTML = gidenRows;
  // Gidenler pagination
  let gidenPag = '';
  for(let i=1;i<=gidenPageCount;i++) {
    gidenPag += `<li class="page-item${i===gidenPage?' active':''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
  }
  document.getElementById('gidenPagination').innerHTML = gidenPag;
  // Gelenler
  let gelenData = comingGoingData.filter(x => x.type === 'Dönüş');
  // Filtreler
  const gelenSearch = document.getElementById('gelenSearch').value.toLowerCase();
  const gelenLokasyon = document.getElementById('gelenLokasyon').value;
  const gelenYetkili = document.getElementById('gelenYetkili').value;
  const gelenDurum = document.getElementById('gelenDurum').value;
  if (gelenSearch) gelenData = gelenData.filter(x => x.location.toLowerCase().includes(gelenSearch) || x.person.toLowerCase().includes(gelenSearch));
  if (gelenLokasyon) gelenData = gelenData.filter(x => x.location === gelenLokasyon);
  if (gelenYetkili) gelenData = gelenData.filter(x => x.person === gelenYetkili);
  if (gelenDurum) gelenData = gelenData.filter(x => x.status === gelenDurum);
  // Filtre selectlerini doldur
  const gelenLokasyonlar = getUnique(comingGoingData.filter(x=>x.type==='Dönüş'), 'location');
  const gelenYetkililer = getUnique(comingGoingData.filter(x=>x.type==='Dönüş'), 'person');
  document.getElementById('gelenLokasyon').innerHTML = '<option value="">Tümü</option>' + gelenLokasyonlar.map(l=>`<option value="${l}">${l}</option>`).join('');
  document.getElementById('gelenYetkili').innerHTML = '<option value="">Tümü</option>' + gelenYetkililer.map(y=>`<option value="${y}">${y}</option>`).join('');
  const gelenTotal = gelenData.length;
  const gelenPageCount = Math.ceil(gelenTotal/perPage);
  const gelenStart = (gelenPage-1)*perPage;
  const gelenEnd = gelenStart+perPage;
  const gelenRows = gelenData.slice(gelenStart, gelenEnd).map(item => `
    <tr>
      <td>${item.location}</td>
      <td>${item.person}</td>
      <td>${item.date}</td>
      <td><span class="badge badge-${item.statusClass}">${item.status}</span></td>
      <td><button class="btn btn-info btn-sm" onclick="showDetail('${item.id}')"><i class='fas fa-eye'></i>Detay</button></td>
    </tr>
  `).join('');
  document.getElementById('gelenTableBody').innerHTML = gelenRows;
  // Gelenler pagination
  let gelenPag = '';
  for(let i=1;i<=gelenPageCount;i++) {
    gelenPag += `<li class="page-item${i===gelenPage?' active':''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
  }
  document.getElementById('gelenPagination').innerHTML = gelenPag;
}
// Detay modalı
function showDetail(id) {
  const item = comingGoingData.find(x => x.id === id);
  if (!item) return;
  let html = `<ul class='list-group mb-3'>
    <li class='list-group-item'><b>Lokasyon:</b> ${item.location}</li>
    <li class='list-group-item'><b>Yetkili:</b> ${item.person}</li>
    <li class='list-group-item'><b>Tarih:</b> ${item.date}</li>`;
  if (item.status) html += `<li class='list-group-item'><b>Durum:</b> <span class='badge badge-${item.statusClass}'>${item.status}</span></li>`;
  if (item.description) html += `<li class='list-group-item'><b>Açıklama:</b> ${item.description}</li>`;
  html += '</ul>';
  if (item.ekipmanlar && item.ekipmanlar.length > 0) {
    html += `<div class='mb-2'><b>Ekipmanlar:</b></div><div class='row'>`;
    item.ekipmanlar.forEach(eq => {
      html += `<div class='col-6 col-md-4 mb-3 text-center'>
        <img src='${eq.resim}' alt='${eq.isim}' class='img-fluid rounded mb-2' style='max-height:80px;'>
        <div><b>${eq.isim}</b></div>
        <div>Adet: ${eq.adet}</div>
      </div>`;
    });
    html += '</div>';
  }
  document.getElementById('detailModalBody').innerHTML = html;
  var modal = new bootstrap.Modal(document.getElementById('detailModal'));
  modal.show();
}
// İşlemi Bitir modalı
let finishItemId = null;
function openFinishModal(id) {
  finishItemId = id;
  const item = comingGoingData.find(x => x.id === id);
  if (!item) return;
  let html = `<div class='mb-3'><b>Lokasyon:</b> ${item.location} <br><b>Yetkili:</b> ${item.person}</div>`;
  if (item.ekipmanlar && item.ekipmanlar.length > 0) {
    html += `<div class='row'>`;
    item.ekipmanlar.forEach((eq, idx) => {
      html += `<div class='col-md-6 mb-4'>
        <div class='card shadow-sm h-100'>
          <div class='card-body text-center'>
            <img src='${eq.resim}' alt='${eq.isim}' class='img-fluid rounded mb-2' style='max-height:90px;'>
            <div class='fw-bold mb-2'>${eq.isim} <span class='badge bg-primary ms-2'>Adet: ${eq.adet}</span></div>
            <div class='mb-2'>
              <label class='form-label mb-1'>Açıklama</label>
              <input type='text' class='form-control' name='desc_${idx}' placeholder='Açıklama'>
            </div>
            <div class='mb-2'>
              <label class='form-label mb-1'>Arıza Var mı?</label>
              <select class='form-select' name='fault_${idx}'>
                <option value='Sorunsuz'>Sorunsuz</option>
                <option value='Hasarlı'>Hasarlı</option>
                <option value='Eksik'>Eksik</option>
              </select>
            </div>
          </div>
        </div>
      </div>`;
    });
    html += '</div>';
  }
  html += `<div class='mb-3'><label class='form-label'>Genel Not</label><textarea class='form-control' name='genelNot' rows='2'></textarea></div>`;
  document.getElementById('finishModalBody').innerHTML = html;
  var modal = new bootstrap.Modal(document.getElementById('finishModal'));
  modal.show();
}
document.getElementById('finishForm').onsubmit = function(e) {
  e.preventDefault();
  const item = comingGoingData.find(x => x.id === finishItemId);
  if (!item) return;
  const ekipmanlar = item.ekipmanlar.map((eq, idx) => {
    const desc = document.querySelector(`[name='desc_${idx}']`).value;
    const fault = document.querySelector(`[name='fault_${idx}']`).value;
    return {
      ...eq,
      desc,
      fault,
    };
  });
  const genelNot = document.querySelector(`[name='genelNot']`).value;
  // Gelenler tablosuna ekle
  const now = new Date();
  const tarih = now.toISOString().slice(0,10);
  ekipmanlar.forEach(eq => {
    comingGoingData.push({
      id: item.id + '-RET-' + Math.random().toString(36).substr(2,5),
      date: tarih,
      type: 'Dönüş',
      location: item.location,
      person: item.person,
      status: eq.fault,
      statusClass: eq.fault === 'Sorunsuz' ? 'success' : (eq.fault === 'Eksik' ? 'warning' : 'danger'),
      ekipmanlar: [
        { isim: eq.isim, adet: eq.adet, resim: eq.resim, desc: eq.desc }
      ],
      description: (eq.desc ? eq.desc + ' ' : '') + (genelNot ? 'Not: ' + genelNot : '')
    });
  });
  // Gidenler listesinden çıkar
  const idx = comingGoingData.findIndex(x => x.id === finishItemId);
  if (idx > -1) comingGoingData.splice(idx, 1);
  renderTables();
  bootstrap.Modal.getInstance(document.getElementById('finishModal')).hide();
  alert('Dönüş işlemi başarıyla kaydedildi ve Gelenler tablosuna eklendi!');
};
// Pagination tıklama eventleri
document.addEventListener('DOMContentLoaded', function() {
  renderTables();
  // Pagination tıklama
  document.getElementById('gidenPagination').onclick = function(e) {
    const a = e.target.closest('a');
    if (a) {
      gidenPage = parseInt(a.dataset.page);
      renderTables();
    }
  };
  document.getElementById('gelenPagination').onclick = function(e) {
    const a = e.target.closest('a');
    if (a) {
      gelenPage = parseInt(a.dataset.page);
      renderTables();
    }
  };
  // Filtre eventleri
  document.getElementById('gidenSearch').oninput = renderTables;
  document.getElementById('gidenLokasyon').onchange = renderTables;
  document.getElementById('gidenYetkili').onchange = renderTables;
  document.getElementById('gelenSearch').oninput = renderTables;
  document.getElementById('gelenLokasyon').onchange = renderTables;
  document.getElementById('gelenYetkili').onchange = renderTables;
  document.getElementById('gelenDurum').onchange = renderTables;
});
function clearGidenFilters() {
  document.getElementById('gidenSearch').value = '';
  document.getElementById('gidenLokasyon').value = '';
  document.getElementById('gidenYetkili').value = '';
  renderTables();
}
function clearGelenFilters() {
  document.getElementById('gelenSearch').value = '';
  document.getElementById('gelenLokasyon').value = '';
  document.getElementById('gelenYetkili').value = '';
  document.getElementById('gelenDurum').value = '';
  renderTables();
}