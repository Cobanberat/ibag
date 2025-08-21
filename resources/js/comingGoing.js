// Coming Going JavaScript

document.addEventListener('DOMContentLoaded', function() {
    console.log('Coming Going page loaded');
    
    // Initialize any necessary functionality
    initializeComingGoing();
});

function initializeComingGoing() {
    // Add event listeners for coming/going functionality
    const comingGoingForm = document.getElementById('comingGoingForm');
    if (comingGoingForm) {
        comingGoingForm.addEventListener('submit', handleComingGoingSubmit);
    }
    
    // Initialize date pickers if they exist
    initializeDatePickers();
    
    // Initialize any modals or dropdowns
    initializeModals();
}

function handleComingGoingSubmit(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    
    // Send AJAX request to save coming/going data
    fetch('/admin/coming-going', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Coming/Going record saved successfully!', 'success');
            // Refresh the page or update the table
            location.reload();
        } else {
            showNotification('Error saving record: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while saving the record.', 'error');
    });
}

function initializeDatePickers() {
    // Initialize any date picker components
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        // Add any custom date picker functionality here
        input.addEventListener('change', function() {
            console.log('Date selected:', this.value);
        });
    });
}

function initializeModals() {
    // Initialize any modal components
    const modalTriggers = document.querySelectorAll('[data-modal]');
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal');
            openModal(modalId);
        });
    });
    
    // Close modal functionality
    const modalCloses = document.querySelectorAll('.modal-close, .modal-overlay');
    modalCloses.forEach(close => {
        close.addEventListener('click', function() {
            closeModal();
        });
    });
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal() {
    const modals = document.querySelectorAll('.modal.active');
    modals.forEach(modal => {
        modal.classList.remove('active');
    });
    document.body.style.overflow = '';
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Hide and remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Export functions for use in other modules if needed
window.ComingGoing = {
    initialize: initializeComingGoing,
    handleSubmit: handleComingGoingSubmit,
    openModal: openModal,
    closeModal: closeModal,
    showNotification: showNotification
};
