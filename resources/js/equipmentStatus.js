  // Fade-in animasyonu
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.equipment-card').forEach(function(card, i) {
      setTimeout(function() {
        card.classList.add('fade-in');
      }, 100 + i * 120);
    });
  });
  // Favori butonu animasyonu
  document.querySelectorAll('.favorite-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      btn.classList.toggle('favorited');
      var icon = btn.querySelector('i');
      icon.classList.toggle('far');
      icon.classList.toggle('fas');
    });
  });
  // Detay ve servis talep butonları (önceki gibi)
  document.querySelectorAll('.detay-gor-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var eid = btn.getAttribute('data-eid');
      var modalId = '';
      if(eid === '1') modalId = '#detayModal1';
      if(eid === '3') modalId = '#detayModal3';
      if(modalId) {
        var modal = new bootstrap.Modal(document.querySelector(modalId));
        modal.show();
      }
    });
  });
  document.querySelectorAll('.servis-talep-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var modal = new bootstrap.Modal(document.getElementById('servisTalepModal'));
      modal.show();
    });
  });
  // Bakım Gerektiren Ekipmanlar Modalı Aç
  var bakimBtn = document.getElementById('bakimEkipmanModalBtn');
  if(bakimBtn) {
    bakimBtn.addEventListener('click', function() {
      var modal = new bootstrap.Modal(document.getElementById('bakimEkipmanModal'));
      modal.show();
    });
  }
