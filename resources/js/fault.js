// Demo: Form submit sonrası başarı mesajı göster
const form = document.getElementById('faultForm');
if(form) {
  form.onsubmit = function(e) {
    e.preventDefault();
    document.getElementById('faultSuccessAlert').classList.remove('d-none');
    setTimeout(()=>{
      document.getElementById('faultSuccessAlert').classList.add('d-none');
      form.reset();
    }, 2500);
  };
}