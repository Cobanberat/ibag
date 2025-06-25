@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body { background: #f8fafc; }
.equip-header { background:linear-gradient(90deg,#6366f1 0%,#43e97b 100%);color:#fff;border-radius:1.2em;box-shadow:0 4px 24px #d1d9e6;padding:2em 1.5em 1.2em 1.5em;margin-bottom:2em;display:flex;flex-direction:column;align-items:flex-start;position:relative;overflow:hidden;animation:fadeInDown .8s ease-out; }
.equip-header h2 { font-size:2.1rem;font-weight:800;margin-bottom:.3em;letter-spacing:-1px; }
.equip-header p { font-size:1.1rem;font-weight:400;opacity:.93; }
.equip-kpi-row { display:flex;gap:1.2em;margin-bottom:2em;flex-wrap:wrap; }
.equip-kpi-card { flex:1 1 180px;min-width:170px;max-width:100%;background:linear-gradient(135deg,#43e97b 0%,#6366f1 100%);color:#fff;border-radius:1.2em;box-shadow:0 4px 24px #d1d9e6;padding:1.2em 1em 1em 1em;display:flex;flex-direction:column;align-items:center;position:relative;transition:all .3s cubic-bezier(0.4,0,0.2,1);cursor:pointer;overflow:visible;animation:fadeInUp .7s cubic-bezier(.4,2,.6,1) both; }
.equip-kpi-card:nth-child(1) { animation-delay:0.1s; }
.equip-kpi-card:nth-child(2) { animation-delay:0.2s; }
.equip-kpi-card:nth-child(3) { animation-delay:0.3s; }
.equip-kpi-card:nth-child(4) { animation-delay:0.4s; }
.equip-kpi-card:hover { transform:translateY(-8px) scale(1.05);box-shadow:0 12px 40px rgba(99,102,241,0.3); }
.equip-kpi-card:hover .equip-kpi-icon { background:#fff;color:#6366f1;transform:rotate(360deg); }
.equip-kpi-card .equip-kpi-value { animation:pulse 2s infinite; }
.equip-kpi-icon { width:38px;height:38px;display:flex;align-items:center;justify-content:center;border-radius:50%;font-size:1.3rem;margin-bottom:.3rem;background:#fff1;box-shadow:0 2px 8px #e0e7ef;transition:all .3s cubic-bezier(0.4,0,0.2,1); }
.equip-kpi-value { font-size:1.3rem;font-weight:800;color:#fff;margin-bottom:.1rem;letter-spacing:-1px; }
.equip-kpi-label { font-size:.98rem;color:#e0e7ef;font-weight:500;text-align:center; }
.equip-progress { background:#fff1;border-radius:1em;height:8px;width:100%;margin-top:.5em;overflow:hidden; }
.equip-progress-bar { height:100%;border-radius:1em;background:linear-gradient(90deg,#fff 0%,#f0f0f0 100%);transition:width 1.5s cubic-bezier(0.4,0,0.2,1);position:relative; }
.equip-progress-bar::after { content:'';position:absolute;top:0;left:0;right:0;bottom:0;background:linear-gradient(90deg,transparent 0%,rgba(255,255,255,0.3) 50%,transparent 100%);animation:progressShine 2s ease-in-out infinite; }
.equip-section-title { font-size:1.2em;font-weight:700;color:#6366f1;margin-bottom:.7em;display:flex;align-items:center;gap:.4em; }
.equip-box { background:#fff;border-radius:1.2em;box-shadow:0 2px 12px #e0e7ef;padding:1.2em 1.3em;margin-bottom:1.3em;animation:fadeInUp .7s cubic-bezier(.4,2,.6,1) both;transition:all .3s cubic-bezier(0.4,0,0.2,1);position:relative;overflow:hidden; }
.equip-box::before { content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent 0%,rgba(99,102,241,0.05) 50%,transparent 100%);transition:left .5s ease-out; }
.equip-box:hover::before { left:100%; }
.equip-box:hover { box-shadow:0 8px 32px rgba(99,102,241,0.15);transform:translateY(-3px) scale(1.02); }
.equip-pie-canvas, .equip-bar-canvas, .equip-line-canvas {
  width:100%!important;
  max-width:340px;
  min-width:120px;
  height:auto!important;
  max-height:160px!important;
  min-height:90px!important;
  aspect-ratio:2/1;
  margin:0 auto;
  display:block;
}
.equip-bar-list-row { display:flex;align-items:center;gap:.7em;margin-bottom:.7em;animation:slideInLeft .6s ease-out both; }
.equip-bar-list-row:nth-child(1) { animation-delay:0.1s; }
.equip-bar-list-row:nth-child(2) { animation-delay:0.2s; }
.equip-bar-list-row:nth-child(3) { animation-delay:0.3s; }
.equip-bar-list-row:nth-child(4) { animation-delay:0.4s; }
.equip-bar-list-row:nth-child(5) { animation-delay:0.5s; }
.equip-bar-label { min-width:110px;font-weight:600; }
.equip-bar-outer { flex:1;background:#e0e7ef;border-radius:1em;height:12px;overflow:hidden; }
.equip-bar-inner { height:100%;border-radius:1em;transition:width 1.5s cubic-bezier(0.4,0,0.2,1);position:relative; }
.equip-bar-inner::after { content:'';position:absolute;top:0;left:0;right:0;bottom:0;background:linear-gradient(90deg,transparent 0%,rgba(255,255,255,0.3) 50%,transparent 100%);animation:barShine 2s ease-in-out infinite; }
.equip-badge { font-size:.85em;border-radius:.7em;padding:.1em .7em;font-weight:600;margin-left:.5em;color:#fff;animation:pulse 2s ease-in-out infinite; }
.equip-table th, .equip-table td { padding:.4em .7em; }
.equip-table th { color:#6366f1;background:#f3f6fa;font-weight:700;position:sticky;top:0;z-index:10; }
.equip-table tr { border-bottom:1px solid #e0e7ef;transition:all .2s ease; }
.equip-table tr:hover { background:linear-gradient(90deg,rgba(99,102,241,0.05) 0%,rgba(67,233,123,0.05) 100%);transform:scale(1.01);box-shadow:0 2px 8px rgba(99,102,241,0.1); }
.equip-table tr:last-child { border-bottom:none; }
.equip-table tr:nth-child(even) { background:#f8fafc; }
.equip-avatar { width:28px;height:28px;border-radius:50%;background:#6366f1;color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:700;margin-right:.7em;font-size:1em;transition:all .3s ease; }
.equip-avatar:hover { transform:scale(1.2) rotate(10deg); }
.equip-warning { background:linear-gradient(90deg,#ffc10722 0%,#fffbe6 100%);color:#b8860b;border-radius:.8em;padding:.7em 1em;margin-bottom:1em;font-weight:600;display:flex;align-items:center;gap:.6em;animation:shake 0.5s ease-in-out; }
.equip-advice { background:linear-gradient(90deg,#43e97b22 0%,#e0e7ff 100%);color:#43e97b;border-radius:.8em;padding:.7em 1em;margin-bottom:1em;font-weight:600;display:flex;align-items:center;gap:.6em;animation:bounceIn 0.6s ease-out; }
.equip-filter-bar { background:linear-gradient(120deg,#f8fafc 60%,#e0e7ff 100%);border-radius:1.2em;box-shadow:0 2px 12px #e0e7ef;padding:1em 1.2em;margin-bottom:1.5em;display:flex;flex-wrap:wrap;gap:.8em;align-items:center;animation:slideInDown .6s ease-out; }
.equip-filter-bar .form-control, .equip-filter-bar .form-select { border-radius:.9em;border:1.5px solid #e0e7ef;background:#fff;font-size:1em;min-width:120px;padding:.5em 1em;transition:all .3s ease; }
.equip-filter-bar .form-control:focus, .equip-filter-bar .form-select:focus { border-color:#6366f1;box-shadow:0 2px 12px rgba(99,102,241,0.2);transform:scale(1.02); }
.equip-btn { border-radius:.9em;font-weight:600;min-width:100px;transition:all .3s cubic-bezier(0.4,0,0.2,1);box-shadow:0 2px 12px #e0e7ef;display:flex;align-items:center;gap:.4em;position:relative;overflow:hidden; }
.equip-btn::before { content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent 0%,rgba(255,255,255,0.2) 50%,transparent 100%);transition:left .5s ease-out; }
.equip-btn:hover::before { left:100%; }
.equip-btn:hover { background:#6366f1!important;color:#fff!important;transform:scale(1.05) translateY(-2px);box-shadow:0 8px 25px rgba(99,102,241,0.4); }
.equip-btn:active { transform:scale(0.95); }
.equip-performance-card { background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;border-radius:1em;padding:1em;margin-bottom:1em;text-align:center;animation:fadeInUp .7s ease-out both; }
.equip-performance-card:nth-child(1) { animation-delay:0.1s; }
.equip-performance-card:nth-child(2) { animation-delay:0.2s; }
.equip-performance-card:nth-child(3) { animation-delay:0.3s; }
.equip-performance-score { font-size:2rem;font-weight:800;margin-bottom:.2em; }
.equip-performance-label { font-size:.9rem;opacity:.9; }
.equip-quick-actions { display:flex;gap:.5em;flex-wrap:wrap; }
.equip-quick-btn { background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);color:#fff;border:none;border-radius:.7em;padding:.5em 1em;font-size:.9em;font-weight:600;transition:all .3s ease;cursor:pointer; }
.equip-quick-btn:hover { transform:scale(1.1) translateY(-2px);box-shadow:0 4px 15px rgba(67,233,123,0.4); }
#equipSnackbar { display:none;position:fixed;bottom:30px;right:30px;z-index:9999;background:linear-gradient(135deg,#6366f1 0%,#43e97b 100%);color:#fff;padding:1em 2em;border-radius:1em;box-shadow:0 4px 20px rgba(99,102,241,0.3);font-weight:600;animation:slideInRight .3s ease-out; }
.equip-loading { display:inline-block;width:16px;height:16px;border:2px solid #fff;border-radius:50%;border-top-color:transparent;animation:spin 1s linear infinite;margin-right:.5em; }
@keyframes fadeInUp { 0%{opacity:0;transform:translateY(30px);} 100%{opacity:1;transform:none;} }
@keyframes fadeInDown { 0%{opacity:0;transform:translateY(-30px);} 100%{opacity:1;transform:none;} }
@keyframes slideInLeft { 0%{opacity:0;transform:translateX(-30px);} 100%{opacity:1;transform:none;} }
@keyframes slideInRight { 0%{opacity:0;transform:translateX(30px);} 100%{opacity:1;transform:none;} }
@keyframes slideInDown { 0%{opacity:0;transform:translateY(-20px);} 100%{opacity:1;transform:none;} }
@keyframes pulse { 0%,100%{transform:scale(1);} 50%{transform:scale(1.05);} }
@keyframes shake { 0%,100%{transform:translateX(0);} 25%{transform:translateX(-5px);} 75%{transform:translateX(5px);} }
@keyframes bounceIn { 0%{opacity:0;transform:scale(0.3);} 50%{opacity:1;transform:scale(1.05);} 70%{transform:scale(0.9);} 100%{opacity:1;transform:scale(1);} }
@keyframes shine { 0%{left:-100%;} 100%{left:100%;} }
@keyframes progressShine { 0%{left:-100%;} 100%{left:100%;} }
@keyframes barShine { 0%{left:-100%;} 100%{left:100%;} }
@keyframes float { 0%,100%{transform:translateY(0px);} 50%{transform:translateY(-10px);} }
@keyframes spin { 0%{transform:rotate(0deg);} 100%{transform:rotate(360deg);} }
.dark-mode .equip-box { background:#2d3748;color:#e2e8f0; }
.dark-mode .equip-table th { background:#4a5568;color:#e2e8f0; }
.dark-mode .equip-table tr:nth-child(even) { background:#2d3748; }
.dark-mode .equip-filter-bar { background:linear-gradient(120deg,#2d3748 60%,#4a5568 100%); }
</style>
<div class="container-fluid">
  <div class="equip-header">
    <div class="d-flex align-items-center justify-content-between w-100">
      <div>
        <h2>🚀 Ekipman Analizi Paneli</h2>
        <p>Ekipmanların durumu, kullanımı, arızaları ve performansını modern, canlı ve etkileşimli olarak analiz edin.</p>
      </div>
      <button class="btn btn-outline-light" onclick="toggleEquipDarkMode()" title="Karanlık Mod"><i class="fas fa-moon"></i></button>
    </div>
  </div>
  <div class="equip-filter-bar">
    <input type="text" class="form-control" id="equipFilterDate" placeholder="📅 Tarih Aralığı">
    <select class="form-select" id="equipFilterCategory">
      <option value="">🏷️ Kategori (Tümü)</option>
      <option>⚡ Elektrik</option>
      <option>🏗️ İnşaat</option>
      <option>🔧 Makine</option>
      <option>🔌 Tesisat</option>
    </select>
    <select class="form-select" id="equipFilterStatus">
      <option value="">📊 Durum (Tümü)</option>
      <option>✅ Aktif</option>
      <option>⚠️ Arızalı</option>
      <option>🔧 Bakımda</option>
      <option>📦 Depoda</option>
    </select>
    <button class="btn btn-outline-primary equip-btn" id="clearEquipFiltersBtn"><i class="fas fa-times"></i> Temizle</button>
    <button class="btn btn-primary equip-btn" onclick="exportEquipData()" id="exportBtn"><i class="fas fa-file-csv"></i> CSV İndir</button>
  </div>
  <div class="equip-kpi-row">
    <div class="equip-kpi-card">
      <div class="equip-kpi-icon"><i class="fas fa-cubes"></i></div>
      <div class="equip-kpi-value" id="kpiTotalEquip">120</div>
      <div class="equip-kpi-label">Toplam Ekipman</div>
      <div class="equip-progress"><div class="equip-progress-bar" id="totalEquipBar" style="width:100%;background:#fff;"></div></div>
    </div>
    <div class="equip-kpi-card">
      <div class="equip-kpi-icon"><i class="fas fa-check-circle"></i></div>
      <div class="equip-kpi-value" id="kpiActiveEquip">98</div>
      <div class="equip-kpi-label">Aktif Ekipman</div>
      <div class="equip-progress"><div class="equip-progress-bar" id="activeEquipBar" style="width:82%;background:#fff;"></div></div>
    </div>
    <div class="equip-kpi-card">
      <div class="equip-kpi-icon"><i class="fas fa-exclamation-triangle"></i></div>
      <div class="equip-kpi-value" id="kpiFaultEquip">7</div>
      <div class="equip-kpi-label">Arızalı Ekipman</div>
      <div class="equip-progress"><div class="equip-progress-bar" id="faultEquipBar" style="width:6%;background:#fff;"></div></div>
    </div>
    <div class="equip-kpi-card">
      <div class="equip-kpi-icon"><i class="fas fa-star"></i></div>
      <div class="equip-kpi-value" id="kpiTopEquip">Jeneratör</div>
      <div class="equip-kpi-label">Son 7 Günün En Çok Kullanılanı</div>
      <div class="equip-progress"><div class="equip-progress-bar" id="topEquipBar" style="width:75%;background:#fff;"></div></div>
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="equip-box">
        <div class="equip-section-title"><i class="fas fa-chart-pie"></i> Ekipman Durum Dağılımı</div>
        <canvas id="equipPieChart" class="equip-pie-canvas"></canvas>
      </div>
      <div class="equip-box">
        <div class="equip-section-title"><i class="fas fa-list-ol"></i> En Çok Kullanılan Ekipmanlar</div>
        <div id="equipBarList"></div>
      </div>
      <div class="equip-box">
        <div class="equip-section-title"><i class="fas fa-trophy"></i> Performans Skorları</div>
        <div class="equip-performance-card">
          <div class="equip-performance-score">94%</div>
          <div class="equip-performance-label">Genel Performans</div>
        </div>
        <div class="equip-performance-card">
          <div class="equip-performance-score">87%</div>
          <div class="equip-performance-label">Kullanım Oranı</div>
        </div>
        <div class="equip-performance-card">
          <div class="equip-performance-score">96%</div>
          <div class="equip-performance-label">Çalışma Süresi</div>
        </div>
      </div>
      <div class="equip-box">
        <div class="equip-section-title"><i class="fas fa-exclamation-circle"></i> Düşük Stokta Olanlar</div>
        <table class="equip-table" style="width:100%;font-size:.98em;">
          <thead><tr><th>Ekipman</th><th>Stok</th><th>Uyarı</th></tr></thead>
          <tbody>
            <tr><td>UPS</td><td>2</td><td><span class="equip-badge" style="background:#dc3545;">Kritik</span></td></tr>
            <tr><td>Oksijen</td><td>4</td><td><span class="equip-badge" style="background:#ffc107;">Düşük</span></td></tr>
            <tr><td>Hilti</td><td>6</td><td><span class="equip-badge" style="background:#ffc107;">Düşük</span></td></tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-md-6">
      <div class="equip-box">
        <div class="equip-section-title"><i class="fas fa-chart-line"></i> Arıza Trendleri (6 Ay)</div>
        <canvas id="equipLineChart" class="equip-line-canvas"></canvas>
      </div>
      <div class="equip-box">
        <div class="equip-section-title"><i class="fas fa-history"></i> Son Ekipman Hareketleri</div>
        <table class="equip-table" style="width:100%;font-size:.98em;">
          <thead><tr><th></th><th>Kullanıcı</th><th>Ekipman</th><th>İşlem</th><th>Saat</th></tr></thead>
          <tbody id="equipMoveTable"></tbody>
        </table>
      </div>
      <div class="equip-box">
        <div class="equip-section-title"><i class="fas fa-calendar-alt"></i> Bakım Takvimi</div>
        <div style="font-size:1.1em;font-weight:500;">
          <div style="margin-bottom:.5em;"><span style="background:#6366f1;color:#fff;border-radius:.5em;padding:.2em .6em;font-size:.9em;margin-right:.5em;">15 Haz</span> Jeneratör Bakımı</div>
          <div style="margin-bottom:.5em;"><span style="background:#43e97b;color:#fff;border-radius:.5em;padding:.2em .6em;font-size:.9em;margin-right:.5em;">18 Haz</span> UPS Kontrolü</div>
          <div><span style="background:#ffc107;color:#23272b;border-radius:.5em;padding:.2em .6em;font-size:.9em;margin-right:.5em;">22 Haz</span> Hilti Bakımı</div>
        </div>
      </div>
      <div class="equip-box">
        <div class="equip-section-title"><i class="fas fa-bolt"></i> Hızlı İşlemler</div>
        <div class="equip-quick-actions">
          <button class="equip-quick-btn" onclick="quickAction('add')"><i class="fas fa-plus"></i> Ekipman Ekle</button>
          <button class="equip-quick-btn" onclick="quickAction('maintenance')"><i class="fas fa-tools"></i> Bakım Planla</button>
          <button class="equip-quick-btn" onclick="quickAction('report')"><i class="fas fa-file-alt"></i> Rapor Oluştur</button>
          <button class="equip-quick-btn" onclick="quickAction('alert')"><i class="fas fa-bell"></i> Uyarı Ayarla</button>
        </div>
      </div>
      <div class="equip-warning"><i class="fas fa-exclamation-triangle"></i> Jeneratör arızaları artıyor, bakım planı oluşturun!</div>
      <div class="equip-advice"><i class="fas fa-lightbulb"></i> Oksijen ekipmanında stok azaldı, sipariş verin.</div>
    </div>
  </div>
  <div id="equipSnackbar">Veriler güncellendi!</div>
</div>
<script>
// Sayma animasyonu
function animateEquipCount(id, target, suffix = '', duration = 1200) {
  let el = document.getElementById(id);
  let start = Math.max(1, Math.floor(target * 0.7));
  let startTime = null;
  function animate(ts) {
    if (!startTime) startTime = ts;
    let progress = Math.min((ts - startTime) / duration, 1);
    el.innerText = Math.floor(start + (target-start)*progress) + suffix;
    if (progress < 1) requestAnimationFrame(animate);
    else el.innerText = target + suffix;
  }
  el.innerText = start + suffix;
  requestAnimationFrame(animate);
}
// KPI animasyonları
animateEquipCount('kpiTotalEquip', 120);
animateEquipCount('kpiActiveEquip', 98);
animateEquipCount('kpiFaultEquip', 7);
// Pie chart
const equipPieChart = new Chart(document.getElementById('equipPieChart'), {
  type: 'pie',
  data: {
    labels: ['Aktif','Arızalı','Bakımda','Depoda'],
    datasets: [{
      data: [98,7,8,7],
      backgroundColor: ['#43e97b','#dc3545','#ffc107','#6366f1']
    }]
  },
  options: {
    responsive:true,
    maintainAspectRatio:true,
    plugins:{legend:{display:true}}
  }
});
// Bar list
const equipBarData = [
  {name:'Jeneratör', count:32, color:'#43e97b'},
  {name:'UPS', count:28, color:'#6366f1'},
  {name:'Oksijen', count:22, color:'#ffc107'},
  {name:'Hilti', count:18, color:'#17a2b8'},
  {name:'Diğer', count:12, color:'#b6c2e1'}
];
document.getElementById('equipBarList').innerHTML = equipBarData.map(e=>`
  <div class="equip-bar-list-row">
    <span class="equip-bar-label">${e.name}</span>
    <div class="equip-bar-outer"><div class="equip-bar-inner" style="width:0;background:${e.color};"></div></div>
    <span class="equip-badge" style="background:${e.color};">${e.count}</span>
  </div>
`).join('');
setTimeout(()=>{
  document.querySelectorAll('.equip-bar-inner').forEach((el,i)=>{
    el.style.width = equipBarData[i].count*2+'%';
  });
}, 200);
// Line chart
const equipLineChart = new Chart(document.getElementById('equipLineChart'), {
  type: 'line',
  data: {
    labels: ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran'],
    datasets: [{
      label: 'Arıza',
      data: [2,3,4,5,6,7],
      borderColor: '#dc3545',
      backgroundColor: 'rgba(220,53,69,0.08)',
      tension: 0.4,
      fill: true,
      pointRadius: 4,
      pointHoverRadius: 7
    }]
  },
  options: {
    responsive:true,
    maintainAspectRatio:true,
    plugins:{legend:{display:false}}
  }
});
// Son hareketler tablosu
const equipMoves = [
  {user:'admin', avatar:'A', equip:'Jeneratör', type:'Çıkış', time:'09:12'},
  {user:'ayse', avatar:'A', equip:'UPS', type:'Giriş', time:'10:05'},
  {user:'mehmet', avatar:'M', equip:'Oksijen', type:'Bakım', time:'11:20'},
  {user:'fatma', avatar:'F', equip:'Hilti', type:'Arıza', time:'12:30'},
  {user:'admin', avatar:'A', equip:'UPS', type:'Çıkış', time:'13:00'}
];
document.getElementById('equipMoveTable').innerHTML = equipMoves.map(d=>`
  <tr>
    <td><span class='equip-avatar'>${d.avatar}</span></td>
    <td>${d.user}</td>
    <td>${d.equip}</td>
    <td>${d.type}</td>
    <td>${d.time}</td>
  </tr>
`).join('');
// Karanlık mod toggle
function toggleEquipDarkMode() {
  document.body.classList.toggle('dark-mode');
  showEquipSnackbar(document.body.classList.contains('dark-mode') ? '🌙 Karanlık mod aktif!' : '☀️ Aydınlık mod aktif!');
}
// Snackbar
function showEquipSnackbar(msg) {
  let sb = document.getElementById('equipSnackbar');
  sb.innerText = msg;
  sb.style.display = 'block';
  setTimeout(()=>{sb.style.display='none';}, 2200);
}
// Export fonksiyonu
function exportEquipData() {
  let btn = document.getElementById('exportBtn');
  let originalText = btn.innerHTML;
  btn.innerHTML = '<span class="equip-loading"></span>İndiriliyor...';
  btn.disabled = true;
  
  setTimeout(() => {
    btn.innerHTML = originalText;
    btn.disabled = false;
    showEquipSnackbar('✅ Ekipman verileri CSV olarak indirildi!');
  }, 2000);
}
// Hızlı işlemler
function quickAction(action) {
  const actions = {
    add: '➕ Yeni ekipman ekleme formu açılıyor...',
    maintenance: '🔧 Bakım planlama sayfasına yönlendiriliyorsunuz...',
    report: '📊 Rapor oluşturuluyor...',
    alert: '🔔 Uyarı ayarları açılıyor...'
  };
  showEquipSnackbar(actions[action] || 'İşlem başlatılıyor...');
}
// Filtreleme
document.getElementById('clearEquipFiltersBtn').addEventListener('click', ()=>{
  document.getElementById('equipFilterDate').value = '';
  document.getElementById('equipFilterCategory').value = '';
  document.getElementById('equipFilterStatus').value = '';
  showEquipSnackbar('🧹 Filtreler temizlendi!');
});
// Filtre değişikliklerini dinle
['equipFilterDate', 'equipFilterCategory', 'equipFilterStatus'].forEach(id => {
  document.getElementById(id).addEventListener('change', () => {
    showEquipSnackbar('🔍 Filtreler uygulandı!');
  });
});
// Sayfa yüklendiğinde animasyonları başlat
document.addEventListener('DOMContentLoaded', () => {
  setTimeout(() => {
    showEquipSnackbar('🎉 Ekipman analizi paneli hazır!');
  }, 1000);
});
</script>
@endsection 