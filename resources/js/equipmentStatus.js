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

// --- Pagination Başlangıç ---
document.addEventListener('DOMContentLoaded', function() {
  const cards = Array.from(document.querySelectorAll('.equipment-card'));
  const cardsPerPage = 3;
  const pagination = document.getElementById('equipmentPagination');
  if (!cards.length || !pagination) return;

  function showPage(page) {
    const start = (page - 1) * cardsPerPage;
    const end = start + cardsPerPage;
    cards.forEach((card, i) => {
      card.parentElement.style.display = (i >= start && i < end) ? '' : 'none';
    });
  }

  function renderPagination() {
    const pageCount = Math.ceil(cards.length / cardsPerPage);
    let html = '';
    for (let i = 1; i <= pageCount; i++) {
      html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
    }
    pagination.innerHTML = html;
    setActive(1);
    pagination.querySelectorAll('a.page-link').forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const page = parseInt(this.getAttribute('data-page'));
        showPage(page);
        setActive(page);
      });
    });
  }

  function setActive(page) {
    pagination.querySelectorAll('li').forEach((li, idx) => {
      li.classList.toggle('active', idx === page - 1);
    });
  }

  showPage(1);
  renderPagination();
});
// --- Pagination Sonu ---
