function animateCounters() {
    document.querySelectorAll('.counter').forEach(function(el) {
        let count = +el.getAttribute('data-count');
        let i = 0;
        let step = Math.ceil(count / 40);
        let interval = setInterval(function() {
            i += step;
            if(i >= count) { el.textContent = count; clearInterval(interval); }
            else { el.textContent = i; }
        }, 20);
    });
}



document.addEventListener('DOMContentLoaded', function() {
    animateCounters();
});

