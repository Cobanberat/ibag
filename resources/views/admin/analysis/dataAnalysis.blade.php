@extends('layouts.admin')
@section('content')
@vite(['resources/css/dataAnalysis.css'])
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
    <!-- <input type="text" class="form-control" id="filterDateRange" placeholder="Tarih Aralığı Seçin" readonly style="max-width:220px;"> -->
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
      <tbody id="lastTableBody">
        <tr>
          <td><span class='avatar-circle'>A</span></td>
          <td>admin</td>
          <td>Giriş</td>
          <td>09:12</td>
        </tr>
        <tr>
          <td><span class='avatar-circle'>A</span></td>
          <td>ayse</td>
          <td>Rapor</td>
          <td>10:05</td>
        </tr>
        <tr>
          <td><span class='avatar-circle'>M</span></td>
          <td>mehmet</td>
          <td>Şifre</td>
          <td>11:20</td>
        </tr>
        <tr>
          <td><span class='avatar-circle'>F</span></td>
          <td>fatma</td>
          <td>Profil</td>
          <td>12:30</td>
        </tr>
        <tr>
          <td><span class='avatar-circle'>A</span></td>
          <td>admin</td>
          <td>Giriş</td>
          <td>13:00</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div id="snackbar">Veriler güncellendi!</div>
</div>
@vite(['resources/js/dataAnalysis.js'])
@endsection