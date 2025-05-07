<?php
session_start();
include 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

$mostrar_busqueda = true;
include 'includes/header.php';

// Datos de las películas (¡hardcoded! - ¡AJUSTA ESTO!)
$movies_by_genre = [
    'Aventura' => [
        ['title' => 'El viaje de chihiro', 'thumbnail' => 'thumbnails/chihiro.jpg', 'clip_url' => 'assets/clips/CHIHIRO.mp4'],
        ['title' => 'Avatar the way of water', 'thumbnail' => 'thumbnails/avatar.webp', 'clip_url' => 'assets/clips/avatar.mp4'],
        ['title' => 'Thor love and thunder', 'thumbnail' => 'thumbnails/thor.jpg', 'clip_url' => 'assets/clips/Thor.mp4'],
        ['title' => 'Star Wars la venganza de los sith', 'thumbnail' => 'thumbnails/StarwarsIII.webp', 'clip_url' => 'assets/clips/stawars.mp4'],
        ['title' => 'Jurassic Park', 'thumbnail' => 'thumbnails/jurassic.webp', 'clip_url' => 'assets/clips/jurassic.mp4'],
    ],
    'Horror' => [
        ['title' => 'El conjuro', 'thumbnail' => 'thumbnails/conjuero.webp', 'clip_url' => 'assets/clips/conjuro.mp4'],
        ['title' => 'Smile (2022)', 'thumbnail' => 'thumbnails/Smile (2022).jpg', 'clip_url' => 'assets/clips/smille.mp4'],
        ['title' => 'El telefono negro', 'thumbnail' => 'thumbnails/black.jpg', 'clip_url' => 'assets/clips/black.mp4'],
        ['title' => 'El exorcista', 'thumbnail' => 'thumbnails/exorcista.jpg', 'clip_url' => 'assets/clips/exorcista.mp4'],
        ['title' => 'Halloween (1978)', 'thumbnail' => 'thumbnails/Hallowen.jpg', 'clip_url' => 'assets/clips/hallowen.mp4'],
        // ... más películas de terror
    ],
    'Acción' => [
        ['title' => 'Jhon Wick', 'thumbnail' => 'thumbnails/J1.webp', 'clip_url' => 'assets/clips/J1.mp4'],
        ['title' => 'Jhon Wick 4', 'thumbnail' => 'thumbnails/J4.jpg', 'clip_url' => 'assets/clips/J4.mp4'],
        ['title' => 'Mad Max fury road', 'thumbnail' => 'thumbnails/mad.jpg', 'clip_url' => 'assets/clips/mad.mp4'],
        ['title' => '007 Skyfall', 'thumbnail' => 'thumbnails/007.webp', 'clip_url' => 'assets/clips/007.mp4'],
        ['title' => 'Mision imposible repercusion', 'thumbnail' => 'thumbnails/mision.jpg', 'clip_url' => 'assets/clips/mision.mp4'],
        // ... más películas de terror
    ],
    'Familiar' => [
        ['title' => 'Patos', 'thumbnail' => 'thumbnails/patos.jpg', 'clip_url' => 'assets/clips/patos.mp4'],
        ['title' => 'Bob esponja', 'thumbnail' => 'thumbnails/bobesponja.webp', 'clip_url' => 'assets/clips/bob.mp4'],
        ['title' => 'La familia Mitchell...', 'thumbnail' => 'thumbnails/maquinas.jpg', 'clip_url' => 'assets/clips/La familia.mp4'],
        ['title' => 'La vida secreta de tus mascotas', 'thumbnail' => 'thumbnails/pet.webp', 'clip_url' => 'assets/clips/mascotas.mp4'],
        ['title' => 'La vida secreta de tus mascotas 2', 'thumbnail' => 'thumbnails/pet2.jpg', 'clip_url' => 'assets/clips/mascotas 2.mp4'],
        // ... más películas de terror
    ],
    'Fantasia' => [
        ['title' => 'Animales fantasticos 3', 'thumbnail' => 'thumbnails/AnimalesS.webp', 'clip_url' => 'assets/clips/animales2.mp4'],
        ['title' => 'Animales fantasticos', 'thumbnail' => 'thumbnails/Animales.jpg', 'clip_url' => 'assets/clips/animales.mp4'],
        ['title' => 'Harry Potter y la piedra filosofal', 'thumbnail' => 'thumbnails/HP piedra filosofal.webp', 'clip_url' => 'assets/clips/HPfilosofal.mp4'],
        ['title' => 'Harry Potter y la camara secreta', 'thumbnail' => 'thumbnails/HP y la camara secreta.webp', 'clip_url' => 'assets/clips/HPsecreta.mp4'],
        ['title' => 'Harry Potter y la orden del fenix', 'thumbnail' => 'thumbnails/HP y la orden del fenix.webp', 'clip_url' => 'assets/clips/HPfenix.mp4'],
        // ... más películas de terror
    ],
    // ... más géneros
];

// Función para obtener todas las películas sin importar el género
function getAllMovies($movies_by_genre) {
    $all_movies = [];
    foreach ($movies_by_genre as $genre => $movies) {
        $all_movies = array_merge($all_movies, $movies);
    }
    return $all_movies;
}

$all_movies = getAllMovies($movies_by_genre);
?>

<div class="hero">
    <h1>Bienvenido a Tecflix</h1>
    <p>Disfruta de las mejores películas en línea</p>
    <img src="assets/images/bienvenida.jpg" alt="">
</div>

