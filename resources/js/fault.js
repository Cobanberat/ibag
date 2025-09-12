// Form submit handling
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('faultForm');
  
  if(form) {
    form.addEventListener('submit', function(e) {
      // Show loading state
      const submitBtn = form.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Gönderiliyor...';
      submitBtn.disabled = true;
      
      // Let the form submit naturally to the server
      // The success/error message will be handled by the server response
    });
  }
});