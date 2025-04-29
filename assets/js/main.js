document.addEventListener('DOMContentLoaded', function() {
    // Modal del tráiler
    const modal = document.getElementById('trailer-modal');
    const modalIframe = document.getElementById('trailer-iframe');
    const closeBtn = document.querySelector('.close');
    
    // Mostrar tráiler al pasar el cursor
    const movieCards = document.querySelectorAll('.movie-card');
    movieCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            const trailerUrl = this.getAttribute('data-trailer');
            if (trailerUrl) {
                // Extraer el ID del video de YouTube (si es un enlace de YouTube)
                let videoId = '';
                if (trailerUrl.includes('youtube.com') || trailerUrl.includes('youtu.be')) {
                    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
                    const match = trailerUrl.match(regExp);
                    videoId = (match && match[2].length === 11) ? match[2] : null;
                    
                    if (videoId) {
                        modalIframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&mute=1`;
                        modal.style.display = 'block';
                    }
                }
            }
        });
        
        card.addEventListener('mouseleave', function() {
            modalIframe.src = '';
            modal.style.display = 'none';
        });
    });
    
    // Cerrar modal
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
        modalIframe.src = '';
    });
    
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
            modalIframe.src = '';
        }
    });
});