<div class="carousel-container">
    <h2>¿Qué quieres ver hoy?</h2>
    <div class="carousel">
        <?php foreach ($all_movies as $movie): ?>
            <div class="carousel-item" data-clip-url="<?php echo htmlspecialchars($movie['clip_url']); ?>">
                <img src="assets/images/<?php echo htmlspecialchars($movie['thumbnail']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="carousel-arrow left">&#10094;</div>
    <div class="carousel-arrow right">&#10095;</div>
</div>

<div class="categories">
    <?php foreach ($movies_by_genre as $genre => $movies): ?>
        <section class="movie-section">
            <h2><?php echo ucfirst($genre); ?></h2>
            <div class="movie-row">
                <?php foreach ($movies as $movie): ?>
                    <div class="movie-card" data-clip-url="<?php echo htmlspecialchars($movie['clip_url']); ?>">
                        <img src="assets/images/<?php echo htmlspecialchars($movie['thumbnail']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                        <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; ?>
</div>

<div id="clip-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <video id="movie-clip" width="100%" height="500" controls></video>
    </div>
</div>

<style>
    /* Estilos del carrusel */
    .carousel-container {
        margin: 2rem 0;
        padding: 0 2rem;
        position: relative; /* Asegura que las flechas se posicionen relativamente a este contenedor */
    }

    .carousel-container h2 {
        margin-bottom: 1rem;
    }

    .carousel {
        display: flex;
        overflow-x: auto;
        gap: 1rem;
        padding: 1rem 0;
        scroll-behavior: smooth; /* Para el scroll suave */
    }

    .carousel-item {
        min-width: 200px;
        transition: transform 0.3s;
        cursor: pointer;
        text-align: center;
    }

    .carousel-item:hover {
        transform: scale(1.05);
    }

    .carousel-item img {
        width: 100%;
        border-radius: 4px;
        margin-bottom: 0.5rem;
    }

    .carousel-item h3 {
        font-size: 1rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    /* Estilos para las flechas */
    .carousel-arrow {
        position: absolute; /* Posicionamiento absoluto dentro de .carousel-container */
        top: 50%;
        transform: translateY(-50%);
        font-size: 2rem;
        color: white;
        cursor: pointer;
        user-select: none; /* Evita la selección de texto al hacer clic en las flechas */
        background-color: rgba(0, 0, 0, 0.7); /* Fondo semi-transparente para las flechas */
        border-radius: 50%; /* Hace las flechas circulares */
        width: 2.5rem; /* Ancho de las flechas */
        height: 2.5rem; /* Alto de las flechas */
        display: flex;
        align-items: center; /* Centra el icono verticalmente */
        justify-content: center; /* Centra el icono horizontalmente */
        opacity: 0.7;
        transition: opacity 0.3s;
        z-index: 10; /* Asegura que las flechas estén por encima del carrusel */
    }

    .carousel-arrow:hover {
        opacity: 1;
    }

    .carousel-arrow.left {
        left: 0.5rem;
    }

    .carousel-arrow.right {
        right: 0.5rem;
    }

    /* (El resto del CSS se mantiene igual) */
    .hero {
        height: 70vh;
        background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('../images/hero-bg.jpg');
        background-size: cover;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding-top: 80px;
    }

    .hero h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .movie-section {
        margin: 2rem 0;
        padding: 0 2rem;
    }

    .movie-section h2 {
        margin-bottom: 1rem;
    }

    .movie-row {
        display: flex;
        overflow-x: auto;
        gap: 1rem;
        padding: 1rem 0;
    }

    .movie-card {
        min-width: 200px;
        transition: transform 0.3s;
        cursor: pointer;
    }

    .movie-card:hover {
        transform: scale(1.05);
    }

    .movie-card img {
        width: 100%;
        border-radius: 4px;
    }

    /* Modal del tráiler */
    .modal {
        display: none;
        position: fixed;
        z-index: 101;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.9);
    }

    .modal-content {
        position: relative;
        margin: auto;
        padding: 20px;
        width: 80%;
        max-width: 800px;
        top: 50%;
        transform: translateY(-50%);
    }

    .close {
        color: white;
        position: absolute;
        top: -40px;
        right: 0;
        font-size: 2rem;
        font-weight: bold;
        cursor: pointer;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal del clip
        const modal = document.getElementById('clip-modal');
        const movieClip = document.getElementById('movie-clip');
        const closeBtn = document.querySelector('.close');

        // Función para manejar el clic en las películas
        function handleMovieClick(event) {
            const clipUrl = this.getAttribute('data-clip-url');
            if (clipUrl) {
                movieClip.src = clipUrl;
                modal.style.display = 'block';
                movieClip.load();
                movieClip.play();
            }
        }

        // Mostrar clip al hacer clic en las películas del carrusel
        const carouselItems = document.querySelectorAll('.carousel-item');
        carouselItems.forEach(item => {
            item.addEventListener('click', handleMovieClick);
        });

        // Mostrar clip al hacer clic en las películas de las categorías
        const movieCards = document.querySelectorAll('.movie-card');
        movieCards.forEach(card => {
            card.addEventListener('click', handleMovieClick);
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

        // Desplazamiento del carrusel con flechas
        const carousel = document.querySelector('.carousel');
        const arrowLeft = document.querySelector('.carousel-arrow.left');
        const arrowRight = document.querySelector('.carousel-arrow.right');
        const itemWidth = 210; // Ancho aproximado de cada elemento (ajusta si es necesario)

        arrowLeft.addEventListener('click', () => {
            carousel.scrollLeft -= itemWidth * 2; // Desplaza dos elementos a la vez (ajusta según necesites)
        });

        arrowRight.addEventListener('click', () => {
            carousel.scrollLeft += itemWidth * 2; // Desplaza dos elementos a la vez
        });
    });
</script>