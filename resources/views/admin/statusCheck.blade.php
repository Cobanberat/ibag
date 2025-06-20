@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
  .kpi-box {
    border-radius: 1rem;
    background: linear-gradient(135deg,#f7e7ce 0%,#e0e7ff 100%);
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    padding: 1.2rem 1.5rem;
    display: flex; align-items: center; gap: 1rem;
    min-height: 90px;
  }
  .kpi-icon {
    font-size: 2.2rem;
    opacity: 0.7;
  }
  .kpi-value {
    font-size: 1.7rem;
    font-weight: bold;
    margin-bottom: 0.1rem;
  }
  .kpi-label {
    font-size: 1rem;
    color: #666;
  }
  .equipment-status {
    font-size: 0.95rem;
    font-weight: 600;
    padding: 0.3em 0.8em;
    border-radius: 1em;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
  }
  .equipment-card {
    border-radius: 1rem;
    box-shadow: 0 2px 16px rgba(0,0,0,0.08), 0 1.5px 4px rgba(0,0,0,0.04);
    transition: box-shadow 0.3s, transform 0.3s;
    background: #fff;
    opacity: 0;
    transform: translateY(30px);
  }
  .equipment-card.fade-in {
    opacity: 1;
    transform: translateY(0);
    transition: opacity 0.7s cubic-bezier(.4,0,.2,1), transform 0.7s cubic-bezier(.4,0,.2,1);
  }
  .equipment-img-box {
    position: relative;
    height: 120px;
    background: #f5f5f5;
    overflow: hidden;
    border-top-left-radius: 1rem;
    border-top-right-radius: 1rem;
  }
  .equipment-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s cubic-bezier(.4,0,.2,1), filter 0.4s;
    filter: brightness(0.92) saturate(1.1);
  }
  .equipment-card:hover .equipment-img {
    transform: scale(1.08);
    filter: brightness(1) saturate(1.2) blur(1px);
  }
  .equipment-title-bar {
    position: absolute;
    left: 0; right: 0; top: 0;
    z-index: 2;
    padding: 0.4rem 1rem 0.4rem 1rem;
    background: rgba(255,255,255,0.7);
    border-bottom-left-radius: 1rem;
    border-bottom-right-radius: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    backdrop-filter: blur(2px);
  }
  .equipment-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem;
    color: #bbb;
    background: linear-gradient(135deg,#f7e7ce 0%,#e0e7ff 100%);
  }
  .badge-overdue { background: #ff4d4f; color: #fff; }
  .badge-upcoming { background: #ffec3d; color: #333; }
  .timeline {
    position: relative;
    margin: 2rem 0 2rem 1.5rem;
    padding-left: 2rem;
    border-left: 3px solid #e0e7ff;
  }
  .timeline-event {
    position: relative;
    margin-bottom: 2.2rem;
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.7s cubic-bezier(.4,0,.2,1), transform 0.7s cubic-bezier(.4,0,.2,1);
  }
  .timeline-event.visible {
    opacity: 1;
    transform: translateY(0);
  }
  .timeline-dot {
    position: absolute;
    left: -2.1rem;
    top: 0.2rem;
    width: 2.2rem;
    height: 2.2rem;
    border-radius: 50%;
    background: #fff;
    border: 3px solid #b3b3ff;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
  }
  .timeline-dot.bakim { border-color: #ffc107; color: #ffc107; }
  .timeline-dot.test { border-color: #17a2b8; color: #17a2b8; }
  .timeline-dot.ariza { border-color: #ff4d4f; color: #ff4d4f; }
  .timeline-dot.tasinma { border-color: #6f42c1; color: #6f42c1; }
  .timeline-dot.kullanim { border-color: #28a745; color: #28a745; }
  .timeline-dot.diger { border-color: #adb5bd; color: #adb5bd; }
  .timeline-content {
    background: #fff;
    border-radius: 0.7rem;
    box-shadow: 0 1px 6px rgba(0,0,0,0.06);
    padding: 1rem 1.2rem;
    margin-left: 0.5rem;
    min-width: 220px;
    max-width: 600px;
  }
  .timeline-date {
    font-size: 0.95rem;
    color: #888;
    margin-bottom: 0.2rem;
  }
  .timeline-title {
    font-weight: bold;
    margin-bottom: 0.2rem;
  }
  .timeline-desc {
    font-size: 1rem;
    margin-bottom: 0.1rem;
  }
  .timeline-person {
    font-size: 0.95rem;
    color: #666;
  }
  .badge-done { background: #28a745; color: #fff; }
  .timeline-event:last-child { margin-bottom: 0; }
  .equipment-detail-panel { background: #fff; border-radius: 1rem; box-shadow: 0 2px 12px rgba(0,0,0,0.07); padding: 1.2rem 1.5rem; margin-bottom: 1.5rem; }
  .qr-box { background: #f7f7fa; border-radius: 0.7rem; padding: 0.7rem; text-align: center; }
  .chart-box { background: #fff; border-radius: 1rem; box-shadow: 0 2px 12px rgba(0,0,0,0.07); padding: 1.2rem 1.5rem; margin-bottom: 1.5rem; }
  .calendar-box { background: #fff; border-radius: 1rem; box-shadow: 0 2px 12px rgba(0,0,0,0.07); padding: 1.2rem 1.5rem; margin-bottom: 1.5rem; }
</style>

<!-- Bildirim ve Hatırlatıcılar -->
<div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
  <i class="fas fa-bell fa-lg me-2"></i>
  <div><b>2 ekipmanda yaklaşan bakım var!</b> <span class="small">Bakım planlaması yapmayı unutmayın.</span></div>
</div>
<div class="alert alert-danger d-flex align-items-center mb-3" role="alert">
  <i class="fas fa-exclamation-triangle fa-lg me-2"></i>
  <div><b>1 ekipmanda geciken işlem var!</b> <span class="small">Acil müdahale gereklidir.</span></div>
</div>
<div class="alert alert-info d-flex align-items-center mb-3" role="alert">
  <i class="fas fa-robot fa-lg me-2"></i>
  <div><b>Yapay Zeka Önerisi:</b> Jeneratör 5kVA için 10 gün içinde arıza riski yüksek. Önleyici bakım önerilir.</div>
</div>

<!-- KPI Kutuları -->
<div class="row g-3 mb-4">
  <div class="col-md-3"><div class="kpi-box"><i class="fas fa-cogs kpi-icon text-primary"></i><div><div class="kpi-value">12</div><div class="kpi-label">Toplam Ekipman</div></div></div></div>
  <div class="col-md-3"><div class="kpi-box"><i class="fas fa-calendar-alt kpi-icon text-success"></i><div><div class="kpi-value">3</div><div class="kpi-label">Yaklaşan Bakım</div></div></div></div>
  <div class="col-md-3"><div class="kpi-box"><i class="fas fa-exclamation-triangle kpi-icon text-danger"></i><div><div class="kpi-value">2</div><div class="kpi-label">Geciken Bakım</div></div></div></div>
  <div class="col-md-3"><div class="kpi-box"><i class="fas fa-stopwatch kpi-icon text-info"></i><div><div class="kpi-value">4g</div><div class="kpi-label">Ortalama Bakım Süresi</div></div></div></div>
</div>

<!-- Ana Panel -->
<div class="row g-4">
  <div class="col-md-3">
    <!-- Ekipman Detay Paneli -->
    <div class="equipment-detail-panel mb-4" id="equipmentDetailPanel">
      <div class="fw-bold mb-2">Ekipman Detayları</div>
      <div id="equipmentDetailContent" class="small text-muted">Bir ekipman seçin...</div>
      <div class="qr-box mt-3" id="qrBox" style="display:none;"></div>
    </div>
    <!-- Grafikler -->
    <div class="chart-box mb-4">
      <div class="fw-bold mb-2">Olay Türü Dağılımı</div>
      <canvas id="eventPieChart" height="180"></canvas>
    </div>
    <div class="chart-box mb-4">
      <div class="fw-bold mb-2">Aylık Olay Sayısı</div>
      <canvas id="eventBarChart" height="180"></canvas>
    </div>
    <!-- Takvim -->
    <div class="calendar-box">
      <div class="fw-bold mb-2">Bakım Takvimi</div>
      <input type="text" id="calendarInput" class="form-control" placeholder="Tarihe göre filtrele">
    </div>
    <!-- Toplu Rapor İndir -->
    <button class="btn btn-outline-secondary w-100 mt-3" id="downloadReportBtn"><i class="fas fa-file-download"></i> Toplu Rapor İndir</button>
  </div>
  <div class="col-md-4">
    <div class="card shadow-sm">
      <div class="card-header bg-dark text-white"><i class="fas fa-cogs me-2"></i> Ekipmanlar</div>
      <div class="card-body p-2" id="equipmentList"></div>
    </div>
  </div>
  <div class="col-md-5">
    <div class="card shadow-sm mb-3">
      <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
        <span><i class="fas fa-stethoscope me-2"></i> Durum Zaman Çizelgesi</span>
        <button class="btn btn-sm btn-success" id="addEventBtn" style="display:none;"><i class="fas fa-plus"></i> Olay Ekle</button>
      </div>
      <div class="card-body" id="timelineBody">
        <div class="alert alert-info">Bir ekipman seçin ve geçmiş/gelecek tüm önemli olayları burada görün.</div>
      </div>
    </div>
  </div>
</div>

<!-- Olay Ekle Modalı -->
<div class="modal fade" id="eventAddModal" tabindex="-1" aria-labelledby="eventAddModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventAddModalLabel">Olay Ekle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <form id="eventAddForm">
          <div class="mb-2">
            <label class="form-label">İşlem Tipi</label>
            <select class="form-select" id="eventTypeInput" required>
              <option value="bakim">Bakım</option>
              <option value="test">Test</option>
              <option value="ariza">Arıza</option>
              <option value="tasinma">Taşınma</option>
              <option value="kullanim">Kullanım</option>
              <option value="diger">Diğer</option>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label">Başlık</label>
            <input type="text" class="form-control" id="eventTitleInput" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Açıklama</label>
            <textarea class="form-control" id="eventDescInput" rows="2" required></textarea>
          </div>
          <div class="mb-2">
            <label class="form-label">Tarih</label>
            <input type="text" class="form-control" id="eventDateInput" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Dosya Ekle (PDF/JPG/PNG)</label>
            <input type="file" class="form-control" id="eventFileInput">
          </div>
          <div class="mb-2">
            <label class="form-label">Yorum</label>
            <input type="text" class="form-control" id="eventCommentInput">
          </div>
          <div class="mb-2">
            <label class="form-label">Sorumlu</label>
            <select class="form-select" id="eventPersonInput" required>
              <option>lazBerat</option>
              <option>admin</option>
              <option>teknisyen1</option>
            </select>
          </div>
          <button type="submit" class="btn btn-success w-100">Kaydet</button>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
// Statik örnek veri
document.equipmentData = [
  {
    id: 1,
    name: 'Jeneratör 5kVA',
    kategori: 'Elektrik',
    sorumlu: 'lazBerat',
    teknik: {garanti:'2026', satinAlma:'2022', toplamBakim:7, model:'GEN-5K-2023'},
    timeline: [
      {tarih:'01.06.2025', tip:'bakim', baslik:'Periyodik Bakım', aciklama:'Yağ filtresi değişti, genel kontrol yapıldı.', yapan:'teknisyen1'},
      {tarih:'15.06.2025', tip:'test', baslik:'Motor Testi', aciklama:'Motor performans testi uygulandı.', yapan:'teknisyen1'},
      {tarih:'18.06.2025', tip:'ariza', baslik:'Arıza Bildirimi', aciklama:'Yağ kaçağı tespit edildi.', yapan:'lazBerat'},
      {tarih:'19.06.2025', tip:'bakim', baslik:'Arıza Sonrası Bakım', aciklama:'Yağ kaçağı giderildi.', yapan:'teknisyen1'},
      {tarih:'15.07.2025', tip:'bakim', baslik:'Planlanan Periyodik Bakım', aciklama:'Planlanan bakım tarihi.', yapan:'admin', gelecek:true}
    ]
  },
  {
    id: 2,
    name: 'Oksijen Konsantratörü',
    kategori: 'Medikal',
    sorumlu: 'admin',
    teknik: {garanti:'2025', satinAlma:'2021', toplamBakim:4, model:'OKS-2021-02'},
    timeline: [
      {tarih:'01.05.2025', tip:'bakim', baslik:'Periyodik Bakım', aciklama:'Filtre değişimi yapıldı.', yapan:'admin'},
      {tarih:'10.06.2025', tip:'test', baslik:'Fonksiyon Testi', aciklama:'Cihaz fonksiyon testi uygulandı.', yapan:'admin'},
      {tarih:'20.06.2025', tip:'bakim', baslik:'Planlanan Periyodik Bakım', aciklama:'Planlanan bakım tarihi.', yapan:'admin', gelecek:true}
    ]
  },
  {
    id: 3,
    name: 'Hilti Kırıcı',
    kategori: 'İnşaat',
    sorumlu: 'teknisyen1',
    teknik: {garanti:'2024', satinAlma:'2020', toplamBakim:6, model:'HILT-2020-01'},
    timeline: [
      {tarih:'01.06.2025', tip:'bakim', baslik:'Periyodik Bakım', aciklama:'Batarya değişimi.', yapan:'teknisyen1'},
      {tarih:'10.06.2025', tip:'tasinma', baslik:'Taşınma', aciklama:'Gaziantep şubesine taşındı.', yapan:'admin'},
      {tarih:'15.06.2025', tip:'ariza', baslik:'Arıza Bildirimi', aciklama:'Motor arızası tespit edildi.', yapan:'teknisyen1'},
      {tarih:'16.06.2025', tip:'bakim', baslik:'Arıza Sonrası Bakım', aciklama:'Motor değişimi yapıldı.', yapan:'teknisyen1'},
      {tarih:'01.07.2025', tip:'bakim', baslik:'Planlanan Periyodik Bakım', aciklama:'Planlanan bakım tarihi.', yapan:'admin', gelecek:true}
    ]
  }
];

function renderEquipmentList() {
  const list = document.getElementById('equipmentList');
  list.innerHTML = '';
  let data = document.equipmentData;
  // Filtreleme
  const search = document.getElementById('searchInput').value.toLowerCase();
  const kategori = document.getElementById('kategoriFilter').value;
  const islem = document.getElementById('islemFilter').value;
  const sorumlu = document.getElementById('sorumluFilter').value;
  data = data.filter(e =>
    (!search || e.name.toLowerCase().includes(search)) &&
    (!kategori || e.kategori === kategori) &&
    (!sorumlu || e.sorumlu === sorumlu) &&
    (!islem || e.timeline.some(ev=>ev.tip.toLowerCase()===islem.toLowerCase()))
  );
  if(data.length === 0) {
    list.innerHTML = '<div class="alert alert-info m-2">Kriterlere uygun ekipman bulunamadı.</div>';
    return;
  }
  data.forEach((e,i) => {
    list.insertAdjacentHTML('beforeend', `
      <div class="card mb-2 equipment-select" data-id="${e.id}" style="cursor:pointer;">
        <div class="card-body py-2 d-flex align-items-center">
          <i class="fas fa-cogs fa-lg text-primary me-2"></i>
          <div>
            <div class="fw-bold">${e.name}</div>
            <div class="small text-muted">${e.kategori} | ${e.sorumlu}</div>
          </div>
        </div>
      </div>
    `);
  });
  // Ekipman seçimi
  document.querySelectorAll('.equipment-select').forEach(card => {
    card.onclick = function() {
      const id = card.getAttribute('data-id');
      renderTimeline(id);
      document.querySelectorAll('.equipment-select').forEach(c=>c.classList.remove('border-primary','border-2'));
      card.classList.add('border-primary','border-2');
    }
  });
}
function renderTimeline(id) {
  const eq = document.equipmentData.find(e=>e.id==id);
  const timeline = eq.timeline;
  let html = `<div class='mb-3'><b>${eq.name}</b> <span class='badge bg-info'>${eq.kategori}</span> <span class='badge bg-secondary'>${eq.sorumlu}</span></div>`;
  html += `<div class='timeline'>`;
  timeline.forEach((ev,i) => {
    let icon = 'fa-cogs', dot = 'diger', badge = 'badge-done';
    if(ev.tip==='bakim') { icon='fa-tools'; dot='bakim'; badge=ev.gelecek?'badge-upcoming':'badge-done'; }
    if(ev.tip==='test') { icon='fa-vial'; dot='test'; badge=ev.gelecek?'badge-upcoming':'badge-done'; }
    if(ev.tip==='ariza') { icon='fa-exclamation-triangle'; dot='ariza'; badge='badge-overdue'; }
    if(ev.tip==='tasinma') { icon='fa-truck'; dot='tasinma'; badge='badge-done'; }
    if(ev.tip==='kullanim') { icon='fa-user-cog'; dot='kullanim'; badge='badge-done'; }
    html += `
      <div class='timeline-event'>
        <div class='timeline-dot ${dot}'><i class='fas ${icon}'></i></div>
        <div class='timeline-content'>
          <div class='timeline-date'><span class='badge ${badge}'>${ev.tarih}</span></div>
          <div class='timeline-title'>${ev.baslik}</div>
          <div class='timeline-desc'>${ev.aciklama}</div>
          <div class='timeline-person'><i class='fas fa-user me-1'></i>${ev.yapan}</div>
        </div>
      </div>
    `;
  });
  html += `</div>`;
  // Öneri ve mevcut durum
  const last = timeline.filter(ev=>!ev.gelecek).slice(-1)[0];
  const next = timeline.find(ev=>ev.gelecek);
  html += `<div class='mt-3'>
    <div class='alert alert-secondary mb-2'><b>Son Yapılan İşlem:</b> ${last.baslik} (${last.tarih})</div>`;
  if(next) {
    html += `<div class='alert alert-warning'><b>Planlanan İşlem:</b> ${next.baslik} (${next.tarih})<br><span class='small text-muted'>Bu işlem için hazırlık yapınız.</span></div>`;
  } else {
    html += `<div class='alert alert-success'><b>Tüm zorunlu işlemler güncel.</b></div>`;
  }
  html += `</div>`;
  document.getElementById('timelineBody').innerHTML = html;
  // Timeline animasyonu
  setTimeout(()=>{
    document.querySelectorAll('.timeline-event').forEach((el,i)=>{
      setTimeout(()=>el.classList.add('visible'), 120*i);
    });
  }, 100);
}
renderEquipmentList();
document.getElementById('filterForm').onsubmit = function(e){e.preventDefault();renderEquipmentList();document.getElementById('timelineBody').innerHTML='<div class="alert alert-info">Bir ekipman seçin ve geçmiş/gelecek tüm önemli olayları burada görün.</div>';};
['searchInput','kategoriFilter','islemFilter','sorumluFilter'].forEach(id=>{
  document.getElementById(id).onchange = function(){renderEquipmentList();document.getElementById('timelineBody').innerHTML='<div class="alert alert-info">Bir ekipman seçin ve geçmiş/gelecek tüm önemli olayları burada görün.</div>';};
});
</script>
@endpush
@endsection