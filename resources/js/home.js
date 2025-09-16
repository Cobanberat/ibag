// Admin Home Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Counter Animation
    animateCounters();
    
    // Initialize Tooltips
    initializeTooltips();
    
    // Auto Refresh Stats
    setInterval(refreshStats, 30000); // 30 saniyede bir güncelle
    
    // Add hover effects
    addHoverEffects();
    
});

// Counter Animation Function
function animateCounters() {
    const counters = document.querySelectorAll('.counter');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-count'));
        const duration = 2000; // 2 saniye
        const increment = target / (duration / 16); // 60 FPS
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            counter.textContent = Math.floor(current);
        }, 16);
    });
}

// Initialize Bootstrap Tooltips
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Refresh Statistics
function refreshStats() {
    fetch('/admin/stats')
        .then(response => response.json())
        .then(data => {
            updateStats(data);
        })
        .catch(error => {
            console.error('Stats güncellenirken hata:', error);
        });
}

// Update Statistics Display
function updateStats(stats) {
    // KPI kartlarını güncelle
    const totalEquipment = document.querySelector('[data-count]');
    if (totalEquipment) {
        totalEquipment.setAttribute('data-count', stats.total_equipment);
    }
    
    // Diğer istatistikleri güncelle
    updateCounter('total_equipment', stats.total_equipment);
    updateCounter('active_users', stats.active_users);
    updateCounter('pending_faults', stats.pending_faults);
    updateCounter('critical_stocks', stats.critical_stocks);
    
    // Bugünkü artışları güncelle
    updateTodayStats(stats);
}

// Update Individual Counter
function updateCounter(type, value) {
    const elements = document.querySelectorAll(`[data-type="${type}"]`);
    elements.forEach(element => {
        element.setAttribute('data-count', value);
        animateCounter(element, value);
    });
}

// Animate Single Counter
function animateCounter(element, target) {
    const current = parseInt(element.textContent) || 0;
    const difference = target - current;
    const duration = 1000;
    const increment = difference / (duration / 16);
    let currentValue = current;
    
    const timer = setInterval(() => {
        currentValue += increment;
        if ((increment > 0 && currentValue >= target) || (increment < 0 && currentValue <= target)) {
            currentValue = target;
            clearInterval(timer);
        }
        element.textContent = Math.floor(currentValue);
    }, 16);
}

// Update Today's Statistics
function updateTodayStats(stats) {
    // Bugünkü artışları güncelle
    const todayEquipment = document.querySelector('.badge.text-primary');
    if (todayEquipment && stats.today_equipment !== undefined) {
        todayEquipment.textContent = `+${stats.today_equipment} bugün`;
    }
    
    const todayUsers = document.querySelector('.badge.text-primary');
    if (todayUsers && stats.today_users !== undefined) {
        // İkinci badge'i bul (kullanıcı kartındaki)
        const badges = document.querySelectorAll('.badge.text-primary');
        if (badges.length > 1) {
            badges[1].textContent = `+${stats.today_users} yeni`;
        }
    }
}

// Add Hover Effects
function addHoverEffects() {
    // KPI kartlarına hover efekti
    const kpiCards = document.querySelectorAll('.kpi-card');
    kpiCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Quick action butonlarına hover efekti
    const quickActions = document.querySelectorAll('.quick-action-btn');
    quickActions.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 10px 25px rgba(0,0,0,0.15)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.1)';
        });
    });
}

// Show Snackbar Notification
function showSnackbar(message, type = 'info') {
    const snackbar = document.getElementById('snackbar');
    if (snackbar) {
        snackbar.textContent = message;
        snackbar.className = `position-fixed bottom-0 end-0 m-4 px-4 py-2 rounded shadow text-white`;
        
        // Type'a göre renk ayarla
        switch(type) {
            case 'success':
                snackbar.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
                break;
            case 'error':
                snackbar.style.background = 'linear-gradient(135deg, #dc3545 0%, #e74c3c 100%)';
                break;
            case 'warning':
                snackbar.style.background = 'linear-gradient(135deg, #ffc107 0%, #fd7e14 100%)';
                break;
            default:
                snackbar.style.background = 'linear-gradient(135deg, #495057 0%, #343a40 100%)';
        }
        
        snackbar.style.display = 'block';
        snackbar.style.zIndex = '9999';
        
        // 3 saniye sonra gizle
        setTimeout(() => {
            snackbar.style.display = 'none';
        }, 3000);
    }
}

// Table Row Click Handler
function handleTableRowClick() {
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('click', function() {
            // Satıra tıklandığında detay modalı açılabilir
            console.log('Row clicked:', this);
        });
    });
}

// Initialize Table Interactions
function initializeTableInteractions() {
    handleTableRowClick();
}

// Refresh Page Data
function refreshPageData() {
    // Sayfayı yenile
    window.location.reload();
}


// Export Functions to Global Scope
window.showSnackbar = showSnackbar;
window.refreshPageData = refreshPageData;
window.refreshStats = refreshStats;

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeTableInteractions();
});

// Error Handling
window.addEventListener('error', function(e) {
    console.error('JavaScript Error:', e.error);
    showSnackbar('Bir hata oluştu. Lütfen sayfayı yenileyin.', 'error');
});

// Network Error Handling
window.addEventListener('unhandledrejection', function(e) {
    console.error('Promise Rejection:', e.reason);
    showSnackbar('Bağlantı hatası. Lütfen internet bağlantınızı kontrol edin.', 'error');
});