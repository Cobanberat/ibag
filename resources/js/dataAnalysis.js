

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
  // Mini özet kutusu
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
    // Flatpickr kaldırıldı
  });