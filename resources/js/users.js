document.addEventListener('DOMContentLoaded', function() {
    // filterByKpi fonksiyonu: karta göre modal aç
    window.filterByKpi = function(type) {
      let title = '', html = '';
      if(type==='all') {title='Tüm Kullanıcılar'; html='Sistemdeki tüm kullanıcılar listeleniyor.';}
      if(type==='admin') {title='Adminler'; html='Sistemdeki tüm adminler listeleniyor.';}
      if(type==='active') {title='Aktif Kullanıcılar'; html='Şu anda aktif olan kullanıcılar.';}
      if(type==='new') {title='Bu Ay Eklenenler'; html='Bu ay eklenen yeni kullanıcılar.';}
      Swal.fire({title, html, icon:'info', confirmButtonText:'Kapat'});
    };
    // DataTable başlat ve arama inputunu bağla
    var userTable = new DataTable('#userTable', {
      paging: true,
      searching: true,
      ordering: true,
      info: false,
      responsive: false,
      pageLength: 10,
      lengthMenu: [10, 20, 50, 100],
      lengthChange: false,
      language: {},
      dom: 'lrtp',
      pagingType: 'numbers',
      drawCallback: function() {
        // Pagination'ı kesin sağa yasla
        var pag = document.querySelector('.dataTables_paginate');
        if(pag) {
          pag.classList.add('d-flex','justify-content-end','w-100');
          pag.style.marginTop = '18px';
          pag.style.justifyContent = 'flex-end';
          pag.style.float = 'right';
          pag.style.textAlign = 'right';
        }
      }
    });
    userTable.draw();
    // Üstteki arama inputunu DataTables aramasına bağla
    var userSearch = document.getElementById('userSearch');
    if(userSearch) {
      userSearch.placeholder = 'Kullanıcı ara';
      userSearch.addEventListener('input', function() {
        userTable.search(this.value).draw();
      });
    }
    // Tümünü seç checkbox
    var selectAllUserRows = document.getElementById('selectAllUserRows');
    if(selectAllUserRows) {
      selectAllUserRows.addEventListener('change', function() {
        document.querySelectorAll('#userTable tbody .user-row-check').forEach(cb=>{cb.checked = selectAllUserRows.checked;});
      });
    }
    // flatpickr güvenli kontrol
    var userFilterDateEl = document.getElementById('userFilterDate');
    if(userFilterDateEl && typeof flatpickr !== 'undefined') {
      flatpickr('#userFilterDate', {mode:'range', dateFormat:'Y-m-d', locale:{rangeSeparator:' - '}});
    }
    
    // admin.js kaynaklı hataları önlemek için örnek koruma (örnek id: someId)
    var someIdEl = document.getElementById('someId');
    if(someIdEl) {
      // someIdEl.classList.add('foo');
      // veya someIdEl.length
    }
    // Rol ve durum filtreleriyle tabloyu filtrele
    var userFilterRole = document.getElementById('userFilterRole');
    var userFilterStatus = document.getElementById('userFilterStatus');
    function filterUserTable() {
      var role = userFilterRole ? userFilterRole.value : '';
      var status = userFilterStatus ? userFilterStatus.value : '';
      userTable.columns(5).search(role).columns(6).search(status).draw();
    }
    if(userFilterRole) userFilterRole.addEventListener('change', filterUserTable);
    if(userFilterStatus) userFilterStatus.addEventListener('change', filterUserTable);
    });