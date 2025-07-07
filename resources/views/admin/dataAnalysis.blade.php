@extends('layouts.admin')
@section('content')

<style>
:root {
  --soft-shadow: 0 4px 24px 0 #d1d9e6;
  --soft-radius: 1.2em;
  --soft-bg: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
  --soft-card: #fff;
  --soft-primary: #6366f1;
  --soft-success: #43e97b;
  --soft-warning: #ffc107;
  --soft-info: #17a2b8;
  --soft-gray: #e0e7ef;
  --soft-gradient: linear-gradient(90deg, #6366f1 0%, #43e97b 100%);
}
.dashboard-header {
  background: var(--soft-gradient);
  color: #fff;
  border-radius: var(--soft-radius);
  box-shadow: var(--soft-shadow);
  padding: 2.2em 1.5em 1.2em 1.5em;
  margin-bottom: 2.2em;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  position: relative;
  overflow: hidden;
}
.dashboard-header h2 {
  font-size: 2.1rem;
  font-weight: 800;
  margin-bottom: .3em;
  letter-spacing: -1px;
}
.dashboard-header p {
  font-size: 1.1rem;
  font-weight: 400;
  opacity: .93;
}
.dashboard-kpi-row,
.row {
  display: flex;
  flex-wrap: wrap;
  align-items: stretch;
}
.dashboard-kpi-card,
.dashboard-box,
.soft-chart-box,
.calendar-box {
  height: 100%;
  min-height: 220px;
  display: flex;
  flex-direction: column;
}
.dashboard-kpi-card {
  flex: 1 1 200px;
  min-width: 180px;
  max-width: 100%;
  background: linear-gradient(135deg, #6366f1 0%, #43e97b 100%);
  border: none;
  border-radius: var(--soft-radius);
  box-shadow: var(--soft-shadow);
  padding: 1.5rem 1.1rem 1.1rem 1.1rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  position: relative;
  transition: transform .18s, box-shadow .18s;
  cursor: pointer;
  overflow: visible;
  color: #fff;
  animation: kpiFadeIn .7s cubic-bezier(.4,2,.6,1) both;
}
@keyframes kpiFadeIn {
  0% { opacity: 0; transform: translateY(30px) scale(.95); }
  100% { opacity: 1; transform: none; }
}
.dashboard-kpi-card:hover {
  transform: translateY(-6px) scale(1.05);
  box-shadow: 0 12px 32px 0 #b6c2e1;
}
.dashboard-kpi-icon {
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  font-size: 1.2rem;
  margin-bottom: .5rem;
  background: rgba(255,255,255,0.13);
  box-shadow: 0 2px 12px #e0e7ef;
  border: 2px solid #fff2;
  transition: background .2s;
}
.dashboard-kpi-card:hover .dashboard-kpi-icon {
  background: #fff;
  color: var(--soft-primary);
}
.dashboard-kpi-value {
  font-size: 2.1rem;
  font-weight: 800;
  color: #fff;
  margin-bottom: .1rem;
  letter-spacing: -1px;
  text-shadow: 0 2px 8px #6366f144;
}
.dashboard-kpi-label {
  font-size: 1.08rem;
  color: #e0e7ef;
  font-weight: 500;
  text-align: center;
  opacity: .93;
}
.dashboard-card, .dashboard-box {
  background: var(--soft-card);
  border-radius: var(--soft-radius);
  box-shadow: var(--soft-shadow);
  padding: 1em 1.1em;
  margin-bottom: 1em;
}
.dashboard-card h6, .dashboard-box h6 {
  font-weight: 600;
  font-size: 1.01rem;
  margin-bottom: .7em;
  display: flex;
  align-items: center;
  gap: .4em;
}
.dashboard-card h6 i, .dashboard-box h6 i { color: var(--soft-primary); }
.soft-chart-box {
  background: #fff;
  border-radius: var(--soft-radius);
  box-shadow: var(--soft-shadow);
  padding: 1.2em 1.2em 1.7em 1.2em;
  margin-bottom: 1.3em;
  transition: box-shadow .18s, transform .18s;
  min-width: 0;
  position: relative;
  overflow: hidden;
}
.soft-chart-box:hover {
  box-shadow: 0 8px 32px #b6c2e1;
  transform: translateY(-3px) scale(1.01);
}
.soft-chart-title {
  font-size: 1.13em;
  font-weight: 700;
  color: var(--soft-primary);
  margin-bottom: .7em;
  display: flex;
  align-items: center;
  gap: .4em;
  letter-spacing: -.5px;
}
.soft-chart-canvas {
  display: block;
  width: 100%;
  max-width: 420px;
  height: 110px;
  min-height: 80px;
  margin: 0 auto;
  background: linear-gradient(90deg,#f8fafc 0,#e0e7ff 100%);
  border-radius: .7em;
  box-shadow: 0 1px 8px #e0e7ef;
}
.filter-bar {
  background: var(--soft-bg);
  border-radius: .9em;
  box-shadow: 0 1px 6px #e0e7ef;
  padding: .5em .8em;
  margin-bottom: 1.2em;
  display: flex;
  flex-wrap: wrap;
  gap: .5em;
  align-items: center;
}
.filter-bar .form-select, .filter-bar .form-control {
  border-radius: .9em;
  border: 1px solid #e0e7ef;
  background: #fff;
  font-size: .95rem;
  min-width: 110px;
}
.filter-bar .btn {
  border-radius: .9em;
  font-weight: 500;
  min-width: 80px;
}
.mini-summary-box {
  background: linear-gradient(90deg,#43e97b 0,#6366f1 100%);
  border-radius: .9em;
  box-shadow: 0 1px 8px #e0e7ef;
  padding: 1.1em 1.5em;
  margin-bottom: 1.3em;
  font-size: 1.08em;
  color: #fff;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: .7em;
  letter-spacing: -.2px;
}
.export-btn {
  border-radius: .9em;
  background: var(--soft-primary);
  color: #fff;
  border: none;
  padding: .4em 1em;
  font-size: .97em;
  font-weight: 500;
  transition: background .2s;
  display: flex;
  align-items: center;
  gap: .4em;
  box-shadow: 0 1px 6px #e0e7ef;
}
.export-btn:hover { background: #4b4be1; }
.last-table-box {
  background: #fff;
  border-radius: .9em;
  box-shadow: 0 1px 6px #e0e7ef;
  padding: .7em 1em;
  margin-bottom: 1em;
}
.last-table-box table {
  width: 100%;
  font-size: .95em;
}
.last-table-box th, .last-table-box td {
  padding: .3em .5em;
  text-align: left;
}
.last-table-box th {
  color: #6366f1;
  font-weight: 600;
  background: #f3f6fa;
  border-top: none;
}
.last-table-box tr {
  border-bottom: 1px solid #e0e7ef;
}
.last-table-box tr:last-child { border-bottom: none; }
.user-list-box .user-item {
  display: flex;
  align-items: center;
  gap: .7em;
  margin-bottom: .4em;
  font-size: .97em;
}
.user-list-box .user-item:last-child { margin-bottom: 0; }
.user-status-badge {
  font-size: .85em;
  border-radius: 1em;
  padding: .15em .6em;
  font-weight: 500;
  margin-left: .5em;
}
.user-status-online { background: #43e97b; color: #fff; }
.user-status-offline { background: #b6c2e1; color: #fff; }
.user-status-wait { background: #ffc107; color: #23272b; }
.system-status-box {
  display: flex;
  gap: .7em;
  align-items: center;
  font-size: .97em;
}
.system-status-dot {
  width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-right: .2em;
}
.status-ok { background: #43e97b; }
.status-down { background: #dc3545; }
.status-warn { background: #ffc107; }
@media (max-width: 1200px) {
  .dashboard-kpi-row { flex-direction: column; gap: .7rem; }
}
@keyframes fadeInUp { 0%{opacity:0;transform:translateY(30px);} 100%{opacity:1;transform:none;} }
.dashboard-box, .dashboard-kpi-card { animation: fadeInUp .7s cubic-bezier(.4,2,.6,1) both; }
.dashboard-kpi-card:hover .dashboard-kpi-icon { transform: scale(1.15); transition: transform .18s; }
.progress-outer { background:#e0e7ef; border-radius:1em; height:10px; width:100%; margin-top:.5em; }
.progress-inner { background:linear-gradient(90deg,#43e97b,#6366f1); height:100%; border-radius:1em; width:0; transition:width 1.2s cubic-bezier(.4,2,.6,1);}
.badge-status { font-size:.85em; border-radius:.7em; padding:.1em .7em; font-weight:600; margin-left:.5em;}
.badge-high { background:#43e97b; color:#fff;}
.badge-low { background:#ffc107; color:#23272b;}
.badge-critical { background:#dc3545; color:#fff;}
.avatar-circle { width:28px; height:28px; border-radius:50%; background:#6366f1; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-weight:700; margin-right:1em; font-size:1em;}
.table-hover tbody tr:hover { background: #e0e7ff44; transition: background .2s; }
#snackbar { display:none;position:fixed;bottom:30px;right:30px;z-index:9999;background:#6366f1;color:#fff;padding:1em 2em;border-radius:1em;box-shadow:0 2px 12px #b6c2e1;font-weight:600; }
.dark-mode { background:linear-gradient(120deg,#23272b 0%,#6366f1 100%) !important; color:#fff !important; }
.dark-mode .dashboard-header, .dark-mode .dashboard-box, .dark-mode .dashboard-kpi-card { background: #23272b !important; color: #fff !important; }
.dark-mode .dashboard-kpi-icon { background: #23272b !important; color: #43e97b !important; }
.input-group {
  display: flex;
  align-items: center;
  gap: .5em;
}
.form-control, .form-select {
  border-radius: .9em !important;
  border: 1.5px solid #e0e7ef !important;
  background: #fff !important;
  font-size: 1.01em;
  min-width: 120px;
  padding-left: 2.2em !important;
  transition: box-shadow .18s, border .18s;
  box-shadow: 0 1px 6px #e0e7ef22;
  position: relative;
}
.form-control:focus, .form-select:focus {
  border-color: #6366f1 !important;
  box-shadow: 0 2px 12px #6366f122;
}

.btn, .export-btn {
  border-radius: .9em !important;
  font-weight: 600;
  min-width: 100px;
  transition: background .18s, transform .13s, box-shadow .13s;
  box-shadow: 0 1px 6px #e0e7ef;
  display: flex;
  align-items: center;
  gap: .4em;
}
.btn:hover, .export-btn:hover {
  background: #6366f1 !important;
  color: #fff !important;
  transform: scale(1.07);
  box-shadow: 0 4px 18px #6366f1aa;
}
.table-hover tbody tr:hover { background: #e0e7ff44; transition: background .2s; }
.fade-in { animation: fadeInUp .7s cubic-bezier(.4,2,.6,1) both; }
.sla-bar-row { display:flex;align-items:center;gap:.7em;margin-bottom:.7em; }
.sla-bar-label { min-width:110px;font-weight:600; }
.sla-bar-outer { flex:1;background:#e0e7ef;border-radius:1em;height:12px;overflow:hidden; }
.sla-bar-inner { height:100%;border-radius:1em;transition:width 1.2s; }
.sla-badge { font-size:.85em;border-radius:.7em;padding:.1em .7em;font-weight:600;margin-left:.5em;color:#fff; }
.modern-table tbody tr { transition: box-shadow .18s, transform .18s; }
.modern-table tbody tr:hover { background: #e0e7ff44; box-shadow: 0 2px 12px #6366f122; transform: scale(1.01);}
.modern-table tbody tr:nth-child(even) { background: #f8fafc; }
.modern-table thead th { position:sticky;top:0;background:#e0e7ff;z-index:2; }
.activity-bar-row { display:flex;align-items:center;gap:.7em;margin-bottom:.7em; }
.activity-bar-label { min-width:90px;font-weight:600; }
.activity-bar-outer { flex:1;background:#e0e7ef;border-radius:1em;height:10px;overflow:hidden; }
.activity-bar-inner { height:100%;border-radius:1em;transition:width 1.2s; }
.calendar-box { background:linear-gradient(120deg,#f8fafc 60%,#e0e7ff 100%);border-radius:1.2em;box-shadow:0 2px 12px 0 #e0e7ef;padding:1.1em 1.2em;margin-bottom:1.2em; }
.calendar-date { display:inline-block;background:#6366f1;color:#fff;border-radius:.7em;padding:.2em .8em;font-weight:600;margin-right:.5em; }
.calendar-box > div { margin-bottom:0.6em; }
.active-user-row { display: flex; align-items: center; margin-bottom: 0.7em; }
</style>
<div class="container-fluid" id="mainPanel">
  <div class="dashboard-header mb-4">
    <div class="d-flex align-items-center justify-content-between w-100">
      <div>
        <h2 style="color:#fff;font-weight:800;letter-spacing:-1px;text-shadow:0 2px 8px #6366f144;">Veri Analiz Paneli</h2>
        <p style="color:#fff;font-size:1.1em;opacity:.98;text-shadow:0 2px 8px #6366f144;">İstatistikleri ve kullanıcı aktivitelerini modern, canlı ve etkileşimli olarak görüntüleyin.</p>
      </div>
    </div>
  </div>
  <div class="dashboard-box mb-4" style="background:linear-gradient(90deg,#e0e7ff 0%,#f8fafc 100%);color:#23272b;">
    <h3 style="font-size:1.5em;font-weight:800;margin-bottom:.2em;letter-spacing:-1px;color:#6366f1;">Hoş geldin, <span id="welcomeUser">admin</span>!</h3>
    <div style="font-size:1.1em;font-weight:500;opacity:.96;">Bugün <b id="todayAction">5</b> işlem gerçekleştirdin. Harika bir performans, böyle devam et!</div>
  </div>
  <!-- Filtre Barı -->
  <div class="filter-bar mb-4 input-group">
    <input type="text" class="form-control" id="filterDateRange" placeholder="Tarih Aralığı Seçin" readonly style="max-width:220px;">
    <select class="form-select" id="filterUser">
      <option value="">Kullanıcı (Tümü)</option>
      <option>admin</option>
      <option>ayse</option>
      <option>mehmet</option>
      <option>fatma</option>
    </select>
    <select class="form-select" id="filterType">
      <option value="">İşlem Tipi (Tümü)</option>
      <option>Giriş</option>
      <option>Rapor</option>
      <option>Şifre</option>
      <option>Profil</option>
    </select>
    <button class="btn btn-outline-primary" id="clearFiltersBtn"><i class="fas fa-times"></i> Temizle</button>
    <button class="export-btn" onclick="downloadCSV()"><i class="fas fa-file-csv"></i> CSV İndir</button>
  </div>
  <!-- KPI Kartları -->
  <div class="dashboard-kpi-row">
    <div class="dashboard-kpi-card">
      <div class="dashboard-kpi-icon"><i class="fas fa-chart-bar"></i></div>
      <div class="dashboard-kpi-value" id="kpiVisit">1200</div>
      <div class="dashboard-kpi-label">Toplam Ziyaret <span class="badge-status badge-high" id="visitTrend">+12%</span></div>
      <div class="progress-outer"><div class="progress-inner" id="visitBar" style="width:80%"></div></div>
    </div>
    <div class="dashboard-kpi-card">
      <div class="dashboard-kpi-icon"><i class="fas fa-users"></i></div>
      <div class="dashboard-kpi-value" id="kpiActive">87</div>
      <div class="dashboard-kpi-label">Aktif Kullanıcı <span class="badge-status badge-high" id="activeTrend">+8%</span></div>
      <div class="progress-outer"><div class="progress-inner" id="activeBar" style="width:87%"></div></div>
    </div>
    <div class="dashboard-kpi-card">
      <div class="dashboard-kpi-icon"><i class="fas fa-clock"></i></div>
      <div class="dashboard-kpi-value" id="kpiAvgTime">14 dk</div>
      <div class="dashboard-kpi-label">Ortalama Oturum <span class="badge-status badge-low" id="avgTimeTrend">-2%</span></div>
      <div class="progress-outer"><div class="progress-inner" id="avgTimeBar" style="width:60%"></div></div>
    </div>
    <div class="dashboard-kpi-card">
      <div class="dashboard-kpi-icon"><i class="fas fa-user-plus"></i></div>
      <div class="dashboard-kpi-value" id="kpiNewUser">32</div>
      <div class="dashboard-kpi-label">Yeni Kullanıcı <span class="badge-status badge-high" id="newUserTrend">+5%</span></div>
      <div class="progress-outer"><div class="progress-inner" id="newUserBar" style="width:40%"></div></div>
    </div>
  </div>
  <!-- Yönetimsel ve Kullanıcı Analiz Kutuları -->
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="dashboard-box" style="background:linear-gradient(120deg,#43e97b22 0%,#6366f122 100%);">
        <h6>
          <i class="fas fa-user-check"></i> Kullanıcı Katılım Oranı
          <span class="badge-status badge-high" id="userParticipationBadge">Yüksek</span>
          <i class="fas fa-info-circle" style="cursor:pointer;" title="Sisteme giriş yapan kullanıcı oranı"></i>
        </h6>
        <div style="font-size:1.5em;font-weight:700;color:#43e97b;"><span id="userParticipation">87%</span></div>
        <div class="progress-outer"><div class="progress-inner" id="userParticipationBar" style="width:87%"></div></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="dashboard-box" style="background:linear-gradient(120deg,#6366f122 0%,#43e97b22 100%);">
        <h6><i class="fas fa-stopwatch"></i> Ortalama Oturum Süresi</h6>
        <div style="font-size:1.5em;font-weight:700;color:#6366f1;"><span id="avgSession">14</span> dk</div>
        <div class="progress-outer"><div class="progress-inner" id="avgSessionBar" style="width:60%"></div></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="dashboard-box" style="background:linear-gradient(120deg,#ffc10722 0%,#6366f122 100%);">
        <h6><i class="fas fa-tasks"></i> Bekleyen Onay</h6>
        <div style="font-size:1.5em;font-weight:700;color:#ffc107;"><span id="pendingApprovals">3</span></div>
        <span class="badge-status badge-critical" id="pendingBadge">Kritik</span>
      </div>
    </div>
    <div class="col-md-3">
      <div class="dashboard-box" style="background:linear-gradient(120deg,#6366f122 0%,#17a2b822 100%);">
        <h6><i class="fas fa-star"></i> Kullanıcı Memnuniyet Skoru</h6>
        <div style="font-size:1.5em;font-weight:700;color:#17a2b8;"><span id="satisfactionScore">4.6</span>/5</div>
        <div class="progress-outer"><div class="progress-inner" id="satisfactionBar" style="width:92%"></div></div>
      </div>
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="dashboard-box" style="background:linear-gradient(120deg,#6366f122 0%,#43e97b22 100%);">
        <h6><i class="fas fa-users"></i> Son 7 Günün En Aktif Kullanıcıları</h6>
        <ul id="activeUsersList" style="list-style:none;padding-left:0;margin-bottom:0;font-size:1.08em;">
          <li class="active-user-row"><span class='avatar-circle'>A</span><b>admin</b> <span style='color:#43e97b;font-weight:600;'>12 işlem</span></li>
          <li class="active-user-row"><span class='avatar-circle'>A</span><b>ayse</b> <span style='color:#43e97b;font-weight:600;'>10 işlem</span></li>
          <li class="active-user-row"><span class='avatar-circle'>M</span><b>mehmet</b> <span style='color:#43e97b;font-weight:600;'>8 işlem</span></li>
          <li class="active-user-row"><span class='avatar-circle'>F</span><b>fatma</b> <span style='color:#43e97b;font-weight:600;'>7 işlem</span></li>
        </ul>
      </div>
    </div>
    <div class="col-md-4">
      <div class="dashboard-box" style="background:linear-gradient(120deg,#43e97b22 0%,#ffc10722 100%);">
        <h6><i class="fas fa-headset"></i> Destek Talebi Yoğunluğu <i class="fas fa-info-circle" style="cursor:pointer;" title="Destek taleplerinin en yoğun olduğu saat"></i></h6>
        <div id="supportDemand" style="font-size:1.1em;font-weight:500;color:#43e97b;">En yoğun saat: 14:00-15:00, Toplam: 6 talep</div>
      </div>
    </div>
    <div class="col-md-2">
      <div class="dashboard-box" style="background:linear-gradient(120deg,#6366f122 0%,#43e97b22 100%);">
        <h6><i class="fas fa-server"></i> Sistem Uptime</h6>
        <div style="font-size:1.3em;font-weight:700;color:#43e97b;"><span id="systemUptime">99.7%</span></div>
        <div class="progress-outer"><div class="progress-inner" id="uptimeBar" style="width:99.7%"></div></div>
      </div>
    </div>
    <div class="col-md-2">
      <div class="dashboard-box" style="background:linear-gradient(120deg,#6366f122 0%,#ffc10722 100%);">
        <h6><i class="fas fa-bell"></i> Bildirim Okunma Oranı</h6>
        <div style="font-size:1.3em;font-weight:700;color:#ffc107;"><span id="notificationRead">82%</span></div>
        <div class="progress-outer"><div class="progress-inner" id="notificationBar" style="width:82%"></div></div>
      </div>
    </div>
  </div>
  <!-- Yeni yönetimsel kutular -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="dashboard-box fade-in" style="background:linear-gradient(120deg,#6366f122 0%,#ffc10722 100%);">
        <h6><i class="fas fa-calendar-day"></i> En Çok İşlem Yapılan Gün</h6>
        <div style="font-size:1.2em;font-weight:700;color:#6366f1;">Çarşamba <span class="badge-status badge-high">320 işlem</span></div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="dashboard-box fade-in" style="background:linear-gradient(120deg,#43e97b22 0%,#6366f122 100%);">
        <h6><i class="fas fa-tasks"></i> En Çok Kullanılan İşlem Tipi</h6>
        <div style="font-size:1.2em;font-weight:700;color:#43e97b;">Giriş <span class="badge-status badge-high">410 kez</span></div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="dashboard-box fade-in" style="background:linear-gradient(120deg,#dc354522 0%,#6366f122 100%);">
        <h6><i class="fas fa-exclamation-triangle"></i> Sistem Uyarısı</h6>
        <div style="font-size:1.1em;font-weight:600;color:#dc3545;">Bekleyen onaylar kritik seviyede!</div>
      </div>
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="dashboard-box fade-in" style="background:linear-gradient(120deg,#6366f122 0%,#43e97b22 100%);">
        <h6><i class="fas fa-lightbulb"></i> Kullanıcıya Özel Öneri</h6>
        <div style="font-size:1.1em;font-weight:500;color:#43e97b;">Daha fazla rapor oluştur, puanını yükselt!</div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="dashboard-box fade-in" style="background:linear-gradient(120deg,#6366f122 0%,#ffc10722 100%);">
        <h6><i class="fas fa-bell"></i> Son Bildirimler</h6>
        <ul style="list-style:none;padding-left:0;margin-bottom:0;font-size:1.08em;">
          <li><i class="fas fa-check-circle text-success"></i> Profiliniz başarıyla güncellendi.</li>
          <li><i class="fas fa-exclamation-circle text-warning"></i> Şifreniz 5 gün sonra değişmeli.</li>
          <li><i class="fas fa-info-circle text-info"></i> Yeni rapor özelliği eklendi.</li>
        </ul>
      </div>
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="dashboard-box fade-in" style="background:linear-gradient(120deg,#f8fafc 60%,#e0e7ff 100%);">
        <h6><i class="fas fa-percentage"></i> SLA Yüzdelik Durumları</h6>
        <div id="slaBarList"></div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="dashboard-box fade-in" style="background:linear-gradient(120deg,#f8fafc 60%,#e0e7ff 100%);">
        <h6><i class="fas fa-bolt"></i> Kullanıcı Aktivite Zaman Çizelgesi</h6>
        <div id="activityBarList"></div>
      </div>
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="dashboard-box fade-in" style="background:linear-gradient(120deg,#f8fafc 60%,#e0e7ff 100%);">
        <h6><i class="fas fa-bullseye"></i> Bu Ayki Hedefin</h6>
        <div style="font-size:1.1em;font-weight:500;">20 işlem / <span style="color:#43e97b;font-weight:700;">15 tamamlandı</span></div>
        <div class="progress-outer"><div class="progress-inner" id="goalBar" style="width:75%"></div></div>
        <span class="badge-status badge-high" style="margin-top:.5em;">%75 Tamamlandı</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="dashboard-box fade-in calendar-box">
        <h6><i class="fas fa-calendar-alt"></i> Yaklaşan Tarihler</h6>
        <div><span class="calendar-date">12 Haz</span> Haftalık Toplantı</div>
        <div><span class="calendar-date">15 Haz</span> Rapor Teslimi</div>
        <div><span class="calendar-date">20 Haz</span> Sistem Bakımı</div>
      </div>
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="dashboard-box fade-in" style="background:linear-gradient(120deg,#f8fafc 60%,#e0e7ff 100%);">
        <h6><i class="fas fa-bell"></i> Son Bildirimler</h6>
        <ul style="list-style:none;padding-left:0;margin-bottom:0;font-size:1.08em;">
          <li><i class="fas fa-check-circle text-success"></i> Profiliniz başarıyla güncellendi.</li>
          <li><i class="fas fa-exclamation-circle text-warning"></i> Şifreniz 5 gün sonra değişmeli.</li>
          <li><i class="fas fa-info-circle text-info"></i> Yeni rapor özelliği eklendi.</li>
        </ul>
      </div>
    </div>
  </div>
  <div class="last-table-box fade-in">
    <div class="soft-chart-title"><i class="fas fa-list"></i> Son 5 İşlem <span style="font-size:.9em;color:#6366f1;font-weight:400;">(Güncel)</span></div>
    <table class="table table-hover modern-table">
      <thead>
        <tr><th></th><th>Kullanıcı</th><th>İşlem</th><th>Saat</th></tr>
      </thead>
      <tbody id="lastTableBody"></tbody>
    </table>
  </div>
  <div id="snackbar">Veriler güncellendi!</div>
</div>
<script>
// Örnek veri
const allData = [
  {user:'admin', date:'2024-06-10 09:12', time:12, type:'Giriş'},
  {user:'ayse', date:'2024-06-10 10:05', time:7, type:'Rapor'},
  {user:'mehmet', date:'2024-06-10 11:20', time:15, type:'Şifre'},
  {user:'fatma', date:'2024-06-10 12:30', time:5, type:'Profil'},
  {user:'admin', date:'2024-06-10 13:00', time:9, type:'Giriş'},
  {user:'ayse', date:'2024-06-10 14:10', time:8, type:'Rapor'},
  {user:'mehmet', date:'2024-06-10 15:20', time:11, type:'Şifre'},
  {user:'fatma', date:'2024-06-10 16:30', time:6, type:'Profil'},
];
// KPI'lar ve özet
function updateKPIs() {
  document.getElementById('kpiVisit').innerText = allData.length;
  document.getElementById('kpiActive').innerText = new Set(allData.map(d=>d.user)).size;
  document.getElementById('kpiAvgTime').innerText = (allData.reduce((a,b)=>a+b.time,0)/allData.length).toFixed(1)+' dk';
  document.getElementById('kpiNewUser').innerText = 2;
  // Mini özet
  document.getElementById('summaryVisit').innerText = allData.length;
  document.getElementById('summaryNew').innerText = 2;
  document.getElementById('summaryHour').innerText = '15:00';
  document.getElementById('summaryAdvice').innerText = 'Yoğun saatlerde destek ekibi hazır bulundurulmalı.';
  // Son 5 işlem tablosu
  let lastRows = allData.slice(-5).reverse().map(d=>`<tr><td>${d.user}</td><td>${d.type}</td><td>${d.date.split(' ')[1]}</td></tr>`).join('');
  document.getElementById('lastTableBody').innerHTML = lastRows;
}
updateKPIs();
// Chart.js shadow plugin
Chart.register({
  id: 'customShadow',
  beforeDraw: chart => {
    const ctx = chart.ctx;
    ctx.save();
    ctx.shadowColor = 'rgba(99,102,241,0.18)';
    ctx.shadowBlur = 16;
    ctx.shadowOffsetX = 0;
    ctx.shadowOffsetY = 6;
  },
  afterDraw: chart => {
    chart.ctx.restore();
  }
});
// Gradient ve shadow efektli trendChart
const trendCtx = document.getElementById('trendChart').getContext('2d');
const trendGradient = trendCtx.createLinearGradient(0, 0, 0, 180);
trendGradient.addColorStop(0, '#6366f1cc');
trendGradient.addColorStop(1, '#43e97b22');
const trendLineGradient = trendCtx.createLinearGradient(0, 0, 400, 0);
trendLineGradient.addColorStop(0, '#6366f1');
trendLineGradient.addColorStop(1, '#43e97b');
const trendChart = new Chart(trendCtx, {
  type: 'line',
  data: {
    labels: ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran'],
    datasets: [{
      label: 'Ziyaret',
      data: [5, 7, 8, 6, 9, 12],
      borderColor: trendLineGradient,
      backgroundColor: trendGradient,
      tension: 0.4,
      fill: true,
      pointRadius: 5,
      pointHoverRadius: 9,
      pointBackgroundColor: '#fff',
      pointBorderColor: trendLineGradient,
      borderWidth: 3,
    }]
  },
  options: {
    responsive:true,
    plugins:{
      legend:{display:false},
      tooltip:{
        backgroundColor:'#fff',
        titleColor:'#6366f1',
        bodyColor:'#23272b',
        borderColor:'#6366f1',
        borderWidth:1,
        padding:12,
        cornerRadius:8,
        displayColors:false
      },
      customShadow: {}
    },
    scales:{
      y:{beginAtZero:true, ticks:{stepSize:2, color:'#6366f1', font:{weight:'bold'}}},
      x:{ticks:{color:'#6366f1', font:{weight:'bold'}}}
    },
    animation: {
      duration: 1200,
      easing: 'easeOutElastic'
    }
  }
});
// Gradient ve shadow efektli userBarChart
const userBarCtx = document.getElementById('userBarChart').getContext('2d');
const barGradient = userBarCtx.createLinearGradient(0, 0, 0, 180);
barGradient.addColorStop(0, '#6366f1');
barGradient.addColorStop(1, '#43e97b');
const userBarChart = new Chart(userBarCtx, {
  type: 'bar',
  data: {
    labels: ['admin','ayse','mehmet','fatma'],
    datasets: [{
      label: 'İşlem',
      data: [3,2,2,2],
      backgroundColor: barGradient,
      borderRadius: 16,
      barPercentage: 0.5,
      categoryPercentage: 0.5,
      borderWidth: 2,
      borderColor: '#fff',
    }]
  },
  options: {
    responsive:true,
    plugins:{
      legend:{display:false},
      tooltip:{
        backgroundColor:'#fff',
        titleColor:'#43e97b',
        bodyColor:'#23272b',
        borderColor:'#43e97b',
        borderWidth:1,
        padding:12,
        cornerRadius:8,
        displayColors:false
      },
      customShadow: {}
    },
    scales:{
      y:{beginAtZero:true, ticks:{stepSize:1, color:'#43e97b', font:{weight:'bold'}}},
      x:{ticks:{color:'#43e97b', font:{weight:'bold'}}}
    },
    animation: {
      duration: 1200,
      easing: 'easeOutBounce'
    }
  }
});
// Pie ve saat bar grafiğine de gradient ve animasyon ekle
const userPieCtx = document.getElementById('userPieChart').getContext('2d');
const userPieChart = new Chart(userPieCtx, {
  type: 'pie',
  data: {
    labels: ['Web','Mobil','API'],
    datasets: [{
      label: 'Kullanıcı',
      data: [4,2,2],
      backgroundColor: [
        'linear-gradient(135deg, #6366f1 0%, #43e97b 100%)',
        '#43e97b',
        '#ffc107'
      ]
    }]
  },
  options: {
    responsive:true,
    plugins:{
      legend:{display:true},
      tooltip:{
        backgroundColor:'#fff',
        titleColor:'#6366f1',
        bodyColor:'#23272b',
        borderColor:'#6366f1',
        borderWidth:1,
        padding:12,
        cornerRadius:8,
        displayColors:true
      },
      customShadow: {}
    },
    animation: {
      duration: 1200,
      easing: 'easeOutCubic'
    }
  }
});
const hourBarCtx = document.getElementById('hourBarChart').getContext('2d');
const hourBarGradient = hourBarCtx.createLinearGradient(0, 0, 0, 180);
hourBarGradient.addColorStop(0, '#6366f1');
hourBarGradient.addColorStop(1, '#43e97b');
const hourBarChart = new Chart(hourBarCtx, {
  type: 'bar',
  data: {
    labels: ['09','10','11','12','13','14','15','16'],
    datasets: [{
      label: 'Yoğunluk',
      data: [2,1,1,1,1,1,1,1],
      backgroundColor: hourBarGradient,
      borderRadius: 12,
      barPercentage: 0.5,
      categoryPercentage: 0.5,
      borderWidth: 2,
      borderColor: '#fff',
    }]
  },
  options: {
    responsive:true,
    plugins:{
      legend:{display:false},
      tooltip:{
        backgroundColor:'#fff',
        titleColor:'#6366f1',
        bodyColor:'#23272b',
        borderColor:'#6366f1',
        borderWidth:1,
        padding:12,
        cornerRadius:8,
        displayColors:false
      },
      customShadow: {}
    },
    scales:{
      y:{beginAtZero:true, ticks:{stepSize:1, color:'#6366f1', font:{weight:'bold'}}},
      x:{ticks:{color:'#6366f1', font:{weight:'bold'}}}
    },
    animation: {
      duration: 1200,
      easing: 'easeOutCubic'
    }
  }
});
function downloadCSV() { alert('CSV indirme örnektir.'); }
function saveNote() {
  document.getElementById('noteSavedMsg').style.display = 'block';
  setTimeout(()=>{
    document.getElementById('noteSavedMsg').style.display = 'none';
    document.getElementById('userNoteInput').value = '';
  }, 1800);
}
// Hoş geldin kutusu ve örnek veri
const userName = 'admin';
document.getElementById('welcomeUser').innerText = userName;
document.getElementById('todayAction').innerText = 5;
// Sayma animasyonu (0'dan başlamasın, örnek veriyle başlasın)
function animateCount(id, target, suffix = '', duration = 1200) {
  let el = document.getElementById(id);
  let start = Math.max(1, Math.floor(target * 0.7)); // 0 yerine örnek veriyle başla
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
// Progress bar animasyonu
function animateBar(id, percent) {
  document.getElementById(id).style.width = percent + '%';
}
// KPI ve analiz kutuları için örnek veri ve animasyonlar
animateCount('kpiVisit', 1200, ''); animateBar('visitBar', 80);
document.getElementById('visitTrend').innerText = '+12%';
document.getElementById('visitTrend').className = 'badge-status badge-high';
animateCount('kpiActive', 87, ''); animateBar('activeBar', 87);
document.getElementById('activeTrend').innerText = '+8%';
document.getElementById('activeTrend').className = 'badge-status badge-high';
animateCount('kpiAvgTime', 14, ' dk'); animateBar('avgTimeBar', 60);
document.getElementById('avgTimeTrend').innerText = '-2%';
document.getElementById('avgTimeTrend').className = 'badge-status badge-low';
animateCount('kpiNewUser', 32, ''); animateBar('newUserBar', 40);
document.getElementById('newUserTrend').innerText = '+5%';
document.getElementById('newUserTrend').className = 'badge-status badge-high';
// Katılım oranı
animateCount('userParticipation', 87, '%'); animateBar('userParticipationBar', 87);
document.getElementById('userParticipationBadge').className = 'badge-status badge-high';
// Ortalama oturum süresi
animateCount('avgSession', 14, ''); animateBar('avgSessionBar', 60);
// Bekleyen onay
animateCount('pendingApprovals', 3, ''); document.getElementById('pendingBadge').className = 'badge-status badge-critical';
// Kullanıcı memnuniyet skoru
animateCount('satisfactionScore', 4.6, ''); animateBar('satisfactionBar', 92);
// En aktif kullanıcılar
const activeUsers = [
  {name:'admin', count:12, avatar:'A'},
  {name:'ayse', count:10, avatar:'A'},
  {name:'mehmet', count:8, avatar:'M'},
  {name:'fatma', count:7, avatar:'F'}
];
document.getElementById('activeUsersList').innerHTML = activeUsers.map(u=>`<li class="active-user-row"><span class='avatar-circle'>${u.avatar}</span><b>${u.name}</b> <span style='color:#43e97b;font-weight:600;'>${u.count} işlem</span></li>`).join('');
// Destek talebi yoğunluğu
const supportDemand = 'En yoğun saat: 14:00-15:00, Toplam: 6 talep';
document.getElementById('supportDemand').innerText = supportDemand;
// Sistem uptime
animateCount('systemUptime', 99.7, '%'); animateBar('uptimeBar', 99.7);
// Bildirim okunma oranı
animateCount('notificationRead', 82, '%'); animateBar('notificationBar', 82);
// Son 5 işlem tablosu highlight
const lastRows = [
  {user:'admin', type:'Giriş', time:'09:12', avatar:'A'},
  {user:'ayse', type:'Rapor', time:'10:05', avatar:'A'},
  {user:'mehmet', type:'Şifre', time:'11:20', avatar:'M'},
  {user:'fatma', type:'Profil', time:'12:30', avatar:'F'},
  {user:'admin', type:'Giriş', time:'13:00', avatar:'A'}
];
document.getElementById('lastTableBody').innerHTML = lastRows.map(d=>`
  <tr>
    <td><span class='avatar-circle'>${d.avatar}</span></td>
    <td>${d.user}</td>
    <td>${d.type}</td>
    <td>${d.time}</td>
  </tr>
`).join('');
// Mini özet kutusu
const allData = [
  {user:'admin', date:'2024-06-10 09:12', time:12, type:'Giriş'},
  {user:'ayse', date:'2024-06-10 10:05', time:7, type:'Rapor'},
  {user:'mehmet', date:'2024-06-10 11:20', time:15, type:'Şifre'},
  {user:'fatma', date:'2024-06-10 12:30', time:5, type:'Profil'},
  {user:'admin', date:'2024-06-10 13:00', time:9, type:'Giriş'},
  {user:'ayse', date:'2024-06-10 14:10', time:8, type:'Rapor'},
  {user:'mehmet', date:'2024-06-10 15:20', time:11, type:'Şifre'},
  {user:'fatma', date:'2024-06-10 16:30', time:6, type:'Profil'},
];
document.getElementById('summaryVisit').innerText = allData.length;
document.getElementById('summaryNew').innerText = 2;
document.getElementById('summaryHour').innerText = '15:00';
document.getElementById('summaryAdvice').innerText = 'Yoğun saatlerde destek ekibi hazır bulundurulmalı.';
// SLA barları
const slaData = [
  {name: 'Yanıtlama', percent: 98, color: '#43e97b'},
  {name: 'Çözüm', percent: 92, color: '#6366f1'},
  {name: 'Geri Bildirim', percent: 85, color: '#ffc107'},
  {name: 'Takip', percent: 70, color: '#dc3545'},
  {name: 'Ekstra SLA', percent: 60, color: '#17a2b8'},
];
document.getElementById('slaBarList').innerHTML = slaData.map(sla => `
  <div class="sla-bar-row">
    <span class="sla-bar-label">${sla.name}</span>
    <div class="sla-bar-outer"><div class="sla-bar-inner" style="width:0;background:${sla.color};"></div></div>
    <span class="sla-badge" style="background:${sla.color};">${sla.percent}%</span>
  </div>
`).join('');
setTimeout(()=>{
  document.querySelectorAll('.sla-bar-inner').forEach((el,i)=>{
    el.style.width = slaData[i].percent+'%';
  });
}, 200);
// Kullanıcı aktivite zaman çizelgesi
const activityData = [
  {label:'09:00', percent: 30, color:'#6366f1'},
  {label:'10:00', percent: 60, color:'#43e97b'},
  {label:'11:00', percent: 80, color:'#ffc107'},
  {label:'12:00', percent: 50, color:'#17a2b8'},
  {label:'13:00', percent: 90, color:'#43e97b'},
  {label:'14:00', percent: 100, color:'#dc3545'},
  {label:'15:00', percent: 70, color:'#6366f1'},
  {label:'16:00', percent: 40, color:'#43e97b'}
];
document.getElementById('activityBarList').innerHTML = activityData.map(a => `
  <div class="activity-bar-row">
    <span class="activity-bar-label">${a.label}</span>
    <div class="activity-bar-outer"><div class="activity-bar-inner" style="width:0;background:${a.color};"></div></div>
    <span class="badge-status" style="background:${a.color};color:#fff;">${a.percent}%</span>
  </div>
`).join('');
setTimeout(()=>{
  document.querySelectorAll('.activity-bar-inner').forEach((el,i)=>{
    el.style.width = activityData[i].percent+'%';
  });
}, 200);
// Filtreleme örneği
const allRows = [
  {user:'admin', type:'Giriş', time:'09:12'},
  {user:'ayse', type:'Rapor', time:'10:05'},
  {user:'mehmet', type:'Şifre', time:'11:20'},
  {user:'fatma', type:'Profil', time:'12:30'},
  {user:'admin', type:'Giriş', time:'13:00'},
  {user:'ayse', type:'Profil', time:'13:40'},
  {user:'mehmet', type:'Rapor', time:'14:10'},
  {user:'fatma', type:'Giriş', time:'15:00'}
];
function renderTable(filtered) {
  document.getElementById('lastTableBody').innerHTML = filtered.map(d=>`<tr><td>${d.user}</td><td>${d.type}</td><td>${d.time}</td></tr>`).join('');
}
renderTable(allRows.slice(0,5));
function filterTable() {
  const user = document.getElementById('filterUser').value;
  const type = document.getElementById('filterType').value;
  const start = document.getElementById('filterStartDate').value;
  const end = document.getElementById('filterEndDate').value;
  let filtered = allRows;
  if(user) filtered = filtered.filter(r=>r.user===user);
  if(type) filtered = filtered.filter(r=>r.type===type);
  if(start) filtered = filtered.filter(r=>r.date.split(' ')[0] >= start);
  if(end) filtered = filtered.filter(r=>r.date.split(' ')[0] <= end);
  renderTable(filtered.slice(0,5));
}
document.getElementById('filterUser').addEventListener('change', filterTable);
document.getElementById('filterType').addEventListener('change', filterTable);
document.getElementById('filterStartDate').addEventListener('change', filterTable);
document.getElementById('filterEndDate').addEventListener('change', filterTable);
document.getElementById('clearFiltersBtn').addEventListener('click', ()=>{
  document.getElementById('filterUser').value = '';
  document.getElementById('filterType').value = '';
  document.getElementById('filterStartDate').value = '';
  document.getElementById('filterEndDate').value = '';
  renderTable(allRows.slice(0,5));
});
document.addEventListener('DOMContentLoaded', function() {
  flatpickr("#filterDateRange", {
    mode: "range",
    dateFormat: "Y-m-d",
    minDate: "2015-01-01",
    maxDate: new Date().toISOString().split('T')[0],
    allowInput: false
  });
});
</script>
@endsection