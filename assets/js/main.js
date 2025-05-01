document.addEventListener('DOMContentLoaded', function() {
    // Modal del clip
    const modal = document.getElementById('clip-modal');
    const movieClip = document.getElementById('movie-clip');
    const closeBtn = document.querySelector('.close');

    // Mostrar clip al hacer clic
    const movieCards = document.querySelectorAll('.movie-card');
    movieCards.forEach(card => {
        card.addEventListener('click', function() {
            const clipUrl = this.getAttribute('data-clip-url');
            if (clipUrl) {
                movieClip.src = clipUrl;
                modal.style.display = 'block';
                movieClip.load();
                movieClip.play();
            }
        });
    });

    // Cerrar modal
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
        movieClip.pause();
        movieClip.currentTime = 0;
        movieClip.src = '';
    });

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
            movieClip.pause();
            movieClip.currentTime = 0;
            movieClip.src = '';
        }
    });
});